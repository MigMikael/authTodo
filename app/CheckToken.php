<?php
/**
 * Created by PhpStorm.
 * User: Mig
 * Date: 8/27/2016
 * Time: 20:35
 */

namespace App;
use Log;

class CheckToken
{
    public static function isExpired($payload, $expireTime) {
        //$tokenExpireTime = $payload['exp'];
        $tokenIat = $payload['iat'];
        $currentTime = time();

        //Log::info('#### Token Expire Time '.date("Y-m-d H:i:s", $tokenExpireTime));
        //Log::info('#### The Current Time '.Carbon::now());

        Log::info('#### Token iat '.$tokenIat);
        Log::info('#### Time  now '.$currentTime);

        $time = ($currentTime - $tokenIat)/60;
        Log::info('#### Diff Time '.$time);

        if($time > $expireTime){
            Log::info('#### Todo | Token is expired');
            return true;
        }else{
            Log::info('#### Todo | Token is valid');
            return false;
        }
    }
}