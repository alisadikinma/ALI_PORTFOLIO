<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Use array cache in development for better responsiveness
        if ($this->app->environment('local')) {
            config(['cache.default' => 'array']);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Auto-clear view cache in development when files change
        if (app()->environment('local')) {
            $this->autoCleanViewCache();
        }
    }

    /**
     * Auto clean view cache in development
     */
    private function autoCleanViewCache()
    {
        try {
            // Clear view cache directory periodically in development
            $viewCachePath = storage_path('framework/views-dev');
            if (File::exists($viewCachePath)) {
                $files = File::files($viewCachePath);
                foreach ($files as $file) {
                    // Delete cache files older than 1 minute in development
                    if (time() - File::lastModified($file) > 60) {
                        File::delete($file);
                    }
                }
            }
        } catch (\Exception $e) {
            // Silently handle any file operations errors
        }
    }
}
