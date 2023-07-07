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
            'message' => 'Test Prod Again',
        ]);
        return response($content,201)
            ->header('Token',Hash::make('secret'))
            ->header('Content-Type', 'application/json')
            ->header( 'Access-Control-Allow-Origin', '*' )
            ->header( 'Access-Control-Allow-Headers', 'Origin, Content-Type' );
    }
    public function signup(Request $request)
    {
        if (Account::where('name', request()->name )->exists()) {
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
    // public function store(Request $request)
    // {
    //     $validatedData = $request->validate([
    //         'name' => 'required',
    //         'lastname' => 'required',
    //         'email' => 'required|email|unique:signup_accounts',
    //         'password' => 'required|min:6',
    //     ]);

    //     $validatedData['password'] = Hash::make($validatedData['password']);

    //     $signupAccount = Account::create($validatedData);

    //     return response()->json(['message' => 'Account created successfully', 'data' => "Hello"], 201);
    // }

    
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
