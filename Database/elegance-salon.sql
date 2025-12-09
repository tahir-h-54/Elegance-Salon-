-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 02, 2025 at 08:33 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `elegance-salon`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `appointment_id` int(11) NOT NULL,
  `client_id` int(11) DEFAULT NULL,
  `service_id` int(11) DEFAULT NULL,
  `stylist_id` int(11) DEFAULT NULL,
  `appointment_date` date DEFAULT NULL,
  `appointment_time` time DEFAULT NULL,
  `status` enum('booked','completed','cancelled') DEFAULT 'booked',
  `remarks` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`appointment_id`, `client_id`, `service_id`, `stylist_id`, `appointment_date`, `appointment_time`, `status`, `remarks`) VALUES
(1, 1, 2, 2, '2025-11-11', '14:00:00', 'completed', 'Balayage refresh before vacation.'),
(2, 3, 4, 3, '2025-11-21', '11:30:00', 'completed', 'HydraGlow facial, used sensitive line.'),
(3, 5, 5, 4, '2025-11-24', '16:00:00', 'completed', 'Spa mani‑pedi with neutral polish.'),
(4, 2, 8, 1, '2025-11-26', '10:00:00', 'completed', 'Men\'s grooming package, beard shaping.'),
(5, 4, 3, 2, '2025-12-04', '09:30:00', 'booked', 'Keratin smoothing before business trip.'),
(6, 6, 6, 3, '2025-12-06', '18:00:00', 'booked', 'Aromatherapy massage after work.'),
(7, 7, 7, 1, '2025-12-13', '13:00:00', 'booked', 'Bridal makeup trial for June wedding.'),
(8, 8, 1, 1, '2025-12-02', '15:30:00', 'booked', 'Signature haircut, prefers low fade.'),
(9, 1, 6, 3, '2025-11-29', '17:00:00', 'cancelled', 'Client unwell, will reschedule.'),
(10, 9, 8, 1, '2025-12-24', '16:00:00', 'booked', 'I have sensitive skin—use mild or hypoallergenic products.');

-- --------------------------------------------------------

--
-- Table structure for table `appointment_notifications`
--

