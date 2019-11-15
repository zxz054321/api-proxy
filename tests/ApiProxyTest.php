<?php

namespace Tests;

use AbelHalo\ApiProxy\ApiProxy;
use Illuminate\Http\JsonResponse;

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

    public function test_get()
    {
        $this->assertInstanceOf(JsonResponse::class,
            $this->proxy->get('/baidu', ['wd' => 'api-proxy'])
        );
    }
}
