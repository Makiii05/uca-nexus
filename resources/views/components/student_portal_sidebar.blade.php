<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/x-icon">
    <title>{{ env('APP_NAME') }} - Student Portal</title>
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
          <div class="px-4">{{ env('APP_NAME') }} - Student Portal</div>
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
              <a href="{{ route('student_portal.dashboard') }}" class="is-drawer-close:tooltip my-2 is-drawer-close:tooltip-right {{ request()->routeIs('student_portal.dashboard') ? 'active' : '' }}" data-tip="Dashboard">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                </svg>
                <span class="is-drawer-close:hidden">Dashboard</span>
              </a>
            </li>
            <!-- Ledger -->
            <li>
              <a href="{{ route('student_portal.ledger') }}" class="is-drawer-close:tooltip my-2 is-drawer-close:tooltip-right {{ request()->routeIs('student_portal.ledger') ? 'active' : '' }}" data-tip="Ledger">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                </svg>
                <span class="is-drawer-close:hidden">Ledger</span>
              </a>
            </li>
            <!-- Examination Permit -->
            <li>
              <a href="{{ route('student_portal.examination_permit') }}" class="is-drawer-close:tooltip my-2 is-drawer-close:tooltip-right {{ request()->routeIs('student_portal.examination_permit') ? 'active' : '' }}" data-tip="Examination Permit">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
                </svg>
                <span class="is-drawer-close:hidden">Examination Permit</span>
              </a>
            </li>
            <!-- Change Password -->
            <li>
              <a href="{{ route('student_portal.change_password') }}" class="is-drawer-close:tooltip my-2 is-drawer-close:tooltip-right {{ request()->routeIs('student_portal.change_password') ? 'active' : '' }}" data-tip="Change Password">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                </svg>
                <span class="is-drawer-close:hidden">Change Password</span>
              </a>
            </li>
          </ul>
          <!-- Logout button -->
          <div class="w-full p-2">
            <form action="{{ route('student_portal.logout') }}" method="POST">
              @csrf
              <button type="submit" class="btn btn-ghost w-full justify-start is-drawer-close:justify-center is-drawer-close:tooltip my-2 is-drawer-close:tooltip-right">
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
</body>
</html>
