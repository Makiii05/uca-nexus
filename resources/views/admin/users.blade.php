<x-admin_sidebar>

    @include('partials.notifications')

    <div class="flex items-center gap-4 mb-4">
        <h2 class="font-bold text-4xl flex-1">Users</h2>
        <button class="btn btn-neutral" onclick="document.getElementById('add_user_modal').showModal()">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Add User
        </button>
    </div>

    <!--TABLE-->
    <div data-table-wrapper>
    <div class="overflow-x-auto bg-white shadow">
        <table class="table" data-sortable-table>
            <!-- head -->
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Office</th>
                    <th>Role</th>
                    <th>Created At</th>
                    <th data-no-sort>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->department_id ? ($user->department->code ?? 'Department') : ucfirst($user->type) }}</td>
                    <td>{{ ucfirst($user->role) }}</td>
                    <td>{{ $user->created_at->format('M d, Y') }}</td>
                    <td>
                        <button 
                            type="button" 
                            class="btn btn-sm btn-ghost text-primary"
                            onclick="openEditModal({{ json_encode($user) }})"
                        >
                            Edit
                        </button>
                        @if(auth()->id() !== $user->id)
                        <button 
                            type="button" 
                            class="btn btn-sm btn-ghost text-red-600"
                            onclick="confirmDelete({{ $user->id }}, '{{ $user->name }}')"
                        >
                            Delete
                        </button>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    </div>

    @include('partials.table-sort-search')

    <!-- Add User Modal -->
    <dialog id="add_user_modal" class="modal">
        <div class="modal-box">
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
            </form>
            <h3 class="font-bold text-xl mb-4">Add New User</h3>
            <form action="{{ route('admin.users.create') }}" method="POST">
                @csrf
                <div class="form-control mb-4">
                    <label class="label">
                        <span class="label-text">Name</span>
                    </label>
                    <input type="text" name="name" class="input input-bordered w-full" required />
                </div>
                <div class="form-control mb-4">
                    <label class="label">
                        <span class="label-text">Email</span>
                    </label>
                    <input type="email" name="email" class="input input-bordered w-full" required />
                </div>
                <div class="form-control mb-4">
                    <label class="label">
                        <span class="label-text">Office</span>
                    </label>
                    <select name="type" id="add_type" class="select select-bordered w-full" required onchange="toggleDepartmentSelect('add')">
                        <option value="">Select Office</option>
                        <option value="admin">Admin</option>
                        <option value="registrar">Registrar</option>
                        <option value="admission">Admission</option>
                        <option value="accounting">Accounting</option>
                        <option value="department">Department</option>
                    </select>
                </div>
                <div class="form-control mb-4" id="add_department_group" style="display:none;">
                    <label class="label">
                        <span class="label-text">Department</span>
                    </label>
                    <select name="department_id" id="add_department_id" class="select select-bordered w-full">
                        <option value="">Select Department</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}">{{ $department->code }} - {{ $department->description }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-control mb-4">
                    <label class="label">
                        <span class="label-text">Role</span>
                    </label>
                    <select name="role" class="select select-bordered w-full" required>
                        <option value="">Select Role</option>
                        <option value="head">Head</option>
                        <option value="proctor">Proctor</option>
                        <option value="guidance">Guidance</option>
                        <option value="principal">Principal</option>
                    </select>
                </div>
                <div class="form-control mb-4">
                    <label class="label">
                        <span class="label-text">Password</span>
                    </label>
                    <input type="password" name="password" class="input input-bordered w-full" required minlength="8" />
                </div>
                <div class="form-control mb-4">
                    <label class="label">
                        <span class="label-text">Confirm Password</span>
                    </label>
                    <input type="password" name="password_confirmation" class="input input-bordered w-full" required minlength="8" />
                </div>
                <div class="modal-action">
                    <button type="submit" class="btn btn-neutral">Create User</button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>

    <!-- Edit User Modal -->
    <dialog id="edit_user_modal" class="modal">
        <div class="modal-box">
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
            </form>
            <h3 class="font-bold text-xl mb-4">Edit User</h3>
            <form id="editUserForm" method="POST">
                @csrf
                <div class="form-control mb-4">
                    <label class="label">
                        <span class="label-text">Name</span>
                    </label>
                    <input type="text" name="name" id="edit_name" class="input input-bordered w-full" required />
                </div>
                <div class="form-control mb-4">
                    <label class="label">
                        <span class="label-text">Email</span>
                    </label>
                    <input type="email" name="email" id="edit_email" class="input input-bordered w-full" required />
                </div>
                <div class="form-control mb-4">
                    <label class="label">
                        <span class="label-text">Type</span>
                    </label>
                    <select name="type" id="edit_type" class="select select-bordered w-full" required onchange="toggleDepartmentSelect('edit')">
                        <option value="">Select Type</option>
                        <option value="admin">Admin</option>
                        <option value="registrar">Registrar</option>
                        <option value="admission">Admission</option>
                        <option value="accounting">Accounting</option>
                        <option value="department">Department</option>
                    </select>
                </div>
                <div class="form-control mb-4" id="edit_department_group" style="display:none;">
                    <label class="label">
                        <span class="label-text">Department</span>
                    </label>
                    <select name="department_id" id="edit_department_id" class="select select-bordered w-full">
                        <option value="">Select Department</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}">{{ $department->code }} - {{ $department->description }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-control mb-4">
                    <label class="label">
                        <span class="label-text">Role</span>
                    </label>
                    <select name="role" id="edit_role" class="select select-bordered w-full" required>
                        <option value="">Select Role</option>
                        <option value="head">Head</option>
                        <option value="proctor">Proctor</option>
                        <option value="guidance">Guidance</option>
                        <option value="principal">Principal</option>
                    </select>
                </div>
                <div class="form-control mb-4">
                    <label class="label">
                        <span class="label-text">New Password (leave blank to keep current)</span>
                    </label>
                    <input type="password" name="password" class="input input-bordered w-full" minlength="8" />
                </div>
                <div class="form-control mb-4">
                    <label class="label">
                        <span class="label-text">Confirm New Password</span>
                    </label>
                    <input type="password" name="password_confirmation" class="input input-bordered w-full" minlength="8" />
                </div>
                <div class="modal-action">
                    <button type="submit" class="btn btn-neutral">Update User</button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>

    <!-- Delete Confirmation Modal -->
    <dialog id="delete_user_modal" class="modal">
        <div class="modal-box">
            <h3 class="font-bold text-xl mb-4">Confirm Delete</h3>
            <p>Are you sure you want to delete user <strong id="delete_user_name"></strong>?</p>
            <p class="text-sm text-gray-500 mt-2">This action cannot be undone.</p>
            <form id="deleteUserForm" method="POST">
                @csrf
                <div class="modal-action">
                    <button type="button" class="btn btn-ghost" onclick="document.getElementById('delete_user_modal').close()">Cancel</button>
                    <button type="submit" class="btn btn-error">Delete</button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>

    <script>
        function toggleDepartmentSelect(prefix) {
            const typeSelect = document.getElementById(prefix + '_type');
            const deptGroup = document.getElementById(prefix + '_department_group');
            const deptSelect = document.getElementById(prefix + '_department_id');

            if (typeSelect.value === 'department') {
                deptGroup.style.display = '';
                deptSelect.required = true;
            } else {
                deptGroup.style.display = 'none';
                deptSelect.required = false;
                deptSelect.value = '';
            }
        }

        function openEditModal(user) {
            document.getElementById('edit_name').value = user.name;
            document.getElementById('edit_email').value = user.email;
            document.getElementById('edit_role').value = user.role;
            document.getElementById('editUserForm').action = `/admin/users/${user.id}/update`;

            // Set type: if user has department_id, they are a department user
            if (user.department_id) {
                document.getElementById('edit_type').value = 'department';
                document.getElementById('edit_department_id').value = user.department_id;
                document.getElementById('edit_department_group').style.display = '';
                document.getElementById('edit_department_id').required = true;
            } else {
                document.getElementById('edit_type').value = user.type;
                document.getElementById('edit_department_group').style.display = 'none';
                document.getElementById('edit_department_id').required = false;
                document.getElementById('edit_department_id').value = '';
            }

            document.getElementById('edit_user_modal').showModal();
        }

        function confirmDelete(userId, userName) {
            document.getElementById('delete_user_name').textContent = userName;
            document.getElementById('deleteUserForm').action = `/admin/users/${userId}/delete`;
            document.getElementById('delete_user_modal').showModal();
        }
    </script>

</x-admin_sidebar>
