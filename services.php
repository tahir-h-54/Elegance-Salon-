<?php
include 'Database/connect_to_db.php';

// Fetch all services with images
$services_query = "
    SELECT s.*, 
           (SELECT image_path FROM service_images WHERE service_id = s.service_id AND is_primary = 1 LIMIT 1) as primary_image
    FROM services s
    WHERE s.service_id IS NOT NULL
    ORDER BY s.service_id ASC
";
$services_result = mysqli_query($conn, $services_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <title>Services - Elegance Salon</title>
    <link rel="shortcut icon" href="images/elegance-saloon-short-logo.png" type="image/png">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body>
    <div class="loader">
        <img src="images/elegance-saloon-logo-no-bg.png" alt="loader-img">
    </div>

    <div id="menu" class="fixed top-0 left-0 w-full h-screen text-3xl translate-y-[-100%] z-[999]">
        <div class="fixed w-[100%] h-[100vh] bg-[#cff752] justify-center flex items-center menu">
            <button class="fixed top-10 left-4 sm:left-20 text-[#000] text-[18px] cursor-pointer p-3 z-5" id="closeBtn">✕</button>
            <div class="text-[#000] md:flex grid justify-between w-[90%] menu-btn">
                <div class="grid gap-5">
                    <button onclick="location.href='src/Elegance_Salon.php'" class="text-[12px] text-start">01<span class="font-classic md:text-[5vw] text-[8vw]"> HOME</span></button>
                    <button onclick="location.href='services.php'" class="text-[12px] text-start">02<span class="font-classic md:text-[5vw] text-[8vw]"> SERVICES</span></button>
                    <button onclick="location.href='book-appointment.php'" class="text-[12px] text-start">03<span class="font-classic md:text-[5vw] text-[8vw]"> BOOK APPOINTMENT</span></button>
                </div>
                <div class="grid gap-5 md:pt-0 pt-4">
                    <button onclick="location.href='gallery.php'" class="text-[12px] text-start">04<span class="text-stroke md:text-[5vw] text-[8vw] font-medium"> GALLERY</span></button>
                    <button onclick="location.href='shop.php'" class="text-[12px] text-start">05<span class="text-stroke md:text-[5vw] text-[8vw] font-medium"> SHOP</span></button>
                    <button onclick="location.href='blog.php'" class="text-[12px] text-start">06<span class="text-stroke md:text-[5vw] text-[8vw] font-medium"> BLOG</span></button>
                    <button onclick="location.href='customer-login.php'" class="text-[12px] text-start">07<span class="text-stroke md:text-[5vw] text-[8vw] font-medium"> LOGIN</span></button>
                </div>
            </div>
        </div>
    </div>

    <header class="flex justify-around lg:gap-40 fixed w-[100%] z-30 bg-white/80 backdrop-blur-sm">
        <div class="sm:flex hidden">
            <span class="p-2 my-10 lg:mr-40 w-25 text-[15px] justify-center items-center flex bg-none text-[#000] rounded-full border-[#000] border-1 hover:bg-[#000] hover:text-[#fff] cursor-pointer" id="menuBtn">MENU</span>
        </div>
        <div class="sm:w-50 w-40 flex select-none">
            <a href="src/Elegance_Salon.php"><img src="images/elegance-saloon-logo-no-bg.png" alt="Elegance logo"></a>
        </div>
        <div class="sm:hidden w-10 flex my-10">
            <span id="menuBtnMobile"><img src="images/hamburger.svg" alt="hamburger"></span>
        </div>
        <div class="sm:flex hidden gap-2">
            <a href="src/Elegance_Salon.php#section-17" class="p-2 my-10 w-30 text-[15px] justify-center items-center flex bg-none text-[#000] rounded-full border-[#000] border-1 hover:bg-[#000] hover:text-[#fff] cursor-pointer">Contact Us</a>
            <a href="book-appointment.php" class="p-2 my-10 w-30 text-[15px] justify-center items-center flex bg-none text-[#000] rounded-full bg-[#CFF752] cursor-pointer">Book Now</a>
        </div>
    </header>

    <main class="pt-32 pb-20">
        <section class="services-hero-section px-8">
            <div class="service-text">
                <h4 class="text-[5vw] md:text-[2vw] service-text-reveal">Our Services</h4>
            </div>
            <div class="service-text">
                <h1 style="font-family: 'ivymode';" class="md:text-[7vw] text-[15vw] md:leading-28 leading-15 service-text-reveal">Beauty & Excellence</h1>
            </div>
            <div class="service-text">
                <p class="text-[#7F7F7F] md:text-[1.2vw] text-[4vw] service-text-reveal mt-8">Discover our premium range of beauty services designed to enhance your natural beauty</p>
            </div>
        </section>

        <section class="services-grid-section px-8 mt-20">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php 
                if(mysqli_num_rows($services_result) > 0):
                    while($service = mysqli_fetch_assoc($services_result)):
                        $image_path = $service['primary_image'] ? $service['primary_image'] : 'images/Services/depilation.png';
                        $duration_minutes = $service['duration'] ?? 60;
                ?>
                <div class="service-card relative card border border-black rounded-3xl overflow-hidden bg-white hover:shadow-2xl transition-all duration-300">
                    <div class="relative h-64 overflow-hidden">
                        <img src="<?php echo htmlspecialchars($image_path); ?>" alt="<?php echo htmlspecialchars($service['service_name']); ?>" class="w-full h-full object-cover">
                        <div class="absolute top-4 right-4 bg-[#CFF752] px-3 py-1 rounded-full">
                            <span class="text-sm font-bold text-black">$<?php echo number_format($service['price'], 2); ?></span>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-2xl font-bold mb-2" style="font-family: 'ivymode';"><?php echo htmlspecialchars($service['service_name']); ?></h3>
                        <p class="text-gray-600 mb-4 text-sm"><?php echo htmlspecialchars(substr($service['description'], 0, 100)) . (strlen($service['description']) > 100 ? '...' : ''); ?></p>
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-sm text-gray-500">
                                <i class="far fa-clock"></i> <?php echo $duration_minutes; ?> min
                            </span>
                        </div>
                        <a href="service-detail.php?id=<?php echo $service['service_id']; ?>" class="block w-full text-center py-3 bg-[#CFF752] text-black rounded-full hover:bg-[#b8e042] transition-colors font-medium">
                            View Details
                        </a>
                    </div>
                </div>
                <?php 
                    endwhile;
                else:
                ?>
                <div class="col-span-3 text-center py-20">
                    <p class="text-gray-500 text-xl">No services available at the moment. Please check back later.</p>
                </div>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <footer class="bg-black text-white py-10 px-6 mt-10">
      <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-8">
        <div>
          <img src="images/elegance-saloon-logo-white.png" alt="Elegance Salon" class="w-44 mb-4">
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
              <a href="Admin/AD_login.php" class="inline-flex items-center px-4 py-2 bg-[#CFF752] text-black rounded-full text-xs font-semibold hover:bg-[#b8e042] transition-colors">
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
        // Menu toggle
        const menu = document.getElementById("menu");
        const menuBtn = document.getElementById("menuBtn");
        const menuBtnMobile = document.getElementById("menuBtnMobile");
        const closeBtn = document.getElementById("closeBtn");

        function openMenu() {
            gsap.to(menu, { y: "0%", duration: 0.8, ease: "power4.out" });
        }
        function closeMenu() {
            gsap.to(menu, { y: "-150%", duration: 0.8, ease: "power4.in" });
        }

        if(menuBtn) menuBtn.addEventListener("click", openMenu);
        if(menuBtnMobile) menuBtnMobile.addEventListener("click", openMenu);
        if(closeBtn) closeBtn.addEventListener("click", closeMenu);

        // Loader
        gsap.to(".loader", { scaleY: 0, transformOrigin: 'top', duration: 1, delay: 4 });
        document.addEventListener("DOMContentLoaded", () => {
            setTimeout(() => {
                document.documentElement.style.overflow = "auto";
            }, 5000);
        });

        // Service text reveal animation
        gsap.registerPlugin(ScrollTrigger);
        gsap.utils.toArray(".service-text-reveal").forEach((el) => {
            gsap.fromTo(el, 
                { y: "100%", opacity: 0 },
                { 
                    y: "0%", 
                    opacity: 1,
                    duration: 1.2,
                    ease: "power3.out",
                    scrollTrigger: {
                        trigger: el,
                        start: "top 80%",
                        toggleActions: "play none none none"
                    }
                }
            );
        });

        // Service cards animation
        gsap.utils.toArray(".card").forEach((el, index) => {
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

