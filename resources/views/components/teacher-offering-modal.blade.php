<dialog id="teacherOfferingModal" class="modal">
    <div class="modal-box w-11/12 max-w-3xl">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </form>

        <h3 class="text-lg font-bold mb-4">Assign Subject to Teacher</h3>

        <form id="teacherOfferingForm" action="{{ route('department.teacher_loading.assign') }}" method="POST" class="space-y-4">
            @csrf

            <div class="grid grid-cols-2 gap-4">
                <div class="form-control col-span-2">
                    <label class="label">
                        <span class="label-text">Teacher</span>
                    </label>
                    <select id="teacherOfferingTeacherId" name="teacher_id_display" class="select select-bordered w-full" required>
                        <option value="">-- Select Teacher --</option>
                        @foreach ($teacherSelectOptions as $teacher)
                            <option value="{{ $teacher->id }}">
                                {{ $teacher->last_name }}, {{ $teacher->first_name }} {{ $teacher->middle_name ?? '' }}
                            </option>
                        @endforeach
                    </select>
                    <input type="hidden" id="teacherOfferingTeacherIdHidden" name="teacher_id" value="">
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Academic Term</span>
                    </label>
                    <select id="teacherOfferingAcademicTermId" name="academic_term_id" class="select select-bordered w-full" required>
                        <option value="">-- Select Academic Term --</option>
                        @foreach ($academicTerms as $term)
                            <option value="{{ $term->id }}">{{ $term->description }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Subject Offering</span>
                    </label>
                    <select id="teacherOfferingOfferingId" name="offering_id" class="select select-bordered w-full" required disabled>
                        <option value="">-- Select Academic Term First --</option>
                    </select>
                </div>

                <div class="form-control col-span-2">
                    <label class="label">
                        <span class="label-text">Status</span>
                    </label>
                    <select id="teacherOfferingStatus" name="status" class="select select-bordered w-full" required>
                        <option value="active" selected>Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
            </div>

            <div class="modal-action">
                <button type="button" class="btn btn-ghost" onclick="document.getElementById('teacherOfferingModal').close()">Cancel</button>
                <button type="submit" class="btn btn-primary">Assign</button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop"><button>close</button></form>
</dialog>

<script>
    const teacherOfferingSubjectOfferingApiBase = @json(url('/department/api/subject-offering'));
    const teacherOfferingDepartmentId = @json($departmentId);

    function resetTeacherOfferingOfferingsSelect(message = '-- Select Academic Term First --') {
        const offeringSelect = document.getElementById('teacherOfferingOfferingId');
        if (!offeringSelect) return;

        offeringSelect.innerHTML = `<option value="">${message}</option>`;
        offeringSelect.disabled = true;
    }

    async function loadTeacherOfferingOfferingsForTerm(academicTermId) {
        const offeringSelect = document.getElementById('teacherOfferingOfferingId');
        if (!offeringSelect) return;

        if (!academicTermId) {
            resetTeacherOfferingOfferingsSelect();
            return;
        }

        offeringSelect.innerHTML = '<option value="">Loading...</option>';
        offeringSelect.disabled = true;

        try {
            const url = `${teacherOfferingSubjectOfferingApiBase}/${academicTermId}/${teacherOfferingDepartmentId}`;
            const response = await fetch(url);
            const offerings = await response.json();

            if (!Array.isArray(offerings) || offerings.length === 0) {
                resetTeacherOfferingOfferingsSelect('-- No subject offerings for this term --');
                return;
            }

            offeringSelect.innerHTML = '<option value="">-- Select Subject Offering --</option>';
            offerings.forEach(offering => {
                const option = document.createElement('option');
                option.value = offering.id;
                option.textContent = `${offering.code} - ${offering.description}`;
                offeringSelect.appendChild(option);
            });

            offeringSelect.disabled = false;
        } catch (e) {
            console.error('Failed to load subject offerings:', e);
            resetTeacherOfferingOfferingsSelect('-- Failed to load offerings --');
        }
    }

    function openTeacherOfferingModal({ teacherId = null, academicTermId = null, lockTeacher = false } = {}) {
        const modal = document.getElementById('teacherOfferingModal');
        const teacherSelect = document.getElementById('teacherOfferingTeacherId');
        const teacherIdHidden = document.getElementById('teacherOfferingTeacherIdHidden');
        const termSelect = document.getElementById('teacherOfferingAcademicTermId');
        const offeringSelect = document.getElementById('teacherOfferingOfferingId');

        if (!modal || !teacherSelect || !teacherIdHidden || !termSelect || !offeringSelect) return;

        // Reset
        teacherSelect.disabled = false;
        termSelect.value = '';
        resetTeacherOfferingOfferingsSelect();
        teacherIdHidden.value = '';

        // Set teacher
        const hasTeacherId = teacherId !== null && teacherId !== undefined && teacherId !== '';
        if (hasTeacherId) {
            teacherSelect.value = String(teacherId);
            teacherIdHidden.value = String(teacherId);
            if (lockTeacher) {
                teacherSelect.disabled = true;
            }
        } else {
            teacherSelect.value = '';
            teacherIdHidden.value = '';
        }

        // Set term and load offerings
        const hasAcademicTermId = academicTermId !== null && academicTermId !== undefined && academicTermId !== '';
        if (hasAcademicTermId) {
            termSelect.value = String(academicTermId);
            loadTeacherOfferingOfferingsForTerm(academicTermId);
        }

        modal.showModal();
    }

    document.addEventListener('DOMContentLoaded', function () {
        const termSelect = document.getElementById('teacherOfferingAcademicTermId');
        const teacherSelect = document.getElementById('teacherOfferingTeacherId');
        const teacherIdHidden = document.getElementById('teacherOfferingTeacherIdHidden');

        if (teacherSelect && teacherIdHidden) {
            teacherIdHidden.value = teacherSelect.value;
            teacherSelect.addEventListener('change', function () {
                teacherIdHidden.value = this.value;
            });
        }

        if (!termSelect) return;

        termSelect.addEventListener('change', function () {
            loadTeacherOfferingOfferingsForTerm(this.value);
        });

        resetTeacherOfferingOfferingsSelect();
    });
</script>
