<?php

namespace App\Services\Document;

use App\Models\User;
use App\Services\Media\UserMediaService;
use Illuminate\Http\UploadedFile;

class DocumentService
{
    public function __construct(
        protected UserMediaService $userMediaService
    ) {}



}
