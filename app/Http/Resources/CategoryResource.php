<?php

namespace App\Http\Resources;

use App\Http\Resources\Question\QuestionResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        $user = auth()->user();
        $isInternalUser = $user->hasRole('employee');

        return [
            'id' => $this->id,
            'name' => $isInternalUser
                ? [
                    'en' => $this->name_en,
                    'ar' => $this->name_ar,
                ]
                : (app()->getLocale() === 'ar' ? $this->name_ar : $this->name_en),
        ];
    }
}
