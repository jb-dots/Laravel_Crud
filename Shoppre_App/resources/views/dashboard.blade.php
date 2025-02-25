<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Shoppre - Dashboard') }}
        </h2>
    </x-slot>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS for dazzling effects -->
    <style>
        /* Gradient background for the header */
        .bg-gradient-header {
            background: linear-gradient(90deg, #6a11cb, #2575fc);
        }

        /* Gradient background for the dashboard */
        .bg-gradient-dashboard {
            background: linear-gradient(135deg, #f5f7fa, #c3cfe2);
        }

        /* Card hover effect */
        .card:hover {
            transform: translateY(-5px);
            transition: transform 0.3s ease;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }

        /* Add shadow to cards */
        .card {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border: none;
            border-radius: 10px;
        }

        /* Style for the "Add New Product" button */
        .btn-add-product {
            background: linear-gradient(90deg, #6a11cb, #2575fc);
            border: none;
            color: white;
            font-weight: bold;
        }

        /* Style for the "Add to Cart" button */
        .btn-add-to-cart {
            background: linear-gradient(90deg, #4caf50, #81c784);
            border: none;
            color: white;
            font-weight: bold;
        }

        /* Style for the "Checkout" button */
        .btn-checkout {
            background: linear-gradient(90deg, #ff416c, #ff4b2b);
            border: none;
            color: white;
            font-weight: bold;
        }

        /* Style for the cart section */
        .cart-section {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Style for the product cards */
        .product-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
        }

        /* Style for the quantity input */
        .quantity-input {
            width: 60px;
            text-align: center;
        }
    </style>

    <div class="py-12 bg-gradient-dashboard">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Add Product Button -->
                    <a href="{{ route('products.create') }}" class="btn btn-add-product mb-4">
                        <i class="fas fa-plus"></i> Add New Product
                    </a>
                    
                    <!-- POS Interface -->
                    <div class="row">
                        <div class="col-md-8">
                            <h2 class="mb-4">Products</h2>
                            <div class="row">
                                @foreach ($products as $product)
                                    <div class="col-md-4 mb-4">
                                        <div class="card product-card">
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
                                                <input type="number" class="form-control mb-2 quantity-input" data-id="{{ $product->id }}" value="1" min="1">
                                                <!-- Add to Cart Button -->
                                                <button type="button" class="btn btn-add-to-cart add-to-cart" data-id="{{ $product->id }}" data-price="₱{{ $product->price }}">
                                                    <i class="fas fa-cart-plus"></i> Add to Cart
                                                </button>
                                                <!-- Edit Button -->
                                                <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning mt-2">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                                <!-- Delete Button -->
                                                <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger mt-2" onclick="return confirm('Are you sure you want to delete this product?')">
                                                        <i class="fas fa-trash"></i> Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="cart-section">
                                <h2 class="mb-4">Cart</h2>
                                <ul id="cart" class="list-group mb-3">
                                    <!-- Cart items will be added here dynamically -->
                                </ul>
                                <div class="mt-3">
                                    <strong>Total: ₱<span id="total">0.00</span></strong>
                                </div>
                                <button type="button" class="btn btn-checkout mt-3" id="checkout">
                                    <i class="fas fa-check"></i> Checkout
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Checkout Form (Hidden) -->
    <form id="checkout-form" action="{{ route('checkout') }}" method="POST" style="display: none;">
        @csrf
        <input type="hidden" name="cart" id="cart-data">
    </form>

    <!-- Include jQuery for dynamic cart functionality -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            let cart = [];
            let total = 0;

            // Add product to cart
            $('.add-to-cart').click(function () {
                const button = $(this);
                button.prop('disabled', true);
                button.find('.spinner-border').removeClass('d-none');

                const productId = $(this).data('id');
                const productName = $(this).closest('.card').find('.card-title').text();
                const productPrice = parseFloat($(this).data('price').replace('₱', ''));
                const quantityInput = $(this).closest('.card-body').find('.quantity-input');
                const quantity = parseInt(quantityInput.val()) || 1;

                if (isNaN(productPrice) || isNaN(quantity) || quantity < 1) {
                    alert('Invalid product price or quantity.');
                    button.prop('disabled', false);
                    button.find('.spinner-border').addClass('d-none');
                    return;
                }

                const totalPrice = productPrice * quantity;

                // Check if the product already exists in the cart
                const existingProduct = cart.find(item => item.id === productId);
                if (existingProduct) {
                    existingProduct.quantity += quantity;
                    existingProduct.totalPrice += totalPrice;
                } else {
                    cart.push({ id: productId, name: productName, price: productPrice, quantity: quantity, totalPrice: totalPrice });
                }

                // Update the total
                total = cart.reduce((sum, item) => sum + item.totalPrice, 0);

                // Update the cart UI
                updateCartUI();
                quantityInput.val(1);

                button.prop('disabled', false);
                button.find('.spinner-border').addClass('d-none');
            });

            // Remove item from cart
            $(document).on('click', '.remove-item', function () {
                const index = $(this).data('index');
                const removedItem = cart.splice(index, 1)[0];
                total -= removedItem.totalPrice;
                updateCartUI();
            });

            // Checkout button
            $('#checkout').click(function () {
                if (cart.length === 0) {
                    alert('Your cart is empty!');
                    return;
                }

                // Serialize cart data
                $('#cart-data').val(JSON.stringify(cart));
                $('#checkout-form').submit();
            });

            // Update the cart UI
            function updateCartUI() {
                $('#cart').empty();
                cart.forEach((item, index) => {
                    $('#cart').append(`
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            ${item.name} (x${item.quantity}) - ₱${item.totalPrice.toFixed(2)}
                            <button type="button" class="btn btn-danger btn-sm remove-item" data-index="${index}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </li>
                    `);
                });
                $('#total').text(total.toFixed(2));
            }
        });
    </script>
</x-app-layout>