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
use Carbon\Carbon;


class PaymentController extends Controller
{

    function getAge($birthdate) {
        $age = date_diff(date_create($birthdate), date_create())->y;

        if ($age >= 0 && $age <= 19) {
            return 19;
        } elseif ($age > 60) {
            return 60;
        } else {
            return intval($age / 10) * 10;
        }
    }

    function formatType($input) {
        if (is_numeric($input) && intval($input) == $input) {
            return str_pad($input, 2, '0', STR_PAD_LEFT);
        } else {
            return $input;
        }
    }

    function threedigit($number) {
        if ($number < 10) {
            return "00" . $number;
        } elseif ($number < 100) {
            return "0" . $number;
        } else {
            return $number;
        }
    }

    public function bibnumber(Request $request){
        $emails = Information::join('payment_evidences', 'information.email', '=', 'payment_evidences.email_uploader')
        ->where('payment_evidences.status', 'checked')
        ->whereNull('information.bibnumber')
        ->orderBy('information.id')
        ->take(40)  // Limit to the first 20 results
        ->pluck('information.email');
    
    
            // ->first();


        foreach($emails as $email){
            error_log($email);
            $information = Information::where('email',$email)->whereNull('bibnumber')->get()->first();

            if($information->type_run == "VIP"){
                $expectbib = $this->formatType($information->type_run).$information->gender;
                $expectbibfind = $expectbib."%";
                $latest_bib = Information::where('bibnumber','like',$expectbibfind )->count();
                $latest_bib2 = OthersInformation::where('bibnumber','like',$expectbibfind )->count();
                $final_bib = $expectbib.$this->threedigit($latest_bib+ $latest_bib2 +1);

                $information->bibnumber = $final_bib;
                $information->save();

                //return $final_bib;
            }
            else{

            $expectbib = $information->gender . $this->getAge($information->birthdate) . "_" . $this->formatType($information->type_run) ."_". $information->size."_";
            $expectbibfind = $expectbib."%";
            $latest_bib = Information::where('bibnumber','like',$expectbibfind )->count();
            $latest_bib2 = OthersInformation::where('bibnumber','like',$expectbibfind )->count();
            
            $final_bib = $expectbib.$this->threedigit($latest_bib+ $latest_bib2 +1);
            $information->bibnumber = $final_bib;
            $information->save();
            }

            $othersinformations = OthersInformation::where('email',$email)->whereNull('bibnumber')->get();
            foreach($othersinformations as $othersinformation){
                if($othersinformation->type_run == "VIP"){
                    $expectbib = $this->formatType($othersinformation->type_run).$othersinformation->gender;
                    $expectbibfind = $expectbib."%";
                    $latest_bib = Information::where('bibnumber','like',$expectbibfind )->count();
                    $latest_bib2 = OthersInformation::where('bibnumber','like',$expectbibfind )->count();
                    $final_bib = $expectbib.$this->threedigit($latest_bib+ $latest_bib2 +1);
    
                    $othersinformation->bibnumber = $final_bib;
                    $othersinformation->save();
    
                    //return $final_bib;
                }
    else{
                $expectbib = $othersinformation->gender . $this->getAge($othersinformation->birthdate) . "_" . $this->formatType($othersinformation->type_run) ."_". $othersinformation->size."_";
                $expectbibfind = $expectbib."%";
                $latest_bib = Information::where('bibnumber','like',$expectbibfind )->count();
                $latest_bib2 = OthersInformation::where('bibnumber','like',$expectbibfind )->count();
                
                $final_bib = $expectbib.$this->threedigit($latest_bib+ $latest_bib2 +1);
                $othersinformation->bibnumber = $final_bib;
                $othersinformation->save();
    }
            }
            
            //return $final_bib;
        }
        
        return $emails;


    }

