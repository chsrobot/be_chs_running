<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Account;
use App\Models\Payment;
use App\Models\Information;
use App\Models\OthersInformation;
use Illuminate\Support\Facades\Hash;

class InformationController extends Controller {

    public function isPaymentChecked( $email ) {
        $myPay = Payment::where( 'email_uploader', $email )->first();
        $isCheck = $myPay->status ?? null;
        error_log( 'Hello ??' );
        error_log( $isCheck );
        if ( $isCheck == 'checked' ) {
            return true;
        }
        else{
            return false;
        }
    }
    // public function findbib(Request $request) {
    //     $email = $request->email;
    
    //     // Fetch the information for the specified email from both tables
    //     $info = Information::where('information.email', $email)
    //     ->select('accounts.name', 'accounts.lastname', 'information.bibnumber')
    //     ->join('accounts', 'information.email', '=', 'accounts.email')
    //     ->first();
    
    //     $others = OthersInformation::where('email', $email)
    //     ->select('name', 'lastname', 'bibnumber')
    //     ->get();
        
    
    //     return response()->json($info )
    //         ->header('Content-Type', 'application/json')
    //         ->header('Access-Control-Allow-Origin', '*')
    //         ->header('Access-Control-Allow-Headers', 'Origin, Content-Type');
    // }
    
    public function findbib(Request $request) {
        $email = $request->email;
    
        // Fetch the information for the specified email from both tables and sort by bibnumber
        $info = Information::
            select('accounts.email','accounts.name', 'accounts.lastname', 'information.bibnumber')
            ->join('accounts', 'information.email', '=', 'accounts.email')
            ->whereNotNull('information.bibnumber') // Filter by not null bibnumber
            // ->orderBy('information.bibnumber') // Sort by bibnumber
            ->get();
    
        $others = OthersInformation::
            select('email','name', 'lastname', 'bibnumber')
            ->whereNotNull('bibnumber') // Filter by not null bibnumber
            // ->orderBy('bibnumber') // Sort by bibnumber
            ->get();
    
        $othersinfo = $others->toArray(); // Convert to array
        $infoinfo = $info->toArray(); // Convert to array
    
        $res = array_merge($infoinfo, $othersinfo);
    
        return response()->json($res)
            ->header('Content-Type', 'application/json')
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Headers', 'Origin, Content-Type');
    }
    
       



    public function insertOrUpdateInformation( Request $request ) {
        try {

            $data = $request->all();
            $email = $data['email'];

            if ( $this->isPaymentChecked( $email ) ) {
                $content = [
                    'message' => 'Cannot update: Payment evidence status is checked',
                ];
                return response( $content, 200)
                ->header( 'Content-Type', 'application/json' )
                ->header( 'Access-Control-Allow-Origin', '*' )
                ->header( 'Access-Control-Allow-Headers', 'Origin, Content-Type' );
            }

            $information = Information::where( 'email', $email )->first();

            if ( !$information ) {
                Information::create( $data );
                $message = 'Created';
                $status = 201;
            } else {
                $information->update( $data );
                $message = 'Updated';
                $status = 200;
            }

            $content = [
                'message' => $message,
            ];
            error_log( $status );
            return response( $content, $status )
            ->header( 'Content-Type', 'application/json' )
            ->header( 'Access-Control-Allow-Origin', '*' )
            ->header( 'Access-Control-Allow-Headers', 'Origin, Content-Type' );
        } catch ( \Exception $e ) {
            $content = [
                'message' => 'An error occurred.',
                'error' => $e->getMessage(),
            ];
            return response( $content, 500 )
            ->header( 'Content-Type', 'application/json' )
            ->header( 'Access-Control-Allow-Origin', '*' )
            ->header( 'Access-Control-Allow-Headers', 'Origin, Content-Type' );
        }
    }

    public function getInformationByEmail( Request $request ) {
        try {
            $information = Information::where( 'email', $request->email )->first();
            return $information;
        } catch ( \Exception $e ) {
            $content = [
                'message' => 'An error occurred.',
                'error' => $e->getMessage(),
            ];
            return response( $content, 500 )
            ->header( 'Content-Type', 'application/json' )
            ->header( 'Access-Control-Allow-Origin', '*' )
            ->header( 'Access-Control-Allow-Headers', 'Origin, Content-Type' );
        }
    }

