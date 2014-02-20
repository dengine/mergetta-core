<?php namespace Mergetta\Http;

/**
 * Class Http
 * @author Karabutin Alex <karabutinalex@gmail.com>
 * @package Mergetta\Http
 */
class Http {

    private static $curl = null;

    /**
     * @param string $url
     * @param array $params
     * @return string
     * @throws HttpException
     */
    public static function get($url, array $params = array())
    {
        if (is_null(self::$curl)) {
            self::$curl = curl_init();
            curl_setopt(self::$curl, CURLOPT_RETURNTRANSFER, true);
        }

        curl_setopt(self::$curl, CURLOPT_URL, $url);
        curl_setopt(self::$curl, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec(self::$curl);

        if ($response === false) {
            throw new TransportException(curl_error(self::$curl), curl_errno(self::$curl));
        }

        $info = curl_getinfo(self::$curl);
        if ($info['http_code'] >= 400) {
            throw new HttpException('', $info['http_code']);
        }

        return $response;
    }
} 