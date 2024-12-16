<?php

namespace Ampeco\OmnipayMonri\Message;

class PurchaseRequest extends AbstractRequest
{
    public function getCustomerIp(): ?string
    {
        return $this->getParameter('customerIp');
    }

    public function setCustomerIp(?string $customerIp): self
    {
        return $this->setParameter('customerIp', $customerIp);
    }

    public function getCustomerAddress(): ?string
    {
        return $this->getParameter('customerAddress');
    }

    public function setCustomerAddress(?string $customerAddress): self
    {
        return $this->setParameter('customerAddress', $customerAddress);
    }

    public function getCustomerCity(): ?string
    {
        return $this->getParameter('customerCity');
    }

    public function setCustomerCity(?string $customerCity): self
    {
        return $this->setParameter('customerCity', $customerCity);
    }

    public function getCustomerCountry(): ?string
    {
        return $this->getParameter('customerCountry');
    }

    public function setCustomerCountry(?string $customerCountry): self
    {
        return $this->setParameter('customerCountry', $customerCountry);
    }

    public function getCustomerEmail(): ?string
    {
        return $this->getParameter('customerEmail');
    }

    public function setCustomerEmail(?string $customerEmail): self
    {
        return $this->setParameter('customerEmail', $customerEmail);
    }

    public function getCustomerFullName(): ?string
    {
        return $this->getParameter('customerFullName');
    }

    public function setCustomerFullName(?string $customerFullName): self
    {
        return $this->setParameter('customerFullName', $customerFullName);
    }

    public function getCustomerPhone(): ?string
    {
        return $this->getParameter('customerPhone');
    }

    public function setCustomerPhone(?string $customerPhone): self
    {
        return $this->setParameter('customerPhone', $customerPhone);
    }

    public function getCustomerPostCode(): ?string
    {
        return $this->getParameter('customerPostCode');
    }

    public function setCustomerPostCode(?string $customerPostCode): self
    {
        return $this->setParameter('customerPostCode', $customerPostCode);
    }

    public function getLanguage(): string
    {
        return $this->getParameter('language');
    }

    public function setLanguage(string $language): self
    {
        return $this->setParameter('language', $language);
    }

    public function getPanToken(): string
    {
        return $this->getParameter('panToken');
    }

    public function setPanToken(string $panToken): self
    {
        return $this->setParameter('panToken', $panToken);
    }

    public function getCitId(): string
    {
        return $this->getParameter('citId');
    }

    public function setCitId(string $citId): self
    {
        return $this->setParameter('citId', $citId);
    }

    public function getEndpoint(array $data = []): string
    {
        return '/v2/transaction';
    }

    public function getPayloadType(): string
    {
        return self::PAYLOAD_JSON;
    }

    public function getHttpMethod(): string
    {
        return 'POST';
    }

    public function getData(): array
    {
        $this->validate(
            'amount',
            'description',
            'currency',
            'transactionId',
            'language',
            'panToken',
        );

        $digest = hash(
            'sha512',
            $this->getMerchantKey().$this->getTransactionId().$this->getAmountInteger().$this->getCurrency()
        );

        return [
            'transaction' => [
                'transaction_type' => 'purchase',
                'amount' => $this->getAmountInteger(),
                'scenario' => 'charge',
                'order_number' => $this->getTransactionId(),
                'ip' => $this->getCustomerIp(),
                'order_info' => $this->trimText($this->getDescription(), 100),
                'ch_address' => $this->getCustomerAddress(),
                'ch_city' => $this->getCustomerCity(),
                'ch_country' => $this->getCustomerCountry(),
                'ch_email' => $this->getCustomerEmail(),
                'ch_full_name' => $this->getCustomerFullName(),
                'ch_phone' => $this->getCustomerPhone(),
                'ch_zip' => $this->getCustomerPostCode(),
                'currency' => $this->getCurrency(),
                'digest' => $digest,
                'authenticity_token' => $this->getAuthenticityToken(),
                'language' => $this->getLanguage(),
                'pan_token' => $this->getPanToken(),
                'supported_payment_methods' => $this->getPanToken(),
                'cit_id' => $this->getCitId(),
                'moto' => true,
                'merchant_initiated_transaction' => true,
            ],
        ];
    }

    protected function createResponse($data, int $statusCode): PurchaseResponse
    {
        return $this->response = new PurchaseResponse($this, $data, $statusCode);
    }
}
