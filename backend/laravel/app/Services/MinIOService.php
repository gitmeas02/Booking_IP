<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\UploadedFile;

class MinIOService
{
    protected $disk;
    
    public function __construct()
    {
        $this->disk = Storage::disk('minio');
    }
    
    /**
     * Upload file to private bucket
     */
    public function uploadPrivateFile(UploadedFile $file, string $directory = ''): array
    {
        try {
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $directory ? $directory . '/' . $filename : $filename;
            
            // Upload file with private visibility
            $uploaded = $this->disk->putFileAs('private', $file, $filename, 'private');
            
            if ($uploaded) {
                return [
                    'success' => true,
                    'path' => $uploaded,
                    'filename' => $filename,
                    'url' => $this->getPrivateUrl($uploaded)
                ];
            }
            
            return ['success' => false, 'message' => 'Upload failed'];
            
        } catch (\Exception $e) {
            Log::error('MinIO upload error: ' . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    
    /**
     * Get temporary signed URL for private file access
     */
    public function getPrivateUrl(string $path, int $expiresInMinutes = 60): string
    {
        try {
            // Generate temporary signed URL
            return $this->disk->temporaryUrl($path, now()->addMinutes($expiresInMinutes));
        } catch (\Exception $e) {
            Log::error('MinIO URL generation error: ' . $e->getMessage());
            return '';
        }
    }
    
    /**
     * Check if file exists in private bucket
     */
    public function fileExists(string $path): bool
    {
        return $this->disk->exists($path);
    }
    
    /**
     * Delete file from private bucket
     */
    public function deleteFile(string $path): bool
    {
        try {
            return $this->disk->delete($path);
        } catch (\Exception $e) {
            Log::error('MinIO delete error: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get file contents from private bucket
     */
    public function getFileContents(string $path): ?string
    {
        try {
            if ($this->fileExists($path)) {
                return $this->disk->get($path);
            }
            return null;
        } catch (\Exception $e) {
            Log::error('MinIO get contents error: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * List files in private bucket directory
     */
    public function listFiles(string $directory = ''): array
    {
        try {
            return $this->disk->files($directory);
        } catch (\Exception $e) {
            Log::error('MinIO list files error: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Create presigned URL for direct upload
     */
    public function createPresignedUploadUrl(string $key, int $expiresInMinutes = 15): array
    {
        try {
            $client = $this->disk->getDriver()->getAdapter()->getClient();
            $bucket = env('MINIO_BUCKET');
            
            $command = $client->getCommand('PutObject', [
                'Bucket' => $bucket,
                'Key' => $key,
                'ContentType' => 'application/octet-stream'
            ]);
            
            $request = $client->createPresignedRequest($command, '+' . $expiresInMinutes . ' minutes');
            
            return [
                'success' => true,
                'upload_url' => (string) $request->getUri(),
                'expires_in' => $expiresInMinutes . ' minutes'
            ];
            
        } catch (\Exception $e) {
            Log::error('MinIO presigned URL error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Set bucket policy for private access
     */
    public function setBucketPolicy(string $bucketName, array $policy): bool
    {
        try {
            $client = $this->disk->getDriver()->getAdapter()->getClient();
            
            $client->putBucketPolicy([
                'Bucket' => $bucketName,
                'Policy' => json_encode($policy)
            ]);
            
            return true;
            
        } catch (\Exception $e) {
            Log::error('MinIO bucket policy error: ' . $e->getMessage());
            return false;
        }
    }
}
