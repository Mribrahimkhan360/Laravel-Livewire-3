<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\RollRequest;
use App\Services\RollService;
use Illuminate\Http\Request;

class RollController extends Controller
{
    protected $service;

    public function __construct(RollService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $rolls = $this->service->getAllRolls();
        return view('rolls.index',compact('rolls'));
    }
    public function create()
    {
        return view('rolls.create');
    }
    public function store(RollRequest $request)
    {
        $this->service->createRoll($request);
        return redirect()->route('rolls.index')->with('success', 'Role created successfully.');
    }
}
