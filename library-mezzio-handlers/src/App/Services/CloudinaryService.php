<?php

declare(strict_types=1);

namespace App\Services;

use Cloudinary\Cloudinary;

class CloudinaryService {
    public function __construct(
        private Cloudinary $cloudinary
    ) {
        
    }

    private function getPublicIdFromURL(string $url): string {
        $parts = explode("/upload/", $url);
        $path = explode("/", $parts[1], 2)[1];
        $publicId = preg_replace('/\.[^.]+$/', '', $path);

        return $publicId;
    }

    public function uploadImage(string $uri, ?string $imageCloudinaryURL, string $folderName): string {
        if ($imageCloudinaryURL) {
            $options = [
                'public_id' => $this->getPublicIdFromURL($imageCloudinaryURL),
                'invalidate' => true
            ];
        } else {
            $options = [
                'folder' => $folderName,
                'unique_filename' => true
            ];
        }

        $response = $this->cloudinary->uploadApi()->upload($uri, $options);
        return $response['secure_url'];
    }

    public function deleteImage(string $url) {
        $publicId = $this->getPublicIdFromURL($url);

        $this->cloudinary->uploadApi()->destroy($publicId, [
            'invalidate' => true
        ]);
    }
}