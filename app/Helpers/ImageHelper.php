<?php

namespace App\Helpers;

class ImageHelper
{
    /**
     * Get optimized image path with fallback to original
     */
    public static function getOptimizedImagePath(string $originalPath, string $size = 'optimized'): string
    {
        $pathInfo = pathinfo($originalPath);
        $directory = dirname($originalPath);
        $basename = $pathInfo['filename'];

        // Check for optimized WebP version
        $optimizedPath = $directory . '/' . $basename . '_' . $size . '.webp';

        if (file_exists(public_path($optimizedPath))) {
            return $optimizedPath;
        }

        // Fallback to original
        return $originalPath;
    }

    /**
     * Generate responsive image HTML with WebP support and fallback
     */
    public static function generateResponsiveImage(string $originalPath, string $alt = '', array $classes = []): string
    {
        $pathInfo = pathinfo($originalPath);
        $directory = dirname($originalPath);
        $basename = $pathInfo['filename'];

        // Remove 'file/' prefix if present for file system check
        $publicPath = str_replace('file/', '', $directory);
        $publicDirectory = public_path($publicPath);

        // Check if optimized versions exist
        $optimizedExists = file_exists($publicDirectory . '/' . $basename . '_optimized.webp');
        $responsiveExists = [
            'small' => file_exists($publicDirectory . '/' . $basename . '_small.webp'),
            'medium' => file_exists($publicDirectory . '/' . $basename . '_medium.webp'),
            'large' => file_exists($publicDirectory . '/' . $basename . '_large.webp')
        ];

        $classString = !empty($classes) ? 'class="' . implode(' ', $classes) . '"' : '';

        if ($optimizedExists && array_filter($responsiveExists)) {
            // Full responsive image with WebP and fallbacks
            return sprintf(
                '<picture>
                    <source
                        srcset="%s 480w, %s 768w, %s 1024w, %s 1200w"
                        sizes="(max-width: 480px) 100vw, (max-width: 768px) 50vw, (max-width: 1024px) 33vw, 25vw"
                        type="image/webp">
                    <img
                        src="%s"
                        srcset="%s 480w, %s 768w, %s 1024w, %s 1200w"
                        sizes="(max-width: 480px) 100vw, (max-width: 768px) 50vw, (max-width: 1024px) 33vw, 25vw"
                        alt="%s"
                        %s
                        loading="lazy"
                        decoding="async"
                        onload="this.style.opacity=1"
                        style="opacity:0;transition:opacity 0.3s ease">
                </picture>',
                asset($directory . '/' . $basename . '_small.webp'),
                asset($directory . '/' . $basename . '_medium.webp'),
                asset($directory . '/' . $basename . '_large.webp'),
                asset($directory . '/' . $basename . '_optimized.webp'),
                asset($originalPath), // fallback
                asset($directory . '/' . $basename . '_small.webp'),
                asset($directory . '/' . $basename . '_medium.webp'),
                asset($directory . '/' . $basename . '_large.webp'),
                asset($directory . '/' . $basename . '_optimized.webp'),
                htmlspecialchars($alt),
                $classString
            );
        } elseif ($optimizedExists) {
            // Just optimized WebP with fallback
            return sprintf(
                '<picture>
                    <source src="%s" type="image/webp">
                    <img src="%s" alt="%s" %s loading="lazy" decoding="async">
                </picture>',
                asset($directory . '/' . $basename . '_optimized.webp'),
                asset($originalPath),
                htmlspecialchars($alt),
                $classString
            );
        }

        // No optimization available, return original with lazy loading
        return sprintf(
            '<img src="%s" alt="%s" %s loading="lazy" decoding="async">',
            asset($originalPath),
            htmlspecialchars($alt),
            $classString
        );
    }

    /**
     * Get optimized asset URL
     */
    public static function optimizedAsset(string $path, string $size = 'optimized'): string
    {
        return asset(self::getOptimizedImagePath($path, $size));
    }

    /**
     * Check if optimized version exists
     */
    public static function hasOptimizedVersion(string $path): bool
    {
        $pathInfo = pathinfo($path);
        $directory = dirname($path);
        $basename = $pathInfo['filename'];

        $publicPath = str_replace('file/', '', $directory);
        $optimizedPath = public_path($publicPath . '/' . $basename . '_optimized.webp');

        return file_exists($optimizedPath);
    }
}