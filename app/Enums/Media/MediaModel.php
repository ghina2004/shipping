<?php

namespace App\Enums\Media;

use App\Models\Podcast;
use App\Models\User;

enum MediaModel: string
{
    case USER_MEDIA = User::class;
}
