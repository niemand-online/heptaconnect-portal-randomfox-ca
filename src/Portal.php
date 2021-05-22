<?php
declare(strict_types=1);

namespace NiemandOnline\HeptaConnect\Portal\RandomFoxCa;

use Heptacom\HeptaConnect\Core\Storage\NormalizationRegistry;
use Heptacom\HeptaConnect\Portal\Base\Emission\EmitterCollection;
use Heptacom\HeptaConnect\Portal\Base\Exploration\ExplorerCollection;
use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalContract;
use Http\Discovery\Psr17FactoryDiscovery;
use Http\Discovery\Psr18ClientDiscovery;
use NiemandOnline\HeptaConnect\Portal\RandomFoxCa\Packer\MediaPacker;
use NiemandOnline\HeptaConnect\Portal\RandomFoxCa\Support\RandomFoxApiClient;
use Psr\Container\ContainerInterface;

class Portal extends PortalContract
{
    public function getExplorers(): ExplorerCollection
    {
        return new ExplorerCollection([
            new Explorer\MediaExplorer(),
        ]);
    }

    public function getEmitters(): EmitterCollection
    {
        return new EmitterCollection([
            new Emitter\MediaEmitter(),
        ]);
    }

    public function getServices(): array
    {
        return [
            RandomFoxApiClient::class => static fn (ContainerInterface $ci): RandomFoxApiClient => new RandomFoxApiClient(
                Psr18ClientDiscovery::find(),
                Psr17FactoryDiscovery::findRequestFactory(),
                Psr17FactoryDiscovery::findUriFactory(),
            ),
            MediaPacker::class => static fn (ContainerInterface $ci): MediaPacker => new MediaPacker(
                $ci->get(NormalizationRegistry::class),
            ),
        ];
    }
}
