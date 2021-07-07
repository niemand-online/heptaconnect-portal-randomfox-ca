<?php
declare(strict_types=1);

use Heptacom\HeptaConnect\Dataset\Ecommerce\Media\Media;
use Heptacom\HeptaConnect\Portal\Base\Builder\FlowComponent;
use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\ConfigurationContract;
use NiemandOnline\HeptaConnect\Portal\RandomFoxCa\Packer\MediaPacker;
use NiemandOnline\HeptaConnect\Portal\RandomFoxCa\Portal;
use NiemandOnline\HeptaConnect\Portal\RandomFoxCa\Support\RandomFoxApiClient;
use Psr\Http\Message\StreamInterface;

FlowComponent::explorer(Media::class)
    ->run(static function (RandomFoxApiClient $api, MediaPacker $packer, ConfigurationContract $config): iterable {
        $i = 1;
        $max = \PHP_INT_MAX;

        if ($config->get(Portal::CONFIG_PREVIEW)) {
            $max = ((int) $config->get(Portal::CONFIG_PREVIEW_LIMIT)) + 1;
        }

        while ($i < $max) {
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
