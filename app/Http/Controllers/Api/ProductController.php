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
        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric',
        ]);
        $productData = [
            ['name' => 'Hat1', 'price' => $request->input('price')],
            ['name' => 'Hat2', 'price' => $request->input('price')],
            ['name' => 'Shirt2km', 'price' => $request->input('price')],
            ['name' => 'Shirt6km', 'price' => $request->input('price')],
            ['name' => 'Shirt10km', 'price' => $request->input('price')],
            ['name' => 'Shirtvip', 'price' => $request->input('price')],
        ];
        try {
            if (count($productData) > 0) {
                Product::insert($productData);
                return response()->json(['message' => 'บันทึกสินค้าเรียบร้อยแล้ว'], 201);
            } else {
                return response()->json(['message' => 'ไม่พบข้อมูลสินค้าที่ต้องการบันทึก'], 422);
            }
        } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
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