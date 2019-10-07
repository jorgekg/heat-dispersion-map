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

}