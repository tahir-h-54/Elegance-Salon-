<?php
include 'Database/connect_to_db.php';

$success = "";
$error = "";
$selected_service = isset($_GET['service']) ? intval($_GET['service']) : 0;

// Fetch services
$services_query = "SELECT * FROM services ORDER BY service_name";
$services_result = mysqli_query($conn, $services_query);

// Fetch staff
$staff_query = "SELECT * FROM staff ORDER BY name";
$staff_result = mysqli_query($conn, $staff_query);

// Handle form submission
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $client_name = mysqli_real_escape_string($conn, $_POST['name']);
    $client_email = mysqli_real_escape_string($conn, $_POST['email']);
    $client_phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $service_id = intval($_POST['service_id']);
    $stylist_id = intval($_POST['stylist_id']);
    $appointment_date = mysqli_real_escape_string($conn, $_POST['appointment_date']);
    $appointment_time = mysqli_real_escape_string($conn, $_POST['appointment_time']);
    $remarks = mysqli_real_escape_string($conn, $_POST['remarks'] ?? '');

    // Check if client exists, if not create
    $client_check = "SELECT client_id FROM clients WHERE email = '$client_email' LIMIT 1";
    $client_result = mysqli_query($conn, $client_check);
    
    if(mysqli_num_rows($client_result) > 0) {
        $client = mysqli_fetch_assoc($client_result);
        $client_id = $client['client_id'];
    } else {
        $insert_client = "INSERT INTO clients (name, email, phone) VALUES ('$client_name', '$client_email', '$client_phone')";
        if(mysqli_query($conn, $insert_client)) {
            $client_id = mysqli_insert_id($conn);
        } else {
            $error = "Failed to create client record.";
        }
    }

    if(!$error) {
        // Check for time slot availability
        $check_availability = "SELECT appointment_id FROM appointments 
                             WHERE stylist_id = $stylist_id 
                             AND appointment_date = '$appointment_date' 
                             AND appointment_time = '$appointment_time' 
                             AND status != 'cancelled'";
        $availability_result = mysqli_query($conn, $check_availability);
        
        if(mysqli_num_rows($availability_result) > 0) {
            $error = "This time slot is already booked. Please choose another time.";
        } else {
            // Insert appointment
            $insert_appointment = "INSERT INTO appointments (client_id, service_id, stylist_id, appointment_date, appointment_time, remarks, status) 
                                  VALUES ($client_id, $service_id, $stylist_id, '$appointment_date', '$appointment_time', '$remarks', 'booked')";
            
            if(mysqli_query($conn, $insert_appointment)) {
                $appointment_id = mysqli_insert_id($conn);
                
                // Create notification record
                $insert_notification = "INSERT INTO appointment_notifications (appointment_id, type, status) VALUES ($appointment_id, 'email', 'pending')";
                mysqli_query($conn, $insert_notification);
                
                $success = "Appointment booked successfully! We'll send you a confirmation email shortly.";
            } else {
                $error = "Failed to book appointment. Please try again.";
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
    <title>Book Appointment - Elegance Salon</title>
    <link rel="shortcut icon" href="images/elegance-saloon-short-logo.png" type="image/png">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body>
    <header class="flex justify-around lg:gap-40 fixed w-[100%] z-30 bg-white/80 backdrop-blur-sm">
        <div class="sm:flex hidden">
            <a href="src/Elegance_Salon.php" class="p-2 my-10 lg:mr-40 w-25 text-[15px] justify-center items-center flex bg-none text-[#000] rounded-full border-[#000] border-1 hover:bg-[#000] hover:text-[#fff] cursor-pointer">← Home</a>
        </div>
        <div class="sm:w-50 w-40 flex select-none">
            <a href="src/Elegance_Salon.php"><img src="images/elegance-saloon-logo-no-bg.png" alt="Elegance logo"></a>
        </div>
        <div class="sm:hidden w-10 flex my-10">
            <a href="src/Elegance_Salon.php"><span class="text-2xl">←</span></a>
        </div>
        <div class="sm:flex hidden gap-2">
            <a href="customer-login.php" class="p-2 my-10 w-30 text-[15px] justify-center items-center flex bg-none text-[#000] rounded-full border-[#000] border-1 hover:bg-[#000] hover:text-[#fff] cursor-pointer">Login</a>
        </div>
    </header>

    <main class="pt-32 pb-20 px-8">
        <div class="max-w-3xl mx-auto">
            <div class="text-center mb-12">
                <h1 class="text-5xl font-bold mb-4" style="font-family: 'ivymode';">Book Your Appointment</h1>
                <p class="text-gray-600 text-lg">Schedule your beauty service with us</p>
            </div>

            <?php if($success): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    <p><?php echo $success; ?></p>
                </div>
            <?php endif; ?>

            <?php if($error): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    <p><?php echo $error; ?></p>
                </div>
            <?php endif; ?>

            <form method="POST" class="bg-white border border-gray-200 rounded-lg p-8 shadow-lg">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                        <input type="text" name="name" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#CFF752]" placeholder="Your name">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                        <input type="email" name="email" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#CFF752]" placeholder="your@email.com">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number *</label>
                        <input type="tel" name="phone" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#CFF752]" placeholder="+1 (555) 123-4567">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Service *</label>
                        <select name="service_id" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#CFF752]">
                            <option value="">Select a service</option>
                            <?php while($service = mysqli_fetch_assoc($services_result)): ?>
                                <option value="<?php echo $service['service_id']; ?>" <?php echo $selected_service == $service['service_id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($service['service_name']); ?> - $<?php echo number_format($service['price'], 2); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Stylist *</label>
                        <select name="stylist_id" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#CFF752]">
                            <option value="">Select a stylist</option>
                            <?php 
                            mysqli_data_seek($staff_result, 0);
                            while($staff = mysqli_fetch_assoc($staff_result)): 
                            ?>
                                <option value="<?php echo $staff['staff_id']; ?>">
                                    <?php echo htmlspecialchars($staff['name']); ?> - <?php echo htmlspecialchars($staff['role']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Date *</label>
                        <input type="date" name="appointment_date" required min="<?php echo date('Y-m-d'); ?>" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#CFF752]">
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Time *</label>
                    <input type="time" name="appointment_time" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#CFF752]">
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Special Requests or Notes</label>
                    <textarea name="remarks" rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#CFF752]" placeholder="Any special requests or notes..."></textarea>
                </div>

                <button type="submit" class="w-full py-4 bg-[#CFF752] text-black rounded-full hover:bg-[#b8e042] transition-colors font-bold text-lg">
                    Confirm Booking
                </button>
            </form>

            <div class="mt-8 text-center">
                <p class="text-gray-600">Already have an account? <a href="customer-login.php" class="text-[#CFF752] font-semibold hover:underline">Login here</a></p>
            </div>
        </div>
    </main>

    <footer class="bg-black text-white py-12 px-8">
        <div class="max-w-7xl mx-auto text-center">
            <p class="text-gray-400">&copy; <?php echo date('Y'); ?> Elegance Salon. All rights reserved.</p>
        </div>
    </footer>

    <script>
        // Set minimum date to today
        document.querySelector('input[name="appointment_date"]').setAttribute('min', new Date().toISOString().split('T')[0]);
    </script>
</body>
</html>

