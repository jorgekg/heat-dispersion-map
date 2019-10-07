<?php

class Database
{
    private $instancePull;

    public function instance($database = 'platform') {
        $this->instancePull = new PDO("mysql:host=localhost;dbname={$database}", "root", "");
        $this->instancePull->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
        $this->instancePull->setAttribute( PDO::ATTR_EMULATE_PREPARES, FALSE );
        return $this->instancePull;
    }

    public function close() {
        $this->instancePull->close();
    }
}
