<?php
declare(strict_types=1);

use Heptacom\HeptaConnect\Dataset\Ecommerce\Media\Media;
use Heptacom\HeptaConnect\Portal\Base\Builder\FlowComponent;
use NiemandOnline\HeptaConnect\Portal\RandomFoxCa\Packer\MediaPacker;
use NiemandOnline\HeptaConnect\Portal\RandomFoxCa\Support\RandomFoxApiClient;
use Psr\Http\Message\StreamInterface;

FlowComponent::explorer(Media::class)
    ->run(static function (RandomFoxApiClient $api, MediaPacker $packer): iterable {
        $i = 1;

        while ($i < \PHP_INT_MAX) {
            $id = (string) $i;
            $blob = $api->getImage($id);

            if (!$blob instanceof StreamInterface) {
                break;
            }

            yield $packer->pack($id, $blob);
            ++$i;
        }
    });

FlowComponent::emitter(Media::class)
    ->run(static fn (string $id, RandomFoxApiClient $client, MediaPacker $packer): ?Media => $packer->packOptional($id, $client->getImage($id)));
