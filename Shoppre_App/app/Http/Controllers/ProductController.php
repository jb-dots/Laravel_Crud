<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    // Display the add product form
    public function create()
    {
        return view('products.create');
    }

    // Handle the form submission
    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public'); // Store in public disk
        } else {
            $imagePath = null;
        }
    
        // Create a new product
        Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'image' => $imagePath,
        ]);
    
        // Redirect to the dashboard with a success message
        return redirect()->route('dashboard')->with('success', 'Product added successfully!');
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }
    
    public function update(Request $request, Product $product)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete the old image if it exists
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            // Store the new image
            $imagePath = $request->file('image')->store('products', 'public');
        } else {
            $imagePath = $product->image;
        }
    
        // Update the product
        $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'image' => $imagePath,
        ]);
    
        // Redirect to the dashboard with a success message
        return redirect()->route('dashboard')->with('success', 'Product updated successfully!');
    }    
    public function destroy(Product $product)
    {
        // Delete the product image if it exists
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
    
        // Delete the product
        $product->delete();
    
        // Redirect to the dashboard with a success message
        return redirect()->route('dashboard')->with('success', 'Product deleted successfully!');
    }
    public function checkout(Request $request)
    {
        // Validate the request
        $request->validate([
            'cart' => 'required|json',
        ]);

        // Decode the cart data
        $cart = json_decode($request->input('cart'), true);

        // Start a database transaction
        DB::beginTransaction();

        try {
            // Example: Save the order to the database
            $order = DB::table('orders')->insertGetId([
                'total_amount' => array_reduce($cart, function ($sum, $item) {
                    return $sum + ($item['price'] * $item['quantity']);
                }, 0),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Save each item in the cart as an order item
            foreach ($cart as $item) {
                DB::table('order_items')->insert([
                    'order_id' => $order,
                    'product_name' => $item['name'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'total_price' => $item['price'] * $item['quantity'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Commit the transaction
            DB::commit();

            // Redirect with a success message
            return redirect()->route('dashboard')->with('success', 'Order placed successfully!');
        } catch (\Exception $e) {
            // Rollback the transaction on error
            DB::rollBack();

            // Redirect with an error message
            return redirect()->route('dashboard')->with('error', 'Failed to place the order. Please try again.');
        }
    }
}