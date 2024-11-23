<?php

namespace App\Services;

use App\Models\ZohoTokens;
use Illuminate\Support\Facades\Http;

class Zoho {
    public $serverUrl = "https://www.zohoapis.eu";
    public $apiPath = "/crm/v7";
    public $accountUrl = "https://accounts.zoho.eu/oauth/v2/token";

    public $client_id;
    public $client_secret;
    public $refresh_token;

    public function __construct() {       
        $this->client_id = env("ZOHO_CLIENT_ID");
        $this->client_secret = env("ZOHO_CLIENT_SECRET");
        $this->refresh_token = env("ZOHO_REFRESH_TOKEN");        
    }

    public function apiPath() {
        return $this->serverUrl . $this->apiPath;    
    }

}