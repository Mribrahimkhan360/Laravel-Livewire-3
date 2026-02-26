php artisan vendor:publish --tag="permission-migrations"

<br />
# Spatie Laravel Permission — 4-Layer Architecture Guide

> **Stack:** Laravel · Spatie Permission · Model → Repository (Contract + Eloquent) → Service → Controller (FormRequest)

---

## 📁 Directory Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   └── Admin/
│   │       ├── RoleController.php
│   │       └── UserController.php
│   └── Requests/
│       ├── Role/
│       │   ├── StoreRoleRequest.php
│       │   └── UpdateRoleRequest.php
│       └── User/
│           ├── StoreUserRequest.php
│           └── UpdateUserRequest.php
├── Models/
│   └── User.php
├── Repositories/
│   ├── Contracts/
│   │   ├── RoleRepositoryInterface.php
│   │   └── UserRepositoryInterface.php
│   └── Eloquent/
│       ├── RoleRepository.php
│       └── UserRepository.php
├── Services/
│   ├── RoleService.php
│   └── UserService.php
└── Providers/
    └── RepositoryServiceProvider.php
```

---

## 1. Installation

```bash
composer require spatie/laravel-permission
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan migrate
```

---

## 2. Model Layer

### `app/Models/User.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
    ];
}
```

> Spatie's `HasRoles` trait adds `assignRole()`, `removeRole()`, `hasRole()`, `hasPermissionTo()`, etc. to your User model.

---

## 3. Repository Contracts (Interfaces)

### `app/Repositories/Contracts/RoleRepositoryInterface.php`

```php
<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Spatie\Permission\Models\Role;

interface RoleRepositoryInterface
{
    public function all(): Collection;

    public function findById(int $id): Role;

    public function findByName(string $name): ?Role;

    public function create(array $data): Role;

    public function update(Role $role, array $data): Role;

    public function delete(Role $role): bool;

    public function syncPermissions(Role $role, array $permissions): Role;

    public function getAllPermissions(): Collection;
}
```

### `app/Repositories/Contracts/UserRepositoryInterface.php`

```php
<?php

namespace App\Repositories\Contracts;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

interface UserRepositoryInterface
{
    public function paginate(int $perPage = 15): LengthAwarePaginator;

    public function findById(int $id): User;

    public function create(array $data): User;

    public function update(User $user, array $data): User;

    public function delete(User $user): bool;

    public function assignRoles(User $user, array $roles): User;

    public function syncRoles(User $user, array $roles): User;

    public function removeRole(User $user, string $role): User;
}
```

---

## 4. Repository Eloquent Implementations

### `app/Repositories/Eloquent/RoleRepository.php`

```php
<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\RoleRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleRepository implements RoleRepositoryInterface
{
    public function __construct(
        protected Role $model,
        protected Permission $permissionModel
    ) {}

    public function all(): Collection
    {
        return $this->model->with('permissions')->get();
    }

    public function findById(int $id): Role
    {
        return $this->model->with('permissions')->findOrFail($id);
    }

    public function findByName(string $name): ?Role
    {
        return $this->model->where('name', $name)->first();
    }

    public function create(array $data): Role
    {
        return $this->model->create([
            'name'       => $data['name'],
            'guard_name' => $data['guard_name'] ?? 'web',
        ]);
    }

    public function update(Role $role, array $data): Role
    {
        $role->update([
            'name' => $data['name'],
        ]);

        return $role->fresh('permissions');
    }

    public function delete(Role $role): bool
    {
        return $role->delete();
    }

    public function syncPermissions(Role $role, array $permissions): Role
    {
        $role->syncPermissions($permissions);

        return $role->fresh('permissions');
    }

    public function getAllPermissions(): Collection
    {
        return $this->permissionModel->orderBy('name')->get();
    }
}
```

### `app/Repositories/Eloquent/UserRepository.php`

```php
<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;

