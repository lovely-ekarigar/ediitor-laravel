@extends('layouts.app')

@section('title', 'Create Question')

@section('content')
<div class="mb-6 sm:mb-8">
    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">Create New Question</h1>
    <p class="text-sm sm:text-base text-gray-600">Add a new question to your question bank</p>
</div>

<div class="bg-white rounded-xl shadow-md p-4 sm:p-6 md:p-8">
    <form action="{{ route('questions.store') }}" method="POST" id="questionForm" novalidate>
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
            <div id="editorjs" class="border border-gray-300 rounded-lg bg-white min-h-[400px] p-4 @error('question_text') border-red-500 @enderror" style="cursor: text;"></div>
            <input type="hidden" name="question_text" id="question_text" value="{{ old('question_text') }}">
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
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-4">
                <label class="block text-sm font-semibold text-gray-700">
                    Answer Options <span class="text-red-500">*</span>
                    <span class="text-xs text-gray-500 font-normal">(Minimum 2 required)</span>
                </label>
                <button 
                    type="button" 
                    onclick="addOption()" 
                    class="w-full sm:w-auto bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition shadow-md hover:shadow-lg"
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
                    <div class="option-row flex flex-col sm:flex-row items-start gap-3 p-3 sm:p-4 bg-gray-50 rounded-lg border border-gray-200" data-option-index="{{ $i }}">
                        <div class="flex items-center pt-2 sm:pt-0">
                            <input 
                                type="radio" 
                                name="correct_option" 
                                value="{{ $i }}" 
                                class="w-5 h-5 text-indigo-600 focus:ring-indigo-500"
                                {{ $i === 0 ? 'required' : '' }}
                            >
                        </div>
                        <div class="flex-1 w-full">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Option {{ $i + 1 }}</label>
                            <div id="editorjs-option-{{ $i }}" class="border border-gray-300 rounded-lg bg-white min-h-[200px] p-2 sm:p-3 @error('options.'.$i.'.option_text') border-red-500 @enderror" style="cursor: text;"></div>
                            <input type="hidden" name="options[{{ $i }}][option_text]" id="option_text_{{ $i }}" value="">
                        </div>
                        <button 
                            type="button" 
                            onclick="removeOption(this)" 
                            class="text-red-600 hover:text-red-800 transition mt-2 sm:mt-0 p-2"
                        >
                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
        <div class="flex flex-col sm:flex-row justify-end gap-3 pt-6 border-t border-gray-200">
            <a href="{{ route('questions.index') }}" class="w-full sm:w-auto text-center px-6 py-2 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition">
                Cancel
            </a>
            <button 
                type="submit" 
                class="w-full sm:w-auto bg-indigo-600 hover:bg-indigo-700 text-white px-6 sm:px-8 py-2 rounded-lg font-medium transition shadow-md hover:shadow-lg"
            >
                Create Question
            </button>
        </div>
    </form>
</div>
@endsection

@push('styles')
<style>
    #editorjs {
        min-height: 400px;
        padding: 20px;
        background: white;
        border: 1px solid #d1d5db;
        border-radius: 0.5rem;
    }
    
    #editorjs .ce-block__content {
        max-width: 100%;
    }
    
    #editorjs .ce-toolbar__content {
        max-width: 100%;
    }
    
    #editorjs .codex-editor__redactor {
        padding-bottom: 50px;
    }
    
    /* Make editor clickable and interactive */
    #editorjs .ce-block {
        cursor: text;
    }
    
    #editorjs .ce-paragraph {
        cursor: text;
        min-height: 1.5em;
    }
    
    /* Style for placeholder */
    #editorjs .ce-paragraph[data-placeholder]:empty::before {
        content: attr(data-placeholder);
        color: #999;
        font-style: italic;
    }
    
    /* Plus icon visibility and styling */
    #editorjs .ce-toolbar__plus {
        color: #6b7280 !important;
        background: transparent !important;
        border: 1px solid #d1d5db !important;
        border-radius: 4px !important;
        width: 24px !important;
        height: 24px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        cursor: pointer !important;
        transition: all 0.2s !important;
    }
    
    #editorjs .ce-toolbar__plus:hover {
        background: #f3f4f6 !important;
        border-color: #9ca3af !important;
        color: #374151 !important;
    }
    
    #editorjs .ce-toolbar__plus svg {
        width: 16px !important;
        height: 16px !important;
        stroke: currentColor !important;
    }
    
    /* Ensure toolbar is visible */
    #editorjs .ce-toolbar {
        opacity: 1 !important;
        visibility: visible !important;
    }
    
    /* Image tool styling */
    #editorjs .image-tool {
        margin: 20px 0;
    }
    
    #editorjs .image-tool__image {
        max-width: 100%;
        height: auto;
    }
    
    /* Make sure editor is visible */
    #editorjs .codex-editor {
        min-height: 400px;
    }
    
    /* Option editor styling */
    [id^="editorjs-option-"] {
        min-height: 200px;
    }
    
    [id^="editorjs-option-"] .ce-block__content {
        max-width: 100%;
    }
    
    [id^="editorjs-option-"] .ce-toolbar__content {
        max-width: 100%;
    }
    
    [id^="editorjs-option-"] .ce-paragraph {
        cursor: text;
        min-height: 1.5em;
    }
