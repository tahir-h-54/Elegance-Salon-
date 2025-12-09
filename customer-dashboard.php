<?php
session_start();
include 'Database/connect_to_db.php';

if(!isset($_SESSION['client_logged_in']) || !$_SESSION['client_logged_in']) {
    header("Location: customer-login.php");
    exit;
}

$client_id = $_SESSION['client_id'];

// Fetch upcoming appointments
$upcoming_query = "
    SELECT a.*, s.service_name, s.price, st.name as stylist_name
    FROM appointments a
    JOIN services s ON a.service_id = s.service_id
    LEFT JOIN staff st ON a.stylist_id = st.staff_id
    WHERE a.client_id = $client_id AND a.status = 'booked' AND a.appointment_date >= CURDATE()
    ORDER BY a.appointment_date, a.appointment_time
    LIMIT 5
";
$upcoming_result = mysqli_query($conn, $upcoming_query);

// Fetch recent appointments
$recent_query = "
    SELECT a.*, s.service_name, s.price, st.name as stylist_name
    FROM appointments a
    JOIN services s ON a.service_id = s.service_id
    LEFT JOIN staff st ON a.stylist_id = st.staff_id
    WHERE a.client_id = $client_id
    ORDER BY a.appointment_date DESC, a.appointment_time DESC
    LIMIT 10
";
$recent_result = mysqli_query($conn, $recent_query);

// Fetch invoices
$invoices_query = "
    SELECT i.*, a.appointment_date, s.service_name
    FROM invoices i
    JOIN appointments a ON i.appointment_id = a.appointment_id
    JOIN services s ON a.service_id = s.service_id
    WHERE a.client_id = $client_id
    ORDER BY i.generated_at DESC
    LIMIT 5
";
$invoices_result = mysqli_query($conn, $invoices_query);

// Count stats
$total_appointments = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM appointments WHERE client_id = $client_id"))['count'];
$upcoming_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM appointments WHERE client_id = $client_id AND status = 'booked' AND appointment_date >= CURDATE()"))['count'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <title>My Dashboard - Elegance Salon</title>
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
                    <a href="book-appointment.php" class="text-gray-700 hover:text-[#CFF752]">Book Appointment</a>
                    <a href="customer-profile.php" class="text-gray-700 hover:text-[#CFF752]">Profile</a>
                    <a href="customer-logout.php" class="text-gray-700 hover:text-red-600">Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-8">
            <h1 class="text-4xl font-bold" style="font-family: 'ivymode';">Welcome, <?php echo htmlspecialchars($_SESSION['client_name']); ?>!</h1>
            <p class="text-gray-600 mt-2">Manage your appointments and account</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm">Total Appointments</p>
                        <p class="text-3xl font-bold mt-2"><?php echo $total_appointments; ?></p>
                    </div>
                    <div class="w-12 h-12 bg-[#CFF752] rounded-full flex items-center justify-center">
                        <i class="far fa-calendar-check text-2xl text-black"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm">Upcoming</p>
                        <p class="text-3xl font-bold mt-2"><?php echo $upcoming_count; ?></p>
                    </div>
                    <div class="w-12 h-12 bg-[#CFF752] rounded-full flex items-center justify-center">
                        <i class="far fa-clock text-2xl text-black"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm">Quick Actions</p>
                        <a href="book-appointment.php" class="text-[#CFF752] font-semibold mt-2 inline-block">Book Now →</a>
                    </div>
                    <div class="w-12 h-12 bg-[#CFF752] rounded-full flex items-center justify-center">
                        <i class="fas fa-plus text-2xl text-black"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Upcoming Appointments -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b">
                    <h2 class="text-2xl font-bold" style="font-family: 'ivymode';">Upcoming Appointments</h2>
                </div>
                <div class="p-6">
                    <?php if(mysqli_num_rows($upcoming_result) > 0): ?>
                        <div class="space-y-4">
                            <?php while($appointment = mysqli_fetch_assoc($upcoming_result)): ?>
                                <div class="border-l-4 border-[#CFF752] pl-4 py-2">
                                    <h3 class="font-semibold"><?php echo htmlspecialchars($appointment['service_name']); ?></h3>
                                    <p class="text-sm text-gray-600">
                                        <i class="far fa-calendar"></i> <?php echo date('M d, Y', strtotime($appointment['appointment_date'])); ?>
                                        <i class="far fa-clock ml-4"></i> <?php echo date('g:i A', strtotime($appointment['appointment_time'])); ?>
                                    </p>
                                    <p class="text-sm text-gray-600">Stylist: <?php echo htmlspecialchars($appointment['stylist_name']); ?></p>
                                    <p class="text-sm font-semibold text-[#CFF752]">$<?php echo number_format($appointment['price'], 2); ?></p>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-gray-500">No upcoming appointments. <a href="book-appointment.php" class="text-[#CFF752] font-semibold">Book one now!</a></p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Recent Invoices -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b">
                    <h2 class="text-2xl font-bold" style="font-family: 'ivymode';">Recent Invoices</h2>
                </div>
                <div class="p-6">
                    <?php if(mysqli_num_rows($invoices_result) > 0): ?>
                        <div class="space-y-4">
                            <?php while($invoice = mysqli_fetch_assoc($invoices_result)): ?>
                                <div class="flex items-center justify-between border-b pb-4">
                                    <div>
                                        <h3 class="font-semibold"><?php echo htmlspecialchars($invoice['service_name']); ?></h3>
                                        <p class="text-sm text-gray-600"><?php echo date('M d, Y', strtotime($invoice['generated_at'])); ?></p>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-bold">$<?php echo number_format($invoice['total_amount'], 2); ?></p>
                                        <a href="invoice.php?id=<?php echo $invoice['invoice_id']; ?>" class="text-sm text-[#CFF752] hover:underline">View</a>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-gray-500">No invoices yet.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Appointment History -->
        <div class="bg-white rounded-lg shadow mt-8">
            <div class="p-6 border-b">
                <h2 class="text-2xl font-bold" style="font-family: 'ivymode';">Appointment History</h2>
            </div>
            <div class="p-6">
                <?php if(mysqli_num_rows($recent_result) > 0): ?>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Service</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Date & Time</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Stylist</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                mysqli_data_seek($recent_result, 0);
                                while($appointment = mysqli_fetch_assoc($recent_result)): 
                                ?>
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="px-4 py-3"><?php echo htmlspecialchars($appointment['service_name']); ?></td>
                                        <td class="px-4 py-3">
                                            <?php echo date('M d, Y', strtotime($appointment['appointment_date'])); ?><br>
                                            <span class="text-sm text-gray-600"><?php echo date('g:i A', strtotime($appointment['appointment_time'])); ?></span>
                                        </td>
                                        <td class="px-4 py-3"><?php echo htmlspecialchars($appointment['stylist_name']); ?></td>
                                        <td class="px-4 py-3">
                                            <span class="px-2 py-1 rounded-full text-xs font-semibold 
                                                <?php 
                                                echo $appointment['status'] == 'completed' ? 'bg-green-100 text-green-800' : 
                                                    ($appointment['status'] == 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800'); 
                                                ?>">
                                                <?php echo ucfirst($appointment['status']); ?>
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 font-semibold">$<?php echo number_format($appointment['price'], 2); ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-gray-500">No appointment history.</p>
                <?php endif; ?>
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

