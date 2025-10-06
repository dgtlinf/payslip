# 📄 Dgtlinf Payslip

[![Latest Version on Packagist](https://img.shields.io/packagist/v/dgtlinf/payslip.svg?style=flat-square)](https://packagist.org/packages/dgtlinf/payslip)
[![License](https://img.shields.io/badge/license-MIT-blue.svg?style=flat-square)](LICENSE.md)

**Professional Laravel package for generating multilingual PDF payslips.**  
Built on top of [`dgtlinf/salary-calculator`](https://github.com/dgtlinf/salary-calculator), this package provides an elegant way to render, stream, or download payslips directly from salary calculator data arrays.

---

## 🚀 Features

- 🇷🇸 Country-specific providers (currently: Serbia)
- 🧾 PDF generation via [barryvdh/laravel-dompdf](https://github.com/barryvdh/laravel-dompdf)
- 🌍 Multilingual templates (English & Serbian)
- ⚙️ Stream, download, or preview as HTML
- 🧩 Simple integration with `dgtlinf/salary-calculator`
- 🎨 Easily extendable with custom providers and templates

---

## 📦 Installation

```bash
composer require dgtlinf/payslip
```

This will also install dependencies like `dgtlinf/salary-calculator` and `barryvdh/laravel-dompdf`.

---

## 🧩 Configuration

Publish the configuration and templates if you want to customize them:

```bash
php artisan vendor:publish --provider="Dgtlinf\Payslip\PayslipServiceProvider"
```

This will publish:
- `config/payslip.php`
- Blade templates under `resources/views/vendor/payslip/`
- Translations under `lang/vendor/payslip/`

---

## ⚙️ Usage Example

A typical usage example integrating with **dgtlinf/salary-calculator**:

```php
use Dgtlinf\SalaryCalculator\Facades\SalaryCalculator;
use Dgtlinf\Payslip\Facades\Payslip;

$months = [
    ['date' => '2025-01-01', 'gross' => 420000],
    ['date' => '2025-02-01', 'gross' => 410000],
    ['date' => '2025-03-01', 'gross' => 435000],
];

$rate = \Dgtlinf\SalaryCalculator\Facades\AverageHourlyRate::calculate($months, 'RS');

$employee = new \Dgtlinf\SalaryCalculator\Models\EmployeeProfile(
    firstName: 'Milan',
    lastName: 'Jovanović',
    address: 'Kralja Petra 10, Beograd',
    idNumber: '0101990123456',
    bankAccount: '160-123456789-01',
    position: 'Software Engineer',
);

$employer = new \Dgtlinf\SalaryCalculator\Models\EmployerProfile(
    name: 'Digital Infinity DOO',
    taxId: '110217311',
    registrationNumber: '21318507',
    address: 'Bulevar Kralja Petra I 89, Novi Sad',
    bankName: 'Raiffeisen Bank',
    bankAccount: '265-0001234567890-00'
);

$context = new \Dgtlinf\SalaryCalculator\Models\SalaryContext(
    2025,
    9,
    'RS',
    vacationDays: 0,
    sickDays: 0,
    sickLeaveFullPay: false,
    yearsInService: 8,
    avgHourlyRateLast12Months: null,
    employee: $employee,
    employer: $employer
);

// Create a calculator instance
$calc = SalaryCalculator::for($context);

// Generate a payslip from net salary
$result = $calc->fromNet(80000);

// Render as HTML (useful for previews)
return Payslip::for($result)->toHtml();

// Or download as PDF
return Payslip::for($result)->download();

// Or stream directly to browser
return Payslip::for($result)->locale('en')->setPaymentDate(now())->stream();
```

---

## ⚙️ Config Overview

`config/payslip.php`

```php
return [
    'default_country' => 'RS',
    'providers' => [
        'RS' => \Dgtlinf\Payslip\Providers\RS\RSPayslipProvider::class,
    ],
    'default_template' => 'default',
    'filename_format' => '{slug}_{year}_{month}_payslip.pdf',
];
```

---

## 🧩 How It Works

1. **Payslip::for($salaryData)** — accepts a salary result array from `SalaryCalculator`
2. **locale('en'|'sr')** — sets the output language
3. **setPaymentDate(Carbon $date)** — defines the payment date shown on the slip
4. **toHtml() / download() / stream()** — output options

All salary data is taken directly from the SalaryCalculator array — no additional DTOs or models are required.

---

## 🧠 Extending

To add a new country provider, extend the `BasePayslipProvider` class and register it in the config:

```php
'providers' => [
    'DE' => \App\Payslip\Providers\GermanyPayslipProvider::class,
]
```

Then place your template under:

```
resources/views/vendor/payslip/DE/default.blade.php
```

and translations under:

```
lang/vendor/payslip/de/{locale}/payslip.php
```

---

## 📂 Directory Structure

```
src/
├── PayslipServiceProvider.php
├── PayslipGenerator.php
├── Facades/Payslip.php
├── Providers/
│   ├── BasePayslipProvider.php
│   └── RS/RSPayslipProvider.php
└── resources/
    ├── views/RS/default.blade.php
    └── lang/rs/{en,sr}/payslip.php
```

---

## 🏷 License

This package is open-sourced under the **MIT License**.

---

## 🧑‍💻 Author

**Digital Infinity d.o.o. Novi Sad**  
Bulevar Kralja Petra I 89, 21000 Novi Sad, Serbia  
[www.digitalinfinity.rs](https://www.digitalinfinity.rs)

---

© 2025 Digital Infinity. All rights reserved.
