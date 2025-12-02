<?php
session_start();
include '../../Database/connect_to_db.php';

if (!isset($_SESSION['role_id']) || $_SESSION['role_id'] != 1) {
    die("Access Denied! Only admin can access this page.");
}

$page_title = "Client List";

$clients_query = "
    SELECT c.client_id, c.name, c.email, c.phone, c.created_at
    FROM clients c
    ORDER BY c.created_at DESC
";
$clients_result = mysqli_query($conn, $clients_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client List</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <div class="flex min-h-screen">
        <?php include '../../Components/AD_DH_sidebar.php'; ?>
        <main class="main-content flex-1 lg:ml-[250px] w-full">
            <?php include '../../Components/DB_Header.php'; ?>

            <div class="p-6">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                    <div>
                        <h2 class="text-2xl font-semibold text-gray-800">Clients</h2>
                        <p class="text-sm text-gray-500 mt-1">All registered and booking clients</p>
                    </div>
                    <a href="add_client.php" class="inline-flex items-center px-4 py-2 bg-black text-white text-sm font-medium rounded-lg hover:bg-gray-800">
                        + Add Client
                    </a>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">ID</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Name</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Email</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Phone</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Created</th>
                                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php if ($clients_result && mysqli_num_rows($clients_result) > 0): ?>
                                    <?php while ($client = mysqli_fetch_assoc($clients_result)): ?>
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-3 text-sm text-gray-700">#<?php echo $client['client_id']; ?></td>
                                            <td class="px-4 py-3 text-sm text-gray-900 font-medium">
                                                <?php echo htmlspecialchars($client['name']); ?>
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-700">
                                                <?php echo htmlspecialchars($client['email']); ?>
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-700">
                                                <?php echo htmlspecialchars($client['phone']); ?>
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-500">
                                                <?php echo $client['created_at'] ? date('M d, Y', strtotime($client['created_at'])) : '-'; ?>
                                            </td>
                                            <td class="px-4 py-3 text-sm text-center space-x-2">
                                                <a href="edit_client.php?id=<?php echo $client['client_id']; ?>" class="text-blue-600 hover:text-blue-800">Edit</a>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="px-4 py-6 text-center text-sm text-gray-500">
                                            No clients found.
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


