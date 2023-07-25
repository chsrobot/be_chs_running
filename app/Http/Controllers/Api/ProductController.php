<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
    $request->validate([
        'name' => 'required|string',
        'price' => 'required|numeric',
    ]);

    // สร้างสินค้าใหม่และกำหนดค่าตั้งต้นโดยใช้ Mass Assignment
    $product = [
        ['name' => 'Hat1', 'price' => $request->input('price')],
        ['name' => 'Hat2', 'price' => $request->input('price')],
        ['name' => 'Shirt2km', 'price' => $request->input('price')],
        ['name' => 'Shirt6km', 'price' => $request->input('price')],
        ['name' => 'Shirt10km', 'price' => $request->input('price')],
        ['name' => 'Shirtvip', 'price' => $request->input('price')],
    ];

    
    Product::insert($product);

    return redirect()->route('products.index')->with('success', 'บันทึกสินค้าเรียบร้อยแล้ว');
}
    //รายละเอียดสินค้า
    public function show($id)
    {
        $product = Product::findOrFail($id);
        return view('products.show', compact('product'));
    }

    // แสดงการแก้ไขสินค้า
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('products.edit', compact('product'));
    }

    // อัปเดตข้อมูลสินค้า
    public function update(Request $request, $id)
    {
        //
    }
}