<?php

/** @table = metrics, @database = platform */
class Metrics {

    /** @id = true, @auto = true, @type = BIGINT */
    public $id;

    /** @type = varchar, @max = 255, nullable = true */
    public $endpoint;

    /** @type = decimal, @max = 16, @decimal = 10, nullable = true */
    public $millis;

    /** @type = varchar, @max = 255, nullable = true */
    public $browser;

    /** @type = varchar, @max = 255, nullable = true */
    public $platform;

    /** @type = datetime, nullable = true */
    public $createAt;

}