<?php

namespace AbelHalo\ApiProxy;

use Monolog\Handler\StreamHandler;
use Monolog\Logger as MonologLogger;

class RequestLogger
{
    protected $logger;
    protected $switch = false;

    public function __construct()
    {
        $this->logger = new MonologLogger('apiproxy');

        $path    = storage_path('logs/apiproxy.log');
        $handler = new StreamHandler($path, MonologLogger::DEBUG, false);

        $this->logger->pushHandler($handler);
    }

    public function log(string $method, string $uri, array $guzzleOptions = []): bool
    {
        if (!$this->switch) {
            return true;
        }

        return $this->logger->debug("$method $uri", $guzzleOptions);
    }

    public function enable(): void
    {
        $this->switch = true;
    }

    public function disable(): void
    {
        $this->switch = false;
    }
}
