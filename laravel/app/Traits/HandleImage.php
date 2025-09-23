<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

trait HandleImage
{
    /**
     * Upload và resize ảnh
     *
     * @param UploadedFile $file
     * @param string $folder
     * @param int $width
     * @param int $height
     * @return string|null
     */
    public function uploadImage(UploadedFile $file, string $folder = 'images', int $width = 800, int $height = 600): ?string
    {
        try {
            // Tạo tên file unique
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            
            // Tạo đường dẫn đầy đủ
            $path = $folder . '/' . $filename;
            
            // Resize và lưu ảnh
            $image = Image::make($file)
                ->resize($width, $height, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })
                ->encode('jpg', 90);
            
            // Lưu vào storage
            Storage::disk('public')->put($path, $image);
            
            return $filename;
        } catch (\Exception $e) {
            \Log::error('Image upload failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Xóa ảnh cũ
     *
     * @param string $filename
     * @param string $folder
     * @return bool
     */
    public function deleteImage(string $filename, string $folder = 'images'): bool
    {
        try {
            if ($filename && Storage::disk('public')->exists($folder . '/' . $filename)) {
                Storage::disk('public')->delete($folder . '/' . $filename);
                return true;
            }
            return false;
        } catch (\Exception $e) {
            \Log::error('Image delete failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Cập nhật ảnh (xóa cũ và upload mới)
     *
     * @param UploadedFile $file
     * @param string $oldFilename
     * @param string $folder
     * @param int $width
     * @param int $height
     * @return string|null
     */
    public function updateImage(UploadedFile $file, ?string $oldFilename, string $folder = 'images', int $width = 800, int $height = 600): ?string
    {
        // Xóa ảnh cũ
        if ($oldFilename) {
            $this->deleteImage($oldFilename, $folder);
        }
        
        // Upload ảnh mới
        return $this->uploadImage($file, $folder, $width, $height);
    }
} 