<?php

/** @table = company, @database = platform */
class Company
{
    /** @id = true, @auto = true, @type = int */
    public $id;

    /** @type = varchar, @max = 255 */
    public $name;

    /** @type = varchar, @max = 255, nullable = true */
    public $fantasyName;

    /** @type = datetime, @nullable = true */
    public $createAt;

    /** @type = datetime, @nullable = true, @default = date() */
    public $updateAt;
}
