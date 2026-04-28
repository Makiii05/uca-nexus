@if($errors->any())
    <div class="alert alert-error shadow-lg m-4" role="alert">
        <div>
            <div>
                <h3 class="font-bold">Error!</h3>
                <div class="text-sm">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endif

@if(session('success'))
    <div class="alert alert-success shadow-lg m-4" role="alert">
        <div>
            <div>
                <h3 class="font-bold">Success!</h3>
                <div class="text-sm">{{ session('success') }}</div>
            </div>
        </div>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-error shadow-lg m-4" role="alert">
        <div>
            <div>
                <h3 class="font-bold">Error!</h3>
                <div class="text-sm">{{ session('error') }}</div>
            </div>
        </div>
    </div>
@endif

@if(session('warning'))
    <div class="alert alert-warning shadow-lg m-4" role="alert">
        <div>
            <div>
                <h3 class="font-bold">Warning!</h3>
                <div class="text-sm">{{ session('warning') }}</div>
            </div>
        </div>
    </div>
@endif

@if(session('info'))
    <div class="alert alert-info shadow-lg m-4" role="alert">
        <div>
            <div>
                <h3 class="font-bold">Info</h3>
                <div class="text-sm">{{ session('info') }}</div>
            </div>
        </div>
    </div>
@endif

<!-- Toast Notification Container -->
<div id="toast-container" class="fixed top-4 right-4 z-50 flex flex-col gap-2"></div>

<script>
    /**
     * Show a toast notification
     * @param {string} message - The message to display
     * @param {string} type - 'success', 'error', 'warning', 'info'
     * @param {number} duration - Duration in ms (default: 4000)
     */
    function showToast(message, type = 'info', duration = 4000) {
        const container = document.getElementById('toast-container');
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
        toast.className = `alert ${alertClasses[type] || alertClasses.info} shadow-lg min-w-80 max-w-md animate-fade-in`;
        toast.innerHTML = `
            <div class="flex items-center gap-2">
                ${icons[type] || icons.info}
                <span>${message}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="btn btn-ghost btn-xs">✕</button>
        `;

        container.appendChild(toast);

        // Auto-remove after duration
        setTimeout(() => {
            toast.classList.add('animate-fade-out');
            setTimeout(() => toast.remove(), 300);
        }, duration);
    }
</script>

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateX(100%); }
        to { opacity: 1; transform: translateX(0); }
    }
    @keyframes fadeOut {
        from { opacity: 1; transform: translateX(0); }
        to { opacity: 0; transform: translateX(100%); }
    }
    .animate-fade-in {
        animation: fadeIn 0.3s ease-out;
    }
    .animate-fade-out {
        animation: fadeOut 0.3s ease-out;
    }
</style>
