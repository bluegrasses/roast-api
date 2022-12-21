<?php

namespace App\utils;

use GuzzleHttp\Client;

class GaodeMaps
{
    //通过真实地址获取对应的经纬度
    public static function geocodeAddress($address, $city, $state)
    {
        // 省、市、区、详细地址
        $address = urlencode($state . $city . $address);
        $apiKey = config('services.gaode.ws_api_key');
        $url = 'https://restapi.amap.com/v3/geocode/geo?address=' . $address . '&key=' . $apiKey;

        $client = new Client();
        $response = $client->get($url)->getBody();
//        将json字符串转为对象
        $geocodeData = json_decode($response);
        // 初始化地理编码位置
        $coordinates['lat'] = null;
        $coordinates['lng'] = null;
        // 如果响应数据不为空则解析出经纬度
        if (!empty($geocodeData)
            && $geocodeData->status  // 0 表示失败，1 表示成功
            && isset($geocodeData->geocodes)
            && isset($geocodeData->geocodes[0])) {
//            php list函数 将数据值赋值给变量
            list($latitude, $longitude) = explode(',', $geocodeData->geocodes[0]->location);
            $coordinates['lat'] = $latitude;  // 经度
            $coordinates['lng'] = $longitude; // 纬度
        }
        // 返回地理编码位置数据
        return $coordinates;
    }
}
