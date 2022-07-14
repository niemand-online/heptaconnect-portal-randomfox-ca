<?php
declare(strict_types=1);

namespace NiemandOnline\HeptaConnect\Portal\RandomFoxCa\Support;

use Heptacom\HeptaConnect\Portal\Base\Web\Http\Contract\HttpClientContract;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;
use Psr\Http\Message\UriInterface;

class RandomFoxApiClient
{
    private HttpClientContract $client;

    private RequestFactoryInterface $requestFactory;

    private UriFactoryInterface $uriFactory;

    public function __construct(
        HttpClientContract $client,
        RequestFactoryInterface $requestFactory,
        UriFactoryInterface $uriFactory
    ) {
        $this->client = $client->withMaxRetry(1);
        $this->requestFactory = $requestFactory;
        $this->uriFactory = $uriFactory;
    }

    public function getImageUrl(string $id): string
    {
        return (string) $this->getBaseUri()->withPath('/images/'.$id.'.jpg');
    }

    public function isUrlAvailable(string $url): bool
    {
        try {
            $request = $this->requestFactory->createRequest('HEAD', $this->uriFactory->createUri($url));
            $this->client->sendRequest($request);
            return true;
        } catch (\Throwable $_) {
            return false;
        }
    }

    protected function getBaseUri(): UriInterface
    {
        return $this->uriFactory->createUri('https://randomfox.ca/');
    }
}
