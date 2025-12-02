<?php
session_start();
include '../../Database/connect_to_db.php';

if (!isset($_SESSION['role_id']) || $_SESSION['role_id'] != 1) {
    die("Access Denied! Only admin can access reports.");
}

$page_title = "Appointments Reports";

$report_query = "
    SELECT a.appointment_id, a.appointment_date, a.appointment_time, a.status,
           c.name AS client_name,
           s.service_name
    FROM appointments a
    LEFT JOIN clients c ON a.client_id = c.client_id
    LEFT JOIN services s ON a.service_id = s.service_id
    ORDER BY a.appointment_date DESC, a.appointment_time DESC
    LIMIT 100
";
$report_result = mysqli_query($conn, $report_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointments Reports</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <div class="flex min-h-screen">
        <?php include '../../Components/AD_DH_sidebar.php'; ?>
        <main class="main-content flex-1 lg:ml-[250px] w-full">
            <?php include '../../Components/DB_Header.php'; ?>

            <div class="p-6">
                <h2 class="text-2xl font-semibold text-gray-800 mb-2">Appointments Overview</h2>
                <p class="text-sm text-gray-500 mb-6">Last 100 appointments including client, service, and status.</p>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">ID</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Time</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Client</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Service</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php if ($report_result && mysqli_num_rows($report_result) > 0): ?>
                                    <?php while ($row = mysqli_fetch_assoc($report_result)): ?>
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-3 text-sm text-gray-700">#<?php echo $row['appointment_id']; ?></td>
                                            <td class="px-4 py-3 text-sm text-gray-700">
                                                <?php echo date('M d, Y', strtotime($row['appointment_date'])); ?>
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-700">
                                                <?php echo date('g:i A', strtotime($row['appointment_time'])); ?>
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-700">
                                                <?php echo htmlspecialchars($row['client_name'] ?? 'Unknown'); ?>
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-700">
                                                <?php echo htmlspecialchars($row['service_name'] ?? 'Unknown'); ?>
                                            </td>
                                            <td class="px-4 py-3 text-sm">
                                                <span class="px-2 py-1 rounded-full text-xs font-semibold
                                                    <?php
                                                        echo $row['status'] === 'completed' ? 'bg-green-100 text-green-800' :
                                                            ($row['status'] === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800');
                                                    ?>">
                                                    <?php echo ucfirst($row['status']); ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="px-4 py-6 text-center text-sm text-gray-500">
                                            No appointments found.
                                        </td>
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


