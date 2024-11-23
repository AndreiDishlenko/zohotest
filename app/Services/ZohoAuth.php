<?php

namespace App\Services;

use App\Services\Zoho;
use App\Models\ZohoTokens;
use Illuminate\Support\Facades\Http;

class ZohoAuth {
    public function __construct() {       
    }

    // Use this function before do any actions with Zoho API
    public function init(): ZohoToken {       
        $token = ZohoTokens::lastToken();

        // Check expired
        if ( $token->is_empty() || $token->is_expired() ) {
            $token = $this->getNewAccessToken();
            if ( !$token->is_empty() )                    
                ZohoTokens::putToken($token);
        }       

        return $token;
    }

    public static function token() {
        return $token = ZohoTokens::lastToken();
    }

    // Get new token from Zoho Auth API using refreshToken
    public function getNewAccessToken() {
        $zoho = new Zoho();

        $body = [            
            "client_id"     => $zoho->client_id,
            "client_secret" => $zoho->client_secret,
            "refresh_token" => $zoho->refresh_token,
            "grant_type"    => "refresh_token"
        ];

        $result = new ZohoToken();

        try {
            $response = Http::asForm()->post($zoho->accountUrl, $body);

            if ($response->successful())                   
                return $result
                    ->token($response->json()['access_token'])
                    ->expires_in($response->json()['expires_in']);
            else 
                return $result;
        } catch (\Exception $e) {
            return $result;
        }

    }

}