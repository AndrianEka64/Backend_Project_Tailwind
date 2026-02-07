<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrdersResource;
use App\Models\Orders;
use App\Models\Product;
use Illuminate\Http\Request;
use Str;

class OrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Orders::all();
        return OrdersResource::collection($orders);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validate = $request->validate([
                'customer_name' => 'required|string',
                'product_id' => 'required|exists:products,id',
            ]);
            $product = Product::findOrFail($validate['product_id']);
            if ($product->stock <= 0) {
                return response()->json([
                    'message' => 'Product out of stock'
                ]);
            }
            $order = Orders::create([
                'order_code' => 'ORD-' . strtoupper(Str::random(8)),
                'customer_name' => $validate['customer_name'],
                'product_id' => $product->id,
                'order_date' => now(),
                'total_amount' => $product->price,
                'status' => 'processing',
            ]);
            return response()->json([
                'message' => 'Order created successfully',
                'data' => $order
            ]);
        } catch (\Exception $th) {
            return response()->json([
                'message' => 'failed to add order data',
                'error' => $th->getMessage()
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $orders = Orders::find($id);
        return response()->json([
            'data' => $orders
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Orders $orders)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $order = Orders::findOrFail($id);
            $validate = $request->validate([
                'status' => 'required|in:processing,completed,cancelled',
            ]);
            $order->status = $validate['status'];
            $order->save();
            return response()->json([
                'message' => 'Order updated successfully',
                'data' => $order
            ]);
        } catch (\Exception $th) {
            return response()->json([
                'message' => 'Failed to update data',
                'error' => $th->getMessage()
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $order = Orders::find($id)->delete();
        return response()->json([
            'message' => 'Orders data has been successfully deleted',
            'deleted data' => $order
        ]);
    }
}
