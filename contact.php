<?php
include 'Database/connect_to_db.php';

$success = "";
$error = "";

// Handle form submission
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_contact'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name'] ?? '');
    $email = mysqli_real_escape_string($conn, $_POST['email'] ?? '');
    $phone = mysqli_real_escape_string($conn, $_POST['phone'] ?? '');
    $message = mysqli_real_escape_string($conn, $_POST['message'] ?? '');
    
    if(empty($name) || empty($email) || empty($message)) {
        $error = "Name, email, and message are required fields.";
    } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } else {
        // Check if consultations table exists, if not use feedback table
        $table_check = mysqli_query($conn, "SHOW TABLES LIKE 'consultations'");
        
        // Prepare message with contact info
        $full_message = "Email: $email\n";
        if(!empty($phone)) {
            $full_message .= "Phone: $phone\n";
        }
        $full_message .= "\nMessage:\n$message";
        
        // Check if consultations table exists, if not use feedback table
        $table_check = mysqli_query($conn, "SHOW TABLES LIKE 'consultations'");
        
        if(mysqli_num_rows($table_check) > 0) {
            // Use consultations table
            $insert_query = "INSERT INTO consultations (name, procedure_type, message, status, created_at) 
                           VALUES ('$name', 'Contact Form', '$full_message', 'pending', NOW())";
        } else {
            // Use feedback table as fallback
            $insert_query = "INSERT INTO feedback (name, email, message, submitted_at) 
                           VALUES ('$name', '$email', '$full_message', NOW())";
        }
        
        if(mysqli_query($conn, $insert_query)) {
            $success = "Thank you for contacting us! We'll get back to you soon.";
            // Clear form data
            $name = $email = $phone = $message = "";
        } else {
            $error = "Failed to submit your message. Please try again.";
        }
    }
}

// Get contact information from settings table (if exists)
$contact_phone = "+1 (555) 123-4567";
$contact_email = "info@elegance-salon.com";
$contact_address = "123 Beauty Street, City";

