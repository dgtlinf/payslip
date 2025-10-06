# Changelog

All notable changes to this project will be documented in this file.


---

## v1.0.0 - 2025-10-06

### v1.0.0 – Initial Release

#### Overview

Initial stable release of the **Dgtlinf Payslip** Laravel package.
This version introduces a production-ready implementation for generating multilingual and localized payslips directly from salary calculation data.

#### Features

- Seamless integration with **dgtlinf/salary-calculator**
- Country-specific providers (initially **Serbia**)
- PDF generation using **barryvdh/laravel-dompdf**
- Blade-based template rendering with localization
- Multilingual support (**English** and **Serbian**)
- Stream, download, and HTML preview output options
- Configurable filename formats and templates
- Extendable architecture for adding new countries
- Published config, translations, and views for customization

#### Requirements

- **PHP 8.2+**
- **Laravel 10+**
- **barryvdh/laravel-dompdf**
- **dgtlinf/salary-calculator**

#### License

Released under the **MIT License**
© 2025 Digital Infinity

## [Unreleased]

### Added

- Base CHANGELOG.md initialized for GitHub Actions automation.

### Changed

- None.

### Fixed

- None.
