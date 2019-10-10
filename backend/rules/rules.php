<?php

$rules = [
  '/api/login' => [TokenController::class, 'platform/users/token.controller.php'],
  '/api/users' => [UserController::class, 'platform/users/user.controller.php'],
  '/api/companies' => [CompanyController::class, 'platform/company/company.controller.php']
];

$publicPost = [
  '/api/login' => true,
  '/api/users' => true
];

$publicGet = [
  
];