<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Account;
use Illuminate\Support\Facades\Hash;

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
        $content = ([
            'message' => 'Test Prod Again !!',
        ]);
        return response($content,201)
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
                    ->header('Token', Hash::make('secret'))
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
            $token = Hash::make($request->input('email') + $request->input('password'));
    
            if ($account->exists()) {
                $user = $account->first();
                $inputPassword = $request->input('password');
    
                if ($user && Hash::check($inputPassword, $user->password)) {
                    $content = [
                        'message' => 'Success',
                        'name' => $user->name,
                        'lastname' => $user->lastname,
                        'email' => $user->email,
                    ];
                    return response($content, 200)
                        ->header('Token', Hash::make($token))
                        ->header('Content-Type', 'application/json')
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
