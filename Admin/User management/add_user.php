<?php
session_start();

include '../../Database/connect_to_db.php';

// Allow only admin
if (!isset($_SESSION['role_id']) || $_SESSION['role_id'] != 1) {
    die("Access Denied! Only admin can add users.");
}

// Page title for admin header
$page_title = "Add User";

// Initialize variables
$errors = [];
$success = "";

// Handle Form Submit
if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['add_user'])) {

    // Sanitize Inputs
    $name = trim(filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS));
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'];  // sanitized manually later
    $confirm_password = $_POST['confirm_password'];
    $role_id = filter_input(INPUT_POST, 'role_id', FILTER_VALIDATE_INT);

    // -----------------------
    // VALIDATION
    // -----------------------

    if (empty($name)) {
        $errors[] = "Name is required";
    }

    if (!$email) {
        $errors[] = "Valid email is required";
    }

    if (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters";
    }

    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match";
    }

    if (!$role_id) {
        $errors[] = "Please select a valid role";
    }

    // Check if email already exists
    $check_query = "SELECT email FROM users WHERE email = ?";
    $stmt = mysqli_prepare($conn, $check_query);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) > 0) {
        $errors[] = "Email already exists";
    }

    mysqli_stmt_close($stmt);

    // -----------------------
    // INSERT USER
    // -----------------------

    if (empty($errors)) {

        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $insert_query = "INSERT INTO users (name, email, password, role_id, status) 
                         VALUES (?, ?, ?, ?, 'active')";

        $stmt = mysqli_prepare($conn, $insert_query);
        mysqli_stmt_bind_param($stmt, "sssi", $name, $email, $hashed_password, $role_id);

        if (mysqli_stmt_execute($stmt)) {
            $success = "User added successfully!";
        } else {
            $errors[] = "Failed to add user. Try again.";
        }

        mysqli_stmt_close($stmt);
    }
}

// Fetch Roles for Dropdown
$role_query = "SELECT role_id, role_name FROM roles ORDER BY role_name ASC";
$roles = mysqli_query($conn, $role_query);

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
            <!-- Add User Form -->
            <div class="form grid place-items-center place-content-center mt-40 mb-10">

            <h2>Add New User</h2>
            <hr>

                <!-- Show Errors -->
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <ul>
                            <?php foreach ($errors as $err): ?>
                                <li><?= htmlspecialchars($err) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <!-- Show Success -->
                <?php if (!empty($success)): ?>
                    <div class="alert alert-success">
                        <?= htmlspecialchars($success) ?>
                    </div>
                <?php endif; ?>
                

                <form method="post" class="min-w-[400px] mx-auto">
                    <div class="relative z-0 w-full mb-5 group">
                        <input type="text" name="name" id="floating_full_name" class="block py-2.5 px-0 w-full text-sm text-heading bg-transparent border-0 border-b-2 border-default-medium appearance-none focus:outline-none focus:ring-0 focus:border-brand peer" placeholder=" " required />
                        <label for="floating_full_name" class="absolute text-sm text-body duration-300 transform -translate-y-7 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 peer-focus:text-fg-brand peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-7 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto">Full name</label>
                    </div>
                    <div class="relative z-0 w-full mb-5 group">
                        <input type="email" name="email" id="floating_email" class="block py-2.5 px-0 w-full text-sm text-heading bg-transparent border-0 border-b-2 border-default-medium appearance-none focus:outline-none focus:ring-0 focus:border-brand peer" placeholder=" " required />
                        <label for="floating_email" class="absolute text-sm text-body duration-300 transform -translate-y-7 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 peer-focus:text-fg-brand peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-7 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto">Email address</label>
                    </div>
                    <div class="relative z-0 w-full mb-5 group">
                        <input type="password" name="password" id="floating_password" class="block py-2.5 px-0 w-full text-sm text-heading bg-transparent border-0 border-b-2 border-default-medium appearance-none focus:outline-none focus:ring-0 focus:border-brand peer" placeholder=" " required />
                        <label for="floating_password" class="absolute text-sm text-body duration-300 transform -translate-y-7 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 peer-focus:text-fg-brand peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-7 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto">Password</label>
                    </div>
                    <div class="relative z-0 w-full mb-5 group">
                        <input type="password" name="confirm_password" id="floating_repeat_password" class="block py-2.5 px-0 w-full text-sm text-heading bg-transparent border-0 border-b-2 border-default-medium appearance-none focus:outline-none focus:ring-0 focus:border-brand peer" placeholder=" " required />
                        <label for="floating_repeat_password" class="absolute text-sm text-body duration-300 transform -translate-y-7 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 peer-focus:text-fg-brand peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-7 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto">Confirm password</label>
                    </div>
                    <div class="relative z-0 w-full mb-5 group">
                        <!-- <label for="countries" class="block mb-2.5 text-sm font-medium text-heading">Select an option</label> -->
                        <select name="role_id" id="user-role" class="block w-full px-3 py-2.5 bg-neutral-secondary-medium border border-default-medium text-start text-heading text-sm rounded-base focus:ring-brand focus:border-brand shadow-xs placeholder:text-body">
                            <option disabled selected>Select Role</option>
                            <?php while ($row = mysqli_fetch_assoc($roles)): ?>
                                <option value="<?= $row['role_id'] ?>">
                                    <?= htmlspecialchars($row['role_name']) ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <button type="submit" name="add_user" class="w-full text-black bg-[#CFF752] box-border border border-transparent hover:bg-brand-strong focus:ring-4 focus:ring-brand-medium shadow-xs font-medium leading-5 rounded-xl text-sm px-4 py-2.5 focus:outline-none">Submit</button>
                </form>

            </div>
        </main>
    </div>
</body>

</html>