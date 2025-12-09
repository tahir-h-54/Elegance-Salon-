<?php
session_start();
include 'Database/connect_to_db.php';

$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$success = "";
$error = "";

// Fetch product
$product_query = "SELECT * FROM products WHERE product_id = $product_id AND status = 'active'";
$product_result = mysqli_query($conn, $product_query);
$product = mysqli_fetch_assoc($product_result);

if(!$product) {
    header("Location: shop.php");
    exit;
}

// Fetch product images
$images_query = "SELECT * FROM product_images WHERE product_id = $product_id ORDER BY is_primary DESC";
$images_result = mysqli_query($conn, $images_query);

// Handle add to cart
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_to_cart'])) {
    if(!isset($_SESSION['client_logged_in']) || !$_SESSION['client_logged_in']) {
        $error = "Please login to add items to cart.";
    } else {
        $client_id = $_SESSION['client_id'];
        $quantity = intval($_POST['quantity']);
        
        // Check if item already in cart
        $check_cart = "SELECT cart_id, quantity FROM cart WHERE client_id = $client_id AND product_id = $product_id";
        $cart_result = mysqli_query($conn, $check_cart);
        
        if(mysqli_num_rows($cart_result) > 0) {
            $cart_item = mysqli_fetch_assoc($cart_result);
            $new_quantity = $cart_item['quantity'] + $quantity;
            $update_cart = "UPDATE cart SET quantity = $new_quantity WHERE cart_id = " . $cart_item['cart_id'];
            mysqli_query($conn, $update_cart);
        } else {
            $insert_cart = "INSERT INTO cart (client_id, product_id, quantity) VALUES ($client_id, $product_id, $quantity)";
            mysqli_query($conn, $insert_cart);
        }
        
        $success = "Product added to cart!";
    }
}

$price = $product['sale_price'] ? $product['sale_price'] : $product['price'];
$original_price = $product['sale_price'] ? $product['price'] : null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <title><?php echo htmlspecialchars($product['name']); ?> - Elegance Salon</title>
    <link rel="shortcut icon" href="images/elegance-saloon-short-logo.png" type="image/png">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body>
    <header class="flex justify-around lg:gap-40 fixed w-[100%] z-30 bg-white/80 backdrop-blur-sm">
        <div class="sm:flex hidden">
            <a href="shop.php" class="p-2 my-10 lg:mr-40 w-25 text-[15px] justify-center items-center flex bg-none text-[#000] rounded-full border-[#000] border-1 hover:bg-[#000] hover:text-[#fff] cursor-pointer">← Back</a>
        </div>
        <div class="sm:w-50 w-40 flex select-none">
            <a href="src/Elegance_Salon.php"><img src="images/elegance-saloon-logo-no-bg.png" alt="Elegance logo"></a>
        </div>
        <div class="sm:hidden w-10 flex my-10">
            <a href="shop.php"><span class="text-2xl">←</span></a>
        </div>
        <div class="sm:flex hidden gap-2">
            <?php if(isset($_SESSION['client_logged_in'])): ?>
                <a href="cart.php" class="p-2 my-10 w-30 text-[15px] justify-center items-center flex bg-none text-[#000] rounded-full border-[#000] border-1 hover:bg-[#000] hover:text-[#fff] cursor-pointer">
                    <i class="fas fa-shopping-cart"></i> Cart
                </a>
            <?php endif; ?>
        </div>
    </header>

    <main class="pt-32 pb-20 px-8">
        <div class="max-w-6xl mx-auto">
            <?php if($success): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    <p><?php echo $success; ?></p>
                </div>
            <?php endif; ?>

            <?php if($error): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    <p><?php echo $error; ?></p>
                </div>
            <?php endif; ?>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <!-- Product Images -->
                <div>
                    <?php if(mysqli_num_rows($images_result) > 0): ?>
                        <div class="space-y-4">
                            <?php while($img = mysqli_fetch_assoc($images_result)): ?>
                                <img src="<?php echo htmlspecialchars($img['image_path']); ?>" alt="Product Image" class="w-full rounded-lg">
                            <?php endwhile; ?>
                        </div>
                    <?php else: ?>
                        <img src="<?php echo htmlspecialchars($product['image_path'] ?: 'images/e3f27f701d467c0ba4656df6a28432e9.jpg'); ?>" alt="Product Image" class="w-full rounded-lg">
                    <?php endif; ?>
                </div>

                <!-- Product Info -->
                <div>
                    <h1 class="text-4xl font-bold mb-4" style="font-family: 'ivymode';"><?php echo htmlspecialchars($product['name']); ?></h1>
                    
                    <div class="mb-6">
                        <?php if($original_price): ?>
                            <span class="text-gray-400 line-through text-lg">$<?php echo number_format($original_price, 2); ?></span>
                        <?php endif; ?>
                        <span class="text-3xl font-bold text-[#CFF752] ml-2">$<?php echo number_format($price, 2); ?></span>
                        <?php if($product['sale_price']): ?>
                            <span class="ml-2 bg-red-500 text-white px-2 py-1 rounded text-sm">SAVE <?php echo round((($original_price - $price) / $original_price) * 100); ?>%</span>
                        <?php endif; ?>
                    </div>

                    <div class="mb-6">
                        <p class="text-gray-700 leading-relaxed"><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
                    </div>

                    <div class="mb-6">
                        <p class="text-sm text-gray-600 mb-2">
                            <strong>SKU:</strong> <?php echo htmlspecialchars($product['sku']); ?>
                        </p>
                        <p class="text-sm text-gray-600 mb-2">
                            <strong>Category:</strong> <?php echo htmlspecialchars($product['category']); ?>
                        </p>
                        <p class="text-sm <?php echo $product['stock_quantity'] > 0 ? 'text-green-600' : 'text-red-600'; ?> font-semibold">
                            <strong>Stock:</strong> <?php echo $product['stock_quantity'] > 0 ? $product['stock_quantity'] . ' available' : 'Out of stock'; ?>
                        </p>
                    </div>

                    <?php if($product['stock_quantity'] > 0): ?>
                        <form method="POST" class="mb-6">
                            <div class="flex items-center gap-4 mb-4">
                                <label class="font-semibold">Quantity:</label>
                                <input type="number" name="quantity" value="1" min="1" max="<?php echo $product['stock_quantity']; ?>" class="w-20 px-3 py-2 border border-gray-300 rounded-lg">
                            </div>
                            <button type="submit" name="add_to_cart" class="w-full py-4 bg-[#CFF752] text-black rounded-full hover:bg-[#b8e042] transition-colors font-bold text-lg">
                                <i class="fas fa-shopping-cart"></i> Add to Cart
                            </button>
                        </form>
                    <?php else: ?>
                        <button disabled class="w-full py-4 bg-gray-400 text-white rounded-full cursor-not-allowed font-bold text-lg">
                            Out of Stock
                        </button>
                    <?php endif; ?>

                    <?php if(!isset($_SESSION['client_logged_in'])): ?>
                        <p class="text-sm text-gray-600 mt-4">
                            <a href="customer-login.php" class="text-[#CFF752] font-semibold hover:underline">Login</a> to add items to cart
                        </p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
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

