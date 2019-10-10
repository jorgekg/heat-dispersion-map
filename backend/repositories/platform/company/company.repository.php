<?php

require_once __DIR__ . '/../../repository.php';
require_once __DIR__ . '/../../../models/platform/company/company.model.php';
require_once __DIR__ . '/../../../models/platform/company/company.model.php';

class CompanyRepository extends Repository
{
    public function __construct()
    {
        parent::__construct(Company::class);
    }
}
