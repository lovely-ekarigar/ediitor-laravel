<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Question;
use App\Http\Requests\StoreQuestionRequest;
use App\Http\Requests\UpdateQuestionRequest;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    /**
     * Extract plain text from Editor.js JSON blocks for search.
     */
    private function extractTextFromEditorJs($content)
    {
        if (empty($content)) {
            return '';
        }

        // Try to parse as JSON (Editor.js format)
        $decoded = json_decode($content, true);
        if (json_last_error() === JSON_ERROR_NONE && isset($decoded['blocks']) && is_array($decoded['blocks'])) {
            $texts = [];
            foreach ($decoded['blocks'] as $block) {
                if (isset($block['type']) && isset($block['data'])) {
                    switch ($block['type']) {
                        case 'paragraph':
                        case 'header':
                            if (isset($block['data']['text'])) {
                                $texts[] = strip_tags($block['data']['text']);
                            }
                            break;
                        case 'list':
                            if (isset($block['data']['items']) && is_array($block['data']['items'])) {
                                foreach ($block['data']['items'] as $item) {
                                    $texts[] = strip_tags($item);
                                }
                            }
                            break;
                        case 'checklist':
                            if (isset($block['data']['items']) && is_array($block['data']['items'])) {
                                foreach ($block['data']['items'] as $item) {
                                    if (isset($item['text'])) {
                                        $texts[] = strip_tags($item['text']);
                                    }
                                }
                            }
                            break;
                        case 'quote':
                            if (isset($block['data']['text'])) {
                                $texts[] = strip_tags($block['data']['text']);
                            }
                            if (isset($block['data']['caption'])) {
                                $texts[] = strip_tags($block['data']['caption']);
                            }
                            break;
                        case 'table':
                            if (isset($block['data']['content']) && is_array($block['data']['content'])) {
                                foreach ($block['data']['content'] as $row) {
                                    if (is_array($row)) {
                                        foreach ($row as $cell) {
                                            $texts[] = strip_tags($cell);
                                        }
                                    }
                                }
                            }
                            break;
                        case 'code':
                            if (isset($block['data']['code'])) {
                                $texts[] = $block['data']['code'];
                            }
                            break;
                    }
                }
            }
            return implode(' ', $texts);
        }

        // Fallback: treat as HTML and strip tags
        return strip_tags($content);
    }

    /**
     * Display a listing of questions.
     */
    public function index(Request $request)
    {
        $query = Question::with(['category', 'options']);

        // Search functionality - handle both JSON (Editor.js) and HTML formats
        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $query->where(function($q) use ($search) {
                // Search in Editor.js JSON format
                $q->whereRaw('LOWER(question_text) LIKE ?', ['%' . $search . '%'])
                  // Also search in extracted text from JSON blocks
                  ->orWhere(function($subQ) use ($search) {
                      // This will search in the JSON structure
                      $subQ->whereRaw('LOWER(question_text) LIKE ?', ['%"text":"%' . $search . '%'])
                           ->orWhereRaw('LOWER(question_text) LIKE ?', ['%"items":["%' . $search . '%']);
                  });
            });
        }

        // Filter by category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by difficulty
        if ($request->filled('difficulty')) {
            $query->where('difficulty', $request->difficulty);
        }

        $questions = $query->latest()->paginate(10);
        $categories = Category::all();

        return view('questions.index', compact('questions', 'categories'));
    }

    /**
     * Show the form for creating a new question.
     */
    public function create()
    {
        $categories = Category::all();
        return view('questions.create', compact('categories'));
    }

    /**
     * Store a newly created question in storage.
     */
    public function store(StoreQuestionRequest $request)
    {
        // Create the question
        $question = Question::create([
            'category_id' => $request->category_id,
            'question_text' => $request->question_text,
            'difficulty' => $request->difficulty,
            'marks' => $request->marks ?? 1,
            'status' => $request->status,
        ]);

        // Create options
        foreach ($request->options as $index => $optionData) {
            $question->options()->create([
                'option_text' => $optionData['option_text'],
                'is_correct' => $index == $request->correct_option,
            ]);
        }

        return redirect()
            ->route('questions.index')
            ->with('success', 'Question created successfully!');
    }

    /**
     * Display the specified question.
     */
    public function show(Question $question)
    {
        $question->load(['category', 'options']);
        return view('questions.show', compact('question'));
    }

    /**
     * Show the form for editing the specified question.
     */
    public function edit(Question $question)
    {
        $categories = Category::all();
        $question->load('options');
        return view('questions.edit', compact('question', 'categories'));
    }

    /**
     * Update the specified question in storage.
     */
    public function update(UpdateQuestionRequest $request, Question $question)
    {
        // Update the question
        $question->update([
            'category_id' => $request->category_id,
            'question_text' => $request->question_text,
            'difficulty' => $request->difficulty,
            'marks' => $request->marks ?? 1,
            'status' => $request->status,
        ]);

        // Delete old options
        $question->options()->delete();

        // Create new options
        foreach ($request->options as $index => $optionData) {
            $question->options()->create([
                'option_text' => $optionData['option_text'],
                'is_correct' => $index == $request->correct_option,
            ]);
        }

        return redirect()
            ->route('questions.index')
            ->with('success', 'Question updated successfully!');
    }

    /**
     * Remove the specified question from storage.
     */
    public function destroy(Question $question)
    {
        $question->delete();

        return redirect()
            ->route('questions.index')
            ->with('success', 'Question deleted successfully!');
    }
}
