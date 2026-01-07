@extends('layouts.app')

@section('title', 'Categories')

@section('content')
<div class="mb-6 sm:mb-8">
    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">Categories</h1>
    <p class="text-sm sm:text-base text-gray-600">Browse all question categories</p>
</div>

<!-- Categories Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
    @forelse($categories as $category)
        <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition p-4 sm:p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-indigo-100 rounded-lg p-2 sm:p-3">
                    <svg class="w-6 h-6 sm:w-8 sm:h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                </div>
                <span class="text-2xl sm:text-3xl font-bold text-indigo-600">{{ $category->questions_count }}</span>
            </div>
            
            <h3 class="text-lg sm:text-xl font-semibold text-gray-900 mb-2">{{ $category->name }}</h3>
            <p class="text-gray-500 text-xs sm:text-sm mb-4">{{ $category->questions_count }} {{ Str::plural('question', $category->questions_count) }}</p>
            
            <a href="{{ route('questions.index', ['category_id' => $category->id]) }}" 
               class="inline-flex items-center text-sm sm:text-base text-indigo-600 hover:text-indigo-800 font-medium transition">
                View Questions
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
    @empty
        <div class="col-span-1 sm:col-span-2 lg:col-span-3 text-center py-8 sm:py-12">
            <svg class="w-12 h-12 sm:w-16 sm:h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
            </svg>
            <p class="text-gray-500 text-base sm:text-lg font-medium">No categories found</p>
        </div>
    @endforelse
</div>
@endsection
