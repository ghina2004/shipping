<?php

namespace App\Http\Controllers\User;

use App\Enums\Media\MediaCategory;
use App\Enums\Media\MediaModel;
use App\Enums\Media\MediaFolder;
use App\Enums\Media\MediaType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Media\MultipleImageRequest;
use App\Http\Requests\Media\SingleImageRequest;
use App\Models\Media;
use App\Services\Media\UserMediaService;
use App\Services\Media\MediaService;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;

class ImageController extends Controller
{
    use ResponseTrait;
    private MediaService $mediaService;
    private UserMediaService  $imageService;

    public function __construct(UserMediaService $imageService){
        $this->imageService = $imageService;
    }



}
