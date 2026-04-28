<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/x-icon">
    <title>{{ env('APP_NAME') }} - Student Assessment</title>
    <style>
        @page {
            size: A4;
            margin: 15mm;
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 9px;
            line-height: 1.4;
            padding: 20px;
        }
        .print-date {
            font-size: 9px;
            margin-bottom: 10px;
        }
        .header {
            text-align: center;
            margin-bottom: 15px;
        }
        .header img {
            width: 60px;
            height: 60px;
        }
        .header h1 {
            font-size: 9px;
            font-weight: bold;
            margin-top: 5px;
            text-transform: uppercase;
        }
        .header p {
            font-size: 9px;
        }
        .title {
            text-align: center;
            font-weight: bold;
            font-size: 14px;
            padding: 5px 0;
            border-top: 1px solid #000;
            border-bottom: 1px solid #000;
            margin-bottom: 12px;
        }
        .term-label {
            text-align: center;
            font-size: 9px;
            margin-bottom: 12px;
        }
        .info-grid {
            display: table;
            width: 100%;
            margin-bottom: 10px;
        }
        .info-row {
            display: table-row;
        }
        .info-field {
            display: table-cell;
            padding: 2px 10px 2px 0;
            vertical-align: top;
            width: 50%;
        }
        .label {
            font-size: 9px;
            font-weight: bold;
            color: #555;
        }
        .value {
            font-size: 9px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9px;
            margin-bottom: 8px;
        }
        th, td {
            border: 1px solid #000;
            padding: 4px 6px;
            text-align: left;
        }
        th {
            font-weight: bold;
            font-size: 9px;
            text-transform: uppercase;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .font-bold {
            font-weight: bold;
        }
        .two-col {
            width: 100%;
            display: table;
        }
        .two-col-left, .two-col-right {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }
        .two-col-left {
            padding-right: 10px;
        }
        .two-col-right {
            padding-left: 10px;
        }
        .separator {
            border: none;
            border-top: 2px solid #000;
        }
        .fee-table {
            font-size: 9px;
        }
        .fee-table th, .fee-table td {
            border: none;
            padding: 2px 0 2px 12px;
        }
        .fee-table th {
            padding-left: 12px;
            border-bottom: 1px solid #000;
        }
        .fee-table tfoot td {
            border-top: 1px solid #000;
        }
        .fee-label {
            font-weight: bold;
            font-size: 9px;
            margin-top: 8px;
            margin-bottom: 2px;
        }
        .schedule-table {
            font-size: 9px;
        }
        .schedule-table th, .schedule-table td {
            border: none;
            padding: 2px 0 2px 12px;
        }
        .schedule-table th {
            border-bottom: 1px solid #000;
        }
        .schedule-table tfoot td {
            border-top: 1px solid #000;
        }
        .summary-table {
            font-size: 9px;
        }
        .summary-table td {
            border: none;
            padding: 2px 0 2px 12px;
        }
        .summary-table .separator-row td {
            border-top: 1px solid #000;
        }
        .amount-to-pay td {
            font-size: 9px;
            font-weight: bold;
            border-top: 2px solid #000;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 9px;
            border-top: 1px solid #000;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <p class="print-date">Printed: {{ date('F d, Y') }}</p>

    <!-- Header -->
    <div class="header">
        <img src="{{ public_path('images/logo.png') }}" alt="Logo">
        <h1>{{ strtoupper(env('APP_NAME')) }}</h1>
        <p>School Address</p>
        <p>000-0000-000 | example@example.com</p>
    </div>

    <div class="title">STUDENT ASSESSMENT</div>

    @if($academicTerm)
        <p class="term-label">{{ $academicTerm->description }}</p>
    @endif

    <!-- Student Information -->
    <div class="info-grid">
        <div class="info-row">
            <div class="info-field">
                <p class="label">Student No.</p>
                <p class="value">{{ $student->student_number }}</p>
            </div>
            <div class="info-field">
                <p class="label">Name</p>
                <p class="value">{{ $student->last_name }}, {{ $student->first_name }} {{ $student->middle_name }}</p>
            </div>
            <div class="info-field">
                <p class="label">Year Level</p>
                <p class="value">{{ $student->level->description ?? '—' }}</p>
            </div>
        </div>
        <div class="info-row">
            <div class="info-field">
                <p class="label">Program</p>
                <p class="value">{{ $student->program->code ?? '—' }}</p>
            </div>
            <div class="info-field">
                <p class="label">Department</p>
                <p class="value">{{ $student->department->code ?? '—' }}</p>
            </div>
        </div>
    </div>

    <!-- Enrolled Subjects -->
    <table>
        <thead>
            <tr>
                <th>Code</th>
                <th>Description</th>
                <th class="text-center">Units</th>
            </tr>
        </thead>
        <tbody>
            @php $totalUnits = 0; @endphp
            @forelse($enlistments as $enlistment)
                <tr>
                    <td>{{ $enlistment->subjectOffering->code ?? '—' }}</td>
                    <td>{{ $enlistment->subjectOffering->description ?? '—' }}</td>
                    <td class="text-center">{{ $enlistment->subjectOffering->subject->unit ?? 0 }}</td>
                </tr>
                @php $totalUnits += $enlistment->subjectOffering->subject->unit ?? 0; @endphp
            @empty
                <tr><td colspan="3" class="text-center">No enlisted subjects.</td></tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2" class="text-right font-bold">Total Units:</td>
                <td class="text-center font-bold">{{ $totalUnits }}</td>
            </tr>
        </tfoot>
    </table>

    <!-- Fees by Type -->
    @php
        $enlistmentCount = $enlistments->count();
        $majorTotal = 0;
        $otherTotal = 0;
        $additionalTotal = 0;
    @endphp

    {{-- Pre-calculate totals for right column --}}
    @php
        foreach ($fees['major'] as $fee) {
            $amt = $fee->amount;
            if (strtolower($fee->description) === 'unit fee') $amt = $fee->amount * $enlistmentCount;
            $majorTotal += $amt;
        }
        foreach ($fees['other'] as $fee) {
            $amt = $fee->amount;
            if (strtolower($fee->description) === 'unit fee') $amt = $fee->amount * $enlistmentCount;
            $otherTotal += $amt;
        }
        foreach ($fees['additional'] as $fee) {
            $amt = $fee->amount;
            if (strtolower($fee->description) === 'unit fee') $amt = $fee->amount * $enlistmentCount;
            $additionalTotal += $amt;
        }
        $grossTotal = $majorTotal + $otherTotal + $additionalTotal;
        $downPayment = $grossTotal * 0.30;
        $monthlyPay = $grossTotal * 0.175;
    @endphp

    <div class="two-col">
        <!-- LEFT: Fee Tables -->
        <div class="two-col-left">
            <!-- Major Fees -->
            <p class="fee-label">Major Fees</p>
            <table class="fee-table">
                <thead>
                    <tr><th>Description</th><th class="text-right">Amount</th></tr>
                </thead>
                <tbody>
                    @forelse($fees['major'] as $fee)
                        @php
                            $desc = $fee->description;
                            $amount = $fee->amount;
                            if (strtolower($fee->description) === 'unit fee') {
                                $desc = 'Unit Fee (' . number_format($fee->amount, 2) . ')';
                                $amount = $fee->amount * $enlistmentCount;
                            }
                        @endphp
                        <tr>
                            <td>{{ $desc }}</td>
                            <td class="text-right">{{ number_format($amount, 2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="2" class="text-center">No major fees.</td></tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr><td class="text-right font-bold">Total</td><td class="text-right font-bold">{{ number_format($majorTotal, 2) }}</td></tr>
                </tfoot>
            </table>

            <!-- Other Fees -->
            <p class="fee-label">Other Fees</p>
            <table class="fee-table">
                <thead>
                    <tr><th>Description</th><th class="text-right">Amount</th></tr>
                </thead>
                <tbody>
                    @forelse($fees['other'] as $fee)
                        @php
                            $desc = $fee->description;
                            $amount = $fee->amount;
                            if (strtolower($fee->description) === 'unit fee') {
                                $desc = 'Unit Fee (' . number_format($fee->amount, 2) . ')';
                                $amount = $fee->amount * $enlistmentCount;
                            }
                        @endphp
                        <tr>
                            <td>{{ $desc }}</td>
                            <td class="text-right">{{ number_format($amount, 2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="2" class="text-center">No other fees.</td></tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr><td class="text-right font-bold">Total</td><td class="text-right font-bold">{{ number_format($otherTotal, 2) }}</td></tr>
                </tfoot>
            </table>

            <!-- Additional Fees -->
            <p class="fee-label">Additional Fees</p>
            <table class="fee-table">
                <thead>
                    <tr><th>Description</th><th class="text-right">Amount</th></tr>
                </thead>
                <tbody>
                    @forelse($fees['additional'] as $fee)
                        @php
                            $desc = $fee->description;
                            $amount = $fee->amount;
                            if (strtolower($fee->description) === 'unit fee') {
                                $desc = 'Unit Fee (' . number_format($fee->amount, 2) . ')';
                                $amount = $fee->amount * $enlistmentCount;
                            }
                        @endphp
                        <tr>
                            <td>{{ $desc }}</td>
                            <td class="text-right">{{ number_format($amount, 2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="2" class="text-center">No additional fees.</td></tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr><td class="text-right font-bold">Total</td><td class="text-right font-bold">{{ number_format($additionalTotal, 2) }}</td></tr>
                </tfoot>
            </table>
        </div>

        <!-- RIGHT: Schedule of Fees + Summary -->
        <div class="two-col-right">
            <!-- Schedule of Fees -->
            <p class="fee-label">Schedule of Fees</p>
            <table class="schedule-table">
                <thead>
                    <tr><th>Schedule</th><th class="text-right">Amount</th></tr>
                </thead>
                <tbody>
                    <tr><td>Down Payment</td><td class="text-right">{{ number_format($downPayment, 2) }}</td></tr>
                    <tr><td>1st Month Pay</td><td class="text-right">{{ number_format($monthlyPay, 2) }}</td></tr>
                    <tr><td>2nd Month Pay</td><td class="text-right">{{ number_format($monthlyPay, 2) }}</td></tr>
                    <tr><td>3rd Month Pay</td><td class="text-right">{{ number_format($monthlyPay, 2) }}</td></tr>
                    <tr><td>4th Month Pay</td><td class="text-right">{{ number_format($monthlyPay, 2) }}</td></tr>
                </tbody>
                <tfoot>
                    <tr><td class="font-bold">Total</td><td class="text-right font-bold">{{ number_format($grossTotal, 2) }}</td></tr>
                </tfoot>
            </table>

            <!-- Fee Summary -->
            <p class="fee-label">Fee Summary</p>
            <table class="summary-table">
                <tbody>
                    <tr><td>Major Fees</td><td class="text-right">{{ number_format($majorTotal, 2) }}</td></tr>
                    <tr><td>Other Fees</td><td class="text-right">{{ number_format($otherTotal, 2) }}</td></tr>
                    <tr><td>Additional Fees</td><td class="text-right">{{ number_format($additionalTotal, 2) }}</td></tr>
                    <tr class="separator-row"><td class="font-bold">Gross Total</td><td class="text-right font-bold">{{ number_format($grossTotal, 2) }}</td></tr>
                </tbody>
            </table>
            <table class="summary-table">
                <tbody>
                    <tr class="amount-to-pay"><td>Amount to Pay</td><td class="text-right">{{ number_format($grossTotal, 2) }}</td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>This is a system-generated document.</p>
    </div>
</body>
</html>
