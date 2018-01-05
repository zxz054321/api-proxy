<?php

namespace AbelHalo\ApiProxy;

use GuzzleHttp\Client;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
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
    protected $baseUri;
    protected $returnAs;
    protected $options = [];
    protected $log = false;

    public function __construct($baseUri)
    {
        $this->baseUri  = $baseUri;
        $this->client   = new Client(['base_uri' => $baseUri]);
        $this->returnAs = static::RETURN_AS_JSON_RESPONSE;
    }

    public function authorizationHeader($value)
    {
        $this->options['headers']['Authorization'] = $value;

        return $this;
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

    public function enableLog()
    {
        $this->log = true;

        return $this;
    }

    public function disableLog()
    {
        $this->log = false;

        return $this;
    }

    public function logRequest($method, $uri, array $options = [])
    {
        static $logger = null;

        if (!$logger) {
            $logger  = new Logger('apiproxy');
            $path    = storage_path('logs/apiproxy.log');
            $handler = new StreamHandler($path, Logger::INFO, false);

            $logger->pushHandler($handler);
        }

        return $logger->info("$method $this->baseUri $uri", $this->options($options));
    }

    protected function request($method, $uri, array $options = [])
    {
        $this->logRequest($method, $uri, $options);

        return $this->client->request($method, $uri, $this->options($options));
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

    protected function options(array $options = [])
    {
        return array_merge($this->options, $options);
    }
}
