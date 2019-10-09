<?php

abstract class Controller {

    protected $class;
    protected $service;

    /** @method = get, @route = / */
    public function get($query = null, $headers = null) {
        $reflaction = new ReflectionClass($this);
        if ($reflaction->hasMethod('getOverride')) {
            return $this->getOverride($query, $headers);
        } else {
            $id = isset($query['id']) ? $query['id'] : 0;
            return $this->service->findById($id);
        }
    }

    /** @method = get, @route = /list */
    public function list($query = null, $headers = null) {
        $reflaction = new ReflectionClass($this);
        if ($reflaction->hasMethod('listOverride')) {
            return $this->listOverride($query, $headers);
        } else { 
            $pageAndSize = Utils::getPageAndSize($query);
            return $this->service->list($pageAndSize[0], $pageAndSize[1]);
        }
    }

    /** @method = post, @route = / */
    public function create($data = null, $headers = null) {
        $reflaction = new ReflectionClass($this);
        if ($reflaction->hasMethod('createOverride')) {
            return $this->createOverride(Utils::instanceClass($this->class, $data), $headers);
        } else {
            return $this->service.create(Utils::instanceClass($this->class, $data));
        }
    }

}