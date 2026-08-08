<?php

namespace App\Services;

abstract class BaseService
{
    protected $repository;

    public function __construct($repository)
    {
        $this->repository = $repository;
    }

    public function getAll()
    {
        return $this->repository->getAll();
    }

    public function find($id)
    {
        return $this->repository->find($id);
    }

    public function findOrFail($id)
    {
        return $this->repository->findOrFail($id);
    }

    public function create(array $data)
    {
        return $this->repository->create($data);
    }

    public function update($id, array $data)
    {
        $model = $this->repository->findOrFail($id);

        return $this->repository->update($model, $data);
    }

    public function delete($id)
    {
        $model = $this->repository->findOrFail($id);

        return $this->repository->delete($model);
    }
}
