<?php

namespace App\Http\Controllers\Api;

use DateTime;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Zoho;
use App\Http\Controllers\Controller;

class DealsApiController extends Controller
{
    // Deal_Name,Account_Name,Created_Time,Stage
    public static function createRecord(Request $request) {
        $data = [];
        
        foreach($request->getPayload() as $key=>$value) 
            $data[$key] = $value;
        
        $data['Created_Time'] = new DateTime($data['Created_Time']);

        try {
            Zoho::init();
            $result = Zoho::createRecords("Deals", $data);
            
            return response($result, 200);
        } catch (Exception $e) {
            dd($e);
            return response()->json($e->getMessage(), 400);
        }    
    }
}
