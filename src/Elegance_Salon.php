<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../css/style.css">
  <title>Elegance - Because you deserve beauty</title>
  <link rel="shortcut icon" href="../images/elegance-saloon-short-logo.png" type="image/png">
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body>


  <div class="loader">
    <img src="../images/elegance-saloon-logo-no-bg.png" alt="loader-img">
  </div>

  <div id="menu"
    class="fixed top-0 left-0 w-full h-screen text-3xl translate-y-[-100%] z-[999]">


    <div class="fixed w-[100%] h-[100vh] bg-[#cff752] justify-center flex items-center menu">
      <button class="fixed top-10 left-4 sm:left-20 text-[#000] text-[18px] cursor-pointer p-3 z-5" id="closeBtn">✕</button>
      <div class="text-[#000] md:flex grid justify-between w-[90%] menu-btn">
        <div class="grid gap-5">
          <button onclick="location.href='Elegance_Salon.php'" class="text-[12px] text-start">01<span class="font-classic md:text-[5vw] text-[8vw]"> HOME</span></button>
          <button onclick="location.href='../services.php'" class="text-[12px] text-start">02<span class="font-classic md:text-[5vw] text-[8vw]"> SERVICES</span></button>
          <button onclick="location.href='../book-appointment.php'" class="text-[12px] text-start">03<span class="font-classic md:text-[5vw] text-[8vw]"> BOOK APPOINTMENT</span></button>
          <button onclick="location.href='../gallery.php'" class="text-[12px] text-start">04<span class="font-classic md:text-[5vw] text-[8vw]"> GALLERY</span></button>
        </div>
        <div class="grid gap-5 md:pt-0 pt-4">
          <button onclick="location.href='../shop.php'" class="text-[12px] text-start">05<span class="text-stroke md:text-[5vw] text-[8vw] font-medium"> SHOP</span></button>
          <button onclick="location.href='../blog.php'" class="text-[12px] text-start">06<span class="text-stroke md:text-[5vw] text-[8vw] font-medium"> BLOG</span></button>
          <button onclick="location.href='../customer-login.php'" class="text-[12px] text-start">07<span class="text-stroke md:text-[5vw] text-[8vw] font-medium"> LOGIN</span></button>
          <button onclick="location.href='../contact.php'" class="text-[12px] text-start">08<span class="text-stroke md:text-[5vw] text-[8vw] font-medium"> CONTACT</span></button>
        </div>
      </div>
    </div>
  </div>

  <header class="flex justify-around lg:gap-40 fixed w-[100%] z-30">
    <div class="sm:flex hidden">
      <span class="p-2 my-10 lg:mr-40 w-25 text-[15px] justify-center items-center flex bg-none text-[#000] rounded-full border-[#000] border-1 hover:bg-[#000] hover:text-[#fff] cursor-pointer" id="menuBtn">MENU</span>
    </div>
    <div class="sm:w-50 w-40 flex select-none">
      <a href="Elegance_Salon.php"><img src="../images/elegance-saloon-logo-no-bg.png" alt="Elegance logo"></a>
    </div>
    <div class="sm:hidden w-10 flex my-10">
      <span id="menuBtnMobile"><img src="../images/hamburger.svg" alt="hamburger"></span>
    </div>
    <div class="sm:flex hidden gap-2">
      <a href="../contact.php" class="p-2 my-10 w-30 text-[15px] justify-center items-center flex bg-none text-[#000] rounded-full border-[#000] border-1 hover:bg-[#000] hover:text-[#fff] cursor-pointer">Contact Us</a>
      <a href="book-appointment.php" class="p-2 my-10 w-30 text-[15px] justify-center items-center flex bg-none text-[#000] rounded-full bg-[#CFF752] cursor-pointer">Book Now</a>
    </div>
  </header>

  <main>
    <div class="hero-wrapper">
      <section class="hero-section">
        <div class="w-[100%] h-[100vh] flex flex-col justify-center items-center">
          <div class="flex flex-col px-5 md:px-0">
            <div class="hero-text">
              <h4 class="text-[5vw] md:text-[2vw] hero-reveal">Styling Excellence</h4>
            </div>
            <div class="hero-text">
              <h1 style="font-family: 'ivymode';" class="md:text-[7vw] text-[15vw] md:leading-28 leading-15 hero-reveal">Your ideal expression of beauty</h1>
            </div>
          </div>
        </div>
        <div class="hero-images md:block hidden">
          <div class="hero-img">
            <img src="../images/hero-pic-1.png" alt="">
          </div>
          <div class="hero-img">
            <img src="../images/hero-pic-2.png" alt="">
          </div>
          <div class="hero-img">
            <img src="../images/hero-pic-3.png" alt="">
          </div>
          <div class="hero-img">
            <img src="../images/hero-pic-4.jpg" alt="">
          </div>
        </div>

      </section>
    </div>
    <section class="direction-section overflow-hidden relative flex h-[100vh]">
      <div class="w-[100%] h-[100vh] flex flex-col justify-center ml-8">
        <div class="direction-text">
          <h4 class="text-[5vw] md:text-[2vw] direction-text-reveal">Our Directions</h4>
        </div>
        <div class="direction-text w-[100%]">
          <h2 style="font-family: 'ivymode';" class="text-[2vw] md:text-[4.4vw] leading-[5vw] direction-text-reveal">
            The essence of refined
          </h2>
        </div>
        <div class="direction-text w-[100%]">
          <h2 style="font-family: 'ivymode';" class="text-[2vw] md:text-[4.4vw] leading-[5vw] direction-text-reveal">
            beauty: premium salon
          </h2>
        </div>
        <div class="direction-text w-[100%]">
          <h2 style="font-family: 'ivymode';" class="text-[2vw] md:text-[4.4vw] leading-[5vw] direction-text-reveal">
            services
          </h2>
        </div>
        <div class="direction-text">
          <p class="text-[#7F7F7F] direction-text-reveal">At Elegance Salon, we surround you with an</p>
        </div>
        <div class="direction-text">
          <p class="text-[#7F7F7F] direction-text-reveal">atmosphere of sophistication and comfort.</p>
        </div>
        <div class="direction-text">
          <p class="direction-text-reveal">Every detail from the touch of our specialists to the</p>
        </div>
        <div class="direction-text">
          <p class="direction-text-reveal">ambiance of our space</p>
        </div>
      </div>
      <div class="grid-cols-3 grid w-[120%] items-center gap-2 mr-5 relative service-cards overflow-hidden">
        <div>
          <div class="relative card">
            <img src="../images/Services/depilation.png" class="h-auto w-[90vw]" alt="">
            <div class="absolute bottom-3 w-full flex justify-center">
              <a href="" class="text-[12px] text-[#fff] py-2 px-5 text-center w-[90%] border border-[#fff] rounded-full hover:bg-[#CFF752] hover:border-[#CFF752] hover:text-[#000]">Depilation</a>
            </div>
          </div>
          <div class="relative card">
            <img src="../images/Services/facial-skin-care.png" class="h-auto w-[90vw]" alt="">
            <div class="absolute bottom-3 w-full flex justify-center">
              <a href="" class="text-[12px] text-[#fff] py-2 px-5 text-center w-[90%] border border-[#fff] rounded-full hover:bg-[#CFF752] hover:border-[#CFF752] hover:text-[#000]">Facial skin care</a>
            </div>
          </div>
        </div>
        <div>
          <div class="relative card">
            <img src="../images/Services/lip-augmentation.png" class="h-auto w-[90vw]" alt="">
            <div class="absolute bottom-3 w-full flex justify-center">
              <a href="" class="text-[12px] text-[#fff] py-2 px-5 text-center w-[90%] border border-[#fff] rounded-full hover:bg-[#CFF752] hover:border-[#CFF752] hover:text-[#000]">Lip augmentation</a>
            </div>
          </div>
          <div class="relative card">
            <img src="../images/Services/massage.png" class="h-auto w-[90vw]" alt="">
            <div class="absolute bottom-3 w-full flex justify-center">
              <a href="" class="text-[12px] text-[#fff] py-2 px-5 text-center w-[90%] border border-[#fff] rounded-full hover:bg-[#CFF752] hover:border-[#CFF752] hover:text-[#000]">Massage</a>
            </div>
          </div>
        </div>
        <div>
          <div class="relative card">
            <img src="../images/Services/laser-pigmentation-removal.png" class="h-auto w-[90vw]" alt="">
            <div class="absolute bottom-3 w-full flex justify-center">
              <a href="" class="text-[12px] text-[#fff] py-2 px-5 text-center w-[90%] border border-[#fff] rounded-full hover:bg-[#CFF752] hover:border-[#CFF752] hover:text-[#000]">Laser depigmentation</a>
            </div>
          </div>
          <div class="relative card">
            <img src="../images/Services/manicure.png" class="h-auto w-[90vw]" alt="">
            <div class="absolute bottom-3 w-full flex justify-center">
              <a href="" class="text-[12px] text-[#fff] py-2 px-5 text-center w-[90%] border border-[#fff] rounded-full hover:bg-[#CFF752] hover:border-[#CFF752] hover:text-[#000]">Manicure</a>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="review-section">
      <div class="w-[100%] h-full md:h-[100vh] flex justify-center items-center">
        <div>
          <div class="review-text">
            <h2 class="text-[90px] review-text-reveal w-[100%] text-center" style="font-family: 'ivymode';">OUR HAPPY CUSTOMERS</h2>
          </div>
          <div class="lg:flex md:grid-cols-2 md:grid gap-8 md:ml-0 ml-20">
            <div
              class="card-wrapper relative md:w-64 md:mb-0 mb-10 w-[90%] h-[410px] rounded-3xl overflow-hidden border border-black cursor-pointer">
              <!-- IMAGE + SLIDE WRAPPER -->
              <div
                class="img-area relative w-full h-80 overflow-hidden rounded-t-3xl">
                <img
                  src="../images/Review/person-1.jpg"
                  class="w-full h-full object-cover rounded-t-3xl"
                  alt="" />

                <!-- Slide -->
                <div
                  class="info-slide absolute bottom-0 left-0 w-full h-full bg-[#cff752] text-[#1b1b1b] p-4 flex flex-col justify-end">
                  <p
                    class="pb-16 text-[14px] font-normal leading-5"
                    style="word-spacing: 3px">
                    Elegance Salon is fantastic! The stylist understood exactly
                    what I wanted and delivered even better. The place feels
                    modern and calming, and I walked out with the best haircut
                    I've had in years.
                  </p>
                </div>
              </div>

              <!-- NAME + STARS -->
              <div class="p-2">
                <h4 class="text-[20px]">Emma Collins</h4>
                <p class="text-[13px] text-[#cff752]">
                  <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                </p>
              </div>
            </div>

            <div
              class="card-wrapper relative md:w-64 md:mb-0 mb-10 w-[90%] h-[410px] rounded-3xl overflow-hidden border border-black cursor-pointer">
              <!-- IMAGE + SLIDE WRAPPER -->
              <div
                class="img-area relative w-full h-80 overflow-hidden rounded-t-3xl">
                <img
                  src="../images/Review/person-2.jpg"
                  class="w-full h-full object-cover rounded-t-3xl"
                  alt="" />

                <!-- Slide -->
                <div
                  class="info-slide absolute bottom-0 left-0 w-full h-full bg-[#cff752] text-[#1b1b1b] p-4 flex flex-col justify-end">
                  <p
                    class="pb-16 text-[14px] font-normal leading-5"
                    style="word-spacing: 3px">
                    Great service and super friendly staff. I had a facial
                    treatment, and my skin felt amazing afterward. The only
                    downside was a short wait, but overall a lovely experience.
                  </p>
                </div>
              </div>

              <!-- NAME + STARS -->
              <div class="p-2">
                <h4 class="text-[20px]">Lily Andersen</h4>
                <p class="text-[13px] text-[#cff752]">
                  <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-regular fa-star"></i>
                </p>
              </div>
            </div>

            <div
              class="card-wrapper relative md:w-64 md:mb-0 mb-10 w-[90%] h-[410px] rounded-3xl overflow-hidden border border-black cursor-pointer">
              <!-- IMAGE + SLIDE WRAPPER -->
              <div
                class="img-area relative w-full h-80 overflow-hidden rounded-t-3xl">
                <img
                  src="../images/Review/person-3.jpg"
                  class="w-full h-full object-cover rounded-t-3xl"
                  alt="" />

                <!-- Slide -->
                <div
                  class="info-slide absolute bottom-0 left-0 w-full h-full bg-[#cff752] text-[#1b1b1b] p-4 flex flex-col justify-end">
                  <p
                    class="pb-20 text-[14px] font-normal leading-5"
                    style="word-spacing: 3px">
                    Absolutely wonderful! I booked a color refresh and the results
                    were stunning. The team is skilled, professional, and really
                    listens to what you want. I'll definitely be returning.
                  </p>
                </div>
              </div>

              <!-- NAME + STARS -->
              <div class="p-2">
                <h4 class="text-[20px]">Sofia Ramirez</h4>
                <p class="text-[13px] text-[#cff752]">
                  <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                </p>
              </div>
            </div>
            <div
              class="card-wrapper relative md:w-64 md:mb-0 mb-10 w-[90%] h-[410px] rounded-3xl overflow-hidden border border-black cursor-pointer">
              <!-- IMAGE + SLIDE WRAPPER -->
              <div
                class="img-area relative w-full h-80 overflow-hidden rounded-t-3xl">
                <img
                  src="../images/Review/person-4.jpg"
                  class="w-full h-full object-cover rounded-t-3xl"
                  alt="" />

                <!-- Slide -->
                <div
                  class="info-slide absolute bottom-0 left-0 w-full h-full bg-[#cff752] text-[#1b1b1b] p-4 flex flex-col justify-end">
                  <p
                    class="pb-12 text-[14px] font-normal leading-5"
                    style="word-spacing: 3px">
                    My experience at Elegance Salon was amazing! I booked a hair
                    spa and blowout, and the results were beautiful. The
                    atmosphere feels luxurious and relaxing, and the staff treated
                    me with so much care. I'll definitely be coming back.
                  </p>
                </div>
              </div>

              <!-- NAME + STARS -->
              <div class="p-2">
                <h4 class="text-[20px]">Hannah Lewis</h4>
                <p class="text-[13px] text-[#cff752]">
                  <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="contact-section relative flex">
      <div class="w-[100%] h-[100vh] flex flex-col justify-center ml-8">
        <div class="contact-text">
          <h4 class="text-[3vw] md:text-[2vw] contact-text-reveal">We will answer any of your questions</h4>
        </div>
        <div class="contact-text w-[100%]">
          <h2 style="font-family: 'ivymode';" class="text-[8vw] md:text-[6vw] leading-[10vw] contact-text-reveal">
            Sign up for a consultation
          </h2>
        </div>
        <div class="md:flex grid gap-5 justify-start">
          <div>
            <div class="contact-text">
              <p class="text-[#7F7F7F] md:text-[1vw] md:leading-[1.4vw] text-[2vw] contact-text-reveal">Our team is a close-knit group of professionals whose main</p>
            </div>
            <div class="contact-text">
              <p class="text-[#7F7F7F] md:text-[1vw] md:leading-[1.4vw] text-[2vw] contact-text-reveal">goal is to create an ideal atmosphere for each of our clients.</p>
            </div>
            <div class="contact-text">
              <p class="md:text-[1vw] text-[2vw] md:leading-[1.4vw] contact-text-reveal">We process every request with love and attention, striving to</p>
            </div>
            <div class="contact-text">
              <p class="md:text-[1vw] text-[2vw] md:leading-[1.4vw] contact-text-reveal">exceed expectations and give our guests confidence.</p>
            </div>
          </div>
          <form method="POST" action="submit-consultation.php" class="gap-2 grid">
            <div>
              <input name="name" required class="bg-[#F1F1F1] text-[12px] py-3 pl-4 w-[90vw] md:w-[30vw] rounded-md outline-none" type="text" placeholder="Name">
            </div>
            <div>
              <input name="surname" required class="bg-[#F1F1F1] text-[12px] py-3 pl-4 w-[90vw] md:w-[30vw] rounded-md outline-none" type="text" placeholder="Surname">
            </div>
            <div>
              <select name="procedure" class="text-[12px] bg-[#F1F1F1] py-3 pl-4 w-[90vw] md:w-[30vw] rounded-md outline-none text-[#787878]">
                <option disabled selected hidden>Select a procedure</option>
                <option value="Depilation" class="text-[#000]">Depilation</option>
                <option value="Facial skin care" class="text-[#000]">Facial skin care</option>
                <option value="Lip augmentation" class="text-[#000]">Lip augmentation</option>
                <option value="Massage" class="text-[#000]">Massage</option>
                <option value="Laser pigmentation removal" class="text-[#000]">Laser pigmentation removal</option>
                <option value="Manicure" class="text-[#000]">Manicure</option>
              </select>
            </div>
            <div>
              <textarea name="message" required class="text-[12px] bg-[#F1F1F1] pt-2 pb-25 h-auto pl-4 md:w-[30vw] w-[90vw] rounded-md resize-none outline-none" placeholder="Here you can ask  any question and indicate a convenient time"></textarea>
            </div>
            <div>
              <button type="submit" class="p-2 mt-5 md:ml-[31vw] w-[90%] md:w-[21vw] lg:text-[15px] text-[10px] justify-center items-center flex bg-none text-[#000] rounded-full bg-[#CFF752] cursor-pointer hover:bg-[#b8d946] transition">Sign up for the procedure</button>
            </div>
          </form>
        </div>
      </div>

    </section>

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


  </main>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@studio-freight/lenis@1.0.42/dist/lenis.umd.min.js"></script>

  <script src="../js/script.js"></script>

</body>

</html>