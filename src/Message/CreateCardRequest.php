<?php

namespace Ampeco\OmnipayMonri\Message;

class CreateCardRequest extends AbstractRequest
{
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

    public function getSuccessUrl(): string
    {
        return $this->getParameter('successUrl');
    }

    public function setSuccessUrl(string $successUrl): self
    {
        return $this->setParameter('successUrl', $successUrl);
    }

    public function getCallbackUrl(): string
    {
        return $this->getParameter('callbackUrl');
    }

    public function setCallbackUrl(string $callbackUrl): self
    {
        return $this->setParameter('callbackUrl', $callbackUrl);
    }

    public function getEndpoint(array $data = []): string
    {
        return '/v2/form';
    }

    public function getPayloadType(): string
    {
        return self::PAYLOAD_FORM_DATA;
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
            'successUrl',
            'cancelUrl',
            'callbackUrl',
        );

        $digest = hash(
            'sha512',
            $this->getMerchantKey().$this->getTransactionId().$this->getAmountInteger().$this->getCurrency()
        );

        return [
            'transaction_type' => 'purchase',
            'amount' => $this->getAmountInteger(),
            'order_number' => $this->getTransactionId(),
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
            'moto' => true,
            'tokenize_pan' => true,
            'future_usage' => 'recurring',
            'success_url_override' => $this->getSuccessUrl(),
            'cancel_url_override' => $this->getCancelUrl(),
            'callback_url_override' => $this->getCallbackUrl(),
            // needed later on to accept notification
            'custom_params' => json_encode([
                'digest' => $digest,
            ]),
        ];
    }

    protected function createResponse($data, int $statusCode): CreateCardResponse
    {
        return $this->response = new CreateCardResponse($this, $data, $statusCode);
    }
}
