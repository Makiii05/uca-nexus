<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/x-icon">
    <title>Daily Transactions Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
        }
        .header h2 {
            margin: 5px 0;
            font-size: 14px;
            font-weight: normal;
        }
        .info {
            margin-bottom: 15px;
        }
        .info p {
            margin: 3px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #333;
            padding: 6px 8px;
            text-align: left;
        }
        th {
            background-color: #f0f0f0;
            font-weight: bold;
        }
        .text-end {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .total-row {
            font-weight: bold;
            background-color: #f0f0f0;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="header">
        <div style="display: flex; justify-content: center; align-items: center; gap: 15px; margin-bottom: 10px;">
            <img src="{{ public_path('images/logo.png') }}" alt="Logo" style="height: 50px;">
        </div>
        <h1>{{ env('APP_NAME') }}</h1>
        <h2>Daily Transactions Report</h2>
    </div>

    <div class="info">
        <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($date)->format('F j, Y') }}</p>
        <p><strong>Cashier:</strong> {{ $cashierName }}</p>
        <p><strong>Generated:</strong> {{ now()->format('F j, Y h:i A') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th class="text-center">#</th>
                <th>OR No.</th>
                <th>Student No.</th>
                <th>Student Name</th>
                <th>Academic Term</th>
                <th>Type</th>
                <th>Description</th>
                <th class="text-end">Amount</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($transactions as $index => $t)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $t->or_number ?? '-' }}</td>
                    <td>{{ $t->student->student_number ?? '-' }}</td>
                    <td>{{ $t->student ? $t->student->last_name . ', ' . $t->student->first_name : '-' }}</td>
                    <td>{{ $t->academicTerm->description ?? '-' }}</td>
                    <td>{{ $t->paymentType->description ?? '-' }}</td>
                    <td>{{ $t->paymentAccount->description ?? '-' }}</td>
                    <td class="text-end">{{ number_format($t->amount, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center">No transactions found for this date.</td>
                </tr>
            @endforelse
        </tbody>
        @if($transactions->count() > 0)
        <tfoot>
            <tr class="total-row">
                <td colspan="7" class="text-end">Total:</td>
                <td class="text-end">{{ number_format($totalAmount, 2) }}</td>
            </tr>
        </tfoot>
        @endif
    </table>

    <div class="footer">
        <p>Prepared by: {{ $cashierName }}</p>
    </div>
</body>
</html>
