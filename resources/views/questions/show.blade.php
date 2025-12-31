@extends('layouts.app')

@section('title', 'View Question')

@section('content')
<div class="mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Question Details</h1>
            <p class="text-gray-600">View full question information</p>
        </div>
        <a href="{{ route('questions.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg font-medium transition">
            <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to List
        </a>
    </div>
</div>

<div class="bg-white rounded-xl shadow-md">
    <!-- Question Header -->
    <div class="border-b border-gray-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center space-x-3">
                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                    {{ $question->category->name }}
                </span>
                @php
                    $difficultyColors = [
                        'Easy' => 'bg-green-100 text-green-800',
                        'Medium' => 'bg-yellow-100 text-yellow-800',
                        'Hard' => 'bg-red-100 text-red-800',
                    ];
                @endphp
                <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $difficultyColors[$question->difficulty] }}">
                    {{ $question->difficulty }}
                </span>
                @if($question->status === 'Published')
                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-800">
                        Published
                    </span>
                @else
                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                        Draft
                    </span>
                @endif
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-500">Marks</p>
                <p class="text-2xl font-bold text-indigo-600">{{ $question->marks }}</p>
            </div>
        </div>
    </div>

    <!-- Question Text -->
    <div class="p-6 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900 mb-3">Question:</h3>
        <div class="prose max-w-none bg-gray-50 p-4 rounded-lg" id="questionContent">
            <div id="editorjs-output"></div>
        </div>
    </div>

    <!-- Options -->
    <div class="p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Answer Options:</h3>
        <div class="space-y-3">
            @foreach($question->options as $index => $option)
                <div class="flex items-start p-4 rounded-lg border-2 {{ $option->is_correct ? 'border-green-500 bg-green-50' : 'border-gray-200 bg-gray-50' }}">
                    <div class="flex-shrink-0 mr-3">
                        @if($option->is_correct)
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        @else
                            <div class="w-6 h-6 rounded-full border-2 border-gray-300"></div>
                        @endif
                    </div>
                    <div class="flex-1">
                        <p class="text-gray-900 font-medium">Option {{ $index + 1 }}</p>
                        <p class="text-gray-700 mt-1">{{ $option->option_text }}</p>
                        @if($option->is_correct)
                            <span class="inline-block mt-2 px-2 py-1 text-xs font-semibold rounded bg-green-200 text-green-800">
                                Correct Answer
                            </span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="bg-gray-50 px-6 py-4 flex justify-end space-x-3">
        <a href="{{ route('questions.edit', $question) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg font-medium transition shadow-md hover:shadow-lg">
            <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            Edit Question
        </a>
        <form action="{{ route('questions.destroy', $question) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this question?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg font-medium transition shadow-md hover:shadow-lg">
                <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
                Delete Question
            </button>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Function to render Editor.js JSON to HTML
    function renderEditorJsToHtml(blocks) {
        if (!blocks || !Array.isArray(blocks)) {
            return '';
        }

        let html = '';
        
        blocks.forEach(block => {
            switch (block.type) {
                case 'header':
                    const level = block.data.level || 3;
                    const headerText = block.data.text || '';
                    html += `<h${level}>${escapeHtml(headerText)}</h${level}>`;
                    break;
                    
                case 'paragraph':
                    const paragraphText = block.data.text || '';
                    html += `<p>${escapeHtml(paragraphText)}</p>`;
                    break;
                    
                case 'list':
                    const listItems = block.data.items || [];
                    const listTag = block.data.style === 'ordered' ? 'ol' : 'ul';
                    html += `<${listTag}>`;
                    listItems.forEach(item => {
                        html += `<li>${escapeHtml(item)}</li>`;
                    });
                    html += `</${listTag}>`;
                    break;
                    
                case 'checklist':
                    const checklistItems = block.data.items || [];
                    html += '<ul class="checklist">';
                    checklistItems.forEach(item => {
                        const checked = item.checked ? 'checked' : '';
                        html += `<li><input type="checkbox" ${checked} disabled> ${escapeHtml(item.text || '')}</li>`;
                    });
                    html += '</ul>';
                    break;
                    
                case 'quote':
                    const quoteText = block.data.text || '';
                    const quoteCaption = block.data.caption || '';
                    html += '<blockquote>';
                    html += `<p>${escapeHtml(quoteText)}</p>`;
                    if (quoteCaption) {
                        html += `<cite>${escapeHtml(quoteCaption)}</cite>`;
                    }
                    html += '</blockquote>';
                    break;
                    
                case 'code':
                    const codeText = block.data.code || '';
                    html += `<pre><code>${escapeHtml(codeText)}</code></pre>`;
                    break;
                    
                case 'table':
                    const tableContent = block.data.content || [];
                    html += '<table class="table-auto border-collapse border border-gray-300 w-full">';
                    tableContent.forEach(row => {
                        html += '<tr>';
                        row.forEach(cell => {
                            html += `<td class="border border-gray-300 p-2">${escapeHtml(cell)}</td>`;
                        });
                        html += '</tr>';
                    });
                    html += '</table>';
                    break;
                    
                case 'image':
                    const imageUrl = block.data.file?.url || block.data.url || '';
                    const imageCaption = block.data.caption || '';
                    const withBorder = block.data.withBorder ? 'border-2 border-gray-400' : '';
                    const withBackground = block.data.withBackground ? 'bg-gray-100' : '';
                    const stretched = block.data.stretched ? 'w-full' : '';
                    
                    if (imageUrl) {
                        html += `<figure class="${withBorder} ${withBackground} ${stretched} inline-block">`;
                        html += `<img src="${escapeHtml(imageUrl)}" alt="${escapeHtml(imageCaption)}" class="max-w-full h-auto">`;
                        if (imageCaption) {
                            html += `<figcaption class="text-sm text-gray-600 mt-2">${escapeHtml(imageCaption)}</figcaption>`;
                        }
                        html += '</figure>';
                    }
                    break;
                    
                case 'marker':
                    const markerText = block.data.text || '';
                    html += `<mark>${escapeHtml(markerText)}</mark>`;
                    break;
            }
        });
        
        return html;
    }

    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Render question content
    document.addEventListener('DOMContentLoaded', function() {
        const questionText = @json($question->question_text);
        const outputDiv = document.getElementById('editorjs-output');
        
        if (!questionText) {
            outputDiv.innerHTML = '<p class="text-gray-500">No question text available.</p>';
            return;
        }

        // Try to parse as Editor.js JSON
        try {
            const parsed = JSON.parse(questionText);
            if (parsed && Array.isArray(parsed.blocks)) {
                // Editor.js format
                outputDiv.innerHTML = renderEditorJsToHtml(parsed.blocks);
            } else {
                // Invalid JSON, treat as HTML
                outputDiv.innerHTML = questionText;
            }
        } catch (e) {
            // Not JSON, treat as HTML (backward compatibility)
            outputDiv.innerHTML = questionText;
        }
    });
</script>
@endpush
@endsection
