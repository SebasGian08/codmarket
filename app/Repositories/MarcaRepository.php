<?php

namespace App\Repositories;

use App\Models\Marca;

class MarcaRepository extends BaseRepository implements MarcaRepositoryInterface
{
    protected function modelClass()
    {
        return Marca::class;
    }

    public function getAllOrdered()
    {
        return $this->model->orderBy('orden', 'asc')->get();
    }
}
