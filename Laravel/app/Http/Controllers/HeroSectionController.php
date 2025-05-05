<?php

namespace App\Http\Controllers;

use App\Models\HeroSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HeroSectionController extends Controller
{
    public function index()
    {
        $heroSections = HeroSection::where('is_active', true)->get();
        return response()->json($heroSections);
    }

    public function show($page)
    {
        $heroSection = HeroSection::where('page_name', $page)
            ->where('is_active', true)
            ->firstOrFail();
        return response()->json($heroSection);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'page_name' => 'required|string|unique:hero_sections',
            'title' => 'required|string',
            'subtitle' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'button_text' => 'nullable|string',
            'button_link' => 'nullable|string',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('hero_images', 'public');
            $validated['image'] = $path;
        }

        $heroSection = HeroSection::create($validated);
        return response()->json($heroSection, 201);
    }

    public function update(Request $request, $id)
    {
        $heroSection = HeroSection::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string',
            'subtitle' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'button_text' => 'nullable|string',
            'button_link' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($heroSection->image) {
                Storage::disk('public')->delete($heroSection->image);
            }
            $path = $request->file('image')->store('hero_images', 'public');
            $validated['image'] = $path;
        }

        $heroSection->update($validated);
        return response()->json($heroSection);
    }

    public function destroy($id)
    {
        $heroSection = HeroSection::findOrFail($id);
        
        if ($heroSection->image) {
            Storage::disk('public')->delete($heroSection->image);
        }
        
        $heroSection->delete();
        return response()->json(null, 204);
    }
} 