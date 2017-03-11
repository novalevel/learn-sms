<?php

namespace Wwp66650\LearnSms\Gateways;

use Wwp66650\LearnSms\Contracts\GatewayInterface;

/**
 * Class LogGateway
 *
 * @package \\${NAMESPACE}
 */
class LogGateway implements GatewayInterface
{

    /**
     * Send a short message.
     *
     * @param string|int $to
     * @param string     $template
     * @param array      $data
     *
     * @return mixed
     */
    public function send($to, $template, array $data = [])
    {
        // TODO: Implement send() method.
    }
}
