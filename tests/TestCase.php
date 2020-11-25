<?php

namespace AmbitionWorks\ModelSchema\Tests;

use AmbitionWorks\ModelSchema\ModelSchemaServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            ModelSchemaServiceProvider::class,
        ];
    }
}
