<x-teacher_portal_sidebar>

    @include('partials.notifications')

    <div class="m-4 font-bold text-4xl">
        <h2>Change Password</h2>
    </div>

    <div class="m-4">
        <div class="card bg-white shadow-lg max-w-md mx-auto">
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('teacher_portal.change_password.submit') }}" class="space-y-4">
                    @csrf
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Current Password</span>
                        </label>
                        <input name="current_password" type="password" placeholder="Enter current password" class="input input-bordered w-full" required/>
                        @error('current_password')
                            <label class="label">
                                <span class="label-text-alt text-red-500">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">New Password</span>
                        </label>
                        <input name="new_password" type="password" placeholder="Enter new password (min. 6 characters)" class="input input-bordered w-full" required/>
                        @error('new_password')
                            <label class="label">
                                <span class="label-text-alt text-red-500">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Confirm New Password</span>
                        </label>
                        <input name="new_password_confirmation" type="password" placeholder="Confirm new password" class="input input-bordered w-full" required/>
                    </div>
                    <div class="form-control mt-6">
                        <button type="submit" class="btn btn-primary">
                            Change Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</x-teacher_portal_sidebar>
