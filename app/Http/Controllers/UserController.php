<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserStoreRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $userService;
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    //
    public function index()
    {
        $users = $this->userService->getAllUsers();
        return view('users.index',compact('users'));
    }
    public function create()
    {
        return view('users.create');
    }

    public function store(UserStoreRequest $request)
    {
        $this->userService->createUser($request->validated());
        return redirect()->route('users.index')->with('success','User Create Successfully');



//        $data = $request->only(['name', 'email', 'password', 'flag']);
//        $data['password'] = bcrypt($data['password']);
//
//        $user = $this->userService->createUser($data);
//
//        return response()->json($user, 201);
    }

    public function show()
    {

    }

    public function edit($id)
    {
        $user = $this->userService->findUserById($id);
        return view('users.edit',compact('user'));
    }

    public function update(UserStoreRequest $request, $id)
    {
        $data = $request->validated();
        $this->userService->updateUser($id,$data);
        return redirect()->back()->with('success','Product updated successfully.');
    }

    public function destroy($id)
    {
        $this->userService->deleteUser($id);
        return redirect()->back()->with('success','User delete successfully!');
    }
}
