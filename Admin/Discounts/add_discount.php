<?php
session_start();
include '../../Database/connect_to_db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
    header("Location: ../AD_login.php");
    exit();
}

$success = "";
$error = "";

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $code = strtoupper(mysqli_real_escape_string($conn, $_POST['code']));
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $discount_type = mysqli_real_escape_string($conn, $_POST['discount_type']);
    $discount_value = floatval($_POST['discount_value']);
    $min_purchase = floatval($_POST['min_purchase']);
    $max_discount = !empty($_POST['max_discount']) ? floatval($_POST['max_discount']) : NULL;
    $start_date = mysqli_real_escape_string($conn, $_POST['start_date']);
    $end_date = !empty($_POST['end_date']) ? mysqli_real_escape_string($conn, $_POST['end_date']) : NULL;
    $usage_limit = !empty($_POST['usage_limit']) ? intval($_POST['usage_limit']) : NULL;
    
    // Check if code already exists
    $check_code = "SELECT discount_id FROM discounts WHERE code = '$code'";
    $code_result = mysqli_query($conn, $check_code);
    
    if(mysqli_num_rows($code_result) > 0) {
        $error = "Discount code already exists. Please use a different code.";
    } else {
        $insert_query = "INSERT INTO discounts (code, name, description, discount_type, discount_value, min_purchase, max_discount, start_date, end_date, usage_limit) 
                        VALUES ('$code', '$name', '$description', '$discount_type', $discount_value, $min_purchase, " . ($max_discount ? $max_discount : 'NULL') . ", '$start_date', " . ($end_date ? "'$end_date'" : 'NULL') . ", " . ($usage_limit ? $usage_limit : 'NULL') . ")";
        
        if(mysqli_query($conn, $insert_query)) {
            $success = "Discount created successfully!";
        } else {
            $error = "Failed to create discount.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Discount - Elegance Salon</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <div class="flex min-h-screen">
        <?php include '../../Components/AD_DH_sidebar.php'; ?>
        <main class="main-content flex-1 lg:ml-[250px] w-full">
            <?php include '../../Components/DB_Header.php'; ?>
            
            <div class="main p-6">
                <div class="mb-6">
                    <a href="list_discounts.php" class="text-gray-600 hover:text-gray-800">← Back to Discounts</a>
                    <h1 class="text-3xl font-bold text-[#1a1333] mt-4">Add New Discount</h1>
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

                <div class="bg-white rounded-lg shadow p-6 max-w-2xl">
                    <form method="POST">
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Discount Code *</label>
                                <input type="text" name="code" required pattern="[A-Z0-9]+" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#d946ef]" placeholder="SAVE20">
                                <p class="text-xs text-gray-500 mt-1">Uppercase letters and numbers only</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Discount Type *</label>
                                <select name="discount_type" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#d946ef]">
                                    <option value="percentage">Percentage</option>
                                    <option value="fixed">Fixed Amount</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Name *</label>
                            <input type="text" name="name" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#d946ef]">
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <textarea name="description" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#d946ef]"></textarea>
                        </div>
                        <div class="grid grid-cols-3 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Discount Value *</label>
                                <input type="number" name="discount_value" required step="0.01" min="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#d946ef]">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Min Purchase</label>
                                <input type="number" name="min_purchase" step="0.01" min="0" value="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#d946ef]">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Max Discount</label>
                                <input type="number" name="max_discount" step="0.01" min="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#d946ef]">
                                <p class="text-xs text-gray-500 mt-1">For percentage only</p>
                            </div>
                        </div>
                        <div class="grid grid-cols-3 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Start Date *</label>
                                <input type="date" name="start_date" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#d946ef]">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                                <input type="date" name="end_date" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#d946ef]">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Usage Limit</label>
                                <input type="number" name="usage_limit" min="1" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#d946ef]">
                                <p class="text-xs text-gray-500 mt-1">Leave empty for unlimited</p>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <button type="submit" class="px-6 py-2 bg-[#d946ef] text-white rounded-lg hover:bg-purple-700 font-semibold">
                                Create Discount
                            </button>
                            <a href="list_discounts.php" class="px-6 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 font-semibold">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</body>
</html>

