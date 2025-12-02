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
    $discount_id = intval($_GET['delete']);
    $delete_query = "DELETE FROM discounts WHERE discount_id = $discount_id";
    if(mysqli_query($conn, $delete_query)) {
        $success = "Discount deleted successfully.";
    } else {
        $error = "Failed to delete discount.";
    }
}

// Fetch all discounts
$discounts_query = "SELECT * FROM discounts ORDER BY created_at DESC";
$discounts_result = mysqli_query($conn, $discounts_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Discounts - Elegance Salon</title>
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
                    <h1 class="text-3xl font-bold text-[#1a1333]">Manage Discounts</h1>
                    <a href="add_discount.php" class="px-6 py-2 bg-[#d946ef] text-white rounded-lg hover:bg-purple-700 font-semibold">
                        + Add Discount
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
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Code</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Discount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Valid Period</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Usage</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php if(mysqli_num_rows($discounts_result) > 0): ?>
                                <?php while($discount = mysqli_fetch_assoc($discounts_result)): ?>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap font-mono font-bold"><?php echo htmlspecialchars($discount['code']); ?></td>
                                        <td class="px-6 py-4">
                                            <div class="font-medium"><?php echo htmlspecialchars($discount['name']); ?></div>
                                            <div class="text-sm text-gray-500"><?php echo htmlspecialchars(substr($discount['description'], 0, 50)); ?>...</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <?php if($discount['discount_type'] == 'percentage'): ?>
                                                <?php echo $discount['discount_value']; ?>%
                                            <?php else: ?>
                                                $<?php echo number_format($discount['discount_value'], 2); ?>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <?php echo date('M d, Y', strtotime($discount['start_date'])); ?><br>
                                            <?php echo $discount['end_date'] ? date('M d, Y', strtotime($discount['end_date'])) : 'No expiry'; ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <?php echo $discount['used_count']; ?> / <?php echo $discount['usage_limit'] ? $discount['usage_limit'] : '∞'; ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 py-1 rounded-full text-xs font-semibold 
                                                <?php 
                                                echo $discount['status'] == 'active' ? 'bg-green-100 text-green-800' : 
                                                    ($discount['status'] == 'expired' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800'); 
                                                ?>">
                                                <?php echo ucfirst($discount['status']); ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <a href="edit_discount.php?id=<?php echo $discount['discount_id']; ?>" class="text-blue-600 hover:text-blue-800 mr-3">Edit</a>
                                            <a href="?delete=<?php echo $discount['discount_id']; ?>" onclick="return confirm('Are you sure?')" class="text-red-600 hover:text-red-800">Delete</a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">No discounts found. <a href="add_discount.php" class="text-[#d946ef] hover:underline">Add one now</a></td>
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

