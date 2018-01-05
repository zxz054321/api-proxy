<?php

namespace Tests\Unit;

use AbelHalo\ApiProxy\ApiProxy;
use Tests\TestCase;

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

        $this->proxy->enableLog();
    }

    public function testGet()
    {
        $this->proxy->get('/baidu', ['wd' => 'api-proxy']);
    }

    public function testLogRequest()
    {
        $this->assertTrue($this->proxy->logRequest('GET', '/baidu', [
            'query' => ['wd' => 'api-proxy'],
        ]));
    }
}
