<?php

use App\Http\Controllers\ProfileController;
use App\Models\Product;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/dashboard', function () {
    $products = Product::all(); // Fetch all products
    return view('dashboard', compact('products'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Display the add product form
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');

    // Handle the form submission
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
});

Route::middleware('auth')->group(function () {
    // Display the edit product form
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');

    // Handle the form submission for updating a product
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
});

Route::middleware('auth')->group(function () {
    // Handle the deletion of a product
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
});

require __DIR__.'/auth.php';
