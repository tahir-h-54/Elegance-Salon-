<?php
session_start();
include '../../Database/connect_to_db.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['role_id']) || $_SESSION['role_id'] != 1) {
    header("Location: ../AD_login.php");
    exit();
}

$page_title = "Account Settings";
$errors = [];
$success = "";

$user_id = $_SESSION['user_id'];

// Fetch current user
$stmt = mysqli_prepare($conn, "SELECT name, email FROM users WHERE user_id = ?");
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$user) {
    die("User not found");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_profile'])) {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');

        if ($name === '') $errors[] = "Name is required";
        if ($email === '') $errors[] = "Email is required";

        if (empty($errors)) {
            $stmt = mysqli_prepare($conn, "UPDATE users SET name = ?, email = ? WHERE user_id = ?");
            mysqli_stmt_bind_param($stmt, "ssi", $name, $email, $user_id);
            if (mysqli_stmt_execute($stmt)) {
                $success = "Profile updated successfully";
                $_SESSION['name'] = $name;
                $user['name'] = $name;
                $user['email'] = $email;
            } else {
                $errors[] = "Failed to update profile";
            }
            mysqli_stmt_close($stmt);
        }
    } elseif (isset($_POST['update_password'])) {
        $current_password = $_POST['current_password'] ?? '';
        $new_password = $_POST['new_password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';

        if ($new_password === '' || strlen($new_password) < 6) {
            $errors[] = "New password must be at least 6 characters";
        }
        if ($new_password !== $confirm_password) {
            $errors[] = "New passwords do not match";
        }

        if (empty($errors)) {
            $stmt = mysqli_prepare($conn, "SELECT password FROM users WHERE user_id = ?");
            mysqli_stmt_bind_param($stmt, "i", $user_id);
            mysqli_stmt_execute($stmt);
            $res = mysqli_stmt_get_result($stmt);
            $row = mysqli_fetch_assoc($res);
            mysqli_stmt_close($stmt);

            if (!$row || !password_verify($current_password, $row['password'])) {
                $errors[] = "Current password is incorrect";
            } else {
                $hash = password_hash($new_password, PASSWORD_DEFAULT);
                $stmt = mysqli_prepare($conn, "UPDATE users SET password = ? WHERE user_id = ?");
                mysqli_stmt_bind_param($stmt, "si", $hash, $user_id);
                if (mysqli_stmt_execute($stmt)) {
                    $success = "Password updated successfully";
                } else {
                    $errors[] = "Failed to update password";
                }
                mysqli_stmt_close($stmt);
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Settings</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <div class="flex min-h-screen">
        <?php include '../../Components/AD_DH_sidebar.php'; ?>
        <main class="main-content flex-1 lg:ml-[250px] w-full">
            <?php include '../../Components/DB_Header.php'; ?>

            <div class="p-6 max-w-5xl mx-auto space-y-6">
                <?php if (!empty($errors)): ?>
                    <div class="p-3 rounded bg-red-100 text-sm text-red-700">
                        <?php foreach ($errors as $e): ?>
                            <div><?php echo htmlspecialchars($e); ?></div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                <?php if ($success): ?>
                    <div class="p-3 rounded bg-green-100 text-sm text-green-700">
                        <?php echo htmlspecialchars($success); ?>
                    </div>
                <?php endif; ?>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">Profile</h2>
                        <form method="POST" class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                                <input type="text" name="name" required value="<?php echo htmlspecialchars($user['name']); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-black">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                <input type="email" name="email" required value="<?php echo htmlspecialchars($user['email']); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-black">
                            </div>
                            <button type="submit" name="update_profile" class="px-4 py-2 bg-black text-white text-sm font-medium rounded-lg hover:bg-gray-800">
                                Save Changes
                            </button>
                        </form>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">Change Password</h2>
                        <form method="POST" class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
                                <input type="password" name="current_password" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-black">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                                <input type="password" name="new_password" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-black">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                                <input type="password" name="confirm_password" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-black">
                            </div>
                            <button type="submit" name="update_password" class="px-4 py-2 bg-black text-white text-sm font-medium rounded-lg hover:bg-gray-800">
                                Update Password
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>


