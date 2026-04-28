<!-- Website Page Toast Notifications -->
<div id="website-toast-container" class="fixed top-4 right-4 z-50 flex flex-col gap-2"></div>

<script>
    function showWebsiteToast(message, type = 'info', duration = 4000) {
        const container = document.getElementById('website-toast-container');
        if (!container) return;

        const alertClasses = {
            success: 'alert-success',
            error: 'alert-error',
            warning: 'alert-warning',
            info: 'alert-info'
        };

        const icons = {
            success: '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
            error: '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
            warning: '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>',
            info: '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>'
        };

        const toast = document.createElement('div');
        toast.className = `alert ${alertClasses[type] || alertClasses.info} shadow-lg min-w-80 max-w-md`;
        toast.style.animation = 'wToastIn 0.3s ease-out';
        toast.innerHTML = `
            <div class="flex items-center gap-2">
                ${icons[type] || icons.info}
                <span>${message}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="btn btn-ghost btn-xs">✕</button>
        `;

        container.appendChild(toast);

        setTimeout(() => {
            toast.style.animation = 'wToastOut 0.3s ease-out';
            setTimeout(() => toast.remove(), 300);
        }, duration);
    }

    // Auto-show session flash messages
    document.addEventListener('DOMContentLoaded', function() {
        @if(session('success'))
            showWebsiteToast(@json(session('success')), 'success');
        @endif
        @if(session('error'))
            showWebsiteToast(@json(session('error')), 'error');
        @endif
        @if($errors->any())
            @foreach($errors->all() as $error)
                showWebsiteToast(@json($error), 'error');
            @endforeach
        @endif
    });
</script>

<style>
    @keyframes wToastIn {
        from { opacity: 0; transform: translateX(100%); }
        to { opacity: 1; transform: translateX(0); }
    }
    @keyframes wToastOut {
        from { opacity: 1; transform: translateX(0); }
        to { opacity: 0; transform: translateX(100%); }
    }
</style>
