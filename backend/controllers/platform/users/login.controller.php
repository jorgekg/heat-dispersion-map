<?php

require_once __DIR__ . '/../../../configs/utils.php';
require_once __DIR__ . '/../../../models/platform/user/user.model.php';
require_once __DIR__ . '/../../../services/platform/user/user.service.php';
require_once __DIR__ . '/../../controller.php';

class LoginController extends Controller {

    public function __construct()
    {
        $this->service = new UserService();
        $this->class = User::class;
    }

    public function createOverride(User $user, $header) {
      return 'ok';
    }

    public function getOverride($query, $header) {
      throw new NotImplmentationException();
    }

}