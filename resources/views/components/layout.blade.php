<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/x-icon">
    <title>{{ env('APP_NAME') }}</title>
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">
    <nav class="flex items-center justify-center navbar w-full bg-base-300 shadow">
        <div class="text-center space-y-2 m-3">
            <h2 class="text-4xl font-extrabold text-slate-900 tracking-tight">
                WELCOME TO <span class="text-indigo-600">{{ strtoupper(env('APP_NAME')) }}</span>
            </h2>
        </div>
    </nav>
    <div class="flex grow">
        {{ $slot }}
    </div>
</body>
</html>