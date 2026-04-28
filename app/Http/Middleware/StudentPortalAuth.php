<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StudentPortalAuth
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $studentId = session('student_portal_student_id');
        
        if (!$studentId) {
            return redirect()->route('student_portal.login');
        }

        // Optionally verify the student still exists and account is active
        $account = \App\Models\StudentAccount::where('student_id', $studentId)->first();
        
        if (!$account || !$account->isActive()) {
            session()->forget('student_portal_student_id');
            return redirect()->route('student_portal.login');
        }

        return $next($request);
    }
}
