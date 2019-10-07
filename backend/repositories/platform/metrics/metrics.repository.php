<?php

require_once __DIR__ . '/../../repository.php';
require_once __DIR__ . '/../../../models/platform/metrics/metrics.model.php';

class MetricsRepository extends Repository {

    public function __construct($database)
    {
        $this->database = $database;
        $instance = new ReflectionClass(Metrics::class);
        $this->table = Utils::getAnnotation($instance->getDocComment(), '@table');
    }

}