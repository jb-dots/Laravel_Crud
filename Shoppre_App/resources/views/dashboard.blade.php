<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Shoppre') }}
        </h2>
    </x-slot>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Add Product Button -->
                    <a href="{{ route('products.create') }}" class="btn btn-success mb-4">
                        Add New Product
                    </a>
                    
                    <!-- POS Interface -->
                    <div class="row">
                        <div class="col-md-12">
                            <h2>Products</h2>
                            <div class="row">
                                @foreach ($products as $product)
                                    <div class="col-md-4 mb-3">
                                        <div class="card">
                                            <!-- Display Product Image -->
                                            @if ($product->image)
                                                <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}">
                                            @else
                                                <img src="https://via.placeholder.com/150" class="card-img-top" alt="No Image">
                                            @endif
                                            <div class="card-body">
                                                <h5 class="card-title">{{ $product->name }}</h5>
                                                <p class="card-text">{{ $product->description }}</p>
                                                <p class="card-text"><strong>Price:</strong> ₱{{ $product->price }}</p>
                                                <!-- Quantity Input -->
                                                <input type="number" class="form-control mb-2 quantity" data-id="{{ $product->id }}" value="1" min="1">
                                                <!-- Add to Cart Button -->
                                                <button type="button" class="btn btn-success add-to-cart" data-id="{{ $product->id }}" data-price="₱{{ $product->price }}">
                                                    Add to Cart
                                                </button>
                                                <!-- Edit Button -->
                                                <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning mt-2">
                                                    Edit
                                                </a>
                                                <!-- Delete Button -->
                                                <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger mt-2" onclick="return confirm('Are you sure you want to delete this product?')">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-md-4">
                            <h2>Cart</h2>
                            <ul id="cart" class="list-group">
                                <!-- Cart items will be added here dynamically -->
                            </ul>
                            <div class="mt-3">
                                <strong>Total: ₱<span id="total">0.00</span></strong>
                            </div>
                            <button type="button" class="btn btn-success mt-3" id="checkout">Checkout</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Include jQuery for dynamic cart functionality -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            let cart = [];
            let total = 0;

            // Add product to cart
            $('.add-to-cart').click(function () {
                const productId = $(this).data('id');
                const productName = $(this).closest('.card').find('.card-title').text();
                const productPrice = parseFloat($(this).data('price').replace('₱', '')); // Remove ₱ if present
                const quantityInput = $(this).closest('.card-body').find('.quantity');
                const quantity = parseInt(quantityInput.val()) || 1; // Default to 1 if invalid

                console.log('Product Price:', productPrice); // Debug
                console.log('Quantity:', quantity); // Debug

                // Validate productPrice and quantity
                if (isNaN(productPrice) || isNaN(quantity) || quantity < 1) {
                    alert('Invalid product price or quantity.');
                    return;
                }

                // Calculate total price for this product
                const totalPrice = productPrice * quantity;

                console.log('Total Price:', totalPrice); // Debug

                // Add product to cart array
                cart.push({ id: productId, name: productName, price: productPrice, quantity: quantity });
                total += totalPrice;

                // Update the cart UI
                $('#cart').append(`<li class="list-group-item">${productName} (x${quantity}) - ₱${totalPrice.toFixed(2)}</li>`);
                $('#total').text(total.toFixed(2));

                // Reset quantity input to 1
                quantityInput.val(1);
            });

            // Checkout button
            $('#checkout').click(function () {
                alert('Checkout completed! Total: ₱' + total.toFixed(2));
                cart = [];
                total = 0;
                $('#cart').empty();
                $('#total').text('0.00');
            });
        });
    </script>
</x-app-layout>