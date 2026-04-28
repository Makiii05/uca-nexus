<?php

namespace Database\Seeders;

use App\Models\Applicant;
use Illuminate\Database\Seeder;

class ApplicantsBulkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $lastSchools = [
            ['name' => 'Quezon City National High School', 'address' => 'Quezon City'],
            ['name' => 'Manila Science High School', 'address' => 'Manila'],
            ['name' => 'Pasig City Senior High School', 'address' => 'Pasig City'],
            ['name' => 'Makati Senior High School', 'address' => 'Makati City'],
            ['name' => 'Rizal National High School', 'address' => 'Rizal'],
            ['name' => 'Caloocan City Senior High School', 'address' => 'Caloocan City'],
            ['name' => 'Taguig Integrated High School', 'address' => 'Taguig City'],
        ];

        $firstNames = [
            'Luis', 'Andrea', 'Mark', 'Janelle', 'Paolo', 'Katrina', 'Joshua', 'Angela', 'Ramon', 'Bianca',
            'Carl', 'Denise', 'Miguel', 'Patricia', 'Kevin', 'Rose', 'Noel', 'Shane', 'Ian', 'Faith',
        ];

        $lastNames = [
            'Navarro', 'Reyes', 'Santos', 'Dela Cruz', 'Garcia', 'Mendoza', 'Torres', 'Castro', 'Aquino', 'Ramos',
            'Villanueva', 'Domingo', 'Bautista', 'Fernandez', 'Lopez', 'Morales', 'Gutierrez', 'Pascual', 'Salazar', 'Cruz',
        ];

        $middleNames = [
            'Reyes', 'Santos', 'Garcia', 'Lopez', 'Torres', 'Castillo', 'Mendoza', 'Panganiban', 'Marquez', 'Cortez',
        ];

        $programChoices = [1, 2, 3, 4];

        for ($i = 1; $i <= 100; $i++) {
            $school = $lastSchools[($i - 1) % count($lastSchools)];

            $first = $firstNames[($i - 1) % count($firstNames)];
            $last = $lastNames[(($i - 1) * 3) % count($lastNames)];
            $middle = $middleNames[(($i - 1) * 2) % count($middleNames)];

            $choiceA = $programChoices[($i - 1) % 4];
            $choiceB = $programChoices[$i % 4];
            $choiceC = $programChoices[($i + 1) % 4];

            $birthYear = 2005 + (($i - 1) % 3);
            $birthMonth = str_pad((string) ((($i - 1) % 12) + 1), 2, '0', STR_PAD_LEFT);
            $birthDay = str_pad((string) ((($i - 1) % 28) + 1), 2, '0', STR_PAD_LEFT);

            Applicant::create([
                'application_no' => '20260318' . str_pad((string) $i, 8, '0', STR_PAD_LEFT),
                'level' => 'College',
                'student_type' => 'new',
                'year_level' => '1st Year',
                'strand' => null,
                'first_program_choice' => (string) $choiceA,
                'second_program_choice' => (string) $choiceB,
                'third_program_choice' => (string) $choiceC,
                'last_name' => $last,
                'first_name' => $first,
                'middle_name' => $middle,
                'sex' => $i % 2 === 0 ? 'female' : 'male',
                'citizenship' => 'Filipino',
                'religion' => 'Christian',
                'birthdate' => $birthYear . '-' . $birthMonth . '-' . $birthDay,
                'place_of_birth' => 'Quezon City',
                'civil_status' => 'single',
                'zip_code' => 1100,
                'present_address' => ($i + 20) . ' Mabini St., Quezon City',
                'permanent_address' => ($i + 20) . ' Mabini St., Quezon City',
                'telephone_number' => 'N/A',
                'mobile_number' => '0918' . str_pad((string) (1000000 + $i), 7, '0', STR_PAD_LEFT),
                'email' => strtolower(str_replace(' ', '', $first . '.' . $last)) . $i . '@email.com',
                'mother_name' => 'Maria ' . $middle . ' ' . $last,
                'mother_occupation' => 'Teacher',
                'mother_contact_number' => '0917' . str_pad((string) (2000000 + $i), 7, '0', STR_PAD_LEFT),
                'mother_monthly_income' => 25000 + ($i * 100),
                'father_name' => 'Jose ' . $last,
                'father_occupation' => 'Driver',
                'father_contact_number' => '0919' . str_pad((string) (3000000 + $i), 7, '0', STR_PAD_LEFT),
                'father_monthly_income' => 18000 + ($i * 90),
                'guardian_name' => 'Maria ' . $middle . ' ' . $last,
                'guardian_occupation' => 'Teacher',
                'guardian_contact_number' => '0917' . str_pad((string) (2000000 + $i), 7, '0', STR_PAD_LEFT),
                'guardian_monthly_income' => 25000 + ($i * 100),
                'elementary_school_name' => 'QC Central Elementary School',
                'elementary_school_address' => 'Quezon City',
                'elementary_inclusive_years' => '2012-2018',
                'junior_school_name' => 'Quezon City National High School',
                'junior_school_address' => 'Quezon City',
                'junior_inclusive_years' => '2018-2022',
                'senior_school_name' => $school['name'],
                'senior_school_address' => $school['address'],
                'senior_inclusive_years' => '2022-2024',
                'college_school_name' => 'N/A',
                'college_school_address' => 'N/A',
                'college_inclusive_years' => 'N/A',
                'lrn' => 201200000 + $i,
                'status' => 'pending',
                'academic_year' => '2025 - 2026',
                'reject_reason' => null,
            ]);
        }
    }
}
