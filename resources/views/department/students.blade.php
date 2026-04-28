<x-department_sidebar>

    @include('partials.notifications')
    @include('partials.dept-student-full-modal')

    <div class="flex items-center gap-4 mb-4">
        <h2 class="font-bold text-4xl flex-1">Students</h2>
    </div>
    
    <!-- Search Section -->
    <div class="flex justify-end gap-2 mb-4">
        <input 
            type="text" 
            id="studentSearchInput" 
            placeholder="Search student details" 
            class="input input-bordered w-96"
        >
        <button type="button" id="studentSearchBtn" class="btn btn-primary">
            Search
        </button>
    </div>

    <!--TABLE-->
    <div data-table-wrapper>
    <!-- Hidden search input to prevent auto-injection -->
    <input type="hidden" data-table-search>
    <div class="overflow-x-auto bg-white shadow">
        <table class="table" data-sortable-table>
            <!-- head -->
            <thead>
                <tr>
                    <th>Student No.</th>
                    <th>Full Name</th>
                    <th>Program</th>
                    <th>Level</th>
                    <th>Status</th>
                    <th data-no-sort>Actions</th>
                </tr>
            </thead>
            <tbody id="studentTableBody">
                <tr id="initialMessage">
                    <td colspan="6" class="text-center text-gray-500 py-8">Enter a search term and click Search to find students.</td>
                </tr>
            </tbody>
        </table>
    </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('studentSearchInput');
        const searchBtn = document.getElementById('studentSearchBtn');
        const tableBody = document.getElementById('studentTableBody');

        function performSearch() {
            const searchTerm = searchInput.value.trim();
            
            if (!searchTerm) {
                tableBody.innerHTML = '<tr><td colspan="6" class="text-center text-gray-500 py-8">Please enter a search term.</td></tr>';
                return;
            }

            tableBody.innerHTML = '<tr><td colspan="6" class="text-center text-gray-500 py-8">Searching...</td></tr>';

            fetch(`{{ route('department.api.students.search') }}?search=${encodeURIComponent(searchTerm)}`)
                .then(response => response.json())
                .then(data => {
                    if (data.data.length === 0) {
                        tableBody.innerHTML = '<tr><td colspan="6" class="text-center text-gray-500 py-8">No students found.</td></tr>';
                        return;
                    }

                    tableBody.innerHTML = data.data.map(student => `
                        <tr>
                            <td>${student.student_number}</td>
                            <td>${student.last_name}, ${student.first_name} ${student.middle_name || ''}</td>
                            <td>${student.program?.code || '-'}</td>
                            <td>${student.level?.description || '-'}</td>
                            <td>${student.status ? student.status.charAt(0).toUpperCase() + student.status.slice(1) : '-'}</td>
                            <td>
                                <div class="flex gap-1 flex-wrap">
                                    <button 
                                        type="button" 
                                        class="btn btn-sm btn-ghost text-primary font-semibold"
                                        onclick='openDeptStudentFullModal(${JSON.stringify(student)}, ${JSON.stringify(student.contact)}, ${JSON.stringify(student.guardian)}, ${JSON.stringify(student.academic_history)})'
                                    >
                                        Details
                                    </button>
                                    <a 
                                        href="/department/student/${student.id}/edit" 
                                        class="btn btn-sm btn-ghost text-amber-600 font-semibold"
                                    >
                                        Edit
                                    </a>
                                </div>
                            </td>
                        </tr>
                    `).join('');
                })
                .catch(error => {
                    console.error('Search error:', error);
                    tableBody.innerHTML = '<tr><td colspan="6" class="text-center text-red-500 py-8">Error searching students. Please try again.</td></tr>';
                });
        }

        searchBtn.addEventListener('click', performSearch);
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                performSearch();
            }
        });
    });
    </script>

    @include('partials.table-sort-search')

</x-department_sidebar>        