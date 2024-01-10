<?php

namespace Ampeco\OmnipayMonri\Message;

class RefundRequest extends AbstractRequest
{
    public function getEndpoint(array $data = []): string
    {
        return sprintf('/transactions/%s/refund.xml', $data['transaction']['order-number']);
    }

    public function getPayloadType(): string
    {
        return self::PAYLOAD_XML;
    }

    public function getHttpMethod(): string
    {
        return 'POST';
    }

    public function getData(): array
    {
        $this->validate(
            'amount',
            'currency',
            'transactionReference',
        );

        $digest = hash(
            'sha1',
            $this->getMerchantKey().$this->getTransactionReference().$this->getAmountInteger().$this->getCurrency()
        );

        return [
            'transaction' => [
                'amount' => $this->getAmountInteger(),
                'currency' => $this->getCurrency(),
                'digest' => $digest,
                'authenticity-token' => $this->getAuthenticityToken(),
                'order-number' => $this->getTransactionReference(),
            ],
        ];
    }

    protected function createResponse($data, int $statusCode): RefundResponse
    {
        return $this->response = new RefundResponse($this, $data, $statusCode);
    }
}
