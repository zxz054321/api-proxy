<?php

namespace AbelHalo\ApiProxy;

use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger as MonologLogger;

class RequestLogger
{
    protected $logger;
    protected $switch = false;

    public function __construct()
    {
        $this->logger = new MonologLogger('apiproxy');

        $path = storage_path('logs/apiproxy.log');
        $handler = new RotatingFileHandler($path, 14, MonologLogger::DEBUG, false);

        $this->logger->pushHandler($handler);
    }

    public function log(string $method, string $uri, array $guzzleOptions = []): void
    {
        if (!$this->switch) {
            return;
        }

        $this->logger->debug("$method $uri", $guzzleOptions);
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
