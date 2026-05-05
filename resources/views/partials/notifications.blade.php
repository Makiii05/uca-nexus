<div id="toast-container" class="toast-container" aria-live="polite" aria-atomic="true"></div>

<script>
    (function () {
        const icons = {
            success: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="toast__icon"><path d="M9 12l2 2 4-4"></path><circle cx="12" cy="12" r="9"></circle></svg>',
            error: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="toast__icon"><path d="M15 9l-6 6"></path><path d="M9 9l6 6"></path><circle cx="12" cy="12" r="9"></circle></svg>',
            warning: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="toast__icon"><path d="M12 9v4"></path><path d="M12 17h.01"></path><path d="M10.3 4.9l-7 12.1a2 2 0 001.7 3h14a2 2 0 001.7-3l-7-12.1a2 2 0 00-3.4 0z"></path></svg>',
            info: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="toast__icon"><circle cx="12" cy="12" r="9"></circle><path d="M12 16v-4"></path><path d="M12 8h.01"></path></svg>'
        };

        function removeToast(toast) {
            if (!toast || toast.classList.contains('toast--exit')) return;
            toast.classList.add('toast--exit');
            toast.addEventListener('animationend', () => toast.remove(), { once: true });
        }

        function showToast(message, type = 'info', duration = 4000) {
            const container = document.getElementById('toast-container');
            if (!container) return;

            const validTypes = ['success', 'error', 'warning', 'info'];
            const resolvedType = validTypes.includes(type) ? type : 'info';

            const toast = document.createElement('div');
            toast.className = `toast toast--${resolvedType}`;
            toast.setAttribute('role', 'status');

            const icon = document.createElement('span');
            icon.className = 'toast__icon-wrap';
            icon.innerHTML = icons[resolvedType] || icons.info;

            const text = document.createElement('div');
            text.className = 'toast__message';
            text.textContent = String(message ?? '');

            const close = document.createElement('button');
            close.type = 'button';
            close.className = 'toast__close';
            close.setAttribute('aria-label', 'Close');
            close.textContent = 'x';
            close.addEventListener('click', () => removeToast(toast));

            toast.appendChild(icon);
            toast.appendChild(text);
            toast.appendChild(close);
            container.appendChild(toast);

            if (Number(duration) > 0) {
                setTimeout(() => removeToast(toast), Number(duration));
            }

            return toast;
        }

        window.showToast = showToast;

        if (!window.__toastAlertPatched) {
            window.__toastAlertPatched = true;
            const nativeAlert = window.alert;
            window.alert = function (message) {
                if (document.getElementById('toast-container')) {
                    showToast(String(message ?? ''), 'warning');
                    return;
                }
                nativeAlert(message);
            };
        }

        document.addEventListener('DOMContentLoaded', function () {
            @if(!($suppressSuccessToast ?? false) && session('success'))
                showToast(@json(session('success')), 'success');
            @endif
            @if(session('error'))
                showToast(@json(session('error')), 'error');
            @endif
            @if(session('warning'))
                showToast(@json(session('warning')), 'warning');
            @endif
            @if(session('info'))
                showToast(@json(session('info')), 'info');
            @endif
            @if($errors->any())
                @foreach($errors->all() as $error)
                    showToast(@json($error), 'error');
                @endforeach
            @endif
        });
    })();
</script>

<style>
    .toast-container {
        position: fixed;
        top: 1rem;
        right: 1rem;
        z-index: 9999;
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
        width: min(92vw, 420px);
        pointer-events: none;
    }

    .toast {
        pointer-events: auto;
        display: grid;
        grid-template-columns: 20px 1fr 20px;
        gap: 0.75rem;
        align-items: start;
        padding: 0.75rem 0.9rem;
        border-radius: 0.6rem;
        background: #111827;
        color: #f9fafb;
        border-left: 4px solid var(--toast-accent, #3b82f6);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        animation: toast-slide-in 0.2s ease-out;
    }

    .toast__icon-wrap {
        margin-top: 2px;
        color: inherit;
    }

    .toast__icon {
        width: 18px;
        height: 18px;
    }

    .toast__message {
        font-size: 0.875rem;
        line-height: 1.25rem;
        word-break: break-word;
    }

    .toast__close {
        background: transparent;
        border: 0;
        color: #d1d5db;
        font-size: 0.9rem;
        cursor: pointer;
        line-height: 1;
    }

    .toast__close:hover {
        color: #ffffff;
    }

    .toast--success { --toast-accent: #22c55e; }
    .toast--error { --toast-accent: #ef4444; }
    .toast--warning { --toast-accent: #f59e0b; }
    .toast--info { --toast-accent: #3b82f6; }

    .toast--exit {
        animation: toast-slide-out 0.2s ease-in forwards;
    }

    @keyframes toast-slide-in {
        from { opacity: 0; transform: translateX(20px); }
        to { opacity: 1; transform: translateX(0); }
    }

    @keyframes toast-slide-out {
        from { opacity: 1; transform: translateX(0); }
        to { opacity: 0; transform: translateX(20px); }
    }

    @media (max-width: 640px) {
        .toast-container {
            left: 1rem;
            right: 1rem;
            width: auto;
        }
    }
</style>

@include('partials.delete-confirm-modal')
