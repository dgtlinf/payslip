<?php

namespace Dgtlinf\Payslip;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class PayslipServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('payslip')
            ->hasConfigFile()
            ->hasTranslations()
            ->hasViews();
    }

    public function bootingPackage(): void
    {
        // Bind the payslip generator singleton
        $this->app->singleton('payslip', function () {
            return new PayslipGenerator();
        });
    }
}
