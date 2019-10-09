<?php

class NotImplmentationException extends Exception {

    public function __construct()
    {
        $this->message = json_encode([
            'error' => 'This request not result data',
            'code' => 404
        ]);
        $this->code = 404;
    }

}