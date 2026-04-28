<x-admission_sidebar>

    <div class="mx-auto">
        <!-- Header -->
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-gray-800">Dashboard</h1>
                <p class="text-sm text-gray-500">Overview of admission statistics</p>
            </div>
            <div class="flex items-center gap-3">
                <!-- Academic Year Selector -->
                <form method="GET" action="{{ route('admission.dashboard') }}" class="flex items-center gap-2">
                    <label for="academic_year" class="text-sm font-medium text-gray-600 whitespace-nowrap">Academic Year:</label>
                    <select name="academic_year" id="academic_year" onchange="this.form.submit()"
                        class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-main-primary focus:border-main-primary">
                        @foreach($academicYears as $year)
                            <option value="{{ $year }}" {{ $selectedYear === $year ? 'selected' : '' }}>{{ $year }}</option>
                        @endforeach
                    </select>
                </form>
                <a href="{{ route('admission.print.admission.stats', ['academic_year' => $selectedYear]) }}" target="_blank" 
                   class="inline-flex items-center gap-2 bg-main-primary hover:bg-main-primary/90 bg-black text-white px-4 py-2 rounded-lg shadow-sm transition-colors duration-200">
                    Print Stats
                </a>
            </div>
        </div>

        <!-- Top Row: Official Students & Total Applicants -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <!-- Official Students Card -->
            <div class="bg-white border-l-4 border-emerald-500 rounded-lg p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 uppercase tracking-wide">Official Students</p>
                        <p class="text-3xl font-bold text-gray-800 mt-1">{{ number_format($officialStudents) }}</p>
                    </div>
                    <div class="text-gray-300">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-10">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Applicants Card -->
            <div class="bg-white border-l-4 border-blue-500 rounded-lg p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 uppercase tracking-wide">Total Applicants</p>
                        <p class="text-3xl font-bold text-gray-800 mt-1">{{ number_format($totalApplicants) }}</p>
                    </div>
                    <div class="text-gray-300">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-10">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Admission Process Row -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
            <!-- Interview Card -->
            <div class="bg-white border-l-4 border-amber-500 rounded-lg p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 uppercase tracking-wide">Interviewee</p>
                        <p class="text-3xl font-bold text-gray-800 mt-1">{{ number_format($interviewCount) }}</p>
                    </div>
                    <div class="text-gray-300">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-10">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 0 1-.825-.242m9.345-8.334a2.126 2.126 0 0 0-.476-.095 48.64 48.64 0 0 0-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.637c0-1.621-1.152-3.026-2.76-3.235A48.455 48.455 0 0 0 11.25 3c-2.115 0-4.198.137-6.24.402-1.608.209-2.76 1.614-2.76 3.235v6.226c0 1.621 1.152 3.026 2.76 3.235.577.075 1.157.14 1.74.194V21l4.155-4.155" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Exam Card -->
            <div class="bg-white border-l-4 border-violet-500 rounded-lg p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 uppercase tracking-wide">Examinee</p>
                        <p class="text-3xl font-bold text-gray-800 mt-1">{{ number_format($examCount) }}</p>
                    </div>
                    <div class="text-gray-300">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-10">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Evaluation Card -->
            <div class="bg-white border-l-4 border-rose-500 rounded-lg p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 uppercase tracking-wide">Evaluatee</p>
                        <p class="text-3xl font-bold text-gray-800 mt-1">{{ number_format($evaluationCount) }}</p>
                    </div>
                    <div class="text-gray-300">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-10">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 0 1-3.296-1.043 3.745 3.745 0 0 1-1.043-3.296A3.745 3.745 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 0 1 1.043-3.296 3.746 3.746 0 0 1 3.296-1.043A3.746 3.746 0 0 1 12 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 0 1 3.296 1.043 3.746 3.746 0 0 1 1.043 3.296A3.745 3.745 0 0 1 21 12Z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Admitted Card -->
            <div class="bg-white border-l-4 border-green-500 rounded-lg p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 uppercase tracking-wide">Admitted</p>
                        <p class="text-3xl font-bold text-gray-800 mt-1">{{ number_format($admittedCount) }}</p>
                    </div>
                    <div class="text-gray-300">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-10">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Variance Card -->
            <div class="bg-white border-l-4 border-orange-500 rounded-lg p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 uppercase tracking-wide">Variance</p>
                        <p class="text-3xl font-bold {{ $variance > 0 ? 'text-red-600' : 'text-green-600' }} mt-1">{{ number_format($variance) }}</p>
                        <p class="text-xs text-gray-400 mt-1">Applicants − Admitted</p>
                    </div>
                    <div class="text-gray-300">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-10">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Feeder School Data Table -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200" data-table-wrapper>
            <div class="px-5 py-4 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-800">Feeder School — {{ $selectedYear }}</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full" data-sortable-table>
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">School</th>
                            <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ongoing</th>
                            <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Failed</th>
                            <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Passed</th>
                            <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Applicant</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($feederSchools as $school)
                        <tr class="hover:bg-gray-50">
                            <td class="px-5 py-3 text-sm text-gray-800">{{ $school->last_school_attended }}</td>
                            <td class="px-5 py-3 text-sm text-gray-600">{{ number_format($school->ongoing) }}</td>
                            <td class="px-5 py-3 text-sm text-gray-600">{{ number_format($school->failed) }}</td>
                            <td class="px-5 py-3 text-sm text-gray-600">{{ number_format($school->passed) }}</td>
                            <td class="px-5 py-3 text-sm font-medium text-gray-800">{{ number_format($school->total_applicants) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-5 py-10 text-center text-gray-400">
                                No data available
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @include('partials.table-sort-search')
    </div>

</x-admission_sidebar>