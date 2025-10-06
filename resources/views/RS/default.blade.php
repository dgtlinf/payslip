@php
    $months = [
        1 => __("rs::payslip.months.january"), 2 => __("rs::payslip.months.february"), 3 => __("rs::payslip.months.march"), 4 => __("rs::payslip.months.april"),
        5 => __("rs::payslip.months.may"), 6 => __("rs::payslip.months.june"), 7 => __("rs::payslip.months.july"), 8 => __("rs::payslip.months.august"),
        9 => __("rs::payslip.months.september"), 10 => __("rs::payslip.months.october"), 11 => __("rs::payslip.months.november"), 12 => __("rs::payslip.months.december")
    ];
    $monthName = $months[$data['context']['month']] ?? '';
@endphp
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <title>Platni listić</title>
    <link rel="stylesheet" href="https://unpkg.com/paper-css@0.4.1/paper.css">
    <style>
        @page { size: A4; }
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        .align-left { text-align: left }
        .align-right { text-align: right }
        .align-center { text-align: center }
        .font-medium { font-size: 12px; }
        .font-small { font-size: 10px; }
        .uppercase { text-transform: uppercase }
        .border-bottom { border-bottom: 1px solid #000;}
        .border-top { border-top: 1px solid #000;}
        .border-bottom-semibold { border-bottom: 2px solid #000;}
        tr.border-bottom-td th, tr.border-bottom-td td { border-bottom: 1px solid #000; }
        tr.border-top-td th, tr.border-top-td td { border-top: 1px solid #000; }
        table tr td, table tr th {
            padding: 1.5mm 0;
        }
        .padding-4 {
            padding: 4mm 0;
        }
        .padding-8 {
            padding: 8mm 0;
        }
        .padding-10 {
            padding: 10mm 0;
        }
        .spacing-items div {
            line-height: 1.6; /* povećava vertikalni razmak između redova */
            margin-bottom: 2px; /* dodatni buffer */
        }
        td, th {
            vertical-align: top;
        }
        td.width-50 {
            width: 50%;
        }
    </style>
</head>
<body class="A4">
<section class="sheet padding-10mm">
    <table>
        <tr class="border-bottom">
            <td class="align-right uppercase font-small border-bottom">{{ $data['context']['employer']['name'] }}</td>
        </tr>
        <tr>
            <td>
                <h3>
                    {{ __("rs::payslip.title", [
                            'month' => ucfirst($monthName),
                            'year' => $data['context']['year']
                    ]) }}
                </h3>
            </td>
        </tr>
    </table>

    <table style="margin-bottom: 10mm">
        <tr>
            <td class="spacing-items width-50">
                <div class="font-medium uppercase">{{ __("rs::payslip.employee") }}</div>
                <div>
                    <strong>{{ $data['context']['employee']['first_name'] }} {{ $data['context']['employee']['last_name'] }}</strong>
                </div>
                <div class="font-small">
                    {{ $data['context']['employee']['address'] }}
                </div>
                <div class="font-small">
                    {{ __("rs::payslip.employee_id") }} : {{ $data['context']['employee']['id_number'] }}
                </div>
            </td>
            <td class="spacing-items width-50">
                <div class="font-medium uppercase">{{ __("rs::payslip.employer") }}</div>
                <div>
                    <strong>{{ $data['context']['employer']['name'] }}</strong>
                </div>
                <div class="font-small">
                    {{ $data['context']['employer']['address'] }}
                </div>
                <div class="font-small">
                    {{ __("rs::payslip.tax_id") }}: {{ $data['context']['employer']['tax_id'] }}
                </div>
                <div class="font-small">
                    {{ __("rs::payslip.reg_no") }}: {{ $data['context']['employer']['registration_number'] }}
                </div>
                <div class="font-small">
                    {{ __("rs::payslip.bank_name") }}: {{ $data['context']['employer']['bank_name'] }}
                </div>
                <div class="font-small">
                    {{ __("rs::payslip.bank_no") }}: {{ $data['context']['employer']['bank_account'] }}
                </div>
            </td>
        </tr>
    </table>

    <table class="font-medium">
        <tr>
            <td>{{ __("rs::payslip.payout_date") }}: {{ $data['payed_at']->format('d.m.Y.') }}</td>
        </tr>
    </table>
    <table class="font-medium">
        <thead>
        <tr class="border-bottom-td">
            <th scope="col" class="align-left">{{ __("rs::payslip.description") }}</th>
            <th scope="col" class="align-right">{{ __("rs::payslip.unit_number") }}</th>
            <th scope="col" class="align-right">{{ __("rs::payslip.unit") }}</th>
            <th scope="col" class="align-right">{{ __("rs::payslip.per_unit") }}</th>
            <th scope="col" class="align-right">%</th>
            <th scope="col" class="align-right">{{ __("rs::payslip.base") }}</th>
            <th scope="col" class="align-right">{{ __("rs::payslip.amount") }}</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($data['salary']['gross']['items'] as $key => $item)
        @if( $item['amount'] > 0 )
        <tr>
            <td style="width: 18%">{{ __("rs::payslip.items.{$key}") }}</td>
            <td style="width: 12%" class="align-right">{{ isset($item['units']) ? $item['units'] : '' }}</td>
            <td style="width: 12%" class="align-right">{{ isset($item['unit']) ? __("rs::payslip.{$item["unit"]}") : '' }}</td>
            <td style="width: 12%" class="align-right">{{ isset($item['per_unit']) ? number_format($item['per_unit'], 2, ',', '.') : '' }}</td>
            <td style="width: 12%" class="align-right">{{ isset($item['basis']) ? number_format($item['basis'] * 100, 2, ',', '.') : '' }}</td>
            <td style="width: 12%" class="align-right"></td>
            <td style="width: 12%" class="align-right">{{ isset($item['amount']) ? number_format($item['amount'], 2, ',', '.') : '' }}</td>
        </tr>
        @endif
        @endforeach
        <tr class="border-top-td">
            <td>
                <strong class="uppercase">{{ __("rs::payslip.labels.gross_total") }}</strong>
            </td>
            <td class="align-right">
                <strong>{{ $data['context']['hours']['total_hours']  }} {{ __("rs::payslip._hours") }}</strong>
            </td>
            <td colspan="5" class="align-right">
                <strong>{{ number_format($data['salary']['gross']['total'], 2, ',', '.')  }}</strong>
            </td>
        </tr>
        <tr>
            <td colspan="7"></td>
        </tr>
        <tr>
            <td colspan="4">{{ __("rs::payslip.unemployment_contributions") }}</td>
            <td class="align-right">{{ number_format($data['tax_table']['employee']['unemployment_rate'] * 100, 2, ',', '.') }}</td>
            <td class="align-right">{{ number_format($data['salary']['contributions_base'], 2, ',', '.') }}</td>
            <td class="align-right">{{ number_format($data['salary']['employee_contributions']['unemployment_contributions'], 2, ',', '.') }}</td>
        </tr>
        <tr>
            <td colspan="4">{{ __("rs::payslip.pension_contributions") }}</td>
            <td class="align-right">{{ number_format($data['tax_table']['employee']['pension_rate'] * 100, 2, ',', '.') }}</td>
            <td class="align-right">{{ number_format($data['salary']['contributions_base'], 2, ',', '.') }}</td>
            <td class="align-right">{{ number_format($data['salary']['employee_contributions']['pension_contributions'], 2, ',', '.') }}</td>
        </tr>
        <tr>
            <td colspan="4">{{ __("rs::payslip.healthcare_contributions") }}</td>
            <td class="align-right">{{ number_format($data['tax_table']['employee']['health_rate'] * 100, 2, ',', '.') }}</td>
            <td class="align-right">{{ number_format($data['salary']['contributions_base'], 2, ',', '.') }}</td>
            <td class="align-right">{{ number_format($data['salary']['employee_contributions']['healthcare_contributions'], 2, ',', '.') }}</td>
        </tr>

        <tr>
            <td colspan="5">
                <strong class="uppercase">{{ __("rs::payslip.labels.employee_total") }}</strong>
            </td>
            <td colspan="2" class="align-right">
                <strong>{{ number_format($data['salary']['employee_contributions']['total'], 2, ',', '.')  }}</strong>
            </td>
        </tr>

        <tr>
            <td colspan="5">{{ __("rs::payslip.tax_deductions") }}</td>
            <td class="align-right">
                {{ number_format($data['tax_table']['non_taxable_limit'], 2, ',', '.')  }}
            </td>
            <td></td>
        </tr>

        <tr>
            <td colspan="4">{{ __("rs::payslip.income_tax") }}</td>
            <td class="align-right">{{ number_format($data['tax_table']['tax_rate'] * 100, 2, ',', '.') }}</td>
            <td class="align-right">{{ number_format($data['salary']['income_tax']['base'], 2, ',', '.') }}</td>
            <td class="align-right">{{ number_format($data['salary']['income_tax']['amount'], 2, ',', '.') }}</td>
        </tr>

        <tr>
            <td colspan="5">
                <strong class="uppercase">{{ __("rs::payslip.labels.net_total") }}</strong>
            </td>
            <td colspan="2" class="align-right">
                <strong>{{ number_format($data['salary']['net_salary'], 2, ',', '.')  }}</strong>
            </td>
        </tr>

        <tr>
            <td colspan="7"></td>
        </tr>

        <tr class="border-bottom-td border-top-td">
            <td colspan="5">
                <strong class="uppercase">
                    {{ __("rs::payslip.for_payout") }}<br>
                    RS35 {{ $data['context']['employee']['bank_account'] }}
                </strong>
            </td>
            <td colspan="2" class="align-right" style="vertical-align: bottom">
                <strong>{{ number_format($data['salary']['net_salary'], 2, ',', '.')  }}</strong>
            </td>
        </tr>


        <tr>
            <td colspan="7"></td>
        </tr>

        <tr>
            <td colspan="4">{{ __("rs::payslip.pension_contributions") }}</td>
            <td class="align-right">{{ number_format($data['tax_table']['employer']['pension_rate'] * 100, 2, ',', '.') }}</td>
            <td class="align-right">{{ number_format($data['salary']['contributions_base'], 2, ',', '.') }}</td>
            <td class="align-right">{{ number_format($data['salary']['employer_contributions']['pension_contributions'], 2, ',', '.') }}</td>
        </tr>
        <tr>
            <td colspan="4">{{ __("rs::payslip.healthcare_contributions") }}</td>
            <td class="align-right">{{ number_format($data['tax_table']['employer']['health_rate'] * 100, 2, ',', '.') }}</td>
            <td class="align-right">{{ number_format($data['salary']['contributions_base'], 2, ',', '.') }}</td>
            <td class="align-right">{{ number_format($data['salary']['employer_contributions']['healthcare_contributions'], 2, ',', '.') }}</td>
        </tr>

        <tr>
            <td colspan="5">
                <strong class="uppercase">{{ __("rs::payslip.labels.organization_total") }}</strong>
            </td>
            <td colspan="2" class="align-right">
                <strong>{{ number_format($data['salary']['employee_contributions']['total'], 2, ',', '.')  }}</strong>
            </td>
        </tr>


        <tr class="border-top-td">
            <td colspan="5">
                <strong class="uppercase">{{ __("rs::payslip.labels.total_cost") }}</strong>
            </td>
            <td colspan="2" class="align-right">
                <strong>{{ number_format($data['salary']['total_salary_cost'], 2, ',', '.')  }}</strong>
            </td>
        </tr>

        </tbody>
    </table>

    <table class="font-medium" style="margin-top: 40mm">
        <tr>
            <td style="width: 50%; padding-right: 10mm" class="align-center">
                <div class="border-bottom"></div>
                <div>{{ __("rs::payslip.employer_signature") }}</div>
            </td>
            <td style="width: 50%; padding-left: 10mm" class="align-center">
                <div class="border-bottom"></div>
                <div>{{ __("rs::payslip.employee_signature") }}</div>
            </td>
        </tr>
    </table>
</section>
</body>
</html>
