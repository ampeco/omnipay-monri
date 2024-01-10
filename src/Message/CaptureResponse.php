<?php

namespace Ampeco\OmnipayMonri\Message;

class CaptureResponse extends AbstractResponse
{
    public function isSuccessful(): bool
    {
        return $this->statusCode === 200 && $this->data['status'] === 'approved';
    }

    public function getTransactionReference()
    {
        return $this->isSuccessful() ? $this->data['order-number'] : null;
    }

    public function getMessage(): ?string
    {
        return $this->data['error'] ?? null;
    }
}
