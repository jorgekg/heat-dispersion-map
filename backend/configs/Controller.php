<?php

class Controller
{

  private static $endpoints = [];

  public static function get($endpoint, $callback, $publicApi = false)
  {
    self::abstractEndpoint('GET', $endpoint, $callback, $publicApi);
  }

  public static function post($endpoint, $callback, $publicApi = false)
  {
    self::abstractEndpoint('POST', $endpoint, $callback, $publicApi);
  }

  private static function abstractEndpoint($method, $endpoint, $callback, $publicApi = false)
  {
    $abstractEndpoint = new Api();
    $abstractEndpoint->method = $method;
    $abstractEndpoint->endpoint = $endpoint;
    $abstractEndpoint->callback = $callback;
    $abstractEndpoint->publicApi = $publicApi;
    self::$endpoints[] = $abstractEndpoint;
  }

  public function send($body, $code = 200)
  {
    http_response_code($code);
    echo json_encode($body);
  }

  public static function map()
  {
    try {
      $path = !empty($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '/';
      $paths = explode('/', $path);
      if (empty($paths[1])) {
        unset($paths[1]);
      }
      foreach (self::$endpoints as $endpoint) {
        $endpointPaths =  explode('/', $endpoint->endpoint);
        if (empty($endpointPaths[1])) {
          unset($endpointPaths[1]);
        }
        if (count($endpointPaths) == count($paths) && $_SERVER['REQUEST_METHOD'] == $endpoint->method) {
          $params = [];
          for ($i = 0; $i < count($endpointPaths); $i++) {
            if (count(explode(':', $endpointPaths[$i])) > 1) {
              $params[] = $paths[$i];
            }
          }
          $call = $endpoint->callback;
          $call($params, $_GET, json_decode(file_get_contents('php://input', true)), new Controller());
          return;
        }
      }
    } catch (Exception $e) {
      echo $e->getMessage();
      exit;
    }
  }
}
