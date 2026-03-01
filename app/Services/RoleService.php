<?php


namespace App\Services;


use App\Repositories\Contracts\PermissionRepositoryInterface;
use App\Repositories\Contracts\RoleRepositoryInterface;
use Illuminate\Http\Request;

class RoleService
{
    protected $roleRepository;
    protected $permissionRepository;
    public function __construct(RoleRepositoryInterface $roleRepository,PermissionRepositoryInterface $permissionRepository)
    {
        $this->roleRepository        = $roleRepository;
        $this->permissionRepository  = $permissionRepository;

    }

    public function getAllRoles()
    {
        return $this->roleRepository->all();
    }
    public function findUserById($id)
    {
        return $this->roleRepository->find($id);
    }

//    public function getRole($id)
//    {
//        return $this->repo->find($id);
//    }

    public function createRole(Request $request,$roleId,$permissionIds)
    {
        $role = $this->roleRepository->find($roleId);
        $role->syncPermissions($permissionIds);
        return $this->roleRepository->create([
            'name' => $request->name,
            'guard_name' => 'null', // ✅ manually set
        ]);

    }

    public function updateRole(Request $request, $id, $roleId,$permissionIds)
    {
//        $role = $this->roleRepository->find($roleId);
//        $role->syncPermissions($permissionIds);

        return $this->roleRepository->update($id, [
            'name' => $request->name,
            'guard_name' => 'null',
        ]);
    }

    public function deleteRole($id)
    {
        return $this->roleRepository->delete($id);
    }


//    public function assignPermissionsToRole($roleId, array $permissionIds)
//    {
//        $role = $this->roleRepository->find($roleId);
//
//        // Spatie method: syncPermissions
//        $role->syncPermissions($permissionIds);
//    }
}
