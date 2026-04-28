<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/x-icon">
    <title>{{ env('APP_NAME') }} - Class List</title>
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
            font-size: 8px;
            line-height: 1.3;
            padding: 10px;
        }
        .print-date {
            font-size: 7px;
            margin-bottom: 8px;
        }
        .header {
            text-align: center;
            margin-bottom: 10px;
        }
        .header img {
            width: 50px;
            height: 50px;
        }
        .header h1 {
            font-size: 10px;
            font-weight: bold;
            margin-top: 3px;
            text-transform: uppercase;
        }
        .header p {
            font-size: 8px;
        }
        .title {
            text-align: center;
            font-weight: bold;
            font-size: 11px;
            padding: 4px 0;
            border-top: 1px solid #000;
            border-bottom: 1px solid #000;
            margin-bottom: 8px;
        }
        .subject-info {
            margin-bottom: 10px;
        }
        .subject-info p {
            font-size: 8px;
            margin: 2px 0;
        }
        .subject-info strong {
            display: inline-block;
            width: 100px;
        }
        .section-title {
            font-weight: bold;
            font-size: 9px;
            margin-top: 10px;
            margin-bottom: 4px;
            padding: 4px;
            border-bottom: 1px solid #000;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8px;
            margin-bottom: 8px;
        }
        th, td {
            border: 1px solid #000;
            padding: 3px 5px;
            text-align: left;
        }
        th {
            font-weight: bold;
            font-size: 8px;
            text-transform: uppercase;
        }
        .text-center {
            text-align: center;
        }
        .total-section {
            margin-top: 10px;
            font-weight: bold;
            text-align: center;
            padding: 8px;
            border: 1px solid #000;
        }
        .signature-line {
            margin-top: 30px;
            display: table;
            width: 100%;
        }
        .signature-box {
            display: table-cell;
            width: 50%;
            text-align: center;
        }
        .signature-box .line {
            border-top: 1px solid #000;
            width: 150px;
            margin: 0 auto;
            padding-top: 4px;
            font-size: 8px;
        }
        .gender-header {
            text-align: center;
            font-weight: bold;
            font-size: 9px;
        }
        .student-table {
            width: 100%;
        }
        .student-table th, .student-table td {
            border: 1px solid #000;
            padding: 2px 4px;
        }
    </style>
</head>
<body>
    <div class="print-date">
        Printed: {{ now()->format('F d, Y h:i A') }}
    </div>

    <div class="header">
        <img src="{{ public_path('images/logo.png') }}" alt="Logo">
        <h1>{{ env('APP_NAME') }}</h1>
        <p>Class List</p>
    </div>

    <div class="title">CLASS LIST</div>

    <div class="subject-info">
        <p><strong>Subject Code:</strong> {{ $subjectOffering->code }}</p>
        <p><strong>Description:</strong> {{ $subjectOffering->subject->description ?? 'N/A' }}</p>
        <p><strong>Program:</strong> {{ $subjectOffering->program->code ?? 'N/A' }} - {{ $subjectOffering->program->description ?? '' }}</p>
        <p><strong>Year Level:</strong> {{ $yearLevel }}</p>
        <p><strong>Academic Term:</strong> {{ $subjectOffering->academicTerm->description ?? 'N/A' }}</p>
    </div>

    <!-- Female Students Table -->
    <table class="student-table">
        <thead>
            <tr>
                <th colspan="{{ $hasTuitionFees ? 4 : 3 }}" class="gender-header">Female Students ({{ $femaleStudents->count() }})</th>
            </tr>
            <tr>
                <th style="width: 30px;">#</th>
                <th style="width: 100px;">Student No.</th>
                <th>Name</th>
                @if($hasTuitionFees)
                    <th style="width: 60px;">OR#</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse ($femaleStudents as $enlistment)
            @php static $femaleCounter = 1; @endphp
            <tr>
                <td class="text-center">{{ $femaleCounter++ }}</td>
                <td>{{ $enlistment->student->student_number ?? 'N/A' }}</td>
                <td>{{ $enlistment->student->last_name }}, {{ $enlistment->student->first_name }} {{ $enlistment->student->middle_name }}</td>
                @if($hasTuitionFees)
                    <td>{{ $enlistment->or_number ?? '' }}</td>
                @endif
            </tr>
            @empty
            <tr>
                <td colspan="{{ $hasTuitionFees ? 4 : 3 }}" class="text-center">No female students</td>
            </tr>
            @endforelse
            <tr>
                <td colspan="{{ $hasTuitionFees ? 4 : 3 }}" class="text-center" style="font-weight: bold;">**nothing follows**</td>
            </tr>
        </tbody>
    </table>
    <!-- Male Students Table -->
    <table class="student-table">
        <thead>
            <tr>
                <th colspan="{{ $hasTuitionFees ? 4 : 3 }}" class="gender-header">Male Students ({{ $maleStudents->count() }})</th>
            </tr>
            <tr>
                <th style="width: 30px;">#</th>
                <th style="width: 100px;">Student No.</th>
                <th>Name</th>
                @if($hasTuitionFees)
                    <th style="width: 60px;">OR#</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse ($maleStudents as $enlistment)
            @php static $maleCounter = 1; @endphp
            <tr>
                <td class="text-center">{{ $maleCounter++ }}</td>
                <td>{{ $enlistment->student->student_number ?? 'N/A' }}</td>
                <td>{{ $enlistment->student->last_name }}, {{ $enlistment->student->first_name }} {{ $enlistment->student->middle_name }}</td>
                @if($hasTuitionFees)
                    <td>{{ $enlistment->or_number ?? '' }}</td>
                @endif
            </tr>
            @empty
            <tr>
                <td colspan="{{ $hasTuitionFees ? 4 : 3 }}" class="text-center">No male students</td>
            </tr>
            @endforelse
            <tr>
                <td colspan="{{ $hasTuitionFees ? 4 : 3 }}" class="text-center" style="font-weight: bold;">**nothing follows**</td>
            </tr>
        </tbody>
    </table>

    <div class="total-section">
        Total Enrolled: {{ $femaleStudents->count() + $maleStudents->count() }}
    </div>

    <div class="signature-line">
        <div class="signature-box">
            <div class="line">Prepared by</div>
        </div>
        <div class="signature-box">
            <div class="line">Verified by</div>
        </div>
    </div>
</body>
</html>
