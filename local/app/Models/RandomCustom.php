<?php
namespace Models;

use Bitrix\Main\Localization\Loc;
	

/**
 * Class RandomCustom
 *
 * @package Models
*/

class RandomCustom
{
  
 
    const url = 'https://api.random.org/json-rpc/1/invoke';
    const apiKey = "e09eee81-8e53-49ab-894a-3d428da5dbbc";
 
    /*public function __construct()
    {
         
             
        
    }*/

    public static function generateIntegers($kol, $min, $max)
    {  
        $seans = curl_init($url);
        $request = [
           "jsonrpc" => "2.0",
           "method" => "generateIntegers",
           "id" => mt_rand()
        ];
        $request['params'] = [
           "apiKey" => self::apiKey,
           "n" => $kol,
           "min" =>$min,
           "max" =>$max,
           "replacement" =>true
        ];
        $headers = [
          'Content-Type: application/json',
          'Accept: application/json'
        ];

        $optionsSet = curl_setopt_array($seans, [
            CURLOPT_URL => self::url,
            CURLOPT_HEADER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_USERAGENT => 'JSON-RPC Random.org PHP Client',
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_POSTFIELDS => json_encode($request)
        ]);
        if (!$optionsSet) {
            throw new \Exception('Cannot set curl options');
        }

        $res = curl_exec( $seans);
        curl_close( $seans);
 
        $arrr = json_decode($res,  true);
     
      $result_arr = [];
      foreach ($arrr as $key => $item) {
       
        foreach ($item as $key2 => $item2) {
           foreach ($item2 as $key3 => $item3) {
             if ($key3 == 'data'){
                $result_arr = $item3;
             }
           }
         
        }
     }
     return $result_arr;
   }
}