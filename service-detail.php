<?php
include 'Database/connect_to_db.php';

$service_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch service details
$service_query = "SELECT * FROM services WHERE service_id = $service_id";
$service_result = mysqli_query($conn, $service_query);
$service = mysqli_fetch_assoc($service_result);

// Fetch service images
$images_query = "SELECT * FROM service_images WHERE service_id = $service_id ORDER BY is_primary DESC";
$images_result = mysqli_query($conn, $images_query);

// Fetch reviews for this service
$reviews_query = "
    SELECT sr.*, c.name as client_name, c.email as client_email
    FROM service_reviews sr
    LEFT JOIN clients c ON sr.client_id = c.client_id
    WHERE sr.service_id = $service_id AND sr.status = 'approved'
    ORDER BY sr.created_at DESC
    LIMIT 10
";
$reviews_result = mysqli_query($conn, $reviews_query);

if(!$service) {
    header("Location: services.php");
    exit;
}

// Calculate average rating
$avg_rating_query = "SELECT AVG(rating) as avg_rating, COUNT(*) as total_reviews FROM service_reviews WHERE service_id = $service_id AND status = 'approved'";
$avg_result = mysqli_query($conn, $avg_rating_query);
$avg_data = mysqli_fetch_assoc($avg_result);
$avg_rating = round($avg_data['avg_rating'] ?? 0, 1);
$total_reviews = $avg_data['total_reviews'] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <title><?php echo htmlspecialchars($service['service_name']); ?> - Elegance Salon</title>
    <link rel="shortcut icon" href="images/elegance-saloon-short-logo.png" type="image/png">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body>
    <header class="flex justify-around lg:gap-40 fixed w-[100%] z-30 bg-white/80 backdrop-blur-sm">
        <div class="sm:flex hidden">
            <a href="services.php" class="p-2 my-10 lg:mr-40 w-25 text-[15px] justify-center items-center flex bg-none text-[#000] rounded-full border-[#000] border-1 hover:bg-[#000] hover:text-[#fff] cursor-pointer">← Back</a>
        </div>
        <div class="sm:w-50 w-40 flex select-none">
            <a href="src/Elegance_Salon.php"><img src="images/elegance-saloon-logo-no-bg.png" alt="Elegance logo"></a>
        </div>
        <div class="sm:hidden w-10 flex my-10">
            <a href="services.php"><span class="text-2xl">←</span></a>
        </div>
        <div class="sm:flex hidden gap-2">
            <a href="book-appointment.php?service=<?php echo $service_id; ?>" class="p-2 my-10 w-30 text-[15px] justify-center items-center flex bg-none text-[#000] rounded-full bg-[#CFF752] cursor-pointer">Book Now</a>
        </div>
    </header>

    <main class="pt-32 pb-20 px-8">
        <div class="max-w-6xl mx-auto">
            <!-- Service Images -->
            <div class="mb-12">
                <?php if(mysqli_num_rows($images_result) > 0): ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <?php while($img = mysqli_fetch_assoc($images_result)): ?>
                            <img src="<?php echo htmlspecialchars($img['image_path']); ?>" alt="Service Image" class="w-full h-96 object-cover rounded-lg">
                        <?php endwhile; ?>
                    </div>
                <?php else: ?>
                    <img src="images/Services/depilation.png" alt="Service Image" class="w-full h-96 object-cover rounded-lg">
                <?php endif; ?>
            </div>

            <!-- Service Info -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2">
                    <h1 class="text-5xl font-bold mb-4" style="font-family: 'ivymode';"><?php echo htmlspecialchars($service['service_name']); ?></h1>
                    
                    <div class="flex items-center gap-4 mb-6">
                        <div class="flex items-center">
                            <?php for($i = 1; $i <= 5; $i++): ?>
                                <i class="fas fa-star <?php echo $i <= $avg_rating ? 'text-[#CFF752]' : 'text-gray-300'; ?>"></i>
                            <?php endfor; ?>
                            <span class="ml-2 text-gray-600"><?php echo $avg_rating; ?> (<?php echo $total_reviews; ?> reviews)</span>
                        </div>
                        <span class="text-gray-500"><i class="far fa-clock"></i> <?php echo $service['duration']; ?> minutes</span>
                    </div>

                    <div class="prose max-w-none mb-8">
                        <p class="text-gray-700 text-lg leading-relaxed"><?php echo nl2br(htmlspecialchars($service['description'])); ?></p>
                    </div>

                    <!-- Reviews Section -->
                    <div class="mt-12">
                        <h2 class="text-3xl font-bold mb-6" style="font-family: 'ivymode';">Customer Reviews</h2>
                        <?php if(mysqli_num_rows($reviews_result) > 0): ?>
                            <div class="space-y-6">
                                <?php while($review = mysqli_fetch_assoc($reviews_result)): ?>
                                    <div class="border-b border-gray-200 pb-6">
                                        <div class="flex items-center justify-between mb-2">
                                            <div>
                                                <h4 class="font-semibold"><?php echo htmlspecialchars($review['client_name'] ?? 'Anonymous'); ?></h4>
                                                <div class="flex items-center gap-1">
                                                    <?php for($i = 1; $i <= 5; $i++): ?>
                                                        <i class="fas fa-star <?php echo $i <= $review['rating'] ? 'text-[#CFF752]' : 'text-gray-300'; ?> text-sm"></i>
                                                    <?php endfor; ?>
                                                </div>
                                            </div>
                                            <span class="text-sm text-gray-500"><?php echo date('M d, Y', strtotime($review['created_at'])); ?></span>
                                        </div>
                                        <p class="text-gray-700"><?php echo htmlspecialchars($review['review_text']); ?></p>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        <?php else: ?>
                            <p class="text-gray-500">No reviews yet. Be the first to review this service!</p>
                        <?php endif; ?>
                        
                        <!-- Add Review Form (for logged-in customers) -->
                        <?php if(isset($_SESSION['client_logged_in']) && $_SESSION['client_logged_in']): 
                            // Check if customer has completed an appointment for this service
                            $check_appointment = "SELECT appointment_id FROM appointments WHERE client_id = " . $_SESSION['client_id'] . " AND service_id = $service_id AND status = 'completed' LIMIT 1";
                            $appointment_check = mysqli_query($conn, $check_appointment);
                            if(mysqli_num_rows($appointment_check) > 0):
                                $appointment_data = mysqli_fetch_assoc($appointment_check);
                        ?>
                            <div class="mt-8 border-t pt-8">
                                <h3 class="text-xl font-bold mb-4">Write a Review</h3>
                                <form method="POST" action="submit-review.php" class="space-y-4">
                                    <input type="hidden" name="service_id" value="<?php echo $service_id; ?>">
                                    <input type="hidden" name="appointment_id" value="<?php echo $appointment_data['appointment_id']; ?>">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Rating *</label>
                                        <div class="flex gap-2" id="ratingStars">
                                            <?php for($i = 1; $i <= 5; $i++): ?>
                                                <i class="far fa-star text-2xl text-gray-300 cursor-pointer hover:text-[#CFF752] rating-star" data-rating="<?php echo $i; ?>"></i>
                                            <?php endfor; ?>
                                        </div>
                                        <input type="hidden" name="rating" id="ratingValue" required>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Your Review *</label>
                                        <textarea name="review_text" required rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#CFF752]"></textarea>
                                    </div>
                                    <button type="submit" class="px-6 py-3 bg-[#CFF752] text-black rounded-full hover:bg-[#b8e042] transition-colors font-semibold">
                                        Submit Review
                                    </button>
                                </form>
                            </div>
                        <?php endif; endif; ?>
                    </div>
                </div>

                <div class="lg:col-span-1">
                    <div class="bg-white border border-gray-200 rounded-lg p-6 sticky top-32">
                        <div class="text-center mb-6">
                            <div class="text-4xl font-bold mb-2">$<?php echo number_format($service['price'], 2); ?></div>
                            <p class="text-gray-500">per session</p>
                        </div>
                        <a href="book-appointment.php?service=<?php echo $service_id; ?>" class="block w-full text-center py-4 bg-[#CFF752] text-black rounded-full hover:bg-[#b8e042] transition-colors font-bold text-lg mb-4">
                            Book Appointment
                        </a>
                        <div class="space-y-3 text-sm text-gray-600">
                            <div class="flex items-center gap-2">
                                <i class="far fa-clock text-[#CFF752]"></i>
                                <span>Duration: <?php echo $service['duration']; ?> minutes</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i class="fas fa-check-circle text-[#CFF752]"></i>
                                <span>Professional service</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i class="fas fa-star text-[#CFF752]"></i>
                                <span><?php echo $avg_rating; ?> average rating</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="bg-black text-white py-10 px-6 mt-10">
      <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-8">
        <div>
          <img src="../images/elegance-saloon-logo-white.png" alt="Elegance Salon" class="w-44 mb-4">
          <p class="text-gray-400 text-sm">
            Because you deserve beauty. Premium hair, skin and spa experiences in one elegant space.
          </p>
        </div>
        <div>
          <h4 class="font-semibold mb-3 text-sm tracking-wide">Explore</h4>
          <ul class="space-y-2 text-sm text-gray-400">
            <li><a href="../services.php" class="hover:text-[#CFF752]">Services</a></li>
            <li><a href="../gallery.php" class="hover:text-[#CFF752]">Gallery</a></li>
            <li><a href="../shop.php" class="hover:text-[#CFF752]">Shop</a></li>
            <li><a href="../blog.php" class="hover:text-[#CFF752]">Blog</a></li>
          </ul>
        </div>
        <div>
          <h4 class="font-semibold mb-3 text-sm tracking-wide">For Guests</h4>
          <ul class="space-y-2 text-sm text-gray-400">
            <li><a href="../book-appointment.php" class="hover:text-[#CFF752]">Book Appointment</a></li>
            <li><a href="../membership-plans.php" class="hover:text-[#CFF752]">Memberships</a></li>
            <li><a href="../gift-cards.php" class="hover:text-[#CFF752]">Gift Cards</a></li>
            <li><a href="../customer-dashboard.php" class="hover:text-[#CFF752]">My account</a></li>
          </ul>
        </div>
        <div>
          <h4 class="font-semibold mb-3 text-sm tracking-wide">Admin & Contact</h4>
          <ul class="space-y-2 text-sm text-gray-400">
            <li><span class="block">Email: info@elegancesalon.com</span></li>
            <li><span class="block">Phone: +1 (555) 123-4567</span></li>
            <li><span class="block">123 Beauty Street, City</span></li>
            <li class="pt-2">
              <a href="../Admin/AD_login.php" class="inline-flex items-center px-4 py-2 bg-[#CFF752] text-black rounded-full text-xs font-semibold hover:bg-[#b8e042] transition-colors">
                Admin Panel Login
              </a>
            </li>
          </ul>
        </div>
      </div>
      <div class="border-t border-gray-800 mt-8 pt-4 text-center text-xs text-gray-500">
        &copy; <?php echo date('Y'); ?> Elegance Salon. All rights reserved.
      </div>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script>
        gsap.to(".loader", { scaleY: 0, transformOrigin: 'top', duration: 1, delay: 4 });
        
        // Rating stars functionality
        const stars = document.querySelectorAll('.rating-star');
        const ratingInput = document.getElementById('ratingValue');
        
        if(stars.length > 0) {
            let selectedRating = 0;
            
            stars.forEach((star, index) => {
                star.addEventListener('click', () => {
                    selectedRating = index + 1;
                    ratingInput.value = selectedRating;
                    updateStars(selectedRating);
                });
                
                star.addEventListener('mouseenter', () => {
                    updateStars(index + 1);
                });
            });
            
            document.getElementById('ratingStars').addEventListener('mouseleave', () => {
                updateStars(selectedRating);
            });
        }
        
        function updateStars(rating) {
            stars.forEach((star, index) => {
                if(index < rating) {
                    star.classList.remove('far');
                    star.classList.add('fas');
                    star.classList.add('text-[#CFF752]');
                    star.classList.remove('text-gray-300');
                } else {
                    star.classList.remove('fas');
                    star.classList.add('far');
                    star.classList.remove('text-[#CFF752]');
                    star.classList.add('text-gray-300');
                }
            });
        }
    </script>
</body>
</html>

