<?php

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
        foreach ($$fields as $field) {
            $instance->$field = isset($data[$field]) ? $data[$field] : null;
        }
        return $instance;
    }

    public static function getPageAndSize($query) {
        return [(isset($query['page']) ? $query['page'] : 0), (isset($query['offset']) ? $query['offset'] : 10)];
    }

}