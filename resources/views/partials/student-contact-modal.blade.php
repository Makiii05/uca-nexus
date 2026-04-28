<!-- Student Contact Modal -->
<dialog id="student_contact_modal" class="modal">
    <div class="modal-box w-11/12 max-w-3xl">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </form>
        
        <h3 class="font-bold text-2xl mb-2">Student Contact Information</h3>
        <p class="text-gray-500 mb-6">Student: <span id="contact_modal_student_number" class="font-medium">-</span></p>
        
        <!-- Contact Information -->
        <div class="bg-yellow-50 p-4 rounded-lg mb-4">
            <h4 class="font-semibold text-lg mb-3 text-primary">Contact Details</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <span class="text-sm text-gray-500">Email</span>
                    <p class="font-medium" id="contact_modal_email">-</p>
                </div>
                <div>
                    <span class="text-sm text-gray-500">Mobile Number</span>
                    <p class="font-medium" id="contact_modal_mobile_number">-</p>
                </div>
                <div>
                    <span class="text-sm text-gray-500">Telephone Number</span>
                    <p class="font-medium" id="contact_modal_telephone_number">-</p>
                </div>
                <div>
                    <span class="text-sm text-gray-500">Zip Code</span>
                    <p class="font-medium" id="contact_modal_zip_code">-</p>
                </div>
            </div>
        </div>

        <!-- Address Information -->
        <div class="bg-gray-50 p-4 rounded-lg mb-4">
            <h4 class="font-semibold text-lg mb-3 text-primary">Address</h4>
            <div class="grid grid-cols-1 gap-4">
                <div>
                    <span class="text-sm text-gray-500">Present Address</span>
                    <p class="font-medium" id="contact_modal_present_address">-</p>
                </div>
                <div>
                    <span class="text-sm text-gray-500">Permanent Address</span>
                    <p class="font-medium" id="contact_modal_permanent_address">-</p>
                </div>
            </div>
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
    function openStudentContactModal(contact, studentNumber) {
        document.getElementById('contact_modal_student_number').textContent = studentNumber || '-';
        
        if (contact) {
            document.getElementById('contact_modal_email').textContent = contact.email || '-';
            document.getElementById('contact_modal_mobile_number').textContent = contact.mobile_number || '-';
            document.getElementById('contact_modal_telephone_number').textContent = contact.telephone_number || '-';
            document.getElementById('contact_modal_zip_code').textContent = contact.zip_code || '-';
            document.getElementById('contact_modal_present_address').textContent = contact.present_address || '-';
            document.getElementById('contact_modal_permanent_address').textContent = contact.permanent_address || '-';
        } else {
            document.getElementById('contact_modal_email').textContent = '-';
            document.getElementById('contact_modal_mobile_number').textContent = '-';
            document.getElementById('contact_modal_telephone_number').textContent = '-';
            document.getElementById('contact_modal_zip_code').textContent = '-';
            document.getElementById('contact_modal_present_address').textContent = '-';
            document.getElementById('contact_modal_permanent_address').textContent = '-';
        }

        document.getElementById('student_contact_modal').showModal();
    }
</script>