</style>
@endpush

@push('scripts')
<!-- Load Editor.js Core FIRST - Using unpkg CDN -->
<script src="https://unpkg.com/@editorjs/editorjs@latest" onload="console.log('Editor.js core script loaded'); window.editorJSCoreLoaded = true; console.log('EditorJS available:', typeof EditorJS, typeof window.EditorJS);" onerror="console.error('Failed to load Editor.js core script');"></script>

<!-- Load Editor.js Tools - Using unpkg CDN -->
<script src="https://unpkg.com/@editorjs/header@latest"></script>
<script src="https://unpkg.com/@editorjs/list@latest"></script>
<script src="https://unpkg.com/@editorjs/checklist@latest"></script>
<script src="https://unpkg.com/@editorjs/table@latest"></script>
<script src="https://unpkg.com/@editorjs/quote@latest"></script>
<script src="https://unpkg.com/@editorjs/marker@latest"></script>
<script src="https://unpkg.com/@editorjs/code@latest"></script>
<script src="https://unpkg.com/@editorjs/image@latest"></script>
<script src="https://unpkg.com/@editorjs/link@latest"></script>
<script src="https://unpkg.com/@editorjs/inline-code@latest"></script>
<script src="https://unpkg.com/@editorjs/delimiter@latest"></script>
<script src="https://unpkg.com/@editorjs/warning@latest"></script>
<script src="https://unpkg.com/@editorjs/paragraph@latest"></script>

