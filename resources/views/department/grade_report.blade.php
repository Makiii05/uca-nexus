<x-department_sidebar>
    @include('partials.notifications')

    @php
        $filterParams = [
            'academic_term_id' => $selectedAcademicTermId,
            'subject_offering_id' => $selectedSubjectOfferingId,
            'period' => $selectedPeriod,
        ];
    @endphp

    <div class="space-y-6">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Grade Report</h1>
                <p class="text-sm text-gray-500">Filter by academic term, subject offering, and period.</p>
            </div>
        </div>

        <div class="card bg-white shadow-lg">
            <div class="card-body space-y-4">
                <form method="GET" action="{{ route('department.grade_report') }}" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <label class="form-control w-full">
                            <div class="label"><span class="label-text font-semibold">Academic Term</span></div>
                            <select id="academic-term-select" name="academic_term_id" class="select select-bordered w-full">
                                <option value="">Select academic term</option>
                                @foreach ($academicTerms as $term)
                                    <option value="{{ $term->id }}" {{ (string) $selectedAcademicTermId === (string) $term->id ? 'selected' : '' }}>
                                        {{ $term->code ? $term->code . ' - ' : '' }}{{ $term->description }}
                                    </option>
                                @endforeach
                            </select>
                        </label>

                        <label class="form-control w-full">
                            <div class="label"><span class="label-text font-semibold">Subject Offering</span></div>
                            <select id="subject-offering-select" name="subject_offering_id" class="select select-bordered w-full" {{ $selectedAcademicTermId ? '' : 'disabled' }}>
                                <option value="">Select subject offering</option>
                                @foreach ($subjectOfferings as $offering)
                                    <option value="{{ $offering->id }}" {{ (string) $selectedSubjectOfferingId === (string) $offering->id ? 'selected' : '' }}>
                                        {{ $offering->code }} - {{ $offering->subject?->code ? $offering->subject->code . ' - ' : '' }}{{ $offering->subject?->description ?? $offering->description }}
                                    </option>
                                @endforeach
                            </select>
                        </label>

                        <label class="form-control w-full">
                            <div class="label"><span class="label-text font-semibold">Period</span></div>
                            <select id="period-select" name="period" class="select select-bordered w-full" {{ $selectedAcademicTermId ? '' : 'disabled' }}>
                                <option value="">Select period</option>
                                @foreach ($periodOptions as $p)
                                    <option value="{{ $p }}" {{ (string) $selectedPeriod === (string) $p ? 'selected' : '' }}>{{ $p }}</option>
                                @endforeach
                            </select>
                        </label>
                    </div>

                    <div class="flex items-center justify-end gap-2">
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </div>
                </form>

                @if ($selectedSubjectOffering)
                    <div class="rounded-lg border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700">
                        <span class="font-semibold">Selected:</span>
                        <span class="ml-1">
                            {{ $selectedSubjectOffering->academicTerm?->description ? $selectedSubjectOffering->academicTerm->description . ' • ' : '' }}
                            {{ $selectedSubjectOffering->code }}
                            {{ $selectedPeriod ? ' • ' . $selectedPeriod : '' }}
                        </span>
                    </div>
                @endif
            </div>
        </div>

        <div class="card bg-white shadow-lg">
            <div class="card-body">
                <div class="flex items-center justify-between gap-3 mb-4">
                    <div class="flex items-center gap-3">
                        <h2 class="text-lg font-semibold text-gray-800">Students</h2>
                        <span class="badge badge-neutral badge-lg">{{ $students->count() }} students</span>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="table border border-gray-200 w-full">
                        <thead>
                            <tr>
                                <th>Student No.</th>
                                <th>Name</th>
                                <th>Program</th>
                                <th>Level</th>
                                <th class="w-20">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($students as $student)
                                <tr>
                                    <td>{{ $student->student_number }}</td>
                                    <td>{{ $student->last_name }}, {{ $student->first_name }} {{ $student->middle_name }}</td>
                                    <td>{{ $student->program?->code ?? '—' }}</td>
                                    <td>{{ $student->level?->description ?? '—' }}</td>
                                    <td>
                                        <a
                                            href="{{ route('department.grade_report.view', array_merge(['studentId' => $student->id], $filterParams)) }}"
                                            class="btn btn-outline btn-sm"
                                        >
                                            View
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-sm text-gray-500 py-8">
                                        Select filters, then click <span class="font-semibold">Filter</span>.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        const apiAcademicTermsUrl = '{{ route('department.api.grade_report.academic_terms') }}';
        const apiSubjectOfferingsUrl = '{{ route('department.api.grade_report.subject_offerings') }}';
        const apiPeriodsUrl = '{{ route('department.api.grade_report.periods') }}';

        const academicTermSelect = document.getElementById('academic-term-select');
        const subjectOfferingSelect = document.getElementById('subject-offering-select');
        const periodSelect = document.getElementById('period-select');

        function resetSelect(select, placeholder, disabled = true) {
            select.innerHTML = `<option value="">${placeholder}</option>`;
            select.disabled = disabled;
        }

        async function fetchJson(url) {
            const response = await fetch(url, {
                headers: {
                    'Accept': 'application/json',
                },
            });

            if (!response.ok) {
                throw new Error(`HTTP ${response.status}`);
            }

            return response.json();
        }

        async function loadSubjectOfferings(academicTermId) {
            resetSelect(subjectOfferingSelect, 'Loading subject offerings...', true);

            const departmentId = {{ auth()->user()->department_id }};
            const offerings = await fetchJson(`${apiSubjectOfferingsUrl}?department_id=${encodeURIComponent(departmentId)}&academic_term_id=${encodeURIComponent(academicTermId)}`);

            subjectOfferingSelect.innerHTML = `<option value="">Select subject offering</option>` + offerings.map(offering => {
                const subjectPart = offering.subject_code ? `${offering.subject_code} - ${offering.subject_description}` : (offering.description || '');
                return `<option value="${offering.id}">${offering.code ? offering.code + ' - ' : ''}${subjectPart}</option>`;
            }).join('');
            subjectOfferingSelect.disabled = false;
        }

        async function loadPeriods(academicTermId) {
            resetSelect(periodSelect, 'Loading periods...', true);

            const data = await fetchJson(`${apiPeriodsUrl}?academic_term_id=${encodeURIComponent(academicTermId)}`);

            periodSelect.innerHTML = `<option value="">Select period</option>` + (data.periods || []).map(p => `<option value="${p}">${p}</option>`).join('');
            periodSelect.disabled = false;
        }

        academicTermSelect?.addEventListener('change', async () => {
            const academicTermId = academicTermSelect.value;

            resetSelect(subjectOfferingSelect, 'Select subject offering', true);
            resetSelect(periodSelect, 'Select period', true);

            if (!academicTermId) {
                return;
            }

            try {
                await loadSubjectOfferings(academicTermId);
                await loadPeriods(academicTermId);
            } catch (e) {
                resetSelect(subjectOfferingSelect, 'Select subject offering', true);
                resetSelect(periodSelect, 'Select period', true);
            }
        });
    </script>
</x-department_sidebar>
