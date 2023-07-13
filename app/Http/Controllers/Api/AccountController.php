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
        $validatedData = $request->validate([
            'name' => 'required|string',
            'lastname' => 'required|string',
            'email' => 'required|email|unique:accounts,email',
            'password' => 'required|string|min:8'
        ]);
    
        $account = Account::create([
            'name' => $validatedData['name'],
            'lastname' => $validatedData['lastname'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password'])
        ]);
    
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
        $validatedData = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);
    
        $account = Account::where('email', $validatedData['email'])->first();
    
        if (!$account || !Hash::check($validatedData['password'], $account->password)) {
            $content = ([
                'message' => 'Invalid Credentials',
                'user' => $validatedData['email'],
                'password' => $validatedData['password'],
            ]);
            return response($content,401)
                ->header('Token',Hash::make('secret'))
                ->header('Content-Type', 'application/json')
                ->header( 'Access-Control-Allow-Origin', '*' )
                ->header( 'Access-Control-Allow-Headers', 'Origin, Content-Type' );
        }
    
        $content = ([
            'message' => 'Success',
            'name' => $account->name,
            'lastname' => $account->lastname,
            'email' => $account->email
        ]);
        return response($content,200)
            ->header('Token','test')
            ->header('Content-Type', 'application/json')
            ->header( 'Access-Control-Allow-Origin', '*' )
            ->header( 'Access-Control-Allow-Headers', 'Origin, Content-Type' );
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
