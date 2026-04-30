<x-teacher_portal_sidebar>

    @include('partials.notifications')

    <div class="m-4 font-bold text-4xl">
        <h2>Dashboard</h2>
    </div>

    <div class="m-4 grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="card bg-white shadow-lg">
            <div class="card-body">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">Teacher Information</h3>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="font-semibold text-gray-500">Teacher Code</span>
                        <p class="text-base">{{ $teacher->code ?? '-' }}</p>
                    </div>
                    <div>
                        <span class="font-semibold text-gray-500">Name</span>
                        <p class="text-base">{{ $teacher->last_name ?? '' }}, {{ $teacher->first_name ?? '' }} {{ $teacher->middle_name ?? '' }}</p>
                    </div>
                    <div>
                        <span class="font-semibold text-gray-500">Email</span>
                        <p class="text-base">{{ $teacher->email ?? '-' }}</p>
                    </div>
                    <div>
                        <span class="font-semibold text-gray-500">Status</span>
                        <p class="text-base">{{ ucfirst($teacher->status ?? 'N/A') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card bg-white shadow-lg">
            <div class="card-body">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">Quick Links</h3>
                <div class="grid grid-cols-2 gap-4">
                    <a href="{{ route('teacher_portal.subject_load') }}" class="btn btn-outline btn-primary">
                        View Subject Load
                    </a>
                </div>
            </div>
        </div>
    </div>

</x-teacher_portal_sidebar>
