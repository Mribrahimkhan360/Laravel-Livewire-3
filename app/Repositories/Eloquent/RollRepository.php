<?php


namespace App\Repositories\Eloquent;
use App\Models\Role;
use App\Repositories\Contracts\RollRepositoryInterface;

class RollRepository implements RollRepositoryInterface
{
    protected $model;

    public function __construct(Role $roll)
    {
        $this->model = $roll;
    }

    public function all()
    {
        return $this->model->all();
    }

    public function find($id)
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $roll = $this->find($id);
        $roll->update($data);
        return $roll;
    }

    public function delete($id)
    {
        $roll = $this->find($id);
        return $roll->delete();
    }
}
