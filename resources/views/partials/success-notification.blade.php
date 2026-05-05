@if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof showToast !== 'function') return;

            const baseMessage = @json(session('success'));
            const applicationNo = @json(session('application_no'));
            const applicantEmail = @json(session('applicant_email'));
            const isNew = @json(session('is_new'));

            const parts = [];
            if (applicationNo) parts.push('Application No: ' + applicationNo);
            if (applicantEmail) parts.push('Email: ' + applicantEmail);
            if (isNew === true) parts.push('Status: New application received.');
            if (isNew === false) parts.push('Status: Application updated.');

            const fullMessage = [baseMessage, ...parts].filter(Boolean).join(' | ');
            showToast(fullMessage, 'success', 6000);
        });
    </script>
@endif
