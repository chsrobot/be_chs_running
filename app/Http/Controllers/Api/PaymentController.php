<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;


class PaymentController extends Controller
{
    public function index()
    {
        try {
            $paymentEvidences = Payment::all();
            return response()->json($paymentEvidences);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong'], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'email_uploader' => 'required|email',
                'picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);
    
            $userEmail = $request->email_uploader;
    
            // Check if the user has already uploaded a payment evidence
            $existingEvidence = Payment::where('email_uploader', $userEmail)->first();
    
            if ($existingEvidence) {
                // If status is "checked," prevent further uploads
                if ($existingEvidence->status === 'checked') {
                    return response()->json(['error' => 'You cannot upload again.'], 400);
                }
    
                // Delete the old picture from storage
                Storage::disk('public')->delete($existingEvidence->picture_path);
    
                // Update the existing evidence with the new picture
                $picturePath = $request->file('picture')->store('payment_evidence', 'public');
                $existingEvidence->update([
                    'picture_path' => $picturePath,
                    'status' => 'pending',
                    'timestamp' => now(),
                ]);
    
                return response()->json($existingEvidence, 200);
            }
    
            // If no existing evidence, create a new one
            $picturePath = $request->file('picture')->store('payment_evidence', 'public');
    
            $paymentEvidence = Payment::create([
                'email_uploader' => $request->email_uploader,
                'picture_path' => $picturePath,
                'status' => 'pending',
                'email_checker' => null,
                'timestamp' => now(),
            ]);
    
            return response()->json($paymentEvidence, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong'], 500);
        }
    }

    public function show($id)
    {
        try {
            $paymentEvidence = Payment::findOrFail($id);
            return response()->json($paymentEvidence);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Payment evidence not found'], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $paymentEvidence = Payment::findOrFail($id);
            $paymentEvidence->update($request->all());
            return response()->json($paymentEvidence, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $paymentEvidence = Payment::findOrFail($id);
            $paymentEvidence->delete();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong'], 500);
        }
    }
}
