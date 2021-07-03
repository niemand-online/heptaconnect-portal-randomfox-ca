<?php
declare(strict_types=1);

namespace NiemandOnline\HeptaConnect\Portal\RandomFoxCa\Packer;

use Heptacom\HeptaConnect\Dataset\Ecommerce\Media\Media;
use Heptacom\HeptaConnect\Portal\Base\Serialization\Contract\NormalizationRegistryContract;
use Heptacom\HeptaConnect\Portal\Base\Serialization\Contract\SerializableStream;
use Psr\Http\Message\StreamInterface;

class MediaPacker
{
    private NormalizationRegistryContract $normalizer;

    public function __construct(NormalizationRegistryContract $normalizer)
    {
        $this->normalizer = $normalizer;
    }

    public function packOptional(?string $id, ?StreamInterface $stream): ?Media
    {
        return $id === null || $stream === [] ? null : $this->pack($id, $stream);
    }

    public function pack(string $id, StreamInterface $blob): Media
    {
        $stream = new SerializableStream($blob);
        $normalizer = $this->normalizer->getNormalizer($stream);

        $result = new Media();

        $result->setPrimaryKey($id);
        $result->setMimeType('image/jpeg');
        $result->setNormalizedStream((string) $normalizer->normalize($stream, null, [
            'mediaId' => $id,
        ]));

        return $result;
    }
}
