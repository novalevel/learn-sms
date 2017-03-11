<?php

namespace Wwp66650\LearnSms\Tests;

use Mockery;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;
/**
 * Class TestCase
 *
 * @package \Wwp66650\LearnSms\Tests
 */
class TestCase extends PHPUnitTestCase
{
    public function tearDown()
    {
        parent::tearDown();

        if ($container = Mockery::getContainer()) {
            $this->addToAssertionCount($container->mockery_getExpectationCount());
        }

        Mockery::close();
    }

}
