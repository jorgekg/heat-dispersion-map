<?php

require_once __DIR__ . '/../../../repositories/platform/metrics/metrics.repository.php';

class MetricsService {

    private $system;

    public function analyze($endpoint, $startMicroTime) {
        $repository = new MetricsRepository();
        $metrics = new Metrics();
        
        $this->system = get_browser(null, true);
        $metrics->millis = (microtime(true) - $startMicroTime);
        $metrics->browser = $this->system['browser'];
        $metrics->platform = $this->system['platform'];
        $metrics->createAt = date('Y-m-d h:i:s');
        $metrics->ip = $_SERVER['REMOTE_ADDR'];

        $metrics->endpoint = $endpoint; 
        $repository->create($metrics);
    }

}