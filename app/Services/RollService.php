<?php


namespace App\Services;


use App\Repositories\Contracts\RollRepositoryInterface;
use Illuminate\Http\Request;

class RollService
{
    protected $repo;
    public function __construct(RollRepositoryInterface $rollRepository)
    {
        $this->repo = $rollRepository;
    }

    public function getAllRolls()
    {
        return $this->repo->all();
    }

    public function getRoll($id)
    {
        return $this->repo->find($id);
    }

    public function createRoll(Request $request)
    {
        return $this->repo->create($request->only('name','guard_name'));
    }

    public function updateRoll(Request $request, $id)
    {
        return $this->repo->update($id, $request->only('name','guard_name'));
    }

    public function deleteRoll($id)
    {
        return $this->repo->delete($id);
    }
}
