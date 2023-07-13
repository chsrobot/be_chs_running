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
        if (Account::where('email', request()->email )->exists()) {
            $content = ([
                'message' => 'Conflict',
            ]);
            return response($content,409)
                ->header('Token',Hash::make('secret'))
                ->header('Content-Type', 'application/json')
                ->header( 'Access-Control-Allow-Origin', '*' )
                ->header( 'Access-Control-Allow-Headers', 'Origin, Content-Type' );
        }

        $data = request()->all();
        $data['name'] = request()->name;
        $data['lastname'] = request()->lastname;
        $data['email'] = request()->email;
        $data['password'] = Hash::make(request()->password);
        Account::create($data);

        $content = ([
            'message' => 'Created',
        ]);
        return response($content,201)
            ->header('Token',Hash::make('secret'))
            ->header('Content-Type', 'application/json')
            ->header( 'Access-Control-Allow-Origin', '*' )
            ->header( 'Access-Control-Allow-Headers', 'Origin, Content-Type' );
    }

    public function signin(Request $request)
    {
        $account = Account::where('email', request()->email);
        if ($account->exists()) {
            $user = Account::where('email', request()->email)->first();
            $inputPassword = request()->password;
            if($user && Hash::check($inputPassword , $user->password)){
                $content = ([
                    'message' => 'OK',
                    'name' => $user->name,
                    'lastname' => $user->lastname,
                    'email' => $user->email
                ]);
                return response($content,200)
                    ->header('Token','test')
                    ->header('Content-Type', 'application/json')
                    ->header( 'Access-Control-Allow-Origin', '*' )
                    ->header( 'Access-Control-Allow-Headers', 'Origin, Content-Type' );
            }
            else{
                $content = ([
                    'message' => 'Invalid Credentials',
                    'user' => request()->email,
                    'password' => request()->password,
                ]);
                return response($content,401)
                    ->header('Token',Hash::make('secret'))
                    ->header('Content-Type', 'application/json')
                    ->header( 'Access-Control-Allow-Origin', '*' )
                    ->header( 'Access-Control-Allow-Headers', 'Origin, Content-Type' );

            }
        }
        else {
            $content = ([
                'message' => 'User Not Found',
            ]);
            return response($content,401)
                //->header('Token',Hash::make('secret'))
                ->header('Content-Type', 'application/json')
                ->header( 'Access-Control-Allow-Origin', '*' )
                ->header( 'Access-Control-Allow-Headers', 'Origin, Content-Type' );

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
