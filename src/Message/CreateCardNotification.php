<?php

namespace Ampeco\OmnipayMonri\Message;

use Omnipay\Common\Message\NotificationInterface;

class CreateCardNotification implements NotificationInterface
{
    public function __construct(protected array $data) {}

    public function getData(): array
    {
        return $this->data;
    }

    public function getTransactionReference(): string
    {
        return $this->data['order_number'];
    }

    public function getTransactionStatus(): string
    {
        return match ($this->data['status']) {
            'approved' => NotificationInterface::STATUS_COMPLETED,
            'decline' => NotificationInterface::STATUS_FAILED,
            default => NotificationInterface::STATUS_PENDING,
        };
    }

    public function getMessage(): string
    {
        return $this->data['response_message'];
    }

    public function getDigest(): string
    {
        $customParams = json_decode($this->data['custom_params'], true);

        return $customParams['digest'];
    }

    public function getCardReference(): string
    {
        return $this->data['pan_token'];
    }

    public function getCitId(): string
    {
        return $this->data['cit_id'];
    }

    public function getPaymentMethod(): object
    {
        $result = new \stdClass();

        $result->imageUrl = '';
        $result->last4 = substr($this->data['masked_pan'], -4);
        $result->cardType = $this->data['cc_type'];

        $expiryDate = $this->data['expiration_date'];
        $expirationMonth = (int) substr($expiryDate, 2, 4);
        $expirationYear = \DateTime::createFromFormat('y', (int) substr($expiryDate, 0, 2))->format('Y');

        $result->expirationMonth = $expirationMonth;
        $result->expirationYear = $expirationYear;

        return $result;
    }
}
