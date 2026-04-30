<?php

namespace App\Http\Middleware;

use App\Models\Teacher;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TeacherPortalAuth
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $teacherId = session('teacher_portal_teacher_id');

        if (!$teacherId) {
            return redirect()->route('teacher_portal.login');
        }

        $teacher = Teacher::with('account')->find($teacherId);

        if (!$teacher) {
            session()->forget('teacher_portal_teacher_id');
            return redirect()->route('teacher_portal.login');
        }

        if ($teacher->status !== 'active') {
            session()->forget('teacher_portal_teacher_id');
            return redirect()->route('teacher_portal.login')
                ->withErrors(['code' => 'Your account is inactive. Please contact the registrar.']);
        }

        if (!$teacher->account || $teacher->account->status !== 'on') {
            session()->forget('teacher_portal_teacher_id');
            return redirect()->route('teacher_portal.login')
                ->withErrors(['code' => 'Your teacher portal account is closed. Please contact the registrar.']);
        }

        return $next($request);
    }
}
