<?php

namespace Ampeco\OmnipayMonri;

trait CommonParameters
{
    public function getAuthenticityToken(): string
    {
        return $this->getParameter('authenticityToken');
    }

    public function setAuthenticityToken(string $authenticityToken): self
    {
        return $this->setParameter('authenticityToken', $authenticityToken);
    }

    public function getMerchantKey(): string
    {
        return $this->getParameter('merchantKey');
    }

    public function setMerchantKey(string $merchantKey): self
    {
        return $this->setParameter('merchantKey', $merchantKey);
    }
}