class UserRepository implements UserRepositoryInterface
{
    public function __construct(protected User $model) {}

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model
            ->with('roles', 'permissions')
            ->latest()
            ->paginate($perPage);
    }

    public function findById(int $id): User
    {
        return $this->model->with('roles', 'permissions')->findOrFail($id);
    }

    public function create(array $data): User
    {
        return $this->model->create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    public function update(User $user, array $data): User
    {
        $payload = [
            'name'  => $data['name'],
            'email' => $data['email'],
        ];

        if (!empty($data['password'])) {
            $payload['password'] = Hash::make($data['password']);
        }

        $user->update($payload);

        return $user->fresh('roles', 'permissions');
    }

    public function delete(User $user): bool
    {
        return $user->delete();
    }

    public function assignRoles(User $user, array $roles): User
    {
        $user->assignRole($roles);

        return $user->fresh('roles');
    }

    public function syncRoles(User $user, array $roles): User
    {
        $user->syncRoles($roles);

        return $user->fresh('roles');
    }

    public function removeRole(User $user, string $role): User
    {
        $user->removeRole($role);

        return $user->fresh('roles');
    }
}
```

---

## 5. Service Layer

### `app/Services/RoleService.php`

```php
<?php

namespace App\Services;

use App\Repositories\Contracts\RoleRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Spatie\Permission\Models\Role;

class RoleService
{
    public function __construct(
        protected RoleRepositoryInterface $roleRepository
    ) {}

    public function getAllRoles(): Collection
    {
        return $this->roleRepository->all();
    }

    public function getAllPermissions(): Collection
    {
        return $this->roleRepository->getAllPermissions();
    }

    public function findRole(int $id): Role
    {
        return $this->roleRepository->findById($id);
    }

    public function createRole(array $data): Role
    {
        $role = $this->roleRepository->create($data);

        if (!empty($data['permissions'])) {
            $role = $this->roleRepository->syncPermissions($role, $data['permissions']);
        }

        return $role;
    }

    public function updateRole(int $id, array $data): Role
    {
        $role = $this->roleRepository->findById($id);
        $role = $this->roleRepository->update($role, $data);

        if (array_key_exists('permissions', $data)) {
            $role = $this->roleRepository->syncPermissions($role, $data['permissions'] ?? []);
        }

        return $role;
    }

    public function deleteRole(int $id): bool
    {
        $role = $this->roleRepository->findById($id);

        // Prevent deletion of super-admin role
        if ($role->name === 'super-admin') {
            throw new \RuntimeException('The super-admin role cannot be deleted.');
        }

        return $this->roleRepository->delete($role);
    }
}
```

### `app/Services/UserService.php`

```php
<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class UserService
{
    public function __construct(
        protected UserRepositoryInterface $userRepository
    ) {}

    public function getPaginatedUsers(int $perPage = 15): LengthAwarePaginator
    {
        return $this->userRepository->paginate($perPage);
    }

    public function findUser(int $id): User
    {
        return $this->userRepository->findById($id);
    }

    public function createUser(array $data): User
    {
        $user = $this->userRepository->create($data);

        if (!empty($data['roles'])) {
            $user = $this->userRepository->syncRoles($user, $data['roles']);
        }

        return $user;
    }

    public function updateUser(int $id, array $data): User
    {
        $user = $this->userRepository->findById($id);
        $user = $this->userRepository->update($user, $data);

        if (array_key_exists('roles', $data)) {
            $user = $this->userRepository->syncRoles($user, $data['roles'] ?? []);
        }

        return $user;
    }

    public function deleteUser(int $id): bool
    {
        $user = $this->userRepository->findById($id);

        return $this->userRepository->delete($user);
    }
}
```

---

## 6. Form Requests

### `app/Http/Requests/Role/StoreRoleRequest.php`

```php
<?php

namespace App\Http\Requests\Role;

use Illuminate\Foundation\Http\FormRequest;

class StoreRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create roles');
    }

    public function rules(): array
    {
        return [
            'name'          => ['required', 'string', 'max:125', 'unique:roles,name'],
            'guard_name'    => ['sometimes', 'string', 'in:web,api'],
            'permissions'   => ['sometimes', 'array'],
            'permissions.*' => ['string', 'exists:permissions,name'],
        ];
    }
}
```

### `app/Http/Requests/Role/UpdateRoleRequest.php`

```php
<?php

namespace App\Http\Requests\Role;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('edit roles');
    }

    public function rules(): array
    {
        return [
            'name'          => ['required', 'string', 'max:125', 'unique:roles,name,' . $this->route('role')],
            'permissions'   => ['sometimes', 'array'],
            'permissions.*' => ['string', 'exists:permissions,name'],
        ];
    }
}
```

### `app/Http/Requests/User/StoreUserRequest.php`

```php
<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create users');
    }

    public function rules(): array
    {
        return [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'roles'    => ['sometimes', 'array'],
            'roles.*'  => ['string', 'exists:roles,name'],
        ];
    }
}
```

### `app/Http/Requests/User/UpdateUserRequest.php`

```php
<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('edit users');
    }

    public function rules(): array
    {
        return [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'unique:users,email,' . $this->route('user')],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'roles'    => ['sometimes', 'array'],
            'roles.*'  => ['string', 'exists:roles,name'],
        ];
    }
}
```

---

## 7. Controllers

### `app/Http/Controllers/Admin/RoleController.php`

```php
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Role\StoreRoleRequest;
use App\Http\Requests\Role\UpdateRoleRequest;
use App\Services\RoleService;
use Illuminate\Http\JsonResponse;

class RoleController extends Controller
{
    public function __construct(protected RoleService $roleService)
    {
        $this->middleware('permissions:view roles')->only(['index', 'show']);
        $this->middleware('permissions:create roles')->only(['store']);
        $this->middleware('permissions:edit roles')->only(['update']);
        $this->middleware('permissions:delete roles')->only(['destroy']);
    }

    public function index(): JsonResponse
    {
        $roles       = $this->roleService->getAllRoles();
        $permissions = $this->roleService->getAllPermissions();

        return response()->json([
            'roles'       => $roles,
            'permissions' => $permissions,
        ]);
    }

    public function store(StoreRoleRequest $request): JsonResponse
    {
        $role = $this->roleService->createRole($request->validated());

        return response()->json([
            'message' => 'Role created successfully.',
            'role'    => $role,
        ], 201);
    }

    public function show(int $id): JsonResponse
    {
        $role = $this->roleService->findRole($id);

        return response()->json(['role' => $role]);
    }

