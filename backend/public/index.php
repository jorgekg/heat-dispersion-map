<?php

$startMicrotime = microtime(true);

include '../rules/rules.php';
require_once __DIR__ . '/../services/platform/metrics/metrics.service.php';
require_once __DIR__ . '/../configs/utils.php';
require_once __DIR__ . '/../configs/database.php';

$path = '';

try {
  // get endpoit
  $path = !empty($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '/';
  $rule = isset($rules[$path]) ? $rules[$path] : null;
  if (!empty($rule)) {
    // load controller
    require_once __DIR__ . '/../controllers/' . $rule[2];
    
    // create instance of controller
    $class = $rule[0];
    $instance = new $class();

    // call to response function
    $method = $rule[1];
    $response = $instance->$method($_GET, $_POST, $_SERVER);
    
    // repsonse requests
    echo json_encode($response);
    http_response_code(200);
  }
} catch (Exception $e) {
  print_r($e);
}

try {
  // start analictis
  $metrics = new MetricsService();
  $metrics->analyze($path, $startMicrotime);
} catch (Exception $e) {
  // ignore error metrics
}
