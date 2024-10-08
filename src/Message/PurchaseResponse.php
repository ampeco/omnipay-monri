<?php

namespace Ampeco\OmnipayMonri\Message;

class PurchaseResponse extends AbstractResponse
{
    public function isSuccessful(): bool
    {
        return $this->statusCode === 201 && isset($this->data['transaction']['status']) && $this->data['transaction']['status'] === 'approved';
    }

    public function getTransactionReference()
    {
        return $this->isSuccessful() ? ($this->data['transaction']['order_number'] ?? null) : null;
    }

    public function getMessage(): ?string
    {
        return isset($this->data['errors']) ? current($this->data['errors']) : null;
    }
}
