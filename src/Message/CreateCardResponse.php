<?php

namespace Ampeco\OmnipayMonri\Message;

use Omnipay\Common\Message\RedirectResponseInterface;

class CreateCardResponse extends AbstractResponse implements RedirectResponseInterface
{
    public function isSuccessful(): bool
    {
        return $this->statusCode === 200 && isset($this->data['payment_url']) && !empty($this->data['payment_url']);
    }

    public function getTransactionReference()
    {
        return $this->isSuccessful() ? ($this->data['order_number'] ?? null) : null;
    }

    public function getMessage(): ?string
    {
        return isset($this->data['errors']) && is_array($this->data['errors']) ? $this->convertErrorsToString($this->data['errors']) : null;
    }

    public function getRedirectUrl(): string
    {
        return $this->data['payment_url'];
    }

    public function isRedirect(): bool
    {
        return true;
    }

    public function getRedirectMethod(): string
    {
        return 'GET';
    }

    private function convertErrorsToString(array $errors): string
    {
        $errorMessages = [];
        foreach ($errors as $field => $messages) {
            if (!is_array($messages)) {
                $errorMessages[] = $field.' '.$messages;

                continue;
            }

            $errorMessages[] = $field.' '.implode(' ', $messages);
        }

        return implode(' | ', $errorMessages);
    }
}
