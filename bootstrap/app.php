<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Register custom middleware aliases
        $middleware->alias([
            'student.portal.auth' => \App\Http\Middleware\StudentPortalAuth::class,
            'teacher.portal.auth' => \App\Http\Middleware\TeacherPortalAuth::class,
        ]);

        $middleware->redirectGuestsTo(function (Request $request) {
            // Check if the request is for accounting routes
            if ($request->is('accounting/*')) {
                return route('accounting.login');
            }
            // Check if the request is for registrar routes
            if ($request->is('registrar/*')) {
                return route('registrar.login');
            }
            // Check if the request is for admission routes
            if ($request->is('admission/*')) {
                return route('admission.login');
            }
            // Check if the request is for department routes
            if ($request->is('department/*')) {
                return route('department.login');
            }
            // Check if the request is for admin routes
            if ($request->is('admin/*')) {
                return route('admin.login');
            }
            // Check if the request is for student portal routes
            if ($request->is('student-portal/*')) {
                return route('student_portal.login');
            }
            // Check if the request is for teacher portal routes
            if ($request->is('teacher-portal/*')) {
                return route('teacher_portal.login');
            }
            // Default redirect
            return route('index');
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Handle 419 Page Expired (CSRF token mismatch)
        $exceptions->render(function (\Illuminate\Session\TokenMismatchException $e, Request $request) {
            $routeMap = [
                'accounting/*' => 'accounting.login',
                'registrar/*' => 'registrar.login',
                'admission/*' => 'admission.login',
                'department/*' => 'department.login',
                'admin/*' => 'admin.login',
                'student-portal/*' => 'student_portal.login',
                'teacher-portal/*' => 'teacher_portal.login',
            ];

            $resolveLoginRoute = function (?string $path) use ($routeMap): ?string {
                if (!$path) {
                    return null;
                }

                $normalizedPath = ltrim($path, '/');

                foreach ($routeMap as $pattern => $routeName) {
                    if (Str::is($pattern, $normalizedPath)) {
                        return $routeName;
                    }
                }

                return null;
            };

            $routeName = $resolveLoginRoute($request->path());

            if (!$routeName) {
                $refererPath = parse_url((string) $request->headers->get('referer'), PHP_URL_PATH);
                $routeName = $resolveLoginRoute(is_string($refererPath) ? $refererPath : null);
            }

            if ($routeName) {
                return redirect()->route($routeName)
                    ->withErrors(['session' => 'Your session has expired. Please log in again.']);
            }

            return redirect()->route('index')
                ->withErrors(['session' => 'Your session has expired. Please try again.']);
        });
    })->create();
