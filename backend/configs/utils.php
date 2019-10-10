<?php

require_once __DIR__ . '/../exceptions/not-found.exception.php';
require_once __DIR__ . '/../exceptions/not-implementation.exception.php';

class Utils {

    public static function getAnnotation($annotations, $annotation)
    {
        foreach (explode(',', $annotations) as $str) {
            if (strpos($str, $annotation)) {
                return str_replace('=', '', str_replace(' ', '', str_replace($annotation, '', str_replace('*/', '', str_replace('/**', '', $str)))));
            }
        }
    }

    public static function instanceClass($class, $data) {
        $reflection = new ReflectionClass($class);
        $fields = $reflection->getProperties(ReflectionProperty::IS_PUBLIC);
        $instance = new $class();
        foreach ($fields as $field) {
            $property = $field->name;
            $instance->$property = isset($data[$property]) ? $data[$property] : null;
        }
        return $instance;
    }

    public static function getPageAndSize($query) {
        return [(isset($query['page']) ? $query['page'] : 0), (isset($query['offset']) ? $query['offset'] : 10)];
    }

    public static function printError($error) {
        try {
            echo $error->getMessage();
            // http_response_code($error->getCode());
        } catch (Exception $err) {
            http_response_code(500);
            print_r($err);
        }
    }

}