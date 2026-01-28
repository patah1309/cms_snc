<?php

namespace App\Support;

trait ImageCompression
{
    protected function saveUploadedImage($file, string $dir, string $filename): void
    {
        $destPath = $dir . DIRECTORY_SEPARATOR . $filename;
        $ext = strtolower($file->getClientOriginalExtension());
        if (!$this->compressAndSaveImage($file->getRealPath(), $destPath, $ext)) {
            $file->move($dir, $filename);
        }
    }

    protected function compressAndSaveImage(string $srcPath, string $destPath, string $ext): bool
    {
        if (!extension_loaded('gd') || !function_exists('getimagesize')) {
            return false;
        }
        $info = @getimagesize($srcPath);
        if (!$info) {
            return false;
        }
        [$width, $height] = $info;
        if (!$width || !$height) {
            return false;
        }

        $source = $this->createImageFromPath($srcPath, $ext, $info['mime'] ?? null);
        if (!$source) {
            return false;
        }

        $dest = imagecreatetruecolor($width, $height);
        if (!$dest) {
            imagedestroy($source);
            return false;
        }

        if ($ext === 'png') {
            imagealphablending($dest, false);
            imagesavealpha($dest, true);
            $transparent = imagecolorallocatealpha($dest, 0, 0, 0, 127);
            imagefilledrectangle($dest, 0, 0, $width, $height, $transparent);
        } elseif ($ext === 'gif') {
            $transparentIndex = imagecolortransparent($source);
            if ($transparentIndex >= 0) {
                $transparentColor = imagecolorsforindex($source, $transparentIndex);
                $transparentIndex = imagecolorallocate(
                    $dest,
                    $transparentColor['red'],
                    $transparentColor['green'],
                    $transparentColor['blue']
                );
                imagefill($dest, 0, 0, $transparentIndex);
                imagecolortransparent($dest, $transparentIndex);
            }
        }

        imagecopy($dest, $source, 0, 0, 0, 0, $width, $height);

        $saved = $this->saveImageToPath($dest, $destPath, $ext);

        imagedestroy($source);
        imagedestroy($dest);

        return $saved;
    }

    protected function createImageFromPath(string $path, string $ext, ?string $mime)
    {
        $type = $mime ?: $ext;
        switch ($type) {
            case 'image/jpeg':
            case 'image/jpg':
            case 'jpg':
            case 'jpeg':
                return @imagecreatefromjpeg($path);
            case 'image/png':
            case 'png':
                return @imagecreatefrompng($path);
            case 'image/gif':
            case 'gif':
                return @imagecreatefromgif($path);
            case 'image/webp':
            case 'webp':
                return function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($path) : null;
            default:
                return null;
        }
    }

    protected function saveImageToPath($image, string $path, string $ext): bool
    {
        switch ($ext) {
            case 'jpg':
            case 'jpeg':
                return @imagejpeg($image, $path, 80);
            case 'png':
                return @imagepng($image, $path, 6);
            case 'gif':
                return @imagegif($image, $path);
            case 'webp':
                return function_exists('imagewebp') ? @imagewebp($image, $path, 80) : false;
            default:
                return false;
        }
    }
}
