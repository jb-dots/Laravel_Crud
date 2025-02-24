<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
}