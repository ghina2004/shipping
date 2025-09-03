<?php

namespace App\Services\Media;

use App\Models\Media;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaService
{
    public function createMedia($med_id,$med_type,$type,$url): Media
    {
        return Media::query()->create([
            'mediable_id' => $med_id,
            'mediable_type' => $med_type,
            'type' => $type,
            'url' => $url,
        ]);
    }
    public function uploadFile(UploadedFile $file,$med_id,$med_type,$type,$folder): Media
    {
        $this->deleteOldMedia($med_id, $med_type, $type);

        $filePath = $this->storeFile($file, $folder);

        return $this->createMedia($med_id, $med_type, $type, $filePath);

    }
    public function deleteFile(Media $media): void
    {
        Storage::disk('public')->delete($media->url);
        $media->delete();
    }

    public function deleteOldMedia($med_id, $med_type, $type): void
    {
        $oldMedia = Media::where('mediable_id', $med_id)
            ->where('mediable_type', $med_type)
            ->where('type', $type)
            ->first();

        if ($oldMedia) {
            $this->deleteFile($oldMedia);
        }
    }


    private function storeFile(UploadedFile $file, string $folder): string
    {
        $filename = Str::random(32) . "." . time() . '.' . $file->getClientOriginalExtension();

        $destinationPath = public_path($folder);

        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        $file->move($destinationPath, $filename);

        return $folder . '/' . $filename;
    }
}
