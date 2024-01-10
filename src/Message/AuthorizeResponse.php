<?php

namespace Ampeco\OmnipayMonri\Message;

class AuthorizeResponse extends AbstractResponse
{
    public function isSuccessful(): bool
    {
        return $this->statusCode === 201 && $this->data['transaction']['status'] === 'approved';
    }

    public function getTransactionReference()
    {
        return $this->isSuccessful() ? $this->data['transaction']['order_number'] : null;
    }

    public function getMessage(): ?string
    {
        return $this->data['errors'] ? current($this->data['errors']) : null;
    }
}
