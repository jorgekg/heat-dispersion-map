<?php

require_once __DIR__ . '/../services/platform/user/token.service.php';
require_once __DIR__ . '/../models/platform/user/user.model.php';

class Authorization {

    private static $authorization;

    public function createContext() {
        $tokenService = new TokenService();
        self::$authorization[self::getToken()] = $tokenService->validateToken(self::getToken());
    }

    public static function getContext(): User {
        return self::$authorization[self::getToken()];
    }

    public static function getToken() {
        return $_SERVER['HTTP_AUTHORIZATION'];
    }

}