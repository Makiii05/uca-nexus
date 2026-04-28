<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/x-icon">
    <title>{{ env('APP_NAME') }} - Application Details</title>
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
        .grid {
            display: table;
            width: 100%;
        }
        .grid-row {
            display: table-row;
        }
        .grid-2 .field { width: 50%; }
        .grid-3 .field { width: 33.33%; }
        .grid-4 .field { width: 25%; }
        .field {
            display: table-cell;
            padding: 3px 10px 3px 0;
            vertical-align: top;
        }
        .label {
            font-size: 10px;
            font-weight: bold;
        }
        .value {
            font-size: 12px;
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
    @foreach($applicants as $applicant)
    <p class="print-date">Printed: {{ date('F d, Y') }}</p>
    
    <!-- Header -->
    <div class="header">
        <img src="{{ public_path('images/logo.png') }}" alt="Logo">
        <h1>{{ strtoupper(env('APP_NAME')) }}</h1>
        <p>School Address</p>
        <p>000-0000-000 | example@example.com</p>
    </div>
    
    <div class="title">APPLICATION DETAILS</div>
    
    <!-- Application Information -->
    <div class="section">
        <div class="section-title">I. APPLICATION INFORMATION</div>
        <div class="grid grid-3">
            <div class="field">
                <p class="label">Application No.</p>
                <p class="value">{{ $applicant->application_no ?? '—' }}</p>
            </div>
            <div class="field">
                <p class="label">Status</p>
                <p class="value">{{ ucfirst($applicant->status ?? '—') }}</p>
            </div>
            <div class="field">
                <p class="label">LRN</p>
                <p class="value">{{ $applicant->lrn ?? '—' }}</p>
            </div>
        </div>
    </div>

    <!-- Academic Preferences -->
    <div class="section">
        <div class="section-title">II. ACADEMIC PREFERENCES</div>
        <div class="grid grid-3">
            <div class="field">
                <p class="label">Level</p>
                <p class="value">{{ $applicant->level ?? '—' }}</p>
            </div>
            <div class="field">
                <p class="label">Student Type</p>
                <p class="value">{{ ucfirst($applicant->student_type ?? '—') }}</p>
            </div>
            <div class="field">
                <p class="label">Year Level</p>
                <p class="value">{{ $applicant->year_level ?? '—' }}</p>
            </div>
        </div>
        <div class="grid grid-3" style="margin-top: 8px;">
            <div class="field">
                <p class="label">Strand</p>
                <p class="value">{{ $applicant->strand ?? '—' }}</p>
            </div>
            <div class="field">
                <p class="label">1st Program Choice</p>
                <p class="value">{{ $applicant->first_program_choice ?? '—' }}</p>
            </div>
            <div class="field">
                <p class="label">2nd Program Choice</p>
                <p class="value">{{ $applicant->second_program_choice ?? '—' }}</p>
            </div>
        </div>
        <div class="grid grid-3" style="margin-top: 8px;">
            <div class="field">
                <p class="label">3rd Program Choice</p>
                <p class="value">{{ $applicant->third_program_choice ?? '—' }}</p>
            </div>
        </div>
    </div>

    <!-- Personal Information -->
    <div class="section">
        <div class="section-title">III. PERSONAL INFORMATION</div>
        <div class="grid grid-3">
            <div class="field">
                <p class="label">Last Name</p>
                <p class="value">{{ $applicant->last_name ?? '—' }}</p>
            </div>
            <div class="field">
                <p class="label">First Name</p>
                <p class="value">{{ $applicant->first_name ?? '—' }}</p>
            </div>
            <div class="field">
                <p class="label">Middle Name</p>
                <p class="value">{{ $applicant->middle_name ?? '—' }}</p>
            </div>
        </div>
        <div class="grid grid-3" style="margin-top: 8px;">
            <div class="field">
                <p class="label">Sex</p>
                <p class="value">{{ ucfirst($applicant->sex ?? '—') }}</p>
            </div>
            <div class="field">
                <p class="label">Civil Status</p>
                <p class="value">{{ ucfirst($applicant->civil_status ?? '—') }}</p>
            </div>
            <div class="field">
                <p class="label">Citizenship</p>
                <p class="value">{{ $applicant->citizenship ?? '—' }}</p>
            </div>
        </div>
        <div class="grid grid-3" style="margin-top: 8px;">
            <div class="field">
                <p class="label">Birthdate</p>
                <p class="value">{{ $applicant->birthdate ? \Carbon\Carbon::parse($applicant->birthdate)->format('F d, Y') : '—' }}</p>
            </div>
            <div class="field">
                <p class="label">Place of Birth</p>
                <p class="value">{{ $applicant->place_of_birth ?? '—' }}</p>
            </div>
            <div class="field">
                <p class="label">Religion</p>
                <p class="value">{{ $applicant->religion ?? '—' }}</p>
            </div>
        </div>
    </div>

    <!-- Contact Information -->
    <div class="section">
        <div class="section-title">IV. CONTACT INFORMATION</div>
        <div class="grid grid-2">
            <div class="field">
                <p class="label">Present Address</p>
                <p class="value">{{ $applicant->present_address ?? '—' }}</p>
            </div>
            <div class="field">
                <p class="label">Permanent Address</p>
                <p class="value">{{ $applicant->permanent_address ?? '—' }}</p>
            </div>
        </div>
        <div class="grid grid-4" style="margin-top: 8px;">
            <div class="field">
                <p class="label">Zip Code</p>
                <p class="value">{{ $applicant->zip_code ?? '—' }}</p>
            </div>
            <div class="field">
                <p class="label">Mobile Number</p>
                <p class="value">{{ $applicant->mobile_number ?? '—' }}</p>
            </div>
            <div class="field">
                <p class="label">Telephone Number</p>
                <p class="value">{{ $applicant->telephone_number ?? '—' }}</p>
            </div>
            <div class="field">
                <p class="label">Email Address</p>
                <p class="value">{{ $applicant->email ?? '—' }}</p>
            </div>
        </div>
    </div>

    <!-- Family Background -->
    <div class="section">
        <div class="section-title">V. FAMILY BACKGROUND</div>
        <table>
            <thead>
                <tr>
                    <th>Relation</th>
                    <th>Name</th>
                    <th>Occupation</th>
                    <th>Contact Number</th>
                    <th>Monthly Income</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Mother</td>
                    <td>{{ $applicant->mother_name ?? '—' }}</td>
                    <td>{{ $applicant->mother_occupation ?? '—' }}</td>
                    <td>{{ $applicant->mother_contact_number ?? '—' }}</td>
                    <td>{{ $applicant->mother_monthly_income ?? '—' }}</td>
                </tr>
                <tr>
                    <td>Father</td>
                    <td>{{ $applicant->father_name ?? '—' }}</td>
                    <td>{{ $applicant->father_occupation ?? '—' }}</td>
                    <td>{{ $applicant->father_contact_number ?? '—' }}</td>
                    <td>{{ $applicant->father_monthly_income ?? '—' }}</td>
                </tr>
                <tr>
                    <td>Guardian</td>
                    <td>{{ $applicant->guardian_name ?? '—' }}</td>
                    <td>{{ $applicant->guardian_occupation ?? '—' }}</td>
                    <td>{{ $applicant->guardian_contact_number ?? '—' }}</td>
                    <td>{{ $applicant->guardian_monthly_income ?? '—' }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Educational Background -->
    <div class="section">
        <div class="section-title">VI. EDUCATIONAL BACKGROUND</div>
        <table>
            <thead>
                <tr>
                    <th>Education Level</th>
                    <th>School Name</th>
                    <th>School Address</th>
                    <th>Inclusive Years</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Elementary</td>
                    <td>{{ $applicant->elementary_school_name ?? '—' }}</td>
                    <td>{{ $applicant->elementary_school_address ?? '—' }}</td>
                    <td>{{ $applicant->elementary_inclusive_years ?? '—' }}</td>
                </tr>
                <tr>
                    <td>Junior High School</td>
                    <td>{{ $applicant->junior_school_name ?? '—' }}</td>
                    <td>{{ $applicant->junior_school_address ?? '—' }}</td>
                    <td>{{ $applicant->junior_inclusive_years ?? '—' }}</td>
                </tr>
                <tr>
                    <td>Senior High School</td>
                    <td>{{ $applicant->senior_school_name ?? '—' }}</td>
                    <td>{{ $applicant->senior_school_address ?? '—' }}</td>
                    <td>{{ $applicant->senior_inclusive_years ?? '—' }}</td>
                </tr>
                <tr>
                    <td>College (if applicable)</td>
                    <td>{{ $applicant->college_school_name ?? '—' }}</td>
                    <td>{{ $applicant->college_school_address ?? '—' }}</td>
                    <td>{{ $applicant->college_inclusive_years ?? '—' }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    @endforeach
    
    <div class="footer">
        <p>This report was automatically generated by the Enrollment System.</p>
        <p>© {{ date('Y') }} All Rights Reserved</p>
    </div>
</body>
</html>
    