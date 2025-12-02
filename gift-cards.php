<?php
session_start();
include 'Database/connect_to_db.php';

$success = "";
$error = "";

// Handle gift card purchase
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['purchase_gift_card'])) {
    $amount = floatval($_POST['amount']);
    $recipient_name = mysqli_real_escape_string($conn, $_POST['recipient_name']);
    $recipient_email = mysqli_real_escape_string($conn, $_POST['recipient_email']);
    $message = mysqli_real_escape_string($conn, $_POST['message'] ?? '');
    $purchased_by = isset($_SESSION['client_id']) ? $_SESSION['client_id'] : NULL;
    
    // Generate unique code
    $code = 'GC-' . strtoupper(substr(uniqid(), -8));
    $expiry_date = date('Y-m-d', strtotime('+1 year'));
    
    $insert_query = "INSERT INTO gift_cards (code, amount, balance, purchased_by, recipient_name, recipient_email, message, expiry_date) 
                    VALUES ('$code', $amount, $amount, " . ($purchased_by ? $purchased_by : 'NULL') . ", '$recipient_name', '$recipient_email', '$message', '$expiry_date')";
    
    if(mysqli_query($conn, $insert_query)) {
        $success = "Gift card purchased successfully! Code: $code (Sent to $recipient_email)";
    } else {
        $error = "Failed to purchase gift card. Please try again.";
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
    <title>Gift Cards - Elegance Salon</title>
    <link rel="shortcut icon" href="images/elegance-saloon-short-logo.png" type="image/png">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body>
    <header class="flex justify-around lg:gap-40 fixed w-[100%] z-30 bg-white/80 backdrop-blur-sm">
        <div class="sm:flex hidden">
            <a href="src/Elegance_Salon.php" class="p-2 my-10 lg:mr-40 w-25 text-[15px] justify-center items-center flex bg-none text-[#000] rounded-full border-[#000] border-1 hover:bg-[#000] hover:text-[#fff] cursor-pointer">← Home</a>
        </div>
        <div class="sm:w-50 w-40 flex select-none">
            <a href="src/Elegance_Salon.php"><img src="images/elegance-saloon-logo-no-bg.png" alt="Elegance logo"></a>
        </div>
        <div class="sm:hidden w-10 flex my-10">
            <a href="src/Elegance_Salon.php"><span class="text-2xl">←</span></a>
        </div>
        <div class="sm:flex hidden gap-2">
            <a href="book-appointment.php" class="p-2 my-10 w-30 text-[15px] justify-center items-center flex bg-none text-[#000] rounded-full bg-[#CFF752] cursor-pointer">Book Now</a>
        </div>
    </header>

    <main class="pt-32 pb-20 px-8">
        <div class="max-w-4xl mx-auto">
            <div class="text-center mb-12">
                <h1 class="text-5xl font-bold mb-4" style="font-family: 'ivymode';">Gift Cards</h1>
                <p class="text-gray-600 text-lg">Give the gift of beauty</p>
            </div>

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

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
                <div class="bg-gradient-to-br from-[#CFF752] to-yellow-300 rounded-lg p-8 text-center shadow-lg">
                    <i class="fas fa-gift text-6xl mb-4 text-black"></i>
                    <h2 class="text-3xl font-bold mb-4" style="font-family: 'ivymode';">Perfect Gift</h2>
                    <p class="text-gray-800">Give someone special the gift of beauty and self-care</p>
                </div>
                <div class="bg-white border-2 border-[#CFF752] rounded-lg p-8">
                    <h3 class="text-xl font-bold mb-4">Why Gift Cards?</h3>
                    <ul class="space-y-2 text-gray-700">
                        <li><i class="fas fa-check text-[#CFF752] mr-2"></i> Valid for all services</li>
                        <li><i class="fas fa-check text-[#CFF752] mr-2"></i> Never expires (1 year validity)</li>
                        <li><i class="fas fa-check text-[#CFF752] mr-2"></i> Instant delivery via email</li>
                        <li><i class="fas fa-check text-[#CFF752] mr-2"></i> Perfect for any occasion</li>
                    </ul>
                </div>
            </div>

            <form method="POST" class="bg-white rounded-lg shadow-lg p-8">
                <h2 class="text-2xl font-bold mb-6" style="font-family: 'ivymode';">Purchase Gift Card</h2>
                
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Gift Card Amount *</label>
                    <select name="amount" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#CFF752]">
                        <option value="25">$25</option>
                        <option value="50">$50</option>
                        <option value="100">$100</option>
                        <option value="150">$150</option>
                        <option value="200">$200</option>
                        <option value="500">$500</option>
                    </select>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Recipient Name *</label>
                        <input type="text" name="recipient_name" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#CFF752]" placeholder="Recipient's name">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Recipient Email *</label>
                        <input type="email" name="recipient_email" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#CFF752]" placeholder="recipient@email.com">
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Personal Message (Optional)</label>
                    <textarea name="message" rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#CFF752]" placeholder="Add a personal message..."></textarea>
                </div>

                <button type="submit" name="purchase_gift_card" class="w-full py-4 bg-[#CFF752] text-black rounded-full hover:bg-[#b8e042] transition-colors font-bold text-lg">
                    Purchase Gift Card
                </button>
            </form>
        </div>
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