$settings_query = "SELECT `key`, `value` FROM settings WHERE `key` IN ('contact_phone', 'contact_email', 'contact_address')";
$settings_result = mysqli_query($conn, $settings_query);
if($settings_result) {
    while($row = mysqli_fetch_assoc($settings_result)) {
        if($row['key'] == 'contact_phone') $contact_phone = $row['value'];
        if($row['key'] == 'contact_email') $contact_email = $row['value'];
        if($row['key'] == 'contact_address') $contact_address = $row['value'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
      integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="css/style.css" />
    <title>Contact Us - Elegance Salon</title>
    <link
      rel="shortcut icon"
      href="images/elegance-saloon-short-logo.png"
      type="image/png"
    />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  </head>
  <body>
    <div class="loader">
      <img src="images/elegance-saloon-logo-no-bg.png" alt="loader-img" />
    </div>

    <div
      id="menu"
      class="fixed top-0 left-0 w-full h-screen text-3xl translate-y-[-100%] z-[999]"
    >
      <div class="fixed w-[100%] h-[100vh] bg-[#cff752] justify-center flex items-center menu">
      <button class="fixed top-10 left-4 sm:left-20 text-[#000] text-[18px] cursor-pointer p-3 z-5" id="closeBtn">✕</button>
      <div class="text-[#000] md:flex grid justify-between w-[90%] menu-btn">
        <div class="grid gap-5">
          <button onclick="location.href='src/Elegance_Salon.php'" class="text-[12px] text-start">01<span class="font-classic md:text-[5vw] text-[8vw]"> HOME</span></button>
          <button onclick="location.href='services.php'" class="text-[12px] text-start">02<span class="font-classic md:text-[5vw] text-[8vw]"> SERVICES</span></button>
          <button onclick="location.href='book-appointment.php'" class="text-[12px] text-start">03<span class="font-classic md:text-[5vw] text-[8vw]"> BOOK APPOINTMENT</span></button>
          <button onclick="location.href='gallery.php'" class="text-[12px] text-start">04<span class="font-classic md:text-[5vw] text-[8vw]"> GALLERY</span></button>
        </div>
        <div class="grid gap-5 md:pt-0 pt-4">
          <button onclick="location.href='shop.php'" class="text-[12px] text-start">05<span class="text-stroke md:text-[5vw] text-[8vw] font-medium"> SHOP</span></button>
          <button onclick="location.href='blog.php'" class="text-[12px] text-start">06<span class="text-stroke md:text-[5vw] text-[8vw] font-medium"> BLOG</span></button>
          <button onclick="location.href='customer-login.php'" class="text-[12px] text-start">07<span class="text-stroke md:text-[5vw] text-[8vw] font-medium"> LOGIN</span></button>
          <button onclick="location.href='contact.php'" class="text-[12px] text-start">08<span class="text-stroke md:text-[5vw] text-[8vw] font-medium"> CONTACT</span></button>
        </div>
      </div>
    </div>
    </div>

    <header class="flex justify-around lg:gap-40 fixed w-[100%] z-30 bg-white/80 backdrop-blur-sm">
      <div class="sm:flex hidden">
        <span
          class="p-2 my-10 lg:mr-40 w-25 text-[15px] justify-center items-center flex bg-none text-[#000] rounded-full border-[#000] border-1 hover:bg-[#000] hover:text-[#fff] cursor-pointer"
          id="menuBtn"
          >MENU</span
        >
      </div>
      <div class="sm:w-50 w-40 flex select-none">
        <a href="src/Elegance_Salon.php"
          ><img src="images/elegance-saloon-logo-no-bg.png" alt="Elegance logo"
        /></a>
      </div>
      <div class="sm:hidden w-10 flex my-10">
        <span id="menuBtnMobile"
          ><img src="images/hamburger.svg" alt="hamburger"
        /></span>
      </div>
      <div class="sm:flex hidden gap-2">
        <a
          href="contact.php"
          class="p-2 my-10 w-30 text-[15px] justify-center items-center flex bg-none text-[#000] rounded-full border-[#000] border-1 hover:bg-[#000] hover:text-[#fff] cursor-pointer"
          >Contact Us</a
        >
        <a
          href="book-appointment.php"
          class="p-2 my-10 w-30 text-[15px] justify-center items-center flex bg-none text-[#000] rounded-full bg-[#CFF752] cursor-pointer"
          >Book Now</a
        >
      </div>
    </header>

    <div
      class="bg-gradient-to-br min-h-screen flex items-center justify-center p-6"
    >
      <div
        class="rounded-3xl border border-[#000] flex max-w-5xl w-full overflow-hidden bg-[#cff752] my-40"
      >
        <!-- Left Section -->
        <div class="w-full lg:w-[70%] p-10 bg-white border-r border-[#000]">
          <div class="contact-page-text">
            <h1
              class="text-[5vw] mb-3 contact-page-reveal"
              style="font-family: 'ivymode'"
            >
              GET IN TOUCH
            </h1>
          </div>

          <?php if($success): ?>
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
              <p><?php echo htmlspecialchars($success); ?></p>
            </div>
          <?php endif; ?>

          <?php if($error): ?>
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
              <p><?php echo htmlspecialchars($error); ?></p>
            </div>
          <?php endif; ?>

          <form method="POST" action="contact.php" class="space-y-3 contact-form">
            <input
              type="text"
              name="name"
              placeholder="Name"
              required
              value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>"
              class="contact-input w-[70%] border border-[#000] rounded-full p-3 pl-7 placeholder:text-[#000] focus:outline-none"
            />
            <input
              type="email"
              name="email"
              placeholder="Email"
              required
              value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
              class="contact-input w-[70%] border border-[#000] rounded-full p-3 pl-7 placeholder:text-[#000] focus:outline-none"
            />
            <input
              type="text"
              name="phone"
              placeholder="Phone number"
              value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>"
              class="contact-input w-[70%] border border-[#000] rounded-full p-3 pl-7 placeholder:text-[#000] focus:outline-none"
            />
            <textarea
              name="message"
              placeholder="Your message"
              required
              rows="4"
              class="contact-input w-[70%] border border-[#000] rounded-lg p-3 pl-7 placeholder:text-[#000] focus:outline-none resize-none"
            ><?php echo isset($_POST['message']) ? htmlspecialchars($_POST['message']) : ''; ?></textarea>

            <button
              type="submit"
              name="submit_contact"
              class="w-[70%] bg-[#cff752] text-[#000] py-3 rounded-full contact-btn my-7 hover:bg-[#b8d946] transition"
            >
              SEND
            </button>
          </form>

          <!-- Contact Info -->
          <div class="flex flex-col sm:flex-row items-start sm:items-center space-y-4 sm:space-y-0 sm:space-x-10 mt-10 text-sm text-gray-700">
            <div class="flex items-center space-x-2">
              <div>
                <p class="font-semibold">PHONE</p>
                <p><a href="tel:<?php echo htmlspecialchars($contact_phone); ?>" class="hover:text-[#CFF752] transition"><?php echo htmlspecialchars($contact_phone); ?></a></p>
              </div>
            </div>

            <div class="flex items-center space-x-2">
              <div>
                <p class="font-semibold">EMAIL</p>
                <p><a href="mailto:<?php echo htmlspecialchars($contact_email); ?>" class="hover:text-[#CFF752] transition"><?php echo htmlspecialchars($contact_email); ?></a></p>
              </div>
            </div>

            <div class="flex items-center space-x-2">
              <div>
                <p class="font-semibold">ADDRESS</p>
                <p><?php echo htmlspecialchars($contact_address); ?></p>
              </div>
            </div>
          </div>
        </div>

        <iframe
          src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3618.1581916056502!2d67.0927060746752!3d24.92668034260589!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3eb338caa5ac57cb%3A0x2c4b1fc512ab6bb!2sAptech%20Gulshan-e-Iqbal!5e0!3m2!1sen!2s!4v1764628869956!5m2!1sen!2s"
          width="400"
          height="450"
          style="
            border: 0;
            position: absolute;
            z-index: 20;
            margin-left: 42%;
            margin-top: 4%;
          "
          class="rounded-3xl hidden lg:block"
          allowfullscreen=""
          referrerpolicy="no-referrer-when-downgrade"
        ></iframe>
      </div>
    </div>

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

