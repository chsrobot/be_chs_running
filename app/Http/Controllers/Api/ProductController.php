<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ProductList;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return response()->json(['data' => $products], 200);
    }

    public function store(Request $request)
    {
        $productData = $request->only(['name', 'price', 'category']);
    
        if ($request->hasFile('picture')) {
            $picture = $request->file('picture');
            $picturePath = $picture->store('products', 'public');
            $productData['picture_path'] = $picturePath;
        }
    
        $product = Product::create($productData);
    
        return response()->json(['message' => 'Product created successfully', 'data' => $product], 201);
    }
    
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
    
        $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category' => 'required|string',
            'picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        $productData = $request->only(['name', 'price', 'category']);
    
        if ($request->hasFile('picture')) {
            $existingPicturePath = $product->picture_path;
            if ($existingPicturePath) {
                Storage::disk('public')->delete($existingPicturePath);
            }
    
            $picture = $request->file('picture');
            $picturePath = $picture->store('products', 'public');
            $productData['picture_path'] = $picturePath;
        }
    
        $product->update($productData);
    
        return response()->json(['message' => 'Product updated successfully', 'data' => $product], 200);
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return response()->json(['message' => 'Product deleted successfully'], 200);
    }
}
