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
        $rolls = $this->service->getAllRoles();
        return view('rolls.index',compact('rolls'));
    }
    public function create()
    {
        return view('rolls.create');
    }
    public function store(RoleRequest $request)
    {
        $this->service->createRole($request);
        return redirect()->route('rolls.index')->with('success', 'Role created successfully.');
    }
    public function destroy($id)
    {
        $this->service->deleteRole($id);
        return redirect()->back()->with('success','User delete successfully!');
    }
}
