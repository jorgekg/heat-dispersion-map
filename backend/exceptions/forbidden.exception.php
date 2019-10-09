<?php

class ForbiddenException extends Exception {

    public function __construct()
    {
        $this->message = json_encode([
            "message" => "E-mail or password incorrect",
            "code" => 403
        ]);
        $this->code = 403;
    }

}