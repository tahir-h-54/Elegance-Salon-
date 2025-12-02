<?php
session_start();

include '../../Database/connect_to_db.php';

// Allow only admin
if (!isset($_SESSION['role_id']) || $_SESSION['role_id'] != 1) {
    die("Access Denied! Only admin can access this page.");
}

// Page title for header
$page_title = "View Users";

$success = "";
$error = "";

// DELETE USER ACTION
if (isset($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);

    // Prevent admin from deleting themselves
    if ($delete_id == $_SESSION['user_id']) {
        $error = "You cannot delete your own account!";
    } else {
        $delete_query = "DELETE FROM users WHERE user_id = ?";
        $stmt = mysqli_prepare($conn, $delete_query);
        mysqli_stmt_bind_param($stmt, "i", $delete_id);

        if (mysqli_stmt_execute($stmt)) {
            $success = "User deleted successfully.";
        } else {
            $error = "Failed to delete user.";
        }
        mysqli_stmt_close($stmt);
    }
}

// FETCH ALL USERS
$query = "
    SELECT u.user_id, u.name, u.email, u.status, r.role_name
    FROM users u
    LEFT JOIN roles r ON u.role_id = r.role_id
    ORDER BY u.user_id DESC
";

$users = mysqli_query($conn, $query);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>

<body>
    <div class="flex min-h-screen">
        <?php include '../../Components/AD_DH_sidebar.php'; ?>
        <!-- Main Content -->
        <main class="main-content flex-1 lg:ml-[250px] w-full">
            <!-- Header -->
            <?php include "../../Components/DB_Header.php"; ?>
            <!-- View Users -->
            <div class="form grid place-items-center place-content-center mt-40 mb-10">

                <!-- Heading -->
                <div class="text-center mb-8">
                    <h2 class="text-3xl font-semibold text-gray-800 tracking-wide">All Users</h2>
                    <div class="w-20 h-1 bg-blue-600 mx-auto mt-2 rounded-full"></div>
                </div>

                <!-- Success Alert -->
                <?php if (!empty($success)): ?>
                    <div class="max-w-4xl mx-auto mb-4 p-4 bg-green-100 text-green-700 border border-green-300 rounded">
                        <?= htmlspecialchars($success) ?>
                    </div>
                <?php endif; ?>

                <!-- Error Alert -->
                <?php if (!empty($error)): ?>
                    <div class="max-w-4xl mx-auto mb-4 p-4 bg-red-100 text-red-700 border border-red-300 rounded">
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>

                <!-- Responsive Table Container -->
                <div class="w-full max-w-7xl mx-auto bg-white shadow-lg rounded-lg overflow-hidden">

                    <!-- Table Wrapper for Mobile Scroll -->
                    <div class="overflow-x-auto">

                        <table class="w-full text-left border-collapse">
                            <thead class="bg-gray-800 text-white">
                                <tr>
                                    <th class="py-3 px-4 text-sm font-semibold">ID</th>
                                    <th class="py-3 px-4 text-sm font-semibold">Name</th>
                                    <th class="py-3 px-4 text-sm font-semibold">Email</th>
                                    <th class="py-3 px-4 text-sm font-semibold">Role</th>
                                    <th class="py-3 px-4 text-sm font-semibold">Status</th>
                                    <th class="py-3 px-4 text-sm font-semibold text-center">Actions</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-200">

                                <?php while ($user = mysqli_fetch_assoc($users)): ?>
                                    <tr class="hover:bg-gray-100 transition">
                                        <td class="py-3 px-4"><?= $user['user_id']; ?></td>
                                        <td class="py-3 px-4"><?= htmlspecialchars($user['name']); ?></td>
                                        <td class="py-3 px-4"><?= htmlspecialchars($user['email']); ?></td>
                                        <td class="py-3 px-4"><?= htmlspecialchars($user['role_name']); ?></td>

                                        <td class="py-3 px-4">
                                            <?php if ($user['status'] === "active"): ?>
                                                <span class="px-2 py-1 text-xs bg-green-100 text-green-700 rounded">Active</span>
                                            <?php else: ?>
                                                <span class="px-2 py-1 text-xs bg-red-100 text-red-700 rounded">Inactive</span>
                                            <?php endif; ?>
                                        </td>

                                        <td class="py-3 px-4 flex gap-2 justify-center">
                                            <a href="edit_user.php?id=<?= $user['user_id'] ?>"
                                                class="px-3 py-1 bg-blue-600 text-white text-sm rounded hover:bg-blue-700">
                                                Edit
                                            </a>

                                            <a href="view_user.php?delete=<?= $user['user_id'] ?>"
                                                onclick="return confirm('Are you sure you want to delete this user?');"
                                                class="px-3 py-1 bg-red-600 text-white text-sm rounded hover:bg-red-700">
                                                Delete
                                            </a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>

                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </main>
    </div>
</body>

</html>