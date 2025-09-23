<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * Performance Optimization Trait
 * Provides common performance monitoring and optimization methods
 *
 * Expected Impact:
 * - Query time monitoring
 * - Automatic cache management
 * - N+1 query detection
 */
trait PerformanceOptimized
{
    /**
     * Monitor query execution time and log slow queries
     *
     * @param callable $callback
     * @param string $description
     * @param int $slowThreshold Threshold in milliseconds
     * @return mixed
     */
    public static function monitorQuery(callable $callback, string $description = 'Database Query', int $slowThreshold = 100)
    {
        $startTime = microtime(true);
        $startQueries = DB::getQueryLog();

        // Enable query logging for this operation
        DB::enableQueryLog();

        try {
            $result = $callback();

            $endTime = microtime(true);
            $executionTime = ($endTime - $startTime) * 1000; // Convert to milliseconds
            $queries = DB::getQueryLog();
            $queryCount = count($queries) - count($startQueries);

            // Log performance metrics
            if ($executionTime > $slowThreshold) {
                Log::warning('Slow Query Detected', [
                    'description' => $description,
                    'execution_time_ms' => round($executionTime, 2),
                    'query_count' => $queryCount,
                    'queries' => array_slice($queries, count($startQueries))
                ]);
            } else {
                Log::info('Query Performance', [
                    'description' => $description,
                    'execution_time_ms' => round($executionTime, 2),
                    'query_count' => $queryCount
                ]);
            }

            return $result;

        } catch (\Exception $e) {
            Log::error('Query Execution Failed', [
                'description' => $description,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            throw $e;
        }
    }

    /**
     * Cache with automatic invalidation tags
     *
     * @param string $key
     * @param int $ttl
     * @param callable $callback
     * @param array $tags
     * @return mixed
     */
    public static function cacheWithTags(string $key, int $ttl, callable $callback, array $tags = [])
    {
        // Add model class as default tag
        $modelTags = array_merge($tags, [static::class]);

        return Cache::remember($key, $ttl, function() use ($callback, $modelTags) {
            $result = $callback();

            // Store cache tags for invalidation
            Cache::put($key . '_tags', $modelTags, $ttl + 300); // Store tags slightly longer

            return $result;
        });
    }

    /**
     * Clear cache by tags
     *
     * @param array $tags
     * @return void
     */
    public static function clearCacheByTags(array $tags = [])
    {
        $modelTags = array_merge($tags, [static::class]);

        // Get all cache keys with these tags
        $pattern = config('cache.prefix', '') . '*';
        $cacheKeys = [];

        try {
            // This is a simplified approach - in production, consider using Redis tags
            $allKeys = Cache::getRedis()->keys($pattern);

            foreach ($allKeys as $key) {
                $keyTags = Cache::get($key . '_tags', []);
                if (array_intersect($modelTags, $keyTags)) {
                    $cacheKeys[] = str_replace(config('cache.prefix', ''), '', $key);
                }
            }

            foreach ($cacheKeys as $key) {
                Cache::forget($key);
                Cache::forget($key . '_tags');
            }

        } catch (\Exception $e) {
            // Fallback to clearing specific known keys
            Log::warning('Cache tag clearing failed, using fallback', [
                'error' => $e->getMessage(),
                'tags' => $modelTags
            ]);
        }
    }

    /**
     * Optimize SELECT queries by limiting columns
     *
     * @param array $columns
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOptimizedSelect($query, array $columns = ['*'])
    {
        if ($columns === ['*']) {
            // If all columns requested, use specific essential columns for this model
            $columns = $this->getEssentialColumns();
        }

        return $query->select($columns);
    }

    /**
     * Get essential columns for performance optimization
     * Override in models to specify which columns are most commonly needed
     *
     * @return array
     */
    protected function getEssentialColumns(): array
    {
        // Default essential columns - override in specific models
        return [
            $this->getKeyName(),
            'created_at',
            'updated_at'
        ];
    }

    /**
     * Batch operations for better performance
     *
     * @param array $data
     * @param int $batchSize
     * @return bool
     */
    public static function batchInsert(array $data, int $batchSize = 1000): bool
    {
        $chunks = array_chunk($data, $batchSize);

        return DB::transaction(function() use ($chunks) {
            foreach ($chunks as $chunk) {
                static::insert($chunk);
            }
            return true;
        });
    }

    /**
     * Check if queries exceed N+1 threshold and log warning
     *
     * @param callable $callback
     * @param int $threshold
     * @return mixed
     */
    public static function detectN1Queries(callable $callback, int $threshold = 10)
    {
        DB::enableQueryLog();
        $initialQueryCount = count(DB::getQueryLog());

        $result = $callback();

        $finalQueryCount = count(DB::getQueryLog());
        $queryCount = $finalQueryCount - $initialQueryCount;

        if ($queryCount > $threshold) {
            Log::warning('Potential N+1 Query Detected', [
                'query_count' => $queryCount,
                'threshold' => $threshold,
                'model' => static::class,
                'queries' => array_slice(DB::getQueryLog(), $initialQueryCount)
            ]);
        }

        return $result;
    }

    /**
     * Memory-efficient chunked processing
     *
     * @param int $chunkSize
     * @param callable $callback
     * @return void
     */
    public function scopeChunkOptimized($query, int $chunkSize, callable $callback)
    {
        return $query->chunk($chunkSize, function($records) use ($callback) {
            // Clear query cache between chunks to prevent memory buildup
            DB::flushQueryLog();

            return $callback($records);
        });
    }

    /**
     * Boot trait to add automatic performance monitoring
     */
    public static function bootPerformanceOptimized()
    {
        // Monitor model events for performance
        static::creating(function($model) {
            Log::debug('Model Creating', ['model' => get_class($model)]);
        });

        static::created(function($model) {
            // Clear related caches
            static::clearModelCaches();
        });

        static::updated(function($model) {
            // Clear related caches
            static::clearModelCaches();
        });

        static::deleted(function($model) {
            // Clear related caches
            static::clearModelCaches();
        });
    }

    /**
     * Clear model-specific caches
     * Override in models to specify which caches to clear
     */
    protected static function clearModelCaches()
    {
        // Default cache clearing - override in specific models
        $modelName = strtolower(class_basename(static::class));

        $defaultCaches = [
            $modelName . '_all',
            $modelName . '_active',
            $modelName . '_featured',
            $modelName . '_recent',
            'homepage_complete_data_v2'
        ];

        foreach ($defaultCaches as $cacheKey) {
            Cache::forget($cacheKey);
        }
    }
}