<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Status;
use App\Models\StudentAccount;
use Illuminate\Support\Facades\Hash;

class StudentPortalStatusController extends Controller
{
    /**
     * Get the current student portal status.
     */
    public function status()
    {
        return response()->json([
            'status' => Status::getStudentPortalStatus(),
            'is_on' => Status::isStudentPortalOn(),
        ]);
    }

    /**
     * Toggle the student portal status.
     */
    public function toggle()
    {
        $newStatus = Status::toggleStudentPortalStatus();

        return response()->json([
            'status' => $newStatus,
            'is_on' => $newStatus === 'on',
            'message' => 'Student portal status updated to ' . $newStatus,
        ]);
    }

    /**
     * Deactivate all student accounts.
     */
    public function deactivateAllAccounts(Request $request)
    {
        $request->validate([
            'password' => 'required|string',
        ]);

        $user = auth()->user();
        
        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid password.',
            ], 401);
        }

        $count = StudentAccount::where('account_status', 'on')->update(['account_status' => 'off']);

        return response()->json([
            'success' => true,
            'message' => "Successfully deactivated {$count} student accounts.",
            'count' => $count,
        ]);
    }

    /**
     * Toggle a single student account status.
     */
    public function toggleAccountStatus($id)
    {
        $account = StudentAccount::findOrFail($id);
        $newStatus = $account->account_status === 'on' ? 'off' : 'on';
        $account->update(['account_status' => $newStatus]);

        return response()->json([
            'success' => true,
            'status' => $newStatus,
            'message' => "Account status updated to {$newStatus}.",
        ]);
    }

    /**
     * Generate examination permit for a student account.
     */
    public function generateExaminationPermit($accountId)
    {
        $account = StudentAccount::findOrFail($accountId);
        
        // Generate a 7-character alphanumeric permit code
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $permit = '';
        for ($i = 0; $i < 7; $i++) {
            $permit .= $characters[random_int(0, strlen($characters) - 1)];
        }
        
        $account->update(['examination_permit' => $permit]);

        return response()->json([
            'success' => true,
            'permit' => $permit,
            'message' => 'Examination permit generated successfully.',
        ]);
    }

    /**
     * Clear examination permit for a student account.
     */
    public function clearExaminationPermit($accountId)
    {
        $account = StudentAccount::findOrFail($accountId);
        
        $account->update(['examination_permit' => null]);

        return response()->json([
            'success' => true,
            'message' => 'Examination permit cleared successfully.',
        ]);
    }

    /**
     * Clear all examination permits.
     */
    public function clearAllExaminationPermits(Request $request)
    {
        $request->validate([
            'password' => 'required|string',
        ]);

        $user = auth()->user();
        
        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid password.',
            ], 401);
        }

        $count = StudentAccount::whereNotNull('examination_permit')->update(['examination_permit' => null]);

        return response()->json([
            'success' => true,
            'message' => "Successfully cleared {$count} examination permits.",
            'count' => $count,
        ]);
    }
}
