<?php


namespace App\Repositories\Contracts;


interface UserRepositoryInterface
{
    public function store(array $data);
    public function all();
    public function find($id);
    public function update($id, array $data);
    public function delete($id);
}
