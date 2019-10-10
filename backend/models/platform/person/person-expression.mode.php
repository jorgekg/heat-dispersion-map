<?php

/** @table = personExpression, @database = platform */
class PersonExpression
{
    /** @id = true, @type = BIGINT, @auto = true */
    public $id;

    /** @type = varchar, @max = 50, nullable = true */
    public $expression;

    /** @type = int*/
    public $personId;

    /** @type = datetime, @nullable = true */
    public $createAt;
}
