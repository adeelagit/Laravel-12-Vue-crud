# Roles and Permissions System - Admin Panel

This document outlines the roles and permissions system implemented for the admin panel CRUD operations.

## Overview

The system uses **Spatie Laravel Permissions** package to manage roles and permissions for the admin panel.

## Database Structure

### Tables Created:
- `permissions` - Stores permission definitions
- `roles` - Stores role definitions  
- `model_has_permissions` - Links direct permissions to admins
- `model_has_roles` - Links roles to admins
- `role_has_permissions` - Links permissions to roles

## Available Permissions

### User Management
- `view_users` - View user list
- `create_users` - Create and view create form
- `edit_users` - Edit and update users
- `delete_users` - Delete users

## Available Roles

### 1. **Super Admin**
   - All permissions: `view_users`, `create_users`, `edit_users`, `delete_users`
   - Use case: System administrators with full control

### 2. **Admin**
   - Permissions: `view_users`, `create_users`, `edit_users`
   - Use case: Administrators who can manage users but cannot delete
   - Cannot: Delete users

### 3. **User Manager**
   - Permissions: `view_users`, `create_users`, `edit_users`, `delete_users`
   - Use case: Dedicated user management staff with full CRUD access

### 4. **User Viewer**
   - Permissions: `view_users`
   - Use case: Read-only access to user information

## Permission Checking Architecture

### 1. **Controller Level - Using Gates**

Gates are defined in `app/Providers/AppServiceProvider.php` and check permissions in controllers:

```php
// In Controller
public function index()
{
    // Check permission using gate and authorize
    $this->authorize('view_users', auth('admin')->user());

    $users = User::latest()->paginate(2);
    
    return Inertia::render('admin/users/Index', [
        'users' => $users,
        'permissions' => [
            'can_create' => auth('admin')->user()->can('create_users'),
            'can_edit' => auth('admin')->user()->can('edit_users'),
            'can_delete' => auth('admin')->user()->can('delete_users'),
            'can_view' => auth('admin')->user()->can('view_users'),
        ]
    ]);
}
```

### 2. **Vue Page Level - Conditional Rendering**

Permissions are passed from controller to Vue pages and used to show/hide UI elements:

```vue
<!-- Index.vue - Show Create button only if user has permission -->
<div v-if="props.permissions.can_create">
    <Link :href="route('admin.users.create')" class="px-4 py-2 bg-slate-600 text-white rounded-md">
        <Plus class="inline-block space-x-2" />
        Create User
    </Link>
</div>

<!-- Show Edit/Delete buttons only if user has permission -->
<Button v-if="props.permissions.can_edit" class="bg-slate-600 text-white">Edit</Button>
<Button v-if="props.permissions.can_delete" class="bg-red-600 text-white">Delete</Button>
```

## Usage in Routes

Routes are **NOT** protected with middleware. Permission checking happens at:
1. **Controller level** - Using `$this->authorize()` and gates
2. **Vue level** - Using conditional rendering

```php
// routes/admin.php - No middleware permission checks
Route::middleware(['auth:admin'])->group(function () {
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::post('users', [UserController::class, 'store'])->name('users.store');
    Route::delete('users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    // ... other routes
});
```

## How to Use in Code

### Check if Admin has Permission in Controller

```php
// Using authorize (throws 403 if no permission)
$this->authorize('view_users', auth('admin')->user());

// Using can() method
if (auth('admin')->user()->can('view_users')) {
    // User has permission
}
```

### Check if Admin has Role in Controller

```php
if (auth('admin')->user()->hasRole('Super Admin')) {
    // User is Super Admin
}
```

### In Vue Templates

```vue
<script setup>
interface Props {
    permissions: {
        can_create: boolean,
        can_edit: boolean,
        can_delete: boolean,
        can_view: boolean,
    }
}
const props = defineProps<Props>();
</script>

<template>
    <!-- Show element only if user has permission -->
    <button v-if="props.permissions.can_delete">Delete</button>
</template>
```

## Assigning Roles to Admins

### In Seeder
```php
$admin = Admin::find(1);
$admin->syncRoles(['Super Admin']);
// or
$admin->assignRole('Super Admin');
```

### In Controller
```php
$admin = Admin::find($id);
$admin->syncRoles($request->roles); // syncRoles replaces existing roles
// or
$admin->assignRole('Admin'); // Adds a role
```

## Assigning Permissions to Roles

Permissions are assigned in the `PermissionSeeder`:

```php
$role = Role::findByName('Admin', 'admin');
$role->syncPermissions(['view_users', 'create_users', 'edit_users']);
```

