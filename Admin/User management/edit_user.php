<?php
session_start();
include '../../Database/connect_to_db.php';

// Only Admin
if (!isset($_SESSION['role_id']) || $_SESSION['role_id'] != 1) {
    die("Access Denied!");
}

// Page title for header
$page_title = "Edit User";

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Invalid User ID");
}

$user_id = intval($_GET['id']);
$errors = [];
$success = "";

// Fetch User Data
$query = "SELECT * FROM users WHERE user_id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$user) {
    die("User not found!");
}

// Fetch Roles
$roles = mysqli_query($conn, "SELECT role_id, role_name FROM roles");


// PROCESS UPDATE
if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['update_user'])) {

    $name = trim(filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS));
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $role_id = filter_input(INPUT_POST, 'role_id', FILTER_VALIDATE_INT);
    $status = $_POST['status'];

    // Validation
    if (empty($name)) $errors[] = "Name is required";
    if (!$email) $errors[] = "Valid email is required";
    if (!$role_id) $errors[] = "Select a valid role";

    if (empty($errors)) {
        $update_query = "
            UPDATE users 
            SET name = ?, email = ?, role_id = ?, status = ?
            WHERE user_id = ?
        ";

        $stmt = mysqli_prepare($conn, $update_query);
        mysqli_stmt_bind_param($stmt, "ssisi", $name, $email, $role_id, $status, $user_id);

        if (mysqli_stmt_execute($stmt)) {
            $success = "User updated successfully";
        } else {
            $errors[] = "Failed to update user";
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
    <title>Edit User</title>
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
            <!-- Add User Form -->
            <div class="form grid place-items-center place-content-center gap-6 mt-10 mb-10">

            <h2 class="">Edit User</h2>
    <hr>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <?php foreach ($errors as $err) echo "<div>$err</div>"; ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>


    <form method="POST" class="min-w-[400px] mx-auto">

        <div class="relative z-0 w-full mb-6 group">
            <label class="form-label absolute text-sm text-body duration-300 transform -translate-y-7 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 peer-focus:text-fg-brand peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-7 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto">Full Name</label>
            <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" class="form-control block py-2.5 px-0 w-full text-sm text-heading bg-transparent border-0 border-b-2 border-default-medium appearance-none focus:outline-none focus:ring-0 focus:border-brand peer" required>
        </div>

        <div class="relative z-0 w-full mb-5 group">
            <label class="form-label absolute text-sm text-body duration-300 transform -translate-y-7 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 peer-focus:text-fg-brand peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-7 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto">Email Address</label>
            <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" class="form-control block py-2.5 px-0 w-full text-sm text-heading bg-transparent border-0 border-b-2 border-default-medium appearance-none focus:outline-none focus:ring-0 focus:border-brand peer" required>
        </div>

        <div class="relative z-0 w-full mb-5 group">
            <label class="form-label">Role</label>
            <select name="role_id" class="form-select block w-full px-3 py-2.5 bg-neutral-secondary-medium border border-default-medium text-start text-heading text-sm rounded-base focus:ring-brand focus:border-brand shadow-xs placeholder:text-body" required>
                <?php while($role = mysqli_fetch_assoc($roles)): ?>
                    <option value="<?= $role['role_id'] ?>"
                        <?= $role['role_id'] == $user['role_id'] ? "selected" : "" ?>>
                        <?= htmlspecialchars($role['role_name']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="relative z-0 w-full mb-5 group">
            <label>Status</label>
            <select name="status" class="form-select block w-full px-3 py-2.5 bg-neutral-secondary-medium border border-default-medium text-start text-heading text-sm rounded-base focus:ring-brand focus:border-brand shadow-xs placeholder:text-body">
                <option value="active" <?= $user['status'] == "active" ? "selected" : "" ?>>Active</option>
                <option value="inactive" <?= $user['status'] == "inactive" ? "selected" : "" ?>>Inactive</option>
            </select>
        </div>

        <button type="submit" name="update_user" class="btn btn-primary text-black bg-[#CFF752] box-border border border-transparent hover:bg-brand-strong focus:ring-4 focus:ring-brand-medium shadow-xs font-medium leading-5 rounded-xl text-sm px-4 py-2.5 focus:outline-none">Update User</button>
        <a href="view_users.php" class="btn btn-secondary text-black bg-[#CFF752] box-border border border-transparent hover:bg-brand-strong focus:ring-4 focus:ring-brand-medium shadow-xs font-medium leading-5 rounded-xl text-sm px-4 py-2.5 focus:outline-none">Back</a>

    </form>
            </div>
        </main>
    </div>
</body>
</html>