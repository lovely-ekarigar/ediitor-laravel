<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateQuestionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'category_id' => 'required|exists:categories,id',
            'question_text' => 'required|string',
            'difficulty' => 'required|in:Easy,Medium,Hard',
            'marks' => 'nullable|integer|min:1',
            'status' => 'required|in:Draft,Published',
            'options' => 'required|array|min:2',
            'options.*.option_text' => 'required|string',
            'options.*.is_correct' => 'nullable|boolean',
            'correct_option' => 'required|integer|min:0',
        ];
    }

    /**
     * Get custom error messages.
     */
    public function messages(): array
    {
        return [
            'category_id.required' => 'Please select a category.',
            'category_id.exists' => 'The selected category is invalid.',
            'question_text.required' => 'The question text is required.',
            'difficulty.required' => 'Please select a difficulty level.',
            'difficulty.in' => 'The difficulty must be Easy, Medium, or Hard.',
            'status.required' => 'Please select a status.',
            'status.in' => 'The status must be Draft or Published.',
            'options.required' => 'At least 2 options are required.',
            'options.min' => 'At least 2 options are required.',
            'options.*.option_text.required' => 'All option texts are required.',
            'correct_option.required' => 'Please mark one option as correct.',
        ];
    }
}
