<?php

abstract class TestCase extends Orchestra\Testbench\TestCase
{
    protected function getPackageAliases($app)
    {
        return ['ApiProxy' => \AbelHalo\ApiProxy\Facade::class];
    }
}
