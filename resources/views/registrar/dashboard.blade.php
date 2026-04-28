<x-registrar_sidebar>
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-gray-800">Dashboard</h1>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Departments Card -->
            <div class="card bg-white shadow-lg">
                <div class="card-body">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500 uppercase tracking-wide">Departments</p>
                            <p class="text-3xl font-bold text-gray-800">{{ $departmentCount }}</p>
                        </div>
                        <div class="p-3 bg-blue-100 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-8 text-blue-600">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Programs Card -->
            <div class="card bg-white shadow-lg">
                <div class="card-body">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500 uppercase tracking-wide">Programs</p>
                            <p class="text-3xl font-bold text-gray-800">{{ $programCount }}</p>
                        </div>
                        <div class="p-3 bg-green-100 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-8 text-green-600">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Students Card -->
            <div class="card bg-white shadow-lg">
                <div class="card-body">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500 uppercase tracking-wide">Students</p>
                            <p class="text-3xl font-bold text-gray-800">{{ $studentCount }}</p>
                        </div>
                        <div class="p-3 bg-purple-100 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-8 text-purple-600">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Enrollment Status Toggle -->
        <div class="card bg-white shadow-lg">
            <div class="card-body">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-800">Enrollment Status</h2>
                        <p class="text-sm text-gray-500">Toggle to open or close enrollment</p>
                    </div>
                    <div class="flex items-center gap-4">
                        <span id="enrollment-status-label" class="badge {{ $enrollmentStatus === 'open' ? 'badge-success' : 'badge-error' }} badge-lg">
                            {{ ucfirst($enrollmentStatus) }}
                        </span>
                        <input type="checkbox" 
                               id="enrollment-toggle" 
                               class="toggle toggle-success toggle-lg" 
                               {{ $enrollmentStatus === 'open' ? 'checked' : '' }}
                               onchange="toggleEnrollmentStatus()" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Enrollment Statistics Table -->
        <div class="card bg-white shadow-lg">
            <div class="card-body">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Enrollment Statistics by Program</h2>
                <div class="overflow-x-auto">
                    <table class="table table-zebra w-full">
                        <thead>
                            <tr>
                                <th rowspan="2" class="border text-center align-middle">Program</th>
                                <th colspan="3" class="border text-center bg-green-50">New Students</th>
                                <th colspan="3" class="border text-center bg-blue-50">Old Students</th>
                                <th colspan="3" class="border text-center bg-gray-100">Total</th>
                            </tr>
                            <tr>
                                <th class="border text-center bg-green-50">Male</th>
                                <th class="border text-center bg-green-50">Female</th>
                                <th class="border text-center bg-green-50">Total</th>
                                <th class="border text-center bg-blue-50">Male</th>
                                <th class="border text-center bg-blue-50">Female</th>
                                <th class="border text-center bg-blue-50">Total</th>
                                <th class="border text-center bg-gray-100">Male</th>
                                <th class="border text-center bg-gray-100">Female</th>
                                <th class="border text-center bg-gray-100 font-bold">Grand Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $totalNewMale = 0;
                                $totalNewFemale = 0;
                                $totalNewTotal = 0;
                                $totalOldMale = 0;
                                $totalOldFemale = 0;
                                $totalOldTotal = 0;
                                $totalMale = 0;
                                $totalFemale = 0;
                                $grandTotal = 0;
                            @endphp
                            @forelse($enrollmentStats as $stat)
                                @php
                                    $totalNewMale += $stat['new_male'];
                                    $totalNewFemale += $stat['new_female'];
                                    $totalNewTotal += $stat['new_total'];
                                    $totalOldMale += $stat['old_male'];
                                    $totalOldFemale += $stat['old_female'];
                                    $totalOldTotal += $stat['old_total'];
                                    $totalMale += $stat['total_male'];
                                    $totalFemale += $stat['total_female'];
                                    $grandTotal += $stat['grand_total'];
                                @endphp
                                <tr>
                                    <td class="border font-medium">{{ $stat['program_code'] }}</td>
                                    <td class="border text-center">{{ $stat['new_male'] }}</td>
                                    <td class="border text-center">{{ $stat['new_female'] }}</td>
                                    <td class="border text-center font-semibold">{{ $stat['new_total'] }}</td>
                                    <td class="border text-center">{{ $stat['old_male'] }}</td>
                                    <td class="border text-center">{{ $stat['old_female'] }}</td>
                                    <td class="border text-center font-semibold">{{ $stat['old_total'] }}</td>
                                    <td class="border text-center">{{ $stat['total_male'] }}</td>
                                    <td class="border text-center">{{ $stat['total_female'] }}</td>
                                    <td class="border text-center font-bold">{{ $stat['grand_total'] }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center text-gray-500 py-4">No programs found</td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr class="bg-gray-200 font-bold">
                                <td class="border">TOTAL</td>
                                <td class="border text-center">{{ $totalNewMale }}</td>
                                <td class="border text-center">{{ $totalNewFemale }}</td>
                                <td class="border text-center">{{ $totalNewTotal }}</td>
                                <td class="border text-center">{{ $totalOldMale }}</td>
                                <td class="border text-center">{{ $totalOldFemale }}</td>
                                <td class="border text-center">{{ $totalOldTotal }}</td>
                                <td class="border text-center">{{ $totalMale }}</td>
                                <td class="border text-center">{{ $totalFemale }}</td>
                                <td class="border text-center">{{ $grandTotal }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        async function toggleEnrollmentStatus() {
            const toggle = document.getElementById('enrollment-toggle');
            const label = document.getElementById('enrollment-status-label');
            
            try {
                const response = await fetch('{{ route("registrar.api.enrollment-status.toggle") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                    },
                });
                
                const data = await response.json();
                
                // Update the label
                label.textContent = data.status.charAt(0).toUpperCase() + data.status.slice(1);
                label.className = data.is_open 
                    ? 'badge badge-success badge-lg' 
                    : 'badge badge-error badge-lg';
                    
            } catch (error) {
                console.error('Error toggling enrollment status:', error);
                // Revert toggle if error
                toggle.checked = !toggle.checked;
            }
        }
    </script>
</x-registrar_sidebar>