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
                    "Accept: application/json",
                    "Authorization: Token " . $this->getConfig("apiKey", "Authorization"),
//                    "X-Secret: " . $this->getConfig("secretKey", "Authorization"),
                ],
                "content" => ""
            ]
        ];
    }

    public function getAddressInfo($address)
    {
        /*$requestData = [
            "structure" => [$this->getConfig("address", "URLs")],
            "data" => [[$address]]
        ];*/

        $requestData = [
            "query" => $address,
            "count" => 1
        ];

        return $this->sendRequest($this->getConfig("address", "URLs"), array_merge($this->defaultRequest, $requestData));
    }

    protected function sendRequest($page, $requestData)
    {

        $this->defaultOptions["http"]["content"] = json_encode($requestData);

        $context = stream_context_create($this->defaultOptions);

        $result = file_get_contents($this->getConfig("defaultURL", "URLs") . $page, false, $context);

        return json_decode($result);
    }

    public function getConfig($key = "", $section = "")
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