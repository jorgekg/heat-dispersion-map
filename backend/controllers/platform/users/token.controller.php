<?php

require_once __DIR__ . '/../../controller.php';
require_once __DIR__ . '/../../../services/platform/user/token.service.php';
require_once __DIR__ . '/../../../models/platform/user/token.model.php';
require_once __DIR__ . '/../../../models/platform/user/user.model.php';

class TokenController extends Controller {

    public function __construct()
    {
        $this->service = new TokenService();
        $this->class = Token::class;
    }

    public function createOverride(User $user, $header) {
      return $this->service->create;
    }

    public function getOverride($query, $header) {
      throw new NotImplmentationException();
    }

    public function listOverride($query, $header) {
      throw new NotImplmentationException();
    }

}