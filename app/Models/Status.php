<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    protected $fillable = [
        'enrollment_status',
        'student_portal_status',
        'visible_grade',
        'submission_of_grade',
    ];

    /**
     * Get the current enrollment status.
     */
    public static function getEnrollmentStatus(): string
    {
        return static::first()?->enrollment_status ?? 'close';
    }

    /**
     * Check if enrollment is open.
     */
    public static function isEnrollmentOpen(): bool
    {
        return static::getEnrollmentStatus() === 'open';
    }

    /**
     * Toggle enrollment status.
     */
    public static function toggleEnrollmentStatus(): string
    {
        $status = static::first();
        if (!$status) {
            $status = static::create(['enrollment_status' => 'open']);
            return 'open';
        }

        $newStatus = $status->enrollment_status === 'open' ? 'close' : 'open';
        $status->update(['enrollment_status' => $newStatus]);
        return $newStatus;
    }

    /**
     * Get the current student portal status.
     */
    public static function getStudentPortalStatus(): string
    {
        return static::first()?->student_portal_status ?? 'off';
    }

    /**
     * Check if student portal is on.
     */
    public static function isStudentPortalOn(): bool
    {
        return static::getStudentPortalStatus() === 'on';
    }

    /**
     * Toggle student portal status.
     */
    public static function toggleStudentPortalStatus(): string
    {
        $status = static::first();
        if (!$status) {
            $status = static::create(['student_portal_status' => 'on']);
            return 'on';
        }

        $newStatus = $status->student_portal_status === 'on' ? 'off' : 'on';
        $status->update(['student_portal_status' => $newStatus]);
        return $newStatus;
    }
}