    public function update(UpdateRoleRequest $request, int $id): JsonResponse
    {
        $role = $this->roleService->updateRole($id, $request->validated());

        return response()->json([
            'message' => 'Role updated successfully.',
            'role'    => $role,
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->roleService->deleteRole($id);

        return response()->json(['message' => 'Role deleted successfully.']);
    }
}
```

### `app/Http/Controllers/Admin/UserController.php`

```php
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function __construct(protected UserService $userService)
    {
        $this->middleware('permissions:view users')->only(['index', 'show']);
        $this->middleware('permissions:create users')->only(['store']);
        $this->middleware('permissions:edit users')->only(['update']);
        $this->middleware('permissions:delete users')->only(['destroy']);
    }

    public function index(): JsonResponse
    {
        $users = $this->userService->getPaginatedUsers(perPage: 15);

        return response()->json($users);
    }

    public function store(StoreUserRequest $request): JsonResponse
    {
        $user = $this->userService->createUser($request->validated());

        return response()->json([
            'message' => 'User created successfully.',
            'user'    => $user,
        ], 201);
    }

    public function show(int $id): JsonResponse
    {
        $user = $this->userService->findUser($id);

        return response()->json(['user' => $user]);
    }

    public function update(UpdateUserRequest $request, int $id): JsonResponse
    {
        $user = $this->userService->updateUser($id, $request->validated());

        return response()->json([
            'message' => 'User updated successfully.',
            'user'    => $user,
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->userService->deleteUser($id);

        return response()->json(['message' => 'User deleted successfully.']);
    }
}
```

---

## 8. Repository Service Provider (Binding)

### `app/Providers/RepositoryServiceProvider.php`

```php
<?php

namespace App\Providers;

use App\Repositories\Contracts\RoleRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Eloquent\RoleRepository;
use App\Repositories\Eloquent\UserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(RoleRepositoryInterface::class, RoleRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
    }
}
```

Register in `bootstrap/providers.php` (Laravel 11) or `config/app.php` (Laravel 10):

```php
// bootstrap/providers.php  (Laravel 11)
return [
    App\Providers\AppServiceProvider::class,
    App\Providers\RepositoryServiceProvider::class,  // ← add this
];

// config/app.php  (Laravel 10)
'providers' => [
    // ...
    App\Providers\RepositoryServiceProvider::class,
],
```

---

## 9. Routes

### `routes/api.php`

```php
<?php

use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->prefix('admin')->name('admin.')->group(function () {
    Route::apiResource('roles', RoleController::class);
    Route::apiResource('users', UserController::class);
});
```

---

## 10. Database Seeder — Roles, Permissions & Super Admin

### `database/seeders/RolePermissionSeeder.php`

```php
<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'view users',   'create users',   'edit users',   'delete users',
            'view roles',   'create roles',   'edit roles',   'delete roles',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $superAdmin = Role::firstOrCreate(['name' => 'super-admin']);
        $admin      = Role::firstOrCreate(['name' => 'admin']);
        $editor     = Role::firstOrCreate(['name' => 'editor']);

        // super-admin gets all permissions via Gate::before in AuthServiceProvider
        $admin->syncPermissions(Permission::all());
        $editor->syncPermissions(['view users', 'view roles']);

        // Create a super admin user
        $user = User::firstOrCreate(
            ['email' => 'superadmin@example.com'],
            ['name' => 'Super Admin', 'password' => bcrypt('password')]
        );

        $user->assignRole('super-admin');
    }
}
```

### `app/Providers/AppServiceProvider.php` — Gate::before for super-admin

```php
<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Super admin bypasses all permissions checks
        Gate::before(function ($user, $ability) {
            return $user->hasRole('super-admin') ? true : null;
        });
    }
}
```

---

## 11. Flow Summary

```
HTTP Request
    │
    ▼
FormRequest          ← Validates input + checks authorization via can()
    │
    ▼
Controller           ← Thin layer, calls Service, returns response
    │
    ▼
Service              ← Business logic, orchestrates repository calls
    │
    ▼
Repository Interface ← Contract / abstraction
    │
    ▼
Repository Eloquent  ← Actual DB queries using Spatie + Eloquent ORM
    │
    ▼
Model (HasRoles)     ← Spatie trait, DB relationships
```

---

## Key Principles

| Layer | Responsibility |
|---|---|
| **Model** | Data structure + Spatie `HasRoles` trait |
| **Repository Contract** | Interface defining what operations exist |
| **Repository Eloquent** | Concrete DB implementation (swap easily for testing) |
| **Service** | Business rules, orchestration, error handling |
| **FormRequest** | Input validation + `authorize()` using Spatie permissions |
| **Controller** | HTTP in/out, delegates everything to Service |

> **Testing tip:** Because the Controller depends on the *interface*, you can swap `RoleRepository` with a mock in tests without touching any other layer.

<br />
composer require spatie/laravel-permission:6.*
<br />
<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[WebReinvent](https://webreinvent.com/)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Jump24](https://jump24.co.uk)**
- **[Redberry](https://redberry.international/laravel/)**
- **[Active Logic](https://activelogic.com)**
- **[byte5](https://byte5.de)**
- **[OP.GG](https://op.gg)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
