<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/x-icon">
    <title>{{ env('APP_NAME') }} - Official Student Details</title>
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
            position: relative;
        }
        .header img.logo {
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
        
        /* Profile Picture Styling */
        .profile-picture-container {
            position: absolute;
            top: 0;
            right: 0;
            width: 80px;
            height: 80px;
            border: 1px solid #000;
        }
        .profile-picture-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .profile-picture-container .placeholder {
            width: 100%;
            height: 100%;
            display: table;
            background: #eee;
        }
        .profile-picture-container .placeholder span {
            display: table-cell;
            vertical-align: middle;
            text-align: center;
            font-size: 10px;
            color: #666;
        }

        .title {
            text-align: center;
            font-weight: bold;
            font-size: 12px;
            padding: 5px 0;
            border-top: 1px solid #000;
            border-bottom: 1px solid #000;
            margin-bottom: 15px;
            margin-top: 30px; /* Space for PFP if it overlaps */
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
    <p class="print-date">Printed: {{ date('F d, Y') }}</p>
    
    <!-- Header -->
    <div class="header">
        <img class="logo" src="{{ public_path('images/logo.png') }}" alt="Logo">
        <h1>{{ strtoupper(env('APP_NAME')) }}</h1>
        <p>School Address</p>
        <p>000-0000-000 | example@example.com</p>

        <!-- Profile Picture positioned top right relative to header -->
        <div class="profile-picture-container">
            @if($student->profilePicture && file_exists(public_path('assets/images/profile_picture/' . $student->profilePicture->filename)))
                <img src="{{ public_path('assets/images/profile_picture/' . $student->profilePicture->filename) }}" alt="Profile Picture">
            @else
                <div class="placeholder"><span>No PFP</span></div>
            @endif
        </div>
    </div>
    
    <div class="title">OFFICIAL STUDENT DETAILS</div>
    
    <!-- Student Information -->
    <div class="section">
        <div class="section-title">I. STUDENT INFORMATION</div>
        <div class="grid grid-3">
            <div class="field">
                <p class="label">Student No.</p>
                <p class="value">{{ $student->student_number ?? '—' }}</p>
            </div>
            <div class="field">
                <p class="label">Status</p>
                <p class="value">{{ ucfirst($student->status ?? '—') }}</p>
            </div>
            <div class="field">
                <p class="label">LRN</p>
                <p class="value">{{ $student->lrn ?? '—' }}</p>
            </div>
        </div>
    </div>

    <!-- Academic Information -->
    <div class="section">
        <div class="section-title">II. ACADEMIC INFORMATION</div>
        <div class="grid grid-3">
            <div class="field">
                <p class="label">Department</p>
                <p class="value">{{ $student->department->description ?? '—' }}</p>
            </div>
            <div class="field">
                <p class="label">Program</p>
                <p class="value">{{ $student->program->description ?? '—' }}</p>
            </div>
            <div class="field">
                <p class="label">Level</p>
                <p class="value">{{ $student->level->description ?? '—' }}</p>
            </div>
        </div>
    </div>

    <!-- Personal Information -->
    <div class="section">
        <div class="section-title">III. PERSONAL INFORMATION</div>
        <div class="grid grid-3">
            <div class="field">
                <p class="label">Last Name</p>
                <p class="value">{{ $student->last_name ?? '—' }}</p>
            </div>
            <div class="field">
                <p class="label">First Name</p>
                <p class="value">{{ $student->first_name ?? '—' }}</p>
            </div>
            <div class="field">
                <p class="label">Middle Name</p>
                <p class="value">{{ $student->middle_name ?? '—' }}</p>
            </div>
        </div>
        <div class="grid grid-3" style="margin-top: 8px;">
            <div class="field">
                <p class="label">Sex</p>
                <p class="value">{{ ucfirst($student->sex ?? '—') }}</p>
            </div>
            <div class="field">
                <p class="label">Civil Status</p>
                <p class="value">{{ ucfirst($student->civil_status ?? '—') }}</p>
            </div>
            <div class="field">
                <p class="label">Citizenship</p>
                <p class="value">{{ $student->citizenship ?? '—' }}</p>
            </div>
        </div>
        <div class="grid grid-3" style="margin-top: 8px;">
            <div class="field">
                <p class="label">Birthdate</p>
                <p class="value">{{ $student->birthdate ? \Carbon\Carbon::parse($student->birthdate)->format('F d, Y') : '—' }}</p>
            </div>
            <div class="field">
                <p class="label">Place of Birth</p>
                <p class="value">{{ $student->place_of_birth ?? '—' }}</p>
            </div>
            <div class="field">
                <p class="label">Religion</p>
                <p class="value">{{ $student->religion ?? '—' }}</p>
            </div>
        </div>
    </div>

    <!-- Contact Information -->
    <div class="section">
        <div class="section-title">IV. CONTACT INFORMATION</div>
        <div class="grid grid-2">
            <div class="field">
                <p class="label">Present Address</p>
                <p class="value">{{ $student->contact->present_address ?? '—' }}</p>
            </div>
            <div class="field">
                <p class="label">Permanent Address</p>
                <p class="value">{{ $student->contact->permanent_address ?? '—' }}</p>
            </div>
        </div>
        <div class="grid grid-4" style="margin-top: 8px;">
            <div class="field">
                <p class="label">Zip Code</p>
                <p class="value">{{ $student->contact->zip_code ?? '—' }}</p>
            </div>
            <div class="field">
                <p class="label">Mobile Number</p>
                <p class="value">{{ $student->contact->mobile_number ?? '—' }}</p>
            </div>
            <div class="field">
                <p class="label">Telephone Number</p>
                <p class="value">{{ $student->contact->telephone_number ?? '—' }}</p>
            </div>
            <div class="field">
                <p class="label">Email Address</p>
                <p class="value">{{ $student->contact->email ?? '—' }}</p>
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
                    <td>{{ $student->guardian->mother_name ?? '—' }}</td>
                    <td>{{ $student->guardian->mother_occupation ?? '—' }}</td>
                    <td>{{ $student->guardian->mother_contact_number ?? '—' }}</td>
                    <td>{{ $student->guardian->mother_monthly_income ?? '—' }}</td>
                </tr>
                <tr>
                    <td>Father</td>
                    <td>{{ $student->guardian->father_name ?? '—' }}</td>
                    <td>{{ $student->guardian->father_occupation ?? '—' }}</td>
                    <td>{{ $student->guardian->father_contact_number ?? '—' }}</td>
                    <td>{{ $student->guardian->father_monthly_income ?? '—' }}</td>
                </tr>
                <tr>
                    <td>Guardian</td>
                    <td>{{ $student->guardian->guardian_name ?? '—' }}</td>
                    <td>{{ $student->guardian->guardian_occupation ?? '—' }}</td>
                    <td>{{ $student->guardian->guardian_contact_number ?? '—' }}</td>
                    <td>{{ $student->guardian->guardian_monthly_income ?? '—' }}</td>
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
                    <td>{{ $student->academicHistory->elementary_school_name ?? '—' }}</td>
                    <td>{{ $student->academicHistory->elementary_school_address ?? '—' }}</td>
                    <td>{{ $student->academicHistory->elementary_inclusive_years ?? '—' }}</td>
                </tr>
                <tr>
                    <td>Junior High School</td>
                    <td>{{ $student->academicHistory->junior_school_name ?? '—' }}</td>
                    <td>{{ $student->academicHistory->junior_school_address ?? '—' }}</td>
                    <td>{{ $student->academicHistory->junior_inclusive_years ?? '—' }}</td>
                </tr>
                <tr>
                    <td>Senior High School</td>
                    <td>{{ $student->academicHistory->senior_school_name ?? '—' }}</td>
                    <td>{{ $student->academicHistory->senior_school_address ?? '—' }}</td>
                    <td>{{ $student->academicHistory->senior_inclusive_years ?? '—' }}</td>
                </tr>
                <tr>
                    <td>College</td>
                    <td>{{ $student->academicHistory->college_school_name ?? '—' }}</td>
                    <td>{{ $student->academicHistory->college_school_address ?? '—' }}</td>
                    <td>{{ $student->academicHistory->college_inclusive_years ?? '—' }}</td>
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
