<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ env('APP_NAME') }} - Grade Reports</title>

    <style>
        @page {
            size: A4 portrait;
            margin: 12mm;
        }

        html, body {
            margin: 0;
            padding: 0;
        }

        /* 🔥 FIX: prevent Dompdf vertical stretching issues */
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 9px;
            line-height: 1.3;
            color: #000;
            height: auto !important;
        }

        * {
            box-sizing: border-box;
        }

        .page {
            width: 100%;
            height: auto;
            display: block;
        }

        /* KEEP YOUR FRAME (stable in Dompdf) */
        .page-frame {
            width: 100%;
            border: 1px solid #000;
            padding: 6mm;
            border-collapse: collapse;
            vertical-align: top; /* 🔥 important fix */
        }

        .report {
            width: 100%;
            padding: 5mm;

            /* 🔥 REDUCE GAP (main fix for bottom whitespace) */
            margin-bottom: 2mm;

            page-break-inside: avoid;
        }

        .report:last-child {
            margin-bottom: 0;
        }

        .header {
            text-align: center;
            margin-bottom: 4mm;
        }

        .header .title {
            font-weight: bold;
            font-size: 11px;
        }

        .header .sub {
            margin-top: 1mm;
            font-size: 9px;
        }

        .meta {
            margin-bottom: 4mm;
            width: 100%;
        }

        .meta .row {
            display: table;
            width: 100%;
            table-layout: fixed;
        }

        .meta .cell {
            display: table-cell;
            padding: 1mm 2mm;
        }

        .label {
            font-weight: bold;
            font-size: 8px;
            color: #333;
        }

        table {
            width: 95%;
            border-collapse: collapse;
            margin-top: 2mm;
        }

        th, td {
            border: 1px solid #000;
            padding: 2mm;
        }

        th {
            font-size: 8px;
            text-transform: uppercase;
        }

        .text-right { text-align: right; }
        .text-center { text-align: center; }

        .footer {
            margin-top: 6mm;
            text-align: center;
        }

        .sign-line {
            border-top: 1px solid #000;
            width: 45%;
            margin: 10mm auto 0;
            padding-top: 1mm;
            font-size: 9px;
        }

        .muted {
            color: #555;
        }
    </style>
</head>

<body>

@php
    $chunks = collect($reports)->chunk(2);
@endphp

@foreach ($chunks as $chunk)
<div class="page">

    <table class="page-frame" cellpadding="0" cellspacing="0">
        <tr>
            <td>

                @foreach ($chunk as $report)

                    @php
                        $student = $report['student'];
                        $academicTerm = $report['academicTerm'];
                        $period = $report['period'];
                        $rows = $report['rows'] ?? collect();
                        $generalAverage = $report['generalAverage'] ?? null;
                        $totalUnits = $report['totalUnits'] ?? null;
                    @endphp

                    <div class="report">

                        <div class="header">
                            <div class="title">GRADE REPORT</div>
                            <div class="sub muted">
                                {{ $academicTerm?->description ?? '' }}{{ $period ? ' • ' . $period : '' }}
                            </div>
                            <div class="sub muted">Printed: {{ date('F d, Y') }}</div>
                        </div>

                        <div class="meta">
                            <div class="row">
                                <div class="cell">
                                    <div class="label">Student No.</div>
                                    {{ $student->student_number }}
                                </div>
                                <div class="cell">
                                    <div class="label">Name</div>
                                    {{ $student->last_name }}, {{ $student->first_name }} {{ $student->middle_name }}
                                </div>
                            </div>

                            <div class="row">
                                <div class="cell">
                                    <div class="label">Program</div>
                                    {{ $student->program?->code ?? '—' }}
                                </div>
                                <div class="cell">
                                    <div class="label">Year Level</div>
                                    {{ $student->level?->description ?? '—' }}
                                </div>
                            </div>
                        </div>

                        <table>
                            <thead>
                                <tr>
                                    <th style="width:18%">Code</th>
                                    <th>Description</th>
                                    <th style="width:10%">Units</th>
                                    <th style="width:14%">Grade</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($rows as $row)
                                    <tr>
                                        <td>{{ $row['subject_code'] }}</td>
                                        <td>{{ $row['description'] }}</td>
                                        <td class="text-center">{{ (float) ($row['units'] ?? 0) }}</td>
                                        <td class="text-center">
                                            {{ ($row['grade'] ?? null) !== null ? number_format((float) $row['grade'], 2) : '—' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center muted">No subjects found.</td>
                                    </tr>
                                @endforelse
                            </tbody>

                            <tfoot>
                                <tr>
                                    <td colspan="2" class="text-right"><strong>Total Units</strong></td>
                                    <td class="text-center">
                                        <strong>{{ $totalUnits !== null ? number_format((float) $totalUnits, 0) : '—' }}</strong>
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-right"><strong>General Average</strong></td>
                                    <td class="text-center">
                                        <strong>{{ $generalAverage !== null ? number_format((float) $generalAverage, 2) : '—' }}</strong>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>

                        <div class="footer">
                            <div class="sign-line">Registrar</div>
                        </div>

                    </div>

                @endforeach

            </td>
        </tr>
    </table>

</div>
@endforeach

</body>
</html>