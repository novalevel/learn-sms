<?php

namespace Wwp66650\LearnSms\Tests\Support;

use Wwp66650\LearnSms\Support\Config;
use Wwp66650\LearnSms\Tests\TestCase;

/**
 * Class ConfigTest
 *
 * @package \Wwp66650\LearnSms\Tests\Support
 */
class ConfigTest extends TestCase
{
    public function testConfig()
    {
        $config = new Config([
            'foo' => 'bar',
            'bar' => [
                'screen_name' => 'somebody',
                'profile' => [
                    'id' => 9999,
                    'name' => 'overtrue',
                ]
            ],
            'numbers' => [
                [
                    'id' => 1,
                    'number' => 1
                ],
                [
                    'id' => 2,
                    'number' => 2
                ],
            ],
        ]);

        $this->assertTrue(isset($config['foo']));

        $this->assertSame('bar', $config['foo']);
        $this->assertSame('bar', $config->get('foo'));
        $this->assertNull($config->get(null));

        $this->assertSame(9999, $config->get('bar.profile.id'));
        $this->assertSame('overtrue', $config->get('bar.profile.name'));

        $this->assertSame(1, $config->get('numbers.0.id'));
        $this->assertSame(1, $config->get('numbers.0.number'));

        $this->assertSame(2, $config->get('numbers.1.id'));
        $this->assertSame(2, $config->get('numbers.1.number'));

        $config['foo'] = 'new-bar';
        $this->assertSame('new-bar', $config['foo']);

        unset($config['foo']);
        $this->assertNull($config['foo']);
    }
}
