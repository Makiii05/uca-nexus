<x-student_portal_sidebar>

    <div class="m-4 font-bold text-4xl">
        <h2>Dashboard</h2>
    </div>

    <!-- Student Info Card -->
    <div class="m-4 grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="card bg-white shadow-lg">
            <div class="card-body">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">Student Information</h3>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="font-semibold text-gray-500">Student No.</span>
                        <p class="text-base">{{ $student->student_number ?? '-' }}</p>
                    </div>
                    <div>
                        <span class="font-semibold text-gray-500">Name</span>
                        <p class="text-base">{{ $student->last_name ?? '' }}, {{ $student->first_name ?? '' }} {{ $student->middle_name ?? '' }}</p>
                    </div>
                    <div>
                        <span class="font-semibold text-gray-500">Program</span>
                        <p class="text-base">{{ $student->program->code ?? '-' }} - {{ $student->program->description ?? '' }}</p>
                    </div>
                    <div>
                        <span class="font-semibold text-gray-500">Department</span>
                        <p class="text-base">{{ $student->department->code ?? '-' }}</p>
                    </div>
                    <div>
                        <span class="font-semibold text-gray-500">Year Level</span>
                        <p class="text-base">{{ $student->level->description ?? '-' }}</p>
                    </div>
                    <div>
                        <span class="font-semibold text-gray-500">Contact No.</span>
                        <p class="text-base">{{ $student->contact->mobile_number ?? '-' }}</p>
                    </div>
                    <div>
                        <span class="font-semibold text-gray-500">Status</span>
                        <p class="text-base">{{ ucfirst($student->status ?? 'N/A') }}</p>
                    </div>
                    <div>
                        <span class="font-semibold text-gray-500">Student Type</span>
                        <p class="text-base">{{ ucfirst($student->student_type ?? '-') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Links Card -->
        <div class="card bg-white shadow-lg">
            <div class="card-body">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">Quick Links</h3>
                <div class="grid grid-cols-2 gap-4">
                    <a href="{{ route('student_portal.ledger') }}" class="btn btn-outline btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                        </svg>
                        View Ledger
                    </a>
                    <a href="{{ route('student_portal.examination_permit') }}" class="btn btn-outline btn-secondary">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
                        </svg>
                        Examination Permit
                    </a>
                </div>
            </div>
        </div>
    </div>

</x-student_portal_sidebar>
