<?php

namespace Ampeco\OmnipayMonri\Message;

class VoidResponse extends AbstractResponse
{
    public function isSuccessful(): bool
    {
        return $this->statusCode === 200 && isset($this->data['status']) && $this->data['status'] === 'approved';
    }

    public function getTransactionReference()
    {
        return $this->isSuccessful() ? ($this->data['order-number'] ?? null) : null;
    }

    public function getMessage(): ?string
    {
        return $this->data['error'] ?? null;
    }
}
