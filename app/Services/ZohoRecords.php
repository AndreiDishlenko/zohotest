<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ZohoRecords {
    public static function get(String $module, Array $fields): Array {
        $zoho = new Zoho();
        $token = ZohoAuth::token();            
        $headers = [
            'Authorization' => "Zoho-oauthtoken {$token->token}"
        ];
        $params = [
            'fields' => implode(",", $fields)
        ];

        $response = Http::withHeaders($headers)
            ->asForm()
            ->get($zoho->apiPath()."/$module", $params);

        if ($response->failed()) 
            return [];
        
        return $response->json()['data'];
    }

    public static function create(String $module, Array $data) {
        $zoho = new Zoho();
        $token = ZohoAuth::token();
        $headers = [
            'Authorization' => "Zoho-oauthtoken {$token->token}"
        ];
        $payload = [
            "data" => [$data]
        ];

        $response = Http::withHeaders($headers)
            ->withBody(json_encode($payload))
            ->post($zoho->apiPath()."/$module", $data);

        if ($response->failed()) 
            return [];

        return $response->json();
    }
}