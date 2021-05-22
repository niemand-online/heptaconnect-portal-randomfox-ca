<?php
declare(strict_types=1);

namespace NiemandOnline\HeptaConnect\Portal\RandomFoxCa\Support;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriFactoryInterface;
use Psr\Http\Message\UriInterface;

class RandomFoxApiClient
{
    private ClientInterface $client;

    private RequestFactoryInterface $requestFactory;

    private UriFactoryInterface $uriFactory;

    public function __construct(
        ClientInterface $client,
        RequestFactoryInterface $requestFactory,
        UriFactoryInterface $uriFactory
    ) {
        $this->client = $client;
        $this->requestFactory = $requestFactory;
        $this->uriFactory = $uriFactory;
    }

    public function getRandomId(): string
    {
        $request = $this->requestFactory->createRequest('GET', $this->getBaseUri()->withPath('/floof'));
        $response = $this->client->sendRequest($request);

        if ($response->getStatusCode() < 200 || 300 <= $response->getStatusCode()) {
            return '';
        }

        $responseData = $response->getBody()->getContents();
        $responseObject = (array) \json_decode($responseData, true, 512, \JSON_THROW_ON_ERROR);
        $imageUrl = $responseObject['image'] ?? '';

        if (\preg_match('#images/(\d+).jpg#', $imageUrl, $matches) !== 1) {
            return '';
        }

        return (string) $matches[0];
    }

    public function getImage(string $id): ?StreamInterface
    {
        $request = $this->requestFactory->createRequest('GET', $this->getBaseUri()->withPath('/images/'.$id.'.jpg'));
        $response = $this->client->sendRequest($request);

        if ($response->getStatusCode() < 200 || 300 <= $response->getStatusCode()) {
            return null;
        }

        return $response->getBody();
    }

    protected function getBaseUri(): UriInterface
    {
        return $this->uriFactory->createUri('https://randomfox.ca/');
    }
}
