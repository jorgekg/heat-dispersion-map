<?php

include '../rules/rules.php';

$path = !empty($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '/';
for($i = 0; $i < count($rules); $i++) {
  if ($rules[$i][3]) {
    require_once __DIR__ . "/../controllers/" . $rules[$i][0];
    break;
  }
}