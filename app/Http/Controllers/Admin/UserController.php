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
        $this->authorize('view users');

        $users = User::latest()->paginate(2);
        
        return Inertia::render('admin/users/Index',[
            'users' => $users,
        ]);
    }

    public function create()
    {
        // Check permission using gate
        $this->authorize('create users');

        return Inertia::render('admin/users/Create');
    }

    public function store(Request $request){
        // Check permission using gate
        $this->authorize('create users');

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
        $this->authorize('edit users');

        return Inertia::render('admin/users/Edit',[
            'user' => $user,
        ]);
    }

    public function update(Request $request, User $user){
        // Check permission using gate
        $this->authorize('edit users');

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
        $this->authorize('delete users');

        $user->delete();
        return redirect()->route('admin.users.index')->with('message', 'User deleted successfully');
    }
}
