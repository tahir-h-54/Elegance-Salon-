<?php
session_start();
include '../../Database/connect_to_db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
    header("Location: ../AD_login.php");
    exit();
}

$success = "";
$error = "";

// Handle approve/reject
if(isset($_GET['approve'])) {
    $review_id = intval($_GET['approve']);
    $update_query = "UPDATE service_reviews SET status = 'approved' WHERE review_id = $review_id";
    if(mysqli_query($conn, $update_query)) {
        $success = "Review approved successfully.";
    } else {
        $error = "Failed to approve review.";
    }
}

if(isset($_GET['reject'])) {
    $review_id = intval($_GET['reject']);
    $update_query = "UPDATE service_reviews SET status = 'rejected' WHERE review_id = $review_id";
    if(mysqli_query($conn, $update_query)) {
        $success = "Review rejected.";
    } else {
        $error = "Failed to reject review.";
    }
}

// Fetch all reviews
$reviews_query = "
    SELECT sr.*, c.name as client_name, s.service_name
    FROM service_reviews sr
    JOIN clients c ON sr.client_id = c.client_id
    JOIN services s ON sr.service_id = s.service_id
    ORDER BY sr.created_at DESC
";
$reviews_result = mysqli_query($conn, $reviews_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Reviews - Elegance Salon</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <div class="flex min-h-screen">
        <?php include '../../Components/AD_DH_sidebar.php'; ?>
        <main class="main-content flex-1 lg:ml-[250px] w-full">
            <?php include '../../Components/DB_Header.php'; ?>
            
            <div class="main p-6">
                <h1 class="text-3xl font-bold text-[#1a1333] mb-6">Manage Reviews</h1>

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

                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Service</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Client</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rating</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Review</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php if(mysqli_num_rows($reviews_result) > 0): ?>
                                <?php while($review = mysqli_fetch_assoc($reviews_result)): ?>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4">
                                            <div class="font-medium"><?php echo htmlspecialchars($review['service_name']); ?></div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="font-medium"><?php echo htmlspecialchars($review['client_name']); ?></div>
                                            <div class="text-sm text-gray-500"><?php echo date('M d, Y', strtotime($review['created_at'])); ?></div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-1">
                                                <?php for($i = 1; $i <= 5; $i++): ?>
                                                    <i class="fas fa-star <?php echo $i <= $review['rating'] ? 'text-yellow-400' : 'text-gray-300'; ?>"></i>
                                                <?php endfor; ?>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm"><?php echo htmlspecialchars(substr($review['review_text'], 0, 100)); ?><?php echo strlen($review['review_text']) > 100 ? '...' : ''; ?></div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 py-1 rounded-full text-xs font-semibold 
                                                <?php 
                                                echo $review['status'] == 'approved' ? 'bg-green-100 text-green-800' : 
                                                    ($review['status'] == 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800'); 
                                                ?>">
                                                <?php echo ucfirst($review['status']); ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <?php if($review['status'] == 'pending'): ?>
                                                <a href="?approve=<?php echo $review['review_id']; ?>" class="text-green-600 hover:text-green-800 mr-3">Approve</a>
                                                <a href="?reject=<?php echo $review['review_id']; ?>" class="text-red-600 hover:text-red-800">Reject</a>
                                            <?php else: ?>
                                                <span class="text-gray-400">-</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">No reviews found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</body>
</html>

