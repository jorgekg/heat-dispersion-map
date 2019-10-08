<?php

$rules = [
  '/api/login' => [LoginController::class, 'platform/users/login.controller.php'],
  '/api/users' => [UserController::class, 'platform/users/user.controller.php']
];