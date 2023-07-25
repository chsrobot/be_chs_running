<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Order;


class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::all();
        return response()->json(['orders' => $orders], 200);
    }
    public function show($email)
    {
        $order = Order::find($email);
        if (!$order) {
            return response()->json(['error' => 'ไม่พบคำสั่งซื้อ'], 404);
        }
        return response()->json(['order' => $order], 200);
    }
    public function update(Request $request, $email)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $order = Order::find($email);
        if (!$order) {
            return response()->json(['error' => 'ไม่พบคำสั่งซื้อ'], 404);
        }

        $order->product_id = $request->input('product_id');
        $order->quantity = $request->input('quantity');
        $order->save();

        return response()->json(['message' => 'อัปเดตคำสั่งซื้อเรียบร้อยแล้ว'], 200);
    }
    public function destroy($email)
    {
        $order = Order::find($email);
        if (!$order) {
            return response()->json(['error' => 'ไม่พบคำสั่งซื้อ'], 404);
        }
        $order->delete();

        return response()->json(['message' => 'ลบคำสั่งซื้อเรียบร้อยแล้ว'], 200);
    }
}
