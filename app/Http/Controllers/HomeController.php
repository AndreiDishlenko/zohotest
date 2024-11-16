<?php

namespace App\Http\Controllers;

use view;
use Exception;

use App\Exceptions;
use com\zoho\crm\api\tags\Tag;
use com\zoho\api\logger\Levels;
use com\zoho\crm\api\HeaderMap;
use com\zoho\crm\api\util\Choice;
use com\zoho\crm\api\ParameterMap;
use com\zoho\crm\api\record\Leads;
use com\zoho\api\logger\LogBuilder;
use com\zoho\crm\api\record\Record;
use com\zoho\crm\api\dc\EUDataCenter;
use com\zoho\crm\api\SDKConfigBuilder;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\record\BodyWrapper;
use App\Http\Controllers\DealsController;
use com\zoho\crm\api\record\APIException;
use com\zoho\crm\api\record\ActionWrapper;
use com\zoho\api\authenticator\OAuthBuilder;
// use com\zoho\crm\api\record\GetRecordsParam;
use com\zoho\crm\api\record\SuccessResponse;
use com\zoho\crm\api\record\GetRecordsHeader;
use com\zoho\crm\api\record\RecordOperations;
use com\zoho\api\authenticator\store\FileStore;

// use com\zoho\crm\api\record\Deals;
// use com\zoho\crm\api\record\Accounts;

class HomeController extends Controller
{
    public function index() {
        // var_dump("aaa");

        // try {
            // Zoho::init();  
            // DealsController::getRecords();          

            // $this->createRecords("Leads");          
        // } catch (Exception $e) {
        //     dd($e);
            // dd("Code: {$e->getCode()}, Message: {$e->getMessage()}");
        // }

        
        // $this->createRecords("Leads");

        // Zoho::init();

        // $fieldNames = array("Deal_Name", "Account_Name", "Created_Time", "Stage");
        // $response = Zoho::getRecords("Deals", $fieldNames); //$this->getRecords("Deals", $fieldNames);

        // $fieldNames = array("Account_Name");
        // $response = Zoho::getRecords("Accounts", $fieldNames); //$this->getRecords("Deals", $fieldNames);

        // dd($response->getObject());
        // Zoho::getRecords("Accounts", $fieldNames);

        return view("home");
    }

    public static function init()
    {
        $environment = EUDataCenter::PRODUCTION();
        $token = (new OAuthBuilder())
            ->clientId( env("ZOHO_CLIENT_ID") )
            ->clientSecret( env("ZOHO_CLIENT_SECRET") )
            ->refreshToken( env("ZOHO_REFRESH_TOKEN") )
            ->build();

        (new InitializeBuilder())
            ->environment($environment)
            ->token($token)
            ->initialize();
    }

    public static function getRecords($moduleAPIName, $fieldNames) {
        try {
            $paramInstance = new ParameterMap();
            foreach ($fieldNames as $fieldName) {
                $paramInstance->add(GetRecordsParam::fields(), $fieldName);
            }  
            
            $headerInstance = new HeaderMap();
            $ifmodifiedsince = date_create("2020-06-02T11:03:06+05:30")->setTimezone(new \DateTimeZone(date_default_timezone_get()));
            $headerInstance->add(GetRecordsHeader::IfModifiedSince(), $ifmodifiedsince);
            $recordOperations = new RecordOperations($moduleAPIName);
            $response = $recordOperations->getRecords($paramInstance, $headerInstance);

            return $response;
        } catch (\Exception $e) {
            print_r($e);
        }
    }


    

    
}
