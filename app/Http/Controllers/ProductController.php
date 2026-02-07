<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $Product = Product::all();
        return ProductResource::collection($Product);
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
            $validasi = $request->validate([
                'name' => 'required|string',
                'category' => 'required|in:t-shirt,jacket,hat,hoodie,accessories',
                'price' => 'required|min:0',
                'stock' => 'required|min:0',
                'description' => 'required|string',
                'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            ]);
            $images = $request->file('image');
            $images->storeAs('', $images->hashName(), 'public');
            $slug = Str::slug($validasi['name']);
            $product = Product::create([
                'name' => $validasi['name'],
                'slug' => $slug,
                'category' => $validasi['category'],
                'price' => $validasi['price'],
                'stock' => $validasi['stock'],
                'status' => $validasi['stock'] > 0 ? 'available' : 'out-of-stock',
                'description' => $validasi['description'],
                'image' => $images->hashName(),
            ]);
            return response()->json([
                'message' => 'Product data added successfully',
                'data' => new ProductResource($product)
            ]);
        } catch (\Exception $th) {
            return response()->json([
                'message' => 'failed to add product data',
                'error' => $th->getMessage()
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $Product = Product::find($id);
        return response()->json([
            'data' => new ProductResource($Product)
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $product = Product::findOrFail($id);
            $validasi = $request->validate([
                'name' => 'required|string',
                'category' => 'required|in:t-shirt,jacket,hat,hoodie,accessories',
                'price' => 'required|min:0',
                'stock' => 'required|min:0',
                'description' => 'required|string',
                'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            ]);
            $slug = Str::slug($validasi['name']);
            if ($request->hasFile('image')) {
                if ($product->image && Storage::disk('public')->exists($product->image)) {
                    Storage::disk('public')->delete($product->image);
                }
                $imagePath = $request->file('image')->store('products', 'public');
            } else {
                $imagePath = $product->image;
            }
            $product->update([
                'name' => $validasi['name'],
                'slug' => $slug,
                'category' => $validasi['category'],
                'price' => $validasi['price'],
                'stock' => $validasi['stock'],
                'status' => $validasi['stock'] > 0 ? 'available' : 'out-of-stock',
                'description' => $validasi['description'],
                'image' => $imagePath,
            ]);
            return response()->json([
                'message' => 'product data updated successfully',
                'data' => new ProductResource($product)
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
        $Product = Product::find($id)->delete();
        return response()->json([
            'message' => 'Product data has been successfully deleted',
            'deleted data' => $Product
        ]);
    }
}