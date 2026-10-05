<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDentistRequest;
use App\Models\ActivityLog;
use App\Models\Dentist;
use App\Models\Role;
use App\Models\User;
use App\Support\Uploads;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class DentistController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            $query = Dentist::with('user')->latest();
            $total = Dentist::count();
            $filtered = $total;
            $dentists = $query->skip($request->get('start', 0))->take($request->get('length', 10))->get();

            return response()->json([
                'draw' => (int) $request->get('draw'),
                'recordsTotal' => $total,
                'recordsFiltered' => $filtered,
                'data' => $dentists->map(fn ($d) => [
                    'id' => $d->id,
                    'dentist_code' => $d->dentist_code,
                    'name' => $d->user->name,
                    'email' => $d->user->email,
                    'specialization' => $d->specialization,
                    'photo' => $d->user->photo,
                    'is_active' => $d->is_active,
                ]),
            ]);
        }

        return view('dentists.index');
    }

    public function create(): RedirectResponse
    {
        return redirect()->route('dentists.index');
    }

    public function store(StoreDentistRequest $request): JsonResponse
    {
        $dentist = DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'photo' => $request->hasFile('photo') ? Uploads::store($request->file('photo'), 'avatars') : null,
                'is_active' => true,
            ]);
            $user->roles()->syncWithoutDetaching(Role::where('slug', Role::DENTIST)->value('id'));

            return Dentist::create([
                'user_id' => $user->id,
                'dentist_code' => $this->nextCode(),
                'specialization' => $request->specialization,
                'license_number' => $request->license_number,
                'bio' => $request->bio,
                'is_active' => true,
            ]);
        });

        ActivityLog::log('created', "Dentist {$dentist->user->name} added", $dentist);

        return response()->json(['message' => 'Dentist added successfully.'], 201);
    }

    protected function nextCode(): string
    {
        $last = Dentist::withTrashed()->orderByDesc('dentist_code')->value('dentist_code');
        $next = $last ? ((int) substr($last, -4)) + 1 : 1;

        return 'DEN-' . str_pad($next, 4, '0', STR_PAD_LEFT);
    }

    public function show(Dentist $dentist): View
    {
        $dentist->load(['user', 'schedules', 'appointments.patient', 'appointments.service']);

        return view('dentists.show', compact('dentist'));
    }

    public function quickView(Dentist $dentist): View
    {
        $dentist->load([
            'user',
            'schedules' => fn ($q) => $q->where('type', 'weekly')->orderBy('day_of_week'),
            'appointments' => fn ($q) => $q->latest('appointment_date')->limit(5),
            'appointments.patient',
            'appointments.service',
        ]);

        return view('dentists._quick_view', compact('dentist'));
    }

    public function edit(Dentist $dentist): JsonResponse
    {
        $dentist->load('user');

        return response()->json([
            'id' => $dentist->id,
            'name' => $dentist->user->name,
            'email' => $dentist->user->email,
            'phone' => $dentist->user->phone,
            'photo' => $dentist->user->photo,
            'specialization' => $dentist->specialization,
            'license_number' => $dentist->license_number,
            'bio' => $dentist->bio,
        ]);
    }

    public function update(StoreDentistRequest $request, Dentist $dentist): JsonResponse
    {
        DB::transaction(function () use ($request, $dentist) {
            $dentist->user->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => $request->filled('password') ? Hash::make($request->password) : $dentist->user->password,
                'photo' => $request->hasFile('photo') ? Uploads::store($request->file('photo'), 'avatars') : $dentist->user->photo,
            ]);

            $dentist->update([
                'specialization' => $request->specialization,
                'license_number' => $request->license_number,
                'bio' => $request->bio,
            ]);
        });

        ActivityLog::log('updated', "Dentist {$dentist->user->name} updated", $dentist);

        return response()->json(['message' => 'Dentist updated successfully.']);
    }

    public function destroy(Dentist $dentist): JsonResponse
    {
        $dentist->delete();

        ActivityLog::log('deleted', "Dentist {$dentist->user->name} deleted", $dentist);

        return response()->json(['message' => 'Dentist deleted successfully.']);
    }
}
