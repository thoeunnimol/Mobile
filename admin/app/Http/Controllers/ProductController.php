<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index()
    {
        try {
            $products = Product::with('category')->get();
            return response()->json($products);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error fetching products: ' . $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), Product::rules());

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $product = new Product();
            $product->name = $request->name;
            $product->description = $request->description;
            $product->price = $request->price;
            $product->stock = $request->stock;
            $product->category_id = $request->category_id;
            $product->brand = $request->brand;
            $product->status = $request->status;
            $product->is_active = $request->is_active ?? true;

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->storeAs('products', $imageName, 'public');
                $product->image = 'products/' . $imageName;
            }

            $product->save();
            return response()->json($product->load('category'), 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error creating product: ' . $e->getMessage()], 500);
        }
    }

    public function show(Product $product)
    {
        try {
            return response()->json($product->load('category'));
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error fetching product: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, Product $product)
    {
        try {
            $validator = Validator::make($request->all(), Product::rules());

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $product->name = $request->name;
            $product->description = $request->description;
            $product->price = $request->price;
            $product->stock = $request->stock;
            $product->category_id = $request->category_id;
            $product->brand = $request->brand;
            $product->status = $request->status;
            $product->is_active = $request->is_active ?? true;

            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($product->image) {
                    Storage::disk('public')->delete($product->image);
                }
                
                $image = $request->file('image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->storeAs('products', $imageName, 'public');
                $product->image = 'products/' . $imageName;
            }

            $product->save();
            return response()->json($product->load('category'));
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error updating product: ' . $e->getMessage()], 500);
        }
    }

    public function destroy(Product $product)
    {
        try {
            // Delete image if exists
            if ($product->image) {
                Storage::delete('public/' . $product->image);
            }
            
            $product->delete();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error deleting product: ' . $e->getMessage()], 500);
        }
    }

    public function toggleActive(Product $product)
    {
        $product->update(['is_active' => !$product->is_active]);
        return response()->json($product);
    }
} 