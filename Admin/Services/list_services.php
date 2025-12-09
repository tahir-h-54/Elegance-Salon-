<?php
session_start();
include '../../Database/connect_to_db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
    header("Location: ../AD_login.php");
    exit();
}

$success = "";
$error = "";

// Handle delete
if(isset($_GET['delete'])) {
    $service_id = intval($_GET['delete']);
    $delete_query = "DELETE FROM services WHERE service_id = $service_id";
    if(mysqli_query($conn, $delete_query)) {
        $success = "Service deleted successfully.";
    } else {
        $error = "Failed to delete service.";
    }
}

// Fetch all services
$services_query = "SELECT * FROM services ORDER BY service_id DESC";
$services_result = mysqli_query($conn, $services_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Services - Elegance Salon</title>
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
                    <h1 class="text-3xl font-bold text-[#1a1333]">Manage Services</h1>
                    <a href="add_service.php" class="px-6 py-2 bg-[#CFF752] text-white rounded-lg hover:bg-[#c8f73c] font-semibold">
                        + Add Service
                    </a>
                </div>

                <?php if($success): ?>
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        <?php echo $success; ?>
                    </div>
                <?php endif; ?>

                <?php if($error): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>

                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Service Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Duration</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php if(mysqli_num_rows($services_result) > 0): ?>
                                <?php while($service = mysqli_fetch_assoc($services_result)): ?>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap"><?php echo $service['service_id']; ?></td>
                                        <td class="px-6 py-4">
                                            <div class="font-medium text-gray-900"><?php echo htmlspecialchars($service['service_name']); ?></div>
                                            <div class="text-sm text-gray-500"><?php echo htmlspecialchars(substr($service['description'], 0, 50)); ?>...</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap"><?php echo $service['duration']; ?> min</td>
                                        <td class="px-6 py-4 whitespace-nowrap font-semibold">$<?php echo number_format($service['price'], 2); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <a href="edit_service.php?id=<?php echo $service['service_id']; ?>" class="text-blue-600 hover:text-blue-800 mr-3">Edit</a>
                                            <a href="?delete=<?php echo $service['service_id']; ?>" onclick="return confirm('Are you sure?')" class="text-red-600 hover:text-red-800">Delete</a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">No services found. <a href="add_service.php" class="text-[#d946ef] hover:underline">Add one now</a></td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</body>
</html>

