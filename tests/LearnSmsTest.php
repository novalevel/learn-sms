<?php

namespace Wwp66650\LearnSms\Tests;

use RuntimeException;
use InvalidArgumentException;
use Wwp66650\LearnSms\Contracts\GatewayInterface;
use Wwp66650\LearnSms\LearnSms;

/**
 * Class LearnSmsTest
 *
 * @package \\${NAMESPACE}
 */
class LearnSmsTest extends TestCase
{
    public function testGateway()
    {
        $easySms = new LearnSms([]);

        $this->assertInstanceOf(GatewayInterface::class, $easySms->gateway('Log'));

        // invalid gateway
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Gateway "Wwp66650\LearnSms\Gateways\NotExistsGatewayNameGateway" not exists.');

        $easySms->gateway('NotExistsGatewayName');
    }

    public function testGatewayWithoutDefaultSetting()
    {
        $easySms = new LearnSms([]);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('No default gateway configured.');

        $easySms->gateway();
    }

    public function testGatewayWithDefaultSetting()
    {
        $easySms = new LearnSms(['default' => DummyGatewayForTest::class]);
        $this->assertSame(DummyGatewayForTest::class, $easySms->getDefaultGateway());
        $this->assertInstanceOf(DummyGatewayForTest::class, $easySms->gateway());

        // invalid gateway
        $easySms->setDefaultGateway(DummyInvalidGatewayForTest::class);
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            sprintf('Gateway "%s" not inherited from %s.',
                DummyInvalidGatewayForTest::class,
                GatewayInterface::class)
        );
        $easySms->gateway();
    }

    public function testExtend()
    {
        $easySms = new LearnSms([]);
        $easySms->extend('foo', function() {
            return new DummyGatewayForTest();
        });

        $this->assertInstanceOf(DummyGatewayForTest::class, $easySms->gateway('foo'));
    }

    public function testMagicCall()
    {
        $easySms = new LearnSms(['default' => DummyGatewayForTest::class]);

        $this->assertSame('send-result', $easySms->send('mock-number', 'hello'));
    }
}

class DummyGatewayForTest implements GatewayInterface {
    public function send($to,$template,array $data = [])
    {
        return 'send-result';
    }
}

class DummyInvalidGatewayForTest {
    // nothing
}
