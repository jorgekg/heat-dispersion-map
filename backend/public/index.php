<?php

error_reporting(1);
set_time_limit(60);

include '../rules/rules.php';
require_once '../services/metrics/metrics.service.php';

// Starting metrics analitics
$metrics = new MetricsService();

try {
  // get endpoit
  $path = !empty($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '/';
  // loop into endpoints
  for($i = 0; $i < count($rules); $i++) {
    // verify endpoint exists
    if ($rules[$i][0] == $path) {
      // load controller
      require_once __DIR__ . '/../controllers/' . $rules[$i][3];
      // create instance of controller
      $class = new $rules[$i][1]();
      // call to response function
      $response = $class->$rules[$i][2]($_GET, $_POST, $_SERVER);
      // print json
      echo json_encode($response);
      // set http return code
      http_response_code(200);
      // stop loop
      break;
    }
  }
} catch (Exception $e) {
  print_r($e);
}

try {
  // start analictis
  $metrics->analyze();
} catch (Exception $e) {
  // ignore error!
}