    public function insertFriendsInformation( Request $request ) {
        try {

            $data = $request->all();
            $email = $data['email'];
            if ( $this->isPaymentChecked( $email ) ) {
                $content = [
                    'message' => 'Cannot update: Payment evidence status is checked',
                ];
                return response( $content, 200)
                ->header( 'Content-Type', 'application/json' )
                ->header( 'Access-Control-Allow-Origin', '*' )
                ->header( 'Access-Control-Allow-Headers', 'Origin, Content-Type' );
            }

            $friendsInformation = OthersInformation::create( $data );

            $message = 'Created';
            $status = 201;

            return response()->json( [
                'message' => $message,
                'data' => $friendsInformation,
            ], $status );

        } catch ( \Exception $e ) {

            $content = [
                'message' => 'An error occurred.',
                'error' => $e->getMessage(),
            ];
            return response()->json( $content, 500 )
            ->header( 'Access-Control-Allow-Origin', '*' )
            ->header( 'Access-Control-Allow-Headers', 'Origin, Content-Type' );
        }
    }

    public function viewFriendsInformationByEmail( Request $request ) {
        try {
            $email = $request->email;
            $friendsInformation = OthersInformation::where( 'email', $email )->get();

            if ( !$friendsInformation ) {
                $content = [
                    'message' => 'Data not found.',
                ];
                return response()->json( $content, 404 )
                ->header( 'Access-Control-Allow-Origin', '*' )
                ->header( 'Access-Control-Allow-Headers', 'Origin, Content-Type' );
            }

            $message = 'Data found';
            $status = 200;

            return response()->json( [
                'data' => $friendsInformation,
            ], $status );

        } catch ( \Exception $e ) {
            $content = [
                'message' => 'An error occurred.',
                'error' => $e->getMessage(),
            ];
            return response()->json( $content, 500 )
            ->header( 'Access-Control-Allow-Origin', '*' )
            ->header( 'Access-Control-Allow-Headers', 'Origin, Content-Type' );
        }
    }

    public function deleteFriendsInformationByIdCard( Request $request ) {
        try {

            $data = $request->all();
            $email = $data['email'];
            if ( $this->isPaymentChecked( $email ) ) {
                $content = [
                    'message' => 'Cannot update: Payment evidence status is checked',
                ];
                return response( $content, 200)
                ->header( 'Content-Type', 'application/json' )
                ->header( 'Access-Control-Allow-Origin', '*' )
                ->header( 'Access-Control-Allow-Headers', 'Origin, Content-Type' );
            }

            $idcard = $request->idcard;
            $friendsInformation = OthersInformation::where( 'idcard', $idcard )->first();

            if ( !$friendsInformation ) {
                $content = [
                    'message' => 'Data not found.',
                ];
                return response()->json( $content, 404 )
                ->header( 'Access-Control-Allow-Origin', '*' )
                ->header( 'Access-Control-Allow-Headers', 'Origin, Content-Type' );
            }

            $friendsInformation->delete();
            $message = 'Data deleted';
            $status = 200;

            return response()->json( [
                'message' => $message,
            ], $status );

        } catch ( \Exception $e ) {
            $content = [
                'message' => 'An error occurred.',
                'error' => $e->getMessage(),
            ];
            return response()->json( $content, 500 )
            ->header( 'Access-Control-Allow-Origin', '*' )
            ->header( 'Access-Control-Allow-Headers', 'Origin, Content-Type' );
        }
    }

    public function updateFriendByIdCard( Request $request ) {
        try {

            $data = $request->all();
            $email = $data['email'];
            if ( $this->isPaymentChecked( $email ) ) {
                $content = [
                    'message' => 'Cannot update: Payment evidence status is checked',
                ];
                return response( $content, 200)
                ->header( 'Content-Type', 'application/json' )
                ->header( 'Access-Control-Allow-Origin', '*' )
                ->header( 'Access-Control-Allow-Headers', 'Origin, Content-Type' );
            }

            $idcard = $request->idcard;
            $friend = OthersInformation::where( 'idcard', $idcard )->first();

            if ( !$friend ) {
                $content = [
                    'message' => 'Friend not found.',
                ];
                return response()->json( $content, 404 )
                ->header( 'Access-Control-Allow-Origin', '*' )
                ->header( 'Access-Control-Allow-Headers', 'Origin, Content-Type' );
            }

            $data = $request->all();
            $friend->update( $data );

            $message = 'Friend information updated';
            $status = 200;

            return response()->json( [
                'message' => $message,
                'data' => $friend,
            ], $status );

        } catch ( \Exception $e ) {
            $content = [
                'message' => 'An error occurred.',
                'error' => $e->getMessage(),
            ];
            return response()->json( $content, 500 )
            ->header( 'Access-Control-Allow-Origin', '*' )
            ->header( 'Access-Control-Allow-Headers', 'Origin, Content-Type' );
        }
    }

    public function index() {
        //
    }

    /**
    * Store a newly created resource in storage.
    */

    public function store( Request $request ) {
        //
    }

    /**
    * Display the specified resource.
    */

    public function show( string $id ) {
        //
    }

    /**
    * Update the specified resource in storage.
    */

    public function update( Request $request, string $id ) {
        //
    }

    /**
    * Remove the specified resource from storage.
    */

    public function destroy( string $id ) {
        //
    }
}
