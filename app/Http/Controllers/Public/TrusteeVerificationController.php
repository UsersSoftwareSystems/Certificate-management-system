<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Applicant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TrusteeVerificationController extends Controller
{
    public function show($token)
    {
        // We use the same token for simplicity, or we could generate a separate verification token. 
        // For now, using the applicant token is sufficient as it's a unique identifier.
        // Ideally, we should check if the link is signed/valid to prevent enumeration, but the token is random text.
        
        $applicant = Applicant::where('token', $token)->firstOrFail();
        
        if ($applicant->trustee_status === 'approved' || $applicant->trustee_status === 'rejected') {
            return view('public.trustee-verification', [
                'applicant' => $applicant,
                'alreadyResponded' => true
            ]);
        }

        return view('public.trustee-verification', [
            'applicant' => $applicant,
            'alreadyResponded' => false
        ]);
    }

    public function update(Request $request, $token)
    {
        $applicant = Applicant::where('token', $token)->firstOrFail();
        
        $request->validate([
            'action' => 'required|in:approve,reject',
            'comments' => 'nullable|string|max:1000' // Optional reason for rejection
        ]);

        if ($applicant->trustee_status !== 'pending' && $applicant->trustee_status !== 'requested') {
             return redirect()->route('apply.trustee.verify.show', $token)
                ->with('error', 'This request has already been processed.');
        }

        $status = $request->action === 'approve' ? 'approved' : 'rejected';
        
        $applicant->update([
            'trustee_status' => $status,
            'trustee_responded_at' => now(),
            // We could store comments in notes or a specific field if we added one. 
            // For now, let's append to notes if rejected.
            'notes' => $request->action === 'reject' && $request->comments ? 
                       ($applicant->notes . "\n[Trustee Rejection Reason]: " . $request->comments) : 
                       $applicant->notes
        ]);

        Log::info("Trustee {$status} application for applicant ID: {$applicant->id}");

        return redirect()->route('apply.trustee.verify.show', $token)
            ->with('success', 'Thank you. Your response has been recorded.');
    }
}
