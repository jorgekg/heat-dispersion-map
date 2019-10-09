<?php

require_once __DIR__ . '/../../repository.php';
require_once __DIR__ . '/../../../models/platform/user/token.model.php';

class TokenRepository extends Repository {

    public function __construct()
    {
        parent::__construct(Token::class);
    }

}