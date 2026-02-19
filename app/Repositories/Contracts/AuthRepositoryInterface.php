<?php


namespace App\Repositories\Contracts;


interface AuthRepositoryInterface
{
    public function findByEmail(string $email);
}
