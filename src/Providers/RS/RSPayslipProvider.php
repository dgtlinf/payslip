<?php

namespace Dgtlinf\Payslip\Providers\RS;

use Dgtlinf\Payslip\Providers\BasePayslipProvider;

class RSPayslipProvider extends BasePayslipProvider
{
    protected string $countryCode = 'RS';
    protected string $template = 'default';
    protected string $locale = 'sr';
}