## Database Seeding

Run the seeders to populate roles and permissions:

```bash
php artisan db:seed
```

This will:
1. Create all permissions (via `PermissionSeeder`)
2. Create all roles with their permissions (via `PermissionSeeder`)
3. Create the default admin user with "Super Admin" role (via `AdminSeeder`)

## Default Admin Account

- **Email**: admin@mail.com
- **Password**: 123456789
- **Role**: Super Admin (all permissions)

## Gates Definition

Gates are defined in `app/Providers/AppServiceProvider.php`:

```php
public function boot(): void
{
    // Define gates for admin permissions
    Gate::define('view_users', function (Admin $admin) {
        return $admin->hasPermissionTo('view_users', 'admin');
    });

    Gate::define('create_users', function (Admin $admin) {
        return $admin->hasPermissionTo('create_users', 'admin');
    });

    Gate::define('edit_users', function (Admin $admin) {
        return $admin->hasPermissionTo('edit_users', 'admin');
    });

    Gate::define('delete_users', function (Admin $admin) {
        return $admin->hasPermissionTo('delete_users', 'admin');
    });
}
```

## Adding New Permissions for Future CRUD Operations

### Step 1: Update PermissionSeeder

```php
// database/seeders/PermissionSeeder.php
$permissions = [
    // User permissions
    'view_users', 'create_users', 'edit_users', 'delete_users',
    // Product permissions (new)
    'view_products', 'create_products', 'edit_products', 'delete_products',
];

// Define roles
$roles = [
    [
        'name' => 'Product Manager',
        'permissions' => ['view_products', 'create_products', 'edit_products', 'delete_products'],
    ],
    // ... other roles
];
```

### Step 2: Add Gates in AppServiceProvider

```php
Gate::define('view_products', function (Admin $admin) {
    return $admin->hasPermissionTo('view_products', 'admin');
});

Gate::define('create_products', function (Admin $admin) {
    return $admin->hasPermissionTo('create_products', 'admin');
});
// ... etc
```

### Step 3: Add Authorization in Controller

```php
public function index()
{
    // Check permission
    $this->authorize('view_products', auth('admin')->user());

    $products = Product::latest()->paginate();
    
    return Inertia::render('admin/products/Index', [
        'products' => $products,
        'permissions' => [
            'can_create' => auth('admin')->user()->can('create_products'),
            'can_edit' => auth('admin')->user()->can('edit_products'),
            'can_delete' => auth('admin')->user()->can('delete_products'),
        ]
    ]);
}
```

### Step 4: Use Permissions in Vue Pages

```vue
<template>
    <Button v-if="props.permissions.can_create">Create Product</Button>
    <Button v-if="props.permissions.can_edit">Edit</Button>
    <Button v-if="props.permissions.can_delete">Delete</Button>
</template>
```

### Step 5: Re-seed Database

```bash
php artisan migrate:refresh --seed
```

## Important Files

- `app/Models/Admin.php` - Admin model with HasRoles trait
- `database/seeders/PermissionSeeder.php` - Creates roles and permissions
- `database/seeders/AdminSeeder.php` - Creates default admin with Super Admin role
- `app/Providers/AppServiceProvider.php` - Defines gates for permissions
- `app/Http/Controllers/Admin/UserController.php` - Uses gates and passes permissions to Vue
- `resources/js/pages/admin/users/Index.vue` - Conditionally shows buttons based on permissions
- `resources/js/pages/admin/users/Edit.vue` - Conditionally shows edit/delete buttons
- `routes/admin.php` - Admin routes (NO middleware permission checks)

## Permission Flow Diagram

```
1. User visits /admin/users
   ↓
2. Route directs to UserController@index
   ↓
3. Controller checks: $this->authorize('view_users', $admin)
   ↓
4. If authorized, gets permissions: $admin->can('create_users'), etc.
   ↓
5. Controller passes permissions to Vue page
   ↓
6. Vue conditionally renders buttons: v-if="permissions.can_create"
   ↓
7. User clicks button (if visible)
   ↓
8. Request goes to next controller action
   ↓
9. Controller again checks: $this->authorize(permission)
   ↓
10. If authorized, action performed; else 403 error thrown
```

## Error Handling

When a user tries to access a resource without permission:

```php
// In controller - throws AuthorizationException (403)
$this->authorize('delete_users', auth('admin')->user());

// In Vue - button is hidden, so user can't click it
<Button v-if="props.permissions.can_delete">Delete</Button>
```

This creates a double-layer of security:
- **Frontend**: UI elements are hidden
- **Backend**: Authorization is enforced even if user bypasses UI
