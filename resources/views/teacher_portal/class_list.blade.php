<x-teacher_portal_sidebar>

    @include('partials.notifications')

    <div class="m-4 font-bold text-3xl">Class List</div>

    <div class="m-4">
        <div class="card bg-white shadow p-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
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
                    <label class="label"><span class="label-text">Subject Offering</span></label>
                    <select id="subject-offering" class="select select-bordered w-full">
                        <option value="">-- Select Offering --</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button id="load-class" class="btn btn-primary w-full">Load Class</button>
                </div>
            </div>

            <div class="mt-6">
                <div id="students-area">
                    <p class="text-sm text-gray-500">Select an academic term and offering then click "Load Class" to see students.</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const academicTermSelect = document.getElementById('academic-term');
            const offeringSelect = document.getElementById('subject-offering');
            const loadButton = document.getElementById('load-class');
            const studentsArea = document.getElementById('students-area');

            academicTermSelect.addEventListener('change', async function() {
                const termId = this.value;
                offeringSelect.innerHTML = '<option value="">-- Select Offering --</option>';
                if (!termId) return;

                const res = await fetch(`{{ url('/teacher-portal') }}/api/subject-offerings/${termId}`);
                if (!res.ok) return;
                const data = await res.json();
                data.offerings.forEach(o => {
                    const opt = document.createElement('option');
                    opt.value = o.id;
                    opt.textContent = `${o.code}`;
                    offeringSelect.appendChild(opt);
                });
            });

            loadButton.addEventListener('click', async function() {
                const offeringId = offeringSelect.value;
                if (!offeringId) {
                    studentsArea.innerHTML = '<p class="text-red-500">Please select a subject offering.</p>';
                    return;
                }

                studentsArea.innerHTML = '<p>Loading...</p>';
                const res = await fetch(`{{ url('/teacher-portal') }}/api/class-list/${offeringId}`);
                if (!res.ok) {
                    studentsArea.innerHTML = '<p class="text-red-500">Failed to load class list.</p>';
                    return;
                }
                const data = await res.json();
                if (!data.students || data.students.length === 0) {
                    studentsArea.innerHTML = '<p class="text-sm text-gray-500">No students found for this offering.</p>';
                    return;
                }

                let html = '<table class="table w-full">';
                html += '<thead><tr><th>#</th><th>Student Number</th><th>Name</th></tr></thead><tbody>';
                data.students.forEach((s, idx) => {
                    html += `<tr><td>${idx+1}</td><td>${s.student_number}</td><td>${s.student_name}</td></tr>`;
                });
                html += '</tbody></table>';
                studentsArea.innerHTML = html;
            });
        });
    </script>

</x-teacher_portal_sidebar>
