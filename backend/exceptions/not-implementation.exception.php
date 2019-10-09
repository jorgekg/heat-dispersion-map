<?php

class NotImplmentationException extends Exception {

    public function __construct()
    {
        $this->message = json_encode([
            'error' => 'Not found this endpoint',
            'code' => 404
        ]);
        $this->code = 404;
    }

}