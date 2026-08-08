<?php

namespace App\Repositories;

use App\Models\Marca;

interface MarcaRepositoryInterface
{
    public function getAllOrdered();

    public function findOrFail($id);

    public function create(array $data);

    public function update($model, array $data);

    public function delete($model);
}
