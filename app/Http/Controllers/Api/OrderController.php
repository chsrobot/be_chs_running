<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Order;
use App\Models\Account;


class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::all();
        return response()->json(['orders' => $orders], 200);
    }
    public function create()
    {
        return view('orders.create');
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
        $order = new Order();
        $order->product_id = $request->input('product_id');
        $order->quantity = $request->input('quantity');
        $order->save();

        return response()->json(['message' => 'บันทึกคำสั่งซื้อเรียบร้อยแล้ว'], 201);
    }
    public function show($id)
    {
        $order = Order::findOrFail($id);
        return response()->json(['order' => $order], 200);
    }
    public function edit($id)
    {
        $order = Order::findOrFail($id);
        return view('orders.edit', compact('order'));
    }
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'email' => 'required|email',
            'password' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
        $account = Account::where('email', $request->input('email'))->first();
        if (!$account) {
            $account = new Account();
            $account->email = $request->input('email');
            $account->password = bcrypt($request->input('password')); 
            $account->save();
        }
        $order = new Order();
        $order->product_id = $request->input('product_id');
        $order->quantity = $request->input('quantity');
        $order->user_id = $account->id; 
        $order->save();

        return response()->json(['message' => 'บันทึกคำสั่งซื้อเรียบร้อยแล้ว'], 201);
    }
    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();

        return response()->json(['message' => 'ลบคำสั่งซื้อเรียบร้อยแล้ว'], 200);
    }
}
