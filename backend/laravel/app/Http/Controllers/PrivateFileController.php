<?php

namespace App\Http\Controllers;

use App\Services\MinIOService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

class PrivateFileController extends Controller
{
    protected $minioService;
    
    public function __construct(MinIOService $minioService)
    {
        $this->minioService = $minioService;
    }
    
    /**
     * Upload file to private bucket
     */
    public function uploadPrivateFile(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:10240', // 10MB max
            'directory' => 'nullable|string'
        ]);
        
        $result = $this->minioService->uploadPrivateFile(
            $request->file('file'),
            $request->get('directory', '')
        );
        
        return response()->json($result);
    }
    
    /**
     * Get private file with temporary URL
     */
    public function getPrivateFile(Request $request, string $path)
    {
        // Check if user has permission to access this file
        if (!$this->userCanAccessFile($request, $path)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        if (!$this->minioService->fileExists($path)) {
            return response()->json(['error' => 'File not found'], 404);
        }
        
        $temporaryUrl = $this->minioService->getPrivateUrl($path, 60);
        
        if ($temporaryUrl) {
            return response()->json([
                'success' => true,
                'url' => $temporaryUrl,
                'expires_in' => '60 minutes'
            ]);
        }
        
        return response()->json(['error' => 'Failed to generate URL'], 500);
    }
    
    /**
     * Stream private file directly
     */
    public function streamPrivateFile(Request $request, string $path)
    {
        // Check if user has permission to access this file
        if (!$this->userCanAccessFile($request, $path)) {
            abort(403, 'Unauthorized');
        }
        
        $disk = Storage::disk('minio');
        
        if (!$disk->exists($path)) {
            abort(404, 'File not found');
        }
        
        $file = $disk->get($path);
        $mimeType = $disk->mimeType($path);
        
        return Response::make($file, 200, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . basename($path) . '"'
        ]);
    }
    
    /**
     * Download private file
     */
    public function downloadPrivateFile(Request $request, string $path)
    {
        // Check if user has permission to access this file
        if (!$this->userCanAccessFile($request, $path)) {
            abort(403, 'Unauthorized');
        }
        
        $disk = Storage::disk('minio');
        
        if (!$disk->exists($path)) {
            abort(404, 'File not found');
        }
        
        return $disk->download($path);
    }
    
    /**
     * Delete private file
     */
    public function deletePrivateFile(Request $request, string $path)
    {
        // Check if user has permission to delete this file
        if (!$this->userCanDeleteFile($request, $path)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $result = $this->minioService->deleteFile($path);
        
        return response()->json([
            'success' => $result,
            'message' => $result ? 'File deleted successfully' : 'Failed to delete file'
        ]);
    }
    
    /**
     * List files in private directory
     */
    public function listPrivateFiles(Request $request, string $directory = '')
    {
        // Check if user has permission to list files in this directory
        if (!$this->userCanListFiles($request, $directory)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $files = $this->minioService->listFiles($directory);
        
        return response()->json([
            'success' => true,
            'files' => $files
        ]);
    }
    
    /**
     * Check if user can access specific file
     */
    private function userCanAccessFile(Request $request, string $path): bool
    {
        $user = $request->user();
        
        if (!$user) {
            return false;
        }
        
        // Add your authorization logic here
        // For example, check if file belongs to user or user has proper role
        
        // Example: Check if path contains user ID or if user is admin
        if (str_contains($path, 'user_' . $user->id) || $user->hasRole('admin')) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Check if user can delete specific file
     */
    private function userCanDeleteFile(Request $request, string $path): bool
    {
        $user = $request->user();
        
        if (!$user) {
            return false;
        }
        
        // Add your authorization logic here
        // Usually stricter than read access
        
        if (str_contains($path, 'user_' . $user->id) || $user->hasRole('admin')) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Check if user can list files in directory
     */
    private function userCanListFiles(Request $request, string $directory): bool
    {
        $user = $request->user();
        
        if (!$user) {
            return false;
        }
        
        // Add your authorization logic here
        if (str_contains($directory, 'user_' . $user->id) || $user->hasRole('admin')) {
            return true;
        }
        
        return false;
    }
}
