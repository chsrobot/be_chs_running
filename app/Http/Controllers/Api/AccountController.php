<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Account;
use App\Models\Payment;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }
    public function test()
    {
        $tokenData = [
            'email' => "Test",
            'expires_at' => Carbon::now()->addMinutes(30)->toDateTimeString(),
            'expired' => Carbon::now()->addMinutes(-10)->isPast(),
            
        ];
        return response($tokenData,201)
            ->header('Token',Hash::make('secret'))
            ->header('Content-Type', 'application/json')
            ->header( 'Access-Control-Allow-Origin', '*' )
            ->header( 'Access-Control-Allow-Headers', 'Origin, Content-Type' );
    }


    public function signup(Request $request)
    {
        try {
            $email = $request->input('email');


    
            if (Account::where('email', $email)->exists()) {
                $content = [
                    'message' => 'Conflict',
                ];
                return response($content, 409)
                    ->header('Content-Type', 'application/json')
                    ->header('Access-Control-Allow-Origin', '*')
                    ->header('Access-Control-Allow-Headers', 'Origin, Content-Type');
            }
    
            $data = [
                'name' => $request->input('name'),
                'lastname' => $request->input('lastname'),
                'email' => $email,
                'password' => Hash::make($request->input('password')),
            ];
            Account::create($data);
            Payment::create([
                'email_uploader' => $email,
                'picture_path' => null,
                'status' => 'waiting',
                'email_checker' => null,
                'timestamp' => now(),
            ]);
    
            $content = [
                'message' => 'Created',
            ];
            return response($content, 201)
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
    
    public function signin(Request $request)
    {
        try {
            $email = $request->input('email');
            $account = Account::where('email', $email);
            
    
            if ($account->exists()) {
                $user = $account->first();
                $inputPassword = $request->input('password');
    
                if ($user && Hash::check($inputPassword, $user->password)) {

                    $expiresAt = Carbon::now()->addMinutes(30)->toDateTimeString();

                    $tokenData = [
                        'email' => $email,
                        'salt' => "H7AdNZtPtduXWaPp",
                        'expires_at' => $expiresAt,
                    ];
                    $token = 'Bearer ' . Hash::make(json_encode($tokenData));

                    $content = [
                        'message' => 'Success',
                        'name' => $user->name,
                        'lastname' => $user->lastname,
                        'email' => $user->email,
                    ];
                    return response($content, 200)
                        ->header('Authorization', $token) 
                        ->header('Token-Expires-At', $expiresAt)
                        ->header('Content-Type', 'application/json')
                        ->header('Access-Control-Expose-Headers', '*')
                        ->header('Access-Control-Allow-Origin', '*')
                        ->header('Access-Control-Allow-Headers', 'Origin, Content-Type');

                } else {
                    $content = [
                        'message' => 'Invalid Credentials'
                    ];
                    return response($content, 401)
                        ->header('Content-Type', 'application/json')
                        ->header('Access-Control-Allow-Origin', '*')
                        ->header('Access-Control-Allow-Headers', 'Origin, Content-Type');
                }
            } else {
                $content = [
                    'message' => 'User Not Found',
                ];
                return response($content, 404)
                    ->header('Content-Type', 'application/json')
                    ->header('Access-Control-Allow-Origin', '*')
                    ->header('Access-Control-Allow-Headers', 'Origin, Content-Type');
            }
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
       

    
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
