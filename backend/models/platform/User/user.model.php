<?php

require_once __DIR__ . '/../platform.model.php';

/** @table = user */
class UserModel extends Platform {

    /** @type int */
    private $id;

    /** @type string */
    private $name;
    
    /** @type string */
    private $email;

    /** @type string */
    private $password;

    /** @type int */
    private $companyId;

}