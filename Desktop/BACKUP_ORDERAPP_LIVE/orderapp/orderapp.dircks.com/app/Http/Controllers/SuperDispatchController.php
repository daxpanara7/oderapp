<?php

namespace App\Http\Controllers;

use App\CodeMaintain;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class SuperDispatchController extends Controller
{
    public $superDispatchClientId;
    public $superDispatchClientIdUsername;
    public $superDispatchClientSecretPassword;
    public $superDispatchClientSecret;
    
    
    public $base64Token;
    public $base64TokenInternal;
    public $baseUrl;
    public function __construct()
    {
        $this->superDispatchClientId = Config::get('app.SUPER_DISPATCH_CLIENT_ID');
        $this->superDispatchClientSecret =  Config::get('app.SUPER_DISPATCH_CLIENT_SECRET');
        
        $this->superDispatchClientIdUsername = Config::get('app.SUPER_DISPATCH_CLIENT_ID_USERNAME');
        $this->superDispatchClientSecretPassword =  Config::get('app.SUPER_DISPATCH_CLIENT_SECRET_PASSWORD');
        
        $this->base64Token =  base64_encode($this->superDispatchClientId . ":" .  $this->superDispatchClientSecret);
        $this->base64TokenInternal =  base64_encode($this->superDispatchClientIdUsername . ":" .  $this->superDispatchClientSecretPassword);
        $this->baseUrl = 'https://api.shipper.superdispatch.com/';
    }

    public function authToken()
    {
        $postUrl = 'oauth/token?grant_type=client_credentials';
        $apiUrl = $this->baseUrl . $postUrl;
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $apiUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Basic ' . $this->base64Token
            ),
        ));
        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($httpCode >= 200 && $httpCode < 300) {
            // Successful response
            $responseData = json_decode($response, true);
        } else {
            // Error response
            $responseData = json_decode($response, true);
        }
        return $responseData;
    }
    
     public function authTokenInternal(){
        $token = CodeMaintain::select('token_internal')->first();
        if ($token->token_internal) {
            $token = $token->token_internal;
            list($header, $payload, $signature) = explode(".", $token);
            $tokenDecoded = json_decode(base64_decode($payload));
            $startTime = Carbon::parse($tokenDecoded->exp);
            $finishTime = Carbon::parse(Carbon::now()->timestamp);
            $totalDuration = $finishTime->diffInDays($startTime);
            if($totalDuration <= 1){
                $responseData = $this->InterToeknCall();
            }else{
                $responseData['token'] = $token;
            }
        } else {
            $responseData = $this->InterToeknCall();
        }
        return $responseData;
    }
    
     public function InterToeknCall(){
        $postUrl = 'login';
            $apiUrl = $this->baseUrl . $postUrl;
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $apiUrl,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => array('username' => 'auto@dircks.com', 'password' => 'Mohave1!'),
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Basic bWFucHJpdEB4ZWxlbnRvci5jb206RGlyY2tzMjAyMyE=',
                    'Cookie: __cf_bm=9CY6nbAAJYOVHdBKFBP9k4czLFpkwM5e3nb0uA__T5s-1709096920-1.0-AXeCdUOr9WvpkbL+PSP5e7elFCo/4Id7c9TzFVtQ7qUQGLdlZscC14w6K7CfgRVRL6JQsU1WZbudCe5XkJL1B94='
                ),
            ));
            $response = curl_exec($curl);
            $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            curl_close($curl);

            if ($httpCode >= 200 && $httpCode < 300) {
                // Successful response
                $responseData = json_decode($response, true);
                CodeMaintain::where('id','1')->update(["token_internal"=>trim($responseData['token'])]);
            } else {
                // Error response
                $responseData = json_decode($response, true);
            }
            return $responseData;
    }

    public function getApiCall($postUrl, $postFiled,$isInternal=false)
    {
        $apiUrl = $this->baseUrl . $postUrl;
        if (!empty($postFiled)) {
            $QueryParameters = '?';
            $i = 1;
            foreach ($postFiled as $key => $value) {
                $QueryParameters .= (($i == 1) ? '' : '&') . $key . '=' . urlencode($value);
                $i++;
            }
            $apiUrl .= $QueryParameters;
        }
        if($isInternal){
            $tokenObj = $this->authTokenInternal();
        }else{
            $tokenObj = $this->authToken();
        }
        $token = (!$isInternal) ? $tokenObj['access_token'] : $tokenObj['token'];
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => $apiUrl,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
          CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer '.$token
          ),
        ));
        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        if ($httpCode >= 200 && $httpCode < 300) {
            // Successful response
            $responseData = json_decode($response, true);
        } else {
            // Error response
            $responseData = json_decode($response, true);
        }
        return $responseData;
    }

    public function postApiCallArray($postUrl, $postFiled,$isInternal=false)
    {
        if($isInternal){
            $tokenObj = $this->authTokenInternal();
        }else{
            $tokenObj = $this->authToken();
        }
        $token = (!$isInternal) ? $tokenObj['access_token'] : $tokenObj['token'];
        $apiUrl = $this->baseUrl . $postUrl;
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $apiUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $postFiled,
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . $token
            ),
        ));
        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($httpCode >= 200 && $httpCode < 300) {
            // Successful response
            $responseData = json_decode($response, true);
        } else {
            // Error response
            $responseData = json_decode($response, true);
        }
        return $responseData;
    }

    public function postApiCallJson($postUrl, $postFiled, $isInternal = false)
    {
        if($isInternal){
            $tokenObj = $this->authTokenInternal();
        }else{
            $tokenObj = $this->authToken();
        }
        $token = (!$isInternal) ? $tokenObj['access_token'] : $tokenObj['token'];
        $apiUrl = $this->baseUrl . $postUrl;
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $apiUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode((array)$postFiled),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Bearer ' . $token
            ),
        ));
        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($httpCode >= 200 && $httpCode < 300) {
            // Successful response
            $responseData = json_decode($response, true);
        } else {
            // Error response
            $responseData = json_decode($response, true);
        }
        return $responseData;
    }
}
