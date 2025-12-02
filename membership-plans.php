<?php
include 'Database/connect_to_db.php';

// Fetch active membership plans
$memberships_query = "SELECT * FROM memberships WHERE status = 'active' ORDER BY price ASC";
$memberships_result = mysqli_query($conn, $memberships_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <title>Membership Plans - Elegance Salon</title>
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
            <a href="book-appointment.php" class="p-2 my-10 w-30 text-[15px] justify-center items-center flex bg-none text-[#000] rounded-full bg-[#CFF752] cursor-pointer">Book Now</a>
        </div>
    </header>

    <main class="pt-32 pb-20 px-8">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-12">
                <h1 class="text-5xl font-bold mb-4" style="font-family: 'ivymode';">Membership Plans</h1>
                <p class="text-gray-600 text-lg">Join our VIP membership program and enjoy exclusive benefits</p>
            </div>

            <?php if(mysqli_num_rows($memberships_result) > 0): ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <?php while($membership = mysqli_fetch_assoc($memberships_result)): 
                        $duration_text = $membership['duration_days'] >= 365 ? round($membership['duration_days'] / 365) . ' Year' : round($membership['duration_days'] / 30) . ' Months';
                    ?>
                        <div class="membership-card card bg-white border-2 border-gray-200 rounded-lg p-8 hover:border-[#CFF752] transition-all shadow-lg hover:shadow-2xl">
                            <div class="text-center mb-6">
                                <h3 class="text-2xl font-bold mb-2" style="font-family: 'ivymode';"><?php echo htmlspecialchars($membership['name']); ?></h3>
                                <div class="text-4xl font-bold text-[#CFF752] mb-2">$<?php echo number_format($membership['price'], 2); ?></div>
                                <p class="text-gray-600"><?php echo $duration_text; ?></p>
                            </div>
                            
                            <div class="mb-6">
                                <p class="text-gray-700 mb-4"><?php echo htmlspecialchars($membership['description']); ?></p>
                                <?php if($membership['benefits']): ?>
                                    <ul class="space-y-2">
                                        <?php 
                                        $benefits = explode("\n", $membership['benefits']);
                                        foreach($benefits as $benefit): 
                                            $benefit = trim($benefit);
                                            if($benefit):
                                        ?>
                                            <li class="flex items-start">
                                                <i class="fas fa-check text-[#CFF752] mr-2 mt-1"></i>
                                                <span class="text-gray-700"><?php echo htmlspecialchars($benefit); ?></span>
                                            </li>
                                        <?php 
                                            endif;
                                        endforeach; 
                                        ?>
                                    </ul>
                                <?php endif; ?>
                                <?php if($membership['discount_percentage'] > 0): ?>
                                    <div class="mt-4 p-3 bg-[#CFF752] bg-opacity-20 rounded-lg">
                                        <p class="font-semibold text-[#CFF752]">
                                            <i class="fas fa-tag"></i> <?php echo $membership['discount_percentage']; ?>% Discount on All Services
                                        </p>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <?php if(isset($_SESSION['client_logged_in'])): ?>
                                <a href="purchase-membership.php?id=<?php echo $membership['membership_id']; ?>" class="block w-full text-center py-3 bg-[#CFF752] text-black rounded-full hover:bg-[#b8e042] transition-colors font-bold">
                                    Purchase Now
                                </a>
                            <?php else: ?>
                                <a href="customer-login.php" class="block w-full text-center py-3 bg-gray-200 text-gray-700 rounded-full hover:bg-gray-300 transition-colors font-bold">
                                    Login to Purchase
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <div class="text-center py-20">
                    <p class="text-gray-500 text-xl">No membership plans available at the moment. Check back soon!</p>
                </div>
            <?php endif; ?>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
    <script>
        gsap.registerPlugin(ScrollTrigger);
        gsap.utils.toArray(".membership-card").forEach((el, index) => {
            gsap.fromTo(el,
                { y: "120px", opacity: 0 },
                {
                    y: "0px",
                    opacity: 1,
                    ease: "power3.out",
                    delay: index * 0.1,
                    scrollTrigger: {
                        trigger: el,
                        start: "top 85%",
                        toggleActions: "play none none none"
                    }
                }
            );
        });
    </script>
</body>
</html>

