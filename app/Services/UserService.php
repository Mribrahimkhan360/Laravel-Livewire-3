<?php


namespace App\Services;


use App\Repositories\Contracts\UserRepositoryInterface;

class UserService
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function createUser(array $data)
    {
        // যদি flag না দেওয়া হয়, default হবে Admin
        if (!isset($data['flag'])) {
            $data['flag'] = 'admin';
        }

        // flag শুধু "Admin" বা "custom_user" হতে পারবে
        if (!in_array($data['flag'], ['admin', 'custom_user'])) {
            $data['flag'] = 'admin';
        }

        return $this->userRepository->store($data);
    }

    // Fetch all users
    public function getAllUsers()
    {
        return $this->userRepository ->all();
    }
    public function deleteUser($id)
    {
        return $this->userRepository->delete($id);
    }
}
