<?php

namespace App\Http\Controllers\Api;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Zoho;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;


class AccountsApiController extends Controller
{
    // Account_Name,Website,Phone
    public static function getRecords() {
        $fieldNames = array("Account_Name", "Website", "Phone");
        try {
            Zoho::init();
            $result = Zoho::getRecords("Accounts", $fieldNames);
            return response($result, 200);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 400);
        }        
    }

    public static function createRecord(Request $request) {
        $data = [];
        
        foreach($request->getPayload() as $key=>$value) 
            $data[$key] = $value;

        try {
            Zoho::init();
            $result = Zoho::createRecords("Accounts", $data);
            return response($result, 200);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 400);
        }    
    }
}
