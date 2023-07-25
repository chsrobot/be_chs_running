<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    // แสดงคำสั่งซื้อทั้งหมด
    public function index()
    {
        $orders = Order::all();
        return view('orders.index', compact('orders'));
    }

    // แสดงฟอมคำสั่งซื้อใหม่
    public function create()
    {
        return view('orders.create');
    }

    // บันทึกคำสั่งซื้อใหม่
    public function store(Request $request)
    {
        // ตรวจการรับเข้ามา ดูว่ามีสินค้าไหม
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            
        ]);

        // สร้างคำสั่งซื้อใหม่และบันทึกในฐานข้อมูล
        $order = new Order();
        $order->product_id = $request->input('product_id');
        $order->quantity = $request->input('quantity');
        $order->save();

        return redirect()->route('orders.index')->with('success', 'บันทึกคำสั่งซื้อเรียบร้อยแล้ว');
    }

    // รายละเอียดคำสั่งซื้อ
    public function show($id)
    {
        $order = Order::findOrFail($id);
        return view('orders.show', compact('order'));
    }

    // แก้ไขคำสั่งซื้อ
    public function edit($id)
    {
        $order = Order::findOrFail($id);
        return view('orders.edit', compact('order'));
    }

    // อัปเดตข้อมูลคำสั่งซื้อ
    public function update(Request $request, $id)
    {
        // ตรวจสอบข้อมูลที่รับเข้ามา
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        // หาคำสั่งซื้อที่ต้องการอัปเดต
        $order = Order::findOrFail($id);

        // อัปเดตข้อมูลคำสั่งซื้อ
        $order->product_id = $request->input('product_id');
        $order->quantity = $request->input('quantity');
        $order->save();

        return redirect()->route('orders.index')->with('success', 'อัปเดตคำสั่งซื้อเรียบร้อยแล้ว');
    }

    // ลบคำสั่งซื้อ
    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();

        return redirect()->route('orders.index')->with('success', 'ลบคำสั่งซื้อเรียบร้อยแล้ว');
    }
}
