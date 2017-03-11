<?php

namespace Wwp66650\LearnSms\Contracts;

/**
 * Class GatewayInterface
 *
 * @package \\${NAMESPACE}
 */
interface GatewayInterface
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
    public function send($to, $template, array $data = []);
}
