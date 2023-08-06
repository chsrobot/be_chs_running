<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller {

    public function isPaymentChecked( $email ) {
        $myPay = Payment::where( 'email_uploader', $email )->first();
        $isCheck = $myPay->status;
        if ( $isCheck == 'checked' ) {
            return true;
        }
    }

    public function index() {
        try {
            $orders = Order::all();
            return response()->json( $orders );
        } catch ( \Exception $e ) {
            return response()->json( [ 'error' => 'Something went wrong' ], 500 );
        }
    }

    public function getfromemail( Request $request ) {
        try {
            $user_email = $request->email;
            $orders = Order::where( 'user_email', $user_email )->get();

            return response()->json( $orders );
        } catch ( \Exception $e ) {
            return response()->json( [ 'error' => 'Something went wrong' ], 500 );
        }
    }

    public function store( Request $request ) {
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

            $order = Order::create( [
                'user_email' => $request->user_email,
                'product_name' => $request->product_name,
                'quantity' => $request->quantity,
            ] );

            return response()->json( $order, 201 );
        } catch ( \Exception $e ) {
            return response()->json( [ 'error' => 'Something went wrong' ], 500 );
        }
    }

    public function show( $id ) {
        try {
            $order = Order::findOrFail( $id );
            return response()->json( $order );
        } catch ( \Exception $e ) {
            return response()->json( [ 'error' => 'Order not found' ], 404 );
        }
    }

    public function update( Request $request, $id ) {
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

            $order = Order::findOrFail( $id );
            $order->update( $request->all() );
            return response()->json( $order, 200 );
        } catch ( \Exception $e ) {
            return response()->json( [ 'error' => 'Something went wrong' ], 500 );
        }
    }

    public function destroy( $id ) {
        try {

            $order = Order::findOrFail( $id );
            $order->delete();
            return response()->json( null, 204 );
        } catch ( \Exception $e ) {
            return response()->json( [ 'error' => 'Something went wrong' ], 500 );
        }
    }
}
