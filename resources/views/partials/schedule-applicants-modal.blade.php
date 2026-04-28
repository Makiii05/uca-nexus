<!-- Schedule Applicants Modal -->
<dialog id="schedule_applicants_modal" class="modal">
    <div class="modal-box w-11/12 max-w-5xl">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </form>
        
        <h3 class="font-bold text-2xl mb-2">Applicants for Schedule</h3>
        <div class="mb-4">
            <p class="text-sm text-gray-500">
                <span class="font-medium" id="modal_schedule_type"></span> | 
                <span id="modal_schedule_date"></span> | 
                <span id="modal_schedule_time"></span> | 
                Proctor: <span id="modal_schedule_proctor"></span>
            </p>
        </div>
        
        <!-- Applicants Table -->
        <div class="overflow-x-auto bg-gray-50 rounded-lg">
            <table class="table table-zebra w-full">
                <thead>
                    <tr class="bg-gray-200">
                        <th>#</th>
                        <th>Application No.</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Mobile</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody id="schedule_applicants_tbody">
                    <!-- Populated via JavaScript -->
                </tbody>
            </table>
        </div>
        
        <div class="mt-4 text-sm text-gray-500">
            Total Applicants: <span id="modal_applicants_count" class="font-bold">0</span>
        </div>
        
        <div class="modal-action">
            <form method="dialog">
                <button class="btn">Close</button>
            </form>
        </div>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>

<script>
    function showScheduleApplicants(scheduleId, proctor, date, startTime, endTime, process, applicantsData) {
        // Set schedule info
        document.getElementById('modal_schedule_type').textContent = process.charAt(0).toUpperCase() + process.slice(1);
        document.getElementById('modal_schedule_date').textContent = date;
        document.getElementById('modal_schedule_time').textContent = startTime + ' - ' + endTime;
        document.getElementById('modal_schedule_proctor').textContent = proctor;
        
        // Parse applicants data
        const applicants = JSON.parse(applicantsData);
        const tbody = document.getElementById('schedule_applicants_tbody');
        
        // Clear existing rows
        tbody.innerHTML = '';
        
        if (applicants.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="6" class="text-center text-gray-500 py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                        No applicants assigned to this schedule yet.
                    </td>
                </tr>
            `;
        } else {
            applicants.forEach((applicant, index) => {
                const statusBadge = getStatusBadge(applicant.status);
                tbody.innerHTML += `
                    <tr>
                        <td>${index + 1}</td>
                        <td class="font-mono">${applicant.application_no || '-'}</td>
                        <td class="font-medium">${applicant.last_name}, ${applicant.first_name} ${applicant.middle_name || ''}</td>
                        <td>${applicant.email || '-'}</td>
                        <td>${applicant.mobile_number || '-'}</td>
                        <td>${statusBadge}</td>
                    </tr>
                `;
            });
        }
        
        // Update count
        document.getElementById('modal_applicants_count').textContent = applicants.length;
        
        // Show modal
        document.getElementById('schedule_applicants_modal').showModal();
    }
    
    function getStatusBadge(status) {
        const statusClasses = {
            'pending': 'badge badge-warning',
            'for_exam': 'badge badge-info',
            'for_interview': 'badge badge-info',
            'for_evaluation': 'badge badge-primary',
            'accepted': 'badge badge-success',
            'rejected': 'badge badge-error',
            'waitlisted': 'badge badge-warning'
        };
        
        const badgeClass = statusClasses[status] || 'badge badge-ghost';
        const displayStatus = status ? status.replace(/_/g, ' ').toUpperCase() : 'N/A';
        
        return `<span class="${badgeClass}">${displayStatus}</span>`;
    }
</script>
