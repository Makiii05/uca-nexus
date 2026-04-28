<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/x-icon">
    <title>{{ env('APP_NAME') }} - Department</title>
    @vite('resources/css/app.css')
</head>
<body>
    <div class="drawer lg:drawer-open">
      <input id="my-drawer-4" type="checkbox" class="drawer-toggle" />
      <div class="drawer-content bg-gray-100">
        <!-- Navbar -->
        <nav class="navbar w-full bg-base-300 shadow">
          <label for="my-drawer-4" aria-label="open sidebar" class="btn btn-square btn-ghost">
            <!-- Sidebar toggle icon -->
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-linejoin="round" stroke-linecap="round" stroke-width="2" fill="none" stroke="currentColor" class="my-1.5 inline-block size-4"><path d="M4 4m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z"></path><path d="M9 4v16"></path><path d="M14 10l2 2l-2 2"></path></svg>
          </label>
          <div class="px-4">{{ env('APP_NAME') }}</div>
          <div class="ms-auto mx-3">{{ auth()->user()->department->code ?? auth()->user()->type }}</div>
        </nav>
        <!-- Page content here -->
        <main class="p-4">
            {{ $slot }}
        </main>
      </div>
    
      <div class="drawer-side is-drawer-close:overflow-visible">
        <label for="my-drawer-4" aria-label="close sidebar" class="drawer-overlay"></label>
        <div class="flex min-h-full flex-col items-start bg-white shadow is-drawer-close:w-14 is-drawer-open:w-64">
          <!-- Sidebar content here -->
          <ul class="menu w-full grow">
            <!-- Dashboard -->
            <li>
              <a href="{{ route('department.dashboard') }}" class="is-drawer-close:tooltip my-2 is-drawer-close:tooltip-right" data-tip="Dashboard">
                <!-- Home icon -->
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                </svg>
                <span class="is-drawer-close:hidden">Dashboard</span>
              </a>
            </li>
            <!-- List item -->
            <li>
              <a href="{{ route('department.student') }}" class="is-drawer-close:tooltip my-2 is-drawer-close:tooltip-right" data-tip="Students">
                <!-- Academic Cap icon -->
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5" />
                </svg>
                <span class="is-drawer-close:hidden">Students</span>
              </a>
            </li>
            <li>
              <a href="{{ route('department.enlistment') }}" class="is-drawer-close:tooltip my-2 is-drawer-close:tooltip-right" data-tip="Enlistment"
                 onclick="event.preventDefault(); openAcademicTermModal('{{ route('department.enlistment') }}');">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
                </svg>
                <span class="is-drawer-close:hidden">Enlistment</span>
              </a>
            </li>
            <!-- List item -->
            <li>
              <a href="{{ route('department.subject_offering') }}" class="is-drawer-close:tooltip my-2 is-drawer-close:tooltip-right" data-tip="Subject Offering"
                 onclick="event.preventDefault(); openAcademicTermModal('{{ route('department.subject_offering') }}');">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                </svg>
                <span class="is-drawer-close:hidden">Subject Offering</span>
              </a>
            </li>
          </ul>
          <!-- Logout button -->
          <div class="w-full p-2">
            <form action="{{ route('department.logout') }}" method="POST">
              @csrf
              <button type="submit" class="btn btn-ghost w-full justify-start is-drawer-close:justify-center is-drawer-close:tooltip my-2 is-drawer-close:tooltip-right" data-tip="Logout">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                </svg>
                <span class="is-drawer-close:hidden">Logout</span>
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Academic Term Selection Modal -->
    <dialog id="academicTermModal" class="modal">
        <div class="modal-box">
            <h3 class="text-lg font-bold mb-4">Select Academic Term</h3>
            <div class="form-control">
                <label class="label"><span class="label-text">Academic Term *</span></label>
                <select id="academicTermSelect" class="select select-bordered w-full" required>
                    <option value="">-- Select Academic Term --</option>
                    @php
                        $academicTerms = \App\Models\AcademicTerm::where('status', 'active')
                            ->where('department_id', auth()->user()->department_id)
                            ->orderBy('created_at')
                            ->get();
                    @endphp
                    @foreach ($academicTerms as $term)
                        <option value="{{ $term->id }}">{{ $term->description }}</option>
                    @endforeach
                </select>
            </div>
            <div class="modal-action">
                <button type="button" class="btn btn-ghost" onclick="document.getElementById('academicTermModal').close();">Cancel</button>
                <button type="button" class="btn btn-primary" id="academicTermConfirmBtn" onclick="confirmAcademicTerm()">Confirm</button>
            </div>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>

    <script>
        let academicTermTargetUrl = '';

        function openAcademicTermModal(targetUrl) {
            academicTermTargetUrl = targetUrl;
            document.getElementById('academicTermSelect').value = '';
            document.getElementById('academicTermModal').showModal();
        }

        function confirmAcademicTerm() {
            const termId = document.getElementById('academicTermSelect').value;
            if (!termId) {
                alert('Please select an academic term.');
                return;
            }
            const separator = academicTermTargetUrl.includes('?') ? '&' : '?';
            window.location.href = academicTermTargetUrl + separator + 'academic_term_id=' + termId;
        }
    </script>
</body>
</html>
