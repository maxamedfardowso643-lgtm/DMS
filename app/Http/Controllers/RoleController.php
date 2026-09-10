<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RoleController extends Controller
{
    public function index(): View
    {
        $roles = Role::withCount('users')->get();

        return view('roles.index', compact('roles'));
    }

    public function create(): RedirectResponse
    {
        return redirect()->route('roles.index');
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'slug' => ['required', 'string', 'max:100', 'unique:roles,slug'],
            'description' => ['nullable', 'string'],
        ]);

        Role::create($data);

        return response()->json(['message' => 'Role created successfully.'], 201);
    }

    public function edit(Role $role): JsonResponse
    {
        return response()->json($role);
    }

    public function update(Request $request, Role $role): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
        ]);

        $role->update($data);

        return response()->json(['message' => 'Role updated successfully.']);
    }

    public function destroy(Role $role): JsonResponse
    {
        if ($role->users()->exists()) {
            return response()->json(['message' => 'Cannot delete a role that is assigned to users.'], 422);
        }

        $role->delete();

        return response()->json(['message' => 'Role deleted successfully.']);
    }
}
