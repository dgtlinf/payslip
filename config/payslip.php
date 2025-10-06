<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Country
    |--------------------------------------------------------------------------
    |
    | When generating payslips, the system will use this country code
    | if none is specified in the salary context data.
    |
    */

    'default_country' => 'RS',

    /*
    |--------------------------------------------------------------------------
    | Payslip Providers
    |--------------------------------------------------------------------------
    |
    | Each country has its own provider class responsible for generating
    | localized payslip templates (language, currency, format, etc.).
    | You can extend or override providers from your application.
    |
    */

    'providers' => [
        'RS' => \Dgtlinf\Payslip\Providers\RS\RSPayslipProvider::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Template & Filename
    |--------------------------------------------------------------------------
    |
    | Define which Blade template is used by default and how filenames
    | are formatted when downloading generated payslips.
    |
    */

    'default_template' => 'default',

    'filename_format' => '{slug}_{year}_{month}_payslip.pdf',

];
