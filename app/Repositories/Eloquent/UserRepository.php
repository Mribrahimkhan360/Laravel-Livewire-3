<?php


namespace App\Repositories\Eloquent;


use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    public function store(array $data)
    {
        return User::create($data);
    }
    public function all()
    {
        return User::all();
    }
    public function find($id)
    {
        return User::findOrFail($id);
    }
    public function delete($id)
    {
        $user = $this->find($id);
        return $user->delete();
    }
//    public function findUserById($id)
//    {
//        return User::findOrFail($id);
//    }
    public function update($id, array $data)
    {
        $user = $this->find($id);
        return $user->update($data);
    }
}
