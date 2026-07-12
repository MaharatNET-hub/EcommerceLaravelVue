<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(Request $request): Response
    {
        $this->authorize('users.view');

        return Inertia::render('Admin/Users/Index', [
            'users' => User::query()
                ->with('roles:id,name')
                ->when($request->search, fn ($q, $search) => $q->where(fn ($qq) => $qq
                    ->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")))
                ->latest()
                ->paginate(15)
                ->withQueryString(),
            'filters' => $request->only('search'),
        ]);
    }

    public function create(): Response
    {
        $this->authorize('users.create');

        return Inertia::render('Admin/Users/Form', [
            'user' => null,
            'roles' => Role::orderBy('name')->pluck('name'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('users.create');

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:50'],
            'password' => ['required', 'string', 'min:8'],
            'is_active' => ['boolean'],
            'roles' => ['nullable', 'array'],
            'roles.*' => ['exists:roles,name'],
        ]);

        $user = User::create([
            ...collect($data)->except(['password', 'roles'])->toArray(),
            'password' => Hash::make($data['password']),
        ]);

        $user->syncRoles($data['roles'] ?? []);

        return redirect()->route('admin.users.index')->with('success', 'تم إنشاء المستخدم.');
    }

    public function edit(User $user): Response
    {
        $this->authorize('users.update');

        return Inertia::render('Admin/Users/Form', [
            'user' => $user->load('roles:id,name'),
            'roles' => Role::orderBy('name')->pluck('name'),
        ]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $this->authorize('users.update');

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'phone' => ['nullable', 'string', 'max:50'],
            'password' => ['nullable', 'string', 'min:8'],
            'is_active' => ['boolean'],
            'roles' => ['nullable', 'array'],
            'roles.*' => ['exists:roles,name'],
        ]);

        $user->update([
            ...collect($data)->except(['password', 'roles'])->toArray(),
            ...($data['password'] ? ['password' => Hash::make($data['password'])] : []),
        ]);

        $user->syncRoles($data['roles'] ?? []);

        return redirect()->route('admin.users.index')->with('success', 'تم تحديث المستخدم.');
    }

    public function destroy(User $user): RedirectResponse
    {
        $this->authorize('users.delete');

        abort_if($user->id === auth()->id(), 403, 'لا يمكنك حذف حسابك الخاص.');

        $user->delete();

        return back()->with('success', 'تم حذف المستخدم.');
    }
}
