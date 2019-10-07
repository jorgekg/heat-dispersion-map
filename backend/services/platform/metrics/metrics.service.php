<?php

require_once __DIR__ . '/../../../repositories/platform/metrics/metrics.repository.php';

class MetricsService {

    private $system;

    public function analyze($database, $endpoint, $startMicroTime) {
        $repository = new MetricsRepository($database);
        $class = new Metrics();

        $this->system = get_browser(null, true);
        $class->millis = (microtime(true) - $startMicroTime);
        $class->browser = $this->system['browser'];
        $class->platform = $this->system['platform'];

        $class->endpoint = $endpoint; 
        $repository->create($class);
    }

}