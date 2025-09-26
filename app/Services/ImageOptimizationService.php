<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Storage;

class ImageOptimizationService
{
    private array $imageConfig = [
        // Service/portfolio images
        'services' => [
            'max_width' => 1200,
            'quality' => 85,
            'thumbnail_size' => 400,
            'thumbnail_quality' => 80
        ],
        // Gallery images
        'gallery' => [
            'max_width' => 1920,
            'quality' => 85,
            'thumbnail_size' => 300,
            'thumbnail_quality' => 75
        ],
        // Awards/certificates
        'awards' => [
            'max_width' => 800,
            'quality' => 90,
            'thumbnail_size' => 200,
            'thumbnail_quality' => 80
        ],
        // News/blog images
        'news' => [
            'max_width' => 1200,
            'quality' => 85,
            'thumbnail_size' => 400,
            'thumbnail_quality' => 80
        ]
    ];

    /**
     * Optimize and convert image to WebP format
     */
    public function optimizeImage(string $imagePath, string $type = 'gallery'): array
    {
        try {
            if (!file_exists($imagePath)) {
                throw new \Exception("Image file not found: {$imagePath}");
            }

            $config = $this->imageConfig[$type] ?? $this->imageConfig['gallery'];
            $pathInfo = pathinfo($imagePath);
            $directory = dirname($imagePath);
            $baseName = $pathInfo['filename'];

            // Load image
            $manager = new ImageManager(new Driver());
            $image = $manager->read($imagePath);
            $originalSize = filesize($imagePath);

            // Create optimized main image
            $optimizedPath = $directory . '/' . $baseName . '_optimized.webp';
            $resizedImage = $image->scale(width: $config['max_width']);
            $resizedImage->toWebp($config['quality'])->save($optimizedPath);

            // Create thumbnail
            $thumbnailPath = $directory . '/thumb_' . $baseName . '.webp';
            $thumbnailImage = $image->scale(width: $config['thumbnail_size'], height: $config['thumbnail_size']);
            $thumbnailImage->toWebp($config['thumbnail_quality'])->save($thumbnailPath);

            // Create responsive sizes
            $responsiveSizes = [
                'small' => 480,
                'medium' => 768,
                'large' => 1024
            ];

            $responsivePaths = [];
            foreach ($responsiveSizes as $size => $width) {
                $responsivePath = $directory . '/' . $baseName . '_' . $size . '.webp';
                $responsiveImage = $image->scale(width: $width);
                $responsiveImage->toWebp($config['quality'])->save($responsivePath);
                $responsivePaths[$size] = $responsivePath;
            }

            $optimizedSize = filesize($optimizedPath);
            $thumbnailSize = filesize($thumbnailPath);

            Log::info("Image optimized", [
                'original' => $imagePath,
                'original_size' => $originalSize,
                'optimized_size' => $optimizedSize,
                'thumbnail_size' => $thumbnailSize,
                'compression_ratio' => round((1 - $optimizedSize / $originalSize) * 100, 2) . '%'
            ]);

            return [
                'success' => true,
                'original_path' => $imagePath,
                'optimized_path' => $optimizedPath,
                'thumbnail_path' => $thumbnailPath,
                'responsive_paths' => $responsivePaths,
                'original_size' => $originalSize,
                'optimized_size' => $optimizedSize,
                'compression_ratio' => round((1 - $optimizedSize / $originalSize) * 100, 2)
            ];

        } catch (\Exception $e) {
            Log::error("Image optimization failed", [
                'image' => $imagePath,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Batch optimize all images in directory
     */
    public function optimizeDirectory(string $directoryPath, string $type = 'gallery'): array
    {
        $results = [];
        $extensions = ['jpg', 'jpeg', 'png'];

        foreach ($extensions as $ext) {
            $images = glob($directoryPath . '/*.' . $ext);
            foreach ($images as $image) {
                // Skip already optimized images
                if (strpos(basename($image), '_optimized') !== false ||
                    strpos(basename($image), 'thumb_') !== false ||
                    strpos(basename($image), '_small') !== false ||
                    strpos(basename($image), '_medium') !== false ||
                    strpos(basename($image), '_large') !== false) {
                    continue;
                }

                $result = $this->optimizeImage($image, $type);
                $results[] = $result;
            }
        }

        return $results;
    }

    /**
     * Generate responsive image HTML with srcset
     */
    public function generateResponsiveImageHtml(string $basePath, string $alt = '', array $classes = []): string
    {
        $pathInfo = pathinfo($basePath);
        $directory = dirname($basePath);
        $baseName = $pathInfo['filename'];

        // Remove any existing suffixes
        $baseName = preg_replace('/(thumb_|_optimized|_small|_medium|_large)/', '', $baseName);

        $classString = !empty($classes) ? 'class="' . implode(' ', $classes) . '"' : '';

        return sprintf(
            '<img src="%s"
                  srcset="%s 480w, %s 768w, %s 1024w, %s 1200w"
                  sizes="(max-width: 480px) 100vw, (max-width: 768px) 50vw, (max-width: 1024px) 33vw, 25vw"
                  alt="%s"
                  %s
                  loading="lazy"
                  decoding="async"
                  onload="this.style.opacity=1"
                  style="opacity:0;transition:opacity 0.3s ease">',
            $directory . '/' . $baseName . '_small.webp',
            $directory . '/' . $baseName . '_small.webp',
            $directory . '/' . $baseName . '_medium.webp',
            $directory . '/' . $baseName . '_large.webp',
            $directory . '/' . $baseName . '_optimized.webp',
            htmlspecialchars($alt),
            $classString
        );
    }

    /**
     * Clean up old unoptimized images (after verification)
     */
    public function cleanupOriginalImages(string $directoryPath, bool $dryRun = true): array
    {
        $deleted = [];
        $extensions = ['jpg', 'jpeg', 'png'];

        foreach ($extensions as $ext) {
            $images = glob($directoryPath . '/*.' . $ext);
            foreach ($images as $image) {
                $pathInfo = pathinfo($image);
                $baseName = $pathInfo['filename'];
                $optimizedVersion = dirname($image) . '/' . $baseName . '_optimized.webp';

                if (file_exists($optimizedVersion)) {
                    if (!$dryRun) {
                        unlink($image);
                        $deleted[] = $image;
                    } else {
                        $deleted[] = $image . ' (would be deleted)';
                    }
                }
            }
        }

        return $deleted;
    }
}