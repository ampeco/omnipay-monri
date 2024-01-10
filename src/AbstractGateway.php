<?php

namespace Ampeco\OmnipayMonri;

use Omnipay\Common\AbstractGateway as AbstractOmnipayGateway;

abstract class AbstractGateway extends AbstractOmnipayGateway
{
    use CommonParameters;

    abstract public function getName();

    public function getDefaultParameters(): array
    {
        return [
            'testMode' => true,
            'authenticityToken' => '',
            'merchantKey' => '',
        ];
    }
}
