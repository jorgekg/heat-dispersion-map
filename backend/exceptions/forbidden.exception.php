<?php

class ForbiddenException extends Exception {

    public function __construct($message = null)
    {
        $this->message = json_encode([
            "message" => $message ? $message : "E-mail or password incorrect",
            "code" => 403
        ]);
        $this->code = 403;
    }

}