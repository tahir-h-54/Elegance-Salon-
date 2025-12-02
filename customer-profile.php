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

// Fetch client data
$client_query = "SELECT * FROM clients WHERE client_id = $client_id";
$client_result = mysqli_query($conn, $client_query);
$client = mysqli_fetch_assoc($client_result);

// Handle profile update
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $preferences = mysqli_real_escape_string($conn, $_POST['preferences']);

    $update_query = "UPDATE clients SET name = '$name', phone = '$phone', address = '$address', preferences = '$preferences' WHERE client_id = $client_id";
    
    if(mysqli_query($conn, $update_query)) {
        $_SESSION['client_name'] = $name;
        $success = "Profile updated successfully!";
        // Refresh client data
        $client_result = mysqli_query($conn, $client_query);
        $client = mysqli_fetch_assoc($client_result);
    } else {
        $error = "Failed to update profile. Please try again.";
    }
}

// Handle password change
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Verify current password
    $check_query = "SELECT password FROM client_login WHERE client_id = $client_id";
    $check_result = mysqli_query($conn, $check_query);
    $login_data = mysqli_fetch_assoc($check_result);

    if(password_verify($current_password, $login_data['password'])) {
        if($new_password === $confirm_password) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $update_password = "UPDATE client_login SET password = '$hashed_password' WHERE client_id = $client_id";
            
            if(mysqli_query($conn, $update_password)) {
                $success = "Password changed successfully!";
            } else {
                $error = "Failed to change password. Please try again.";
            }
        } else {
            $error = "New passwords do not match.";
        }
    } else {
        $error = "Current password is incorrect.";
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
    <title>My Profile - Elegance Salon</title>
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
                    <a href="customer-dashboard.php" class="text-gray-700 hover:text-[#CFF752]">Dashboard</a>
                    <a href="book-appointment.php" class="text-gray-700 hover:text-[#CFF752]">Book Appointment</a>
                    <a href="customer-logout.php" class="text-gray-700 hover:text-red-600">Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-8">
            <h1 class="text-4xl font-bold" style="font-family: 'ivymode';">My Profile</h1>
            <p class="text-gray-600 mt-2">Manage your personal information and preferences</p>
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

        <!-- Profile Information -->
        <div class="bg-white rounded-lg shadow mb-8">
            <div class="p-6 border-b">
                <h2 class="text-2xl font-bold" style="font-family: 'ivymode';">Personal Information</h2>
            </div>
            <form method="POST" class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                        <input type="text" name="name" value="<?php echo htmlspecialchars($client['name']); ?>" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#CFF752]">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" value="<?php echo htmlspecialchars($client['email']); ?>" disabled class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-100">
                        <p class="text-xs text-gray-500 mt-1">Email cannot be changed</p>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                        <input type="tel" name="phone" value="<?php echo htmlspecialchars($client['phone'] ?? ''); ?>" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#CFF752]">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                        <input type="text" name="address" value="<?php echo htmlspecialchars($client['address'] ?? ''); ?>" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#CFF752]">
                    </div>
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Preferences</label>
                    <textarea name="preferences" rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#CFF752]" placeholder="Any special preferences or notes..."><?php echo htmlspecialchars($client['preferences'] ?? ''); ?></textarea>
                </div>
                <button type="submit" name="update_profile" class="px-6 py-3 bg-[#CFF752] text-black rounded-lg hover:bg-[#b8e042] transition-colors font-semibold">
                    Update Profile
                </button>
            </form>
        </div>

        <!-- Change Password -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b">
                <h2 class="text-2xl font-bold" style="font-family: 'ivymode';">Change Password</h2>
            </div>
            <form method="POST" class="p-6">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Current Password</label>
                    <input type="password" name="current_password" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#CFF752]">
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                        <input type="password" name="new_password" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#CFF752]">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password</label>
                        <input type="password" name="confirm_password" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#CFF752]">
                    </div>
                </div>
                <button type="submit" name="change_password" class="px-6 py-3 bg-black text-white rounded-lg hover:bg-gray-800 transition-colors font-semibold">
                    Change Password
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

