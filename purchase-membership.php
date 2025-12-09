<?php
session_start();
include 'Database/connect_to_db.php';

if(!isset($_SESSION['client_logged_in']) || !$_SESSION['client_logged_in']) {
    header("Location: customer-login.php");
    exit;
}

$membership_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$client_id = $_SESSION['client_id'];
$success = "";
$error = "";

// Fetch membership details
$membership_query = "SELECT * FROM memberships WHERE membership_id = $membership_id AND status = 'active'";
$membership_result = mysqli_query($conn, $membership_query);
$membership = mysqli_fetch_assoc($membership_result);

if(!$membership) {
    header("Location: membership-plans.php");
    exit;
}

// Handle purchase
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['purchase_membership'])) {
    // Check if client already has active membership
    $check_membership = "SELECT * FROM client_memberships WHERE client_id = $client_id AND status = 'active' AND end_date >= CURDATE()";
    $check_result = mysqli_query($conn, $check_membership);
    
    if(mysqli_num_rows($check_result) > 0) {
        $error = "You already have an active membership. Please wait for it to expire before purchasing a new one.";
    } else {
        $start_date = date('Y-m-d');
        $end_date = date('Y-m-d', strtotime("+{$membership['duration_days']} days"));
        
        $insert_query = "INSERT INTO client_memberships (client_id, membership_id, start_date, end_date, status) 
                        VALUES ($client_id, $membership_id, '$start_date', '$end_date', 'active')";
        
        if(mysqli_query($conn, $insert_query)) {
            $success = "Membership purchased successfully! Your membership is active until " . date('M d, Y', strtotime($end_date));
        } else {
            $error = "Failed to purchase membership. Please try again.";
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
    <title>Purchase Membership - Elegance Salon</title>
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
                    <a href="membership-plans.php" class="text-gray-700 hover:text-[#CFF752]">← Back to Plans</a>
                    <a href="customer-dashboard.php" class="text-gray-700 hover:text-[#CFF752]">Dashboard</a>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-4xl font-bold mb-8" style="font-family: 'ivymode';">Purchase Membership</h1>

        <?php if($success): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                <p><?php echo $success; ?></p>
                <a href="customer-dashboard.php" class="text-green-800 font-semibold hover:underline mt-2 inline-block">Go to Dashboard →</a>
            </div>
        <?php endif; ?>

        <?php if($error): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                <p><?php echo $error; ?></p>
            </div>
        <?php endif; ?>

        <div class="bg-white rounded-lg shadow-lg p-8">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold mb-2" style="font-family: 'ivymode';"><?php echo htmlspecialchars($membership['name']); ?></h2>
                <div class="text-5xl font-bold text-[#CFF752] mb-2">$<?php echo number_format($membership['price'], 2); ?></div>
                <p class="text-gray-600">Valid for <?php echo $membership['duration_days']; ?> days</p>
            </div>

            <div class="mb-8">
                <h3 class="text-xl font-bold mb-4">Membership Benefits:</h3>
                <p class="text-gray-700 mb-4"><?php echo htmlspecialchars($membership['description']); ?></p>
                <?php if($membership['benefits']): ?>
                    <ul class="space-y-2">
                        <?php 
                        $benefits = explode("\n", $membership['benefits']);
                        foreach($benefits as $benefit): 
                            $benefit = trim($benefit);
                            if($benefit):
                        ?>
                            <li class="flex items-start">
                                <i class="fas fa-check text-[#CFF752] mr-2 mt-1"></i>
                                <span class="text-gray-700"><?php echo htmlspecialchars($benefit); ?></span>
                            </li>
                        <?php 
                            endif;
                        endforeach; 
                        ?>
                    </ul>
                <?php endif; ?>
                <?php if($membership['discount_percentage'] > 0): ?>
                    <div class="mt-4 p-4 bg-[#CFF752] bg-opacity-20 rounded-lg">
                        <p class="font-semibold text-lg">
                            <i class="fas fa-tag"></i> <?php echo $membership['discount_percentage']; ?>% Discount on All Services
                        </p>
                    </div>
                <?php endif; ?>
            </div>

            <form method="POST">
                <div class="border-t pt-6">
                    <div class="flex justify-between items-center mb-6">
                        <span class="text-xl font-semibold">Total Amount:</span>
                        <span class="text-3xl font-bold text-[#CFF752]">$<?php echo number_format($membership['price'], 2); ?></span>
                    </div>
                    <button type="submit" name="purchase_membership" class="w-full py-4 bg-[#CFF752] text-black rounded-full hover:bg-[#b8e042] transition-colors font-bold text-lg">
                        Confirm Purchase
                    </button>
                </div>
            </form>
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

