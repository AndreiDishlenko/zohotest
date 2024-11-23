<?php

namespace App\Services;

use DateTime;

class ZohoToken {
    public $token = '';
    public $expire;

    public function __construct() {     
        $this->expire = new DateTime();       
    }

    public function token($token) {
        $this->token = $token;
        return $this;
    }

    public function expire($datetime) {
        // Datetime check
        if ($datetime instanceof DateTime)
            $this->expire = $datetime;
    
        // Timestamp check
        if ( is_int($datetime) )
            $this->expire = (new DateTime())->setTimestamp($datetime);

        return $this;
    }

    public function expires_in($time_in_seconds) {
        $expire = (new DateTime())->modify('+'.$time_in_seconds.' seconds');
        $this->expire = $expire;
        return $this;
    }

    public function is_expired() {
        if ( empty($this->expire) )
            return true;

        if ( (new DateTime) >= $this->expire )
            return true;

        return false;            
    }

    public function is_empty() {
        if ( empty($this->token) || empty($this->expire) )
            return true;

        return false;
    }
}