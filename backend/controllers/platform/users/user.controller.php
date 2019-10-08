<?php

require_once __DIR__ . '/../../../services/platform/user/user.service.php';

class UserController {

    private $userService;

    public function __construct()
    {
        $this->userService = new UserService();
    }

    public function create($queries = null, $body = null, $headers = null) {
        return $this->userService.create($body);
    }

}