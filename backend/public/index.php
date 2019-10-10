<?php

require_once '../configs/database.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
  http_response_code(200);
  exit;
}

/**
 * $startMicrotime int
 * this variable is reposibility for
 * calculate millis to request
 */
$startMicrotime = microtime(true);

/**
 * import of rules
 * $rules => accept routes api for requests
 * $publicPost => routes not require token 
 */
include '../rules/rules.php';


require_once __DIR__ . '/../services/platform/metrics/metrics.service.php';
require_once __DIR__ . '/../configs/utils.php';
require_once __DIR__ . '/../configs/database.php';
require_once __DIR__ . '/../configs/authorization.php';

/**
 * $pathStr string
 * endpoint of request
 */
$pathStr = '';

try {

  /** 
   * $paths array
   * entpoint captured of request
   */
  $paths = explode('/', (!empty($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '/'));

  $rule = null;

  /**
   * discovery request endpoint
   * utility $rules
   */
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
    throw new NotImplmentationException();
  }

  /**
   * create instance of rule
   * load class reposibility
   */
  require_once __DIR__ . '/../controllers/' . $rule[1];

  /**
   * create instance to endpoint
   * $rule[0] => class name of request
   */
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
      if ((!isset($publicPost[$route]) && $_SERVER['REQUEST_METHOD'] == 'POST') || (!isset($publicGet[$route]) && $_SERVER['REQUEST_METHOD'] == 'GET')) {
        if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
          (new Authorization())->createContext();
        } else {
          throw new ForbiddenException('You don\'t have permission for it');
        }
      }
      $callMethod = $method->name;
      $response = $instance->$callMethod($_SERVER['REQUEST_METHOD'] == 'POST'
        ? json_decode(file_get_contents('php://input'), true)
        : $_GET, $_SERVER);
      echo json_encode($response);
      http_response_code(200);
      $hasEndpoint = true;
      break;
    }
  }
  if (!$hasEndpoint) {
    throw new NotImplmentationException();
  }
  Database::commit();
} catch (Exception $e) {
  Utils::printError($e);
  Database::rollback();
}

try {
  // start analictis
  $metrics = new MetricsService();
  $metrics->analyze($path, $startMicrotime);
  Database::commit();
} catch (Exception $e) {
  // ignore error metrics
  Database::rollback();
}
