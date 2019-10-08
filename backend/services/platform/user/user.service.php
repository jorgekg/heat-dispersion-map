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

    public function list($page = 0, $size = 10) {
        return $this->userRepository->list($page, $size);
    }

    public function findById($id) {
        return $this->userRepository->findById($id);
    }

}