<?php

namespace App\Repositories\Eloquents;

use App\Repositories\Contracts\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class BaseRepository implements BaseRepositoryInterface
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function getAll()
    {
        return $this->model->all();
    }

    public function getById($id)
    {
        return $this->model->find($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $record = $this->model->find($id);

        if ($record) {
            $record->update($data);

            return $record;
        }

        return null;
    }

    public function delete($id)
    {
        $record = $this->model->find($id);

        if ($record) {
            $record->delete();
            
            return true;
        }

        return false;
    }

    protected function query(): Builder
    {
        return $this->model->newQuery();
    }
}