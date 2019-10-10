<?php

abstract class Service
{
    protected $repository;

    public function create($data)
    {
        $data->createAt = date('y-m-d h:i:s');
        $data = $this->repository->create($data);
        $reflaction = new ReflectionClass($this);
        if ($reflaction->hasMethod('afterCreate')) {
            $this->afterCreate($data);
        }
        return $data;
    }

    public function update($data) {
        $data->updateAt = date('y-m-d h:i:s');
        $data = $this->repository->update($data->id, $data);
        $reflaction = new ReflectionClass($this);
        if ($reflaction->hasMethod('afterUpdate')) {
            $this->afterUpdate($data);
        }
        return $data;
    }

    public function list($page = 0, $size = 10)
    {
        return $this->repository->list($page, $size);
    }

    public function findById($id)
    {
        $data = $this->repository->findById($id);
        if (empty($data)) {
            throw new NotFoundException(json_encode([
                'error' => 'No retreave result data',
                'code' => 404
            ]));
        }
        return $data;
    }
}
