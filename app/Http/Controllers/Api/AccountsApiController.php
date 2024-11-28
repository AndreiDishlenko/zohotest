<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Services\ZohoAuth;
use Illuminate\Http\Request;
use App\Services\ZohoRecords;

use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;


class AccountsApiController extends Controller
{
    public static function getRecords() {
        // JWTAuth::
        // dd(JWTAuth::parseToken());
        // dd(JWTAuth::user());
        // $token = JWTAuth::parseToken();
        // $token->check
        // dd($token->authenticate());

        $fieldNames = ["Account_Name", "Website", "Phone"];

        try {
            (new ZohoAuth())->init();
            $result = ZohoRecords::get("Accounts", $fieldNames);
            return response($result, 200);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 400);
        }        
    }

    public static function createRecord(Request $request) {
        // Validation
        $validator = Validator::make($request->all(), [
            'Account_Name'  =>  ['required', 'string', 'min:2'],
            'Website'       =>  ['required', 'regex:/^(www\.)?[\w.-]+\.[a-z]{2,6}(\/.*)?$/'],
            'Phone'         =>  ['required', 'regex:/^\(\d{3}\) \d{3}-\d{2}-\d{2}$/'],
        ]);
        if ($validator->fails())
            return response($validator->errors(), 422);

        // Prepare data
        $data = [];        
        foreach($request->getPayload() as $key=>$value) 
            $data[$key] = $value;

        // API request
        try {            
            (new ZohoAuth())->init();
            
            $result = ZohoRecords::create("Accounts", $data);
            return response($result, 200);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 400);
        }    
    }
}
