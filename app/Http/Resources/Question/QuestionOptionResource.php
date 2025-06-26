<?php

namespace App\Http\Resources\Question;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuestionOptionResource extends JsonResource
{

    public function toArray($request): array
    {
        $user = auth()->user();
        $isInternalUser = $user->hasRole('employee');

        return [
            'id' => $this->id,
            'value' => $isInternalUser
                ? [
                    'en' => $this->value_en,
                    'ar' => $this->value_ar,
                ]
                : (app()->getLocale() === 'ar' ? $this->value_ar : $this->value_en),
        ];
    }

}
