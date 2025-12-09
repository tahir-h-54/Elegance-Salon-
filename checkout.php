<?php
session_start();
include 'Database/connect_to_db.php';

if(!isset($_SESSION['client_logged_in']) || !$_SESSION['client_logged_in']) {
    header("Location: customer-login.php");
    exit;
}

$client_id = $_SESSION['client_id'];
$success = "";
$error = "";

// Fetch cart items
$cart_query = "
    SELECT c.*, p.name, p.price, p.sale_price, p.stock_quantity
    FROM cart c
    JOIN products p ON c.product_id = p.product_id
    WHERE c.client_id = $client_id
";
$cart_result = mysqli_query($conn, $cart_query);

$subtotal = 0;
$cart_items = [];
while($item = mysqli_fetch_assoc($cart_result)) {
    $price = $item['sale_price'] ? $item['sale_price'] : $item['price'];
    $item_total = $price * $item['quantity'];
    $subtotal += $item_total;
    $cart_items[] = $item;
}

// Fetch client data
$client_query = "SELECT * FROM clients WHERE client_id = $client_id";
$client = mysqli_fetch_assoc(mysqli_query($conn, $client_query));

// Handle checkout
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['place_order'])) {
    if(count($cart_items) == 0) {
        $error = "Your cart is empty.";
    } else {
        $shipping_address = mysqli_real_escape_string($conn, $_POST['shipping_address']);
        $payment_method = mysqli_real_escape_string($conn, $_POST['payment_method']);
        $discount_code = mysqli_real_escape_string($conn, $_POST['discount_code'] ?? '');
        
        $discount_amount = 0;
        if($discount_code) {
            // Check discount code
            $discount_query = "SELECT * FROM discounts WHERE code = '$discount_code' AND status = 'active' AND start_date <= CURDATE() AND (end_date IS NULL OR end_date >= CURDATE())";
            $discount_result = mysqli_query($conn, $discount_query);
            if(mysqli_num_rows($discount_result) > 0) {
                $discount = mysqli_fetch_assoc($discount_result);
                if($subtotal >= $discount['min_purchase']) {
                    if($discount['discount_type'] == 'percentage') {
                        $discount_amount = ($subtotal * $discount['discount_value']) / 100;
                        if($discount['max_discount']) {
                            $discount_amount = min($discount_amount, $discount['max_discount']);
                        }
                    } else {
                        $discount_amount = $discount['discount_value'];
                    }
                }
            }
        }
        
        $final_amount = $subtotal - $discount_amount;
        $order_number = 'ORD-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
        
        // Create order
        $insert_order = "INSERT INTO orders (client_id, order_number, total_amount, discount_code, discount_amount, final_amount, shipping_address, payment_method, status) 
                        VALUES ($client_id, '$order_number', $subtotal, '$discount_code', $discount_amount, $final_amount, '$shipping_address', '$payment_method', 'pending')";
        
        if(mysqli_query($conn, $insert_order)) {
            $order_id = mysqli_insert_id($conn);
            
            // Create order items and clear cart
            foreach($cart_items as $item) {
                $price = $item['sale_price'] ? $item['sale_price'] : $item['price'];
                $subtotal_item = $price * $item['quantity'];
                $insert_item = "INSERT INTO order_items (order_id, product_id, quantity, price, subtotal) 
                              VALUES ($order_id, " . $item['product_id'] . ", " . $item['quantity'] . ", $price, $subtotal_item)";
                mysqli_query($conn, $insert_item);
            }
            
            // Clear cart
            $clear_cart = "DELETE FROM cart WHERE client_id = $client_id";
            mysqli_query($conn, $clear_cart);
            
            $success = "Order placed successfully! Order Number: $order_number";
        } else {
            $error = "Failed to place order. Please try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <title>Checkout - Elegance Salon</title>
    <link rel="shortcut icon" href="images/elegance-saloon-short-logo.png" type="image/png">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gray-50">
    <nav class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="src/Elegance_Salon.php" class="flex items-center">
                        <img src="images/elegance-saloon-logo-no-bg.png" alt="Logo" class="h-10">
                    </a>
                </div>
                <div class="flex items-center gap-4">
                    <a href="cart.php" class="text-gray-700 hover:text-[#CFF752]">← Back to Cart</a>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-4xl font-bold mb-8" style="font-family: 'ivymode';">Checkout</h1>

        <?php if($success): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                <p><?php echo $success; ?></p>
                <a href="customer-dashboard.php" class="text-green-800 font-semibold hover:underline mt-2 inline-block">View Orders →</a>
            </div>
        <?php endif; ?>

        <?php if($error): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                <p><?php echo $error; ?></p>
            </div>
        <?php endif; ?>

        <?php if(count($cart_items) > 0): ?>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2">
                    <form method="POST" class="bg-white rounded-lg shadow p-6 space-y-6">
                        <h2 class="text-2xl font-bold mb-4">Shipping Information</h2>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Shipping Address *</label>
                            <textarea name="shipping_address" required rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#CFF752]" placeholder="Enter your shipping address"><?php echo htmlspecialchars($client['address'] ?? ''); ?></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method *</label>
                            <select name="payment_method" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#CFF752]">
                                <option value="">Select payment method</option>
                                <option value="cash_on_delivery">Cash on Delivery</option>
                                <option value="credit_card">Credit Card</option>
                                <option value="paypal">PayPal</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Discount Code (Optional)</label>
                            <input type="text" name="discount_code" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#CFF752]" placeholder="Enter discount code">
                        </div>

                        <button type="submit" name="place_order" class="w-full py-4 bg-[#CFF752] text-black rounded-full hover:bg-[#b8e042] transition-colors font-bold text-lg">
                            Place Order
                        </button>
                    </form>
                </div>

                <div>
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-2xl font-bold mb-4">Order Summary</h2>
                        <div class="space-y-3 mb-4">
                            <?php foreach($cart_items as $item): 
                                $price = $item['sale_price'] ? $item['sale_price'] : $item['price'];
                            ?>
                                <div class="flex justify-between text-sm">
                                    <span><?php echo htmlspecialchars($item['name']); ?> x<?php echo $item['quantity']; ?></span>
                                    <span>$<?php echo number_format($price * $item['quantity'], 2); ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="border-t pt-4 space-y-2">
                            <div class="flex justify-between">
                                <span>Subtotal:</span>
                                <span>$<?php echo number_format($subtotal, 2); ?></span>
                            </div>
                            <div class="flex justify-between font-bold text-xl">
                                <span>Total:</span>
                                <span class="text-[#CFF752]">$<?php echo number_format($subtotal, 2); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="bg-white rounded-lg shadow p-12 text-center">
                <p class="text-gray-600 mb-6">Your cart is empty.</p>
                <a href="shop.php" class="inline-block px-8 py-3 bg-[#CFF752] text-black rounded-full hover:bg-[#b8e042] transition-colors font-bold">
                    Continue Shopping
                </a>
            </div>
        <?php endif; ?>
    </main>

    <footer class="bg-black text-white py-10 px-6 mt-10">
      <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-8">
        <div>
          <img src="images/elegance-saloon-logo-white.png" alt="Elegance Salon" class="w-44 mb-4">
          <p class="text-gray-400 text-sm">
            Because you deserve beauty. Premium hair, skin and spa experiences in one elegant space.
          </p>
        </div>
        <div>
          <h4 class="font-semibold mb-3 text-sm tracking-wide">Explore</h4>
          <ul class="space-y-2 text-sm text-gray-400">
            <li><a href="../services.php" class="hover:text-[#CFF752]">Services</a></li>
            <li><a href="../gallery.php" class="hover:text-[#CFF752]">Gallery</a></li>
            <li><a href="../shop.php" class="hover:text-[#CFF752]">Shop</a></li>
            <li><a href="../blog.php" class="hover:text-[#CFF752]">Blog</a></li>
          </ul>
        </div>
        <div>
          <h4 class="font-semibold mb-3 text-sm tracking-wide">For Guests</h4>
          <ul class="space-y-2 text-sm text-gray-400">
            <li><a href="../book-appointment.php" class="hover:text-[#CFF752]">Book Appointment</a></li>
            <li><a href="../membership-plans.php" class="hover:text-[#CFF752]">Memberships</a></li>
            <li><a href="../gift-cards.php" class="hover:text-[#CFF752]">Gift Cards</a></li>
            <li><a href="../customer-dashboard.php" class="hover:text-[#CFF752]">My account</a></li>
          </ul>
        </div>
        <div>
          <h4 class="font-semibold mb-3 text-sm tracking-wide">Admin & Contact</h4>
          <ul class="space-y-2 text-sm text-gray-400">
            <li><span class="block">Email: info@elegancesalon.com</span></li>
            <li><span class="block">Phone: +1 (555) 123-4567</span></li>
            <li><span class="block">123 Beauty Street, City</span></li>
            <li class="pt-2">
              <a href="Admin/AD_login.php" class="inline-flex items-center px-4 py-2 bg-[#CFF752] text-black rounded-full text-xs font-semibold hover:bg-[#b8e042] transition-colors">
                Admin Panel Login
              </a>
            </li>
          </ul>
        </div>
      </div>
      <div class="border-t border-gray-800 mt-8 pt-4 text-center text-xs text-gray-500">
        &copy; <?php echo date('Y'); ?> Elegance Salon. All rights reserved.
      </div>
    </footer>
</body>
</html>

