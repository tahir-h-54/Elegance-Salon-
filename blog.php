<?php
include 'Database/connect_to_db.php';

$blog_query = "
    SELECT *
    FROM blog_posts
    WHERE status = 'published'
    ORDER BY COALESCE(published_at, created_at) DESC
";
$blog_result = mysqli_query($conn, $blog_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <title>Elegance Salon — Blog</title>
    <link rel="shortcut icon" href="images/elegance-saloon-short-logo.png" type="image/png">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body>
    <div class="loader">
        <img src="images/elegance-saloon-logo-no-bg.png" alt="loader-img">
    </div>

    <div id="menu" class="fixed top-0 left-0 w-full h-screen text-3xl translate-y-[-100%] z-[999]">
        <div class="fixed w-full h-full bg-[#cff752] justify-center flex items-center menu">
            <button class="fixed top-10 left-4 sm:left-20 text-[#000] text-[18px] cursor-pointer p-3 z-5" id="closeBtn">✕</button>
            <div class="text-[#000] md:flex grid justify-between w-[90%] menu-btn">
                <div class="grid gap-5">
                    <button class="text-[12px] text-start">01
                        <span class="font-classic md:text-[5vw] text-[8vw]">
                            <a href="src/Elegance_Salon.php"> HOME</a>
                        </span>
                    </button>
                    <button class="text-[12px] text-start">02
                        <span class="font-classic md:text-[5vw] text-[8vw]">
                            <a href="services.php"> SERVICES</a>
                        </span>
                    </button>
                    <button class="text-[12px] text-start">03
                        <span class="font-classic md:text-[5vw] text-[8vw]">
                            <a href="book-appointment.php"> BOOK</a>
                        </span>
                    </button>
                </div>
                <div class="grid gap-5 md:pt-0 pt-4">
                    <button class="text-[12px] text-start">04
                        <span class="text-stroke md:text-[5vw] text-[8vw] font-medium">
                            <a href="gallery.php"> GALLERY</a>
                        </span>
                    </button>
                    <button class="text-[12px] text-start">05
                        <span class="text-stroke md:text-[5vw] text-[8vw] font-medium">
                            <a href="shop.php"> SHOP</a>
                        </span>
                    </button>
                    <button class="text-[12px] text-start">06
                        <span class="text-stroke md:text-[5vw] text-[8vw] font-medium">
                            <a href="customer-login.php"> LOGIN</a>
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <header class="flex justify-around lg:gap-40 fixed w-full z-30 bg-white/80 backdrop-blur-sm">
        <div class="sm:flex hidden">
            <span class="p-2 my-10 lg:mr-40 w-25 text-[15px] justify-center items-center flex bg-none text-[#000] rounded-full border-[#000] border-1 hover:bg-[#000] hover:text-[#fff] cursor-pointer" id="menuBtn">
                MENU
            </span>
        </div>
        <div class="sm:w-50 w-40 flex select-none">
            <a href="src/Elegance_Salon.php">
                <img src="images/elegance-saloon-logo-no-bg.png" alt="Elegance logo">
            </a>
        </div>
        <div class="sm:hidden w-10 flex my-10">
            <span id="menuBtnMobile"><img src="images/hamburger.svg" alt="hamburger"></span>
        </div>
        <div class="sm:flex hidden gap-2">
            <a href="src/Elegance_Salon.php#section-17" class="p-2 my-10 w-30 text-[15px] justify-center items-center flex bg-none text-[#000] rounded-full border-[#000] border-1 hover:bg-[#000] hover:text-[#fff] cursor-pointer">
                Contact Us
            </a>
            <a href="book-appointment.php" class="p-2 my-10 w-30 text-[15px] justify-center items-center flex bg-none text-[#000] rounded-full bg-[#CFF752] cursor-pointer">
                Book Now
            </a>
        </div>
    </header>

    <main class="pt-32 pb-16">
        <div class="w-full text-center sm:text-start my-30 mb-10 sm:my-30 sm:mb-0 sm:m-20 blog-text" style="font-family: 'ivymode';">
            <h2 class="sm:text-[10vw] text-[20vw] blog-reveal">Blogs</h2>
        </div>

        <div class="w-full flex justify-center items-center mb-20 px-4 sm:px-0">
            <div class="w-full sm:w-[90%] grid grid-cols-1 sm:grid-cols-2 gap-6">
                <?php if(mysqli_num_rows($blog_result) > 0): ?>
                    <?php while($post = mysqli_fetch_assoc($blog_result)):
                        $image_path = $post['featured_image'] ?: 'images/Blogs/top-trending-hair-cuts.jpg';
                        $excerpt = $post['excerpt'] ?: substr(strip_tags($post['content']), 0, 220);
                        $published_on = $post['published_at'] ?: $post['created_at'];
                    ?>
                        <article class="overflow-hidden rounded-3xl border border-[#000] bg-[#fff] card">
                            <div class="relative overflow-hidden h-[300px] rounded-t-3xl">
                                <img src="<?php echo htmlspecialchars($image_path); ?>" class="w-full h-full object-cover block" alt="<?php echo htmlspecialchars($post['title']); ?>">
                            </div>
                            <div class="p-5">
                                <p class="text-xs uppercase tracking-widest text-gray-500 mb-2">
                                    <?php echo date('M d, Y', strtotime($published_on)); ?>
                                    <?php if(!empty($post['category'])): ?>
                                        • <?php echo htmlspecialchars($post['category']); ?>
                                    <?php endif; ?>
                                </p>
                                <h4 class="md:text-[1.6vw] sm:text-[3vw] text-[5vw] py-3" style="font-family: 'ivymode';">
                                    <?php echo htmlspecialchars($post['title']); ?>
                                </h4>
                                <p class="sm:text-[1vw] text-[3vw] text-gray-600 line-clamp-2">
                                    <?php echo htmlspecialchars($excerpt); ?>
                                </p>
                            </div>
                            <div class="justify-center pb-4 px-4">
                                <a href="blog-detail.php?slug=<?php echo urlencode($post['slug']); ?>">
                                    <button class="py-2 w-full border border-[#000] text-sm rounded-full hover:bg-[#000] hover:text-[#fff] transition-colors">
                                        Read more
                                    </button>
                                </a>
                            </div>
                        </article>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="col-span-2 text-center py-16">
                        <p class="text-gray-500 text-xl">No blog posts available yet. Check back soon!</p>
                    </div>
                <?php endif; ?>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@studio-freight/lenis@1.0.42/dist/lenis.umd.min.js"></script>
    <script src="js/script.js"></script>
</body>
</html>

