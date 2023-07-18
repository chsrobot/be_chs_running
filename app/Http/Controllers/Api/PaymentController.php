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
        $paymentEvidences = Payment::all();
        return response()->json($paymentEvidences);
    }

    public function store(Request $request)
    {
        $request->validate([
            'email_uploader' => 'required|email',
            'picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $picturePath = $request->file('picture')->store('payment_evidence', 'public');

        $paymentEvidence = Payment::create([
            'email_uploader' => $request->email_uploader,
            'picture_path' => $picturePath,
            'status' => 'pending',
            'email_checker' => null,
            'timestamp' => now(),
        ]);

        return response()->json($paymentEvidence, 201);
    }

    public function show($id)
    {
        $paymentEvidence = Payment::findOrFail($id);
        return response()->json($paymentEvidence);
    }

    public function update(Request $request, $id)
    {
        $paymentEvidence = Payment::findOrFail($id);
        $paymentEvidence->update($request->all());
        return response()->json($paymentEvidence, 200);
    }

    public function destroy($id)
    {
        $paymentEvidence = Payment::findOrFail($id);
        $paymentEvidence->delete();
        return response()->json(null, 204);
    }
}
