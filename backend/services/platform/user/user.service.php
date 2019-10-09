<?php

require_once __DIR__ . '/../../../exceptions/not-found.exception.php';
require_once __DIR__ . '/../../../repositories/platform/user/user.repository.php';
require_once __DIR__ . '/../../service.php';

class UserService extends Service {

    public function __construct()
    {
        $this->repository = new UserRepository();
    }

}