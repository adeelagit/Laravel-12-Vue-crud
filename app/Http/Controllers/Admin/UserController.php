<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index()
    {
        // Check permission using gate
        // $this->authorize('view_users', auth('admin')->user());

        $users = User::latest()->paginate(2);
        
        return Inertia::render('admin/users/Index',[
            'users' => $users,
            'permissions' => [
                'can_create' => auth('admin')->user()->can('create_users'),
                'can_edit' => auth('admin')->user()->can('edit_users'),
                'can_delete' => auth('admin')->user()->can('delete_users'),
                'can_view' => auth('admin')->user()->can('view_users'),
            ]
        ]);
    }

    public function create()
    {
        // Check permission using gate
        //$this->authorize('create_users', auth('admin')->user());

        return Inertia::render('admin/users/Create');
    }

    public function store(Request $request){
        // Check permission using gate
        $this->authorize('create_users', auth('admin')->user());

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        $data['password'] = bcrypt($data['password']);
        User::create($data);

        return redirect()->route('admin.users.index')->with('message', 'User added successfully');
    }

    public function edit(User $user)
    {
        // Check permission using gate
        $this->authorize('edit_users', auth('admin')->user());

        return Inertia::render('admin/users/Edit',[
            'user' => $user,
            'permissions' => [
                'can_edit' => auth('admin')->user()->can('edit_users'),
                'can_delete' => auth('admin')->user()->can('delete_users'),
            ]
        ]);
    }

    public function update(Request $request, User $user){
        // Check permission using gate
        $this->authorize('edit_users', auth('admin')->user());

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,'.$user->id],
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        if($data['password']){
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('message', 'User updated successfully');
    }

    public function destroy(User $user)
    {
        // Check permission using gate
        $this->authorize('delete_users', auth('admin')->user());

        $user->delete();
        return redirect()->route('admin.users.index')->with('message', 'User deleted successfully');
    }
}
