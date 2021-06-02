<?php
declare(strict_types=1);

namespace NiemandOnline\HeptaConnect\Portal\RandomFoxCa\Emitter;

use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Dataset\Ecommerce\Media\Media;
use Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitContextInterface;
use Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitterContract;
use NiemandOnline\HeptaConnect\Portal\RandomFoxCa\Packer\MediaPacker;
use NiemandOnline\HeptaConnect\Portal\RandomFoxCa\Support\RandomFoxApiClient;
use Psr\Http\Message\StreamInterface;

class MediaEmitter extends EmitterContract
{
    public function supports(): string
    {
        return Media::class;
    }

    protected function run(string $externalId, EmitContextInterface $context): ?DatasetEntityContract
    {
        /** @var RandomFoxApiClient $api */
        $api = $context->getContainer()->get(RandomFoxApiClient::class);
        $packer = $context->getContainer()->get(MediaPacker::class);
        $blob = $api->getImage($externalId);

        if (!$blob instanceof StreamInterface) {
            return null;
        }

        return $packer->pack($externalId, $blob);
    }
}
