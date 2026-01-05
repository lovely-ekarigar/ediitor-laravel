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
            'question_text' => ['required', 'string', function ($attribute, $value, $fail) {
                // Validate JSON structure for Editor.js format
                $decoded = json_decode($value, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    $fail('The question text must be valid JSON.');
                    return;
                }
                if (!isset($decoded['blocks']) || !is_array($decoded['blocks'])) {
                    $fail('The question text must be in Editor.js format with blocks array.');
                    return;
                }
                // Check if at least one block has content
                $hasContent = false;
                foreach ($decoded['blocks'] as $block) {
                    if (isset($block['type']) && isset($block['data'])) {
                        if (in_array($block['type'], ['paragraph', 'header']) && isset($block['data']['text']) && trim($block['data']['text']) !== '') {
                            $hasContent = true;
                            break;
                        } elseif (!in_array($block['type'], ['paragraph', 'header'])) {
                            $hasContent = true;
                            break;
                        }
                    }
                }
                if (!$hasContent) {
                    $fail('The question text must contain at least one block with content.');
                }
            }],
            'difficulty' => 'required|in:Easy,Medium,Hard',
            'marks' => 'nullable|integer|min:1',
            'status' => 'required|in:Draft,Published',
            'options' => 'required|array|min:2',
            'options.*.option_text' => ['required', function ($attribute, $value, $fail) {
                // Accept JSON (Editor.js format) or non-empty string
                if (empty($value)) {
                    $fail('The option text is required.');
                    return;
                }
                
                // Try to parse as JSON
                $decoded = json_decode($value, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    // Valid JSON - check if it's Editor.js format
                    if (isset($decoded['blocks']) && is_array($decoded['blocks'])) {
                        // Check if at least one block has content
                        $hasContent = false;
                        foreach ($decoded['blocks'] as $block) {
                            if (isset($block['type']) && isset($block['data'])) {
                                if (in_array($block['type'], ['paragraph', 'header']) && isset($block['data']['text']) && trim($block['data']['text']) !== '') {
                                    $hasContent = true;
                                    break;
                                } elseif (!in_array($block['type'], ['paragraph', 'header'])) {
                                    $hasContent = true;
                                    break;
                                }
                            }
                        }
                        if (!$hasContent) {
                            $fail('The option text must contain at least one block with content.');
                        }
                        return; // Valid Editor.js JSON
                    }
                }
                
                // Not JSON or invalid JSON - treat as string (backward compatibility)
                if (!is_string($value) || trim($value) === '') {
                    $fail('The option text must be valid JSON or a non-empty string.');
                }
            }],
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
