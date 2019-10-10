<?php

require_once __DIR__ . '/../../../configs/utils.php';
require_once __DIR__ . '/../../../models/platform/user/user.model.php';
require_once __DIR__ . '/../../../services/platform/user/user.service.php';
require_once __DIR__ . '/../../../exceptions/conflicted.exception.php';
require_once __DIR__ . '/../../../exceptions/not-found.exception.php';
require_once __DIR__ . '/../../controller.php';

class UserController extends Controller
{
    public function __construct()
    {
        $this->service = new UserService();
        $this->class = User::class;
    }

    public function createValidation(User $user)
    {
        try {
            $this->service->findByEmail($user->email);
            throw new ConflictedException('This e-mail exists');
        } catch (ForbiddenException $e) {
            // permite inserir
        }
    }
}
