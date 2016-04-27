<?php
namespace App\Services\Facebook;

use Facebook\Http\GraphRawResponse;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\HttpClients\FacebookHttpClientInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\RequestException;

class GuzzleHttpClient implements FacebookHttpClientInterface
{
    /**
     * @var \GuzzleHttp\Client The Guzzle client.
     */
    private $guzzleClient;
    /**
     * @param \GuzzleHttp\Client The Guzzle client.
     */
    public function __construct(Client $guzzleClient = null)
    {
        $this->guzzleClient = $guzzleClient ?: new Client();
    }
    /**
     * @inheritdoc
     */
    public function send($url, $method, $body, array $headers, $timeOut)
    {
        $request = new Request($method, $url, $headers, $body);
        $options = [
            'timeout' => $timeOut,
            'connect_timeout' => 10,
            'verify' =>  base_path('/vendor/facebook/php-sdk-v4/src/Facebook/HttpClients/certs/DigiCertHighAssuranceEVRootCA.pem'),
        ];
        try {
            $response = $this->guzzleClient->send($request,$options);
        } catch (RequestException $e) {
            throw new FacebookSDKException($e->getMessage(), $e->getCode());
        }
        $httpStatusCode = $response->getStatusCode();
        $responseHeaders = $response->getHeaders();
        foreach ($responseHeaders as $name => $values) {
            $responseHeaders[$name] = implode(', ', $values);
        }
        $responseBody = $response->getBody()->getContents();
        return new GraphRawResponse($responseHeaders, $responseBody, $httpStatusCode);
    }
}