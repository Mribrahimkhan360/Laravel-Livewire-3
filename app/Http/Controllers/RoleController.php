<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoleRequest;
use App\Services\RoleService;
use App\Services\PermissionService;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    protected $roleService;
    protected $permissionService;
    public function __construct(RoleService $roleService,PermissionService $permissionService)
    {
        $this->roleService              = $roleService;
        $this->permissionService    = $permissionService;
    }

    public function index()
    {
        $roles = $this->roleService->getAllRoles();
        return view('roles.index',compact('roles'));
    }
    public function create()
    {
        $roles = $this->roleService->getAllRoles();
        $permissions = $this->permissionService->getAllPermission();

        return view('roles.create',compact('roles','permissions'));
    }

    public function edit($id)
    {
        $role = $this->roleService->findUserById($id);
        return view('roles.edit',compact('role'));
    }

    public function store(RoleRequest $request)
    {
        $this->roleService->createRole($request);
        return redirect()->route('roles.index')->with('success', 'Role created successfully.');
    }

    public function update(RoleRequest $request, $id)
    {
        $this->roleService->updateRole($request, $id);
        return redirect()->route('roles.index')->with('success','Role updated successfully.');
    }

    public function destroy($id)
    {
        $this->roleService->deleteRole($id);
        return redirect()->back()->with('success','Role delete successfully!');
    }
}
