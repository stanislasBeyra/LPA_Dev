<?php

namespace App\Http\Controllers;

use App\Models\employee;
use App\Models\UserDeactivationReason;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserDeactivationReasonController extends Controller
{
    //

    public function deactivateEmployee(Request $request)
    {
        try {
            $user = employee::findOrFail($request->employeeId);

            $newStatus = $user->status == 1 ? 0 : 1;
            $user->update(['status' => $newStatus]);

            UserDeactivationReason::create([
                'user_id' => $user->id,
                'admin_id' => Auth::id(),
                'reason' => $request->input('reason') ?? "Super admin has deactivated or reactivated this employee"
            ]);

            $statusMessage = $newStatus == 0 ? 'Employee deactivated successfully.' : 'Employee reactivated successfully.';
            return back()->with('success', $statusMessage);
        } catch (\Exception $e) {
            Log::error('Error while deactivating user: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while updating the user.');
        }
    }
}
