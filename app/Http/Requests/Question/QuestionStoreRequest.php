<?php

namespace App\Http\Requests\Question;

use Illuminate\Foundation\Http\FormRequest;

class QuestionStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'title' => 'required|string',
            'quiz_id' => 'required|integer|exists:quizzes,id',
            'answers' => 'required|array',
            'answers.*.text' => 'required|string',
            'answers.*.is_correct' => 'required|boolean',
        ];
    }
}
