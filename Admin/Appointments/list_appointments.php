<?php
session_start();
include '../../Database/connect_to_db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
    header("Location: ../AD_login.php");
    exit();
}

$page_title = "Appointments";
$status_filter = isset($_GET['status']) ? mysqli_real_escape_string($conn, $_GET['status']) : 'all';

// Fetch appointments
$appointments_query = "
    SELECT a.*, c.name as client_name, c.email as client_email, c.phone as client_phone,
           s.service_name, s.price, st.name as stylist_name
    FROM appointments a
    JOIN clients c ON a.client_id = c.client_id
    JOIN services s ON a.service_id = s.service_id
    LEFT JOIN staff st ON a.stylist_id = st.staff_id
    " . ($status_filter != 'all' ? "WHERE a.status = '$status_filter'" : "") . "
    ORDER BY a.appointment_date DESC, a.appointment_time DESC
";
$appointments_result = mysqli_query($conn, $appointments_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointments - Elegance Salon</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <div class="flex min-h-screen">
        <?php include '../../Components/AD_DH_sidebar.php'; ?>
        <main class="main-content flex-1 lg:ml-[250px] w-full">
            <?php include '../../Components/DB_Header.php'; ?>
            
            <div class="main p-6">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-3xl font-bold text-[#1a1333]">Appointments</h1>
                    <a href="calendar.php" class="px-6 py-2 bg-[#d946ef] text-white rounded-lg hover:bg-purple-700 font-semibold">
                        Calendar View
                    </a>
                </div>

                <div class="flex gap-2 mb-6">
                    <a href="?status=all" class="px-4 py-2 <?php echo $status_filter == 'all' ? 'bg-[#d946ef] text-white' : 'bg-white text-gray-700'; ?> rounded-lg">
                        All
                    </a>
                    <a href="?status=booked" class="px-4 py-2 <?php echo $status_filter == 'booked' ? 'bg-[#d946ef] text-white' : 'bg-white text-gray-700'; ?> rounded-lg">
                        Booked
                    </a>
                    <a href="?status=completed" class="px-4 py-2 <?php echo $status_filter == 'completed' ? 'bg-[#d946ef] text-white' : 'bg-white text-gray-700'; ?> rounded-lg">
                        Completed
                    </a>
                    <a href="?status=cancelled" class="px-4 py-2 <?php echo $status_filter == 'cancelled' ? 'bg-[#d946ef] text-white' : 'bg-white text-gray-700'; ?> rounded-lg">
                        Cancelled
                    </a>
                </div>

                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date & Time</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Client</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Service</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stylist</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <?php if(mysqli_num_rows($appointments_result) > 0): ?>
                                    <?php while($apt = mysqli_fetch_assoc($appointments_result)): ?>
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="font-medium"><?php echo date('M d, Y', strtotime($apt['appointment_date'])); ?></div>
                                                <div class="text-sm text-gray-500"><?php echo date('g:i A', strtotime($apt['appointment_time'])); ?></div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="font-medium"><?php echo htmlspecialchars($apt['client_name']); ?></div>
                                                <div class="text-sm text-gray-500"><?php echo htmlspecialchars($apt['client_email']); ?></div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="font-medium"><?php echo htmlspecialchars($apt['service_name']); ?></div>
                                                <div class="text-sm text-gray-500">$<?php echo number_format($apt['price'], 2); ?></div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($apt['stylist_name'] ?? 'N/A'); ?></td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 py-1 rounded-full text-xs font-semibold 
                                                    <?php 
                                                    echo $apt['status'] == 'completed' ? 'bg-green-100 text-green-800' : 
                                                        ($apt['status'] == 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800'); 
                                                    ?>">
                                                    <?php echo ucfirst($apt['status']); ?>
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <a href="edit_appointment.php?id=<?php echo $apt['appointment_id']; ?>" class="text-blue-600 hover:text-blue-800 mr-3">Edit</a>
                                                <a href="view_appointment.php?id=<?php echo $apt['appointment_id']; ?>" class="text-gray-600 hover:text-gray-800">View</a>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">No appointments found</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>

