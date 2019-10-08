<?php

require_once __DIR__ . '/../../repository.php';
require_once __DIR__ . '/../../../models/platform/metrics/metrics.model.php';

class MetricsRepository extends Repository {

    public function __construct()
    {
        parent::__construct(Metrics::class);
    }

}