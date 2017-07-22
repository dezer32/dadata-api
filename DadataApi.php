<?php

namespace DadataApi;


class DadataApi
{
    protected $config;

    protected $defaultOptions;
    protected $defaultRequest;

    /**
     * DadataApi constructor.
     */
    public function __construct()
    {
        $this->defaultRequest = [];

        $this->defaultOptions = [
            "http" => [
                "method" => "POST",
                "header" => [
                    "Content-Type: application/json",
                    "Authorization: Token " . $this->getConfig("apiKey", "Authorization"),
                    "X-Secret: " . $this->getConfig("secretKey", "Authorization")
                ],
                "content" => ""
            ]
        ];
    }

    public function getAddressInfo($address)
    {
        $requestData = [
            "structure" => [$this->getConfig("address", "URLs")],
            "data" => [[$address]]
        ];
        return $this->sendRequest(array_merge($this->defaultRequest, $requestData));
    }

    protected function sendRequest($requestData)
    {

        $this->defaultOptions["http"]["content"] = json_encode($requestData);

        $context = stream_context_create($this->defaultOptions);

        $result = file_get_contents("https://dadata.ru/api/v2/clean", false, $context);

        return json_decode($result);
    }

    protected function getConfig($key = "", $section = "")
    {
        if (empty($this->config)) {
            $this->config = parse_ini_file("config.ini", true);
        }

        $result = $this->config;

        if (!empty($section)) {
            $result = $result[$section];
        }

        if (!empty($key)) {
            $result = $result[$key];
        }

        if (!empty ($result)) {
            return $result;
        } else {
            return false;
        }
    }
}