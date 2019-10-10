<?php

require_once __DIR__ . '/../../service.php';
require_once __DIR__ . '/../../../repositories/platform/company/company.repository.php';
require_once __DIR__ . '/../user/user.service.php';
require_once __DIR__ . '/../../../models/platform/company/company.model.php';

class CompanyService extends Service
{
    private $userService;

    public function __construct()
    {
        $this->repository = new CompanyRepository();
        $this->userService = new UserService();
    }

    public function afterCreate(Company $company)
    {
        $user = Authorization::getContext();
        $user->companyId = $company->id;
        $this->userService->update($user);
    }
}
