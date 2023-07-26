<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Order;


class OrderController extends Controller
{
    public function index()
    {
        $order = Order::all();
        return response()->json(['orders' => $order], 200);
    }
    public function show($email)
    {
        $order = Order::find($email);
        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }
        return response()->json(['order' => $order], 200);
    }
    public function update(Request $request, $email)
    {
    $validator = Validator::make($request->all(), [
        'product_id.*' => 'required|exists:products,id',
        'quantity.*' => 'required|integer|min:1',
    ]);

    if ($validator->fails()) {
        return response()->json(['error' => $validator->errors()], 422);
    }

    $orders = Order::where('email', $email)->get();

    if ($orders->isEmpty()) {
        return response()->json(['error' => 'Order not found'], 404);
    }

    $product_ids = $request->input('product_id');
    $quantities = $request->input('quantity');

    foreach ($orders as $index => $order) {
        $order->product_id = $product_ids[$index];
        $order->quantity = $quantities[$index];
        $order->save();
    }

    return response()->json(['message' => 'orders updated'], 200);
    }

    public function destroy($email)
    {
        $order = Order::find($email);
        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }
        $order->delete();

        return response()->json(['message' => 'order deleted'], 200);
    }
    public function store(Request $request)
    {
    $validator = Validator::make($request->all(), [
        'email' => 'required|email|unique:orders,email',
        'product_id.*' => 'required|exists:products,id',
        'quantity.*' => 'required|integer|min:1',
    ]);

    if ($validator->fails()) {
        return response()->json(['error' => $validator->errors()], 422);
    }

    $email = $request->input('email');
    $product_ids = $request->input('product_id');
    $quantities = $request->input('quantity');

    foreach ($product_ids as $index => $product_id) {
        $order = new Order();
        $order->email = $email;
        $order->product_id = $product_id;
        $order->quantity = $quantities[$index];
        $order->save();
    }

    return response()->json(['message' => 'orders created'], 201);
    }
}