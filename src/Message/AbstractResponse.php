<?php

namespace Ampeco\OmnipayMonri\Message;

use Omnipay\Common\Message\RequestInterface;

abstract class AbstractResponse extends \Omnipay\Common\Message\AbstractResponse
{
    protected int $statusCode;

    public function __construct(RequestInterface $request, $data, int $statusCode)
    {
        parent::__construct($request, $this->decodeData($request, $data));
        $this->statusCode = $statusCode;
    }

    abstract public function isSuccessful(): bool;

    /**
     * @param mixed $data
     *
     * @throws \JsonException
     */
    private function decodeData(RequestInterface $request, $data)
    {
        try {
            if ($request->getPayloadType() === AbstractRequest::PAYLOAD_JSON || $this->isJson($data)) {
                return json_decode($data, true, 512, JSON_THROW_ON_ERROR);
            }
            if ($request->getPayloadType() === AbstractRequest::PAYLOAD_XML) {
                return json_decode(
                    json_encode(simplexml_load_string($data), JSON_THROW_ON_ERROR),
                    true,
                    512,
                    JSON_THROW_ON_ERROR
                );
            }
        } catch (\Throwable $exception) {
            $message = sprintf('%s response payload: %s', $exception->getMessage(), $data);
            throw new \RuntimeException('Failed to decode response data: ' . $message, 0, $exception);
        }

        return $data;
    }

    private function isJson(string $string): bool
    {
        json_decode($string);

        return json_last_error() == JSON_ERROR_NONE;
    }
}
