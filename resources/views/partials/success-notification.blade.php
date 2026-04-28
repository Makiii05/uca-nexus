@if(session('success'))
    <!-- Backdrop -->
    <div id="success-backdrop" class="fixed inset-0 bg-black/50 z-40" onclick="closeSuccessModal()"></div>
    
    <!-- Modal -->
    <div id="success-modal" class="fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 z-50">
        <div class="bg-white rounded-lg shadow-xl border-l-4 border-green-500 p-8 max-w-md w-full">
            <!-- Success Icon -->
            <div class="flex justify-center mb-4">
                <div class="flex items-center justify-center w-16 h-16 bg-green-100 rounded-full">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
            </div>
            
            <!-- Message -->
            <h3 class="text-center text-xl font-bold text-gray-800 mb-2">Application Submitted Successfully</h3>
            
            <!-- Application Number -->
            <div class="bg-gray-50 rounded-lg p-4 mb-4 border border-gray-200">
                <p class="text-sm text-gray-600 font-medium">Your Application Number:</p>
                <p class="text-lg font-mono font-bold text-green-600">{{ session('application_no') }}</p>
                <p class="text-xs text-gray-500 mt-2">Please save this number for your records</p>
            </div>
            
            <!-- Status Info -->
            <div class="bg-blue-50 rounded-lg p-4 mb-4 border border-blue-200">
                <p class="text-sm text-blue-800">
                    @if(session('is_new'))
                        <strong>New Application:</strong> Your application has been received and is now under review.
                    @else
                        <strong>Application Updated:</strong> Your application information has been successfully updated.
                    @endif
                </p>
            </div>

            <!-- Email Confirmation -->
            <div class="bg-green-50 rounded-lg p-4 mb-6 border border-green-200 flex items-start gap-3">
                <svg class="w-5 h-5 text-green-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
                <p class="text-sm text-green-800">
                    A confirmation email has been sent to
                    <strong>{{ session('applicant_email') }}</strong>.
                    Please check your inbox (and spam folder) for the details.
                </p>
            </div>
            
            <!-- Close Button -->
            <div class="flex justify-center">
                <button onclick="closeSuccessModal()" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium">
                    Close
                </button>
            </div>
        </div>
    </div>
    
    <script>
        function closeSuccessModal() {
            document.getElementById('success-modal')?.remove();
            document.getElementById('success-backdrop')?.remove();
        }
    </script>
@endif
