<?php

require_once __DIR__ . '/../../repository.php';
require_once __DIR__ . '/../../../models/platform/user/user.model.php';

class UserRepository extends Repository {

    public function __construct()
    {
        parent::__construct(User::class);
    }

}