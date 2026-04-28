<x-registrar_sidebar>

    <div class="m-4 font-bold text-4xl">
        <h2>Class List</h2>
    </div>

    @include('partials.notifications')

    <!-- Search Form -->
    <div class="flex justify-between items-center">
        <form action="{{ route('registrar.classlist') }}" method="GET" class="ms-auto flex gap-2">
            <input type="text" name="search" value="{{ $search }}" placeholder="Search subject, program, or level..." 
                   class="input input-bordered w-64">
            <button type="submit" class="btn btn-primary">Search</button>
            @if($search)
                <a href="{{ route('registrar.classlist') }}" class="btn btn-ghost">Clear</a>
            @endif
        </form>
    </div>

    <!--TABLE-->
    <div class="overflow-x-auto bg-white shadow m-4">
        <table class="table">
            <thead>
                <tr>
                    <th>
                        <a href="{{ route('registrar.classlist', ['search' => $search, 'sort_by' => 'code', 'sort_dir' => ($sortBy === 'code' && $sortDir === 'asc') ? 'desc' : 'asc']) }}" 
                           class="flex items-center gap-1 hover:text-primary">
                            Subject Code
                            @if($sortBy === 'code')
                                <span class="text-xs">{{ $sortDir === 'asc' ? '↑' : '↓' }}</span>
                            @else
                                <span class="text-xs opacity-40">⇅</span>
                            @endif
                        </a>
                    </th>
                    <th>
                        <a href="{{ route('registrar.classlist', ['search' => $search, 'sort_by' => 'description', 'sort_dir' => ($sortBy === 'description' && $sortDir === 'asc') ? 'desc' : 'asc']) }}" 
                           class="flex items-center gap-1 hover:text-primary">
                            Description
                            @if($sortBy === 'description')
                                <span class="text-xs">{{ $sortDir === 'asc' ? '↑' : '↓' }}</span>
                            @else
                                <span class="text-xs opacity-40">⇅</span>
                            @endif
                        </a>
                    </th>
                    <th>
                        <a href="{{ route('registrar.classlist', ['search' => $search, 'sort_by' => 'program', 'sort_dir' => ($sortBy === 'program' && $sortDir === 'asc') ? 'desc' : 'asc']) }}" 
                           class="flex items-center gap-1 hover:text-primary">
                            Program
                            @if($sortBy === 'program')
                                <span class="text-xs">{{ $sortDir === 'asc' ? '↑' : '↓' }}</span>
                            @else
                                <span class="text-xs opacity-40">⇅</span>
                            @endif
                        </a>
                    </th>
                    <th>
                        <a href="{{ route('registrar.classlist', ['search' => $search, 'sort_by' => 'level', 'sort_dir' => ($sortBy === 'level' && $sortDir === 'asc') ? 'desc' : 'asc']) }}" 
                           class="flex items-center gap-1 hover:text-primary">
                            Level
                            @if($sortBy === 'level')
                                <span class="text-xs">{{ $sortDir === 'asc' ? '↑' : '↓' }}</span>
                            @else
                                <span class="text-xs opacity-40">⇅</span>
                            @endif
                        </a>
                    </th>
                    <th>
                        <a href="{{ route('registrar.classlist', ['search' => $search, 'sort_by' => 'enrolled', 'sort_dir' => ($sortBy === 'enrolled' && $sortDir === 'asc') ? 'desc' : 'asc']) }}" 
                           class="flex items-center gap-1 hover:text-primary">
                            Enrolled
                            @if($sortBy === 'enrolled')
                                <span class="text-xs">{{ $sortDir === 'asc' ? '↑' : '↓' }}</span>
                            @else
                                <span class="text-xs opacity-40">⇅</span>
                            @endif
                        </a>
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse ($subjectOfferings as $offering)
                <tr class="hover:bg-gray-100 cursor-pointer transition-colors" 
                    onclick="window.location='{{ route('registrar.classlist.enrolled', $offering->id) }}'">
                    <td>{{ $offering->subject->code ?? 'N/A' }}</td>
                    <td>{{ $offering->subject->description ?? 'N/A' }}</td>
                    <td>{{ $offering->program->code ?? 'N/A' }}</td>
                    <td>{{ $offering->level->description ?? 'N/A' }}</td>
                    <td class="text-green-600 font-semibold">{{ $offering->enrolled_count }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-gray-500 py-8">No subject offerings found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="m-3">
        {{ $subjectOfferings->links() }}
    </div>

</x-registrar_sidebar>
