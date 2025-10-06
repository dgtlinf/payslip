<?php

namespace Dgtlinf\Payslip\Providers;

use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Lang;


abstract class BasePayslipProvider
{
    protected string $countryCode;
    protected string $template = 'default';
    protected string $locale = 'en';
    protected ?Carbon $payedAt = null;

    public function __construct(protected array $salaryData)
    {

        if ( $this->payedAt === null ) {
            $this->payedAt = Carbon::now();
        }

        // Normalize context objects into arrays immediately
        if (isset($this->salaryData['context']['employee']) &&
            is_object($this->salaryData['context']['employee']) &&
            method_exists($this->salaryData['context']['employee'], 'toArray')) {
            $this->salaryData['context']['employee'] = $this->salaryData['context']['employee']->toArray();
        }

        if (isset($this->salaryData['context']['employer']) &&
            is_object($this->salaryData['context']['employer']) &&
            method_exists($this->salaryData['context']['employer'], 'toArray')) {
            $this->salaryData['context']['employer'] = $this->salaryData['context']['employer']->toArray();
        }

        $this->salaryData['payed_at'] = $this->payedAt;

        app()->setLocale($this->locale);

        // Load translations for current country
        $this->registerCountryTranslations();
    }

    /**
     * Dynamically register translations for the current country provider.
     */
    protected function registerCountryTranslations(): void
    {
        $folder = strtolower($this->countryCode); // enforce lowercase

        $path = __DIR__ . "/../../resources/lang/{$folder}";
        if (is_dir($path)) {
            Lang::addNamespace($folder, $path);
        }
    }

    /**
     * @param Carbon $payedAt
     * @return $this
     */
    public function setPaymentDate(Carbon $payedAt): static
    {
        $this->payedAt = $payedAt;
        $this->salaryData['payed_at'] = $payedAt;
        return $this;
    }

    /**
     * @param string $locale
     * @return $this
     */
    public function locale(string $locale): static
    {
        $this->locale = $locale;
        app()->setLocale($locale);
        $this->registerCountryTranslations();
        return $this;
    }

    /**
     * Generate the PDF for this country's template.
     */
    public function generate(): \Barryvdh\DomPDF\PDF
    {
        return Pdf::loadView("payslip::{$this->countryCode}.{$this->template}", [
            'data' => $this->salaryData,
        ]);
    }

    public function download(string $filename = null)
    {
        $filename = $filename ?? $this->getDefaultFilename();
        return $this->generate()->download($filename);
    }

    public function stream(string $filename = null)
    {
        $filename = $filename ?? $this->getDefaultFilename();
        return $this->generate()->stream($filename);
    }

    public function toHtml(): string
    {
        $view = "payslip::{$this->countryCode}.{$this->template}";
        return view($view, ['data' => $this->salaryData])->render();
    }

    protected function getDefaultFilename(): string
    {
        $context  = $this->salaryData['context'] ?? [];
        $employee = $context['employee'] ?? [];

        // Normalize to array if object
        if (is_object($employee) && method_exists($employee, 'toArray')) {
            $employee = $employee->toArray();
        }

        // Build slug from name
        $firstName = $employee['first_name'] ?? 'Employee';
        $lastName  = $employee['last_name'] ?? '';
        $slugName  = Str::slug(trim("{$firstName}-{$lastName}"));

        // Merge all possible variables
        $vars = array_merge(
            $employee,
            $context,
            [
                'slug'    => $slugName,
                'country' => $this->countryCode ?? 'XX',
            ]
        );

        // Default format
        $format = config('payslip.filename_format', '{slug}_{year}_{month}_payslip.pdf');

        // Replace all placeholders {key} with values
        $filename = preg_replace_callback('/\{([a-zA-Z0-9_]+)\}/', function ($matches) use ($vars) {
            $key = $matches[1];
            return $vars[$key] ?? 'x';
        }, $format);

        return $filename;
    }
}
