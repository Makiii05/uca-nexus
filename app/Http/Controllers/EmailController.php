<?php

namespace App\Http\Controllers;

use App\Mail\ApplicationSuccessful;
use App\Models\Applicant;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class EmailController extends Controller
{
    /**
     * Send the application confirmation email to the applicant.
     */
    public static function sendApplicationConfirmation(Applicant $applicant, bool $isNew): void
    {
        try {
            Mail::to($applicant->email)->send(
                new ApplicationSuccessful($applicant, $isNew)
            );
        } catch (\Exception $e) {
            Log::error('Failed to send application confirmation email', [
                'application_no' => $applicant->application_no,
                'email'          => $applicant->email,
                'error'          => $e->getMessage(),
            ]);
        }
    }
}
