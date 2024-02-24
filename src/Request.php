<?php

namespace SmartPRO\Technology;

class Request
{

    protected static ?string $error = null;
    protected static ?array $status = null;

    private static function Request(string $method, string $url, ?array $options = null)
    {
        $curl = curl_init($url);
        $config = array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_SSL_VERIFYPEER => ($options["ssl"] ?? false),
            CURLOPT_SSL_VERIFYHOST => ($options["ssl"] ?? false),
            CURLOPT_TIMEOUT => ($options["timeout"] ?? 180),
            CURLOPT_CONNECTTIMEOUT => 30,
            CURLOPT_HTTPHEADER => ($options["headers"] ?? []),
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_POSTFIELDS => ($options["body"] ?? "")
        );

        if (!empty($options["auth"])) {
            $parse = explode(":", $options["auth"]);
            if (!empty($parse[0]) and !empty($parse[1])) {
                $config[CURLOPT_USERNAME] = $parse[0];
                $config[CURLOPT_USERPWD] = $parse[1];
            }
        }

        if (!empty($options["proxy"])) {
            $parseProxy = explode("@", $options["proxy"]);
            if (!empty($parseProxy[0])) {
                $config[CURLOPT_PROXY] = $parseProxy[0];
            }
            if (!empty($parseProxy[1])) {
                $config[CURLOPT_PROXYUSERPWD] = $parseProxy[1];
            }
        }

        curl_setopt_array($curl, $config);
        $result = curl_exec($curl);
        self::$status = curl_getinfo($curl);
        self::$error = curl_error($curl);
        curl_close($curl);
        $json = @json_decode($result);
        if ($json === false or $json === null) {
            return $result;
        }
        return $json;
    }

    public static function DELETE(string $url, ?array $options = null)
    {
        return self::Request("DELETE", $url, $options);
    }

    public static function PUT(string $url, ?array $options = null)
    {
        return self::Request("PUT", $url, $options);
    }

    public static function GET(string $url, ?array $options = null)
    {
        return self::Request("GET", $url, $options);
    }

    public static function POST(string $url, ?array $options = null)
    {
        return self::Request("POST", $url, $options);
    }

    public static function status(): ?array
    {
        return self::$status;
    }

    public static function error(): ?string
    {
        return self::$error;
    }
}