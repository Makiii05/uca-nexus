<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/x-icon">
    <title>Admission Statistics Report</title>
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
            font-size: 12px;
            line-height: 1.4;
            padding: 20px;
        }
        .print-date {
            font-size: 10px;
            margin-bottom: 10px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header img {
            width: 60px;
            height: 60px;
        }
        .header h1 {
            font-size: 14px;
            font-weight: bold;
            margin-top: 5px;
            text-transform: uppercase;
        }
        .header p {
            font-size: 10px;
        }
        .title {
            text-align: center;
            font-weight: bold;
            font-size: 12px;
            padding: 5px 0;
            border-top: 1px solid #000;
            border-bottom: 1px solid #000;
            margin-bottom: 15px;
        }
        .section {
            margin-bottom: 15px;
        }
        .section-title {
            font-weight: bold;
            font-size: 12px;
            border-bottom: 1px solid #000;
            padding-bottom: 3px;
            margin-bottom: 8px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }
        th, td {
            border: 1px solid #000;
            padding: 6px 8px;
            text-align: left;
        }
        th {
            font-weight: bold;
            font-size: 10px;
            text-transform: uppercase;
            text-align: center;
        }
        td {
            text-align: center;
        }
        td:first-child {
            text-align: left;
        }
        .total-row {
            font-weight: bold;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            border-top: 1px solid #000;
            padding-top: 10px;
        }
        @media print {
            body { padding: 0; }
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
    
    <div class="title">ADMISSION STATISTICS REPORT — {{ $selectedYear }}</div>
    
    <!-- Level Breakdown -->
    <div class="section">
        <table>
            <thead>
                <tr>
                    <th style="width: 22%;">Level</th>
                    <th style="width: 11%;">Applicant</th>
                    <th style="width: 13%;">Interviewee</th>
                    <th style="width: 11%;">Examinee</th>
                    <th style="width: 11%;">Evaluatee</th>
                    <th style="width: 11%;">Admitted</th>
                    <th style="width: 11%;">Variance</th>
                </tr>
            </thead>
            <tbody>
                @forelse($levelStats as $level)
                <tr>
                    <td>{{ $level->level_name }}</td>
                    <td>{{ number_format($level->total_applicants) }}</td>
                    <td>{{ number_format($level->total_interviewee) }}</td>
                    <td>{{ number_format($level->total_examinee) }}</td>
                    <td>{{ number_format($level->total_evaluatee) }}</td>
                    <td>{{ number_format($level->total_admitted) }}</td>
                    <td>{{ number_format($level->total_applicants - $level->total_admitted) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center;">No level data available</td>
                </tr>
                @endforelse
                <tr class="total-row">
                    <td>GRAND TOTAL</td>
                    <td>{{ number_format($grandTotals['total_applicants']) }}</td>
                    <td>{{ number_format($grandTotals['total_interviewee']) }}</td>
                    <td>{{ number_format($grandTotals['total_examinee']) }}</td>
                    <td>{{ number_format($grandTotals['total_evaluatee']) }}</td>
                    <td>{{ number_format($grandTotals['total_admitted']) }}</td>
                    <td>{{ number_format($grandTotals['variance']) }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    
    <div class="footer">
        <p>This report was automatically generated by the Enrollment System.</p>
        <p>© {{ date('Y') }} All Rights Reserved</p>
    </div>
</body>
</html>
