<?php
session_start();
include '../../Database/connect_to_db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
    header("Location: ../AD_login.php");
    exit();
}

// Fetch appointments for calendar
$appointments_query = "
    SELECT a.*, c.name as client_name, s.service_name, st.name as stylist_name
    FROM appointments a
    JOIN clients c ON a.client_id = c.client_id
    JOIN services s ON a.service_id = s.service_id
    LEFT JOIN staff st ON a.stylist_id = st.staff_id
    WHERE a.status != 'cancelled'
    ORDER BY a.appointment_date, a.appointment_time
";
$appointments_result = mysqli_query($conn, $appointments_query);

$appointments_json = [];
while($apt = mysqli_fetch_assoc($appointments_result)) {
    $appointments_json[] = [
        'id' => $apt['appointment_id'],
        'title' => $apt['client_name'] . ' - ' . $apt['service_name'],
        'start' => $apt['appointment_date'] . 'T' . $apt['appointment_time'],
        'client' => $apt['client_name'],
        'service' => $apt['service_name'],
        'stylist' => $apt['stylist_name'] ?? 'N/A',
        'status' => $apt['status']
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Calendar - Elegance Salon</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/main.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/main.min.js"></script>
</head>
<body class="bg-gray-50">
    <div class="flex min-h-screen">
        <?php include '../../Components/AD_DH_sidebar.php'; ?>
        <main class="main-content flex-1 lg:ml-[250px] w-full">
            <?php include '../../Components/DB_Header.php'; ?>
            
            <div class="main p-6">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-3xl font-bold text-[#1a1333]">Appointment Calendar</h1>
                    <a href="list_appointments.php" class="px-6 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 font-semibold">
                        List View
                    </a>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div id="calendar"></div>
                </div>

                <!-- Modal for appointment details -->
                <div id="appointmentModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
                    <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-xl font-bold">Appointment Details</h3>
                            <button onclick="closeModal()" class="text-gray-500 hover:text-gray-700">✕</button>
                        </div>
                        <div id="modalContent"></div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        const appointments = <?php echo json_encode($appointments_json); ?>;
        
        document.addEventListener('DOMContentLoaded', function() {
            const calendarEl = document.getElementById('calendar');
            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                events: appointments.map(apt => ({
                    id: apt.id,
                    title: apt.title,
                    start: apt.start,
                    backgroundColor: apt.status === 'completed' ? '#10b981' : apt.status === 'cancelled' ? '#ef4444' : '#f59e0b',
                    borderColor: apt.status === 'completed' ? '#10b981' : apt.status === 'cancelled' ? '#ef4444' : '#f59e0b',
                    extendedProps: apt
                })),
                editable: true,
                droppable: true,
                eventDrop: function(info) {
                    updateAppointmentTime(info.event.id, info.event.start);
                },
                eventClick: function(info) {
                    showAppointmentDetails(info.event.extendedProps);
                }
            });
            calendar.render();
        });

        function updateAppointmentTime(appointmentId, newStart) {
            const formData = new FormData();
            formData.append('appointment_id', appointmentId);
            formData.append('new_date', newStart.toISOString().split('T')[0]);
            formData.append('new_time', newStart.toTimeString().split(' ')[0].substring(0, 5));

            fetch('update_appointment.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    alert('Appointment updated successfully!');
                    location.reload();
                } else {
                    alert('Failed to update appointment: ' + data.error);
                    location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please refresh the page.');
                location.reload();
            });
        }

        function showAppointmentDetails(apt) {
            document.getElementById('modalContent').innerHTML = `
                <div class="space-y-2">
                    <p><strong>Client:</strong> ${apt.client}</p>
                    <p><strong>Service:</strong> ${apt.service}</p>
                    <p><strong>Stylist:</strong> ${apt.stylist}</p>
                    <p><strong>Status:</strong> <span class="px-2 py-1 rounded text-xs ${apt.status === 'completed' ? 'bg-green-100 text-green-800' : apt.status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800'}">${apt.status}</span></p>
                </div>
                <div class="mt-4">
                    <a href="view_appointment.php?id=${apt.id}" class="inline-block px-4 py-2 bg-[#d946ef] text-white rounded-lg hover:bg-purple-700">View Details</a>
                </div>
            `;
            document.getElementById('appointmentModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('appointmentModal').classList.add('hidden');
        }
    </script>
</body>
</html>

