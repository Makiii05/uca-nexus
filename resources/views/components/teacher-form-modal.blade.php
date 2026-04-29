<dialog id="form_modal" class="modal">
    <div class="modal-box w-11/12 max-w-5xl">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </form>

        <h3 id="teacher-modal-title" class="text-lg font-bold mb-4">Add Teacher</h3>

        <form id="teacher-form" action="{{ route('registrar.teacher.create') }}" method="POST" class="space-y-4 space-x-4 grid grid-cols-2">
            @csrf

            <div class="form-control">
                <label class="label">
                    <span class="label-text">Code</span>
                </label>
                <input id="teacher_code" type="text" name="code" class="input input-bordered w-full" placeholder="Enter teacher code" required>
            </div>

            <div class="form-control">
                <label class="label">
                    <span class="label-text">Email</span>
                </label>
                <input id="teacher_email" type="email" name="email" class="input input-bordered w-full" placeholder="Enter teacher email" required>
            </div>

            <div class="form-control">
                <label class="label">
                    <span class="label-text">First Name</span>
                </label>
                <input id="teacher_first_name" type="text" name="first_name" class="input input-bordered w-full" placeholder="Enter first name" required>
            </div>

            <div class="form-control">
                <label class="label">
                    <span class="label-text">Middle Name</span>
                </label>
                <input id="teacher_middle_name" type="text" name="middle_name" class="input input-bordered w-full" placeholder="Enter middle name (optional)">
            </div>

            <div class="form-control">
                <label class="label">
                    <span class="label-text">Last Name</span>
                </label>
                <input id="teacher_last_name" type="text" name="last_name" class="input input-bordered w-full" placeholder="Enter last name" required>
            </div>

            <div class="form-control">
                <label class="label">
                    <span class="label-text">Status</span>
                </label>
                <select id="teacher_status" name="status" class="select select-bordered w-full" required>
                    <option value="active" selected>Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>

            <div class="modal-action col-span-2">
                <button type="button" class="btn" onclick="form_modal.close()">Cancel</button>
                <button id="teacher-modal-submit" type="submit" class="btn btn-primary">Save Teacher</button>
            </div>
        </form>
    </div>
</dialog>
