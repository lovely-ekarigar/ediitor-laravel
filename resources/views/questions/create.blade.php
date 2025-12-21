@extends('layouts.app')

@section('title', 'Create Question')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-2">Create New Question</h1>
    <p class="text-gray-600">Add a new question to your question bank</p>
</div>

<div class="bg-white rounded-xl shadow-md p-8">
    <form action="{{ route('questions.store') }}" method="POST" id="questionForm">
        @csrf

        <!-- Category -->
        <div class="mb-6">
            <label for="category_id" class="block text-sm font-semibold text-gray-700 mb-2">
                Category <span class="text-red-500">*</span>
            </label>
            <select 
                name="category_id" 
                id="category_id" 
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('category_id') border-red-500 @enderror"
                required
            >
                <option value="">Select a category</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            @error('category_id')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Question Text -->
        <div class="mb-6">
            <label for="question_text" class="block text-sm font-semibold text-gray-700 mb-2">
                Question Text <span class="text-red-500">*</span>
            </label>
            <textarea 
                name="question_text" 
                id="question_text" 
                rows="6"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('question_text') border-red-500 @enderror"
                required
            >{{ old('question_text') }}</textarea>
            @error('question_text')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Difficulty and Marks Row -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Difficulty -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Difficulty Level <span class="text-red-500">*</span>
                </label>
                <div class="space-y-2">
                    <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition">
                        <input type="radio" name="difficulty" value="Easy" class="text-indigo-600 focus:ring-indigo-500" {{ old('difficulty') === 'Easy' ? 'checked' : '' }} required>
                        <span class="ml-3 text-sm font-medium text-gray-700">Easy</span>
                    </label>
                    <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition">
                        <input type="radio" name="difficulty" value="Medium" class="text-indigo-600 focus:ring-indigo-500" {{ old('difficulty') === 'Medium' ? 'checked' : '' }} required>
                        <span class="ml-3 text-sm font-medium text-gray-700">Medium</span>
                    </label>
                    <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition">
                        <input type="radio" name="difficulty" value="Hard" class="text-indigo-600 focus:ring-indigo-500" {{ old('difficulty') === 'Hard' ? 'checked' : '' }} required>
                        <span class="ml-3 text-sm font-medium text-gray-700">Hard</span>
                    </label>
                </div>
                @error('difficulty')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Marks and Status -->
            <div class="space-y-6">
                <!-- Marks -->
                <div>
                    <label for="marks" class="block text-sm font-semibold text-gray-700 mb-2">
                        Marks
                    </label>
                    <input 
                        type="number" 
                        name="marks" 
                        id="marks" 
                        value="{{ old('marks', 1) }}"
                        min="1"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('marks') border-red-500 @enderror"
                    >
                    @error('marks')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <div class="space-y-2">
                        <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition">
                            <input type="radio" name="status" value="Draft" class="text-indigo-600 focus:ring-indigo-500" {{ old('status', 'Draft') === 'Draft' ? 'checked' : '' }} required>
                            <span class="ml-3 text-sm font-medium text-gray-700">Draft</span>
                        </label>
                        <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition">
                            <input type="radio" name="status" value="Published" class="text-indigo-600 focus:ring-indigo-500" {{ old('status') === 'Published' ? 'checked' : '' }} required>
                            <span class="ml-3 text-sm font-medium text-gray-700">Published</span>
                        </label>
                    </div>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Options Section -->
        <div class="mb-6">
            <div class="flex justify-between items-center mb-4">
                <label class="block text-sm font-semibold text-gray-700">
                    Answer Options <span class="text-red-500">*</span>
                    <span class="text-xs text-gray-500 font-normal">(Minimum 2 required)</span>
                </label>
                <button 
                    type="button" 
                    onclick="addOption()" 
                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition shadow-md hover:shadow-lg"
                >
                    <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add Option
                </button>
            </div>

            <div id="optionsContainer" class="space-y-3">
                <!-- Default 4 options -->
                @for($i = 0; $i < 4; $i++)
                    <div class="option-row flex items-center gap-3 p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <div class="flex items-center">
                            <input 
                                type="radio" 
                                name="correct_option" 
                                value="{{ $i }}" 
                                class="w-5 h-5 text-indigo-600 focus:ring-indigo-500"
                                {{ $i === 0 ? 'required' : '' }}
                            >
                        </div>
                        <div class="flex-1">
                            <input 
                                type="text" 
                                name="options[{{ $i }}][option_text]" 
                                placeholder="Enter option {{ $i + 1 }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                required
                            >
                        </div>
                        <button 
                            type="button" 
                            onclick="removeOption(this)" 
                            class="text-red-600 hover:text-red-800 transition"
                        >
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </div>
                @endfor
            </div>
            @error('options')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
            @error('correct_option')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
            <p class="mt-2 text-xs text-gray-500">
                <svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Select the radio button to mark the correct answer
            </p>
        </div>

        <!-- Submit Buttons -->
        <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
            <a href="{{ route('questions.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition">
                Cancel
            </a>
            <button 
                type="submit" 
                class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-2 rounded-lg font-medium transition shadow-md hover:shadow-lg"
            >
                Create Question
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    // TinyMCE Initialization
    tinymce.init({
        selector: '#question_text',
        height: 400,
        menubar: true,
        plugins: [
            'advlist', 'autolink', 'lists', 'link', 'image', 'charmap',
            'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
            'insertdatetime', 'media', 'table', 'help', 'wordcount'
        ],
        toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter | image table link | numlist bullist',
        content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; font-size: 14px; }',
        
        // Image Upload Configuration
        images_upload_url: '{{ route("upload.image") }}',
        images_upload_handler: function (blobInfo, success, failure) {
            let xhr, formData;
            xhr = new XMLHttpRequest();
            xhr.withCredentials = false;
            xhr.open('POST', '{{ route("upload.image") }}');
            
            // Add CSRF token
            xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');
            
            xhr.onload = function() {
                if (xhr.status != 200) {
                    failure('HTTP Error: ' + xhr.status);
                    return;
                }
                let json = JSON.parse(xhr.responseText);
                if (!json || typeof json.location != 'string') {
                    failure('Invalid JSON: ' + xhr.responseText);
                    return;
                }
                success(json.location);
            };
            
            formData = new FormData();
            formData.append('file', blobInfo.blob(), blobInfo.filename());
            
            xhr.send(formData);
        },
        
        // Table Configuration
        table_toolbar: 'tableprops tabledelete | tableinsertrowbefore tableinsertrowafter tabledeleterow | tableinsertcolbefore tableinsertcolafter tabledeletecol',
        table_appearance_options: true,
        table_grid: true,
        table_style_by_css: true,
    });

    // Dynamic Options Management
    let optionIndex = 4;

    function addOption() {
        const container = document.getElementById('optionsContainer');
        const optionRow = document.createElement('div');
        optionRow.className = 'option-row flex items-center gap-3 p-4 bg-gray-50 rounded-lg border border-gray-200';
        optionRow.innerHTML = `
            <div class="flex items-center">
                <input 
                    type="radio" 
                    name="correct_option" 
                    value="${optionIndex}" 
                    class="w-5 h-5 text-indigo-600 focus:ring-indigo-500"
                >
            </div>
            <div class="flex-1">
                <input 
                    type="text" 
                    name="options[${optionIndex}][option_text]" 
                    placeholder="Enter option ${optionIndex + 1}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                    required
                >
            </div>
            <button 
                type="button" 
                onclick="removeOption(this)" 
                class="text-red-600 hover:text-red-800 transition"
            >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </button>
        `;
        container.appendChild(optionRow);
        optionIndex++;
        updateOptionIndices();
    }

    function removeOption(button) {
        const container = document.getElementById('optionsContainer');
        const options = container.querySelectorAll('.option-row');
        
        if (options.length > 2) {
            button.closest('.option-row').remove();
            updateOptionIndices();
        } else {
            alert('You must have at least 2 options!');
        }
    }

    function updateOptionIndices() {
        const container = document.getElementById('optionsContainer');
        const options = container.querySelectorAll('.option-row');
        
        options.forEach((option, index) => {
            const radio = option.querySelector('input[type="radio"]');
            const textInput = option.querySelector('input[type="text"]');
            
            radio.value = index;
            textInput.name = `options[${index}][option_text]`;
            textInput.placeholder = `Enter option ${index + 1}`;
        });
    }

    // Form submission validation and TinyMCE content sync
    document.getElementById('questionForm').addEventListener('submit', function(e) {
        // Sync TinyMCE content to textarea before submission
        const editor = tinymce.get('question_text');
        if (editor) {
            const content = editor.getContent();
            // Set the textarea value with TinyMCE content
            document.getElementById('question_text').value = content;
            
            // Validate that content is not empty (strip HTML tags and whitespace)
            const textContent = editor.getContent({ format: 'text' }).trim();
            if (!textContent) {
                e.preventDefault();
                alert('Please enter question text!');
                editor.focus();
                return false;
            }
        }
        
        // Validate correct option selection
        const correctOption = document.querySelector('input[name="correct_option"]:checked');
        if (!correctOption) {
            e.preventDefault();
            alert('Please select the correct answer!');
            return false;
        }
    });
</script>
@endpush
