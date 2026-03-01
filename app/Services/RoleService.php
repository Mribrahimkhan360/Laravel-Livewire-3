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
    public function findUserById($id)
    {
        return $this->repo->find($id);
    }

//    public function getRole($id)
//    {
//        return $this->repo->find($id);
//    }

    public function createRole(Request $request)
    {
        return $this->repo->create([
            'name' => $request->name,
            'guard_name' => 'null', // ✅ manually set
        ]);

    }

    public function updateRole(Request $request, $id)
    {
        return $this->repo->update($id, [
            'name' => $request->name,
            'guard_name' => 'null',
        ]);
    }

    public function deleteRole($id)
    {
        return $this->repo->delete($id);
    }
}
