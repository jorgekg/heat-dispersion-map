<?php

abstract class Service {

    protected $repository;

    public function create($data) {
        return $this->repository->create($data);
    } 

    public function list($page = 0, $size = 10) {
        return $this->repository->list($page, $size);
    }

    public function findById($id) {
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