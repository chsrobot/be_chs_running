<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\Account;
use App\Models\Order;
use App\Models\ProductList;
use App\Models\Information;
use App\Models\OthersInformation;


class PaymentController extends Controller
{

    public function adminPayment(Request $request) {
        $accounts = Account::all();
        $response = [];
    
        foreach ($accounts as $account) {
            $email = $account->email;
            $otherPayTotal = 0;
            $shipment = 0;
            error_log($email);
            
    
            $paymentEvidence = Payment::where('email_uploader', $email)->first();
            $evidence = $paymentEvidence->picture_path ?? null;
            $status = $paymentEvidence->status ?? null;
    
            $orderpay = $this->getPayOrder($email);
            $myPay = Information::where("email", $email)->first();
    
            // Handle the case when $myPay is null
            if (!$myPay) {
                // Set default values for its properties
                $toMyPay = 0;
                $isShipment = false;
            } else {
                // Use the actual values from the $myPay object
                $toMyPay = $this->getPriceRun($myPay->type_run ?? null);
                $isShipment = $myPay->shipment ?? false;
            }
    
            $otherPay = OthersInformation::where("email", $email)->get();
    
            foreach ($otherPay as $friend) {
                $otherPayTotal += $this->getPriceRun($friend->type_run ?? null);
            }
    
            if ($isShipment == "true") {
                $shipment += 50;
    
                $friendshipment = OthersInformation::where('email', $email)->count();
                $shipment += ($friendshipment * 10);
            }
    
            $total = $toMyPay + $otherPayTotal + $orderpay + $shipment;
    
            $response[] = [
                'email' => $email,
                'myPay' => $toMyPay,
                'otherPay' => $otherPayTotal,
                'orderPay' => $orderpay,
                'total' =>  $total,
                'status' =>  $status,
                'path' =>  $evidence
            ];
        }
    
        return response()->json(['data' => $response], 200);
    }

    
    

    public function getPayOrder($email) {
        $topay = 0;
        $orderList = Order::where('user_email', $email)->get();
    
        foreach ($orderList as $order) {
            $product = ProductList::where('name', $order->product_name)->get()->first();
    
            $pricecal = ($product->price ?? 0) * ($order->quantity ?? 0);
            $topay += $pricecal;
        }
    
        $return = $topay;
    
        return $return;
    }
    

    public function getPriceRun($type) {
        switch ($type) {
            case "2":
                return 300;
            case "5":
                return 500;
            case "10":
                return 600;
            case "VIP":
                return 1300;
            default:
                return 0;
        }
    }
    
    public function topayRunning(Request $request) {
        $email = $request->email;
        $otherPayTotal = 0;
        $shipment = 0;
        $myPay = Information::where("email", $email)->first();
        $otherPay = OthersInformation::where("email", $email)->get();

        $isShipment =  $myPay->shipment;
        
        if($isShipment == "true"){
            $shipment += 50;

            $friendshipment = OthersInformation::where('email', $email)->count();
            $shipment += ($friendshipment * 10);
        }
            
        $paymentEvidence = Payment::where('email_uploader', $email)->first();
        $evidence = $paymentEvidence->picture_path ?? null;
        $status = $paymentEvidence->status ?? null;

        $toMyPay = $this->getPriceRun($myPay->type_run ?? null);

        foreach ($otherPay as $friend) {
            $otherPayTotal += $this->getPriceRun($friend->type_run ?? null);
        }

        $orderpay = $this->getPayOrder($email);

        $total = $toMyPay + $otherPayTotal + $orderpay + $shipment;

       
    

        return response()->json([
            'mypay' => $toMyPay,
            'friendpay' => $otherPayTotal,
            'orderpay' => $orderpay,
            'shipment' => $shipment,
            'totalpay' => $total,
            'status' =>  $status,
            'evidence' => $evidence
        ], 500);
    }
    
    



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

            error_log($request);
    
            $userEmail = $request->email_uploader;
            $existingEvidence = Payment::where('email_uploader', $userEmail)->first();
    
            if ($existingEvidence) {
                if ($existingEvidence->status === 'checked') {
                    return response()->json(['error' => 'You cannot upload again.'], 400);
                }
    
                //Storage::disk('public')->delete($existingEvidence->picture_path);
    
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
            error_log($e);
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
