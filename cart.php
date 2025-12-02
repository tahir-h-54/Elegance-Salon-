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

// Handle remove from cart
if(isset($_GET['remove'])) {
    $cart_id = intval($_GET['remove']);
    $delete_query = "DELETE FROM cart WHERE cart_id = $cart_id AND client_id = $client_id";
    mysqli_query($conn, $delete_query);
    header("Location: cart.php");
    exit;
}

// Handle update quantity
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_cart'])) {
    foreach($_POST['quantity'] as $cart_id => $quantity) {
        $cart_id = intval($cart_id);
        $quantity = intval($quantity);
        if($quantity > 0) {
            $update_query = "UPDATE cart SET quantity = $quantity WHERE cart_id = $cart_id AND client_id = $client_id";
            mysqli_query($conn, $update_query);
        } else {
            $delete_query = "DELETE FROM cart WHERE cart_id = $cart_id AND client_id = $client_id";
            mysqli_query($conn, $delete_query);
        }
    }
    $success = "Cart updated successfully!";
}

// Fetch cart items
$cart_query = "
    SELECT c.*, p.name, p.price, p.sale_price, p.image_path, p.stock_quantity
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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <title>Shopping Cart - Elegance Salon</title>
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
                    <a href="shop.php" class="text-gray-700 hover:text-[#CFF752]">Continue Shopping</a>
                    <a href="customer-dashboard.php" class="text-gray-700 hover:text-[#CFF752]">Dashboard</a>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-4xl font-bold mb-8" style="font-family: 'ivymode';">Shopping Cart</h1>

        <?php if($success): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                <p><?php echo $success; ?></p>
            </div>
        <?php endif; ?>

        <?php if(count($cart_items) > 0): ?>
            <form method="POST">
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="p-6">
                        <div class="space-y-6">
                            <?php foreach($cart_items as $item): 
                                $price = $item['sale_price'] ? $item['sale_price'] : $item['price'];
                                $item_total = $price * $item['quantity'];
                            ?>
                                <div class="flex items-center gap-6 border-b pb-6 last:border-0">
                                    <img src="<?php echo htmlspecialchars($item['image_path'] ?: 'images/e3f27f701d467c0ba4656df6a28432e9.jpg'); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" class="w-24 h-24 object-cover rounded">
                                    <div class="flex-1">
                                        <h3 class="font-semibold text-lg"><?php echo htmlspecialchars($item['name']); ?></h3>
                                        <p class="text-[#CFF752] font-bold text-xl">$<?php echo number_format($price, 2); ?></p>
                                    </div>
                                    <div class="flex items-center gap-4">
                                        <input type="number" name="quantity[<?php echo $item['cart_id']; ?>]" value="<?php echo $item['quantity']; ?>" min="1" max="<?php echo $item['stock_quantity']; ?>" class="w-20 px-3 py-2 border border-gray-300 rounded-lg">
                                        <p class="font-bold w-24 text-right">$<?php echo number_format($item_total, 2); ?></p>
                                        <a href="cart.php?remove=<?php echo $item['cart_id']; ?>" class="text-red-600 hover:text-red-800">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="bg-gray-50 p-6 flex justify-between items-center">
                        <button type="submit" name="update_cart" class="px-6 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 font-semibold">
                            Update Cart
                        </button>
                        <div class="text-right">
                            <p class="text-2xl font-bold">Total: $<?php echo number_format($subtotal, 2); ?></p>
                            <a href="checkout.php" class="mt-4 inline-block px-8 py-3 bg-[#CFF752] text-black rounded-full hover:bg-[#b8e042] transition-colors font-bold">
                                Proceed to Checkout
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        <?php else: ?>
            <div class="bg-white rounded-lg shadow p-12 text-center">
                <i class="fas fa-shopping-cart text-6xl text-gray-300 mb-4"></i>
                <h2 class="text-2xl font-bold mb-2">Your cart is empty</h2>
                <p class="text-gray-600 mb-6">Start shopping to add items to your cart</p>
                <a href="shop.php" class="inline-block px-8 py-3 bg-[#CFF752] text-black rounded-full hover:bg-[#b8e042] transition-colors font-bold">
                    Continue Shopping
                </a>
            </div>
        <?php endif; ?>
    </main>

    <footer class="bg-black text-white py-10 px-6 mt-10">
      <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-8">
        <div>
          <img src="../images/elegance-saloon-logo-white.png" alt="Elegance Salon" class="w-44 mb-4">
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
              <a href="../Admin/AD_login.php" class="inline-flex items-center px-4 py-2 bg-[#CFF752] text-black rounded-full text-xs font-semibold hover:bg-[#b8e042] transition-colors">
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

