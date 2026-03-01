<?php


namespace App\Services;


use App\Repositories\Contracts\PermissionRepositoryInterface;
use Illuminate\Http\Request;

class PermissionService
{
    protected $repo;
    public function __construct(PermissionRepositoryInterface $permissionRepository)
    {
        $this->repo = $permissionRepository;
    }

    public function getAllPermission()
    {
        return $this->repo->all();
    }

    public function findPermissionById($id)
    {
        return $this->repo->find($id);
    }
//    public function getPermission($id)
//    {
//        return $this->repo->find($id);
//    }

    public function createPermission(Request $request)
    {
        return $this->repo->create([
            'name' => $request->name,
            'guard_name' => 'null', // ✅ manually set
        ]);
    }

    public function updatePermission(Request $request, $id)
    {
        return $this->repo->update($id, [
            'name' => $request->name,
            'guard_name' => 'null',
        ]);
    }

    public function deletePermission($id)
    {
        return $this->repo->delete($id);
    }

}
