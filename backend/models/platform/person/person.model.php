<?php

/** @table = person, @database = platform */
class Person
{
    /** @id = true, @type = BIGINT, @auto = true */
    public $id;

    /** @type = varchar, @max = 50, nullable = true */
    public $name;

    /** @type = int, nullable = true*/
    public $age;

    /** @type = int, nullable = true*/
    public $gender;

    /** @type = datetime, @nullable = true */
    public $createAt;

    /** @type = datetime, @nullable = true, @default = date() */
    public $updateAt;
}
