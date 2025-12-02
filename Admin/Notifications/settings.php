<?php
session_start();
include '../../Database/connect_to_db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
    header("Location: ../AD_login.php");
    exit();
}

$success = "";
$error = "";

// Fetch or create notification settings
$settings_query = "SELECT * FROM notification_settings WHERE notification_type = 'whatsapp' LIMIT 1";
$settings_result = mysqli_query($conn, $settings_query);
$settings = mysqli_fetch_assoc($settings_result);

if(!$settings) {
    // Create default settings
    $create_query = "INSERT INTO notification_settings (notification_type, is_enabled) VALUES ('whatsapp', 0)";
    mysqli_query($conn, $create_query);
    $settings = ['whatsapp_api_key' => '', 'whatsapp_api_url' => '', 'whatsapp_phone_number' => '', 'is_enabled' => 0];
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $api_key = mysqli_real_escape_string($conn, $_POST['whatsapp_api_key']);
    $api_url = mysqli_real_escape_string($conn, $_POST['whatsapp_api_url']);
    $phone_number = mysqli_real_escape_string($conn, $_POST['whatsapp_phone_number']);
    $is_enabled = isset($_POST['is_enabled']) ? 1 : 0;
    
    if($settings) {
        $update_query = "UPDATE notification_settings SET 
                        whatsapp_api_key = '$api_key',
                        whatsapp_api_url = '$api_url',
                        whatsapp_phone_number = '$phone_number',
                        is_enabled = $is_enabled
                        WHERE notification_type = 'whatsapp'";
    } else {
        $update_query = "INSERT INTO notification_settings (notification_type, whatsapp_api_key, whatsapp_api_url, whatsapp_phone_number, is_enabled) 
                        VALUES ('whatsapp', '$api_key', '$api_url', '$phone_number', $is_enabled)";
    }
    
    if(mysqli_query($conn, $update_query)) {
        $success = "WhatsApp settings updated successfully!";
        // Refresh settings
        $settings_result = mysqli_query($conn, $settings_query);
        $settings = mysqli_fetch_assoc($settings_result);
    } else {
        $error = "Failed to update settings.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WhatsApp Notification Settings - Elegance Salon</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <div class="flex min-h-screen">
        <?php include '../../Components/AD_DH_sidebar.php'; ?>
        <main class="main-content flex-1 lg:ml-[250px] w-full">
            <?php include '../../Components/DB_Header.php'; ?>
            
            <div class="main p-6">
                <h1 class="text-3xl font-bold text-[#1a1333] mb-6">WhatsApp Notification Settings</h1>

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

                <div class="bg-white rounded-lg shadow p-6 max-w-3xl">
                    <form method="POST">
                        <div class="mb-6">
                            <label class="flex items-center gap-3">
                                <input type="checkbox" name="is_enabled" <?php echo $settings['is_enabled'] ? 'checked' : ''; ?> class="w-5 h-5">
                                <span class="text-lg font-semibold">Enable WhatsApp Notifications</span>
                            </label>
                            <p class="text-sm text-gray-500 mt-2 ml-8">When enabled, appointment reminders will be sent via WhatsApp</p>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">WhatsApp API URL *</label>
                            <input type="url" name="whatsapp_api_url" value="<?php echo htmlspecialchars($settings['whatsapp_api_url'] ?? ''); ?>" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#d946ef]" placeholder="https://api.whatsapp.com/v1/send">
                            <p class="text-xs text-gray-500 mt-1">Your WhatsApp Business API endpoint</p>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">API Key *</label>
                            <input type="text" name="whatsapp_api_key" value="<?php echo htmlspecialchars($settings['whatsapp_api_key'] ?? ''); ?>" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#d946ef]" placeholder="Your API key">
                            <p class="text-xs text-gray-500 mt-1">Keep this secure and never share it publicly</p>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">WhatsApp Phone Number</label>
                            <input type="tel" name="whatsapp_phone_number" value="<?php echo htmlspecialchars($settings['whatsapp_phone_number'] ?? ''); ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#d946ef]" placeholder="+1234567890">
                            <p class="text-xs text-gray-500 mt-1">Business WhatsApp number (with country code)</p>
                        </div>

                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                            <h4 class="font-semibold text-blue-900 mb-2">Setup Instructions:</h4>
                            <ol class="list-decimal list-inside text-sm text-blue-800 space-y-1">
                                <li>Sign up for WhatsApp Business API (Twilio, MessageBird, or similar)</li>
                                <li>Get your API endpoint URL and API key from the provider</li>
                                <li>Enter the credentials above and enable notifications</li>
                                <li>Test by booking an appointment - a WhatsApp message will be sent</li>
                            </ol>
                        </div>

                        <div class="flex gap-4">
                            <button type="submit" class="px-6 py-2 bg-[#d946ef] text-white rounded-lg hover:bg-purple-700 font-semibold">
                                Save Settings
                            </button>
                            <button type="button" onclick="testWhatsApp()" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-semibold">
                                Test Connection
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <script>
        function testWhatsApp() {
            alert('Test functionality would send a test message. Implement API call here.');
        }
    </script>
</body>
</html>

