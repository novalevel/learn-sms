<?php
namespace Wwp66650\LearnSms;

use Closure;
use RuntimeException;
use InvalidArgumentException;
use Wwp66650\LearnSms\Contracts\GatewayInterface;
use Wwp66650\LearnSms\Support\Config;

/**
 * Class LearnSms
 *
 * @package \\${NAMESPACE}
 */
class LearnSms
{
    protected $config;
    protected $gateways = [];
    protected $customCreators = [];
    protected $defaultGateway;


    public function __construct(array $config)
    {
        $this->config = new Config($config);

        if (!empty($config['default'])) {
            $this->setDefaultGateway($config['default']);
        }
    }

    public function gateway($name = null)
    {
        $name = $name ?: $this->getDefaultGateway();

        if (!isset($this->gateways[$name])) {
            $this->gateways[$name] = $this->createGateway($name);
        }

        return $this->gateways[$name];
    }

    public function getDefaultGateway()
    {
        if (empty($this->defaultGateway)) {
            throw new RuntimeException('No default gateway configured.');
        }

        return $this->defaultGateway;
    }

    public function setDefaultGateway($name)
    {
        $this->defaultGateway = $name;

        return $this;
    }

    protected function createGateway($name)
    {
        if (isset($this->customCreators[$name])) {
            $gateway = $this->callCustomCreator($name);
        } else {
            $name = $this->formatGatewayClassName($name);
            $gateway = $this->makeGateway($name, $this->config->get($name, []));
        }

        if (!($gateway instanceof GatewayInterface)) {
            throw new InvalidArgumentException(sprintf('Gateway "%s" not inherited from %s.', $name, GatewayInterface::class));
        }

        return $gateway;
    }

    protected function callCustomCreator($gateway)
    {
        return call_user_func($this->customCreators[$gateway], $this->config->get($gateway, []));
    }

    protected function makeGateway($gateway, $config)
    {
        if (!class_exists($gateway)) {
            throw new InvalidArgumentException(sprintf('Gateway "%s" not exists.', $gateway));
        }

        return new $gateway($config);
    }

    protected function formatGatewayClassName($name)
    {
        if (class_exists($name)) {
            return $name;
        }

        $name = $this->camelCase($name);

        return __NAMESPACE__."\\Gateways\\{$name}Gateway";
    }

    protected function camelCase($name)
    {
        return str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $name)));
    }

    /**
     * Register a custom driver creator Closure.
     *
     * @param string   $name
     * @param Closure $callback
     *
     * @return $this
     */
    public function extend($name, Closure $callback)
    {
        $this->customCreators[$name] = $callback;

        return $this;
    }

    public function __call($method, $parameters)
    {
        return call_user_func_array([$this->gateway(), $method], $parameters);
    }
}
