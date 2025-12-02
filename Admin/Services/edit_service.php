<?php
session_start();
include '../../Database/connect_to_db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
    header("Location: ../AD_login.php");
    exit();
}

$service_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$success = "";
$error = "";

// Fetch service
$service_query = "SELECT * FROM services WHERE service_id = $service_id";
$service_result = mysqli_query($conn, $service_query);
$service = mysqli_fetch_assoc($service_result);

if(!$service) {
    header("Location: list_services.php");
    exit();
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $service_name = mysqli_real_escape_string($conn, $_POST['service_name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $duration = intval($_POST['duration']);
    $price = floatval($_POST['price']);
    
    $update_query = "UPDATE services SET service_name = '$service_name', description = '$description', duration = $duration, price = $price WHERE service_id = $service_id";
    
    if(mysqli_query($conn, $update_query)) {
        $success = "Service updated successfully!";
        // Refresh service data
        $service_result = mysqli_query($conn, $service_query);
        $service = mysqli_fetch_assoc($service_result);
    } else {
        $error = "Failed to update service.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Service - Elegance Salon</title>
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
                    <a href="list_services.php" class="text-gray-600 hover:text-gray-800">← Back to Services</a>
                    <h1 class="text-3xl font-bold text-[#1a1333] mt-4">Edit Service</h1>
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
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Service Name *</label>
                            <input type="text" name="service_name" value="<?php echo htmlspecialchars($service['service_name']); ?>" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#d946ef]">
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                            <textarea name="description" required rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#d946ef]"><?php echo htmlspecialchars($service['description']); ?></textarea>
                        </div>
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Duration (minutes) *</label>
                                <input type="number" name="duration" value="<?php echo $service['duration']; ?>" required min="1" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#d946ef]">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Price ($) *</label>
                                <input type="number" name="price" value="<?php echo $service['price']; ?>" required step="0.01" min="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#d946ef]">
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <button type="submit" class="px-6 py-2 bg-[#d946ef] text-white rounded-lg hover:bg-purple-700 font-semibold">
                                Update Service
                            </button>
                            <a href="list_services.php" class="px-6 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 font-semibold">
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

