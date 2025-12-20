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
     * Display a listing of questions.
     */
    public function index(Request $request)
    {
        $query = Question::with(['category', 'options']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('question_text', 'like', "%{$search}%");
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
