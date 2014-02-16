<?php namespace Mergetta\Http;

/**
 * Class Http
 * @author Karabutin Alex <karabutinalex@gmail.com>
 * @package Mergetta\Http
 */
class Http {

    /**
     * @param string $url
     * @param array $params
     * @return string
     * @throws HttpException
     */
    public static function get($url, array $params = array())
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($curl);

        $info = curl_getinfo($curl);
        if ($info['http_code'] >= 400) {
            throw new HttpException('', $info['http_code']);
        }

        curl_close($curl);

        return $response;
    }
} 