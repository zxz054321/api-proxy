<?php

namespace AbelHalo\ApiProxy;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;

class ApiProxy
{
    /**
     * @var Client
     */
    protected $client;

    public function __construct($baseUri)
    {
        $this->client = new Client(['base_uri' => $baseUri]);
    }

    public function get($uri, $parameters = [])
    {
        $response = $this->client->get($uri, ['query' => $parameters]);

        return $this->jsonResponse($response);
    }

    public function post($uri, $data = [])
    {
        $response = $this->client->post($uri, ['json' => $data]);

        return $this->jsonResponse($response);
    }

    public function patch($uri, $data = [])
    {
        $response = $this->client->patch($uri, ['json' => $data]);

        return $this->jsonResponse($response);
    }

    public function delete($uri)
    {
        $response = $this->client->delete($uri);

        return $this->jsonResponse($response);
    }

    protected function jsonResponse(ResponseInterface $response)
    {
        return response()->json(json_decode($response->getBody()));
    }
}
