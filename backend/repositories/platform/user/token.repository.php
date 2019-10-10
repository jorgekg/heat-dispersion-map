<?php

require_once __DIR__ . '/../../repository.php';
require_once __DIR__ . '/../../../models/platform/user/token.model.php';
require_once __DIR__ . '/../../../configs/utils.php';
require_once __DIR__ . '/../../../exceptions/forbidden.exception.php';

class TokenRepository extends Repository
{

    public function __construct()
    {
        parent::__construct(Token::class);
    }

    public function findByToken($token)
    {
        $instance = (Database::instance($this->database));
        $stmt = $instance->prepare("SELECT * FROM " . $this->table ." WHERE token = ?");
        $stmt->bindValue(1, $token);
        $stmt->execute();
        $tokenObject = $stmt->fetch(PDO::FETCH_ASSOC);
        if (empty($tokenObject)) {
            throw new ForbiddenException('You token is not valid');
        } 
        return Utils::instanceClass($this->class, $tokenObject);
    }
}
