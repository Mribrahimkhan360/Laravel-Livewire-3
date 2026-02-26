<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoleRequest;
use App\Services\RoleService;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    protected $service;

    public function __construct(RoleService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $roles = $this->service->getAllRoles();
        return view('roles.index',compact('roles'));
    }
    public function create()
    {
        return view('roles.create');
    }

    public function edit($id)
    {
        $role = $this->service->findUserById($id);
        return view('roles.edit',compact('role'));
    }

    public function store(RoleRequest $request)
    {
        $this->service->createRole($request);
        return redirect()->route('roles.index')->with('success', 'Role created successfully.');
    }

    public function update(RoleRequest $request, $id)
    {
        $this->service->updateRole($request, $id);
        return redirect()->route('roles.index')->with('success','Role updated successfully.');
    }

    public function destroy($id)
    {
        $this->service->deleteRole($id);
        return redirect()->back()->with('success','Role delete successfully!');
    }
}
