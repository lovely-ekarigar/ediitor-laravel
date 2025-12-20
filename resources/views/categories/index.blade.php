@extends('layouts.app')

@section('title', 'Categories')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-2">Categories</h1>
    <p class="text-gray-600">Browse all question categories</p>
</div>

<!-- Categories Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($categories as $category)
        <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-indigo-100 rounded-lg p-3">
                    <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                </div>
                <span class="text-3xl font-bold text-indigo-600">{{ $category->questions_count }}</span>
            </div>
            
            <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ $category->name }}</h3>
            <p class="text-gray-500 text-sm mb-4">{{ $category->questions_count }} {{ Str::plural('question', $category->questions_count) }}</p>
            
            <a href="{{ route('questions.index', ['category_id' => $category->id]) }}" 
               class="inline-flex items-center text-indigo-600 hover:text-indigo-800 font-medium transition">
                View Questions
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
    @empty
        <div class="col-span-3 text-center py-12">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
            </svg>
            <p class="text-gray-500 text-lg font-medium">No categories found</p>
        </div>
    @endforelse
</div>
@endsection
