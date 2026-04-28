<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/x-icon">
    <title>Student Portal Login</title>
    @vite('resources/css/app.css')
</head>

<body class="min-h-screen bg-base-200 flex items-center justify-center">
    <div class="w-full max-w-sm">
        <div class="card bg-base-100 shadow-xl">
            <div class="card-body">
                <div class="flex flex-col items-center mb-4">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-16 h-16 mb-2">
                    <h1 class="text-lg font-bold text-gray-700">{{ env('APP_NAME') }}</h1>
                </div>
                <h2 class="card-title justify-center text-2xl font-bold">
                    Student Portal
                </h2>

                @if(isset($portalDisabled) && $portalDisabled)
                    <div class="alert alert-warning mt-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <span>Student portal is currently disabled. Please contact the registrar's office.</span>
                    </div>
                @else
                    @if($errors->has('session'))
                        <div class="alert alert-info mt-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>{{ $errors->first('session') }}</span>
                        </div>
                    @endif
                    <form class="space-y-4" method="POST" action="{{ route('student_portal.login.submit') }}">
                        @csrf
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Student Number</span>
                            </label>
                            <input autofocus name="student_number" type="text" placeholder="e.g. 2024-00001" class="input input-bordered w-full" required value="{{ old('student_number') }}"/>
                        </div>
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Password</span>
                            </label>
                            <input name="password" type="password" placeholder="••••••••" class="input input-bordered w-full" required/>
                        </div>
                        @if ($errors->any())
                            <ul class="px-4 py-2 bg-red-100">
                                @foreach ($errors->all() as $error)
                                    <li class="my-2 text-red-500">{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif
                        <div class="form-control mt-6">
                            <button class="btn btn-primary w-full">
                                Login
                            </button>
                        </div>
                    </form>
                @endif

                <div class="text-center text-sm text-gray-500 mt-4">
                    <a href="{{ route('index') }}" class="link link-hover">Back to Home</a>
                </div>
                <div class="text-center text-sm text-gray-500 mt-2">
                    © {{ date('Y') }} Enrollment System
                </div>
            </div>
        </div>
    </div>
</body>
</html>
