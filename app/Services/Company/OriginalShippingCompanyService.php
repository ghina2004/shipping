<?php

namespace App\Services\Company;

use App\Models\OriginalShippingCompany;

class OriginalShippingCompanyService
{
    public function index()
    {
        return OriginalShippingCompany::get();
    }

    public function create(array $data)
    {
        return OriginalShippingCompany::create($data);
    }

    public function update(OriginalShippingCompany $company, array $data)
    {
        $company->update($data);
        return $company;
    }

    public function delete(OriginalShippingCompany $company)
    {
        return $company->delete();
    }

    public function show(OriginalShippingCompany $company)
    {
        return $company;
    }
}
