<?php
session_start();
include 'Database/connect_to_db.php';

if(!isset($_SESSION['client_logged_in']) || !$_SESSION['client_logged_in']) {
    header("Location: customer-login.php");
    exit;
}

$client_id = $_SESSION['client_id'];
$service_id = intval($_POST['service_id']);
$appointment_id = intval($_POST['appointment_id']);
$rating = intval($_POST['rating']);
$review_text = mysqli_real_escape_string($conn, $_POST['review_text']);

// Check if review already exists for this appointment
$check_review = "SELECT review_id FROM service_reviews WHERE appointment_id = $appointment_id";
$review_check = mysqli_query($conn, $check_review);

if(mysqli_num_rows($review_check) > 0) {
    header("Location: service-detail.php?id=$service_id&error=You have already reviewed this service");
    exit;
}

// Insert review
$insert_review = "INSERT INTO service_reviews (service_id, client_id, appointment_id, rating, review_text, status) 
                 VALUES ($service_id, $client_id, $appointment_id, $rating, '$review_text', 'pending')";

if(mysqli_query($conn, $insert_review)) {
    header("Location: service-detail.php?id=$service_id&success=Review submitted successfully! It will be visible after approval.");
} else {
    header("Location: service-detail.php?id=$service_id&error=Failed to submit review. Please try again.");
}
exit;
?>

