<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\PermissionRequest;
use App\Http\Requests\RoleRequest;
use App\Services\PermissionService;
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
        $permissions = $this->service->getAllPermission();
        return view('permissions.index',compact('permissions'));
    }
    public function create()
    {
        return view('permissions.create');
    }

    public function edit($id)
    {
        $permissions = $this->service->findPermissionById($id);
        return view('permissions.edit',compact('permissions'));
    }

    public function store(PermissionRequest $request)
    {
        $this->service->createPermission($request);
        return redirect()->route('permissions.index')->with('success', 'Permissions created successfully.');
    }

    public function update(PermissionRequest $request, $id)
    {
        $this->service->updatePermission($request, $id);
        return redirect()->route('permissions.index')->with('success','Permissions updated successfully.');
    }

    public function destroy($id)
    {
        $this->service->deletePermission($id);
        return redirect()->back()->with('success','Permissions delete successfully!');
    }
}
