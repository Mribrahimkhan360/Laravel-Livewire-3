<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoleRequest;
use App\Services\RoleService;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    protected $service;

    public function __construct(PermissionService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $permissions = $this->service->getAllPermissions();
        return view('permissions.index',compact('permissions'));
    }
    public function create()
    {
        return view('permissions.create');
    }

    public function edit($id)
    {
        $permissions = $this->service->findUserById($id);
        return view('permissions.edit',compact('permissions'));
    }

    public function store(RoleRequest $request)
    {
        $this->service->createRole($request);
        return redirect()->route('permissions.index')->with('success', 'Permissions created successfully.');
    }

    public function update(RoleRequest $request, $id)
    {
        $this->service->updateRole($request, $id);
        return redirect()->route('permissions.index')->with('success','Permissions updated successfully.');
    }

    public function destroy($id)
    {
        $this->service->deleteRole($id);
        return redirect()->back()->with('success','Permissions delete successfully!');
    }
}
