<?php

/** @table = user, @database = platform */
class User
{

    /** @id = true, @type = int, @auto = true */
    public $id;

    /** @type = varchar, @max = 50 */
    public $name;

    /** @type = varchar, @max = 255 */
    public $email;

    /** @type = varchar, @max = 255 */
    public $document;

    /** @type = varchar, @max = 255 */
    public $password;

    /** @type = int, @nullable = true, @target = company*/
    public $companyId;

    /** @type = datetime, @nullable = true */
    public $createAt;

    /** @type = datetime, @nullable = true, @default = date() */
    public $updateAt;
}
