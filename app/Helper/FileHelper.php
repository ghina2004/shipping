<?php

namespace App\Helper;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileHelper
{
    public static function upload(UploadedFile $file, string $folder): string
    {
        $filename = Str::random(32) . '.' . time() . '.' . $file->getClientOriginalExtension();
        $relativePath = $folder . '/' . $filename;
        $destinationPath = public_path($folder);

        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        $file->move($destinationPath, $filename);

        return $relativePath;
    }

    public static function delete(?string $filePath): void
    {
        if ($filePath) {
            $fullPath = public_path($filePath);
            if (file_exists($fullPath)) {
                unlink($fullPath);
            }
        }
    }
}
