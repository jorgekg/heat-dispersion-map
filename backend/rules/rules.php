<?php

$rules = [
  '/api/login' => [TokenController::class, 'platform/users/token.controller.php'],
  '/api/users' => [UserController::class, 'platform/users/user.controller.php']
];