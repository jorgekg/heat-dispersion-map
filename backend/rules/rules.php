<?php

$rules = [
  '/api/login' => [LoginController::class, 'generateToken', 'platform/users/login.controller.php']
];