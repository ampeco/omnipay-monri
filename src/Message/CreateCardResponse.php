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
        return $this->isSuccessful() ? $this->data['order_number'] : null;
    }

    public function getMessage(): ?string
    {
        return $this->data['errors'] && is_array($this->data['errors']) ? $this->convertErrorsToString($this->data['errors']) : null;
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
        foreach ($errors as $field => $messages) {
            $errorMessages[] = $field.' '.implode(' ', $messages);
        }

        $result = implode(' | ', $errorMessages);

        echo $result;
    }
}
