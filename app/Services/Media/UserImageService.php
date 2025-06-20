<?php

namespace App\Services\Media;

use App\Enums\Media\MediaCategory;
use App\Enums\Media\MediaFolder;
use App\Enums\Media\MediaModel;
use App\Enums\Media\MediaType;
use App\Models\Media;

class UserImageService extends MediaService
{
    public function uploadUserProfileImage($file, $userId): array
    {
        return $this->uploadFile(
            $file,
            $userId,
            MediaModel::USER_MEDIA->value,
            MediaType::IMAGE->value,
            MediaCategory::USER_PROFILE->value,
            MediaFolder::USER_PROFILE->value
        );
    }

    public function deleteUserImage(Media $media): array
    {
        return $this->deleteFile($media);
    }
}
