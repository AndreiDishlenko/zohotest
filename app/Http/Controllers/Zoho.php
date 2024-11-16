<?php

namespace App\Http\Controllers;

use com\zoho\crm\api\HeaderMap;
use com\zoho\crm\api\ParameterMap;
use com\zoho\crm\api\record\Field;
use com\zoho\crm\api\record\Record;
use com\zoho\crm\api\dc\EUDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\record\BodyWrapper;
use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\record\GetRecordsParam;
use com\zoho\crm\api\record\RecordOperations;

use com\zoho\crm\api\record\ActionWrapper;
use com\zoho\crm\api\record\APIException;
use com\zoho\crm\api\record\SuccessResponse;
use com\zoho\crm\api\util\Choice;

class Zoho extends Controller
{
    public static function init() {
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
            
            $recordOperations = new RecordOperations($moduleAPIName);
            $response = $recordOperations->getRecords($paramInstance, $headerInstance);

            $result = [];
            foreach ($response->getObject()->getData() as $responseItem) {
                    array_push($result, $responseItem->getKeyValues() );
            }

            return $result;
        } catch (\Exception $e) {
            print_r($e);
        }
    }

    public static function createRecords(String $moduleAPIName, array $data) {
        $records = array();

        $record1 = new Record();
        foreach($data as $key => $value) {            
            $record1->addFieldValue(new Field($key), $value);
        }

        // $record1->addFieldValue(Leads::City(), "City");
        // $record1->addFieldValue(Leads::Company(), "company");
        // $record1->addFieldValue(Leads::LastName(), "FROm PHP");
        // $record1->addFieldValue(Leads::FirstName(), "First Name");
        // $record1->addFieldValue(Leads::Email(), "abc@zoho.com");

        // $tagList = array();
        //     $tag = new Tag();
        //     $tag->setName("TestTask");
        //     array_push($tagList, $tag);

        // $record1->setTag($tagList);

        array_push($records, $record1);

        $bodyWrapper = new BodyWrapper();
        $bodyWrapper->setData($records);

        $headerInstance = new HeaderMap();
        
        $recordOperations = new RecordOperations($moduleAPIName);
        
        $response = $recordOperations->createRecords($bodyWrapper, $headerInstance);
        // dd("ff", $response);
        $result = [];
        if ($response != null) {
            $result["statuscode"] = $response->getStatusCode();
            if ($response->isExpected()) {
                $actionHandler = $response->getObject();
                if ($actionHandler instanceof ActionWrapper) {
                    $actionWrapper = $actionHandler;
                    $actionResponses = $actionWrapper->getData();

                    $result['status']=$actionResponses[0]->getStatus()->getValue();
                    $result['code']=$actionResponses[0]->getCode()->getValue();
                    $result['details']=$actionResponses[0]->getDetails();
                    $result['message']=$actionResponses[0]->getMessage() instanceof Choice ? $actionResponses[0]->getMessage()->getValue() : $actionResponses[0]->getMessage();
                    
                    // foreach ($actionResponses as $actionResponse) {
                    //     array_push($result, [
                    //         'status'   => $actionResponse->getStatus()->getValue(),
                    //         'code'     => $actionResponse->getCode()->getValue(),
                    //         'details'  => $actionResponse->getDetails(),
                    //         'message'  => $actionResponse->getMessage() instanceof Choice ? $actionResponse->getMessage()->getValue() : $actionResponse->getMessage()
                    //     ]);
                    // }
                }
                else if ($actionHandler instanceof APIException) {
                    $exception = $actionHandler;

                    $result['status']   = $exception->getStatus()->getValue();
                    $result['code']     = $exception->getCode()->getValue();
                    $result['details']  = $exception->getDetails();
                    $result['message']  = $exception->getMessage() instanceof Choice ? $exception->getMessage()->getValue() : $exception->getMessage();

                    // array_push($result, [
                    //     'status'   => $exception->getStatus()->getValue(),
                    //     'code'     => $exception->getCode()->getValue(),
                    //     'details'  => $exception->getDetails(),
                    //     'message'  => $exception->getMessage() instanceof Choice ? $exception->getMessage()->getValue() : $exception->getMessage()
                    // ]);
                }
            } else {
                $result = $response;
            }
        }

        return $result;
    }
}