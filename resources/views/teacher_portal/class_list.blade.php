<x-teacher_portal_sidebar>

    @include('partials.notifications')

    <div class="m-4 font-bold text-3xl">Class List</div>

    <div class="m-4">
        <div class="card bg-white shadow p-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="label"><span class="label-text">Academic Term</span></label>
                    <select id="academic-term" class="select select-bordered w-full">
                        <option value="">-- Select Academic Term --</option>
                        @foreach($academicTerms as $term)
                            <option value="{{ $term->id }}">{{ $term->description }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="label flex items-center justify-between">
                        <span class="label-text">Subject Offering</span>
                        <span id="offerings-loading" class="loading loading-spinner loading-xs hidden"></span>
                    </label>
                    <select id="subject-offering" class="select select-bordered w-full" disabled>
                        <option value="">-- Select Offering --</option>
                    </select>
                </div>
            </div>

            <div class="mt-6">
                <div class="flex justify-end mb-2">
                    <a id="print-classlist" href="#" target="_blank" class="btn btn-primary btn-sm hidden">
                        Print Class List
                    </a>
                </div>
                <div id="students-area">
                    <p class="text-sm text-gray-500">Select an academic term and offering to see students.</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const academicTermSelect = document.getElementById('academic-term');
            const offeringSelect = document.getElementById('subject-offering');
            const studentsArea = document.getElementById('students-area');
            const offeringsLoading = document.getElementById('offerings-loading');
            const printBtn = document.getElementById('print-classlist');

            const teacherPortalBaseUrl = `{{ url('/teacher-portal') }}`;
            const STORAGE_TERM_KEY = 'teacher_portal_class_list_academic_term_id';
            const STORAGE_OFFERING_KEY = 'teacher_portal_class_list_teacher_offering_id';

            function resetStudentsArea() {
                studentsArea.innerHTML = '<p class="text-sm text-gray-500">Select an academic term and offering to see students.</p>';
                printBtn.classList.add('hidden');
                printBtn.href = '#';
            }

            function isSelectOptionPresent(selectEl, value) {
                if (!value) return false;
                return Array.from(selectEl.options).some(o => o.value === String(value));
            }

            async function loadOfferingsForTerm(termId) {
                offeringSelect.innerHTML = '<option value="">-- Select Offering --</option>';
                offeringSelect.disabled = true;
                resetStudentsArea();

                if (!termId) {
                    return;
                }

                offeringsLoading.classList.remove('hidden');
                try {
                    const res = await fetch(`${teacherPortalBaseUrl}/api/subject-offerings/${termId}`);
                    if (!res.ok) {
                        return;
                    }

                    const data = await res.json();
                    data.offerings.forEach(o => {
                        const opt = document.createElement('option');
                        opt.value = o.id;
                        opt.textContent = `${o.code}`;
                        offeringSelect.appendChild(opt);
                    });
                } finally {
                    offeringsLoading.classList.add('hidden');
                    offeringSelect.disabled = false;
                }
            }

            async function loadStudentsForOffering(teacherOfferingId) {
                resetStudentsArea();

                if (!teacherOfferingId) {
                    return;
                }

                studentsArea.innerHTML = '<p>Loading...</p>';

                const res = await fetch(`${teacherPortalBaseUrl}/api/class-list/${teacherOfferingId}`);
                if (!res.ok) {
                    studentsArea.innerHTML = '<p class="text-red-500">Failed to load class list.</p>';
                    return;
                }

                const data = await res.json();
                if (!data.students || data.students.length === 0) {
                    studentsArea.innerHTML = '<p class="text-sm text-gray-500">No students found for this offering.</p>';
                } else {
                    let html = '<table class="table w-full">';
                    html += '<thead><tr><th>#</th><th>Student Number</th><th>Name</th></tr></thead><tbody>';
                    data.students.forEach((s, idx) => {
                        html += `<tr><td>${idx + 1}</td><td>${s.student_number}</td><td>${s.student_name}</td></tr>`;
                    });
                    html += '</tbody></table>';
                    studentsArea.innerHTML = html;
                }

                printBtn.href = `${teacherPortalBaseUrl}/class-list/${teacherOfferingId}/print`;
                printBtn.classList.remove('hidden');
            }

            academicTermSelect.addEventListener('change', async function() {
                const termId = this.value;
                localStorage.setItem(STORAGE_TERM_KEY, termId || '');
                localStorage.removeItem(STORAGE_OFFERING_KEY);
                await loadOfferingsForTerm(termId);
            });

            offeringSelect.addEventListener('change', async function() {
                const teacherOfferingId = this.value;
                localStorage.setItem(STORAGE_OFFERING_KEY, teacherOfferingId || '');
                await loadStudentsForOffering(teacherOfferingId);
            });

            (async function restoreSelections() {
                resetStudentsArea();

                const storedTermId = localStorage.getItem(STORAGE_TERM_KEY);
                const storedOfferingId = localStorage.getItem(STORAGE_OFFERING_KEY);

                if (storedTermId && isSelectOptionPresent(academicTermSelect, storedTermId)) {
                    academicTermSelect.value = storedTermId;
                    await loadOfferingsForTerm(storedTermId);

                    if (storedOfferingId && isSelectOptionPresent(offeringSelect, storedOfferingId)) {
                        offeringSelect.value = storedOfferingId;
                        await loadStudentsForOffering(storedOfferingId);
                    }
                } else {
                    localStorage.removeItem(STORAGE_TERM_KEY);
                    localStorage.removeItem(STORAGE_OFFERING_KEY);
                }
            })();
        });
    </script>

</x-teacher_portal_sidebar>
