<?php
session_start();
include '../../Database/connect_to_db.php';

if (!isset($_SESSION['role_id']) || $_SESSION['role_id'] != 1) {
    die("Access Denied! Only admin can edit clients.");
}

if (!isset($_GET['id']) || !intval($_GET['id'])) {
    die("Invalid client ID");
}

$client_id = intval($_GET['id']);
$page_title = "Edit Client";
$errors = [];
$success = "";

$stmt = mysqli_prepare($conn, "SELECT * FROM clients WHERE client_id = ?");
mysqli_stmt_bind_param($stmt, "i", $client_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$client = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$client) {
    die("Client not found");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_client'])) {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $preferences = trim($_POST['preferences'] ?? '');

    if ($name === '') $errors[] = "Name is required";
    if ($email === '') $errors[] = "Email is required";

    if (empty($errors)) {
        $stmt = mysqli_prepare($conn, "UPDATE clients SET name = ?, email = ?, phone = ?, address = ?, preferences = ? WHERE client_id = ?");
        mysqli_stmt_bind_param($stmt, "sssssi", $name, $email, $phone, $address, $preferences, $client_id);
        if (mysqli_stmt_execute($stmt)) {
            $success = "Client updated successfully";
            $client['name'] = $name;
            $client['email'] = $email;
            $client['phone'] = $phone;
            $client['address'] = $address;
            $client['preferences'] = $preferences;
        } else {
            $errors[] = "Failed to update client";
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
    <title>Edit Client</title>
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
                    <h2 class="text-2xl font-semibold text-gray-800 mb-4">Edit Client</h2>
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
                            <input type="text" name="name" required value="<?php echo htmlspecialchars($client['name']); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-black">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                            <input type="email" name="email" required value="<?php echo htmlspecialchars($client['email']); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-black">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                            <input type="text" name="phone" value="<?php echo htmlspecialchars($client['phone']); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-black">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                            <textarea name="address" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-black"><?php echo htmlspecialchars($client['address']); ?></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Preferences / Notes</label>
                            <textarea name="preferences" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-black"><?php echo htmlspecialchars($client['preferences']); ?></textarea>
                        </div>
                        <div class="flex items-center justify-between pt-4">
                            <a href="list_clients.php" class="text-sm text-gray-600 hover:text-black">← Back to list</a>
                            <button type="submit" name="update_client" class="px-4 py-2 bg-black text-white text-sm font-medium rounded-lg hover:bg-gray-800">
                                Update Client
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</body>
</html>


