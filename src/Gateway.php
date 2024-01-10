<?php

namespace Ampeco\OmnipayMonri;

use Ampeco\OmnipayMonri\Message\AuthorizeRequest;
use Ampeco\OmnipayMonri\Message\CaptureRequest;
use Ampeco\OmnipayMonri\Message\CreateCardNotification;
use Ampeco\OmnipayMonri\Message\CreateCardRequest;
use Ampeco\OmnipayMonri\Message\PurchaseRequest;
use Ampeco\OmnipayMonri\Message\RefundRequest;
use Ampeco\OmnipayMonri\Message\VoidRequest;

class Gateway extends AbstractGateway
{
    public function getName(): string
    {
        return 'Monri';
    }

    public function createCard(array $options = [])
    {
        return $this->createRequest(CreateCardRequest::class, $options);
    }

    public function acceptNotification(array $options = []): CreateCardNotification
    {
        return new CreateCardNotification($options);
    }

    public function authorize(array $options = [])
    {
        return $this->createRequest(AuthorizeRequest::class, $options);
    }

    public function capture(array $options = [])
    {
        return $this->createRequest(CaptureRequest::class, $options);
    }

    public function void(array $options = [])
    {
        return $this->createRequest(VoidRequest::class, $options);
    }

    public function purchase(array $options = [])
    {
        return $this->createRequest(PurchaseRequest::class, $options);
    }

    public function refund(array $options = [])
    {
        return $this->createRequest(RefundRequest::class, $options);
    }
}
