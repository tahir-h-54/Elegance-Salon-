<?php
session_start();
include '../../Database/connect_to_db.php';

if (!isset($_SESSION['role_id']) || $_SESSION['role_id'] != 1) {
    die("Access Denied! Only admin can access invoices.");
}

$page_title = "Invoice List";

$invoice_query = "
    SELECT i.invoice_id, i.total_amount, i.generated_at,
           c.name AS client_name,
           s.service_name
    FROM invoices i
    LEFT JOIN appointments a ON i.appointment_id = a.appointment_id
    LEFT JOIN clients c ON a.client_id = c.client_id
    LEFT JOIN services s ON a.service_id = s.service_id
    ORDER BY i.generated_at DESC
    LIMIT 100
";
$invoice_result = mysqli_query($conn, $invoice_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice List</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <div class="flex min-h-screen">
        <?php include '../../Components/AD_DH_sidebar.php'; ?>
        <main class="main-content flex-1 lg:ml-[250px] w-full">
            <?php include '../../Components/DB_Header.php'; ?>

            <div class="p-6">
                <h2 class="text-2xl font-semibold text-gray-800 mb-2">Invoices</h2>
                <p class="text-sm text-gray-500 mb-6">List of generated invoices for appointments.</p>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Invoice</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Client</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Service</th>
                                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Total</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php if ($invoice_result && mysqli_num_rows($invoice_result) > 0): ?>
                                    <?php while ($row = mysqli_fetch_assoc($invoice_result)): ?>
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-3 text-sm text-gray-700">INV-<?php echo $row['invoice_id']; ?></td>
                                            <td class="px-4 py-3 text-sm text-gray-700">
                                                <?php echo date('M d, Y', strtotime($row['generated_at'])); ?>
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-700">
                                                <?php echo htmlspecialchars($row['client_name'] ?? 'Unknown'); ?>
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-700">
                                                <?php echo htmlspecialchars($row['service_name'] ?? 'Service'); ?>
                                            </td>
                                            <td class="px-4 py-3 text-sm text-right text-gray-900">
                                                $<?php echo number_format($row['total_amount'], 2); ?>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="px-4 py-6 text-center text-sm text-gray-500">
                                            No invoices found.
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


