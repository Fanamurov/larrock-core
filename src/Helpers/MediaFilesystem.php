<?php

namespace Larrock\Core\Helpers;

use Spatie\MediaLibrary\Filesystem\DefaultFilesystem;
use Spatie\MediaLibrary\Media;
use Spatie\MediaLibrary\PathGenerator\PathGeneratorFactory;

class MediaFilesystem extends DefaultFilesystem
{

    /*
     * Remove all files for the given media.
     */
    public function removeFiles(Media $media)
    {
        $conversionsDirectory = $this->getConversionDirectory($media);

        $pathGenerator = PathGeneratorFactory::create();
        $this->filesystem->disk($media->disk)->delete($pathGenerator->getPath($media) .'/'. $media->file_name);

        collect([$conversionsDirectory])
            ->each(function ($directory) use ($media) {
                $this->filesystem->disk($media->disk)->deleteDirectory($directory);
            });
    }
}
