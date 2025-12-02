<?php
session_start();
include '../../Database/connect_to_db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
    header("Location: ../AD_login.php");
    exit();
}

$page_title = "Edit Appointment";
$success = "";
$error = "";

if (!isset($_GET['id']) || !intval($_GET['id'])) {
    die("Invalid appointment ID");
}

$appointment_id = intval($_GET['id']);

// Fetch appointment details
$appointment_query = "SELECT a.*, c.name as client_name, s.service_name 
                      FROM appointments a
                      JOIN clients c ON a.client_id = c.client_id
                      JOIN services s ON a.service_id = s.service_id
                      WHERE a.appointment_id = $appointment_id";
$appointment_result = mysqli_query($conn, $appointment_query);
$appointment = mysqli_fetch_assoc($appointment_result);

if (!$appointment) {
    die("Appointment not found");
}

// Fetch clients, services, and staff for dropdowns
$clients_query = "SELECT client_id, name FROM clients ORDER BY name";
$clients_result = mysqli_query($conn, $clients_query);

$services_query = "SELECT service_id, service_name FROM services ORDER BY service_name";
$services_result = mysqli_query($conn, $services_query);

$staff_query = "SELECT staff_id, name FROM staff ORDER BY name";
$staff_result = mysqli_query($conn, $staff_query);

// Handle form submission
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $client_id = intval($_POST['client_id']);
    $service_id = intval($_POST['service_id']);
    $stylist_id = !empty($_POST['stylist_id']) ? intval($_POST['stylist_id']) : NULL;
    $appointment_date = mysqli_real_escape_string($conn, $_POST['appointment_date']);
    $appointment_time = mysqli_real_escape_string($conn, $_POST['appointment_time']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $remarks = mysqli_real_escape_string($conn, $_POST['remarks'] ?? '');

    // Check for conflicts (excluding current appointment)
    $check_conflict = "SELECT appointment_id FROM appointments 
                      WHERE appointment_id != $appointment_id 
                      AND appointment_date = '$appointment_date' 
                      AND appointment_time = '$appointment_time' 
                      AND status != 'cancelled'";
    $conflict_result = mysqli_query($conn, $check_conflict);

    if(mysqli_num_rows($conflict_result) > 0) {
        $error = "Time slot already booked. Please choose a different time.";
    } else {
        $update_query = "UPDATE appointments 
                        SET client_id = $client_id, 
                            service_id = $service_id, 
                            stylist_id = " . ($stylist_id ? $stylist_id : 'NULL') . ", 
                            appointment_date = '$appointment_date', 
                            appointment_time = '$appointment_time', 
                            status = '$status', 
                            remarks = '$remarks'
                        WHERE appointment_id = $appointment_id";
        
        if(mysqli_query($conn, $update_query)) {
            $success = "Appointment updated successfully!";
            // Refresh appointment data
            $appointment_result = mysqli_query($conn, $appointment_query);
            $appointment = mysqli_fetch_assoc($appointment_result);
        } else {
            $error = "Failed to update appointment.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Appointment - Elegance Salon</title>
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
                    <a href="list_appointments.php" class="text-gray-600 hover:text-gray-800">← Back to Appointments</a>
                    <h1 class="text-3xl font-bold text-[#1a1333] mt-4">Edit Appointment</h1>
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

                <div class="bg-white rounded-lg shadow p-6 max-w-3xl">
                    <form method="POST">
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Client *</label>
                                <select name="client_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#d946ef]">
                                    <?php while($client = mysqli_fetch_assoc($clients_result)): ?>
                                        <option value="<?php echo $client['client_id']; ?>" <?php echo $client['client_id'] == $appointment['client_id'] ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($client['name']); ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Service *</label>
                                <select name="service_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#d946ef]">
                                    <?php while($service = mysqli_fetch_assoc($services_result)): ?>
                                        <option value="<?php echo $service['service_id']; ?>" <?php echo $service['service_id'] == $appointment['service_id'] ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($service['service_name']); ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Stylist</label>
                                <select name="stylist_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#d946ef]">
                                    <option value="">Select Stylist</option>
                                    <?php 
                                    mysqli_data_seek($staff_result, 0);
                                    while($staff = mysqli_fetch_assoc($staff_result)): ?>
                                        <option value="<?php echo $staff['staff_id']; ?>" <?php echo $staff['staff_id'] == $appointment['stylist_id'] ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($staff['name']); ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                                <select name="status" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#d946ef]">
                                    <option value="booked" <?php echo $appointment['status'] == 'booked' ? 'selected' : ''; ?>>Booked</option>
                                    <option value="completed" <?php echo $appointment['status'] == 'completed' ? 'selected' : ''; ?>>Completed</option>
                                    <option value="cancelled" <?php echo $appointment['status'] == 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                </select>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Date *</label>
                                <input type="date" name="appointment_date" required value="<?php echo htmlspecialchars($appointment['appointment_date']); ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#d946ef]">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Time *</label>
                                <input type="time" name="appointment_time" required value="<?php echo htmlspecialchars($appointment['appointment_time']); ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#d946ef]">
                            </div>
                        </div>
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Remarks</label>
                            <textarea name="remarks" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#d946ef]"><?php echo htmlspecialchars($appointment['remarks'] ?? ''); ?></textarea>
                        </div>
                        <div class="flex gap-4">
                            <button type="submit" class="px-6 py-2 bg-[#d946ef] text-white rounded-lg hover:bg-purple-700 font-semibold">
                                Update Appointment
                            </button>
                            <a href="list_appointments.php" class="px-6 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 font-semibold">
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

