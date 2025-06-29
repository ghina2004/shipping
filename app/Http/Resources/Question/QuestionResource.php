<?php

namespace App\Http\Resources\Question;

use Illuminate\Http\Resources\Json\JsonResource;

class QuestionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        $user = auth()->user();
        $isInternalUser = $user && ($user->hasRole('employee'));

        return [
            'id' => $this->id,
            'type' => $this->type,
            'question' => $isInternalUser
                ? [
                    'ar' => $this->question_ar,
                    'en' => $this->question_en,
                ]
                : (app()->getLocale() === 'ar' ? $this->question_ar : $this->question_en),
            'options' => QuestionOptionResource::collection($this->whenLoaded('questionOption')),
        ];
    }
}
