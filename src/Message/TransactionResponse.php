<?php

namespace Ampeco\OmnipayMonri\Message;

class TransactionResponse extends AbstractResponse
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
        if (isset($this->data['errors'])) {
            return current($this->data['errors']);
        }

        return $this->data['transaction']['response_message'] ?? null;
    }
}