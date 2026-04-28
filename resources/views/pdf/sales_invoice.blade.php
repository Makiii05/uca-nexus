<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/x-icon">
    <title>Sales Invoice</title>
    <style>
        @page {
            margin: 15px;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            margin: 0;
            padding: 10px;
        }
        .header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 1px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 14px;
        }
        .header h2 {
            margin: 5px 0 0 0;
            font-size: 16px;
            font-weight: bold;
        }
        .info-section {
            margin-bottom: 15px;
        }
        .info-row {
            margin: 4px 0;
        }
        .info-label {
            font-weight: bold;
            display: inline-block;
            width: 100px;
        }
        .sum-text {
            margin: 15px 0;
            font-style: italic;
        }
        .amount-highlight {
            font-size: 14px;
            font-weight: bold;
            margin: 10px 0;
        }
        .payment-section {
            margin: 15px 0;
        }
        .payment-section h3 {
            margin: 0 0 8px 0;
            font-size: 11px;
        }
        .payment-table {
            width: 100%;
            border-collapse: collapse;
        }
        .payment-table td {
            padding: 4px 0;
            vertical-align: top;
        }
        .payment-table .amount {
            text-align: right;
            width: 80px;
        }
        .signature-section {
            margin-top: 40px;
            text-align: right;
        }
        .signature-line {
            display: inline-block;
            border-top: 1px solid #333;
            width: 150px;
            padding-top: 5px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <div style="display: flex; justify-content: center; align-items: center; gap: 15px; margin-bottom: 10px;">
            <img src="{{ public_path('images/logo.png') }}" alt="Logo" style="height: 50px;">
        </div>
        <h1>{{ env('APP_NAME') }}</h1>
        <h2>SALES INVOICE</h2>
    </div>

    <div class="info-section">
        <div class="info-row">
            <span class="info-label">OR Number:</span>
            <span>{{ $transaction->or_number ?? 'N/A' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Date:</span>
            <span>{{ \Carbon\Carbon::parse($transaction->date)->format('F j, Y') }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Received From:</span>
            <span>{{ $transaction->student ? $transaction->student->last_name . ', ' . $transaction->student->first_name . ' ' . ($transaction->student->middle_name ?? '') : 'N/A' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Student No.:</span>
            <span>{{ $transaction->student->student_number ?? 'N/A' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Academic Term:</span>
            <span>{{ $transaction->academicTerm->description ?? 'N/A' }}</span>
        </div>
    </div>

    <div class="sum-text">
        The sum of {{ ucwords(strtolower(\NumberFormatter::create('en', \NumberFormatter::SPELLOUT)->format($transaction->amount))) }} pesos only.
    </div>

    <div class="amount-highlight">
        P {{ number_format($transaction->amount, 2) }}
    </div>

    <div class="payment-section">
        <h3>As payment for the following:</h3>
        <table class="payment-table">
            <tr>
                <td>{{ $transaction->paymentAccount->description ?? 'N/A' }}</td>
                <td class="amount">{{ number_format($transaction->amount, 2) }}</td>
            </tr>
        </table>
    </div>

    <div class="signature-section">
        <div class="signature-line">
            {{ $cashierName }}<br>
            <small>Cashier</small>
        </div>
    </div>
</body>
</html>
