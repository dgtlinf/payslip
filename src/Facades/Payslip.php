<?php

namespace Dgtlinf\Payslip\Facades;

use Illuminate\Support\Facades\Facade;

class Payslip extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'payslip';
    }
}
