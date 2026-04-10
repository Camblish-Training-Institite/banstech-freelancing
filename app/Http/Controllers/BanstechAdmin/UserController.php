<?php

namespace App\Http\Controllers\BanstechAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        // Search Filter
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('email', 'like', '%' . $request->search . '%');
        }

        // Role Filter
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // User Type Filter
        if ($request->filled('user_type')) {
            $query->where('user_type', $request->user_type);    
        }

        // Use paginate() instead of get()
        $users = $query->latest()->paginate(10);
        $pagetitle = "Manage Users";

        return view('admin.users.index', compact('users', 'pagetitle'));
    }

    public function show(User $user)
    {
        $pagetitle = "User Details";
        return view('admin.users.show', compact('user', 'pagetitle'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('admin.users.form', ['pagetitle' => 'Create User', 'action' => 'Create', 'roles' => $roles]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'user_type' => ['string'], // Matches your schema
            'role' => ['required', 'exists:roles,name'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'user_type' => $request->user_type,
            'is_verified' => true, // Admin-created users are usually pre-verified
        ]);

        // Assign Spatie Role
        $user->assignRole($request->role);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        return view('admin.users.form', ['pagetitle' => 'Edit User', 'action' => 'Edit', 'user' => $user, 'roles' => $roles]);
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'user_type' => ['required', 'string'],
            'role' => ['required', 'exists:roles,name'],
        ]);

        $user->update($request->only('name', 'email', 'user_type'));

        // Sync Spatie Roles (removes old roles and adds new one)
        $user->syncRoles($request->role);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    /**
     * Change only the user's role.
     */
    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'role' => ['required', 'exists:roles,name'],
        ]);

        $user->syncRoles($request->role);

        return back()->with('success', 'User role updated to ' . $request->role);
    }

    /**
     * Reset the user's password manually.
     */
    public function resetPassword(Request $request, User $user)
    {
        $request->validate([
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password has been reset successfully.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }
}
