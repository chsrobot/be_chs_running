<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InformationController extends Controller
{
    public function insertOrUpdateInformation(Request $request)
    {
        $data = $request->all();
        $email = $data['email'];
        $information = Information::where('email', $email)->first();
    
        if (!$information) {
            Information::create($data);
            $message = 'Created';
            $status = 201;
        } else {
            $information->update($data);
            $message = 'Updated';
            $status = 200;
        }
    
        $content = ([
            'message' => $message,
        ]);
        return response($content, $status)
            ->header('Token', Hash::make('secret'))
            ->header('Content-Type', 'application/json')
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Headers', 'Origin, Content-Type');
    }
    
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
