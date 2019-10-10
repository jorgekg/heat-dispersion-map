<?php

/** @table = permission, @database = platform */
class Permission {

    /** @id = true, @type = int, @auto = true */
    public $id;

    /** @type varchar, @max = 255 */
    public $resource;

}