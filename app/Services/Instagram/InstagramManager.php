<?php
namespace App\Services\Instagram;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class InstagramManager
{
    private $clientId;
    private $clientSecret;

    /**
     * @var Client
     */
    private $httpClient;

    public function __construct()
    {
        $this->clientId = env('INSTAGRAM_CLIENT_ID');
        $this->clientSecret = env('INSTAGRAM_CLIENT_SECRET');
        $this->httpClient = new Client([
            'base_uri' => "https://api.instagram.com/v1/",
        ]);
    }

    public function getLoginUrl($callback)
    {
        return "https://api.instagram.com/oauth/authorize/?client_id=".$this->clientId."&redirect_uri=".$callback."&response_type=code&scope=public_content";
    }

    public function handleCallback($callback)
    {
        $code = $_REQUEST['code'];

        try
        {
            $response = $this->httpClient->post("https://api.instagram.com/oauth/access_token", [
                'form_params' => [
                    'client_id'     => $this->clientId,
                    'client_secret' => $this->clientSecret,
                    'grant_type'    => "authorization_code",
                    'redirect_uri'  => $callback,
                    'code'          => $code,
                ]
            ]);

            $content = \GuzzleHttp\json_decode($response->getBody()->getContents());

            return [
                'access_token' => $content->access_token,
                'user'         => $content->user->id,
            ];
        } catch (ClientException $e)
        {
            \Log::warning("Instagram callback", [
                'message' => $e->getMessage(),
                'code'    => $e->getCode(),
            ]);

            return false;
        }
    }

    private function get($endpoint, $accessToken)
    {
        try
        {
            $endpoint = stripos($endpoint, '?') === false ? $endpoint . "?" : $endpoint . "&";
            $endpoint .= "access_token=" . $accessToken;
            $response = $this->httpClient->get($endpoint);
            return \GuzzleHttp\json_decode($response->getBody()->getContents());
        } catch (ClientException $e)
        {
            \Log::warning("Instagram get error", [
                'message' => $e->getMessage(),
                'code'    => $e->getCode(),
            ]);

            return false;
        }
    }
}