<!-- Add/Edit Website Content Modal -->
<dialog id="websiteFormModal" class="modal">
    <div class="modal-box w-11/12 max-w-2xl">
        <h3 class="font-bold text-lg mb-4" id="websiteFormTitle">Add Content</h3>

        <form id="websiteForm" method="POST" action="{{ route('admin.website.store') }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="_method" id="websiteFormMethod" value="POST">
            <input type="hidden" name="id" id="websiteFormId" value="">
            <input type="hidden" name="type" id="websiteTypeHidden" value="">

            <!-- Type Select (always visible) -->
            <div class="form-control mb-4">
                <label class="label"><span class="label-text font-semibold">Content Type</span></label>
                <select name="type" id="websiteType" class="select select-bordered w-full" required onchange="toggleWebsiteFields()">
                    <option value="" disabled selected>Select a type</option>
                    <option value="carousel">Carousel</option>
                    <option value="event">Recent Event</option>
                    <option value="mission">Mission</option>
                    <option value="vision">Vision</option>
                    <option value="goal">Goal</option>
                    <option value="program">Programs</option>
                    <option value="location">Location</option>
                    <option value="email">Email</option>
                    <option value="contact">Contact</option>
                    <option value="social_link">Social Media Link</option>
                    <option value="office_hour">Office Hour</option>
                </select>
            </div>

            <!-- Dynamic Fields Container -->
            <div id="websiteDynamicFields">
                <!-- Image Field -->
                <div class="form-control mb-4 website-field" data-types="carousel,event,program" style="display:none;">
                    <label class="label"><span class="label-text font-semibold">Image</span></label>
                    <input type="file" name="image" id="websiteImage" class="file-input file-input-bordered w-full" accept="image/*">
                    <div id="websiteImagePreview" class="mt-2 hidden">
                        <img id="websiteImagePreviewImg" src="" alt="Current image" class="w-32 h-20 object-cover rounded">
                        <p class="text-xs text-gray-400 mt-1">Current image (leave empty to keep)</p>
                    </div>
                </div>

                <!-- Title Field -->
                <div class="form-control mb-4 website-field" data-types="carousel,event,mission,vision,goal,program" style="display:none;">
                    <label class="label"><span class="label-text font-semibold">Title</span></label>
                    <input type="text" name="title" id="websiteTitle" class="input input-bordered w-full" placeholder="Enter title">
                </div>

                <!-- Description Field -->
                <div class="form-control mb-4 website-field" data-types="carousel,event,mission,vision,goal,program" style="display:none;">
                    <label class="label"><span class="label-text font-semibold">Description</span></label>
                    <textarea name="description" id="websiteDescription" class="textarea textarea-bordered w-full" rows="3" placeholder="Enter description"></textarea>
                </div>

                <!-- Event Date Field -->
                <div class="form-control mb-4 website-field" data-types="event" style="display:none;">
                    <label class="label"><span class="label-text font-semibold">Event Date</span></label>
                    <input type="date" name="event_date" id="websiteEventDate" class="input input-bordered w-full">
                </div>

                <!-- Embedded URL Field -->
                <div class="form-control mb-4 website-field" data-types="location" style="display:none;">
                    <label class="label"><span class="label-text font-semibold">Embedded URL</span></label>
                    <textarea name="embedded_url" id="websiteEmbeddedUrl" class="textarea textarea-bordered w-full" rows="3" placeholder="Paste Google Maps embed URL here"></textarea>
                </div>

                <!-- Location Field -->
                <div class="form-control mb-4 website-field" data-types="location" style="display:none;">
                    <label class="label"><span class="label-text font-semibold">Location Address</span></label>
                    <input type="text" name="location" id="websiteLocation" class="input input-bordered w-full" placeholder="e.g. 75 L. Sumulong Memorial Circle, Antipolo">
                </div>

                <!-- Email Field -->
                <div class="form-control mb-4 website-field" data-types="email" style="display:none;">
                    <label class="label"><span class="label-text font-semibold">Email</span></label>
                    <input type="email" name="email" id="websiteEmail" class="input input-bordered w-full" placeholder="e.g. info@school.edu">
                </div>

                <!-- Contact Field -->
                <div class="form-control mb-4 website-field" data-types="contact" style="display:none;">
                    <label class="label"><span class="label-text font-semibold">Contact Number</span></label>
                    <input type="text" name="contact" id="websiteContact" class="input input-bordered w-full" placeholder="e.g. 8697-0174">
                </div>

                <!-- Social Link Field -->
                <div class="form-control mb-4 website-field" data-types="social_link" style="display:none;">
                    <label class="label"><span class="label-text font-semibold">Social Media Link</span></label>
                    <input type="url" name="social_link" id="websiteSocialLink" class="input input-bordered w-full" placeholder="e.g. https://facebook.com/page">
                </div>

                <!-- Office Hour Fields -->
                <div class="form-control mb-4 website-field" data-types="office_hour" style="display:none;">
                    <label class="label"><span class="label-text font-semibold">Day(s)</span></label>
                    <input type="text" name="days" id="websiteDays" class="input input-bordered w-full" placeholder="e.g. Monday - Friday">
                </div>

                <div class="form-control mb-4 website-field" data-types="office_hour" style="display:none;">
                    <label class="label"><span class="label-text font-semibold">Is Open</span></label>
                    <input type="hidden" name="is_open" value="0">
                    <label class="label cursor-pointer justify-start gap-3">
                        <input type="checkbox" name="is_open" id="websiteIsOpen" value="1" class="checkbox checkbox-primary" onchange="toggleTimeFields()" checked>
                        <span class="label-text">Open on this day</span>
                    </label>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="form-control mb-4 website-field" data-types="office_hour" style="display:none;">
                        <label class="label"><span class="label-text font-semibold">Start Time</span></label>
                        <input type="time" name="start_time" id="websiteStartTime" class="input input-bordered w-full">
                    </div>

                    <div class="form-control mb-4 website-field" data-types="office_hour" style="display:none;">
                        <label class="label"><span class="label-text font-semibold">End Time</span></label>
                        <input type="time" name="end_time" id="websiteEndTime" class="input input-bordered w-full">
                    </div>
                </div>
            </div>

            <div class="modal-action">
                <button type="button" class="btn" onclick="closeWebsiteFormModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>

