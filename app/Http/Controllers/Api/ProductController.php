<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Product; 

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return response()->json(['message' => 'Data found', 'data' => $products], 200);
    }

    public function create()
    {
        
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric',
        ]);
        $products = [
            ['name' => 'Hat1', 'price' => $request->input('price')],
            ['name' => 'Hat2', 'price' => $request->input('price')],
            ['name' => 'Shirt2km', 'price' => $request->input('price')],
            ['name' => 'Shirt6km', 'price' => $request->input('price')],
            ['name' => 'Shirt10km', 'price' => $request->input('price')],
            ['name' => 'Shirtvip', 'price' => $request->input('price')],
        ];
        try {
            if (count($products) > 0) {
                Product::insert($products);
                return response()->json(['message' => 'Product saved'], 201);
            } else {
                return response()->json(['message' => 'Not found Product'], 404);
            }
        } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage('error')], 500);
        }
    }
    public function show($id)
    {
        $product = Product::findOrFail($id);
        return view('products.show', compact('product'));
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, $id)
    {
        //
    }
}