<?php

namespace App\Services\Media;

use App\Exceptions\Types\CustomException;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaService
{
    public function createMedia($id,$med_type,$type,$category,$url){

        return Media::query()->create([
            'mediable_id' => $id,
            'mediable_type' => $med_type,
            'type' => $type,
            'category' => $category,
            'url' => $url,
        ]);
    }
    public function uploadFile(UploadedFile $file,$id,$model,$type,$category,$folder): array
    {
        $filePath = $this->storeFile($file, $folder);

        $media = $this->createMedia($id, $model, $type, $category, $filePath);

        return ['data' => $media, 'message' => 'File Created Successfully.', 'code' => 200];
    }
    public function uploadMultipleFile(array $files, $id, $model, $type, $category, $folder): array
    {
        $mediaFiles = [];

        foreach ($files as $file) {
            $mediaFiles[] = $this->uploadFile($file, $id, $model, $type, $category, $folder);
        }

        return ['data' => $mediaFiles, 'message' => 'Files uploaded successfully.', 'code' => 200];
    }
    public function deleteFile(Media $media): array
    {
        Storage::disk('public')->delete($media->url);
        $media->delete();

        return ['data' => [], 'message' => 'File Deleted Successfully.', 'code' => 200];
    }
    public function updateFile(Media $media, UploadedFile $file, $folder): array
    {
        $this->deleteFile($media);

        $filePath = $this->storeFile($file, $folder);

        $media->update([
            'url' => $filePath,
        ]);

        return ['data' => $media, 'message' => 'File updated successfully.', 'code' => 200];
    }
    private function storeFile(UploadedFile $file, string $folder): string
    {
        $filename = Str::random(32) . "." . time() . '.' . $file->getClientOriginalExtension();
        $filePath = $folder . '/' . $filename;

        Storage::disk('public')->put($filePath, file_get_contents($file));

        return $filePath;
    }
}
