<?php

namespace Dgtlinf\Payslip;

use Dgtlinf\Payslip\Providers\BasePayslipProvider;
use InvalidArgumentException;

class PayslipGenerator
{

    public function for(array $salaryData): BasePayslipProvider
    {
        $country = strtoupper($salaryData['context']['country'] ?? config('payslip.default_country', 'RS'));

        $providers = config('payslip.providers', []);

        if (! isset($providers[$country])) {
            throw new InvalidArgumentException("No payslip provider registered for country: {$country}");
        }

        $providerClass = $providers[$country];

        return new $providerClass($salaryData);
    }
}
