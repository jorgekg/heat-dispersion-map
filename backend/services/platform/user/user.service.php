<?php

require_once __DIR__ . '/../../../repositories/platform/user/user.repository.php';

class UserService {

    private $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }

    public function create(User $user) {
        return $this->userRepository->create($user);
    } 

}