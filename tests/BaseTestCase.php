<?php
declare(strict_types=1);

namespace App\Tests;

use Mockery;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

abstract class BaseTestCase extends TestCase
{
    public function tearDown(): void
    {
        $container = Mockery::getContainer();
        if ($container) {
            $this->addToAssertionCount($container->mockery_getExpectationCount());
        }
    }

    public static function callMethod($obj, $name, array $args)
    {
        $class = new ReflectionClass($obj);
        $method = $class->getMethod($obj);
        $method->setAccessible(true);
        return $method->invokeArgs($obj, $args);
    }
}
