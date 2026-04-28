<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Status;

class EnrollmentStatusController extends Controller
{
    /**
     * Get the current enrollment status.
     */
    public function status()
    {
        return response()->json([
            'status' => Status::getEnrollmentStatus(),
            'is_open' => Status::isEnrollmentOpen(),
        ]);
    }

    /**
     * Toggle the enrollment status.
     */
    public function toggle()
    {
        $newStatus = Status::toggleEnrollmentStatus();

        return response()->json([
            'status' => $newStatus,
            'is_open' => $newStatus === 'open',
            'message' => 'Enrollment status updated to ' . $newStatus,
        ]);
    }
}
