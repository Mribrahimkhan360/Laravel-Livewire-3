<?php


namespace App\Repositories\Eloquent;


use App\Models\Admin;
use App\Repositories\Contracts\AuthRepositoryInterface;

class AuthRepository implements AuthRepositoryInterface
{
    public function findByEmail(string $email)
    {
        return Admin::where('email',$email)->first();
    }
}
