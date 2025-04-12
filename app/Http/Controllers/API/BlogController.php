<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Blog::with('author:id,name');
        
        // Handle search by title
        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }
        
        $blogs = $query->orderBy('created_at', 'desc')->get();
        
        return response()->json($blogs);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'required|in:published,hidden',
        ]);
        
        $validatedData['created_by'] = Auth::id();
        
        $blog = Blog::create($validatedData);
        
        return response()->json($blog, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Blog $blog)
    {
        $blog->load('author:id,name');
        return response()->json($blog);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Blog $blog)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'required|in:published,hidden',
        ]);
        
        $blog->update($validatedData);
        
        return response()->json($blog);
    }

    /**
     * Update the blog status.
     */
    public function updateStatus(Request $request, Blog $blog)
    {
        $validatedData = $request->validate([
            'status' => 'required|in:published,hidden',
        ]);
        
        $blog->update(['status' => $validatedData['status']]);
        
        return response()->json($blog);
    }

    /**
     * Remove the specified resource from storage (soft delete).
     */
    public function destroy(Blog $blog)
    {
        $blog->delete();
        
        return response()->json(['message' => 'Blog archived successfully']);
    }
}