<?php

class MetricsService {

    private $startMicrotime;
    private $system;

    public function __contruct() {
        $this->startMicrotime = microtime(true);
        $this->system = get_browser(null, true);
    }

    public function analyze() {
        $millis = microtime(true) - $this->startMicrotime;
        $browser = $this->system['browser'];
        $platform = $this->system['platform'];
    }

}