<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\QuestionOptions;
use App\Models\ShipmentQuestion;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;


class QuestionSeeder extends Seeder
{
    public function run(): void
    {
        $jsonPath = database_path('data/questions.json');

        if (!File::exists($jsonPath)) {
            throw new \Exception("File shipment_questions.json not found.");
        }

        $data = json_decode(File::get($jsonPath), true);

        foreach ($data as $categoryFullName => $questions) {
            [$name_ar, $name_en] = explode(' / ', $categoryFullName);

            $category = Category::query()->create([
                'name_ar' => trim($name_ar),
                'name_en' => trim($name_en),
            ]);

            foreach ($questions as $questionData) {
                $question = ShipmentQuestion::query()->create([
                    'question_ar' => $questionData['text_ar'],
                    'question_en' => $questionData['text_en'],
                    'type' => $questionData['type'],
                ]);

                $question->categories()->sync([$category->id]);

                if (!empty($questionData['options'])) {
                    foreach ($questionData['options'] as $option) {
                        [$value_ar, $value_en] = explode('|', $option);
                        QuestionOptions::query()->create([
                            'shipment_question_id' => $question->id,
                            'value_ar' => trim($value_ar),
                            'value_en' => trim($value_en),
                        ]);
                    }
                }
            }
        }
    }
}
