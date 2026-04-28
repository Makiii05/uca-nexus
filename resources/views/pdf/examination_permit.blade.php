<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/x-icon">
    <title>Examination Permit</title>
    <style>
        @page {
            margin: 30px;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
        }
        .header h1 {
            margin: 0;
            font-size: 16px;
        }
        .header h2 {
            margin: 10px 0 0 0;
            font-size: 20px;
            font-weight: bold;
            color: #000;
        }
        .permit-box {
            border: 3px solid #333;
            padding: 30px;
            margin: 30px auto;
            max-width: 500px;
            text-align: center;
        }
        .permit-code {
            font-size: 28px;
            font-weight: bold;
            color: #2563eb;
            letter-spacing: 2px;
            margin: 20px 0;
            padding: 15px;
            background-color: #f8f9fa;
            border: 1px dashed #ccc;
        }
        .student-info {
            margin: 30px 0;
            text-align: left;
        }
        .info-row {
            margin: 10px 0;
            display: flex;
        }
        .info-label {
            font-weight: bold;
            width: 140px;
            display: inline-block;
        }
        .info-value {
            flex: 1;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
        .signature-section {
            margin-top: 60px;
            display: flex;
            justify-content: space-between;
        }
        .signature-box {
            text-align: center;
            width: 200px;
        }
        .signature-line {
            border-top: 1px solid #333;
            padding-top: 5px;
            margin-top: 40px;
        }
        .no-permit {
            color: #dc3545;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="header">
        <div style="text-align: center; margin-bottom: 10px;">
            <img src="{{ public_path('images/logo.png') }}" alt="Logo" style="height: 60px;">
        </div>
        <h1>{{ env('APP_NAME') }}</h1>
        <h2>EXAMINATION PERMIT</h2>
    </div>

    <div class="permit-box">
        <div class="student-info">
            <div class="info-row">
                <span class="info-label">Student Number:</span>
                <span class="info-value">{{ $student->student_number }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Name:</span>
                <span class="info-value">{{ $student->last_name }}, {{ $student->first_name }} {{ $student->middle_name ?? '' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Program:</span>
                <span class="info-value">{{ $student->program->code ?? '-' }} - {{ $student->program->description ?? '-' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Department:</span>
                <span class="info-value">{{ $student->department->code ?? '-' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Year Level:</span>
                <span class="info-value">{{ $student->level->description ?? '-' }}</span>
            </div>
        </div>

        <h3>Permit Code</h3>
        @if($student->account && $student->account->examination_permit)
            <div class="permit-code">{{ $student->account->examination_permit }}</div>
        @else
            <div class="permit-code no-permit">NO PERMIT GENERATED</div>
        @endif
    </div>

    <div class="signature-section">
        <div class="signature-box">
            <div class="signature-line">Student's Signature</div>
        </div>
        <div class="signature-box">
            <div class="signature-line">Authorized Signature</div>
        </div>
    </div>

    <div class="footer">
        <p>Generated on: {{ now()->format('F j, Y h:i A') }}</p>
        <p>This permit is valid only for the current examination period.</p>
    </div>
</body>
</html>