    public function countListpaid(Request $request)
    {
        $counts = [];
        
        $checkedEmails = Information::join('payment_evidences', 'information.email', '=', 'payment_evidences.email_uploader')
            ->where('payment_evidences.status', 'checked')
            ->pluck('information.email');
        
        $informationCheckedData = Information::whereIn('email', $checkedEmails)->get();
        $othersInformationCheckedData = OthersInformation::whereIn('email', $checkedEmails)->get();
        
        $ageRanges = [
            '0-19' => [0, 19],
            '20-29' => [20, 29],
            '30-39' => [30, 39],
            '40-49' => [40, 49],
            '50-59' => [50, 59],
            '60-100' => [60, 100],
        ];
        
        foreach ($informationCheckedData as $information) {
            $typeRun = $information->type_run;
            $gender = $information->gender;
            $age = Carbon::parse($information->birthdate)->age;
            
            $ageRange = null;
            foreach ($ageRanges as $range => $limits) {
                if ($age >= $limits[0] && $age <= $limits[1]) {
                    $ageRange = $range;
                    break;
                }
            }
            
            if ($ageRange === null) {
                continue;
            }
        
            if (!isset($counts[$typeRun])) {
                $counts[$typeRun] = [];
            }
            
            if (!isset($counts[$typeRun][$gender])) {
                $counts[$typeRun][$gender] = [];
            }
            
            if (!isset($counts[$typeRun][$gender][$ageRange])) {
                $counts[$typeRun][$gender][$ageRange] = 0;
            }
        
            $counts[$typeRun][$gender][$ageRange]++;
        }
        
        foreach ($othersInformationCheckedData as $othersInformation) {
            // Similar counting logic for OthersInformation
            $typeRun = $othersInformation->type_run;
            $gender = $othersInformation->gender;
            $age = Carbon::parse($othersInformation->birthdate)->age;
            
            $ageRange = null;
            foreach ($ageRanges as $range => $limits) {
                if ($age >= $limits[0] && $age <= $limits[1]) {
                    $ageRange = $range;
                    break;
                }
            }
            
            if ($ageRange === null) {
                continue;
            }
        
            if (!isset($counts[$typeRun])) {
                $counts[$typeRun] = [];
            }
            
            if (!isset($counts[$typeRun][$gender])) {
                $counts[$typeRun][$gender] = [];
            }
            
            if (!isset($counts[$typeRun][$gender][$ageRange])) {
                $counts[$typeRun][$gender][$ageRange] = 0;
            }
        
            $counts[$typeRun][$gender][$ageRange]++;
        }
        
        return response()->json($counts, 200);
    }



    public function countList(Request $request) {

        $informationData = Information::all();
        $othersInformationData = OthersInformation::all(); 
        
        $ageRanges = [
            '0-19' => [0, 19],
            '20-29' => [20, 29],
            '30-39' => [30, 39],
            '40-49' => [40, 49],
            '50-59' => [50, 59],
            '60-100' => [60, 120],
        ];
        
        $counts = [];
        
        foreach ($informationData as $entry) {
            $typeRun = $entry->type_run;
            $gender = $entry->gender;
            $age = Carbon::parse($entry->birthdate)->age;
            
            $ageRange = null;
            foreach ($ageRanges as $range => $limits) {
                if ($age >= $limits[0] && $age <= $limits[1]) {
                    $ageRange = $range;
                    break;
                }
            }
            
            if ($ageRange === null) {
                continue;
            }
        
            if (!isset($counts[$typeRun])) {
                $counts[$typeRun] = [];
            }
            
            if (!isset($counts[$typeRun][$gender])) {
                $counts[$typeRun][$gender] = [];
            }
            
            if (!isset($counts[$typeRun][$gender][$ageRange])) {
                $counts[$typeRun][$gender][$ageRange] = 0;
            }
        
            $counts[$typeRun][$gender][$ageRange]++;
        }
        
        foreach ($othersInformationData as $entry) {
            $typeRun = $entry->type_run;
            $gender = $entry->gender;
            $age = Carbon::parse($entry->birthdate)->age;
            
            $ageRange = null;
            foreach ($ageRanges as $range => $limits) {
                if ($age >= $limits[0] && $age <= $limits[1]) {
                    $ageRange = $range;
                    break;
                }
            }
            
            if ($ageRange === null) {
                continue;
            }
        
            if (!isset($counts[$typeRun])) {
                $counts[$typeRun] = [];
            }
            
            if (!isset($counts[$typeRun][$gender])) {
                $counts[$typeRun][$gender] = [];
            }
            
            if (!isset($counts[$typeRun][$gender][$ageRange])) {
                $counts[$typeRun][$gender][$ageRange] = 0;
            }
        
            $counts[$typeRun][$gender][$ageRange]++;

        }
    
        return response()->json($counts, 200);
    }

    public function runShellScriptWithArgs($email,$name)
    {
        $arg1 = escapeshellarg($email);
        $arg2 = escapeshellarg($name);

        shell_exec("/home/adminreg/runner_prod/be_chs_running/sendmail.sh $arg1 $arg2");
    }


