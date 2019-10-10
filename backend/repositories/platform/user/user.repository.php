<?php

require_once __DIR__ . '/../../repository.php';
require_once __DIR__ . '/../../../models/platform/user/user.model.php';
require_once __DIR__ . '/../../../exceptions/not-found.exception.php';

class UserRepository extends Repository {

    public function __construct()
    {
        parent::__construct(User::class);
    }

    public function findByEmail($email): User {
        $stmt = (Database::instance($this->database))->prepare('SELECT * FROM ' . $this->table . ' WHERE email = ?');
        $stmt->bindValue(1, $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if (empty($user)) {
            throw new NotFoundException('Email not exists');
        }
        return Utils::instanceClass($this->class, $user);
    }

}