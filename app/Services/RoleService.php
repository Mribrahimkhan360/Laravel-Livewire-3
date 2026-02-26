<?php


namespace App\Services;


use App\Repositories\Contracts\RoleRepositoryInterface;
use Illuminate\Http\Request;

class RoleService
{
    protected $repo;
    public function __construct(RoleRepositoryInterface $rollRepository)
    {
        $this->repo = $rollRepository;
    }

    public function getAllRoles()
    {
        return $this->repo->all();
    }

    public function getRole($id)
    {
        return $this->repo->find($id);
    }

    public function createRole(Request $request)
    {
        return $this->repo->create($request->only('name','guard_name'));
    }

    public function updateRole(Request $request, $id)
    {
        return $this->repo->update($id, $request->only('name','guard_name'));
    }

    public function deleteRole($id)
    {
        return $this->repo->delete($id);
    }
}
