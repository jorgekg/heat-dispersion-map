<?php

$startMicrotime = microtime(true);

include '../rules/rules.php';
require_once __DIR__ . '/../services/platform/metrics/metrics.service.php';
require_once __DIR__ . '/../configs/utils.php';
require_once __DIR__ . '/../configs/database.php';

$pathStr = '';

try {
  // get endpoit
  $paths = explode('/', (!empty($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '/'));
  $rule = null;
  foreach ($paths as $path) {
    if (empty($path)) {
      continue;
    }
    $pathStr .= '/' . $path;
    $rule = isset($rules[$pathStr]) ? $rules[$pathStr] : null;
    if (!empty($rule)) {
      break;
    }
  }
  if (empty($rule)) {
    http_response_code(404);
  }
  if (!empty($rule)) {
    require_once __DIR__ . '/../controllers/' . $rule[1];
    
    // create instance of controller
    $class = $rule[0];
    $instance = new $class();

    $callMethod = null;
    $hasEndpoint = false;
    $reflection = new ReflectionClass($instance);
    foreach ($reflection->getMethods() as $method) {
      $annotation = $reflection->getMethod($method->name)->getDocComment();
      $endpoint = Utils::getAnnotation($annotation, '@route');
      $methodRequest = Utils::getAnnotation($annotation, '@method');
      $route = $pathStr . ($endpoint == '/' ? '' : $endpoint);
      if ($_SERVER['PATH_INFO'] == $route && strtoupper($methodRequest) == $_SERVER['REQUEST_METHOD']) {
        $callMethod = $method->name;
        $response = $instance->$callMethod($_SERVER['REQUEST_METHOD'] == 'POST' ? $_POST : $_GET, $_SERVER);
        echo json_encode($response);
        http_response_code(200);
        $hasEndpoint = true;
        break;
      }
    }
    if (!$hasEndpoint) {
      http_response_code(404);
    }
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
