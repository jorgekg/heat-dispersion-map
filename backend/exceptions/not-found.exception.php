<?php

class NotFoundException extends Exception {

    public function __construct($messagem)
    {
        $this->message = $messagem;
        $this->code = 404;
    }

}