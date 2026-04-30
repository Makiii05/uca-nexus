<x-teacher_portal_sidebar>

    @include('partials.notifications')

    <div class="flex items-center gap-4 mb-4">
        <a href="{{ route('teacher_portal.subject_load', ['academic_term_id' => $teacherOffering->academic_term_id]) }}" class="btn btn-ghost btn-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
            Back
        </a>
        <h2 class="font-bold text-4xl flex-1">Input Grade</h2>
    </div>

    <div class="card bg-white shadow">
        <div class="card-body">
            <h3 class="text-xl font-semibold text-gray-800 mb-2">Subject Offering</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div>
                    <span class="font-semibold text-gray-500">Section</span>
                    <p class="text-base">{{ $teacherOffering->offering?->code ?? '-' }}</p>
                </div>
                <div>
                    <span class="font-semibold text-gray-500">Subject</span>
                    <p class="text-base">{{ $teacherOffering->offering?->subject?->code ?? '-' }} - {{ $teacherOffering->offering?->subject?->description ?? '' }}</p>
                </div>
                <div>
                    <span class="font-semibold text-gray-500">Academic Term</span>
                    <p class="text-base">{{ $teacherOffering->academicTerm?->description ?? '-' }}</p>
                </div>
            </div>

            <div class="alert bg-blue-400 text-white mt-6">
                <span>Grade input page is not implemented yet.</span>
            </div>
        </div>
    </div>

</x-teacher_portal_sidebar>
