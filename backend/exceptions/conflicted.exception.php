<?php

class ConflictedException extends Exception
{

    public function __construct($message)
    {
        $this->message = json_encode([
            "message" => $message,
            "code" => 409
        ]);
        $this->code = 409;
    }
}
