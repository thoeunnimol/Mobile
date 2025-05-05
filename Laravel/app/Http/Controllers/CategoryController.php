<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index()
    {
        try {
            $categories = Category::all();
            return response()->json($categories);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error fetching categories: ' . $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), Category::rules());

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $category = new Category();
            $category->name = $request->name;
            $category->description = $request->description;

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->storeAs('category', $imageName, 'public');
                $category->image = 'category/' . $imageName;
            }

            $category->save();
            return response()->json($category, 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error creating category: ' . $e->getMessage()], 500);
        }
    }

    public function show(Category $category)
    {
        try {
            return response()->json($category);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error fetching category: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, Category $category)
    {
        try {
            $validator = Validator::make($request->all(), Category::rules());

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $category->name = $request->name;
            $category->description = $request->description;

            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($category->image) {
                    Storage::disk('public')->delete($category->image);
                }
                
                $image = $request->file('image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->storeAs('category', $imageName, 'public');
                $category->image = 'category/' . $imageName;
            }

            $category->save();
            return response()->json($category);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error updating category: ' . $e->getMessage()], 500);
        }
    }

    public function destroy(Category $category)
    {
        try {
            // Delete image if exists
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
            
            $category->delete();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error deleting category: ' . $e->getMessage()], 500);
        }
    }
} 