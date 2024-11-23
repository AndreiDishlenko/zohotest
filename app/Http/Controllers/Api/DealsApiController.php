<?php

namespace App\Http\Controllers\Api;

use DateTime;
use Exception;
use App\Services\ZohoAuth;
use Illuminate\Http\Request;
use App\Services\ZohoRecords;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class DealsApiController extends Controller
{
    public static function createRecord(Request $request) {   
        // Validation
        $validator = Validator::make($request->all(), [
            'Deal_Name'     =>  ['required', 'string', 'min:2'],
            'Account_Name'  =>  ['required', 'string', 'min:2'],
            'Closing_Date'  =>  ['required', 'regex:/^\d{2}.\d{2}.\d{4}$/'],
            'Stage'         =>  ['required', 'string', 'min:2'],
        ]);
        if ($validator->fails())
            return response($validator->errors(), 422);
             
        // Prepare data
        $data = [];
        foreach($request->getPayload() as $key=>$value) 
            $data[$key] = $value;

        $date = DateTime::createFromFormat('d.m.Y', $data['Closing_Date']);
        $data['Closing_Date'] = $date->format('Y-m-d');
        
        // API request
        try {            
            (new ZohoAuth())->init();                 
            $result = ZohoRecords::create("Deals", $data);
            
            return response($result, 200);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 400);
        }    
    }
}