<script>
    // Editor.js Initialization
    let editor;
    let toolsLoaded = 0;
    const totalTools = 13;
    
    // Function to check if all required tools are loaded
    function checkToolsLoaded() {
        // Check for EditorJS - try multiple ways it might be exposed
        let EditorJSClass = undefined;
        if (typeof EditorJS !== 'undefined') {
            EditorJSClass = EditorJS;
        } else if (typeof window.EditorJS !== 'undefined') {
            EditorJSClass = window.EditorJS;
        } else if (typeof window.Editorjs !== 'undefined') {
            EditorJSClass = window.Editorjs;
        } else if (window.EditorJS && typeof window.EditorJS === 'function') {
            EditorJSClass = window.EditorJS;
        }
        
        const requiredTools = {
            'EditorJS': EditorJSClass !== undefined,
            'Header': typeof Header !== 'undefined' || typeof window.Header !== 'undefined',
            'List': typeof List !== 'undefined' || typeof window.List !== 'undefined',
            'Paragraph': typeof Paragraph !== 'undefined' || typeof window.Paragraph !== 'undefined',
            'Checklist': typeof Checklist !== 'undefined' || typeof window.Checklist !== 'undefined',
            'Table': typeof Table !== 'undefined' || typeof window.Table !== 'undefined',
            'Quote': typeof Quote !== 'undefined' || typeof window.Quote !== 'undefined',
            'Marker': typeof Marker !== 'undefined' || typeof window.Marker !== 'undefined',
            'Code': typeof Code !== 'undefined' || typeof window.Code !== 'undefined',
            'ImageTool': typeof ImageTool !== 'undefined' || typeof window.ImageTool !== 'undefined',
            'LinkTool': typeof LinkTool !== 'undefined' || typeof window.LinkTool !== 'undefined',
            'InlineCode': typeof InlineCode !== 'undefined' || typeof window.InlineCode !== 'undefined',
            'Delimiter': typeof Delimiter !== 'undefined' || typeof window.Delimiter !== 'undefined',
            'Warning': typeof Warning !== 'undefined' || typeof window.Warning !== 'undefined'
        };
        
        // Debug: Log all possible EditorJS locations
        console.log('Tool availability check:', requiredTools);
        console.log('EditorJS checks:', {
            'EditorJS': typeof EditorJS,
            'window.EditorJS': typeof window.EditorJS,
            'window.Editorjs': typeof window.Editorjs,
            'window.EditorJS (value)': window.EditorJS,
            'Found class': EditorJSClass !== undefined
        });
        
        // Check if core script loaded flag is set
        if (!window.editorJSCoreLoaded) {
            return false;
        }
        
        // Check if core tools are available (EditorJS is critical)
        if (EditorJSClass && requiredTools.Header && requiredTools.Paragraph) {
            // List is optional but preferred
            return true;
        }
        return false;
    }
    
    // Function to wait for scripts to load
    function waitForEditorJS(callback, maxAttempts = 100) {
        let attempts = 0;
        
        function check() {
            attempts++;
            
            if (checkToolsLoaded()) {
                console.log('All required Editor.js tools loaded successfully!');
                callback();
            } else if (attempts < maxAttempts) {
                if (attempts % 10 === 0) {
                    console.log('Waiting for Editor.js tools... Attempt ' + attempts);
                }
                setTimeout(check, 100);
            } else {
                console.error('Editor.js failed to load after ' + maxAttempts + ' attempts');
                const editorElement = document.getElementById('editorjs');
                if (editorElement) {
                    editorElement.innerHTML = '<div class="p-4 text-red-500 border border-red-300 rounded"><p class="font-semibold">Editor.js failed to load</p><p class="text-sm mt-2">Please check browser console (F12) for details and refresh the page.</p></div>';
                }
            }
        }
        
        // Start checking after a short delay to allow scripts to load
        setTimeout(check, 200);
    }
    
    // Initialize Editor.js after all scripts are loaded
    waitForEditorJS(function() {
        try {
            console.log('Initializing Editor.js...');
            
            // Get EditorJS class - try multiple ways it might be exposed
            let EditorJSClass = undefined;
            if (typeof EditorJS !== 'undefined') {
                EditorJSClass = EditorJS;
            } else if (typeof window.EditorJS !== 'undefined') {
                EditorJSClass = window.EditorJS;
            } else if (typeof window.Editorjs !== 'undefined') {
                EditorJSClass = window.Editorjs;
            } else if (window.EditorJS && typeof window.EditorJS === 'function') {
                EditorJSClass = window.EditorJS;
            }
            
            if (!EditorJSClass) {
                console.error('EditorJS not found. Available globals:', Object.keys(window).filter(k => k.toLowerCase().includes('editor')));
                throw new Error('EditorJS class not found. Please check if Editor.js core script loaded correctly.');
            }
            
            console.log('Using EditorJS class:', EditorJSClass);
            
            // Build tools object, filtering out undefined tools
            const toolsConfig = {
                    // Paragraph tool (default, supports inline formatting)
                    paragraph: (typeof Paragraph !== 'undefined' || typeof window.Paragraph !== 'undefined') ? {
                        class: typeof Paragraph !== 'undefined' ? Paragraph : window.Paragraph,
                        inlineToolbar: true,
                        config: {
                            placeholder: 'Type your text here...'
                        }
                    } : undefined,
                    // Header tool
                    header: (typeof Header !== 'undefined' || typeof window.Header !== 'undefined') ? {
                        class: typeof Header !== 'undefined' ? Header : window.Header,
                        config: {
                            levels: [1, 2, 3, 4, 5, 6],
                            defaultLevel: 3,
                            placeholder: 'Enter a header'
                        },
                        inlineToolbar: true,
                        shortcut: 'CMD+SHIFT+H'
                    } : undefined,
                    // List tool
                    list: (typeof List !== 'undefined' || typeof window.List !== 'undefined') ? {
                        class: typeof List !== 'undefined' ? List : window.List,
                        inlineToolbar: true,
                        config: {
                            defaultStyle: 'unordered'
                        },
                        shortcut: 'CMD+SHIFT+L'
                    } : undefined,
                    // Checklist tool
                    checklist: (typeof Checklist !== 'undefined' || typeof window.Checklist !== 'undefined') ? {
                        class: typeof Checklist !== 'undefined' ? Checklist : window.Checklist,
                        inlineToolbar: true,
                        shortcut: 'CMD+SHIFT+C'
                    } : undefined,
                    // Table tool
                    table: (typeof Table !== 'undefined' || typeof window.Table !== 'undefined') ? {
                        class: typeof Table !== 'undefined' ? Table : window.Table,
                        inlineToolbar: true,
                        config: {
                            rows: 2,
                            cols: 2,
                            withHeadings: true
                        },
                        shortcut: 'CMD+ALT+T'
                    } : undefined,
                    // Quote tool
                    quote: (typeof Quote !== 'undefined' || typeof window.Quote !== 'undefined') ? {
                        class: typeof Quote !== 'undefined' ? Quote : window.Quote,
                        inlineToolbar: true,
                        shortcut: 'CMD+SHIFT+O',
                        config: {
                            quotePlaceholder: 'Enter a quote',
                            captionPlaceholder: 'Quote\'s author',
                        }
                    } : undefined,
                    // Marker tool (highlighting)
                    marker: (typeof Marker !== 'undefined' || typeof window.Marker !== 'undefined') ? {
                        class: typeof Marker !== 'undefined' ? Marker : window.Marker,
                        shortcut: 'CMD+SHIFT+M',
                    } : undefined,
                    // Code tool
                    code: (typeof Code !== 'undefined' || typeof window.Code !== 'undefined') ? {
                        class: typeof Code !== 'undefined' ? Code : window.Code,
                        config: {
                            placeholder: 'Enter code',
                        },
                        shortcut: 'CMD+SHIFT+C'
                    } : undefined,
                    // Inline Code tool
                    inlineCode: (typeof InlineCode !== 'undefined' || typeof window.InlineCode !== 'undefined') ? {
                        class: typeof InlineCode !== 'undefined' ? InlineCode : window.InlineCode,
                        shortcut: 'CMD+SHIFT+M',
                    } : undefined,
                    // Link tool
                    linkTool: (typeof LinkTool !== 'undefined' || typeof window.LinkTool !== 'undefined') ? {
                        class: typeof LinkTool !== 'undefined' ? LinkTool : window.LinkTool,
                        config: {
                            endpoint: '{{ route("upload.image") }}',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        }
                    } : undefined,
                    // Delimiter tool
                    delimiter: (typeof Delimiter !== 'undefined' || typeof window.Delimiter !== 'undefined') ? 
                        (typeof Delimiter !== 'undefined' ? Delimiter : window.Delimiter) : undefined,
                    // Warning tool
                    warning: (typeof Warning !== 'undefined' || typeof window.Warning !== 'undefined') ? {
                        class: typeof Warning !== 'undefined' ? Warning : window.Warning,
                        inlineToolbar: true,
                        shortcut: 'CMD+SHIFT+W',
                        config: {
                            titlePlaceholder: 'Title',
                            messagePlaceholder: 'Message',
                        }
                    } : undefined,
                    // Image tool
                    image: (typeof ImageTool !== 'undefined' || typeof window.ImageTool !== 'undefined') ? {
                        class: typeof ImageTool !== 'undefined' ? ImageTool : window.ImageTool,
                        config: {
                            uploader: {
                                uploadByFile(file) {
                                    return new Promise((resolve, reject) => {
                                        console.log('Starting image upload...', file.name, file.size);
                                        
                                        const formData = new FormData();
                                        formData.append('file', file);
                                        
                                        const xhr = new XMLHttpRequest();
                                        xhr.open('POST', '{{ route("upload.image") }}');
                                        xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');
                                        
                                        xhr.onload = function() {
                                            console.log('Upload response status:', xhr.status);
                                            console.log('Upload response:', xhr.responseText);
                                            
                                            if (xhr.status === 200 || xhr.status === 201) {
                                                try {
                                                    const response = JSON.parse(xhr.responseText);
                                                    if (response.success === 1 && response.file && response.file.url) {
                                                        console.log('Upload successful:', response.file.url);
                                                        resolve({
                                                            success: 1,
                                                            file: {
                                                                url: response.file.url,
                                                                caption: response.file.caption || '',
                                                                withBorder: response.file.withBorder || false,
                                                                withBackground: response.file.withBackground || false,
                                                                stretched: response.file.stretched || false,
                                                            }
                                                        });
                                                    } else {
                                                        const errorMsg = response.error || 'Invalid response format';
                                                        console.error('Upload failed:', errorMsg);
                                                        reject(errorMsg);
                                                    }
                                                } catch (e) {
                                                    console.error('Failed to parse response:', e);
                                                    reject('Failed to parse response: ' + e.message);
                                                }
                                            } else {
                                                // Try to parse error response
                                                let errorMsg = 'Upload failed with status: ' + xhr.status;
                                                try {
                                                    const errorResponse = JSON.parse(xhr.responseText);
                                                    if (errorResponse.error) {
                                                        errorMsg = errorResponse.error;
                                                    }
                                                } catch (e) {
                                                    // Use default error message
                                                }
                                                console.error('Upload failed:', errorMsg);
                                                reject(errorMsg);
                                            }
                                        };
                                        
                                        xhr.onerror = function() {
                                            console.error('Network error during upload');
                                            reject('Network error during upload. Please check your connection.');
                                        };
                                        
                                        xhr.ontimeout = function() {
                                            console.error('Upload timeout');
                                            reject('Upload timeout. Please try again.');
                                        };
                                        
                                        // Set timeout to 30 seconds
                                        xhr.timeout = 30000;
                                        
                                        xhr.send(formData);
                                    });
                                },
                                uploadByUrl(url) {
                                    // For existing images, just return the URL
                                    return Promise.resolve({
                                        success: 1,
                                        file: {
                                            url: url,
                                            caption: '',
                                            withBorder: false,
                                            withBackground: false,
                                            stretched: false,
                                        }
                                    });
                                }
                            },
                            captionPlaceholder: 'Enter a caption',
                            buttonContent: 'Select an Image',
                            field: 'file',
                            types: 'image/*'
                        }
                    } : undefined
                };
            
            // Remove undefined tools
            Object.keys(toolsConfig).forEach(key => {
                if (toolsConfig[key] === undefined) {
                    delete toolsConfig[key];
                }
            });
            
            console.log('Available tools:', Object.keys(toolsConfig));
            
            editor = new EditorJSClass({
                holder: 'editorjs',
                placeholder: 'Start typing your question... Press Tab to select a Block',
                autofocus: true,
                tools: toolsConfig,
                data: @json(old('question_text') ? json_decode(old('question_text'), true) : null),
                onReady: function() {
                    console.log('Editor.js is ready and clickable');
                    // Focus the editor
                    const editorElement = document.getElementById('editorjs');
                    if (editorElement) {
                        editorElement.style.cursor = 'text';
                    }
                },
                onChange: function() {
                    console.log('Editor.js content changed');
                }
            });
            
            console.log('Editor.js initialized successfully');
        } catch (error) {
            console.error('Error initializing Editor.js:', error);
            console.error('Error details:', error.message, error.stack);
            const editorElement = document.getElementById('editorjs');
            if (editorElement) {
                editorElement.innerHTML = '<div class="p-4 text-red-500"><p>Error loading editor: ' + error.message + '</p><p class="text-sm mt-2">Please check browser console for details.</p></div>';
            }
        }
    });

    // Store all option editors
    const optionEditors = {};

    // Function to initialize Editor.js for an option
    async function initOptionEditor(index, existingData = null) {
        const editorId = `editorjs-option-${index}`;
        const editorElement = document.getElementById(editorId);
        
        if (!editorElement) {
            console.warn(`Editor element not found: ${editorId}`);
            return;
        }

        // Get EditorJS class
        let EditorJSClass = undefined;
        if (typeof EditorJS !== 'undefined') {
            EditorJSClass = EditorJS;
        } else if (typeof window.EditorJS !== 'undefined') {
            EditorJSClass = window.EditorJS;
        }

        if (!EditorJSClass) {
            console.error('EditorJS class not found for option editor');
            return;
        }

        // Build tools config (full feature set - same as question editor)
        const toolsConfig = {
            // Paragraph tool (default, supports inline formatting)
            paragraph: (typeof Paragraph !== 'undefined' || typeof window.Paragraph !== 'undefined') ? {
                class: typeof Paragraph !== 'undefined' ? Paragraph : window.Paragraph,
                inlineToolbar: true,
                config: {
                    placeholder: 'Enter option text...'
                }
            } : undefined,
            // Header tool
            header: (typeof Header !== 'undefined' || typeof window.Header !== 'undefined') ? {
                class: typeof Header !== 'undefined' ? Header : window.Header,
                config: {
                    levels: [1, 2, 3, 4, 5, 6],
                    defaultLevel: 3,
                    placeholder: 'Enter a header'
                },
                inlineToolbar: true,
                shortcut: 'CMD+SHIFT+H'
            } : undefined,
            // List tool
            list: (typeof List !== 'undefined' || typeof window.List !== 'undefined') ? {
                class: typeof List !== 'undefined' ? List : window.List,
                inlineToolbar: true,
                config: {
                    defaultStyle: 'unordered'
                },
                shortcut: 'CMD+SHIFT+L'
            } : undefined,
            // Checklist tool
            checklist: (typeof Checklist !== 'undefined' || typeof window.Checklist !== 'undefined') ? {
                class: typeof Checklist !== 'undefined' ? Checklist : window.Checklist,
                inlineToolbar: true,
                shortcut: 'CMD+SHIFT+C'
            } : undefined,
            // Table tool
            table: (typeof Table !== 'undefined' || typeof window.Table !== 'undefined') ? {
                class: typeof Table !== 'undefined' ? Table : window.Table,
                inlineToolbar: true,
                config: {
                    rows: 2,
                    cols: 2,
                    withHeadings: true
                },
                shortcut: 'CMD+ALT+T'
            } : undefined,
            // Quote tool
            quote: (typeof Quote !== 'undefined' || typeof window.Quote !== 'undefined') ? {
                class: typeof Quote !== 'undefined' ? Quote : window.Quote,
                inlineToolbar: true,
                shortcut: 'CMD+SHIFT+O',
                config: {
                    quotePlaceholder: 'Enter a quote',
                    captionPlaceholder: 'Quote\'s author',
                }
            } : undefined,
            // Marker tool (highlighting)
            marker: (typeof Marker !== 'undefined' || typeof window.Marker !== 'undefined') ? {
                class: typeof Marker !== 'undefined' ? Marker : window.Marker,
                shortcut: 'CMD+SHIFT+M',
            } : undefined,
            // Code tool
            code: (typeof Code !== 'undefined' || typeof window.Code !== 'undefined') ? {
                class: typeof Code !== 'undefined' ? Code : window.Code,
                config: {
                    placeholder: 'Enter code',
                },
                shortcut: 'CMD+SHIFT+C'
            } : undefined,
            // Inline Code tool
            inlineCode: (typeof InlineCode !== 'undefined' || typeof window.InlineCode !== 'undefined') ? {
                class: typeof InlineCode !== 'undefined' ? InlineCode : window.InlineCode,
                shortcut: 'CMD+SHIFT+M',
            } : undefined,
            // Link tool
            linkTool: (typeof LinkTool !== 'undefined' || typeof window.LinkTool !== 'undefined') ? {
                class: typeof LinkTool !== 'undefined' ? LinkTool : window.LinkTool,
                config: {
                    endpoint: '{{ route("upload.image") }}',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                }
            } : undefined,
            // Delimiter tool
            delimiter: (typeof Delimiter !== 'undefined' || typeof window.Delimiter !== 'undefined') ? 
                (typeof Delimiter !== 'undefined' ? Delimiter : window.Delimiter) : undefined,
            // Warning tool
            warning: (typeof Warning !== 'undefined' || typeof window.Warning !== 'undefined') ? {
                class: typeof Warning !== 'undefined' ? Warning : window.Warning,
                inlineToolbar: true,
                shortcut: 'CMD+SHIFT+W',
                config: {
                    titlePlaceholder: 'Title',
                    messagePlaceholder: 'Message',
                }
            } : undefined,
            // Image tool
            image: (typeof ImageTool !== 'undefined' || typeof window.ImageTool !== 'undefined') ? {
                class: typeof ImageTool !== 'undefined' ? ImageTool : window.ImageTool,
                config: {
                    uploader: {
                        uploadByFile(file) {
                            return new Promise((resolve, reject) => {
                                console.log('Starting image upload...', file.name, file.size);
                                
                                const formData = new FormData();
                                formData.append('file', file);
                                
                                const xhr = new XMLHttpRequest();
                                xhr.open('POST', '{{ route("upload.image") }}');
                                xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');
                                
                                xhr.onload = function() {
                                    console.log('Upload response status:', xhr.status);
                                    console.log('Upload response:', xhr.responseText);
                                    
                                    if (xhr.status === 200 || xhr.status === 201) {
                                        try {
                                            const response = JSON.parse(xhr.responseText);
                                            if (response.success === 1 && response.file && response.file.url) {
                                                console.log('Upload successful:', response.file.url);
                                                resolve({
                                                    success: 1,
                                                    file: {
                                                        url: response.file.url,
                                                        caption: response.file.caption || '',
                                                        withBorder: response.file.withBorder || false,
                                                        withBackground: response.file.withBackground || false,
                                                        stretched: response.file.stretched || false,
                                                    }
                                                });
                                            } else {
                                                const errorMsg = response.error || 'Invalid response format';
                                                console.error('Upload failed:', errorMsg);
                                                reject(errorMsg);
                                            }
                                        } catch (e) {
                                            console.error('Failed to parse response:', e);
                                            reject('Failed to parse response: ' + e.message);
                                        }
                                    } else {
                                        // Try to parse error response
                                        let errorMsg = 'Upload failed with status: ' + xhr.status;
                                        try {
                                            const errorResponse = JSON.parse(xhr.responseText);
                                            if (errorResponse.error) {
                                                errorMsg = errorResponse.error;
                                            }
                                        } catch (e) {
                                            // Use default error message
                                        }
                                        console.error('Upload failed:', errorMsg);
                                        reject(errorMsg);
                                    }
                                };
                                
                                xhr.onerror = function() {
                                    console.error('Network error during upload');
                                    reject('Network error during upload. Please check your connection.');
                                };
                                
                                xhr.ontimeout = function() {
                                    console.error('Upload timeout');
                                    reject('Upload timeout. Please try again.');
                                };
                                
                                // Set timeout to 30 seconds
                                xhr.timeout = 30000;
                                
                                xhr.send(formData);
                            });
                        },
                        uploadByUrl(url) {
                            // For existing images, just return the URL
                            return Promise.resolve({
                                success: 1,
                                file: {
                                    url: url,
                                    caption: '',
                                    withBorder: false,
                                    withBackground: false,
                                    stretched: false,
                                }
                            });
                        }
                    },
                    captionPlaceholder: 'Enter a caption',
                    buttonContent: 'Select an Image',
                    field: 'file',
                    types: 'image/*'
                }
            } : undefined
        };

        // Remove undefined tools
        Object.keys(toolsConfig).forEach(key => {
            if (toolsConfig[key] === undefined) {
                delete toolsConfig[key];
            }
        });

        try {
            optionEditors[index] = new EditorJSClass({
                holder: editorId,
                placeholder: 'Enter option text...',
                tools: toolsConfig,
                data: existingData,
                minHeight: 200,
            });
        } catch (error) {
            console.error(`Error initializing option editor ${index}:`, error);
        }
    }

    // Initialize all option editors after main editor is ready
    waitForEditorJS(function() {
        // Initialize editors for default 4 options
        for (let i = 0; i < 4; i++) {
            setTimeout(() => initOptionEditor(i), i * 100); // Stagger initialization
        }
    });

    // Dynamic Options Management
    let optionIndex = 4;

    function addOption() {
        const container = document.getElementById('optionsContainer');
        const optionRow = document.createElement('div');
        optionRow.className = 'option-row flex items-start gap-3 p-4 bg-gray-50 rounded-lg border border-gray-200';
        optionRow.setAttribute('data-option-index', optionIndex);
        optionRow.innerHTML = `
            <div class="flex items-center pt-2">
                <input 
                    type="radio" 
                    name="correct_option" 
                    value="${optionIndex}" 
                    class="w-5 h-5 text-indigo-600 focus:ring-indigo-500"
                >
            </div>
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-2">Option ${optionIndex + 1}</label>
                <div id="editorjs-option-${optionIndex}" class="border border-gray-300 rounded-lg bg-white min-h-[200px] p-3" style="cursor: text;"></div>
                <input type="hidden" name="options[${optionIndex}][option_text]" id="option_text_${optionIndex}" value="">
            </div>
            <button 
                type="button" 
                onclick="removeOption(this)" 
                class="text-red-600 hover:text-red-800 transition mt-2"
            >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </button>
        `;
        container.appendChild(optionRow);
        
        // Initialize Editor.js for the new option
        setTimeout(() => {
            initOptionEditor(optionIndex);
            updateOptionIndices();
        }, 100);
        
        optionIndex++;
    }

    function removeOption(button) {
        const container = document.getElementById('optionsContainer');
        const options = container.querySelectorAll('.option-row');
        
        if (options.length > 2) {
            const optionRow = button.closest('.option-row');
            const index = parseInt(optionRow.getAttribute('data-option-index'));
            
            // Destroy editor instance if exists
            if (optionEditors[index]) {
                try {
                    optionEditors[index].destroy();
                } catch (e) {
                    console.warn('Error destroying editor:', e);
                }
                delete optionEditors[index];
            }
            
            optionRow.remove();
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
            const label = option.querySelector('label');
            const editorId = option.querySelector('[id^="editorjs-option-"]');
            const hiddenInput = option.querySelector('[id^="option_text_"]');
            
            if (radio) radio.value = index;
            if (label) label.textContent = `Option ${index + 1}`;
            if (editorId) {
                const oldIndex = editorId.id.match(/\d+/)?.[0];
                if (oldIndex !== index.toString()) {
                    editorId.id = `editorjs-option-${index}`;
                    // Update editor reference
                    if (optionEditors[oldIndex]) {
                        optionEditors[index] = optionEditors[oldIndex];
                        delete optionEditors[oldIndex];
                    }
                }
            }
            if (hiddenInput) {
                hiddenInput.id = `option_text_${index}`;
                hiddenInput.name = `options[${index}][option_text]`;
            }
            option.setAttribute('data-option-index', index);
        });
    }

    // Form submission validation and Editor.js content sync
    const form = document.getElementById('questionForm');
    if (form) {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            try {
                // Save main question Editor.js content
                const outputData = await editor.save();
                
                // Check if question content is not empty
                const hasContent = outputData.blocks && outputData.blocks.length > 0 && 
                    outputData.blocks.some(block => {
                        if (block.type === 'paragraph' || block.type === 'header') {
                            return block.data && block.data.text && block.data.text.trim().length > 0;
                        }
                        return true;
                    });
                
                if (!hasContent) {
                    alert('Please enter question text!');
                    return false;
                }
                
                // Serialize question Editor.js JSON to hidden input
                const hiddenInput = document.getElementById('question_text');
                if (hiddenInput) {
                    hiddenInput.value = JSON.stringify(outputData);
                }
                
                // Save all option Editor.js content
                const optionRows = document.querySelectorAll('.option-row');
                let hasEmptyOption = false;
                
                for (let i = 0; i < optionRows.length; i++) {
                    const optionRow = optionRows[i];
                    const index = parseInt(optionRow.getAttribute('data-option-index'));
                    const optionEditor = optionEditors[index];
                    const hiddenInput = document.getElementById(`option_text_${index}`);
                    
                    if (optionEditor && hiddenInput) {
                        try {
                            const optionData = await optionEditor.save();
                            
                            // Check if option has content
                            const hasOptionContent = optionData.blocks && optionData.blocks.length > 0 && 
                                optionData.blocks.some(block => {
                                    if (block.type === 'paragraph' || block.type === 'header') {
                                        return block.data && block.data.text && block.data.text.trim().length > 0;
                                    }
                                    return true;
                                });
                            
                            if (!hasOptionContent) {
                                hasEmptyOption = true;
                                alert(`Please enter text for Option ${i + 1}!`);
                                return false;
                            }
                            
                            hiddenInput.value = JSON.stringify(optionData);
                        } catch (error) {
                            console.error(`Error saving option ${index}:`, error);
                            alert(`Error saving option ${i + 1}. Please try again.`);
                            return false;
                        }
                    } else if (hiddenInput && !hiddenInput.value) {
                        hasEmptyOption = true;
                        alert(`Please enter text for Option ${i + 1}!`);
                        return false;
                    }
                }
                
                // Validate correct option selection
                const correctOption = document.querySelector('input[name="correct_option"]:checked');
                if (!correctOption) {
                    alert('Please select the correct answer!');
                    return false;
                }
                
                // Submit the form
                form.submit();
            } catch (error) {
                console.error('Error saving Editor.js content:', error);
                alert('Error saving question content. Please try again.');
                return false;
            }
        });
    }
</script>
@endpush
