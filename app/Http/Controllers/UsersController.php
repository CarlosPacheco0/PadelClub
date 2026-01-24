<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    public function __invoke()
    {
        // Obtener la info de los usuarios
        $users = User::select(
            'id',
            'name',
            'email',
            'phone',
            'status',
            'role_id'
        )
            ->with([
                'role:id,label',
            ])
            ->orderBy('name', 'asc')
            ->get();

        // Obtener label de Roles
        $roles = Role::orderBy('id', 'asc')->get();

        return view('pages.admin.users', compact('users', 'roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email',
            'phone'    => 'required|string|max:20',
            'password' => 'required|confirmed|min:5',
            'role'     => 'required|exists:roles,id',
            'status'   => 'required|boolean',
        ]);


        // Obtener id del Rol User
        $userRole = Role::where('id', $validated['role'])->first();

        User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role_id'  => $userRole->id,
            'phone'    => $validated['phone'],
            'status'   => $validated['status']
        ]);

        return redirect(route('users'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'id'       => 'required|exists:users,id',
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email,' . $request->id,
            'phone'    => 'required|string|max:20',
            'password' => 'nullable|confirmed|min:5',
            'role'     => 'required|exists:roles,id',
            'status'   => 'required|boolean',
        ]);

        $user = User::findOrFail($validated['id']);

        $user->update([
            'name'    => $validated['name'],
            'email'   => $validated['email'],
            'phone'   => $validated['phone'],
            'role_id' => $validated['role'],
            'status'  => $validated['status'],
        ]);

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
            $user->save();
        }

        return redirect(route('users'));
    }

    public function delete(Request $request)
    {
        $user = User::where('id', $request->user_id)->first();

        if ($user) {
            $user->delete();
        }

        return redirect(route('users'));
    }
}
