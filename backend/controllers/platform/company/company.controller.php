<?php

require_once __DIR__ . '/../../controller.php';
require_once __DIR__ . '/../../../services/platform/company/company.service.php';
require_once __DIR__ . '/../../../models/platform/company/company.model.php';

class CompanyController extends Controller
{
    public function __construct()
    {
        $this->service = new CompanyService();
        $this->class = Company::class;
    }
}
