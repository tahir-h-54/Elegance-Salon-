<?php
session_start();
include 'Database/connect_to_db.php';

$error = "";
$success = "";

// Handle login
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    // Check client_login table first
    $login_query = "
        SELECT cl.*, c.client_id, c.name, c.email, c.phone
        FROM client_login cl
        JOIN clients c ON cl.client_id = c.client_id
        WHERE cl.email = '$email'
    ";
    $result = mysqli_query($conn, $login_query);

    if(mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        if(password_verify($password, $user['password'])) {
            $_SESSION['client_id'] = $user['client_id'];
            $_SESSION['client_name'] = $user['name'];
            $_SESSION['client_email'] = $user['email'];
            $_SESSION['client_logged_in'] = true;
            
            // Update last login
            $update_login = "UPDATE client_login SET last_login = NOW() WHERE client_id = " . $user['client_id'];
            mysqli_query($conn, $update_login);
            
            header("Location: customer-dashboard.php");
            exit;
        } else {
            $error = "Invalid email or password";
        }
    } else {
        $error = "Account not found. Please register first.";
    }
}

// Handle registration
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
    $name = mysqli_real_escape_string($conn, $_POST['reg_name']);
    $email = mysqli_real_escape_string($conn, $_POST['reg_email']);
    $phone = mysqli_real_escape_string($conn, $_POST['reg_phone']);
    $password = $_POST['reg_password'];
    $confirm_password = $_POST['reg_confirm_password'];

    if($password !== $confirm_password) {
        $error = "Passwords do not match";
    } else {
        // Check if email already exists
        $check_email = "SELECT client_id FROM clients WHERE email = '$email'";
        $email_result = mysqli_query($conn, $check_email);
        
        if(mysqli_num_rows($email_result) > 0) {
            $error = "Email already registered. Please login instead.";
        } else {
            // Create client
            $insert_client = "INSERT INTO clients (name, email, phone) VALUES ('$name', '$email', '$phone')";
            if(mysqli_query($conn, $insert_client)) {
                $client_id = mysqli_insert_id($conn);
                
                // Create login credentials
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $insert_login = "INSERT INTO client_login (client_id, email, password) VALUES ($client_id, '$email', '$hashed_password')";
                
                if(mysqli_query($conn, $insert_login)) {
                    $success = "Registration successful! Please login.";
                } else {
                    $error = "Registration failed. Please try again.";
                }
            } else {
                $error = "Registration failed. Please try again.";
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <title>Customer Login - Elegance Salon</title>
    <link rel="shortcut icon" href="images/elegance-saloon-short-logo.png" type="image/png">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-[#CFF752] min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-2xl p-8">
        <div class="text-center mb-8">
            <img src="images/elegance-saloon-logo-no-bg.png" alt="Logo" class="w-32 mx-auto mb-4">
            <h1 class="text-3xl font-bold" style="font-family: 'ivymode';">Welcome Back</h1>
            <p class="text-gray-600 mt-2">Login to your account or create a new one</p>
        </div>

        <?php if($error): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <p><?php echo $error; ?></p>
            </div>
        <?php endif; ?>

        <?php if($success): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                <p><?php echo $success; ?></p>
            </div>
        <?php endif; ?>

        <!-- Login Form -->
        <div id="loginForm">
            <form method="POST">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" name="email" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#CFF752]" placeholder="your@email.com">
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    <input type="password" name="password" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#CFF752]" placeholder="••••••••">
                </div>
                <button type="submit" name="login" class="w-full py-3 bg-black text-white rounded-lg hover:bg-gray-800 transition-colors font-semibold mb-4">
                    Login
                </button>
            </form>
            <p class="text-center text-sm text-gray-600">
                Don't have an account? 
                <button onclick="toggleForms()" class="text-[#CFF752] font-semibold hover:underline">Register here</button>
            </p>
        </div>

        <!-- Registration Form -->
        <div id="registerForm" class="hidden">
            <form method="POST">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                    <input type="text" name="reg_name" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#CFF752]" placeholder="Your name">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" name="reg_email" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#CFF752]" placeholder="your@email.com">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                    <input type="tel" name="reg_phone" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#CFF752]" placeholder="+1 (555) 123-4567">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    <input type="password" name="reg_password" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#CFF752]" placeholder="••••••••">
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                    <input type="password" name="reg_confirm_password" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#CFF752]" placeholder="••••••••">
                </div>
                <button type="submit" name="register" class="w-full py-3 bg-black text-white rounded-lg hover:bg-gray-800 transition-colors font-semibold mb-4">
                    Register
                </button>
            </form>
            <p class="text-center text-sm text-gray-600">
                Already have an account? 
                <button onclick="toggleForms()" class="text-[#CFF752] font-semibold hover:underline">Login here</button>
            </p>
        </div>

        <div class="mt-6 text-center">
            <a href="src/Elegance_Salon.php" class="text-sm text-gray-600 hover:text-[#CFF752]">← Back to Home</a>
        </div>
    </div>

    <script>
        function toggleForms() {
            document.getElementById('loginForm').classList.toggle('hidden');
            document.getElementById('registerForm').classList.toggle('hidden');
        }
    </script>
</body>
</html>

