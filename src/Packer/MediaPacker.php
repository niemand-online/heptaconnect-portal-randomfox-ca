<?php
declare(strict_types=1);

namespace NiemandOnline\HeptaConnect\Portal\RandomFoxCa\Packer;

use Heptacom\HeptaConnect\Dataset\Ecommerce\Media\Media;
use Heptacom\HeptaConnect\Portal\Base\File\FileReferenceFactoryContract;

class MediaPacker
{
    private FileReferenceFactoryContract $fileReferenceFactory;

    public function __construct(FileReferenceFactoryContract $fileReferenceFactory)
    {
        $this->fileReferenceFactory = $fileReferenceFactory;
    }

    public function packOptional(?string $id, string $imageUrl): ?Media
    {
        return $id === null ? null : $this->pack($id, $imageUrl);
    }

    public function pack(string $id, string $imageUrl): Media
    {
        $result = new Media();

        $result->setPrimaryKey($id);
        $result->setMimeType('image/jpeg');
        $result->setFile($this->fileReferenceFactory->fromPublicUrl($imageUrl));

        return $result;
    }
}
