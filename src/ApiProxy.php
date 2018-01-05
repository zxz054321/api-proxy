<?php

namespace AbelHalo\ApiProxy;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;

class ApiProxy
{
    const RETURN_AS_JSON_RESPONSE = 'json';
    const RETURN_AS_ARRAY = 'array';
    const RETURN_AS_OBJECT = 'object';

    /**
     * @var Client
     */
    protected $client;
    protected $returnAs;
    protected $options = [];

    public function __construct($baseUri)
    {
        $this->client   = new Client(['base_uri' => $baseUri]);
        $this->returnAs = static::RETURN_AS_JSON_RESPONSE;
    }

    public function authorizationHeader($value)
    {
        $this->options['headers']['Authorization'] = $value;
    }

    public function get($uri, $parameters = [])
    {
        $response = $this->request('GET', $uri, ['query' => $parameters]);

        return $this->respond($response);
    }

    public function post($uri, $data = [])
    {
        $response = $this->request('POST', $uri, ['json' => $data]);

        return $this->respond($response);
    }

    public function patch($uri, $data = [])
    {
        $response = $this->request('PATCH', $uri, ['json' => $data]);

        return $this->respond($response);
    }

    public function delete($uri)
    {
        $response = $this->request('DELETE', $uri);

        return $this->respond($response);
    }

    public function setReturnAs($returnAs)
    {
        $this->returnAs = $returnAs;

        return $this;
    }

    protected function request($method, $uri, array $options = [])
    {
        return $this->client->request($method, $uri, array_merge($this->options, $options));
    }

    protected function respond(ResponseInterface $response)
    {
        if (static::RETURN_AS_JSON_RESPONSE == $this->returnAs) {
            return $this->jsonResponse($response);
        }

        return $this->decodeJsonData($response, static::RETURN_AS_ARRAY == $this->returnAs);
    }

    protected function jsonResponse(ResponseInterface $response)
    {
        return response()->json(
            $this->decodeJsonData($response)
        );
    }

    protected function decodeJsonData(ResponseInterface $response, $assoc = false)
    {
        return json_decode($response->getBody(), $assoc);
    }
}
