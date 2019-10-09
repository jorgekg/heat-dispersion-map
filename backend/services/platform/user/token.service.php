<?php

require_once __DIR__ . '/../../service.php';
require_once __DIR__ . '/../../../repositories/platform/user/token.repository.php';
require_once __DIR__ . '/../../../models/platform/user/user.model.php';
require_once __DIR__ . '/../../../exceptions/forbidden.exception.php';
require_once __DIR__ . '/user.service.php';

class TokenService extends Service {

    private $userService;
    
    public function __construct()
    {
        $this->repository = new TokenRepository();
        $this->userService = new UserService();
    }

    public function createLogin(User $user) {
        $userFinded = $this->userService->findByEmail($user->email);
        if ($user->password == $userFinded->password) {
            $key = $this->generaToken($user->email, $user->password);
            $token = new Token(null, $key, $key, $user->companyId, date('y-m-d h:i:s'));
            return $this->repository->insert($token);
        }
        throw new ForbiddenException();
    }

    private function generaToken($email, $password) {
        $sha1 = sha1($email . $password);
        return sha1((new DateTime())->getTimestamp() . $sha1);
    }

}