<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class DatabaseBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:backup
                            {--compress : Compress the backup file}
                            {--path= : Custom backup path}
                            {--tables= : Specific tables to backup (comma-separated)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a professional database backup for the portfolio system';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔧 Professional Database Backup System');
        $this->info('=====================================');

        try {
            // Get database configuration
            $database = config('database.default');
            $config = config("database.connections.{$database}");

            $host = $config['host'] ?? 'localhost';
            $port = $config['port'] ?? 3306;
            $dbname = $config['database'];
            $username = $config['username'];
            $password = $config['password'];

            // Validate database connection
            $this->info("🔍 Validating database connection...");
            DB::connection()->getPdo();
            $this->info("✅ Database connection verified");

            // Generate backup filename
            $timestamp = now()->format('Y-m-d_H-i-s');
            $filename = "portfolio_backup_{$timestamp}.sql";

            // Check for custom path
            $customPath = $this->option('path');
            if ($customPath) {
                $backupPath = rtrim($customPath, '/') . '/' . $filename;
            } else {
                // Default to storage/app/backups
                $backupDir = storage_path('app/backups');
                if (!is_dir($backupDir)) {
                    mkdir($backupDir, 0755, true);
                }
                $backupPath = $backupDir . '/' . $filename;
            }

            // Build mysqldump command
            $command = sprintf(
                'mysqldump --host=%s --port=%s --user=%s --password=%s --single-transaction --routines --triggers --complete-insert %s',
                escapeshellarg($host),
                escapeshellarg($port),
                escapeshellarg($username),
                escapeshellarg($password),
                escapeshellarg($dbname)
            );

            // Add specific tables if requested
            $tables = $this->option('tables');
            if ($tables) {
                $tableList = explode(',', $tables);
                $command .= ' ' . implode(' ', array_map('escapeshellarg', $tableList));
                $this->info("📋 Backing up specific tables: " . implode(', ', $tableList));
            } else {
                $this->info("📊 Backing up all tables");
            }

            // Execute backup
            $this->info("💾 Creating backup...");
            $output = [];
            $returnVar = 0;

            exec($command . ' > ' . escapeshellarg($backupPath) . ' 2>&1', $output, $returnVar);

            if ($returnVar !== 0) {
                throw new \Exception('Backup failed: ' . implode("\n", $output));
            }

            // Verify backup file was created
            if (!file_exists($backupPath) || filesize($backupPath) === 0) {
                throw new \Exception('Backup file was not created or is empty');
            }

            $fileSize = $this->formatBytes(filesize($backupPath));
            $this->info("✅ Backup created successfully");
            $this->info("📁 Location: {$backupPath}");
            $this->info("📦 Size: {$fileSize}");

            // Compress if requested
            if ($this->option('compress')) {
                $this->info("🗜️ Compressing backup...");
                $compressedPath = $backupPath . '.gz';

                if (function_exists('gzencode')) {
                    $data = file_get_contents($backupPath);
                    $compressed = gzencode($data, 9);
                    file_put_contents($compressedPath, $compressed);

                    // Remove original if compression successful
                    if (file_exists($compressedPath)) {
                        unlink($backupPath);
                        $compressedSize = $this->formatBytes(filesize($compressedPath));
                        $this->info("✅ Backup compressed successfully");
                        $this->info("📁 Compressed location: {$compressedPath}");
                        $this->info("📦 Compressed size: {$compressedSize}");
                    }
                } else {
                    $this->warn("⚠️ PHP gzip extension not available for compression");
                }
            }

            // Generate backup report
            $this->generateBackupReport($dbname, $backupPath);

            $this->info("\n🎉 Professional database backup completed successfully!");

            return 0;

        } catch (\Exception $e) {
            $this->error("❌ Backup failed: " . $e->getMessage());
            return 1;
        }
    }

    /**
     * Generate a backup report with database statistics
     */
    private function generateBackupReport($database, $backupPath)
    {
        $this->info("\n📊 Backup Report");
        $this->info("================");

        try {
            // Get table statistics
            $tables = DB::select("
                SELECT
                    table_name,
                    table_rows,
                    ROUND(((data_length + index_length) / 1024 / 1024), 2) AS size_mb
                FROM information_schema.tables
                WHERE table_schema = ?
                AND table_type = 'BASE TABLE'
                ORDER BY size_mb DESC
            ", [$database]);

            $this->table(
                ['Table', 'Rows', 'Size (MB)'],
                array_map(function($table) {
                    return [
                        $table->table_name,
                        number_format($table->table_rows),
                        $table->size_mb . ' MB'
                    ];
                }, $tables)
            );

            // Calculate totals
            $totalRows = array_sum(array_column($tables, 'table_rows'));
            $totalSize = array_sum(array_column($tables, 'size_mb'));

            $this->info("📈 Total Records: " . number_format($totalRows));
            $this->info("💽 Total Database Size: " . number_format($totalSize, 2) . " MB");
            $this->info("🕒 Backup Timestamp: " . now()->format('Y-m-d H:i:s'));

        } catch (\Exception $e) {
            $this->warn("⚠️ Could not generate detailed report: " . $e->getMessage());
        }
    }

    /**
     * Format bytes to human readable format
     */
    private function formatBytes($size, $precision = 2)
    {
        $base = log($size, 1024);
        $suffixes = ['B', 'KB', 'MB', 'GB', 'TB'];
        return round(pow(1024, $base - floor($base)), $precision) . ' ' . $suffixes[floor($base)];
    }
}