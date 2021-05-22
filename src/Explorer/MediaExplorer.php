<?php
declare(strict_types=1);

namespace NiemandOnline\HeptaConnect\Portal\RandomFoxCa\Explorer;

use Heptacom\HeptaConnect\Dataset\Ecommerce\Media\Media;
use Heptacom\HeptaConnect\Portal\Base\Exploration\Contract\ExploreContextInterface;
use Heptacom\HeptaConnect\Portal\Base\Exploration\Contract\ExplorerContract;
use NiemandOnline\HeptaConnect\Portal\RandomFoxCa\Packer\MediaPacker;
use NiemandOnline\HeptaConnect\Portal\RandomFoxCa\Support\RandomFoxApiClient;
use Psr\Http\Message\StreamInterface;

class MediaExplorer extends ExplorerContract
{
    public function supports(): string
    {
        return Media::class;
    }

    protected function run(ExploreContextInterface $context): iterable
    {
        /** @var RandomFoxApiClient $api */
        $api = $context->getContainer()->get(RandomFoxApiClient::class);
        $packer = $context->getContainer()->get(MediaPacker::class);
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
    }
}
