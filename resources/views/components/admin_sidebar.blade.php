<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/x-icon">
    <title>{{ env('APP_NAME') }} - Admin</title>
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
          <div class="px-4">{{ env('APP_NAME') }} - Admin Panel</div>
        </nav>
        <!-- Page content here -->
        <main class="p-4">
            {{ $slot }}
        </main>
      </div>
    
      <div class="drawer-side is-drawer-close:overflow-visible">
        <label for="my-drawer-4" aria-label="close sidebar" class="drawer-overlay"></label>
        <div class="flex h-screen flex-col items-start bg-white shadow is-drawer-close:w-14 is-drawer-open:w-64 overflow-hidden">
          <!-- Sidebar content here -->
          <ul class="menu w-full grow overflow-y-auto min-h-0">
            <!-- Dashboard -->
            <li>
              <a href="{{ route('admin.dashboard') }}" class="is-drawer-close:tooltip my-2 is-drawer-close:tooltip-right {{ request()->routeIs('admin.dashboard') ? 'bg-black text-white' : '' }}" data-tip="Dashboard">
                <!-- Home icon -->
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                </svg>
                <span class="is-drawer-close:hidden">Dashboard</span>
              </a>
            </li>
            <!-- Users -->
            <li>
              <a href="{{ route('admin.users') }}" class="is-drawer-close:tooltip my-2 is-drawer-close:tooltip-right {{ request()->routeIs('admin.users*') ? 'bg-black text-white' : '' }}" data-tip="Users">
                <!-- Users icon -->
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                </svg>
                <span class="is-drawer-close:hidden">Users</span>
              </a>
            </li>
            <!-- Website -->
            <li>
              <a href="{{ route('admin.website') }}" class="is-drawer-close:tooltip my-2 is-drawer-close:tooltip-right {{ request()->routeIs('admin.website*') ? 'bg-black text-white' : '' }}" data-tip="Website">
                <!-- Users icon -->
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 0 0 8.716-6.747M12 21a9.004 9.004 0 0 1-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 0 1 7.843 4.582M12 3a8.997 8.997 0 0 0-7.843 4.582m15.686 0A11.953 11.953 0 0 1 12 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0 1 21 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0 1 12 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 0 1 3 12c0-1.605.42-3.113 1.157-4.418" />
                </svg>

                <span class="is-drawer-close:hidden">Website</span>
              </a>
            </li>
          </ul>
          <div class="divider my-1 is-drawer-close:hidden"></div>
          <div class="flex items-center justify-between gap-2 px-2 py-2">
            <div class="text-sm font-semibold text-gray-700 truncate is-drawer-close:hidden flex-1 w-full">{{ auth()->user()->name }}</div>
            <form action="{{ route('admin.logout') }}" method="POST" class="w-full is-drawer-open:w-auto">
              @csrf
              <button type="submit" class="btn btn-ghost btn-sm is-drawer-close:btn-square is-drawer-close:justify-center is-drawer-close:tooltip is-drawer-close:tooltip-right w-full is-drawer-open:w-auto" data-tip="Logout">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                </svg>
                
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        const drawerToggle = document.getElementById('my-drawer-4');
        if (window.innerWidth >= 1024) {
          drawerToggle.checked = true;
        }
      });
    </script>
</body>
</html>
