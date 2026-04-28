<x-layout>
    @include('partials.success-notification')
    @include('partials.consent-modal')
    <div class="w-full max-w-7xl mx-auto p-4 sm:p-6">
        <div class="mb-6">
            <h3 class="text-2xl font-bold text-gray-800">Submit Application</h3>
            <p class="text-gray-500 text-sm mt-1">Please fill out all required fields to complete your application.</p>
        </div>

        {{-- Validation Errors Display --}}
        @if ($errors->any())
            <div class="bg-red-50 border border-red-300 text-red-700 px-4 py-3 rounded-lg mb-6">
                <h4 class="font-bold mb-2">Please fix the following errors:</h4>
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="mb-6 sticky top-2 z-30 bg-gray-100/95 backdrop-blur-sm py-2">
            <div class="overflow-x-auto">
                <ul class="steps steps-horizontal w-full text-sm">
                    <li class="step step-indicator step-primary"><span class="hidden sm:inline">Application Information</span></li>
                    <li class="step step-indicator"><span class="hidden sm:inline">Personal Information</span></li>
                    <li class="step step-indicator"><span class="hidden sm:inline">Parent/Guardian</span></li>
                    <li class="step step-indicator"><span class="hidden sm:inline">Educational Background</span></li>
                    <li class="step step-indicator"><span class="hidden sm:inline">Data Privacy Consent</span></li>
                </ul>
            </div>
        </div>

        <form id="application_form" action="{{ route('applicant.create') }}" method="POST" class="space-y-6">
            @csrf

            <section class="step-panel space-y-6" data-step-title="Application Information">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="border-b border-gray-200 px-6 py-3">
                        <h4 class="text-gray-800 font-semibold">Application Information</h4>
                        <p>Note: <small class="underline text-red-500">Use similar email to resubmit/edit existing application.</small></p>
                    </div>
                    <div class="p-6 grid grid-cols-12 gap-4 mobile-col-12">
                        <div class="form-control col-span-4">
                            <label class="label"><span class="label-text text-gray-700 font-medium">Application No.</span></label>
                            <input type="text" readonly id="application_no" name="application_no" class="input input-bordered w-full bg-white font-mono font-bold text-green-600" placeholder="Auto-generated" value="" required>
                            <input type="hidden" name="academic_year" value="{{ $currentAcademicYear ?? '' }}">
                        </div>
                        <div class="form-control col-span-3">
                            <label class="label"><span class="label-text text-gray-700 font-medium">Level</span></label>
                            <select id="level" name="level" class="select select-bordered w-full bg-white" required>
                                <option value="">-- Select Level --</option>
                                <option value="Nursery">Nursery</option>
                                <option value="Kindergarten">Kindergarten</option>
                                <option value="Grade School">Grade School</option>
                                <option value="Junior High School">Junior High School</option>
                                <option value="Senior High School">Senior High School</option>
                                <option value="College">College</option>
                            </select>
                        </div>
                        <div class="form-control col-span-3">
                            <label class="label"><span class="label-text text-gray-700 font-medium">Student Type</span></label>
                            <select name="student_type" class="select select-bordered w-full bg-white" required>
                                <option value="">-- Select Type --</option>
                                <option value="new">New</option>
                                <option value="transferee">Transferee</option>
                            </select>
                        </div>
                        <div class="form-control col-span-3">
                            <label class="label"><span class="label-text text-gray-700 font-medium">Year Level</span></label>
                            <select id="year_level" name="year_level" class="select select-bordered w-full bg-white" required>
                                <option value="">-- Select Year --</option>
                                @foreach ($levels as $level)
                                <option value="{{$level->description}}" data-program="{{$level->program->description}}" data-department="{{$level->program->department->description}}">{{$level->description}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-control col-span-3">
                            <label class="label"><span class="label-text text-gray-700 font-medium">Strand</span></label>
                            <select id="strand" name="strand" class="select select-bordered w-full bg-white">
                                <option value="">-- Select Strand --</option>
                                @foreach ($strands as $strand)
                                <option value="{{$strand->id}}"><b>{{$strand->code}}</b>-{{$strand->description}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-control col-span-4">
                            <label class="label"><span class="label-text text-gray-700 font-medium">First Choice Program</span></label>
                            <select id="first_program_choice" name="first_program_choice" class="select select-bordered w-full bg-white">
                                <option value="">-- Select Program --</option>
                                @foreach ($college_programs as $program)
                                <option value="{{$program->id}}"><b>{{$program->code}}</b>-{{$program->description}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-control col-span-4">
                            <label class="label"><span class="label-text text-gray-700 font-medium">Second Choice Program</span></label>
                            <select id="second_program_choice" name="second_program_choice" class="select select-bordered w-full bg-white">
                                <option value="">-- Select Program --</option>
                                @foreach ($college_programs as $program)
                                <option value="{{$program->id}}"><b>{{$program->code}}</b>-{{$program->description}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-control col-span-4">
                            <label class="label"><span class="label-text text-gray-700 font-medium">Third Choice Program</span></label>
                            <select id="third_program_choice" name="third_program_choice" class="select select-bordered w-full bg-white">
                                <option value="">-- Select Program --</option>
                                @foreach ($college_programs as $program)
                                <option value="{{$program->id}}"><b>{{$program->code}}</b>-{{$program->description}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </section>

            <section class="step-panel hidden" data-step-title="Personal Information">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="border-b border-gray-200 px-6 py-3">
                        <h4 class="text-gray-800 font-semibold">Personal Information</h4>
                    </div>
                    <div class="p-6 grid grid-cols-12 gap-4 mobile-col-12">
                        <div class="form-control col-span-4">
                            <label class="label"><span class="label-text text-gray-700 font-medium">Last Name</span></label>
                            <input type="text" name="last_name" class="input input-bordered w-full bg-white" placeholder="Enter last name" required>
                        </div>
                        <div class="form-control col-span-4">
                            <label class="label"><span class="label-text text-gray-700 font-medium">First Name</span></label>
                            <input type="text" name="first_name" class="input input-bordered w-full bg-white" placeholder="Enter first name" required>
                        </div>
                        <div class="form-control col-span-4">
                            <label class="label"><span class="label-text text-gray-700 font-medium">Middle Name</span></label>
                            <input type="text" name="middle_name" class="input input-bordered w-full bg-white" placeholder="Enter middle name" required>
                        </div>
                        <div class="form-control col-span-4">
                            <label class="label"><span class="label-text text-gray-700 font-medium">Sex</span></label>
                            <select name="sex" class="select select-bordered w-full bg-white" required>
                                <option value="">-- Select Sex --</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                            </select>
                        </div>
                        <div class="form-control col-span-4">
                            <label class="label"><span class="label-text text-gray-700 font-medium">Citizenship</span></label>
                            <input type="text" name="citizenship" class="input input-bordered w-full bg-white" placeholder="Enter citizenship" required>
                        </div>
                        <div class="form-control col-span-4">
                            <label class="label"><span class="label-text text-gray-700 font-medium">Religion</span></label>
                            <input type="text" name="religion" class="input input-bordered w-full bg-white" placeholder="Enter religion" required>
                        </div>
                        <div class="form-control col-span-4">
                            <label class="label"><span class="label-text text-gray-700 font-medium">Birthdate</span></label>
                            <input type="date" name="birthdate" class="input input-bordered w-full bg-white" required>
                        </div>
                        <div class="form-control col-span-4">
                            <label class="label"><span class="label-text text-gray-700 font-medium">Birth Place</span></label>
                            <input type="text" name="place_of_birth" class="input input-bordered w-full bg-white" placeholder="Enter birth place" required>
                        </div>
                        <div class="form-control col-span-4">
                            <label class="label"><span class="label-text text-gray-700 font-medium">Civil Status</span></label>
                            <select name="civil_status" class="select select-bordered w-full bg-white" required>
                                <option value="">-- Select Status --</option>
                                <option value="single">Single</option>
                                <option value="married">Married</option>
                                <option value="widowed/widower">Widowed/Widower</option>
                            </select>
                        </div>
                        <div class="form-control col-span-4">
                            <label class="label"><span class="label-text text-gray-700 font-medium">Zip Code</span></label>
                            <input type="number" name="zip_code" class="input input-bordered w-full bg-white" placeholder="Enter zip code" value="{{ old('zip_code') }}" required>
                        </div>
                        <div class="form-control col-span-4">
                            <label class="label"><span class="label-text text-gray-700 font-medium">Present Address</span></label>
                            <input type="text" name="present_address" class="input input-bordered w-full bg-white" placeholder="Enter present address" required>
                        </div>
                        <div class="form-control col-span-4">
                            <label class="label"><span class="label-text text-gray-700 font-medium">Permanent Address</span></label>
                            <input type="text" name="permanent_address" class="input input-bordered w-full bg-white" placeholder="Enter permanent address" required>
                        </div>
                        <div class="form-control col-span-3">
                            <label class="label"><span class="label-text text-gray-700 font-medium">Telephone Number</span></label>
                            <input type="text" name="telephone_number" class="input input-bordered w-full bg-white" placeholder="Enter telephone number" required>
                        </div>
                        <div class="form-control col-span-3">
                            <label class="label"><span class="label-text text-gray-700 font-medium">Mobile Number</span></label>
                            <input type="text" name="mobile_number" class="input input-bordered w-full bg-white" placeholder="Enter mobile number" required>
                        </div>
                        <div class="form-control col-span-3">
                            <label class="label"><span class="label-text text-gray-700 font-medium">Email Address</span></label>
                            <input type="email" name="email" class="input input-bordered w-full bg-white" placeholder="Enter email address" required>
                        </div>
                        <div class="form-control col-span-3">
                            <label class="label"><span class="label-text text-gray-700 font-medium">Re-enter Email</span></label>
                            <input type="email" name="email_confirmation" class="input input-bordered w-full bg-white" placeholder="Confirm email address" required>
                        </div>
                    </div>
                </div>
            </section>

            <section class="step-panel hidden" data-step-title="Parent/Guardian Information">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="border-b border-gray-200 px-6 py-3">
                        <h4 class="text-gray-800 font-semibold">Parent/Guardian Information</h4>
                    </div>
                    <div class="p-6 space-y-3">
                        <div class="hidden md:grid grid-cols-12 gap-4 pb-2 border-b border-gray-100">
                            <div class="col-span-1"><span class="text-gray-700 font-medium text-sm">Role</span></div>
                            <div class="col-span-4"><span class="text-gray-700 font-medium text-sm">Full Name</span></div>
                            <div class="col-span-3"><span class="text-gray-700 font-medium text-sm">Occupation</span></div>
                            <div class="col-span-2"><span class="text-gray-700 font-medium text-sm">Contact Number</span></div>
                            <div class="col-span-2"><span class="text-gray-700 font-medium text-sm">Monthly Income</span></div>
                        </div>
                        <div class="grid grid-cols-12 gap-4 items-center mobile-col-12 rounded-lg border border-gray-100 p-3 md:border-0 md:p-0">
                            <div class="col-span-1"><span class="text-gray-600 text-sm font-medium">Mother</span></div>
                            <div class="col-span-4"><input type="text" name="mother_name" class="input input-bordered w-full bg-white input-sm" placeholder="Mother's full name" required></div>
                            <div class="col-span-3"><input type="text" name="mother_occupation" class="input input-bordered w-full bg-white input-sm" placeholder="Occupation" required></div>
                            <div class="col-span-2"><input type="text" name="mother_contact_number" class="input input-bordered w-full bg-white input-sm" placeholder="Contact no." required></div>
                            <div class="col-span-2"><input type="number" name="mother_monthly_income" class="input input-bordered w-full bg-white input-sm" placeholder="Income" required></div>
                        </div>
                        <div class="grid grid-cols-12 gap-4 items-center mobile-col-12 rounded-lg border border-gray-100 p-3 md:border-0 md:p-0">
                            <div class="col-span-1"><span class="text-gray-600 text-sm font-medium">Father</span></div>
                            <div class="col-span-4"><input type="text" name="father_name" class="input input-bordered w-full bg-white input-sm" placeholder="Father's full name" required></div>
                            <div class="col-span-3"><input type="text" name="father_occupation" class="input input-bordered w-full bg-white input-sm" placeholder="Occupation" required></div>
                            <div class="col-span-2"><input type="text" name="father_contact_number" class="input input-bordered w-full bg-white input-sm" placeholder="Contact no." required></div>
                            <div class="col-span-2"><input type="number" name="father_monthly_income" class="input input-bordered w-full bg-white input-sm" placeholder="Income" required></div>
                        </div>
                        <div class="grid grid-cols-12 gap-4 items-center mobile-col-12 rounded-lg border border-gray-100 p-3 md:border-0 md:p-0">
                            <div class="col-span-1"><span class="text-gray-600 text-sm font-medium">Guardian</span></div>
                            <div class="col-span-4"><input type="text" name="guardian_name" class="input input-bordered w-full bg-white input-sm" placeholder="Guardian's full name" required></div>
                            <div class="col-span-3"><input type="text" name="guardian_occupation" class="input input-bordered w-full bg-white input-sm" placeholder="Occupation" required></div>
                            <div class="col-span-2"><input type="text" name="guardian_contact_number" class="input input-bordered w-full bg-white input-sm" placeholder="Contact no." required></div>
                            <div class="col-span-2"><input type="number" name="guardian_monthly_income" class="input input-bordered w-full bg-white input-sm" placeholder="Income" required></div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="step-panel hidden" data-step-title="Educational Background">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="border-b border-gray-200 px-6 py-3">
                        <h4 class="text-gray-800 font-semibold">Educational Background</h4>
                        <small class="underline text-red-500">Enter N/A if not applicable.</small>
                    </div>
                    <div class="p-6 space-y-3">
                        <div class="hidden md:grid grid-cols-12 gap-4 pb-2 border-b border-gray-100">
                            <div class="col-span-2"><span class="text-gray-700 font-medium text-sm">Level</span></div>
                            <div class="col-span-4"><span class="text-gray-700 font-medium text-sm">Previous School</span></div>
                            <div class="col-span-4"><span class="text-gray-700 font-medium text-sm">Address</span></div>
                            <div class="col-span-2"><span class="text-gray-700 font-medium text-sm">Inclusive Years</span></div>
                        </div>
                        <div class="grid grid-cols-12 gap-4 items-center mobile-col-12 rounded-lg border border-gray-100 p-3 md:border-0 md:p-0">
                            <div class="col-span-2"><span class="text-gray-600 text-sm font-medium">Elementary</span></div>
                            <div class="col-span-4"><input type="text" name="elementary_school_name" class="input input-bordered w-full bg-white input-sm" placeholder="School name" required></div>
                            <div class="col-span-4"><input type="text" name="elementary_school_address" class="input input-bordered w-full bg-white input-sm" placeholder="School address" required></div>
                            <div class="col-span-2"><input type="text" name="elementary_inclusive_years" class="input input-bordered w-full bg-white input-sm" placeholder="e.g. 2015-2021" required></div>
                        </div>
                        <div class="grid grid-cols-12 gap-4 items-center mobile-col-12 rounded-lg border border-gray-100 p-3 md:border-0 md:p-0">
                            <div class="col-span-2"><span class="text-gray-600 text-sm font-medium">Junior High</span></div>
                            <div class="col-span-4"><input type="text" name="junior_school_name" class="input input-bordered w-full bg-white input-sm" placeholder="School name" required></div>
                            <div class="col-span-4"><input type="text" name="junior_school_address" class="input input-bordered w-full bg-white input-sm" placeholder="School address" required></div>
                            <div class="col-span-2"><input type="text" name="junior_inclusive_years" class="input input-bordered w-full bg-white input-sm" placeholder="e.g. 2021-2025" required></div>
                        </div>
                        <div class="grid grid-cols-12 gap-4 items-center mobile-col-12 rounded-lg border border-gray-100 p-3 md:border-0 md:p-0">
                            <div class="col-span-2"><span class="text-gray-600 text-sm font-medium">Senior High</span></div>
                            <div class="col-span-4"><input type="text" name="senior_school_name" class="input input-bordered w-full bg-white input-sm" placeholder="School name" required></div>
                            <div class="col-span-4"><input type="text" name="senior_school_address" class="input input-bordered w-full bg-white input-sm" placeholder="School address" required></div>
                            <div class="col-span-2"><input type="text" name="senior_inclusive_years" class="input input-bordered w-full bg-white input-sm" placeholder="e.g. 2025-2027" required></div>
                        </div>
                        <div class="grid grid-cols-12 gap-4 items-center mobile-col-12 rounded-lg border border-gray-100 p-3 md:border-0 md:p-0">
                            <div class="col-span-2"><span class="text-gray-600 text-sm font-medium">College</span></div>
                            <div class="col-span-4"><input type="text" name="college_school_name" class="input input-bordered w-full bg-white input-sm" placeholder="School name" required></div>
                            <div class="col-span-4"><input type="text" name="college_school_address" class="input input-bordered w-full bg-white input-sm" placeholder="School address" required></div>
                            <div class="col-span-2"><input type="text" name="college_inclusive_years" class="input input-bordered w-full bg-white input-sm" placeholder="e.g. 2027-2031" required></div>
                        </div>
                        <div class="grid grid-cols-12 gap-4 items-center mobile-col-12 rounded-lg border border-gray-100 p-3 md:border-0 md:p-0">
                            <div class="col-span-2"><span class="text-gray-600 text-sm font-medium">LRN</span></div>
                            <div class="col-span-10"><input type="number" name="lrn" class="input input-bordered w-full bg-white input-sm" placeholder="Learner Reference Number" value="{{ old('lrn') }}" required></div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="step-panel hidden" data-step-title="Data Privacy Consent">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="border-b border-gray-200 px-6 py-3">
                        <h4 class="text-gray-800 font-semibold">Data Privacy Consent</h4>
                    </div>
                    <div class="p-6">
                        <div class="form-control">
                            <label class="cursor-pointer flex items-start gap-3">
                                <input type="checkbox" id="consent_checkbox" name="consent_to_data_access" class="checkbox checkbox-primary mt-1" value="1">
                                <span class="label-text text-gray-700 text-sm leading-relaxed">
                                    I have read and agree to the
                                    <a href="#" onclick="document.getElementById('consent_modal').showModal(); return false;" class="text-blue-600 hover:text-blue-800 underline font-medium">
                                        Data Privacy Consent
                                    </a>
                                    and I consent to the collection, processing, and storage of my personal data.
                                </span>
                            </label>
                        </div>
                    </div>
                </div>
            </section>

            <div class="grid grid-cols-3 gap-3 border-t border-gray-200 pt-4">
                <button type="button" id="prev_step_btn" class="btn btn-ghost border border-gray-300 w-full">Previous</button>
                <button type="reset" id="reset_btn" class="btn btn-ghost border border-gray-300 w-full">Reset Form</button>
                <button type="button" id="next_step_btn" class="btn btn-primary w-full">Next</button>
                <button type="submit" id="submit_btn" class="btn btn-primary hidden w-full" disabled>Submit Application</button>
            </div>
        </form>
    </div>
</x-layout>

<style>
    @media (max-width: 767px) {
        .mobile-col-12 > * {
            grid-column: 1 / -1 !important;
        }
    }
</style>

<script>
    function generateApplicationNo() {
        const now = new Date();
        const year = String(now.getFullYear());
        const month = String(now.getMonth() + 1).padStart(2, '0');
        const day = String(now.getDate()).padStart(2, '0');
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');
        const milliseconds = String(now.getMilliseconds()).padStart(3, '0');
        
        return `${year}${month}${day}${hours}${minutes}${seconds}${milliseconds}`;
    }

    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('application_form');
        const appNoField = document.getElementById('application_no');
        const consentCheckbox = document.getElementById('consent_checkbox');
        const submitBtn = document.getElementById('submit_btn');
        const prevStepBtn = document.getElementById('prev_step_btn');
        const nextStepBtn = document.getElementById('next_step_btn');
        const stepPanels = Array.from(document.querySelectorAll('.step-panel'));
        const stepIndicators = Array.from(document.querySelectorAll('.step-indicator'));
        const totalSteps = stepPanels.length;
        let currentStep = 0;

        const levelSelect = document.getElementById('level');
        const yearLevelSelect = document.getElementById('year_level');
        const strandSelect = document.getElementById('strand');
        const firstProgramChoiceSelect = document.getElementById('first_program_choice');
        const secondProgramChoiceSelect = document.getElementById('second_program_choice');
        const thirdProgramChoiceSelect = document.getElementById('third_program_choice');

        function isVisible(element) {
            return !!(element.offsetWidth || element.offsetHeight || element.getClientRects().length);
        }

        function updateStepperUI() {
            stepPanels.forEach(function (panel, index) {
                panel.classList.toggle('hidden', index !== currentStep);
            });

            stepIndicators.forEach(function (indicator, index) {
                indicator.classList.toggle('step-primary', index <= currentStep);
            });

            const isFirstStep = currentStep === 0;
            prevStepBtn.disabled = isFirstStep;
            prevStepBtn.classList.toggle('btn-disabled', isFirstStep);
            nextStepBtn.classList.toggle('hidden', currentStep === totalSteps - 1);
            submitBtn.classList.toggle('hidden', currentStep !== totalSteps - 1);
        }

        function validateCurrentStep() {
            const currentPanel = stepPanels[currentStep];
            const fields = currentPanel.querySelectorAll('input, select, textarea');

            for (const field of fields) {
                if (field.type === 'hidden' || field.disabled || !isVisible(field)) {
                    continue;
                }

                if (!field.checkValidity()) {
                    field.reportValidity();
                    field.focus();
                    return false;
                }
            }

            return true;
        }

        function goToStep(stepIndex) {
            currentStep = Math.min(Math.max(stepIndex, 0), totalSteps - 1);
            updateStepperUI();
            form.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }

        function updateApplyingForFields(clearValues = true) {
            const selectedLevel = levelSelect.value;

            yearLevelSelect.parentElement.style.display = 'none';
            strandSelect.parentElement.style.display = 'none';
            firstProgramChoiceSelect.parentElement.style.display = 'none';
            secondProgramChoiceSelect.parentElement.style.display = 'none';
            thirdProgramChoiceSelect.parentElement.style.display = 'none';

            if (clearValues) {
                yearLevelSelect.value = '';
                strandSelect.value = '';
                firstProgramChoiceSelect.value = '';
                secondProgramChoiceSelect.value = '';
                thirdProgramChoiceSelect.value = '';
            }

            for (let option of yearLevelSelect.options) {
                if (!option.value) {
                    option.style.display = 'block';
                    continue;
                }

                if (option.getAttribute('data-program') === `${selectedLevel}` || option.getAttribute('data-department') === `${selectedLevel}`) {
                    option.style.display = 'block';
                } else {
                    option.style.display = 'none';
                }
            }

            if (selectedLevel) {
                yearLevelSelect.parentElement.style.display = 'block';
            }

            if (selectedLevel === "Senior High School") {
                strandSelect.parentElement.style.display = 'block';
            }

            if (selectedLevel === 'College') {
                firstProgramChoiceSelect.parentElement.style.display = 'block';
                secondProgramChoiceSelect.parentElement.style.display = 'block';
                thirdProgramChoiceSelect.parentElement.style.display = 'block';
            }
        }

        if (appNoField && !appNoField.value) {
            appNoField.value = generateApplicationNo();
        }

        submitBtn.disabled = !consentCheckbox.checked;
        consentCheckbox.addEventListener('change', function () {
            submitBtn.disabled = !this.checked;
        });

        levelSelect.addEventListener('change', function () {
            updateApplyingForFields(true);
        });

        prevStepBtn.addEventListener('click', function () {
            goToStep(currentStep - 1);
        });

        nextStepBtn.addEventListener('click', function () {
            if (!validateCurrentStep()) {
                return;
            }

            goToStep(currentStep + 1);
        });

        form.addEventListener('reset', function () {
            window.requestAnimationFrame(function () {
                currentStep = 0;
                updateApplyingForFields(false);

                if (appNoField) {
                    appNoField.value = generateApplicationNo();
                }

                submitBtn.disabled = true;
                updateStepperUI();
                form.scrollIntoView({ behavior: 'smooth', block: 'start' });
            });
        });

        updateApplyingForFields(false);
        updateStepperUI();
    });
</script>