<?php
namespace App\Service;
use Symfony\Component\Filesystem\Filesystem;
class ImageService
{
    public function generateUniqueImageName(string $originalName): string
    {
        $uniqueId = uniqid('img_', true);
        return $uniqueId . '.png';
    }

    public function moveImageToDirectory(string $newImageName, $data): void
    {
         // Move the file to the directory where brochures are stored
        try {
            
            $filesystem = new Filesystem();
            $filesystem->copy(
                $data->getPathname(),
                "/var/www/html/public/content/" . $newImageName
            );

        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }
    }
}