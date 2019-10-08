<?php

class Database
{
    private static $instancePull;

    public static function instance($database = 'platform') {
        if (isset(self::$instancePull[$database])) {
            return self::$instancePull[$database];
        }
        self::$instancePull[$database] = new PDO("mysql:host=localhost;dbname={$database}", "root", "");
        self::$instancePull[$database]->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
        self::$instancePull[$database]->setAttribute( PDO::ATTR_EMULATE_PREPARES, FALSE );
        return self::$instancePull[$database];
    }
}
