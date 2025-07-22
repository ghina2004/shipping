<?php

namespace App\Services\Media;

use App\Enums\Media\MediaCategory;
use App\Enums\Media\MediaFolder;
use App\Enums\Media\MediaModel;
use App\Enums\Media\MediaType;
use App\Models\Media;

class UserMediaService extends MediaService
{
    public function uploadCommercialRegister($file, $userId): Media
    {
        return $this->uploadFile(
            $file,
            $userId,
            MediaModel::USER->value,
            MediaType::COMMERCIAL_REGISTER->value,
            MediaFolder::COMMERCIAL_REGISTER->value
        );
    }
}