<script>
    function toggleWebsiteFields() {
        const type = document.getElementById('websiteType').value;
        document.getElementById('websiteTypeHidden').value = type;
        document.querySelectorAll('.website-field').forEach(field => {
            const types = field.dataset.types.split(',');
            field.style.display = types.includes(type) ? 'block' : 'none';
        });
        if (type === 'office_hour') {
            toggleTimeFields();
        }
    }

    function toggleTimeFields() {
        const isOpen = document.getElementById('websiteIsOpen').checked;
        document.getElementById('websiteStartTime').disabled = !isOpen;
        document.getElementById('websiteEndTime').disabled = !isOpen;
    }

    function openAddContentModal(presetType = '') {
        const form = document.getElementById('websiteForm');
        form.reset();
        form.action = "{{ route('admin.website.store') }}";
        document.getElementById('websiteFormMethod').value = 'POST';
        document.getElementById('websiteFormId').value = '';
        document.getElementById('websiteFormTitle').textContent = 'Add Content';
        document.getElementById('websiteImagePreview').classList.add('hidden');

        if (presetType) {
            document.getElementById('websiteType').value = presetType;
            document.getElementById('websiteTypeHidden').value = presetType;
            document.getElementById('websiteType').disabled = true;
        } else {
            document.getElementById('websiteType').disabled = false;
        }

        toggleWebsiteFields();
        document.getElementById('websiteFormModal').showModal();
    }

    function openEditContentModal(item) {
        const form = document.getElementById('websiteForm');
        form.reset();
        form.action = "{{ url('admin/website') }}/" + item.id + "/update";
        document.getElementById('websiteFormMethod').value = 'POST';
        document.getElementById('websiteFormId').value = item.id;
        document.getElementById('websiteFormTitle').textContent = 'Edit Content';

        document.getElementById('websiteType').value = item.type;
        document.getElementById('websiteTypeHidden').value = item.type;
        document.getElementById('websiteType').disabled = true;

        // Show current image preview if exists
        const previewContainer = document.getElementById('websiteImagePreview');
        if (item.image_url) {
            document.getElementById('websiteImagePreviewImg').src = '/' + item.image_url;
            previewContainer.classList.remove('hidden');
        } else {
            previewContainer.classList.add('hidden');
        }

        // Fill fields
        if (item.title) document.getElementById('websiteTitle').value = item.title;
        if (item.description) document.getElementById('websiteDescription').value = item.description;
        if (item.event_date) document.getElementById('websiteEventDate').value = item.event_date;
        if (item.embedded_url) document.getElementById('websiteEmbeddedUrl').value = item.embedded_url;
        if (item.location) document.getElementById('websiteLocation').value = item.location;
        if (item.email) document.getElementById('websiteEmail').value = item.email;
        if (item.contact) document.getElementById('websiteContact').value = item.contact;
        if (item.social_link) document.getElementById('websiteSocialLink').value = item.social_link;
        if (item.days) document.getElementById('websiteDays').value = item.days;

        document.getElementById('websiteIsOpen').checked = item.is_open == 1;

        if (item.start_time) document.getElementById('websiteStartTime').value = item.start_time;
        if (item.end_time) document.getElementById('websiteEndTime').value = item.end_time;

        toggleWebsiteFields();

        if (item.type === 'office_hour') {
            toggleTimeFields();
        }

        document.getElementById('websiteFormModal').showModal();
    }

    function closeWebsiteFormModal() {
        document.getElementById('websiteType').disabled = false;
        document.getElementById('websiteFormModal').close();
    }
</script>
