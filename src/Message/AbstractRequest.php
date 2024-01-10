<?php

namespace Ampeco\OmnipayMonri\Message;

use Ampeco\OmnipayMonri\CommonParameters;
use Ampeco\OmnipayMonri\ConvertsArrayToXml;
use Ampeco\OmnipayMonri\ManipulatesString;
use Nyholm\Psr7\Stream;

abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{
    use CommonParameters;
    use ConvertsArrayToXml;
    use ManipulatesString;

    public const API_URL_PROD = 'https://ipg.monri.com';
    public const API_URL_TEST = 'https://ipgtest.monri.com';

    public const PAYLOAD_JSON = 'json';
    public const PAYLOAD_XML = 'xml';
    public const PAYLOAD_FORM_DATA = 'formData';

    abstract public function getEndpoint(array $data = []): string;

    abstract public function getPayloadType(): string;

    abstract public function getHttpMethod(): string;

    public function getBaseUrl(): string
    {
        return $this->getTestMode() ? self::API_URL_TEST : self::API_URL_PROD;
    }

    public function sendData($data)
    {
        $originalData = $data;
        $headers = [];
        [$headers, $data] = $this->prepareHeadersAndData($headers, $data);
        $httpResponse = $this->httpClient->request(
            $this->getHttpMethod(),
            $this->getBaseUrl().$this->getEndpoint($originalData),
            $headers,
            $data
        );

        return $this->createResponse($httpResponse->getBody()->getContents(), $httpResponse->getStatusCode());
    }

    abstract protected function createResponse($data, int $statusCode): AbstractResponse;

    /**
     * @param mixed $data
     *
     * @throws \JsonException
     */
    private function prepareHeadersAndData(array $headers, $data): array
    {
        if ($this->getPayloadType() === self::PAYLOAD_JSON) {
            $headers['Content-Type'] = 'application/json';
            $data = json_encode($data, JSON_THROW_ON_ERROR);
        } elseif ($this->getPayloadType() === self::PAYLOAD_XML) {
            $headers['Content-Type'] = 'application/xml';
            $data = $this->arrayToXml($data);
        } elseif ($this->getPayloadType() === self::PAYLOAD_FORM_DATA) {
            $headers['Content-Type'] = 'application/x-www-form-urlencoded';
            $data = Stream::create(http_build_query($data));
        }

        return [$headers, $data];
    }
}
