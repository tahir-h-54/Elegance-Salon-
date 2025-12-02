<?php
include "../../Database/connect_to_db.php";

session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: ../AD_login.php");
  exit();
}
if ($_SESSION['role_id'] != '1') {
  echo "Access denied";
  exit;
}

// Page title for header component
$page_title = "Dashboard";

// Simple dashboard stats
$total_clients = 0;
$total_services = 0;
$total_staff = 0;
$total_appointments = 0;

$result = mysqli_query($conn, "SELECT COUNT(*) AS c FROM clients");
if ($result) {
  $row = mysqli_fetch_assoc($result);
  $total_clients = (int) ($row['c'] ?? 0);
}

$result = mysqli_query($conn, "SELECT COUNT(*) AS c FROM services");
if ($result) {
  $row = mysqli_fetch_assoc($result);
  $total_services = (int) ($row['c'] ?? 0);
}

$result = mysqli_query($conn, "SELECT COUNT(*) AS c FROM staff");
if ($result) {
  $row = mysqli_fetch_assoc($result);
  $total_staff = (int) ($row['c'] ?? 0);
}

$result = mysqli_query($conn, "SELECT COUNT(*) AS c FROM appointments");
if ($result) {
  $row = mysqli_fetch_assoc($result);
  $total_appointments = (int) ($row['c'] ?? 0);
}

// Get revenue data for chart (monthly revenue from invoices)
$revenue_data = [];
$current_year = date('Y');
for($i = 1; $i <= 12; $i++) {
    $month_start = "$current_year-$i-01";
    $month_end = date("Y-m-t", strtotime($month_start));
    $revenue_query = "SELECT COALESCE(SUM(total_amount), 0) as total 
                      FROM invoices 
                      WHERE DATE(generated_at) >= '$month_start' 
                      AND DATE(generated_at) <= '$month_end'";
    $revenue_result = mysqli_query($conn, $revenue_query);
    $revenue_row = mysqli_fetch_assoc($revenue_result);
    $revenue_data[] = (float)($revenue_row['total'] ?? 0);
}

// Get rating data for chart (weekly ratings from service_reviews)
$rating_data = [
    'low' => [0, 0, 0, 0], // 1-2 stars
    'medium' => [0, 0, 0, 0], // 3-4 stars
    'high' => [0, 0, 0, 0] // 5 stars
];

// Check if service_reviews table exists
$table_check = mysqli_query($conn, "SHOW TABLES LIKE 'service_reviews'");
if(mysqli_num_rows($table_check) > 0) {
    for($week = 0; $week < 4; $week++) {
        $week_start = date('Y-m-d', strtotime("-$week weeks Monday"));
        $week_end = date('Y-m-d', strtotime("$week_start +6 days"));
        
        // 1-2 stars
        $low_query = "SELECT COUNT(*) as count FROM service_reviews 
                     WHERE rating BETWEEN 1 AND 2 
                     AND DATE(created_at) >= '$week_start' 
                     AND DATE(created_at) <= '$week_end'";
        $low_result = mysqli_query($conn, $low_query);
        $low_row = mysqli_fetch_assoc($low_result);
        $rating_data['low'][3-$week] = (int)($low_row['count'] ?? 0);
        
        // 3-4 stars
        $med_query = "SELECT COUNT(*) as count FROM service_reviews 
                     WHERE rating BETWEEN 3 AND 4 
                     AND DATE(created_at) >= '$week_start' 
                     AND DATE(created_at) <= '$week_end'";
        $med_result = mysqli_query($conn, $med_query);
        $med_row = mysqli_fetch_assoc($med_result);
        $rating_data['medium'][3-$week] = (int)($med_row['count'] ?? 0);
        
        // 5 stars
        $high_query = "SELECT COUNT(*) as count FROM service_reviews 
                      WHERE rating = 5 
                      AND DATE(created_at) >= '$week_start' 
                      AND DATE(created_at) <= '$week_end'";
        $high_result = mysqli_query($conn, $high_query);
        $high_row = mysqli_fetch_assoc($high_result);
        $rating_data['high'][3-$week] = (int)($high_row['count'] ?? 0);
    }
}

