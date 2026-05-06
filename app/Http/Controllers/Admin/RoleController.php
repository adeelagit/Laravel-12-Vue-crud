<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AdminSetting;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Validation\Rule;

class RoleController extends Controller
{
    public function index()
    {
        $userId = Auth::guard('admin')->id();
        $roles = Role::with('permissions')
            ->where('guard_name', 'admin')
            ->get();

        $permissions = Permission::where('guard_name', 'admin')->get(['id', 'name']);
        // Group permissions by resource name
        $permissionsGrouped = $permissions->groupBy(function ($permission) {

            // Extract category from permission name
            // e.g. "view supplier verification"
            $parts = explode(' ', $permission->name);

            // Remove action word (view, create, update, etc.)
            array_shift($parts);

            return ucfirst(implode(' ', $parts));
        });

        return inertia('admin/roles/Index',[
            'roles' => $roles,
            'permissions' => $permissionsGrouped,  
        ]);
    }

    public function createRole()
    {
        $permissions = Permission::where('guard_name', 'admin')->get(['id', 'name']);
        $permissionsGrouped = $permissions->groupBy(function ($permission) {
            $parts = explode(' ', $permission->name);
            array_shift($parts);
            return ucfirst(implode(' ', $parts));
        })
        ->map(function ($group) {
            return $group->map(function ($permission) {
                return [
                    'id' => $permission->id,
                    'name' => $permission->name,
                ];
            })->values();
        })->toArray();

        return inertia('admin/roles/Create', [
            'permissions' => $permissionsGrouped,
        ]);
    }

    public function storeRole(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                Rule::unique('roles')->where(function ($query) {
                    return $query->where('guard_name', 'admin');
                }),
            ],
            'permissions' => 'required|array|min:1',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role = Role::create([
            'name' => $validated['name'],
            'guard_name' => 'admin',
        ]);

        $role->syncPermissions($validated['permissions']);

        return redirect()->route('admin.roles')->with('message', 'Role with permissions created successfully.');
    }

    public function editRole(Role $role)
    {
        $permissions = Permission::where('guard_name', 'admin')->get();
        $permissionsGrouped = $permissions->groupBy(function ($permission) {
            $parts = explode(' ', $permission->name);
            array_shift($parts);
            return ucfirst(implode(' ', $parts));
        });

        return inertia('admin/roles/EditRolePermissions', [
            'role' => [
                'id' => $role->id,
                'name' => $role->name,
                'permissions' => $role->permissions->pluck('id')->toArray(),
            ],
            'permissions' => $permissionsGrouped,
        ]);
    }

    public function updateRole(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                Rule::unique('roles')->ignore($role->id)->where(function ($query) {
                    return $query->where('guard_name', 'admin');
                }),
            ],
            'permissions' => 'required|array|min:1',
            'permissions.*' => 'exists:permissions,id',
        ]);

        if($role->name !== $validated['name']){
            $role->name = $validated['name'];
            $role->save();
        }
        
        $role->syncPermissions($validated['permissions']);

        return redirect()->route('admin.roles')->with('message', 'Role permissions updated successfully.');
    }
    
    public function destroy(Role $role)
    {
        // Prevent deleting critical roles (optional but recommended)
        if ($role->name === 'Super Admin') {
            return redirect()
                ->back()
                ->with('message', 'Super Admin role cannot be deleted');
        }

        $role->delete();
        return redirect()->route('admin.roles')->with('message', 'Role deleted successfully.');
    }
}
