<?php namespace Mergetta\Http;

class HttpClient {

    const M_POST = 'post';

    private $headers;
    private $url;
    private $disableSsl;

    private static $curl = null;

    public function __construct($url = false)
    {
        if (is_null(self::$curl)) {
            self::$curl = curl_init();
            curl_setopt(self::$curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt(self::$curl, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; Mergetta Http-client; '.php_uname('s').'; PHP/'.phpversion().')');
        }

        $this->headers      = array();
        $this->url          = $url;
        $this->disableSsl   = false;
    }

    private function clear()
    {
        $this->headers      = array();
        $this->disableSsl   = false;
    }

    public function setHeaders(array $headers)
    {
        $this->headers = $headers;
        return $this;
    }

    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    public function disableSsl()
    {
        $this->disableSsl = true;
        return $this;
    }

    public function post($request)
    {
        curl_setopt(self::$curl, CURLOPT_POSTFIELDS, $request);
        return $this->execute(self::M_POST);
    }

    protected function execute($method)
    {
        curl_setopt(self::$curl, CURLOPT_URL, $this->url);

        $headers = array();
        foreach ($this->headers as $key => $value) {
            $headers[] = is_numeric($key)
                ? $value
                : "{$key}: {$value}";
        }
        curl_setopt(self::$curl, CURLOPT_HTTPHEADER, $headers);

        if ($this->disableSsl) {
            curl_setopt(self::$curl, CURLOPT_SSL_VERIFYPEER, false);
        }

        $response = curl_exec(self::$curl);
        if ($response === false) {
            throw new TransportException(curl_error(self::$curl), curl_errno(self::$curl));
        }

        $this->clear();

        return $response;
    }

    /**
     * @param array $params
     * @return string
     */
    public static function buildQuery(array $params)
    {
        return http_build_query($params, '', '&');
    }
} 