<?php

namespace AbelHalo\ApiProxy;

use GuzzleHttp\Client;
use Illuminate\Http\UploadedFile;
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
    protected $logger;

    public function __construct($baseUri = '')
    {
        $this->baseUri  = $baseUri;
        $this->client   = new Client(['base_uri' => $baseUri]);
        $this->returnAs = static::RETURN_AS_JSON_RESPONSE;
        $this->logger   = new Logger();
    }

    public function authorizationHeader($value)
    {
        $this->options['headers']['Authorization'] = $value;

        return $this;
    }

    public function headers($pairs)
    {
        $this->options['headers'] = array_merge($this->options['headers'] ?? [], $pairs);

        return $this;
    }

    public function get($uri, $parameters = [])
    {
        $response = $this->request('GET', $uri, ['query' => $parameters]);

        return $this->respond($response);
    }

    public function post($uri, $data = [])
    {
        $contentType = 'application/x-www-form-urlencoded' == array_get($this->options, 'headers.Content-Type')
            ? 'form_params' : 'json';

        $response = $this->request('POST', $uri, [$contentType => $data]);

        return $this->respond($response);
    }

    public function postWithFiles(string $uri, array $data = [])
    {
        $data = collect($data)
            ->map(function ($value, $key) {
                return [
                    'name' => $key,
                    'contents' => $value instanceof UploadedFile
                        ? fopen($value->path(), 'r')
                        : $value,
                ];
            })
            ->values()
            ->toArray();

        $response = $this->request('POST', $uri, ['multipart' => $data]);

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
        $this->logger->enable();

        return $this;
    }

    public function disableLog()
    {
        $this->logger->disable();

        return $this;
    }

    public function logRequest($method, $uri, array $options = [])
    {
        return $this->logger->logRequest($method, "$this->baseUri $uri", $this->options($options));
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
