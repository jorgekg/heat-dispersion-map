<?php

/** @table = token, @database = platform */
class Token
{

    public function __construct($id = null, $userId = null, $token = null, $companyId = null, $createAt = null)
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->token = $token;
        $this->companyId = $companyId;
        $this->createAt = $createAt;
    }

    /** @id = true, @auto = true, @type = BIGINT */
    public $id;

    /** @type = int, @target = user*/
    public $userId;

    /** @type = varchar, @max = 255 */
    public $token;

    /** @type = int, @target = company, @nullable = true */
    public $companyId;

    /** @type = datetime, @nullable = true */
    public $createAt;
}
