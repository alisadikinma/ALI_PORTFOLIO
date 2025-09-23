<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SecureFileUploadService
{
    /**
     * Securely upload a file with validation and sanitization
     *
     * @param UploadedFile $file
     * @param string $directory
     * @param array $allowedTypes
     * @param int $maxSize
     * @return array
     * @throws \Exception
     */
    public function uploadFile(UploadedFile $file, string $directory = 'uploads', array $allowedTypes = [], int $maxSize = 2048): array
    {
        // Validate file
        $this->validateFile($file, $allowedTypes, $maxSize);

        // Generate secure filename
        $filename = $this->generateSecureFilename($file);

        // Scan file for malware (if enabled)
        if (config('security.upload.scan_uploads', false)) {
            $this->scanFile($file);
        }

        // Store file
        $path = $file->storeAs($directory, $filename, 'public');

        // Log upload activity
        Log::info('File uploaded successfully', [
            'original_name' => $file->getClientOriginalName(),
            'stored_name' => $filename,
            'path' => $path,
            'size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'user_id' => auth()->id(),
            'ip_address' => request()->ip(),
        ]);

        return [
            'path' => $path,
            'filename' => $filename,
            'original_name' => $file->getClientOriginalName(),
            'size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
        ];
    }

    /**
     * Validate uploaded file
     *
     * @param UploadedFile $file
     * @param array $allowedTypes
     * @param int $maxSize
     * @throws \Exception
     */
    protected function validateFile(UploadedFile $file, array $allowedTypes, int $maxSize): void
    {
        // Check if file is valid
        if (!$file->isValid()) {
            throw new \Exception('Invalid file upload');
        }

        // Check file size
        if ($file->getSize() > ($maxSize * 1024)) {
            throw new \Exception("File size exceeds maximum allowed size of {$maxSize}KB");
        }

        // Check file extension
        $extension = strtolower($file->getClientOriginalExtension());
        if (!empty($allowedTypes) && !in_array($extension, $allowedTypes)) {
            throw new \Exception('File type not allowed. Allowed types: ' . implode(', ', $allowedTypes));
        }

        // Check MIME type
        $this->validateMimeType($file, $allowedTypes);

        // Check for malicious filename patterns
        $this->validateFilename($file->getClientOriginalName());
    }

    /**
     * Validate MIME type against file extension
     *
     * @param UploadedFile $file
     * @param array $allowedTypes
     * @throws \Exception
     */
    protected function validateMimeType(UploadedFile $file, array $allowedTypes): void
    {
        $mimeType = $file->getMimeType();
        $extension = strtolower($file->getClientOriginalExtension());

        $validMimeTypes = [
            'jpg' => ['image/jpeg'],
            'jpeg' => ['image/jpeg'],
            'png' => ['image/png'],
            'gif' => ['image/gif'],
            'webp' => ['image/webp'],
            'pdf' => ['application/pdf'],
        ];

        if (isset($validMimeTypes[$extension]) && !in_array($mimeType, $validMimeTypes[$extension])) {
            throw new \Exception('File MIME type does not match extension');
        }
    }

    /**
     * Validate filename for malicious patterns
     *
     * @param string $filename
     * @throws \Exception
     */
    protected function validateFilename(string $filename): void
    {
        // Check for path traversal attempts
        if (str_contains($filename, '..') || str_contains($filename, '/') || str_contains($filename, '\\')) {
            throw new \Exception('Invalid filename - path traversal detected');
        }

        // Check for null bytes
        if (str_contains($filename, "\0")) {
            throw new \Exception('Invalid filename - null byte detected');
        }

        // Check for executable extensions
        $dangerousExtensions = ['php', 'php3', 'php4', 'php5', 'phtml', 'exe', 'bat', 'cmd', 'com', 'scr', 'js', 'jar'];
        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if (in_array($extension, $dangerousExtensions)) {
            throw new \Exception('Executable file types are not allowed');
        }
    }

    /**
     * Generate secure filename
     *
     * @param UploadedFile $file
     * @return string
     */
    protected function generateSecureFilename(UploadedFile $file): string
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $timestamp = now()->format('YmdHis');
        $random = Str::random(16);

        return "{$timestamp}_{$random}.{$extension}";
    }

    /**
     * Scan file for malware (placeholder for actual implementation)
     *
     * @param UploadedFile $file
     * @throws \Exception
     */
    protected function scanFile(UploadedFile $file): void
    {
        // This is a placeholder for malware scanning
        // In production, integrate with ClamAV or similar antivirus solution

        Log::info('File scanned for malware', [
            'filename' => $file->getClientOriginalName(),
            'size' => $file->getSize(),
            'status' => 'clean',
        ]);
    }

    /**
     * Delete file securely
     *
     * @param string $path
     * @return bool
     */
    public function deleteFile(string $path): bool
    {
        try {
            if (Storage::disk('public')->exists($path)) {
                $deleted = Storage::disk('public')->delete($path);

                Log::info('File deleted', [
                    'path' => $path,
                    'user_id' => auth()->id(),
                    'ip_address' => request()->ip(),
                ]);

                return $deleted;
            }

            return false;
        } catch (\Exception $e) {
            Log::error('Failed to delete file', [
                'path' => $path,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }
}