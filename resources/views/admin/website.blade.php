<x-admin_sidebar>

    <div class="p-4">
        <!-- Page Header -->
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Website Content Manager</h1>
            <div class="flex gap-2">
                <button onclick="openPreviewModal()" class="btn btn-outline btn-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    Preview Site
                </button>
                <button onclick="openAddContentModal()" class="btn btn-primary btn-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Add Content
                </button>
            </div>
        </div>

        @php
            $typeConfig = [
                'carousel' => ['label' => 'Carousel Slides', 'columns' => ['image_url' => 'Image', 'title' => 'Title', 'description' => 'Description']],
                'event' => ['label' => 'Recent Events', 'columns' => ['image_url' => 'Image', 'title' => 'Title', 'description' => 'Description', 'event_date' => 'Date']],
                'mission' => ['label' => 'Mission', 'columns' => ['title' => 'Title', 'description' => 'Description']],
                'vision' => ['label' => 'Vision', 'columns' => ['title' => 'Title', 'description' => 'Description']],
                'goal' => ['label' => 'Goals for Students', 'columns' => ['title' => 'Title', 'description' => 'Description']],
                'program' => ['label' => 'Programs Offered', 'columns' => ['image_url' => 'Image', 'title' => 'Title', 'description' => 'Description']],
                'location' => ['label' => 'Location', 'columns' => ['embedded_url' => 'Embedded URL', 'location' => 'Location']],
                'email' => ['label' => 'Email Addresses', 'columns' => ['email' => 'Email']],
                'contact' => ['label' => 'Contact Numbers', 'columns' => ['contact' => 'Contact']],
                'social_link' => ['label' => 'Social Media Links', 'columns' => ['social_link' => 'Social Link']],
                'office_hour' => ['label' => 'Office Hours', 'columns' => ['days' => 'Days', 'start_time' => 'Start', 'end_time' => 'End', 'is_open' => 'Open']],
            ];
        @endphp

        <!-- Content Tables Grouped by Type -->
        <div class="space-y-6">
            @foreach($typeConfig as $type => $config)
                @php $items = $websites->where('type', $type); @endphp
                <div class="card bg-base-100 shadow-sm">
                    <div class="card-body p-4">
                        <div class="flex items-center justify-between mb-3">
                            <h2 class="card-title text-base">
                                {{ $config['label'] }}
                            </h2>
                            <button onclick="openAddContentModal('{{ $type }}')" class="btn btn-ghost btn-xs">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                Add
                            </button>
                        </div>

                        @if($items->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            @foreach($config['columns'] as $col => $label)
                                                <th>{{ $label }}</th>
                                            @endforeach
                                            <th class="text-right">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($items as $index => $item)
                                            <tr class="hover">
                                                <td>{{ $loop->iteration }}</td>
                                                @foreach($config['columns'] as $col => $label)
                                                    <td>
                                                        @if($col === 'image_url')
                                                            @if($item->image_url)
                                                                <div class="avatar">
                                                                    <div class="w-12 h-8 rounded">
                                                                        <img src="{{ asset($item->image_url) }}" alt="thumb">
                                                                    </div>
                                                                </div>
                                                            @else
                                                                <span class="text-gray-400 text-xs">No image</span>
                                                            @endif
                                                        @elseif($col === 'description')
                                                            <span class="line-clamp-2 max-w-xs text-xs">{{ $item->description }}</span>
                                                        @elseif($col === 'event_date')
                                                            {{ $item->event_date ? $item->event_date->format('M d, Y') : '-' }}
                                                        @elseif($col === 'is_open')
                                                            @if($item->is_open)
                                                                <span class="badge badge-success badge-xs">Open</span>
                                                            @else
                                                                <span class="badge badge-error badge-xs">Closed</span>
                                                            @endif
                                                        @elseif($col === 'start_time' || $col === 'end_time')
                                                            {{ $item->$col ? \Carbon\Carbon::parse($item->$col)->format('h:i A') : '-' }}
                                                        @else
                                                            <span class="max-w-xs truncate block">{{ $item->$col ?? '-' }}</span>
                                                        @endif
                                                    </td>
                                                @endforeach
                                                <td class="text-right">
                                                    <div class="flex justify-end gap-1">
                                                        <button
                                                            onclick='openEditContentModal(@json($item))'
                                                            class="btn btn-ghost btn-xs">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                        </button>
                                                        <button
                                                            onclick="confirmWebsiteDelete({{ $item->id }}, '{{ addslashes($item->title ?? $item->days ?? 'this item') }}')"
                                                            class="btn btn-ghost btn-xs text-red-500">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-6 text-gray-400 text-sm">
                                No {{ strtolower($config['label']) }} content yet.
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Partials -->
    @include('partials.website-notification')
    @include('partials.website-form-modal')
    @include('partials.website-delete-modal')
    @include('partials.website-preview-modal')

</x-admin_sidebar>
