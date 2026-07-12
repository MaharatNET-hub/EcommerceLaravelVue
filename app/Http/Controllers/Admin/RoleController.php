<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index(): Response
    {
        $this->authorize('roles.view');

        return Inertia::render('Admin/Roles/Index', [
            'roles' => Role::withCount('users')->orderBy('name')->get(),
        ]);
    }

    public function create(): Response
    {
        $this->authorize('roles.create');

        return Inertia::render('Admin/Roles/Form', [
            'role' => null,
            'permissions' => Permission::orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('roles.create');

        $data = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:roles,name'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['exists:permissions,name'],
        ]);

        $role = Role::create(['name' => $data['name']]);
        $role->syncPermissions($data['permissions'] ?? []);

        return redirect()->route('admin.roles.index')->with('success', 'تم إنشاء الدور.');
    }

    public function edit(Role $role): Response
    {
        $this->authorize('roles.update');

        return Inertia::render('Admin/Roles/Form', [
            'role' => $role->load('permissions:id,name'),
            'permissions' => Permission::orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function update(Request $request, Role $role): RedirectResponse
    {
        $this->authorize('roles.update');

        $data = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:roles,name,'.$role->id],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['exists:permissions,name'],
        ]);

        $role->update(['name' => $data['name']]);
        $role->syncPermissions($data['permissions'] ?? []);

        return redirect()->route('admin.roles.index')->with('success', 'تم تحديث الدور.');
    }

    public function destroy(Role $role): RedirectResponse
    {
        $this->authorize('roles.delete');

        abort_if($role->name === 'Super Admin', 403, 'لا يمكن حذف دور المشرف الرئيسي.');

        $role->delete();

        return back()->with('success', 'تم حذف الدور.');
    }
}
