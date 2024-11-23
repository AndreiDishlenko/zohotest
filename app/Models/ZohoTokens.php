<?php

namespace App\Models;

use App\Services\ZohoToken;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ZohoTokens extends Model
{
    protected $guarded = [];
    public $timestamps = false;

    use HasFactory;

    public static function putToken(ZohoToken $token) {
        $result = ZohoTokens::create([
            'access_token'  => $token->token,
            'expire'        => $token->expire->getTimestamp()
        ]);

        return true;
    }

    
    public static function lastToken(): ZohoToken {
        $token_record = ZohoTokens::orderBy('expire', 'desc')->first();
        
        if ( empty($token_record) )
            return new ZohoToken();

        return (new ZohoToken())
            ->token($token_record->access_token)
            ->expire($token_record->expire);
    }
}