CREATE TABLE `appointment_notifications` (
  `notification_id` int(11) NOT NULL,
  `appointment_id` int(11) DEFAULT NULL,
  `type` enum('email','sms','whatsapp') DEFAULT 'email',
  `status` enum('pending','sent') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointment_notifications`
--

INSERT INTO `appointment_notifications` (`notification_id`, `appointment_id`, `type`, `status`) VALUES
(1, 1, 'email', 'sent'),
(2, 2, 'email', 'sent'),
(3, 3, 'sms', 'sent'),
(4, 4, 'whatsapp', 'sent'),
(5, 5, 'email', 'pending'),
(6, 6, 'whatsapp', 'pending'),
(7, 7, 'email', 'pending'),
(8, 8, 'sms', 'pending'),
(9, 10, 'email', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `blog_posts`
--

CREATE TABLE `blog_posts` (
  `post_id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `slug` varchar(200) DEFAULT NULL,
  `excerpt` text DEFAULT NULL,
  `content` text DEFAULT NULL,
  `featured_image` varchar(255) DEFAULT NULL,
  `author_id` int(11) DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL,
  `tags` varchar(255) DEFAULT NULL,
  `status` enum('draft','published','archived') DEFAULT 'draft',
  `views` int(11) DEFAULT 0,
  `published_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `blog_posts`
--

INSERT INTO `blog_posts` (`post_id`, `title`, `slug`, `excerpt`, `content`, `featured_image`, `author_id`, `category`, `tags`, `status`, `views`, `published_at`, `created_at`, `updated_at`) VALUES
(1, 'Top 5 Hair Trends for This Season', 'top-5-hair-trends-this-season', 'From soft layers to curtain bangs, here are the cuts and colors our clients are loving right now.', 'Staying on top of current hair trends doesn\'t have to be overwhelming. At Elegance Salon, we\'re seeing a big shift towards effortless, lived‑in texture, combined with low‑maintenance color like balayage and face‑framing highlights. In this article we break down five client‑favorite looks and how to style them at home...', 'images/Blogs/top-trending-hair-cuts.jpg', 1, 'Hair', 'hair, trends, balayage', 'published', 127, '2025-11-06 19:53:18', '2025-12-01 14:53:18', '2025-12-01 14:55:35'),
(2, 'How to Build a Simple At‑Home Skincare Ritual', 'how-to-build-a-simple-at-home-skincare-ritual', 'A step‑by‑step evening routine that keeps your skin glowing between facial appointments.', 'Your daily skincare ritual doesn\'t need ten different steps. Focus on a few high‑quality products that cleanse, treat and protect your skin barrier. Our therapists recommend starting with a gentle cleanser, followed by a hydrating serum, eye cream and moisturizer. Once or twice a week, add a professional‑grade exfoliant to keep your complexion smooth and bright...', 'images/Blogs/skincare-rituals.png', 1, 'Skincare', 'skincare, facial, glow', 'published', 91, '2025-11-13 19:53:18', '2025-12-01 14:53:18', '2025-12-01 15:12:17'),
(3, 'Why Regular Massage Is More Than a Luxury', 'why-regular-massage-is-more-than-a-luxury', 'From posture to stress levels, massages support your health in more ways than one.', 'We often think of spa massages as a once‑a‑year treat, but your body benefits most when touch therapy is part of your regular self‑care. Massage improves circulation, eases muscular tension from desk work, and can even improve sleep quality. Our Aromatherapy Body Massage is designed to calm your nervous system while targeting your most tense areas...', 'images/Blogs/spa-facials.png', 1, 'Wellness', 'massage, wellness, stress', 'published', 64, '2025-11-20 19:53:18', '2025-12-01 14:53:18', '2025-12-01 14:53:18'),
(4, 'Bridal Beauty Timeline: When to Book What', 'bridal-beauty-timeline-when-to-book-what', 'A complete six‑month bridal beauty checklist so you feel calm and camera‑ready.', 'From engagement photos to the wedding day itself, there are a lot of beauty appointments to plan. We recommend starting facials at least six months ahead, hair trials three months out, and final color and treatments 1–2 weeks before the big day. This guide walks you through each step so nothing gets missed...', 'images/Blogs/hair-coloring-myths.png', 1, 'Bridal', 'bridal, wedding, makeup', 'published', 47, '2025-11-26 19:53:18', '2025-12-01 14:53:18', '2025-12-01 14:53:18');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cart_id` int(11) NOT NULL,
  `client_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE `clients` (
  `client_id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `preferences` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` (`client_id`, `name`, `phone`, `email`, `address`, `preferences`, `created_at`) VALUES
(1, 'Sophia Martinez', '+1 (555) 201‑4489', 'sophia.martinez@example.com', '12 Bloom Street, Greenview', 'Prefers late afternoon hair appointments, loves balayage.', '2025-12-01 11:55:23'),
(2, 'Ethan Walker', '+1 (555) 772‑9102', 'ethan.walker@example.com', '27 Kingsway Ave, Rivertown', 'Men\'s grooming, beard trim every 3 weeks.', '2025-12-01 11:55:23'),
(3, 'Amelia Johnson', '+1 (555) 983‑2214', 'amelia.j@example.com', '89 Maple Crescent, Lakeside', 'Facials every month, sensitive skin products only.', '2025-12-01 11:55:23'),
(4, 'Noah Patel', '+1 (555) 412‑6655', 'noah.patel@example.com', '44 Westfield Road, Midtown', 'Keratin treatment twice a year, prefers Saturday mornings.', '2025-12-01 11:55:23'),
(5, 'Isabella Rossi', '+1 (555) 640‑1099', 'isabella.rossi@example.com', '3 Garden Lane, Old Town', 'Spa manicure & pedicure before events.', '2025-12-01 11:55:23'),
(6, 'Liam Anderson', '+1 (555) 302‑7788', 'liam.anderson@example.com', '210 Sunset Blvd, Seaview', 'Relaxing massages after work, prefers quiet room.', '2025-12-01 11:55:23'),
(7, 'Olivia Brown', '+1 (555) 690‑3344', 'olivia.brown@example.com', '78 Orchard Park, Hillside', 'Bridal package interest, trial makeup and hair.', '2025-12-01 11:55:23'),
(8, 'Mason Lee', '+1 (555) 229‑4477', 'mason.lee@example.com', '15 Harbor Street, Bayview', 'Walk‑in haircuts, short waiting time preferred.', '2025-12-01 11:55:23'),
(9, 'Lina Matthews', '0332255668', 'lina.matthews01@yahoo.com', NULL, NULL, '2025-12-02 15:43:23'),
(10, 'Mia Anderson', '+1 325-449-8821', 'mia.anderson23@gmail.com', NULL, NULL, '2025-12-02 16:14:36');

-- --------------------------------------------------------

--
-- Table structure for table `client_login`
--

CREATE TABLE `client_login` (
  `login_id` int(11) NOT NULL,
  `client_id` int(11) DEFAULT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_login` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `client_login`
--

INSERT INTO `client_login` (`login_id`, `client_id`, `email`, `password`, `created_at`, `last_login`) VALUES
(1, 10, 'mia.anderson23@gmail.com', '$2y$10$H4gLRyc8WlG226sUFNwjw.DukCuRnGlHACUsQnxBbCm1FBjmTUSyS', '2025-12-02 16:14:36', '2025-12-02 16:14:51');

-- --------------------------------------------------------

--
-- Table structure for table `client_memberships`
--

CREATE TABLE `client_memberships` (
  `subscription_id` int(11) NOT NULL,
  `client_id` int(11) DEFAULT NULL,
  `membership_id` int(11) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `status` enum('active','expired','cancelled') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `discounts`
--

CREATE TABLE `discounts` (
  `discount_id` int(11) NOT NULL,
  `code` varchar(50) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `discount_type` enum('percentage','fixed') DEFAULT 'percentage',
  `discount_value` decimal(10,2) DEFAULT NULL,
  `min_purchase` decimal(10,2) DEFAULT 0.00,
  `max_discount` decimal(10,2) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `usage_limit` int(11) DEFAULT NULL,
  `used_count` int(11) DEFAULT 0,
  `status` enum('active','inactive','expired') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `discounts`
--

INSERT INTO `discounts` (`discount_id`, `code`, `name`, `description`, `discount_type`, `discount_value`, `min_purchase`, `max_discount`, `start_date`, `end_date`, `usage_limit`, `used_count`, `status`, `created_at`) VALUES
(1, 'WELCOME15', 'Welcome Offer 15%', '15% off on your first service booking at Elegance Salon.', 'percentage', 15.00, 0.00, 40.00, '2025-11-26', '2026-01-30', 200, 18, 'active', '2025-12-01 17:49:18'),
(2, 'GLOWUP25', 'Glow Up Package', '25% off HydraGlow Facial or Spa Facial sessions.', 'percentage', 25.00, 60.00, 80.00, '2025-11-21', '2025-12-31', 100, 9, 'active', '2025-12-01 17:49:18'),
(3, 'MIDWEEK10', 'Midweek Calm', 'Flat $10 off appointments booked for Tuesday–Thursday.', 'fixed', 10.00, 40.00, NULL, '2025-11-29', '2026-01-15', NULL, 3, 'active', '2025-12-01 17:49:18'),
(4, 'BRIDAL30', 'Bridal Bliss 30%', 'Save 30% on bridal packages when booked at least 30 days in advance.', 'percentage', 30.00, 200.00, 150.00, '2025-11-11', '2026-03-01', 50, 4, 'active', '2025-12-01 17:49:18'),
(5, 'HAIRLOVE20', 'Hair Love 20%', '20% off all hair color and keratin treatments.', 'percentage', 20.00, 80.00, 120.00, '2025-11-01', '2025-12-16', 150, 47, 'active', '2025-12-01 17:49:18'),
(6, 'SUMMERENDED', 'Summer Campaign (Expired)', 'Old summer promo for reference/testing of expired logic.', 'percentage', 20.00, 50.00, 90.00, '2025-08-03', '2025-11-01', 300, 260, 'expired', '2025-12-01 17:49:18');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `feedback_id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gallery`
--

CREATE TABLE `gallery` (
  `gallery_id` int(11) NOT NULL,
  `title` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL,
  `is_featured` tinyint(1) DEFAULT 0,
  `display_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gallery`
--

INSERT INTO `gallery` (`gallery_id`, `title`, `description`, `image_path`, `category`, `is_featured`, `display_order`, `created_at`) VALUES
(1, 'Soft Copper Balayage', 'Dimensional copper balayage with feathered layers for Sophia.', 'images/Services/laser-pigmentation-removal.png', 'Hair', 1, 1, '2025-12-01 12:00:26'),
(2, 'HydraGlow Facial Room', 'Our calming facial room prepared for an evening of treatments.', 'images/About/about-img.jpg', 'Skincare', 1, 2, '2025-12-01 12:00:26'),
(3, 'Bridal Updo Perfection', 'Elegant low bun with soft curls and fresh flowers for a spring bride.', 'images/About/sophia.png', 'Bridal', 1, 3, '2025-12-01 12:00:26'),
(4, 'Spa Manicure Station', 'Cozy manicure corner ready for a full Saturday of bookings.', 'images/Services/manicure.png', 'Nails', 0, 4, '2025-12-01 12:00:26'),
(5, 'Relaxation Massage', 'Aromatherapy massage setup with warm lighting and oils.', 'images/Services/massage.png', 'Spa', 0, 5, '2025-12-01 12:00:26');

-- --------------------------------------------------------

--
-- Table structure for table `gift_cards`
--

CREATE TABLE `gift_cards` (
  `gift_card_id` int(11) NOT NULL,
  `code` varchar(50) NOT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `balance` decimal(10,2) DEFAULT NULL,
  `purchased_by` int(11) DEFAULT NULL,
  `recipient_name` varchar(100) DEFAULT NULL,
  `recipient_email` varchar(150) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `status` enum('active','used','expired') DEFAULT 'active',
  `expiry_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gift_cards`
--

INSERT INTO `gift_cards` (`gift_card_id`, `code`, `amount`, `balance`, `purchased_by`, `recipient_name`, `recipient_email`, `message`, `status`, `expiry_date`, `created_at`) VALUES
(1, 'GC-SPRING25', 25.00, 25.00, 1, 'Emily Rose', 'emily.rose@example.com', 'Happy birthday! Enjoy a little pampering on me. – Sophia', 'active', '2026-09-01', '2025-12-01 11:58:31'),
(2, 'GC-BRIDAL150', 150.00, 80.00, 7, 'Laura King', 'laura.king@example.com', 'For all your bridal beauty appointments. Love, Olivia.', 'active', '2026-05-01', '2025-12-01 11:58:31');

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `item_id` int(11) NOT NULL,
  `item_name` varchar(100) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `reorder_level` int(11) DEFAULT NULL,
  `cost_price` decimal(10,2) DEFAULT NULL,
  `supplier_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `invoice_id` int(11) NOT NULL,
  `appointment_id` int(11) DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `generated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`invoice_id`, `appointment_id`, `total_amount`, `generated_at`) VALUES
(1, 1, 180.00, '2025-11-11 19:00:00'),
(2, 2, 95.00, '2025-11-21 19:00:00'),
(3, 3, 70.00, '2025-11-24 19:00:00'),
(4, 4, 55.00, '2025-11-26 19:00:00'),
(5, 5, 220.00, '2025-11-16 19:00:00'),
(6, 6, 80.00, '2025-11-19 19:00:00'),
(7, 7, 120.00, '2025-11-23 19:00:00'),
(8, 8, 45.00, '2025-11-28 19:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `memberships`
--

CREATE TABLE `memberships` (
  `membership_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `duration_days` int(11) DEFAULT NULL,
  `benefits` text DEFAULT NULL,
  `discount_percentage` decimal(5,2) DEFAULT 0.00,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `memberships`
--

INSERT INTO `memberships` (`membership_id`, `name`, `description`, `price`, `duration_days`, `benefits`, `discount_percentage`, `status`, `created_at`) VALUES
(1, 'Glow Monthly', 'Perfect for regular self‑care lovers with facials and manicures included.', 59.00, 30, '1 HydraGlow facial per month\nPriority booking\nComplimentary brow shaping', 5.00, 'active', '2025-12-01 11:57:45'),
(2, 'Radiance Quarterly', 'Quarterly package ideal for color and treatment maintenance.', 159.00, 90, '1 balayage refresh or keratin top‑up\n2 spa manicure & pedicure sessions\nFree product samples', 10.00, 'active', '2025-12-01 11:57:45'),
(3, 'Bridal Journey', 'Premium bridal preparation membership for 6 months before the big day.', 399.00, 180, '2 bridal makeup trials\n3 facials\n2 hair trials\nExclusive bridal discounts', 15.00, 'active', '2025-12-01 11:57:45');

-- --------------------------------------------------------

--
-- Table structure for table `notification_settings`
--

CREATE TABLE `notification_settings` (
  `setting_id` int(11) NOT NULL,
  `notification_type` enum('email','sms','whatsapp') NOT NULL,
  `whatsapp_api_key` varchar(255) DEFAULT NULL,
  `whatsapp_api_url` varchar(255) DEFAULT NULL,
  `whatsapp_phone_number` varchar(20) DEFAULT NULL,
  `is_enabled` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notification_settings`
--

INSERT INTO `notification_settings` (`setting_id`, `notification_type`, `whatsapp_api_key`, `whatsapp_api_url`, `whatsapp_phone_number`, `is_enabled`, `created_at`, `updated_at`) VALUES
(1, 'whatsapp', NULL, NULL, NULL, 0, '2025-12-01 10:54:40', '2025-12-01 10:54:40');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `client_id` int(11) DEFAULT NULL,
  `order_number` varchar(50) DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `discount_code` varchar(50) DEFAULT NULL,
  `discount_amount` decimal(10,2) DEFAULT 0.00,
  `final_amount` decimal(10,2) DEFAULT NULL,
  `status` enum('pending','processing','shipped','delivered','cancelled') DEFAULT 'pending',
  `shipping_address` text DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `payment_status` enum('pending','paid','failed') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `item_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `subtotal` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `payment_id` int(11) NOT NULL,
  `appointment_id` int(11) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `payment_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`payment_id`, `appointment_id`, `amount`, `payment_method`, `payment_date`) VALUES
(1, 1, 180.00, 'Credit Card', '2025-11-11 19:00:00'),
(2, 2, 95.00, 'Cash', '2025-11-21 19:00:00'),
(3, 3, 70.00, 'Debit Card', '2025-11-24 19:00:00'),
(4, 4, 55.00, 'Credit Card', '2025-11-26 19:00:00'),
(5, 5, 220.00, 'Credit Card', '2025-11-16 19:00:00'),
(6, 6, 80.00, 'Cash', '2025-11-19 19:00:00'),
(7, 7, 120.00, 'Credit Card', '2025-11-23 19:00:00'),
(8, 8, 45.00, 'Debit Card', '2025-11-28 19:00:00'),
(9, 9, 50.00, 'Credit Card', '2025-11-30 19:00:00'),
(10, 10, 180.00, 'Credit Card', '2025-11-29 19:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `sale_price` decimal(10,2) DEFAULT NULL,
  `stock_quantity` int(11) DEFAULT 0,
  `sku` varchar(50) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive','out_of_stock') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `name`, `description`, `category`, `price`, `sale_price`, `stock_quantity`, `sku`, `image_path`, `status`, `created_at`) VALUES
(1, 'Nail Polish Remover', 'Gentle acetone-based remover for quick and effective nail polish removal.', 'Manicure → Removers', 5.99, 4.99, 10, 'NPR-001', 'images/images.jfif', 'active', '2025-12-01 11:12:03'),
(2, 'Silk Repair Shampoo', 'Sulfate‑free shampoo that gently cleanses while restoring softness and shine.', 'Haircare', 24.99, NULL, 32, 'ES-SH-001', 'images/e3f27f701d467c0ba4656df6a28432e9.jpg', 'active', '2025-12-01 11:59:35'),
(3, 'Silk Repair Conditioner', 'Lightweight conditioner that detangles and smooths without weighing hair down.', 'Haircare', 26.99, 21.99, 18, 'ES-CD-002', 'images/e3f27f701d467c0ba4656df6a28432e9.jpg', 'active', '2025-12-01 11:59:35'),
(4, 'Argan Finishing Oil', 'Nourishing argan oil serum to tame frizz and add mirror‑like shine.', 'Styling', 32.50, NULL, 10, 'ES-OI-003', 'images/e3f27f701d467c0ba4656df6a28432e9.jpg', 'active', '2025-12-01 11:59:35'),
(5, 'HydraGlow Face Serum', 'Vitamin‑rich hydrating serum that plumps and brightens dull skin.', 'Skincare', 39.00, 34.00, 6, 'ES-SR-004', 'images/e3f27f701d467c0ba4656df6a28432e9.jpg', 'active', '2025-12-01 11:59:35'),
(6, 'Spa Hand Cream', 'Rich, non‑greasy cream to deeply moisturize hands and cuticles.', 'Bodycare', 14.99, NULL, 22, 'ES-HC-005', 'images/e3f27f701d467c0ba4656df6a28432e9.jpg', 'active', '2025-12-01 11:59:35'),
(7, 'Volumizing Dry Shampoo', 'Instant refresh between washes with added volume at the roots.', 'Styling', 19.50, NULL, 0, 'ES-DS-006', 'images/e3f27f701d467c0ba4656df6a28432e9.jpg', 'out_of_stock', '2025-12-01 11:59:35');

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `image_id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `is_primary` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_images`
--

INSERT INTO `product_images` (`image_id`, `product_id`, `image_path`, `is_primary`, `created_at`) VALUES
(1, 1, 'images/e3f27f701d467c0ba4656df6a28432e9.jpg', 1, '2025-12-01 11:59:54'),
(2, 2, 'images/e3f27f701d467c0ba4656df6a28432e9.jpg', 1, '2025-12-01 11:59:54'),
(3, 3, 'images/e3f27f701d467c0ba4656df6a28432e9.jpg', 1, '2025-12-01 11:59:54'),
(4, 4, 'images/e3f27f701d467c0ba4656df6a28432e9.jpg', 1, '2025-12-01 11:59:54'),
(5, 5, 'images/e3f27f701d467c0ba4656df6a28432e9.jpg', 1, '2025-12-01 11:59:54'),
(6, 6, 'images/e3f27f701d467c0ba4656df6a28432e9.jpg', 1, '2025-12-01 11:59:54');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_orders`
--

CREATE TABLE `purchase_orders` (
  `order_id` int(11) NOT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `status` enum('pending','ordered','received') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reports_cache`
--

CREATE TABLE `reports_cache` (
  `report_id` int(11) NOT NULL,
  `type` varchar(100) DEFAULT NULL,
  `data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`data`)),
  `generated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `role_id` int(11) NOT NULL,
  `role_name` varchar(50) NOT NULL,
  `permissions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`permissions`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`role_id`, `role_name`, `permissions`) VALUES
(1, 'Admin', '{\"users\":\"all\", \"clients\":\"all\",\"services\":\"all\",\"staff\":\"all\",\"appointments\":\"all\",\"appointments_notifications\":\"all\",\"staff_schedule\":\"all\",\"suppliers\":\"all\",\"inventory\":\"all\",\"purchase_orders\":\"all\",\"invoicess\":\"all\",\"payments\":\"all\",\"feedback\":\"all\",\"reports_cache\":\"all\",\"settings\":\"all\"}'),
(2, 'Receptionist', '{\"appointments\":\"read,write\",\"clients\":\"read,write\"}'),
(3, 'Stylist', '{\"appointments\":\"read\"}');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `service_id` int(11) NOT NULL,
  `service_name` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `duration` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`service_id`, `service_name`, `description`, `duration`, `price`) VALUES
(1, 'Menicure', 'Cleaning and Polish Removal: Old nail polish is removed, and the nails and hands are cleaned.\r\nShaping and Filing: Nails are trimmed and filed into a desired shape (e.g., square, oval, almond).\r\nCuticle Care: Cuticles are softened, pushed back, and tidied to create a neat nail bed.', 30, 1800.00),
(2, 'Signature Haircut', 'Customized haircut with professional consultation, relaxing shampoo and blow‑dry finish.', 45, 45.00),
(3, 'Balayage & Color Blend', 'Sun‑kissed balayage with root melt and gloss for a seamless, low‑maintenance finish.', 150, 180.00),
(4, 'Keratin Smoothing Treatment', 'Frizz‑control smoothing treatment that leaves hair shiny, smooth and manageable for up to 12 weeks.', 180, 220.00),
(5, 'HydraGlow Facial', 'Deep‑cleansing, exfoliating and hydrating facial tailored to your skin type.', 75, 95.00),
(6, 'Spa Manicure & Pedicure', 'Nail shaping, cuticle care, exfoliation, massage and long‑wear polish for hands and feet.', 90, 70.00),
(7, 'Aromatherapy Body Massage', 'Full‑body massage using warm aromatic oils to relieve stress and muscle tension.', 60, 80.00),
(8, 'Bridal Makeup Trial', 'Full bridal makeup trial including consultation, skin prep and lash application.', 90, 120.00),
(9, 'Men\'s Grooming Package', 'Classic haircut, beard detailing and hot towel treatment for gentlemen.', 60, 55.00);

-- --------------------------------------------------------

--
-- Table structure for table `service_images`
--

CREATE TABLE `service_images` (
  `image_id` int(11) NOT NULL,
  `service_id` int(11) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `is_primary` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `service_images`
--

INSERT INTO `service_images` (`image_id`, `service_id`, `image_path`, `is_primary`, `created_at`) VALUES
(1, 1, 'images/Services/massage.png', 1, '2025-12-01 11:54:47'),
(2, 2, 'images/Services/laser-pigmentation-removal.png', 1, '2025-12-01 11:54:47'),
(3, 3, 'images/Services/facial-skin-care.png', 1, '2025-12-01 11:54:47'),
(4, 4, 'images/Services/facial-skin-care.png', 1, '2025-12-01 11:54:47'),
(5, 5, 'images/Services/manicure.png', 1, '2025-12-01 11:54:47'),
(6, 6, 'images/Services/massage.png', 1, '2025-12-01 11:54:47'),
(7, 7, 'images/Services/lip-augmentation.png', 1, '2025-12-01 11:54:47'),
(8, 8, 'images/Services/depilation.png', 1, '2025-12-01 11:54:47');

-- --------------------------------------------------------

--
-- Table structure for table `service_reviews`
--

CREATE TABLE `service_reviews` (
  `review_id` int(11) NOT NULL,
  `service_id` int(11) DEFAULT NULL,
  `client_id` int(11) DEFAULT NULL,
  `appointment_id` int(11) DEFAULT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` >= 1 and `rating` <= 5),
  `review_text` text DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `service_reviews`
--

INSERT INTO `service_reviews` (`review_id`, `service_id`, `client_id`, `appointment_id`, `rating`, `review_text`, `status`, `created_at`) VALUES
(1, 2, 1, 1, 5, 'Absolutely obsessed with my new balayage! The blend is so soft and grows out beautifully.', 'approved', '2025-12-01 14:56:40'),
(2, 4, 3, 2, 5, 'My skin has never felt this clean and hydrated. The therapist explained every step.', 'approved', '2025-12-01 14:56:40'),
(3, 5, 5, 3, 4, 'Loved the spa atmosphere and massage chair. Polish lasted over two weeks.', 'approved', '2025-12-01 14:56:40'),
(4, 8, 2, 4, 5, 'Best men\'s cut I\'ve had in years. Quick but very detailed, and the beard line is perfect.', 'approved', '2025-12-01 14:56:40');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `setting_id` int(11) NOT NULL,
  `key` varchar(100) DEFAULT NULL,
  `value` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `staff_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `role` varchar(50) DEFAULT NULL,
  `commission_rate` decimal(5,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`staff_id`, `user_id`, `name`, `phone`, `email`, `role`, `commission_rate`) VALUES
(1, NULL, 'Elena Carter', '+1 (555) 111‑9023', 'elena.carter@elegancesalon.com', 'Senior Stylist', 18.50),
(2, NULL, 'Jasmine Nguyen', '+1 (555) 222‑7345', 'jasmine.nguyen@elegancesalon.com', 'Color Specialist', 20.00),
(3, NULL, 'Aiden Brooks', '+1 (555) 333‑8174', 'aiden.brooks@elegancesalon.com', 'Therapist', 15.00),
(4, NULL, 'Marina Lopez', '+1 (555) 444‑9832', 'marina.lopez@elegancesalon.com', 'Nail Artist', 16.00),
(5, NULL, 'Sophia Green', '+1 (555) 555‑6244', 'sophia.green@elegancesalon.com', 'Receptionist', 5.00);

-- --------------------------------------------------------

--
-- Table structure for table `staff_schedule`
--

CREATE TABLE `staff_schedule` (
  `schedule_id` int(11) NOT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `supplier_id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `address` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role_id` int(11) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `email`, `password`, `role_id`, `status`) VALUES
(1, 'admin', 'admin@gmail.com', '$2y$10$tIQAywpAmCzV0KVi3auZIOmkjq8KuItSUsjlqrZLU8IjVuPhYuQQm', 1, 'active'),
(2, 'Ebad ur Rehman', 'ebad@gmail.com', '$2y$10$Uj3vzxUB4l1DNJDqmIGDrekeBHtJZn4j0/tFaAmGsNc87QS2gb/VC', 2, 'active');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`appointment_id`),
  ADD KEY `client_id` (`client_id`),
  ADD KEY `service_id` (`service_id`),
  ADD KEY `stylist_id` (`stylist_id`);

--
-- Indexes for table `appointment_notifications`
--
ALTER TABLE `appointment_notifications`
  ADD PRIMARY KEY (`notification_id`),
  ADD KEY `appointment_id` (`appointment_id`);

--
-- Indexes for table `blog_posts`
--
ALTER TABLE `blog_posts`
  ADD PRIMARY KEY (`post_id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `author_id` (`author_id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `client_id` (`client_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`client_id`);

--
-- Indexes for table `client_login`
--
ALTER TABLE `client_login`
  ADD PRIMARY KEY (`login_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `client_id` (`client_id`);

--
-- Indexes for table `client_memberships`
--
ALTER TABLE `client_memberships`
  ADD PRIMARY KEY (`subscription_id`),
  ADD KEY `client_id` (`client_id`),
  ADD KEY `membership_id` (`membership_id`);

--
-- Indexes for table `discounts`
--
ALTER TABLE `discounts`
  ADD PRIMARY KEY (`discount_id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`feedback_id`);

--
-- Indexes for table `gallery`
--
ALTER TABLE `gallery`
  ADD PRIMARY KEY (`gallery_id`);

--
-- Indexes for table `gift_cards`
--
ALTER TABLE `gift_cards`
  ADD PRIMARY KEY (`gift_card_id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `purchased_by` (`purchased_by`);

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`item_id`),
  ADD KEY `supplier_id` (`supplier_id`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`invoice_id`),
  ADD KEY `appointment_id` (`appointment_id`);

--
-- Indexes for table `memberships`
--
ALTER TABLE `memberships`
  ADD PRIMARY KEY (`membership_id`);

--
-- Indexes for table `notification_settings`
--
ALTER TABLE `notification_settings`
  ADD PRIMARY KEY (`setting_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD UNIQUE KEY `order_number` (`order_number`),
  ADD KEY `client_id` (`client_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`item_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `appointment_id` (`appointment_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD UNIQUE KEY `sku` (`sku`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`image_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `supplier_id` (`supplier_id`),
  ADD KEY `item_id` (`item_id`);

--
-- Indexes for table `reports_cache`
--
ALTER TABLE `reports_cache`
  ADD PRIMARY KEY (`report_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`role_id`),
  ADD UNIQUE KEY `role_name` (`role_name`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`service_id`);

--
-- Indexes for table `service_images`
--
ALTER TABLE `service_images`
  ADD PRIMARY KEY (`image_id`),
  ADD KEY `service_id` (`service_id`);

--
-- Indexes for table `service_reviews`
--
ALTER TABLE `service_reviews`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `service_id` (`service_id`),
  ADD KEY `client_id` (`client_id`),
  ADD KEY `appointment_id` (`appointment_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`setting_id`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`staff_id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indexes for table `staff_schedule`
--
ALTER TABLE `staff_schedule`
  ADD PRIMARY KEY (`schedule_id`),
  ADD KEY `staff_id` (`staff_id`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`supplier_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `role_id` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `appointment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `appointment_notifications`
--
ALTER TABLE `appointment_notifications`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `blog_posts`
--
ALTER TABLE `blog_posts`
  MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `clients`
--
ALTER TABLE `clients`
  MODIFY `client_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `client_login`
--
ALTER TABLE `client_login`
  MODIFY `login_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `client_memberships`
--
ALTER TABLE `client_memberships`
  MODIFY `subscription_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `discounts`
--
ALTER TABLE `discounts`
  MODIFY `discount_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `feedback_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gallery`
--
ALTER TABLE `gallery`
  MODIFY `gallery_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `gift_cards`
--
ALTER TABLE `gift_cards`
  MODIFY `gift_card_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `invoice_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `memberships`
--
ALTER TABLE `memberships`
  MODIFY `membership_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `notification_settings`
--
ALTER TABLE `notification_settings`
  MODIFY `setting_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `image_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reports_cache`
--
ALTER TABLE `reports_cache`
  MODIFY `report_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `service_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `service_images`
--
ALTER TABLE `service_images`
  MODIFY `image_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `service_reviews`
--
ALTER TABLE `service_reviews`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `setting_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `staff_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `staff_schedule`
--
ALTER TABLE `staff_schedule`
  MODIFY `schedule_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `supplier_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `clients` (`client_id`),
  ADD CONSTRAINT `appointments_ibfk_2` FOREIGN KEY (`service_id`) REFERENCES `services` (`service_id`),
  ADD CONSTRAINT `appointments_ibfk_3` FOREIGN KEY (`stylist_id`) REFERENCES `staff` (`staff_id`);

--
-- Constraints for table `appointment_notifications`
--
ALTER TABLE `appointment_notifications`
  ADD CONSTRAINT `appointment_notifications_ibfk_1` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`appointment_id`);

--
-- Constraints for table `blog_posts`
--
ALTER TABLE `blog_posts`
  ADD CONSTRAINT `blog_posts_ibfk_1` FOREIGN KEY (`author_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `clients` (`client_id`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Constraints for table `client_login`
--
ALTER TABLE `client_login`
  ADD CONSTRAINT `client_login_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `clients` (`client_id`) ON DELETE CASCADE;

--
-- Constraints for table `client_memberships`
--
ALTER TABLE `client_memberships`
  ADD CONSTRAINT `client_memberships_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `clients` (`client_id`),
  ADD CONSTRAINT `client_memberships_ibfk_2` FOREIGN KEY (`membership_id`) REFERENCES `memberships` (`membership_id`);

--
-- Constraints for table `gift_cards`
--
ALTER TABLE `gift_cards`
  ADD CONSTRAINT `gift_cards_ibfk_1` FOREIGN KEY (`purchased_by`) REFERENCES `clients` (`client_id`);

--
-- Constraints for table `inventory`
--
ALTER TABLE `inventory`
  ADD CONSTRAINT `inventory_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`supplier_id`);

--
-- Constraints for table `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `invoices_ibfk_1` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`appointment_id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `clients` (`client_id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`appointment_id`);

--
-- Constraints for table `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `product_images_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE;

--
-- Constraints for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  ADD CONSTRAINT `purchase_orders_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`supplier_id`),
  ADD CONSTRAINT `purchase_orders_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `inventory` (`item_id`);

--
-- Constraints for table `service_images`
--
ALTER TABLE `service_images`
  ADD CONSTRAINT `service_images_ibfk_1` FOREIGN KEY (`service_id`) REFERENCES `services` (`service_id`) ON DELETE CASCADE;

--
-- Constraints for table `service_reviews`
--
ALTER TABLE `service_reviews`
  ADD CONSTRAINT `service_reviews_ibfk_1` FOREIGN KEY (`service_id`) REFERENCES `services` (`service_id`),
  ADD CONSTRAINT `service_reviews_ibfk_2` FOREIGN KEY (`client_id`) REFERENCES `clients` (`client_id`),
  ADD CONSTRAINT `service_reviews_ibfk_3` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`appointment_id`);

--
-- Constraints for table `staff`
--
ALTER TABLE `staff`
  ADD CONSTRAINT `staff_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `staff_schedule`
--
ALTER TABLE `staff_schedule`
  ADD CONSTRAINT `staff_schedule_ibfk_1` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`staff_id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`role_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
