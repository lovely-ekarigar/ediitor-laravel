<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    /**
     * Handle image upload from Editor.js.
     */
    public function uploadImage(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:5120', // Increased to 5MB
            ]);

            if (!$request->hasFile('file')) {
                return response()->json([
                    'success' => 0,
                    'error' => 'No file uploaded'
                ], 400);
            }

            $image = $request->file('file');
            
            // Generate unique filename
            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            
            // Store image in storage/app/public/questions
            $path = $image->storeAs('questions', $filename, 'public');
            
            if (!$path) {
                return response()->json([
                    'success' => 0,
                    'error' => 'Failed to store image'
                ], 500);
            }
            
            // Return Editor.js format JSON response
            return response()->json([
                'success' => 1,
                'file' => [
                    'url' => asset('storage/' . $path),
                    'caption' => '',
                    'withBorder' => false,
                    'withBackground' => false,
                    'stretched' => false,
                ]
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Handle validation errors
            $errors = $e->errors();
            $firstError = collect($errors)->flatten()->first();
            
            return response()->json([
                'success' => 0,
                'error' => $firstError ?? 'Validation failed'
            ], 422);
        } catch (\Exception $e) {
            // Handle other errors
            \Log::error('Image upload error: ' . $e->getMessage());
            
            return response()->json([
                'success' => 0,
                'error' => 'Upload failed: ' . $e->getMessage()
            ], 500);
        }
    }
}
