<?php

namespace SmartPRO\Technology;

/**
 * @package HttpRequest
 */
class HttpRequest
{
    /*** @var string|null */
    protected ?string $url = null;
    /*** @var array|null */
    protected ?array $headers = [];
    /*** @var string|null */
    protected ?string $proxy = null;
    /*** @var int|null */
    protected ?int $timeout = 15;
    /*** @var string|null */
    protected ?string $body = null;
    /*** @var int|null */
    protected ?int $http_status = null;
    /*** @var int|null */
    protected ?int $json = null;
    /*** @var string|null */
    protected ?string $error = null;

    /**
     * @param $url
     * @param string $method
     * @return bool|mixed|string
     */
    public function send($url, string $method = "GET")
    {
        $url = $this->url . $url;
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => trim($url),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $this->headers,
            CURLOPT_TIMEOUT => $this->timeout,
            CURLOPT_POSTFIELDS => $this->body,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_VERBOSE => 1
        ]);
        if (!empty($this->proxy)) {
            $proxy = explode("@", $this->proxy);
            if (!empty($proxy['0'])) {
                curl_setopt($ch, CURLOPT_PROXY, $proxy['0']);
            }
            if (!empty($proxy[1])) {
                curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxy['1']);
            }
        }
        $results = curl_exec($ch);
        curl_close($ch);
        $requestStatus = curl_getinfo($ch);
        $error = curl_error($ch);
        if ($error) {
            $this->error = $error;
        }
        $this->http_status = $requestStatus['http_code'];
        if ($this->json) {
            return json_decode($results);
        }
        return $results;
    }

    /**
     * @return $this
     */
    public function json(): HttpRequest
    {
        $this->json = true;
        return $this;
    }

    /**
     * @param string $url
     * @return $this
     */
    public function urlBase(string $url): HttpRequest
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @param $body
     * @return $this
     */
    public function body($body): HttpRequest
    {
        $body = is_array($body) ? http_build_query($body) : $body;
        $this->body = $body;
        return $this;
    }

    /**
     * @param string|null $proxy
     * @return $this
     */
    public function proxy(?string $proxy): HttpRequest
    {
        $this->proxy = $proxy;
        return $this;
    }

    /**
     * @param array|null $headers
     * @return $this
     */
    public function headers(?array $headers): HttpRequest
    {
        $this->headers = $headers;
        return $this;
    }

    /**
     * @param int|null $timeout
     * @return $this
     */
    public function timeout(?int $timeout): HttpRequest
    {
        $this->timeout = $timeout;
        return $this;
    }

    /**
     * @return string|null
     */
    public function error(): ?string
    {
        return $this->error;
    }
}