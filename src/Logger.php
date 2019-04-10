<?php

namespace AbelHalo\ApiProxy;

use Monolog\Handler\StreamHandler;
use Monolog\Logger as MonologLogger;

class Logger
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

    public function logRequest(string $method, string $uri, array $options = []): bool
    {
        if (!$this->switch) {
            return true;
        }

        return $this->logger->debug("$method $uri", $options);
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
