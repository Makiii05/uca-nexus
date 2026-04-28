<x-admission_sidebar>

    @include('partials.notifications')

    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('admission.student') }}" class="btn btn-ghost btn-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
            </svg>
            Back
        </a>
        <h2 class="font-bold text-4xl flex-1">Edit Student</h2>
    </div>

    <form action="{{ route('admission.student.update', $student->id) }}" method="POST">
        @csrf
        
        <!-- Student Basic Information -->
        <div class="bg-gray-50 p-6 rounded-lg mb-6 shadow">
            <h3 class="font-semibold text-xl mb-4 text-primary">Student Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="form-control">
                    <label class="label"><span class="label-text">Student Number *</span></label>
                    <input type="text" name="student_number" value="{{ old('student_number', $student->student_number) }}" class="input input-bordered" required />
                    @error('student_number')<span class="text-error text-sm">{{ $message }}</span>@enderror
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text">LRN</span></label>
                    <input type="text" name="lrn" value="{{ old('lrn', $student->lrn) }}" class="input input-bordered" />
                    @error('lrn')<span class="text-error text-sm">{{ $message }}</span>@enderror
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text">Status *</span></label>
                    <select name="status" class="select select-bordered" required>
                        <option value="enrolled" {{ old('status', $student->status) == 'enrolled' ? 'selected' : '' }}>Enrolled</option>
                        <option value="regular" {{ old('status', $student->status) == 'regular' ? 'selected' : '' }}>Regular</option>
                        <option value="irregular" {{ old('status', $student->status) == 'irregular' ? 'selected' : '' }}>Irregular</option>
                        <option value="withdrawn" {{ old('status', $student->status) == 'withdrawn' ? 'selected' : '' }}>Withdrawn</option>
                        <option value="dropped" {{ old('status', $student->status) == 'dropped' ? 'selected' : '' }}>Dropped</option>
                        <option value="graduated" {{ old('status', $student->status) == 'graduated' ? 'selected' : '' }}>Graduated</option>
                    </select>
                    @error('status')<span class="text-error text-sm">{{ $message }}</span>@enderror
                </div>
            </div>
        </div>

        <!-- Academic Information -->
        <div class="bg-blue-50 p-6 rounded-lg mb-6 shadow">
            <h3 class="font-semibold text-xl mb-4 text-primary">Academic Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="form-control">
                    <label class="label"><span class="label-text">Department *</span></label>
                    <select name="department_id" class="select select-bordered" required>
                        <option value="">Select Department</option>
                        @foreach($departments as $department)
                        <option value="{{ $department->id }}" {{ old('department_id', $student->department_id) == $department->id ? 'selected' : '' }}>
                            {{ $department->code }} - {{ $department->description }}
                        </option>
                        @endforeach
                    </select>
                    @error('department_id')<span class="text-error text-sm">{{ $message }}</span>@enderror
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text">Program *</span></label>
                    <select name="program_id" class="select select-bordered" required>
                        <option value="">Select Program</option>
                        @foreach($programs as $program)
                        <option value="{{ $program->id }}" {{ old('program_id', $student->program_id) == $program->id ? 'selected' : '' }}>
                            {{ $program->code }} - {{ $program->description }}
                        </option>
                        @endforeach
                    </select>
                    @error('program_id')<span class="text-error text-sm">{{ $message }}</span>@enderror
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text">Level *</span></label>
                    <select name="level_id" class="select select-bordered" required>
                        <option value="">Select Level</option>
                        @foreach($levels as $level)
                        <option value="{{ $level->id }}" {{ old('level_id', $student->level_id) == $level->id ? 'selected' : '' }}>
                            {{ $level->code }} - {{ $level->description }}
                        </option>
                        @endforeach
                    </select>
                    @error('level_id')<span class="text-error text-sm">{{ $message }}</span>@enderror
                </div>
            </div>
        </div>

        <!-- Personal Information -->
        <div class="bg-green-50 p-6 rounded-lg mb-6 shadow">
            <h3 class="font-semibold text-xl mb-4 text-primary">Personal Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="form-control">
                    <label class="label"><span class="label-text">Last Name *</span></label>
                    <input type="text" name="last_name" value="{{ old('last_name', $student->last_name) }}" class="input input-bordered" required />
                    @error('last_name')<span class="text-error text-sm">{{ $message }}</span>@enderror
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text">First Name *</span></label>
                    <input type="text" name="first_name" value="{{ old('first_name', $student->first_name) }}" class="input input-bordered" required />
                    @error('first_name')<span class="text-error text-sm">{{ $message }}</span>@enderror
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text">Middle Name</span></label>
                    <input type="text" name="middle_name" value="{{ old('middle_name', $student->middle_name) }}" class="input input-bordered" />
                    @error('middle_name')<span class="text-error text-sm">{{ $message }}</span>@enderror
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text">Sex *</span></label>
                    <select name="sex" class="select select-bordered" required>
                        <option value="Male" {{ old('sex', $student->sex) == 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ old('sex', $student->sex) == 'Female' ? 'selected' : '' }}>Female</option>
                    </select>
                    @error('sex')<span class="text-error text-sm">{{ $message }}</span>@enderror
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text">Birthdate</span></label>
                    <input type="date" name="birthdate" value="{{ old('birthdate', $student->birthdate?->format('Y-m-d')) }}" class="input input-bordered" />
                    @error('birthdate')<span class="text-error text-sm">{{ $message }}</span>@enderror
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text">Place of Birth</span></label>
                    <input type="text" name="place_of_birth" value="{{ old('place_of_birth', $student->place_of_birth) }}" class="input input-bordered" />
                    @error('place_of_birth')<span class="text-error text-sm">{{ $message }}</span>@enderror
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text">Citizenship</span></label>
                    <input type="text" name="citizenship" value="{{ old('citizenship', $student->citizenship) }}" class="input input-bordered" />
                    @error('citizenship')<span class="text-error text-sm">{{ $message }}</span>@enderror
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text">Religion</span></label>
                    <input type="text" name="religion" value="{{ old('religion', $student->religion) }}" class="input input-bordered" />
                    @error('religion')<span class="text-error text-sm">{{ $message }}</span>@enderror
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text">Civil Status</span></label>
                    <select name="civil_status" class="select select-bordered">
                        <option value="">Select Civil Status</option>
                        <option value="Single" {{ old('civil_status', $student->civil_status) == 'Single' ? 'selected' : '' }}>Single</option>
                        <option value="Married" {{ old('civil_status', $student->civil_status) == 'Married' ? 'selected' : '' }}>Married</option>
                        <option value="Widowed" {{ old('civil_status', $student->civil_status) == 'Widowed' ? 'selected' : '' }}>Widowed</option>
                        <option value="Separated" {{ old('civil_status', $student->civil_status) == 'Separated' ? 'selected' : '' }}>Separated</option>
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
                    <label class="label"><span class="label-text">Email</span></label>
                    <input type="email" name="contact[email]" value="{{ old('contact.email', $student->contact->email ?? '') }}" class="input input-bordered" />
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text">Mobile Number</span></label>
                    <input type="text" name="contact[mobile_number]" value="{{ old('contact.mobile_number', $student->contact->mobile_number ?? '') }}" class="input input-bordered" />
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text">Telephone Number</span></label>
                    <input type="text" name="contact[telephone_number]" value="{{ old('contact.telephone_number', $student->contact->telephone_number ?? '') }}" class="input input-bordered" />
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text">Zip Code</span></label>
                    <input type="text" name="contact[zip_code]" value="{{ old('contact.zip_code', $student->contact->zip_code ?? '') }}" class="input input-bordered" />
                </div>
                <div class="form-control md:col-span-2">
                    <label class="label"><span class="label-text">Present Address</span></label>
                    <textarea name="contact[present_address]" class="textarea textarea-bordered" rows="2">{{ old('contact.present_address', $student->contact->present_address ?? '') }}</textarea>
                </div>
                <div class="form-control md:col-span-3">
                    <label class="label"><span class="label-text">Permanent Address</span></label>
                    <textarea name="contact[permanent_address]" class="textarea textarea-bordered" rows="2">{{ old('contact.permanent_address', $student->contact->permanent_address ?? '') }}</textarea>
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
                            <input type="text" name="guardian[mother_name]" value="{{ old('guardian.mother_name', $student->guardian->mother_name ?? '') }}" class="input input-bordered input-sm" />
                        </div>
                        <div class="form-control">
                            <label class="label"><span class="label-text">Occupation</span></label>
                            <input type="text" name="guardian[mother_occupation]" value="{{ old('guardian.mother_occupation', $student->guardian->mother_occupation ?? '') }}" class="input input-bordered input-sm" />
                        </div>
                        <div class="form-control">
                            <label class="label"><span class="label-text">Contact</span></label>
                            <input type="text" name="guardian[mother_contact_number]" value="{{ old('guardian.mother_contact_number', $student->guardian->mother_contact_number ?? '') }}" class="input input-bordered input-sm" />
                        </div>
                        <div class="form-control">
                            <label class="label"><span class="label-text">Monthly Income</span></label>
                            <input type="text" name="guardian[mother_monthly_income]" value="{{ old('guardian.mother_monthly_income', $student->guardian->mother_monthly_income ?? '') }}" class="input input-bordered input-sm" />
                        </div>
                    </div>
                </div>
                <!-- Father -->
                <div class="border-r border-purple-200 pr-4">
                    <h4 class="font-medium text-purple-700 mb-3">Father</h4>
                    <div class="space-y-3">
                        <div class="form-control">
                            <label class="label"><span class="label-text">Name</span></label>
                            <input type="text" name="guardian[father_name]" value="{{ old('guardian.father_name', $student->guardian->father_name ?? '') }}" class="input input-bordered input-sm" />
                        </div>
                        <div class="form-control">
                            <label class="label"><span class="label-text">Occupation</span></label>
                            <input type="text" name="guardian[father_occupation]" value="{{ old('guardian.father_occupation', $student->guardian->father_occupation ?? '') }}" class="input input-bordered input-sm" />
                        </div>
                        <div class="form-control">
                            <label class="label"><span class="label-text">Contact</span></label>
                            <input type="text" name="guardian[father_contact_number]" value="{{ old('guardian.father_contact_number', $student->guardian->father_contact_number ?? '') }}" class="input input-bordered input-sm" />
                        </div>
                        <div class="form-control">
                            <label class="label"><span class="label-text">Monthly Income</span></label>
                            <input type="text" name="guardian[father_monthly_income]" value="{{ old('guardian.father_monthly_income', $student->guardian->father_monthly_income ?? '') }}" class="input input-bordered input-sm" />
                        </div>
                    </div>
                </div>
                <!-- Guardian -->
                <div>
                    <h4 class="font-medium text-purple-700 mb-3">Guardian</h4>
                    <div class="space-y-3">
                        <div class="form-control">
                            <label class="label"><span class="label-text">Name</span></label>
                            <input type="text" name="guardian[guardian_name]" value="{{ old('guardian.guardian_name', $student->guardian->guardian_name ?? '') }}" class="input input-bordered input-sm" />
                        </div>
                        <div class="form-control">
                            <label class="label"><span class="label-text">Occupation</span></label>
                            <input type="text" name="guardian[guardian_occupation]" value="{{ old('guardian.guardian_occupation', $student->guardian->guardian_occupation ?? '') }}" class="input input-bordered input-sm" />
                        </div>
                        <div class="form-control">
                            <label class="label"><span class="label-text">Contact</span></label>
                            <input type="text" name="guardian[guardian_contact_number]" value="{{ old('guardian.guardian_contact_number', $student->guardian->guardian_contact_number ?? '') }}" class="input input-bordered input-sm" />
                        </div>
                        <div class="form-control">
                            <label class="label"><span class="label-text">Monthly Income</span></label>
                            <input type="text" name="guardian[guardian_monthly_income]" value="{{ old('guardian.guardian_monthly_income', $student->guardian->guardian_monthly_income ?? '') }}" class="input input-bordered input-sm" />
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
                            <input type="text" name="academic_history[elementary_school_name]" value="{{ old('academic_history.elementary_school_name', $student->academicHistory->elementary_school_name ?? '') }}" class="input input-bordered input-sm" />
                        </div>
                        <div class="form-control">
                            <label class="label"><span class="label-text">Address</span></label>
                            <input type="text" name="academic_history[elementary_school_address]" value="{{ old('academic_history.elementary_school_address', $student->academicHistory->elementary_school_address ?? '') }}" class="input input-bordered input-sm" />
                        </div>
                        <div class="form-control">
                            <label class="label"><span class="label-text">Inclusive Years</span></label>
                            <input type="text" name="academic_history[elementary_inclusive_years]" value="{{ old('academic_history.elementary_inclusive_years', $student->academicHistory->elementary_inclusive_years ?? '') }}" class="input input-bordered input-sm" placeholder="e.g., 2010-2016" />
                        </div>
                    </div>
                </div>
                <!-- Junior High School -->
                <div>
                    <h4 class="font-medium text-orange-700 mb-3">Junior High School</h4>
                    <div class="space-y-3">
                        <div class="form-control">
                            <label class="label"><span class="label-text">School Name</span></label>
                            <input type="text" name="academic_history[junior_school_name]" value="{{ old('academic_history.junior_school_name', $student->academicHistory->junior_school_name ?? '') }}" class="input input-bordered input-sm" />
                        </div>
                        <div class="form-control">
                            <label class="label"><span class="label-text">Address</span></label>
                            <input type="text" name="academic_history[junior_school_address]" value="{{ old('academic_history.junior_school_address', $student->academicHistory->junior_school_address ?? '') }}" class="input input-bordered input-sm" />
                        </div>
                        <div class="form-control">
                            <label class="label"><span class="label-text">Inclusive Years</span></label>
                            <input type="text" name="academic_history[junior_inclusive_years]" value="{{ old('academic_history.junior_inclusive_years', $student->academicHistory->junior_inclusive_years ?? '') }}" class="input input-bordered input-sm" placeholder="e.g., 2016-2020" />
                        </div>
                    </div>
                </div>
                <!-- Senior High School -->
                <div>
                    <h4 class="font-medium text-orange-700 mb-3">Senior High School</h4>
                    <div class="space-y-3">
                        <div class="form-control">
                            <label class="label"><span class="label-text">School Name</span></label>
                            <input type="text" name="academic_history[senior_school_name]" value="{{ old('academic_history.senior_school_name', $student->academicHistory->senior_school_name ?? '') }}" class="input input-bordered input-sm" />
                        </div>
                        <div class="form-control">
                            <label class="label"><span class="label-text">Address</span></label>
                            <input type="text" name="academic_history[senior_school_address]" value="{{ old('academic_history.senior_school_address', $student->academicHistory->senior_school_address ?? '') }}" class="input input-bordered input-sm" />
                        </div>
                        <div class="form-control">
                            <label class="label"><span class="label-text">Inclusive Years</span></label>
                            <input type="text" name="academic_history[senior_inclusive_years]" value="{{ old('academic_history.senior_inclusive_years', $student->academicHistory->senior_inclusive_years ?? '') }}" class="input input-bordered input-sm" placeholder="e.g., 2020-2022" />
                        </div>
                    </div>
                </div>
                <!-- College -->
                <div>
                    <h4 class="font-medium text-orange-700 mb-3">College</h4>
                    <div class="space-y-3">
                        <div class="form-control">
                            <label class="label"><span class="label-text">School Name</span></label>
                            <input type="text" name="academic_history[college_school_name]" value="{{ old('academic_history.college_school_name', $student->academicHistory->college_school_name ?? '') }}" class="input input-bordered input-sm" />
                        </div>
                        <div class="form-control">
                            <label class="label"><span class="label-text">Address</span></label>
                            <input type="text" name="academic_history[college_school_address]" value="{{ old('academic_history.college_school_address', $student->academicHistory->college_school_address ?? '') }}" class="input input-bordered input-sm" />
                        </div>
                        <div class="form-control">
                            <label class="label"><span class="label-text">Inclusive Years</span></label>
                            <input type="text" name="academic_history[college_inclusive_years]" value="{{ old('academic_history.college_inclusive_years', $student->academicHistory->college_inclusive_years ?? '') }}" class="input input-bordered input-sm" placeholder="e.g., 2022-present" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end gap-4">
            <a href="{{ route('admission.student') }}" class="btn btn-ghost">Cancel</a>
            <button type="submit" class="btn btn-primary">Save Changes</button>
        </div>
    </form>

</x-admission_sidebar>
