<x-admission_sidebar>

    @include('partials.notifications')

    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('admission.applicant') }}" class="btn btn-ghost btn-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
            </svg>
            Back
        </a>
        <h2 class="font-bold text-4xl flex-1">Edit Applicant</h2>
    </div>

    <form action="{{ route('admission.applicant.update', $applicant->id) }}" method="POST">
        @csrf
        
        <!-- Application Info (Read-only) -->
        <div class="bg-gray-50 p-6 rounded-lg mb-6 shadow">
            <h3 class="font-semibold text-xl mb-4 text-primary">Application Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="form-control">
                    <label class="label"><span class="label-text">Application No.</span></label>
                    <input type="text" value="{{ $applicant->application_no }}" class="input input-bordered bg-gray-100" disabled />
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text">Status</span></label>
                    <input type="text" value="{{ ucfirst($applicant->status) }}" class="input input-bordered bg-gray-100" disabled />
                </div>

            </div>
        </div>

        <!-- Academic Preferences -->
        <div class="bg-blue-50 p-6 rounded-lg mb-6 shadow">
            <h3 class="font-semibold text-xl mb-4 text-primary">Academic Preferences</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="form-control">
                    <label class="label"><span class="label-text">Level</span></label>
                    <select name="level" class="select select-bordered">
                        <option value="">Select Level</option>
                        <option value="Nursery" {{ old('level', $applicant->level) == 'Nursery' ? 'selected' : '' }}>Nursery</option>
                        <option value="Kindergarten" {{ old('level', $applicant->level) == 'Kindergarten' ? 'selected' : '' }}>Kindergarten</option>
                        <option value="Grade School" {{ old('level', $applicant->level) == 'Grade School' ? 'selected' : '' }}>Grade School</option>
                        <option value="Junior High School" {{ old('level', $applicant->level) == 'Junior High School' ? 'selected' : '' }}>Junior High School</option>
                        <option value="Senior High School" {{ old('level', $applicant->level) == 'Senior High School' ? 'selected' : '' }}>Senior High School</option>
                        <option value="College" {{ old('level', $applicant->level) == 'College' ? 'selected' : '' }}>College</option>
                    </select>
                    @error('level')<span class="text-error text-sm">{{ $message }}</span>@enderror
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text">Student Type</span></label>
                    <select name="student_type" class="select select-bordered">
                        <option value="">Select Type</option>
                        <option value="new" {{ old('student_type', $applicant->student_type) == 'new' ? 'selected' : '' }}>New</option>
                        <option value="transferee" {{ old('student_type', $applicant->student_type) == 'transferee' ? 'selected' : '' }}>Transferee</option>
                        <option value="returning" {{ old('student_type', $applicant->student_type) == 'returning' ? 'selected' : '' }}>Returning</option>
                    </select>
                    @error('student_type')<span class="text-error text-sm">{{ $message }}</span>@enderror
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text">Year Level</span></label>
                    <input type="text" name="year_level" value="{{ old('year_level', $applicant->year_level) }}" class="input input-bordered" />
                    @error('year_level')<span class="text-error text-sm">{{ $message }}</span>@enderror
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text">Strand (SHS)</span></label>
                    <select name="strand" class="select select-bordered">
                        <option value="">Select Strand</option>
                        @foreach($strands as $strand)
                        <option value="{{ $strand->id }}" {{ old('strand', $applicant->strand) == $strand->id ? 'selected' : '' }}>
                            {{ $strand->code }} - {{ $strand->description }}
                        </option>
                        @endforeach
                    </select>
                    @error('strand')<span class="text-error text-sm">{{ $message }}</span>@enderror
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text">1st Program Choice</span></label>
                    <select name="first_program_choice" class="select select-bordered">
                        <option value="">Select Program</option>
                        @foreach($college_programs as $program)
                        <option value="{{ $program->id }}" {{ old('first_program_choice', $applicant->first_program_choice) == $program->id ? 'selected' : '' }}>
                            {{ $program->code }} - {{ $program->description }}
                        </option>
                        @endforeach
                    </select>
                    @error('first_program_choice')<span class="text-error text-sm">{{ $message }}</span>@enderror
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text">2nd Program Choice</span></label>
                    <select name="second_program_choice" class="select select-bordered">
                        <option value="">Select Program</option>
                        @foreach($college_programs as $program)
                        <option value="{{ $program->id }}" {{ old('second_program_choice', $applicant->second_program_choice) == $program->id ? 'selected' : '' }}>
                            {{ $program->code }} - {{ $program->description }}
                        </option>
                        @endforeach
                    </select>
                    @error('second_program_choice')<span class="text-error text-sm">{{ $message }}</span>@enderror
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text">3rd Program Choice</span></label>
                    <select name="third_program_choice" class="select select-bordered">
                        <option value="">Select Program</option>
                        @foreach($college_programs as $program)
                        <option value="{{ $program->id }}" {{ old('third_program_choice', $applicant->third_program_choice) == $program->id ? 'selected' : '' }}>
                            {{ $program->code }} - {{ $program->description }}
                        </option>
                        @endforeach
                    </select>
                    @error('third_program_choice')<span class="text-error text-sm">{{ $message }}</span>@enderror
                </div>
            </div>
        </div>

        <!-- Personal Information -->
        <div class="bg-green-50 p-6 rounded-lg mb-6 shadow">
            <h3 class="font-semibold text-xl mb-4 text-primary">Personal Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="form-control">
                    <label class="label"><span class="label-text">Last Name *</span></label>
                    <input type="text" name="last_name" value="{{ old('last_name', $applicant->last_name) }}" class="input input-bordered" required />
                    @error('last_name')<span class="text-error text-sm">{{ $message }}</span>@enderror
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text">First Name *</span></label>
                    <input type="text" name="first_name" value="{{ old('first_name', $applicant->first_name) }}" class="input input-bordered" required />
                    @error('first_name')<span class="text-error text-sm">{{ $message }}</span>@enderror
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text">Middle Name</span></label>
                    <input type="text" name="middle_name" value="{{ old('middle_name', $applicant->middle_name) }}" class="input input-bordered" />
                    @error('middle_name')<span class="text-error text-sm">{{ $message }}</span>@enderror
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text">Sex *</span></label>
                    <select name="sex" class="select select-bordered" required>
                        <option value="Male" {{ old('sex', $applicant->sex) == 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ old('sex', $applicant->sex) == 'Female' ? 'selected' : '' }}>Female</option>
                    </select>
                    @error('sex')<span class="text-error text-sm">{{ $message }}</span>@enderror
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text">Birthdate</span></label>
                    <input type="date" name="birthdate" value="{{ old('birthdate', $applicant->birthdate) }}" class="input input-bordered" />
                    @error('birthdate')<span class="text-error text-sm">{{ $message }}</span>@enderror
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text">Place of Birth</span></label>
                    <input type="text" name="place_of_birth" value="{{ old('place_of_birth', $applicant->place_of_birth) }}" class="input input-bordered" />
                    @error('place_of_birth')<span class="text-error text-sm">{{ $message }}</span>@enderror
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text">Citizenship</span></label>
                    <input type="text" name="citizenship" value="{{ old('citizenship', $applicant->citizenship) }}" class="input input-bordered" />
                    @error('citizenship')<span class="text-error text-sm">{{ $message }}</span>@enderror
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text">Religion</span></label>
                    <input type="text" name="religion" value="{{ old('religion', $applicant->religion) }}" class="input input-bordered" />
                    @error('religion')<span class="text-error text-sm">{{ $message }}</span>@enderror
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text">Civil Status</span></label>
                    <select name="civil_status" class="select select-bordered">
                        <option value="">Select Civil Status</option>
                        <option value="single" {{ old('civil_status', $applicant->civil_status) == 'single' ? 'selected' : '' }}>Single</option>
                        <option value="married" {{ old('civil_status', $applicant->civil_status) == 'married' ? 'selected' : '' }}>Married</option>
                        <option value="widowed" {{ old('civil_status', $applicant->civil_status) == 'widowed' ? 'selected' : '' }}>Widowed</option>
                        <option value="separated" {{ old('civil_status', $applicant->civil_status) == 'separated' ? 'selected' : '' }}>Separated</option>
                    </select>
                    @error('civil_status')<span class="text-error text-sm">{{ $message }}</span>@enderror
                </div>
            </div>
        </div>

        <!-- Contact Information -->
        <div class="bg-yellow-50 p-6 rounded-lg mb-6 shadow">
            <h3 class="font-semibold text-xl mb-4 text-primary">Contact Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="form-control">
                    <label class="label"><span class="label-text">Email *</span></label>
                    <input type="email" name="email" value="{{ old('email', $applicant->email) }}" class="input input-bordered" required />
                    @error('email')<span class="text-error text-sm">{{ $message }}</span>@enderror
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text">Mobile Number</span></label>
                    <input type="text" name="mobile_number" value="{{ old('mobile_number', $applicant->mobile_number) }}" class="input input-bordered" />
                    @error('mobile_number')<span class="text-error text-sm">{{ $message }}</span>@enderror
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text">Telephone Number</span></label>
                    <input type="text" name="telephone_number" value="{{ old('telephone_number', $applicant->telephone_number) }}" class="input input-bordered" />
                    @error('telephone_number')<span class="text-error text-sm">{{ $message }}</span>@enderror
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text">Zip Code</span></label>
                    <input type="text" name="zip_code" value="{{ old('zip_code', $applicant->zip_code) }}" class="input input-bordered" />
                    @error('zip_code')<span class="text-error text-sm">{{ $message }}</span>@enderror
                </div>
                <div class="form-control md:col-span-2">
                    <label class="label"><span class="label-text">Present Address</span></label>
                    <textarea name="present_address" class="textarea textarea-bordered" rows="2">{{ old('present_address', $applicant->present_address) }}</textarea>
                    @error('present_address')<span class="text-error text-sm">{{ $message }}</span>@enderror
                </div>
                <div class="form-control md:col-span-3">
                    <label class="label"><span class="label-text">Permanent Address</span></label>
                    <textarea name="permanent_address" class="textarea textarea-bordered" rows="2">{{ old('permanent_address', $applicant->permanent_address) }}</textarea>
                    @error('permanent_address')<span class="text-error text-sm">{{ $message }}</span>@enderror
                </div>
            </div>
        </div>

        <!-- Family Background -->
        <div class="bg-purple-50 p-6 rounded-lg mb-6 shadow">
            <h3 class="font-semibold text-xl mb-4 text-primary">Family Background</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Mother -->
                <div class="border-r border-purple-200 pr-4">
                    <h4 class="font-medium text-purple-700 mb-3">Mother</h4>
                    <div class="space-y-3">
                        <div class="form-control">
                            <label class="label"><span class="label-text">Name</span></label>
                            <input type="text" name="mother_name" value="{{ old('mother_name', $applicant->mother_name) }}" class="input input-bordered input-sm" />
                        </div>
                        <div class="form-control">
                            <label class="label"><span class="label-text">Occupation</span></label>
                            <input type="text" name="mother_occupation" value="{{ old('mother_occupation', $applicant->mother_occupation) }}" class="input input-bordered input-sm" />
                        </div>
                        <div class="form-control">
                            <label class="label"><span class="label-text">Contact</span></label>
                            <input type="text" name="mother_contact_number" value="{{ old('mother_contact_number', $applicant->mother_contact_number) }}" class="input input-bordered input-sm" />
                        </div>
                        <div class="form-control">
                            <label class="label"><span class="label-text">Monthly Income</span></label>
                            <input type="number" name="mother_monthly_income" value="{{ old('mother_monthly_income', $applicant->mother_monthly_income) }}" class="input input-bordered input-sm" />
                        </div>
                    </div>
                </div>
                <!-- Father -->
                <div class="border-r border-purple-200 pr-4">
                    <h4 class="font-medium text-purple-700 mb-3">Father</h4>
                    <div class="space-y-3">
                        <div class="form-control">
                            <label class="label"><span class="label-text">Name</span></label>
                            <input type="text" name="father_name" value="{{ old('father_name', $applicant->father_name) }}" class="input input-bordered input-sm" />
                        </div>
                        <div class="form-control">
                            <label class="label"><span class="label-text">Occupation</span></label>
                            <input type="text" name="father_occupation" value="{{ old('father_occupation', $applicant->father_occupation) }}" class="input input-bordered input-sm" />
                        </div>
                        <div class="form-control">
                            <label class="label"><span class="label-text">Contact</span></label>
                            <input type="text" name="father_contact_number" value="{{ old('father_contact_number', $applicant->father_contact_number) }}" class="input input-bordered input-sm" />
                        </div>
                        <div class="form-control">
                            <label class="label"><span class="label-text">Monthly Income</span></label>
                            <input type="number" name="father_monthly_income" value="{{ old('father_monthly_income', $applicant->father_monthly_income) }}" class="input input-bordered input-sm" />
                        </div>
                    </div>
                </div>
                <!-- Guardian -->
                <div>
                    <h4 class="font-medium text-purple-700 mb-3">Guardian</h4>
                    <div class="space-y-3">
                        <div class="form-control">
                            <label class="label"><span class="label-text">Name</span></label>
                            <input type="text" name="guardian_name" value="{{ old('guardian_name', $applicant->guardian_name) }}" class="input input-bordered input-sm" />
                        </div>
                        <div class="form-control">
                            <label class="label"><span class="label-text">Occupation</span></label>
                            <input type="text" name="guardian_occupation" value="{{ old('guardian_occupation', $applicant->guardian_occupation) }}" class="input input-bordered input-sm" />
                        </div>
                        <div class="form-control">
                            <label class="label"><span class="label-text">Contact</span></label>
                            <input type="text" name="guardian_contact_number" value="{{ old('guardian_contact_number', $applicant->guardian_contact_number) }}" class="input input-bordered input-sm" />
                        </div>
                        <div class="form-control">
                            <label class="label"><span class="label-text">Monthly Income</span></label>
                            <input type="number" name="guardian_monthly_income" value="{{ old('guardian_monthly_income', $applicant->guardian_monthly_income) }}" class="input input-bordered input-sm" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Educational Background -->
        <div class="bg-orange-50 p-6 rounded-lg mb-6 shadow">
            <h3 class="font-semibold text-xl mb-4 text-primary">Educational Background</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Elementary -->
                <div>
                    <h4 class="font-medium text-orange-700 mb-3">Elementary</h4>
                    <div class="space-y-3">
                        <div class="form-control">
                            <label class="label"><span class="label-text">School Name</span></label>
                            <input type="text" name="elementary_school_name" value="{{ old('elementary_school_name', $applicant->elementary_school_name) }}" class="input input-bordered input-sm" />
                        </div>
                        <div class="form-control">
                            <label class="label"><span class="label-text">Address</span></label>
                            <input type="text" name="elementary_school_address" value="{{ old('elementary_school_address', $applicant->elementary_school_address) }}" class="input input-bordered input-sm" />
                        </div>
                        <div class="form-control">
                            <label class="label"><span class="label-text">Inclusive Years</span></label>
                            <input type="text" name="elementary_inclusive_years" value="{{ old('elementary_inclusive_years', $applicant->elementary_inclusive_years) }}" class="input input-bordered input-sm" />
                        </div>
                    </div>
                </div>
                <!-- Junior High School -->
                <div>
                    <h4 class="font-medium text-orange-700 mb-3">Junior High School</h4>
                    <div class="space-y-3">
                        <div class="form-control">
                            <label class="label"><span class="label-text">School Name</span></label>
                            <input type="text" name="junior_school_name" value="{{ old('junior_school_name', $applicant->junior_school_name) }}" class="input input-bordered input-sm" />
                        </div>
                        <div class="form-control">
                            <label class="label"><span class="label-text">Address</span></label>
                            <input type="text" name="junior_school_address" value="{{ old('junior_school_address', $applicant->junior_school_address) }}" class="input input-bordered input-sm" />
                        </div>
                        <div class="form-control">
                            <label class="label"><span class="label-text">Inclusive Years</span></label>
                            <input type="text" name="junior_inclusive_years" value="{{ old('junior_inclusive_years', $applicant->junior_inclusive_years) }}" class="input input-bordered input-sm" />
                        </div>
                    </div>
                </div>
                <!-- Senior High School -->
                <div>
                    <h4 class="font-medium text-orange-700 mb-3">Senior High School</h4>
                    <div class="space-y-3">
                        <div class="form-control">
                            <label class="label"><span class="label-text">School Name</span></label>
                            <input type="text" name="senior_school_name" value="{{ old('senior_school_name', $applicant->senior_school_name) }}" class="input input-bordered input-sm" />
                        </div>
                        <div class="form-control">
                            <label class="label"><span class="label-text">Address</span></label>
                            <input type="text" name="senior_school_address" value="{{ old('senior_school_address', $applicant->senior_school_address) }}" class="input input-bordered input-sm" />
                        </div>
                        <div class="form-control">
                            <label class="label"><span class="label-text">Inclusive Years</span></label>
                            <input type="text" name="senior_inclusive_years" value="{{ old('senior_inclusive_years', $applicant->senior_inclusive_years) }}" class="input input-bordered input-sm" />
                        </div>
                    </div>
                </div>
                <!-- College -->
                <div>
                    <h4 class="font-medium text-orange-700 mb-3">College</h4>
                    <div class="space-y-3">
                        <div class="form-control">
                            <label class="label"><span class="label-text">School Name</span></label>
                            <input type="text" name="college_school_name" value="{{ old('college_school_name', $applicant->college_school_name) }}" class="input input-bordered input-sm" />
                        </div>
                        <div class="form-control">
                            <label class="label"><span class="label-text">Address</span></label>
                            <input type="text" name="college_school_address" value="{{ old('college_school_address', $applicant->college_school_address) }}" class="input input-bordered input-sm" />
                        </div>
                        <div class="form-control">
                            <label class="label"><span class="label-text">Inclusive Years</span></label>
                            <input type="text" name="college_inclusive_years" value="{{ old('college_inclusive_years', $applicant->college_inclusive_years) }}" class="input input-bordered input-sm" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end gap-4 mb-6">
            <a href="{{ route('admission.applicant') }}" class="btn btn-ghost">Cancel</a>
            <button type="submit" class="btn btn-primary">Save Changes</button>
        </div>
    </form>

</x-admission_sidebar>
