<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Dentist;
use App\Models\Role;
use App\Models\User;
use App\Support\Uploads;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            $query = User::with('roles')->latest();
            $total = User::count();
            $users = $query->skip($request->get('start', 0))->take($request->get('length', 10))->get();

            return response()->json([
                'draw' => (int) $request->get('draw'),
                'recordsTotal' => $total,
                'recordsFiltered' => $total,
                'data' => $users->map(fn ($u) => [
                    'id' => $u->id, 'name' => $u->name, 'email' => $u->email, 'photo_url' => $u->photoUrl(),
                    'roles' => $u->roles->pluck('name')->join(', '),
                    'is_active' => $u->is_active,
                ]),
            ]);
        }

        $roles = Role::all();

        return view('users.index', compact('roles'));
    }

    public function create(): RedirectResponse
    {
        return redirect()->route('users.index');
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'password' => ['required', 'string', 'min:6'],
            'phone' => ['nullable', 'string', 'max:30'],
            'role_id' => ['required', 'exists:roles,id'],
            'photo' => ['nullable', 'image', 'max:2048'],
        ]);

        $user = User::create([
            'name' => $data['name'], 'email' => $data['email'],
            'password' => Hash::make($data['password']), 'phone' => $data['phone'] ?? null,
            'photo' => $request->hasFile('photo') ? Uploads::store($request->file('photo'), 'avatars') : null,
            'is_active' => true,
        ]);
        $user->roles()->sync([$data['role_id']]);
        $this->ensureDentistProfile($user, $data['role_id']);

        ActivityLog::log('created', "User {$user->name} created", $user);

        return response()->json(['message' => 'User created successfully.'], 201);
    }

    public function edit(User $user): JsonResponse
    {
        return response()->json([
            'id' => $user->id, 'name' => $user->name, 'email' => $user->email,
            'phone' => $user->phone, 'is_active' => $user->is_active, 'photo_url' => $user->photoUrl(),
            'role_id' => $user->roles->first()?->id,
        ]);
    }

    public function update(Request $request, User $user): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:6'],
            'phone' => ['nullable', 'string', 'max:30'],
            'role_id' => ['required', 'exists:roles,id'],
            'is_active' => ['nullable', 'boolean'],
            'photo' => ['nullable', 'image', 'max:2048'],
        ]);

        $user->update([
            'name' => $data['name'], 'email' => $data['email'], 'phone' => $data['phone'] ?? null,
            'is_active' => $request->boolean('is_active', true),
            'password' => $request->filled('password') ? Hash::make($data['password']) : $user->password,
            'photo' => $request->hasFile('photo') ? Uploads::store($request->file('photo'), 'avatars') : $user->photo,
        ]);
        $user->roles()->sync([$data['role_id']]);
        $this->ensureDentistProfile($user, $data['role_id']);

        ActivityLog::log('updated', "User {$user->name} updated", $user);

        return response()->json(['message' => 'User updated successfully.']);
    }

    /**
     * A user given the Dentist role here also needs a dentist profile, or
     * they never show up in the dentist list, schedules or booking forms.
     */
    protected function ensureDentistProfile(User $user, int|string $roleId): void
    {
        if (Role::whereKey($roleId)->value('slug') !== Role::DENTIST) {
            return;
        }

        $profile = Dentist::withTrashed()->where('user_id', $user->id)->first();

        if (! $profile) {
            Dentist::create(['user_id' => $user->id, 'dentist_code' => Dentist::nextCode(), 'is_active' => true]);
        } elseif ($profile->trashed()) {
            $profile->restore();
        }
    }

    public function destroy(User $user): JsonResponse
    {
        $user->delete();
        ActivityLog::log('deleted', "User {$user->name} deleted", $user);

        return response()->json(['message' => 'User deleted successfully.']);
    }

    public function toggleStatus(Request $request, User $user): JsonResponse
    {
        if ($user->id === $request->user()->id) {
            return response()->json(['message' => 'You cannot deactivate your own account.'], 422);
        }

        $user->update(['is_active' => ! $user->is_active]);

        ActivityLog::log(
            $user->is_active ? 'activated' : 'deactivated',
            "User {$user->name} " . ($user->is_active ? 'activated' : 'deactivated'),
            $user
        );

        return response()->json(['message' => 'User status updated.', 'is_active' => $user->is_active]);
    }
}
