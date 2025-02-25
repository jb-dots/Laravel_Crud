<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Edit Product') }}
        </h2>
    </x-slot>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS for modern design -->
    <style>
        /* Gradient background for the header */
        .bg-gradient-header {
            background: linear-gradient(90deg, #6a11cb, #2575fc);
        }

        /* Gradient background for the page */
        .bg-gradient-page {
            background: linear-gradient(135deg, #f5f7fa, #c3cfe2);
        }

        /* Form container styling */
        .form-container {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Form input styling */
        .form-control {
            border-radius: 5px;
            border: 1px solid #ddd;
            padding: 0.75rem;
        }

        .form-control:focus {
            border-color: #6a11cb;
            box-shadow: 0 0 5px rgba(106, 17, 203, 0.5);
        }

        /* Label styling */
        .form-label {
            font-weight: 600;
            color: #333;
        }

        /* Button styling */
        .btn-update {
            background: linear-gradient(90deg, #6a11cb, #2575fc);
            border: none;
            color: white;
            font-weight: bold;
            padding: 0.75rem 1.5rem;
            border-radius: 5px;
            transition: background 0.3s ease;
        }

        .btn-update:hover {
            background: linear-gradient(90deg, #2575fc, #6a11cb);
        }

        /* File input styling */
        .file-input {
            position: relative;
            overflow: hidden;
        }

        .file-input input[type="file"] {
            position: absolute;
            top: 0;
            right: 0;
            min-width: 100%;
            min-height: 100%;
            font-size: 100px;
            text-align: right;
            opacity: 0;
            outline: none;
            background: white;
            cursor: pointer;
            display: block;
        }

        .file-input-label {
            display: block;
            padding: 0.75rem;
            background: #f8f9fa;
            border: 1px solid #ddd;
            border-radius: 5px;
            text-align: center;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .file-input-label:hover {
            background: #e9ecef;
        }

        /* Image thumbnail styling */
        .img-thumbnail {
            border-radius: 5px;
            border: 1px solid #ddd;
            padding: 5px;
            background: #f8f9fa;
        }
    </style>

    <div class="py-12 bg-gradient-page">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="form-container">
                <h2 class="mb-4 text-center">Edit Product</h2>
                <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <label for="name" class="form-label">Product Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ $product->name }}" required>
                    </div>
                    <div class="mb-4">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3">{{ $product->description }}</textarea>
                    </div>
                    <div class="mb-4">
                        <label for="price" class="form-label">Price</label>
                        <input type="number" step="0.01" class="form-control" id="price" name="price" value="{{ $product->price }}" required>
                    </div>
                    <div class="mb-4">
                        <label for="stock" class="form-label">Stock</label>
                        <input type="number" class="form-control" id="stock" name="stock" value="{{ $product->stock }}" required>
                    </div>
                    <div class="mb-4">
                        <label for="image" class="form-label">Product Image</label>
                        <div class="file-input">
                            <input type="file" class="form-control" id="image" name="image">
                            <label for="image" class="file-input-label">
                                <i class="fas fa-upload"></i> Choose File
                            </label>
                        </div>
                        @if ($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="img-thumbnail mt-2" width="150">
                        @endif
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-update">
                            <i class="fas fa-save"></i> Update Product
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>