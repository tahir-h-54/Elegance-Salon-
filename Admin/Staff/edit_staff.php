<?php
session_start();
include '../../Database/connect_to_db.php';

if (!isset($_SESSION['role_id']) || $_SESSION['role_id'] != 1) {
    die("Access Denied! Only admin can edit staff.");
}

if (!isset($_GET['id']) || !intval($_GET['id'])) {
    die("Invalid staff ID");
}

$staff_id = intval($_GET['id']);
$page_title = "Edit Staff";
$errors = [];
$success = "";

$stmt = mysqli_prepare($conn, "SELECT * FROM staff WHERE staff_id = ?");
mysqli_stmt_bind_param($stmt, "i", $staff_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$staff = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$staff) {
    die("Staff member not found");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_staff'])) {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $role = trim($_POST['role'] ?? '');
    $commission_rate = $_POST['commission_rate'] !== '' ? floatval($_POST['commission_rate']) : null;

    if ($name === '') $errors[] = "Name is required";

    if (empty($errors)) {
        $stmt = mysqli_prepare($conn, "UPDATE staff SET name = ?, email = ?, phone = ?, role = ?, commission_rate = ? WHERE staff_id = ?");
        mysqli_stmt_bind_param($stmt, "ssssdi", $name, $email, $phone, $role, $commission_rate, $staff_id);
        if (mysqli_stmt_execute($stmt)) {
            $success = "Staff member updated successfully";
            $staff['name'] = $name;
            $staff['email'] = $email;
            $staff['phone'] = $phone;
            $staff['role'] = $role;
            $staff['commission_rate'] = $commission_rate;
        } else {
            $errors[] = "Failed to update staff member";
        }
        mysqli_stmt_close($stmt);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Staff</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <div class="flex min-h-screen">
        <?php include '../../Components/AD_DH_sidebar.php'; ?>
        <main class="main-content flex-1 lg:ml-[250px] w-full">
            <?php include '../../Components/DB_Header.php'; ?>

            <div class="grid place-items-center place-content-center mt-10 mb-10">
                <div class="w-full max-w-xl bg-white rounded-xl shadow-sm border border-gray-200 p-8">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-4">Edit Staff Member</h2>
                    <?php if (!empty($errors)): ?>
                        <div class="mb-4 p-3 rounded bg-red-100 text-sm text-red-700">
                            <?php foreach ($errors as $e): ?>
                                <div><?php echo htmlspecialchars($e); ?></div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    <?php if ($success): ?>
                        <div class="mb-4 p-3 rounded bg-green-100 text-sm text-green-700">
                            <?php echo htmlspecialchars($success); ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Full Name *</label>
                            <input type="text" name="name" required value="<?php echo htmlspecialchars($staff['name']); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-black">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" name="email" value="<?php echo htmlspecialchars($staff['email']); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-black">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                            <input type="text" name="phone" value="<?php echo htmlspecialchars($staff['phone']); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-black">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Role / Position</label>
                            <input type="text" name="role" value="<?php echo htmlspecialchars($staff['role']); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-black">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Commission Rate (%)</label>
                            <input type="number" step="0.1" min="0" name="commission_rate" value="<?php echo htmlspecialchars($staff['commission_rate']); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-black">
                        </div>
                        <div class="flex items-center justify-between pt-4">
                            <a href="list_staff.php" class="text-sm text-gray-600 hover:text-black">← Back to list</a>
                            <button type="submit" name="update_staff" class="px-4 py-2 bg-black text-white text-sm font-medium rounded-lg hover:bg-gray-800">
                                Update Staff
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</body>
</html>


