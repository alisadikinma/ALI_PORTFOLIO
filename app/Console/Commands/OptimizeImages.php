<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ImageOptimizationService;

class OptimizeImages extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'images:optimize
                           {directory? : Directory to optimize (default: all)}
                           {--dry-run : Show what would be done without actually doing it}
                           {--cleanup : Remove original images after optimization}';

    /**
     * The console command description.
     */
    protected $description = 'Optimize portfolio images for web performance';

    private ImageOptimizationService $imageService;

    public function __construct(ImageOptimizationService $imageService)
    {
        parent::__construct();
        $this->imageService = $imageService;
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $directory = $this->argument('directory');
        $dryRun = $this->option('dry-run');
        $cleanup = $this->option('cleanup');

        $this->info('🚀 Starting Image Optimization Process...');

        if ($dryRun) {
            $this->warn('🔍 DRY RUN MODE - No files will be modified');
        }

        $directories = $this->getDirectoriesToProcess($directory);
        $totalSavings = 0;
        $totalFiles = 0;

        foreach ($directories as $dir => $type) {
            $this->info("\n📁 Processing {$dir} ({$type} images)...");

            if (!is_dir($dir)) {
                $this->warn("Directory {$dir} does not exist, skipping...");
                continue;
            }

            if ($dryRun) {
                $this->showDryRunInfo($dir);
                continue;
            }

            $results = $this->imageService->optimizeDirectory($dir, $type);

            $this->displayResults($results);

            foreach ($results as $result) {
                if ($result['success']) {
                    $totalSavings += ($result['original_size'] - $result['optimized_size']);
                    $totalFiles++;
                }
            }

            if ($cleanup && !$dryRun) {
                $this->info("\n🧹 Cleaning up original images...");
                $cleaned = $this->imageService->cleanupOriginalImages($dir, false);
                $this->info("Removed " . count($cleaned) . " original images");
            }
        }

        if (!$dryRun) {
            $this->info("\n✅ Optimization Complete!");
            $this->info("📊 Total files optimized: {$totalFiles}");
            $this->info("💾 Total space saved: " . $this->formatBytes($totalSavings));
            $this->info("📈 Average compression: " .
                       round($totalFiles > 0 ? ($totalSavings / ($totalSavings + $totalFiles)) * 100 : 0, 2) . "%");
        }

        return 0;
    }

    private function getDirectoriesToProcess(?string $directory): array
    {
        if ($directory) {
            return [public_path("file/{$directory}") => $directory];
        }

        return [
            public_path('file/galeri') => 'gallery',
            public_path('file/layanan') => 'services',
            public_path('file/award') => 'awards',
            public_path('file/berita') => 'news'
        ];
    }

    private function showDryRunInfo(string $directory): void
    {
        $extensions = ['jpg', 'jpeg', 'png'];
        $images = [];

        foreach ($extensions as $ext) {
            $found = glob($directory . '/*.' . $ext);
            $images = array_merge($images, $found);
        }

        // Filter out already optimized images
        $images = array_filter($images, function($image) {
            $basename = basename($image);
            return !preg_match('/(thumb_|_optimized|_small|_medium|_large)/', $basename);
        });

        $totalSize = array_sum(array_map('filesize', $images));

        $this->line("  📸 Found " . count($images) . " images to optimize");
        $this->line("  💾 Total size: " . $this->formatBytes($totalSize));
        $this->line("  🎯 Estimated savings: ~" . $this->formatBytes($totalSize * 0.7) . " (70% compression)");

        if (count($images) > 0) {
            $this->line("  📋 Largest images:");
            usort($images, function($a, $b) {
                return filesize($b) - filesize($a);
            });

            foreach (array_slice($images, 0, 5) as $image) {
                $this->line("    • " . basename($image) . " - " . $this->formatBytes(filesize($image)));
            }
        }
    }

    private function displayResults(array $results): void
    {
        $successful = array_filter($results, fn($r) => $r['success']);
        $failed = array_filter($results, fn($r) => !$r['success']);

        $this->info("✅ Successfully optimized: " . count($successful));

        if (!empty($failed)) {
            $this->error("❌ Failed to optimize: " . count($failed));
        }

        foreach ($successful as $result) {
            $savings = $this->formatBytes($result['original_size'] - $result['optimized_size']);
            $this->line("  • " . basename($result['original_path']) .
                       " → {$result['compression_ratio']}% smaller ({$savings} saved)");
        }

        foreach ($failed as $result) {
            $this->error("  ❌ " . basename($result['original_path']) . ": " . $result['error']);
        }
    }

    private function formatBytes(int $bytes): string
    {
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' B';
        }
    }
}