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
        self::$instancePull[$database]->beginTransaction();
        return self::$instancePull[$database];
    }

    public static function commit() {
        if (empty(self::$instancePull)) {
            return;
        }
        foreach (self::$instancePull as $pull) {
            $pull->commit();
        }
    }

    public static function rollback() {
        try {
            if (empty(self::$instancePull)) {
                return;
            }
            foreach (self::$instancePull as $pull) {
                $pull->rollback();
            }
        } catch (Exception $e) {

        }
    }
}
