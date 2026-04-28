<!-- Website Preview Modal (Fullscreen) -->
<dialog id="websitePreviewModal" class="modal">
    <div class="modal-box w-full max-w-full h-full max-h-full rounded-none p-0 m-0 flex flex-col">
        <!-- Preview Toolbar -->
        <div class="flex items-center justify-between px-4 py-2 bg-gray-800 text-white shrink-0">
            <span class="font-semibold text-sm">Landing Page Preview</span>
            <div class="flex items-center gap-2">
                <!-- Orientation Buttons -->
                <button onclick="setPreviewMode('desktop')" id="previewDesktopBtn" class="btn btn-sm btn-ghost text-white border border-white/30 bg-white/20">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    <span class="hidden sm:inline">Desktop</span>
                </button>
                <button onclick="setPreviewMode('mobile')" id="previewMobileBtn" class="btn btn-sm btn-ghost text-white border border-white/30">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    <span class="hidden sm:inline">Mobile</span>
                </button>
                <button onclick="document.getElementById('websitePreviewModal').close()" class="btn btn-sm btn-ghost text-white">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>
        <!-- Preview Content -->
        <div class="flex-1 bg-gray-300 flex items-start justify-center overflow-auto p-4" id="previewContainer">
            <iframe id="previewIframe" src="{{ url('/') }}" class="bg-white shadow-2xl transition-all duration-300 border-0" style="width:100%; height:100%;"></iframe>
        </div>
    </div>
</dialog>

<script>
    function openPreviewModal() {
        const modal = document.getElementById('websitePreviewModal');
        modal.showModal();
        setPreviewMode('desktop');
        // Reload iframe
        const iframe = document.getElementById('previewIframe');
        iframe.src = iframe.src;
    }

    function setPreviewMode(mode) {
        const iframe = document.getElementById('previewIframe');
        const container = document.getElementById('previewContainer');
        const desktopBtn = document.getElementById('previewDesktopBtn');
        const mobileBtn = document.getElementById('previewMobileBtn');

        desktopBtn.classList.remove('bg-white/20');
        mobileBtn.classList.remove('bg-white/20');

        if (mode === 'mobile') {
            iframe.style.width = '375px';
            iframe.style.height = '667px';
            iframe.style.maxWidth = '100%';
            mobileBtn.classList.add('bg-white/20');
        } else {
            iframe.style.width = '100%';
            iframe.style.height = '100%';
            desktopBtn.classList.add('bg-white/20');
        }
    }
</script>
