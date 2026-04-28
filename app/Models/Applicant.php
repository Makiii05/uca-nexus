<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Applicant extends Model
{
    //
    protected $fillable = [
        "application_no",
        "level",
        "student_type",
        "year_level",
        "strand",
        "first_program_choice",
        "second_program_choice",
        "third_program_choice",
        "last_name",
        "first_name",
        "middle_name",
        "sex",
        "citizenship",
        "religion",
        "birthdate",
        "place_of_birth",
        "civil_status",
        "present_address",
        "zip_code",
        "permanent_address",
        "telephone_number",
        "mobile_number",
        "email",
        "mother_name",
        "mother_occupation",
        "mother_contact_number",
        "mother_monthly_income",
        "father_name",
        "father_occupation",
        "father_contact_number",
        "father_monthly_income",
        "guardian_name",
        "guardian_occupation",
        "guardian_contact_number",
        "guardian_monthly_income",
        "elementary_school_name",
        "elementary_school_address",
        "elementary_inclusive_years",
        "junior_school_name",
        "junior_school_address",
        "junior_inclusive_years",
        "senior_school_name",
        "senior_school_address",
        "senior_inclusive_years",
        "college_school_name",
        "college_school_address",
        "college_inclusive_years",
        "lrn",
        "status",
        "reject_reason",
        "academic_year",
    ];

    protected $appends = [
        'strand_name',
        'first_program_choice_name',
        'second_program_choice_name',
        'third_program_choice_name',
    ];

    // ── Relationships ──

    public function admission()
    {
        return $this->hasOne(Admission::class);
    }

    // ── Accessors: resolve program IDs to readable names ──

    public function getStrandNameAttribute(): ?string
    {
        if (!$this->strand) return null;
        $program = Program::find($this->strand);
        return $program ? "{$program->description}" : null;
    }

    public function getFirstProgramChoiceNameAttribute(): ?string
    {
        if (!$this->first_program_choice) return null;
        $program = Program::find($this->first_program_choice);
        return $program ? "{$program->description}" : null;
    }

    public function getSecondProgramChoiceNameAttribute(): ?string
    {
        if (!$this->second_program_choice) return null;
        $program = Program::find($this->second_program_choice);
        return $program ? "{$program->description}" : null;
    }

    public function getThirdProgramChoiceNameAttribute(): ?string
    {
        if (!$this->third_program_choice) return null;
        $program = Program::find($this->third_program_choice);
        return $program ? "{$program->description}" : null;
    }
}
