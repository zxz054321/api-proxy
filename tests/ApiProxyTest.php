<?php

namespace Tests;

use AbelHalo\ApiProxy\ApiProxy;

class ApiProxyTest extends TestCase
{
    /**
     * @var ApiProxy
     */
    protected $proxy;

    protected function setUp()
    {
        parent::setUp();

        $this->proxy = new ApiProxy('https://www.baidu.com');

        $this->proxy->logger->enable();
    }

    public function testGet()
    {
        $this->proxy->get('/baidu', ['wd' => 'api-proxy']);
    }

    public function testLogRequest()
    {
        $this->assertTrue($this->proxy->logger->log('GET', '/baidu', [
            'query' => ['wd' => 'api-proxy'],
        ]));
    }
}