    public function adminPayment(Request $request) {
        $perPage = $request->input('per_page', 10);
        $page = $request->input('page', 1);
        $status = $request->input('status'); // Get the status filter from the request
        
        $query = Account::query();
        
        if ($status) {
            $query->join('payment_evidences', 'accounts.email', '=', 'payment_evidences.email_uploader')
                  ->where('payment_evidences.status', $status);
        }
        
        $totalCount = $query->count();
        
        $accounts = $query->select('accounts.email', 'accounts.name')
                         ->skip(($page - 1) * $perPage)
                         ->take($perPage)
                         ->get();
        
        $response = [];
    
        foreach ($accounts as $account) {
            $email = $account->email;
            $otherPayTotal = 0;
            $shipment = 0;
            error_log($email);
            
    
            $paymentEvidence = Payment::select('picture_path','email_checker','status')->where('email_uploader', $email)->first();
            $evidence = $paymentEvidence->picture_path ?? null;
            $whocheck = $paymentEvidence->email_checker ?? null;
            $status = $paymentEvidence->status ?? null;
    
            $orderpay = $this->getPayOrder($email);
            $myPay = Information::select('phonenumber','type_run','shipment','bibnumber')->where("email", $email)->first();
            error_log($myPay);
            $tel = $myPay->phonenumber ?? null;
            $accname = $account->name ?? null;
            $mybib = $myPay->bibnumber ?? null;
            

            
    
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
    
            $otherPay = OthersInformation::select('type_run','bibnumber')->where("email", $email)->get();
    
            $others_bib = [];

            foreach ($otherPay as $friend) {
                $otherPayTotal += $this->getPriceRun($friend->type_run ?? null);
                array_push($others_bib, $friend->bibnumber);
            }
    
            if ($isShipment == "true") {
                $shipment += 50;
    
                $friendshipment = OthersInformation::where('email', $email)->count();
                $shipment += ($friendshipment * 10);
            }

            // if ($otherPay !== null) {
            //     $others_bib = $otherPay->bibnumber ?? null;
            // }
            // else{
            //     $others_bib = null;
            // }

            $mybib = array_merge([$mybib], $others_bib);

            $total = $toMyPay + $otherPayTotal + $orderpay + $shipment;
    
            $response[] = [
                'name'=>$accname,
                'tel'=>$tel,
                'email' => $email,
                'myPay' => $toMyPay,
                'otherPay' => $otherPayTotal,
                'orderPay' => $orderpay,
                'total' =>  $total,
                'status' =>  $status,
                'mybib' => $mybib,
                'path' =>  $evidence,
                'whocheck' => $whocheck
            ];
        }
        $sortedResponse = collect($response)->sortBy(function ($item) {
            switch ($item['status']) {
                case 'pending':
                    return 1;
                case 'checked':
                    return 2;
                case 'waiting':
                    return 3;
                default:
                    return 4;
            }
        })->values()->all();
        
        $totalPages = ceil($totalCount / $perPage);
        return response()->json([
            'data' => $sortedResponse,
            'total_pages' => $totalPages, // Include total page count in the response
        ], 200);
        //return response()->json(['data' => $response], 200);
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

        $myinfo = [
            'name' => $myPay->name,
            'lastname' => $myPay->lastname,
            'bibnumber' => $myPay->bibnumber
        ];

        foreach ($otherPay as $friend) {
            $otherPayTotal += $this->getPriceRun($friend->type_run ?? null);
        }

        $orderpay = $this->getPayOrder($email);

        $total = $toMyPay + $otherPayTotal + $orderpay + $shipment;

        
       
    

        return response()->json([
            'myinfo' => $myinfo,
            'mypay' => $toMyPay,
            'friendpay' => $otherPayTotal,
            'orderpay' => $orderpay,
            'shipment' => $shipment,
            'totalpay' => $total,
            'status' =>  $status,
            'evidence' => $evidence
        ], 200);
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

    public function changePaymentStatus(Request $request)
    {
        try {
            $status = $request->input('status');
            $email_checker = $request->input('email');
            $email_uploader =$request->input('email_uploader');

            if ($status !== 'pending' && $status !== 'checked') {
                $content = [
                    'message' => 'Invalid status. The status must be either "pending" or "checked".',
                ];
                return response($content, 400)
                    ->header('Content-Type', 'application/json')
                    ->header('Access-Control-Allow-Origin', '*')
                    ->header('Access-Control-Allow-Headers', 'Origin, Content-Type');
            }

            $payment = Payment::where("email_uploader",$email_uploader)->get()->first();
            if (!$payment) {
                $content = [
                    'message' => 'Payment record not found.',
                ];
                return response($content, 404)
                    ->header('Content-Type', 'application/json')
                    ->header('Access-Control-Allow-Origin', '*')
                    ->header('Access-Control-Allow-Headers', 'Origin, Content-Type');
            }

            $payment->status = $status;
            $payment->email_checker = $email_checker;
            $payment->save();

            $name = Account::where("email",$email_uploader)->get()->first();

            $name = $name->name;

            if($status == "checked"){
                $this->runShellScriptWithArgs($email_uploader,$name);
            }
            

            $content = [
                'message' => 'Payment status updated successfully.',
            ];
            return response($content, 200)
                ->header('Content-Type', 'application/json')
                ->header('Access-Control-Allow-Origin', '*')
                ->header('Access-Control-Allow-Headers', 'Origin, Content-Type');
        } catch (\Exception $e) {
            $content = [
                'message' => 'An error occurred.',
                'error' => $e->getMessage(),
            ];
            return response($content, 500)
                ->header('Content-Type', 'application/json')
                ->header('Access-Control-Allow-Origin', '*')
                ->header('Access-Control-Allow-Headers', 'Origin, Content-Type');
        }
    }
}