// Get low stock items
$low_stock_query = "SELECT * FROM inventory 
                   WHERE quantity <= reorder_level 
                   ORDER BY quantity ASC
                   LIMIT 10";
$low_stock_result = mysqli_query($conn, $low_stock_query);
$low_stock_items = [];
while($row = mysqli_fetch_assoc($low_stock_result)) {
    $low_stock_items[] = $row;
}

// Get upcoming bookings
$bookings_query = "SELECT a.*, c.name as client_name, s.service_name as service_name, st.name as staff_name
                   FROM appointments a
                   LEFT JOIN clients c ON a.client_id = c.client_id
                   LEFT JOIN services s ON a.service_id = s.service_id
                   LEFT JOIN staff st ON a.stylist_id = st.staff_id
                   WHERE a.status = 'booked' 
                   AND a.appointment_date >= CURDATE()
                   ORDER BY a.appointment_date ASC, a.appointment_time ASC
                   LIMIT 10";
$bookings_result = mysqli_query($conn, $bookings_query);
$upcoming_bookings = [];
while($row = mysqli_fetch_assoc($bookings_result)) {
    $upcoming_bookings[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
</head>

<body class="bg-gray-50">
  <div class="flex min-h-screen">
    <?php include "../../Components/AD_DH_sidebar.php"; ?>
    <!-- Main Content -->
    <main class="main-content flex-1 lg:ml-[250px] w-full">
      <!-- Header -->
      <?php include "../../Components/DB_Header.php"; ?>
      <!-- Header
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
                <h1 class="text-3xl md:text-4xl font-bold text-[#1a1333] ml-12 md:ml-0">Dashboard</h1>
                <div class="flex items-center gap-3 w-full md:w-auto">
                    <div class="relative flex-1 md:flex-none">
                        <input type="text" placeholder="Search" class="w-full md:w-[300px] px-4 py-2 pr-10 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <div class="hidden md:flex w-10 h-10 bg-white rounded-lg items-center justify-center cursor-pointer shadow-sm">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                    </div>
                    <div class="hidden md:flex items-center gap-3 cursor-pointer">
                        <div class="w-10 h-10 bg-[#181818] rounded-full"></div>
                        <div>
                            <div class="text-sm font-semibold text-[#1a1333]">John Carlio</div>
                            <div class="text-xs text-gray-400">Admin</div>
                        </div>
                        <svg class="w-4 h-4 text-gray-600" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M7 10l5 5 5-5z"></path>
                        </svg>
                    </div>
                </div>
            </div> 
            <svg
              class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24">
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <input
              type="text"
              placeholder="Search Dashboard"
              class="pl-10 pr-4 py-2 border border-border rounded-lg text-sm w-64 focus:outline-none focus:border-primary" />
            <button class="absolute right-3 top-1/2 -translate-y-1/2">
              <svg
                class="w-4 h-4 text-gray-400"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
              </svg>
            </button>
            
            
            -->

      <div class="main p-3">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-5 mb-8">
          <div class="bg-white p-6 rounded-2xl hover:bg-gray-100 shadow-sm">
            <div class="flex justify-between items-center  mb-4">
              <span class="text-xs text-gray-400 font-medium">Total Clients</span>
              <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path>
              </svg>
            </div>
            <div class="flex items-center gap-3">
              <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
              </svg>
              <span class="text-4xl font-bold text-[#1a1333]"><?php echo $total_clients; ?></span>
            </div>
          </div>

          <div class="bg-white p-6 rounded-2xl shadow-sm">
            <div class="flex justify-between items-center mb-4">
              <span class="text-xs text-gray-400 font-medium">Total Services</span>
              <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path>
              </svg>
            </div>
            <div class="flex items-center gap-3">
              <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.121 14.121L19 19m-7-7l7-7m-7 7l-2.879 2.879M12 12L9.121 9.121m0 5.758a3 3 0 10-4.243 4.243 3 3 0 004.243-4.243zm0-5.758a3 3 0 10-4.243-4.243 3 3 0 004.243 4.243z"></path>
              </svg>
              <span class="text-4xl font-bold text-[#1a1333]"><?php echo $total_services; ?></span>
            </div>
          </div>

          <div class="bg-white p-6 rounded-2xl shadow-sm">
            <div class="flex justify-between items-center mb-4">
              <span class="text-xs text-gray-400 font-medium">Active Employees</span>
              <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path>
              </svg>
            </div>
            <div class="flex items-center gap-3">
              <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
              </svg>
              <span class="text-4xl font-bold text-[#1a1333]"><?php echo $total_staff; ?></span>
            </div>
          </div>

          <div class="bg-white p-6 rounded-2xl shadow-sm">
            <div class="flex justify-between items-center mb-4">
              <span class="text-xs text-gray-400 font-medium">Appointments</span>
              <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path>
              </svg>
            </div>
            <div class="flex items-center gap-3">
              <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
              </svg>
              <span class="text-4xl font-bold text-[#1a1333]"><?php echo $total_appointments; ?></span>
            </div>
          </div>
        </div>

        <!-- Charts -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-8">
          <div class="lg:col-span-2 bg-white p-6 rounded-2xl shadow-sm">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-5">
              <h3 class="text-lg font-semibold text-[#1a1333]">Chart of Annual Revenue</h3>
              <div class="flex items-center gap-2">
                <select class="px-3 py-1.5 border border-gray-200 rounded-lg text-sm bg-white cursor-pointer">
                  <option>Year</option>
                </select>
                <svg class="w-5 h-5 text-gray-400 cursor-pointer" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                </svg>
              </div>
            </div>
            <canvas id="revenueChart" class="w-full"></canvas>
            <script>
              var revenueData = <?php echo json_encode($revenue_data); ?>;
            </script>
          </div>

          <div class="bg-white p-6 rounded-2xl shadow-sm">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-5">
              <h3 class="text-lg font-semibold text-[#1a1333]">Chart of Rating</h3>
              <div class="flex items-center gap-2">
                <select class="px-3 py-1.5 border border-gray-200 rounded-lg text-sm bg-white cursor-pointer">
                  <option>Month</option>
                </select>
                <svg class="w-5 h-5 text-gray-400 cursor-pointer" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                </svg>
              </div>
            </div>
            <canvas id="ratingChart" class="w-full"></canvas>
            <script>
              var ratingData = <?php echo json_encode($rating_data); ?>;
            </script>
            <div class="flex flex-wrap justify-center gap-4 mt-5">
              <div class="flex items-center gap-2 text-xs">
                <div class="w-3 h-3 rounded-sm bg-[#808080]"></div>
                <span>1-2 Stars</span>
              </div>
              <div class="flex items-center gap-2 text-xs">
                <div class="w-3 h-3 rounded-sm bg-[#181818]"></div>
                <span>3-4 Stars</span>
              </div>
              <div class="flex items-center gap-2 text-xs">
                <div class="w-3 h-3 rounded-sm bg-[#CFF752]"></div>
                <span>5 Stars</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Low Stock Alert Section -->
        <div class="bg-white p-6 rounded-2xl shadow-sm mb-8">
          <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-5">
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
              </div>
              <div>
                <h3 class="text-lg font-semibold text-[#1a1333]">Low Stock Alert</h3>
                <p class="text-xs text-gray-400">Items running low on inventory</p>
              </div>
            </div>
            <a href="#" class="text-[#181818] text-sm font-medium">View All →</a>
          </div>

          <div class="overflow-x-auto">
            <table class="w-full">
              <thead class="border-b border-gray-200">
                <tr>
                  <th class="px-3 py-3 text-left text-xs text-gray-400 font-medium">Product Name</th>
                  <th class="px-3 py-3 text-left text-xs text-gray-400 font-medium">Quantity</th>
                  <th class="px-3 py-3 text-left text-xs text-gray-400 font-medium">Price</th>
                  <th class="px-3 py-3 text-center text-xs text-gray-400 font-medium">Status</th>
                </tr>
              </thead>
              <tbody>
                <?php if(empty($low_stock_items)): ?>
                <tr>
                  <td colspan="4" class="px-3 py-8 text-center text-sm text-gray-500">No low stock items</td>
                </tr>
                <?php else: ?>
                <?php foreach($low_stock_items as $item): 
                  $status_class = $item['quantity'] <= ($item['reorder_level'] * 0.5) ? 'critical' : 'low';
                  $status_bg = $status_class == 'critical' ? 'bg-red-100 text-red-600' : 'bg-orange-100 text-orange-600';
                  $quantity_color = $status_class == 'critical' ? 'text-red-500' : 'text-orange-500';
                ?>
                <tr class="border-b border-gray-50 hover:bg-gray-50">
                  <td class="px-3 py-4">
                    <div class="flex items-center gap-3">
                      <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                      </div>
                      <span class="text-sm font-medium text-[#1a1333]"><?php echo htmlspecialchars($item['item_name']); ?></span>
                    </div>
                  </td>
                  <td class="px-3 py-4">
                    <span class="text-sm <?php echo $quantity_color; ?> font-semibold"><?php echo $item['quantity']; ?> units</span>
                  </td>
                  <td class="px-3 py-4">
                    <span class="text-sm text-[#1a1333] font-medium">$<?php echo number_format($item['cost_price'], 2); ?></span>
                  </td>
                  <td class="px-3 py-4">
                    <div class="flex justify-center">
                      <span class="inline-flex items-center gap-1.5 px-3 py-1 <?php echo $status_bg; ?> rounded-full text-xs font-medium">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                          <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        <?php echo ucfirst($status_class); ?>
                      </span>
                    </div>
                  </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Bookings Section -->
        <div class="bg-white p-6 rounded-2xl shadow-sm">
          <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 mb-5">
            <div class="flex flex-wrap gap-2">
              <button class="px-5 py-2.5 bg-[#181818] text-white rounded-lg text-sm font-medium">Upcoming Bookings</button>
              <button class="px-5 py-2.5 bg-transparent text-gray-600 rounded-lg text-sm font-medium hover:bg-gray-50">All Bookings</button>
              <button class="px-5 py-2.5 bg-transparent text-gray-600 rounded-lg text-sm font-medium hover:bg-gray-50">Canceled Bookings</button>
            </div>
            <div class="flex items-center gap-3">
              <button class="px-5 py-2.5 border border-gray-200 bg-white rounded-lg text-sm font-medium flex items-center gap-2">
                Filter
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                </svg>
              </button>
              <a href="#" class="text-[#181818] text-sm font-medium">See all →</a>
            </div>
          </div>

          <div class="overflow-x-auto">
            <table class="w-full">
              <thead class="border-b border-gray-200">
                <tr>
                  <th class="px-3 py-3 text-left text-xs text-gray-400 font-medium">Start Time</th>
                  <th class="px-3 py-3 text-left text-xs text-gray-400 font-medium">Book Services</th>
                  <th class="px-3 py-3 text-left text-xs text-gray-400 font-medium">End Time Expected</th>
                  <th class="px-3 py-3 text-left text-xs text-gray-400 font-medium">Client</th>
                  <th class="px-3 py-3 text-left text-xs text-gray-400 font-medium">Employee</th>
                  <th class="px-3 py-3 text-left text-xs text-gray-400 font-medium">Action</th>
                </tr>
              </thead>
              <tbody>
                <?php if(empty($upcoming_bookings)): ?>
                <tr>
                  <td colspan="6" class="px-3 py-8 text-center text-sm text-gray-500">No upcoming bookings</td>
                </tr>
                <?php else: ?>
                <?php foreach($upcoming_bookings as $booking): 
                  $start_time = date('g:i A', strtotime($booking['appointment_time']));
                  $service_duration = 30; // Default duration, you can get from services table
                  $end_time = date('g:i A', strtotime($booking['appointment_time'] . " +$service_duration minutes"));
                ?>
                <tr class="border-b border-gray-50 hover:bg-gray-50">
                  <td class="px-3 py-4 text-sm text-[#1a1333]"><?php echo $start_time; ?></td>
                  <td class="px-3 py-4 text-sm text-[#1a1333]"><?php echo htmlspecialchars($booking['service_name'] ?? 'N/A'); ?></td>
                  <td class="px-3 py-4 text-sm text-[#1a1333]"><?php echo $end_time; ?></td>
                  <td class="px-3 py-4 text-sm text-[#1a1333]"><?php echo htmlspecialchars($booking['client_name'] ?? 'N/A'); ?></td>
                  <td class="px-3 py-4 text-sm text-[#1a1333]"><?php echo htmlspecialchars($booking['staff_name'] ?? 'Not Assigned'); ?></td>
                  <td class="px-3 py-4 text-sm text-gray-400">
                    <button class="text-lg">⋯</button>
                  </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </main>

  </div>

  <script>
    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    const revenueChartData = <?php echo json_encode($revenue_data); ?>;
    const revenueChart = new Chart(revenueCtx, {
      type: 'line',
      data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        datasets: [{
          label: 'Revenue',
          data: revenueChartData,
          borderColor: '#CFF752',
          backgroundColor: 'rgba(174, 239, 70, 0.1)',
          tension: 0.4,
          borderWidth: 3,
          fill: true,
          pointRadius: 0,
          pointHoverRadius: 6,
          pointHoverBackgroundColor: '#CFF752',
          pointHoverBorderColor: '#fff',
          pointHoverBorderWidth: 2
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: true,
        aspectRatio: 2.5,
        plugins: {
          legend: {
            display: false
          },
          tooltip: {
            backgroundColor: '#1a1333',
            titleColor: '#fff',
            bodyColor: '#fff',
            padding: 12,
            displayColors: false,
            callbacks: {
              label: function(context) {
                return 'Production: ' + context.parsed.y.toLocaleString();
              }
            }
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            max: 25000,
            ticks: {
              callback: function(value) {
                return (value / 1000) + 'K';
              },
              stepSize: 5000,
              color: '#a0a0b0',
              font: {
                size: 11
              }
            },
            grid: {
              color: '#f5f5f5',
              drawBorder: false
            }
          },
          x: {
            ticks: {
              color: '#a0a0b0',
              font: {
                size: 11
              }
            },
            grid: {
              display: false
            }
          }
        }
      }
    });

    // Rating Chart
    const ratingCtx = document.getElementById('ratingChart').getContext('2d');
    const ratingChartData = <?php echo json_encode($rating_data); ?>;
    const ratingChart = new Chart(ratingCtx, {
      type: 'line',
      data: {
        labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
        datasets: [{
          label: '1-2 Stars',
          data: ratingChartData.low || [0, 0, 0, 0],
          borderColor: '#808080',
          backgroundColor: 'rgba(128, 128, 128, 0.1)',
          tension: 0.4,
          borderWidth: 3,
          fill: false,
          pointRadius: 4,
          pointHoverRadius: 6
        }, {
          label: '3-4 Stars',
          data: ratingChartData.medium || [0, 0, 0, 0],
          borderColor: '#181818',
          backgroundColor: 'rgba(174, 239, 70, 0.1)',
          tension: 0.4,
          borderWidth: 3,
          fill: false,
          pointRadius: 4,
          pointHoverRadius: 6
        }, {
          label: '5 Stars',
          data: ratingChartData.high || [0, 0, 0, 0],
          borderColor: '#CFF752',
          backgroundColor: 'rgba(249, 115, 22, 0.1)',
          tension: 0.4,
          borderWidth: 3,
          fill: false,
          pointRadius: 4,
          pointHoverRadius: 6
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: true,
        aspectRatio: 1.2,
        plugins: {
          legend: {
            display: false
          },
          tooltip: {
            backgroundColor: '#1a1333',
            titleColor: '#fff',
            bodyColor: '#fff',
            padding: 12,
            displayColors: true
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            max: 50,
            ticks: {
              stepSize: 10,
              color: '#a0a0b0',
              font: {
                size: 11
              }
            },
            grid: {
              color: '#f5f5f5',
              drawBorder: false
            }
          },
          x: {
            ticks: {
              color: '#a0a0b0',
              font: {
                size: 11
              }
            },
            grid: {
              display: false
            }
          }
        }
      }
    });

    // Responsive chart handling
    window.addEventListener('resize', () => {
      revenueChart.resize();
      ratingChart.resize();
    });
  </script>
</body>

</html>