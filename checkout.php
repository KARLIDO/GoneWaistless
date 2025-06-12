<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require_once 'includes/config.php';


// Generate CSRF token
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if (empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit();
}

$cart_items = $_SESSION['cart'];
$subtotal = 0;

foreach ($cart_items as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}
$currentDay = date('w');
$days = [
    0 => 'Sunday Closed',
    1 => 'Monday 09:00 - 17:00',
    2 => 'Tuesday 09:00 - 17:00',
    3 => 'Wednesday 09:00 - 17:00',
    4 => 'Thursday 09:00 - 17:00',
    5 => 'Friday 09:00 - 17:00',
    6 => 'Saturday 09:00 - 13:00',
    7 => 'Sunday Closed',
    8 => 'Monday 09:00 - 17:00',
    9 => 'Tuesday 09:00 - 17:00',
    10 => 'Wednesday 09:00 - 17:00',
    11 => 'Thursday 09:00 - 17:00',
    12 => 'Friday 09:00 - 17:00',
    13 => 'Saturday 09:00 - 13:00'
];

$orderedDays = [];
for ($i = 0; $i < 14; $i++) {
    $dayIndex = ($currentDay + $i) % 7;
    $orderedDays[] = $days[$dayIndex];
}

$openingHoursText = implode(' • ', $orderedDays);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout | Gone Waistless</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="shortcut icon" href="assets/logogw.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Parisienne&family=Montserrat:wght@400;600&display=swap" rel="stylesheet" media="print" onload="this.media='all'">
     
    <!-- Load Font Awesome asynchronously -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" media="print" onload="this.media='all'">
   
    <style>
         body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }
        
        .top-bar {
            background-color: #383838;
            padding: 5px 0;
        }
        
        .top-bar-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 100%;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        .logo img {
            height: 80px;
            width: auto;
        }
        
        .social-icons a {
            margin-left: 15px;
            color: white;
            font-size: 20px;
        }
        
        .opening-hours {
            overflow: hidden;
            height: 30px;
            position: relative;
            width: 100%;
            max-width: 600px;
            color: white;
            line-height: 30px;
            margin: 0 10px;
        }

        .opening-hours-content {
            position: absolute;
            width: auto;
            white-space: nowrap;
            animation: scroll 20s linear infinite;
            padding: 0 20px;
            box-sizing: border-box;
            /* Start animation immediately with no delay */
            animation-delay: -0.1s;
        }

        @keyframes scroll {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }
        
        /* Navigation Bar */
        .nav-bar {
            background-color: #f557ab;
            color: white;
            padding: 5px 0;
            transition: all 0.3s ease;
        }
        
        .nav-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 100%;
            margin: 0 auto;
            padding: 0 20px;
        }

        .nav-links {
            display: flex;
            list-style: none;
            margin: 0;
            padding: 0;
        }
        .menu-toggle {
            display: none; /* Hidden by default on desktop */
        }
        .nav-links li {
            margin-right: 20px;
            position: relative;
        }
        
        .nav-links a {
            color: white;
            text-decoration: none;
            font-weight: 500;
            padding: 10px 0;
            display: block;
        }
        
        .nav-links a:hover {
            color: #ddd;
        }
        
        .nav-icons {
            display: flex;
            align-items: center;
        }
        
        .nav-icons a {
            margin-left: 15px;
            color: white;
            font-size: 20px;
        }
        
        /* Dropdown styles */
        .dropdown {
            position: relative;
            display: inline-block;
        }
        
        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
            right: 0;
        }

        /* Add this new rule */
        .dropdown-content.open {
            display: block;
        }
        .dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }
        
        .dropdown-content a:hover {
            background-color:rgb(202, 62, 144);
            color: white;
        }
        
        .dropdown:hover .dropdown-content {
            display: block;
        }

        /* Sticky Navigation Styles */
        .nav-bar.sticky {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            width: 100%;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            animation: slideDown 0.3s ease-out;
        }

        @keyframes slideDown {
            from { transform: translateY(-100%); }
            to { transform: translateY(0); }
        }

        /* Add margin to content when nav is sticky */
        .nav-bar.sticky + .banner-container {
            margin-top: 60px; /* Adjust to match your nav bar height */
        }
        
        /* Main content */
        .main-content {
            max-width: 1200px;
            margin: 20px auto;
            padding: 0 20px;
        }
        
        /* User Dropdown Styles */
        .user-dropdown {
            position: relative;
            margin-left: 15px;
        }

        .user-icon {
            color: white;
            font-size: 20px;
            padding: 8px 10px;
            border-radius: 50%;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
        }

        .user-icon:hover {
            background-color: rgba(255, 255, 255, 0.2);
            transform: scale(1.1);
        }

        .user-menu {
            z-index: 1001;
            min-width: 200px;
            border-radius: 8px;
            padding: 10px 0;
            right: 0;
            top: 100%;
            margin-top: 5px;
            border: 1px solid #e0e0e0;
            background-color: white;
            animation: fadeIn 0.2s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .user-menu.open {
            display: block;
        }

        .user-info {
            padding: 10px 15px;
            border-bottom: 1px solid #f0f0f0;
            margin-bottom: 5px;
            background-color: #f9f9f9;
            border-radius: 8px 8px 0 0;
        }

        .username {
            font-weight: 600;
            color: #333;
            font-size: 14px;
        }

        .menu-item {
            padding: 10px 15px;
            color: #555;
            font-size: 14px;
            display: flex;
            align-items: center;
            transition: all 0.2s ease;
        }

        .menu-item i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
            color: #777;
        }

        .menu-item:hover {
            background-color: #f5f5f5;
            color: #f557ab;
            text-decoration: none;
        }

        .menu-item:hover i {
            color: #f557ab;
        }

        .logout {
            color: #e74c3c;
        }

        .logout:hover {
            color: #c0392b;
            background-color: #fdeaea;
        }

        .logout i {
            color: #e74c3c;
        }

        /* Banner with Blurred Frame */
        .banner-container {
            position: relative;
            width: 100%;
            height: 80vh;
            max-height: 800px;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .blurred-frame {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
        }

        .blurred-bg {
            width: 100%;
            height: 100%;
            object-fit: cover;
            filter: blur(10px) brightness(0.8);
            transform: scale(1.05);
        }

        .main-image-container {
            position: relative;
            z-index: 2;
            max-width: 90%;
            max-height: 90%;
            /* Radial gradient mask for all four edges */
            mask-image: radial-gradient(
                ellipse at center,
                white 60%,
                transparent 100%
            );
            -webkit-mask-image: radial-gradient(
                ellipse at center,
                white 60%,
                transparent 100%
            );
            /* Soften the mask edges */
            mask-mode: alpha;
            -webkit-mask-mode: alpha;
        }

        .main-image {
            width: auto;
            height: auto;
            max-width: 100%;
            max-height: 80vh;
            display: block;
            margin: 0 auto;
            /* Subtle blur inset shadow on all sides */
            box-shadow: 
                inset 0 0 15px 10px rgba(0,0,0,0.15),
                /* Outer shadow for depth */
                0 0 30px rgba(0,0,0,0.3);
            /* Edge blur effect */
            filter: blur(0.5px);
            /* Counteract blur in center */
            mask-image: radial-gradient(
                circle at center,
                white 70%,
                transparent 100%
            );
            -webkit-mask-image: radial-gradient(
                circle at center,
                white 70%,
                transparent 100%
            );
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .main-image-container {
                mask-image: radial-gradient(
                    ellipse at center,
                    white 50%,
                    transparent 100%
                );
                -webkit-mask-image: radial-gradient(
                    ellipse at center,
                    white 50%,
                    transparent 100%
                );
            }
            
            .main-image {
                mask-image: radial-gradient(
                    circle at center,
                    white 60%,
                    transparent 100%
                );
                -webkit-mask-image: radial-gradient(
                    circle at center,
                    white 60%,
                    transparent 100%
                );
            }
        }
        /* Banner Text Styles */
        @import url('https://fonts.googleapis.com/css2?family=Mr+Dafoe&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap');

        @import url('https://fonts.googleapis.com/css2?family=Parisienne&family=Montserrat:wght@400;600&display=swap');

        /* Banner Text Styles */
        .banner-text {
            position: absolute;
            z-index: 3;
            color: white;
            text-shadow: 0 0 10px rgba(0,0,0,0.5);
            padding: 20px;
            max-width: 25%;
            animation: fadeInUp 1s ease-out both;
        }

        .script-font {
            
            font-size: 2.8rem;
            margin: 0;
            line-height: 1.2;
            font-weight: normal;
            font-family: 'WaititesFont', 'Parisienne', cursive;
        }

        .banner-text h2 {
            font-family: 'Montserrat', sans-serif;
            font-size: 2rem;
            margin: 0;
            line-height: 1.3;
            font-weight: 600;
        }

        .left-text {
            left: 5%;
            top: 50%;
            transform: translateY(-50%);
            text-align: left;
            animation-delay: 0.5s;
        }

        .right-text {
            right: 5%;
            top: 50%;
            transform: translateY(-50%);
            text-align: right;
            animation-delay: 0.8s;
        }

        .shop-now-btn {
            background-color: #f557ab;
            color: white;
            border: none;
            padding: 12px 25px;
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            font-size: 1rem;
            border-radius: 30px;
            margin-top: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            animation: pulse 2s infinite;
        }

        .shop-now-btn:hover {
            background-color: #e04d99;
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.3);
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px) translateY(-50%);
            }
            to {
                opacity: 1;
                transform: translateY(0) translateY(-50%);
            }
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
            100% {
                transform: scale(1);
            }
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .banner-text {
                max-width: 35%;
            }
            
            .banner-text h2 {
                font-size: 1.8rem;
            }
            
            .shop-now-btn {
                padding: 10px 20px;
                font-size: 0.9rem;
            }
        }

        @media (max-width: 480px) {
            .banner-text {
                max-width: 40%;
            }
            
            .banner-text h2 {
                font-size: 1.5rem;
            }
            
            .left-text, .right-text {
                padding: 10px;
            }
        }
        @media (max-width: 768px) {
            .script-font {
                font-size: 2.2rem;
            }
            
            .banner-text h2 {
                font-size: 1.5rem;
            }
        }

        @media (max-width: 480px) {
            .script-font {
                font-size: 1.8rem;
            }
        }
        @font-face {
            font-family: 'WaititesFont';
            src: url('fonts/waitites-font.woff2') format('woff2'),
                url('fonts/waitites-font.woff') format('woff');
            font-weight: normal;
            font-style: normal;
        }

        /* Carousel Styles */
        /* Main Image Container Adjustments */
        .main-image-container {
            position: relative;
            z-index: 2;
            width: 100%;
            max-width: 90%;
            height: 80vh;
            max-height: 80vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Carousel Adjustments */
        .carousel {
            position: relative;
            width: 100%;
            height: 100%;
        }

        .carousel-inner {
            position: relative;
            width: 100%;
            height: 100%;
            overflow: hidden;
        }

        .carousel-item {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            transition: opacity 1s ease-in-out;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .carousel-item.active {
            opacity: 1;
            z-index: 1;
        }

        .main-image {
            width: auto;
            height: auto;
            max-width: 100%;
            max-height: 80vh;
            display: block;
            margin: 0 auto;
            box-shadow: 
                inset 0 0 15px 10px rgba(0,0,0,0.15),
                0 0 30px rgba(0,0,0,0.3);
            filter: blur(0.5px);
        }

        /* Remove conflicting mask effects */
        .main-image-container,
        .main-image {
            mask-image: none;
            -webkit-mask-image: none;
        }
        .carousel-control {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            z-index: 5;
            background: rgba(0,0,0,0.3);
            color: white;
            border: none;
            font-size: 2rem;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .carousel-control:hover {
            background: rgba(0,0,0,0.6);
        }

        .carousel-control.prev {
            left: 20px;
        }

        .carousel-control.next {
            right: 20px;
        }

        .carousel-indicators {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 5;
            display: flex;
            gap: 10px;
        }

        .indicator {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: rgba(255,255,255,0.5);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .indicator.active {
            background: white;
            transform: scale(1.2);
        }

        .indicator:hover {
            background: rgba(255,255,255,0.8);
        }
        /* Carousel Adjustments */
        .main-image-container .carousel {
            width: 100%;
            height: 100%;
            position: relative;
        }

        .main-image-container .carousel-inner {
            width: 100%;
            height: 100%;
            position: relative;
        }

        .main-image-container .carousel-item {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            transition: opacity 1s ease-in-out;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .main-image-container .carousel-item.active {
            opacity: 1;
            z-index: 1;
        }

        .main-image-container .carousel-item img {
            width: auto;
            height: auto;
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }
        @media (max-width: 768px) {
            /* Mobile menu styles */
            .nav-container {
                position: relative;
                flex-wrap: nowrap;
                justify-content: flex-start;
            }
            
            .nav-links {
                display: none;
                flex-direction: column;
                width: 100%;
                background-color: #f557ab;
                position: fixed;
                top: 60px; /* Adjust based on your nav bar height */
                left: 0;
                z-index: 1000;
                padding: 10px 0;
                box-shadow: 0 5px 10px rgba(0,0,0,0.1);
            }
            
            .nav-links.active {
                display: flex;
            }
            
            .nav-links li {
                margin: 0;
                padding: 10px 20px;
                border-top: 1px solid rgba(255,255,255,0.1);
            }
            
            .menu-toggle {
                display: block;
                background: none;
                border: none;
                color: white;
                font-size: 24px;
                cursor: pointer;
                padding: 10px;
                margin-right: 15px;
            }
            
            .nav-icons {
                margin-left: auto;
            }
            
            /* Adjust the dropdown position for mobile */
            .dropdown-content {
                position: static;
                width: 100%;
                box-shadow: none;
            }
            
            .menu-toggle i {
                transition: transform 0.3s ease;
            }
            
            .menu-toggle.active i {
                transform: rotate(90deg);
            }
            
            /* Remove blur effects for mobile */
            .blurred-bg {
                filter: none;
                transform: none;
            }
            
            .main-image {
                filter: none;
            }
            
            /* Adjust banner layout for mobile */
            .banner-container {
                height: auto;
                min-height: 80vh;
                flex-direction: column;
            }
            
            .main-image-container {
                width: 100%;
                max-width: 100%;
                height: auto;
                order: 1;
            }
            
            .main-image {
                max-height: none;
                width: 100%;
                height: auto;
            }
            
            .banner-text {
                position: relative;
                max-width: 100%;
                text-align: center;
                padding: 15px;
                transform: none;
                top: auto;
                left: auto;
                right: auto;
            }
            
            .left-text, .right-text {
                order: 2;
                text-align: center;
            }
            
            .shop-now-btn {
                margin: 10px auto;
            }
            
            .carousel-control {
                display: none;
            }
            
            .carousel-indicators {
                bottom: 10px;
            }
        }

        @media (max-width: 480px) {
            /* Additional small phone adjustments */
            .top-bar-container {
                flex-direction: column;
                padding: 10px;
            }
            
            .logo img {
                height: 60px;
                margin-bottom: 10px;
            }
            
            .opening-hours {
                width: 100%;
                margin: 10px 0;
            }
            
            .social-icons {
                margin-top: 10px;
            }
            
            .nav-container {
                padding: 5px;
            }
            
            .banner-text h2 {
                font-size: 1.3rem;
            }
            
            .script-font {
                font-size: 1.5rem;
            }
        }
        @media (min-width: 769px) {
            .nav-links {
                flex-grow: 1;
            }
            
            .nav-icons {
                margin-left: auto;
            }
        }
        /* Extra Small Devices (phones, 400px and down) */
        @media only screen and (max-width: 400px) {
        /* Top Bar Adjustments */
        .top-bar-container {
            flex-direction: column;
            padding: 5px;
        }
        
        .logo img {
            height: 50px;
            margin-bottom: 5px;
        }
        
        .opening-hours {
            width: 100%;
            font-size: 12px;
            height: 24px;
            line-height: 24px;
            margin: 5px 0;
        }
        
        .social-icons {
            margin: 0 8px;
            font-size: 16px;
        }
        
        /* Navigation Adjustments */
        .nav-container {
            padding: 2px 5px;
        }
        
        .menu-toggle {
            font-size: 20px;
            padding: 8px;
        }
        
        .nav-links {
            top: 50px; /* Adjust based on reduced nav height */
        }
        
        .nav-icons a {
            font-size: 16px;
            margin-left: 10px;
        }
        
        .user-icon {
            font-size: 16px;
            width: 30px;
            height: 30px;
        }
        
        /* Banner Adjustments */
        .banner-container {
            min-height: 70vh;
        }
        
        .banner-text {
            padding: 10px;
        }
        
        .script-font {
            font-size: 1.3rem;
        }
        
        .banner-text h2 {
            font-size: 1.1rem;
        }
        
        .shop-now-btn {
            padding: 8px 15px;
            font-size: 0.8rem;
            margin-top: 10px;
        }
        
        /* Carousel Adjustments */
        .carousel-indicators {
            bottom: 5px;
        }
        
        .indicator {
            width: 8px;
            height: 8px;
        }
        
        /* Opening Hours Animation */
        .opening-hours-content {
            animation: scroll 25s linear infinite;
        }
        
        /* User Dropdown */
        .user-menu {
            min-width: 160px;
        }
        
        .menu-item {
            padding: 8px 10px;
            font-size: 12px;
        }
        
        /* Hide some decorative elements on very small screens */
        .dropdown-content.mobile-open {
            display: block;
        }
        }
        @media (min-width: 769px) {
            .dropdown:hover .dropdown-content {
                display: block;
            }
        }
        /* Super Small Devices (phones, 320px and down) */
        @media only screen and (max-width: 320px) {
        .script-font {
            font-size: 1.1rem;
        }
        
        .banner-text h2 {
            font-size: 0.9rem;
        }
        
        .shop-now-btn {
            padding: 6px 12px;
            font-size: 0.7rem;
        }
        
        .nav-links li {
            padding: 8px 15px;
        }
        
        .logo img {
            height: 40px;
        }
        }
        @media (max-width: 768px) {
            .banner-text {
                
                text-shadow: 0 2px 4px rgba(0,0,0,0.5), 
                            0 4px 8px rgba(0,0,0,0.3); /* Added stronger shadow */
            }
            
            
            .shop-now-btn {
                padding: 10px 20px;
                font-size: 0.9rem;
            }
             .carousel-item::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.2); /* Light grey shade with 20% opacity */
                z-index: 1; /* Ensure it's above the image but below text */
            }

            /* Ensure text is above the overlay */
            .banner-text {
                z-index: 3;
            }

            /* Adjust the main image to ensure it doesn't have conflicting styles */
            .main-image {
                position: relative; /* Ensure the image is positioned for the pseudo-element to work correctly */
                z-index: 0; /* Place image below the overlay */
            }
        }
        
        
        @media (max-width: 480px) {
            .banner-text {
            
                text-shadow: 0 2px 4px rgba(0,0,0,0.7), 
                            0 4px 8px rgba(0,0,0,0.5); /* Even stronger shadow for smaller screens */
            }
            
            
            
            .left-text, .right-text {
                padding: 10px;
            }
        }

        /* For extra small devices */
        @media (max-width: 400px) {
            .banner-text {
                text-shadow: 0 2px 5px rgba(0,0,0,0.8), 
                            0 5px 10px rgba(0,0,0,0.6); /* Very strong shadow for visibility */
                
            }
             .carousel-item::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.2); /* Light grey shade with 20% opacity */
                z-index: 1; /* Ensure it's above the image but below text */
            }

            /* Ensure text is above the overlay */
            .banner-text {
                z-index: 3;
            }

            /* Adjust the main image to ensure it doesn't have conflicting styles */
            .main-image {
                position: relative; /* Ensure the image is positioned for the pseudo-element to work correctly */
                z-index: 0; /* Place image below the overlay */
            }
        }
            
        

        /* For super small devices */
        @media (max-width: 320px) {
            .banner-text {
                text-shadow: 0 3px 6px rgba(0,0,0,0.9), 
                            0 6px 12px rgba(0,0,0,0.7); /* Maximum shadow for smallest screens */
            }
             .carousel-item::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.2); /* Light grey shade with 20% opacity */
                z-index: 1; /* Ensure it's above the image but below text */
            }

            /* Ensure text is above the overlay */
            .banner-text {
                z-index: 3;
            }

            /* Adjust the main image to ensure it doesn't have conflicting styles */
            .main-image {
                position: relative; /* Ensure the image is positioned for the pseudo-element to work correctly */
                z-index: 0; /* Place image below the overlay */
            }
        }
        
        
        @media (min-width: 769px) {
        .dropdown:hover .dropdown-content {
            display: block;
        }
        }
        @media (max-width: 768px) {
        .user-menu {
            position: absolute;
            right: 0;
            width: 200px; /* Fixed width */
        }
        }
        /* WhatsApp Button Styles */
        .whatsapp-button {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background-color: #25D366;
            color: white;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            text-align: center;
            font-size: 30px;
            line-height: 60px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            z-index: 999;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
        }

        .whatsapp-button:hover {
            background-color: #128C7E;
            transform: scale(1.1);
        }

        /* Chatbot Styles */
        .chatbot-toggle {
            position: fixed;
            bottom: 100px;
            right: 30px;
            background-color: #f557ab;
            color: white;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            border: none;
            font-size: 24px;
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            z-index: 999;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .chatbot-toggle:hover {
            background-color: #e04d99;
            transform: scale(1.1);
        }

        .chatbot-container {
            position: fixed;
            bottom: 100px;
            right: 30px;
            width: 350px;
            height: 500px;
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            display: none;
            flex-direction: column;
            overflow: hidden;
            transform-origin: bottom right;
            animation: fadeInScale 0.3s ease-out;
        }

        @keyframes fadeInScale {
            from {
                opacity: 0;
                transform: scale(0.8);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .chatbot-container.open {
            display: flex;
        }

        .chatbot-header {
            background-color: #f557ab;
            color: white;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .chatbot-header h3 {
            margin: 0;
            font-size: 18px;
        }

        .chatbot-close {
            background: none;
            border: none;
            color: white;
            font-size: 18px;
            cursor: pointer;
        }

        .chatbot-messages {
            flex: 1;
            padding: 15px;
            overflow-y: auto;
            background-color: #f9f9f9;
        }

        .chatbot-input {
            display: flex;
            padding: 10px;
            border-top: 1px solid #eee;
            background-color: white;
        }

        .chatbot-input input {
            flex: 1;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 20px;
            outline: none;
        }

        .chatbot-send {
            background-color: #f557ab;
            color: white;
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-left: 10px;
            cursor: pointer;
        }

        /* Responsive adjustments */
        @media (max-width: 480px) {
            .chatbot-container {
                width: 90%;
                right: 5%;
                bottom: 80px;
                height: 60vh;
            }
            
            .whatsapp-button,
            .chatbot-toggle {
                width: 50px;
                height: 50px;
                font-size: 20px;
                line-height: 50px;
                bottom: 20px;
            }
            
            .chatbot-toggle {
                bottom: 80px;
            }
        }
                /* Chat Message Styles */
        .chatbot-messages {
            display: flex;
            flex-direction: column;
            gap: 10px;
            padding: 15px;
        }

        .message {
            max-width: 80%;
            padding: 10px 15px;
            border-radius: 18px;
            font-size: 14px;
            line-height: 1.4;
            position: relative;
            word-wrap: break-word;
        }

        .message.user {
            align-self: flex-end;
            background-color: #f557ab;
            color: white;
            border-bottom-right-radius: 4px;
            animation: slideInRight 0.3s ease-out;
        }

        .message.bot {
            align-self: flex-start;
            background-color: #f0f0f0;
            color: #333;
            border-bottom-left-radius: 4px;
            animation: slideInLeft 0.3s ease-out;
        }

        /* Message animations */
        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* Timestamp styling */
        .message-time {
            display: block;
            font-size: 11px;
            margin-top: 4px;
            opacity: 0.7;
            text-align: right;
        }

        /* Typing indicator */
        .typing-indicator {
            display: inline-block;
            padding: 10px 15px;
            background-color: #f0f0f0;
            border-radius: 18px;
            border-bottom-left-radius: 4px;
        }

        .typing-indicator span {
            height: 8px;
            width: 8px;
            background-color: #999;
            border-radius: 50%;
            display: inline-block;
            margin: 0 2px;
            animation: bounce 1.5s infinite ease-in-out;
        }

        .typing-indicator span:nth-child(2) {
            animation-delay: 0.2s;
        }

        .typing-indicator span:nth-child(3) {
            animation-delay: 0.4s;
        }

        @keyframes bounce {
            0%, 60%, 100% {
                transform: translateY(0);
            }
            30% {
                transform: translateY(-5px);
            }
        }
        
        /* Reuse your existing styles for top-bar, nav-bar etc. */
        /* ... (copy all the relevant styles from index2.php) ... */
        
        /* Products Grid Styles */
        .products-section {
            padding: 50px 20px;
            background-color: #f9f9f9;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .section-title {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
            font-size: 2rem;
            font-weight: 600;
        }
        
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 30px;
            padding: 20px 0;
        }
        
        .product-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            position: relative;
        }
        
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }
        
        .product-image {
            height: 300px;
            overflow: hidden;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .product-image img {
            width: auto;
            height: 100%;
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            transition: transform 0.5s ease;
        }
        
        .product-card:hover .product-image img {
            transform: scale(1.03);
        }
        
        .sale-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background-color: #f557ab;
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }
        
        .product-info {
            padding: 20px;
            text-align: center;
        }
        
        .product-name {
            color: #333;
            font-size: 1.1rem;
            margin: 0 0 10px;
            font-weight: 600;
            height: 40px;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }
        
        .product-category {
            color: #777;
            font-size: 0.9rem;
            margin-bottom: 5px;
        }
        
        .product-price {
            color: #f557ab;
            font-size: 1.2rem;
            font-weight: 700;
            margin-bottom: 15px;
        }
        
        .original-price {
            text-decoration: line-through;
            color: #999;
            font-size: 0.9rem;
            margin-left: 8px;
            font-weight: normal;
        }
        
        .add-to-cart {
            display: inline-block;
            padding: 10px 20px;
            background-color: #f557ab;
            color: white;
            border: none;
            border-radius: 30px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
            max-width: 200px;
        }
        
        .add-to-cart:hover {
            background-color: #e04d99;
            transform: translateY(-2px);
            box-shadow: 0 5px 10px rgba(0,0,0,0.1);
        }
        
        .add-to-cart i {
            margin-right: 8px;
        }
        
        .no-products {
            text-align: center;
            color: #666;
            padding: 30px;
            grid-column: 1 / -1;
        }
        
        .view-all-container {
            text-align: center;
            margin-top: 40px;
        }
        
        .view-all-btn {
            display: inline-block;
            padding: 12px 30px;
            background-color: #f557ab;
            color: white;
            text-decoration: none;
            border-radius: 30px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .view-all-btn:hover {
            background-color: #e04d99;
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        /* Responsive styles */
        @media (max-width: 768px) {
            .products-grid {
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            }
            
            .product-image {
                height: 250px;
            }
            
            .section-title {
                font-size: 1.8rem;
            }
        }
        
        @media (max-width: 480px) {
            .products-grid {
                grid-template-columns: 1fr;
            }
            
            .section-title {
                font-size: 1.5rem;
            }
        }
        
        .product-detail-section {
            padding: 50px 20px;
            background-color: #f9f9f9;
        }
        
        .product-detail-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            flex-wrap: wrap;
            gap: 40px;
        }
        
        .product-images {
            flex: 1;
            min-width: 300px;
        }
        
        .main-image {
            width: 100%;
            height: 500px;
            object-fit: contain;
            background: white;
            border-radius: 10px;
            margin-bottom: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        .thumbnail-container {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        
        .thumbnail {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 5px;
            cursor: pointer;
            border: 2px solid transparent;
            transition: all 0.3s ease;
        }
        
        .thumbnail:hover, .thumbnail.active {
            border-color: #f557ab;
        }
        
        .product-info {
            flex: 1;
            min-width: 300px;
        }
        
        .product-title {
            font-size: 2rem;
            color: #333;
            margin-bottom: 10px;
        }
        
        .product-category {
            color: #777;
            font-size: 1rem;
            margin-bottom: 20px;
        }
        
        .product-price {
            font-size: 1.8rem;
            color: #f557ab;
            font-weight: 700;
            margin-bottom: 20px;
        }
        
        .original-price {
            text-decoration: line-through;
            color: #999;
            font-size: 1.4rem;
            margin-left: 10px;
            font-weight: normal;
        }
        
        .product-description {
            margin-bottom: 30px;
            line-height: 1.6;
            color: #555;
        }
        
        .options-form {
            margin-bottom: 30px;
        }
        
        .option-group {
            margin-bottom: 20px;
        }
        
        .option-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }
        
        .option-select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
        }
        
        .quantity-selector {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .quantity-btn {
            width: 40px;
            height: 40px;
            background-color: #f557ab;
            color: white;
            border: none;
            font-size: 1.2rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .quantity-input {
            width: 60px;
            height: 40px;
            text-align: center;
            border: 1px solid #ddd;
            margin: 0 5px;
            font-size: 1rem;
        }
        
        .add-to-cart-btn {
            padding: 15px 30px;
            background-color: #f557ab;
            color: white;
            border: none;
            border-radius: 30px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
            max-width: 300px;
        }
        
        .add-to-cart-btn:hover {
            background-color: #e04d99;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .back-to-products {
            display: inline-block;
            margin-top: 30px;
            color: #f557ab;
            text-decoration: none;
            font-weight: 600;
        }
        
        .back-to-products:hover {
            text-decoration: underline;
        }
        
        .error-message {
            color: #e74c3c;
            margin-bottom: 20px;
        }
        
        .success-message {
            color: #2ecc71;
            margin-bottom: 20px;
        }
        
        @media (max-width: 768px) {
            .main-image {
                height: 400px;
            }
            
            .product-title {
                font-size: 1.8rem;
            }
            
            .product-price {
                font-size: 1.5rem;
            }
        }
        
        @media (max-width: 480px) {
            .main-image {
                height: 300px;
            }
            
            .product-title {
                font-size: 1.5rem;
            }
        }
        .cart-icon {
    position: relative;
    margin-left: 15px;
    color: white;
    font-size: 20px;
}

.cart-count {
    position: absolute;
    top: -8px;
    right: -8px;
    background-color: #e74c3c;
    color: white;
    border-radius: 50%;
    width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    font-weight: bold;
}
        .checkout-container {
            max-width: 1000px;
            margin: 50px auto;
            padding: 20px;
            display: flex;
            gap: 30px;
        }
        
        .checkout-form {
            flex: 2;
        }
        
        .order-summary {
            flex: 1;
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 10px;
            position: sticky;
            top: 20px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
        }
        
        input, textarea, select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 5px;
        }
        
        .order-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        
        .order-subtotal {
            display: flex;
            justify-content: space-between;
            margin: 15px 0;
            padding-top: 15px;
            border-top: 1px solid #eee;
        }
        
        .shipping-fee {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
        }
        
        .order-total {
            display: flex;
            justify-content: space-between;
            font-weight: bold;
            font-size: 1.2rem;
            padding-top: 15px;
            border-top: 1px solid #eee;
        }
        
        #city-group {
            display: none;
        }
        
        .btn-checkout {
            background-color: #f557ab;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            width: 100%;
            margin-top: 10px;
            position: relative;
        }
        
        .btn-checkout:hover {
            background-color: #e04d99;
        }
        
        .btn-checkout:disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }
        
        .spinner-border {
            display: none;
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
        }
        
        .is-invalid {
            border-color: #dc3545 !important;
        }
        
        .invalid-feedback {
            color: #dc3545;
            font-size: 0.875em;
            display: none;
        }
        
        .invalid-feedback.show {
            display: block;
        }
        
        /* Error and success message styles */
        .alert {
            padding: 12px 20px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            border-radius: 4px;
            display: none;
        }
        
        .alert-success {
            color: #155724;
            background-color: #d4edda;
            border-color: #c3e6cb;
        }
        
        .alert-danger {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }
        
        .alert-warning {
            color: #856404;
            background-color: #fff3cd;
            border-color: #ffeaa7;
        }
        
        .phone-help {
            font-size: 0.875em;
            color: #6c757d;
            margin-top: 5px;
        }

        /* Hidden form for PayFast submission */
        #payfast-form {
            display: none;
        }
        /* Small Phone Styles (under 400px) */
@media only screen and (max-width: 400px) {
    /* Checkout Page Specific */
    .checkout-container {
        flex-direction: column;
        padding: 10px;
        margin: 20px auto;
    }
    
    .checkout-form, 
    .order-summary {
        flex: 1 1 100%;
        width: 100%;
    }
    
    .order-summary {
        margin-top: 20px;
        position: static;
    }
    
    .form-group input,
    .form-group select,
    .form-group textarea {
        padding: 8px;
        font-size: 14px;
    }
    
    label {
        font-size: 14px;
    }
    
    .btn-checkout {
        padding: 10px 15px;
        font-size: 14px;
    }
    
    /* Cart Page Specific */
    .cart-container {
        padding: 10px;
    }
    
    .cart-table {
        font-size: 12px;
    }
    
    .cart-table th,
    .cart-table td {
        padding: 8px 5px;
    }
    
    .cart-item-image {
        width: 50px;
        height: 50px;
    }
    
    .quantity-input {
        width: 40px;
        padding: 3px;
    }
    
    .cart-summary {
        flex-direction: column;
        gap: 15px;
    }
    
    .cart-actions {
        flex-direction: column;
        gap: 8px;
    }
    
    .btn {
        width: 100%;
        padding: 8px;
        font-size: 14px;
    }
    
    /* Shared Elements */
    .cart-header h1,
    .checkout-form h2,
    .order-summary h3 {
        font-size: 1.3rem;
    }
    
    .empty-cart i {
        font-size: 40px;
    }
    
    .empty-cart h2 {
        font-size: 1.2rem;
    }
    
    .empty-cart p {
        font-size: 14px;
    }
    
    /* Adjust form elements for small screens */
    input, select, textarea {
        font-size: 14px;
    }
    
    /* Make buttons full width */
    .btn-continue,
    .btn-clear,
    .btn-checkout {
        display: block;
        width: 100%;
        margin-bottom: 8px;
    }
    
    /* Hide less important columns on cart table */
    .cart-table th:nth-child(3),
    .cart-table td:nth-child(3),
    .cart-table th:nth-child(4),
    .cart-table td:nth-child(4) {
        display: none;
    }
}

/* Super Small Devices (under 320px) */
@media only screen and (max-width: 320px) {
    .checkout-form h2 {
        font-size: 1.2rem;
    }
    
    .cart-header h1 {
        font-size: 1.1rem;
    }
    
    .cart-table {
        font-size: 11px;
    }
    
    .cart-item-image {
        width: 40px;
        height: 40px;
    }
    
    .quantity-input {
        width: 35px;
    }
    
    .btn {
        font-size: 13px;
    }
}
/* Footer Styles */
.footer {
    background-color: #383838;
    color: #fff;
    padding: 40px 20px 20px;
    font-size: 14px;
}

.footer-container {
    max-width: 1200px;
    margin: 0 auto;
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
}

.footer-links {
    display: flex;
    flex-wrap: wrap;
    gap: 30px;
    margin-bottom: 30px;
    width: 100%;
}

.footer-column {
    flex: 1;
    min-width: 150px;
    margin-bottom: 20px;
}

.footer-column h3 {
    color: #fff;
    font-size: 16px;
    margin-bottom: 15px;
    font-weight: 600;
}

.footer-column ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.footer-column ul li {
    margin-bottom: 10px;
}

.footer-column ul li a {
    color: #ccc;
    text-decoration: none;
    transition: color 0.3s ease;
}

.footer-column ul li a:hover {
    color: #f557ab;
}

/* Contact Info Styles - Improved */
.footer-contact {
    width: 100%;
    margin: 20px 0;
    padding: 20px 0;
    border-top: 1px solid #555;
    border-bottom: 1px solid #555;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 15px;
}

.contact-info {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 30px;
}

.contact-item {
    display: flex;
    align-items: center;
    color: #fff;
    text-decoration: none;
    font-size: 16px;
    transition: all 0.3s ease;
}

.contact-item:hover {
    color: #f557ab;
    transform: translateY(-2px);
}

.contact-icon {
    margin-right: 10px;
    font-size: 18px;
    width: 24px;
    height: 24px;
    background-color: #f557ab;
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.contact-item:hover .contact-icon {
    background-color: white;
    color: #f557ab;
}

/* Footer Bottom */
.footer-bottom {
    width: 100%;
    padding-top: 20px;
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    align-items: center;
}

.copyright {
    color: #aaa;
    margin-bottom: 10px;
}

.footer-legal-links {
    display: flex;
    gap: 15px;
    margin-bottom: 10px;
}

.footer-legal-links a {
    color: #aaa;
    text-decoration: none;
    transition: color 0.3s ease;
}

.footer-legal-links a:hover {
    color: #f557ab;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .footer-column {
        min-width: 120px;
    }
    
    .footer-bottom {
        flex-direction: column;
        text-align: center;
    }
    
    .footer-legal-links {
        justify-content: center;
    }
    
    .contact-info {
        flex-direction: column;
        align-items: center;
        gap: 15px;
    }
}

@media (max-width: 480px) {
    .footer-links {
        flex-direction: column;
        gap: 20px;
    }
    
    .footer-column {
        min-width: 100%;
    }
}
/* Payment Methods Styles */
.payment-methods {
    width: 100%;
    margin: 20px 0;
    padding: 15px 0;
    border-top: 1px solid #555;
    border-bottom: 1px solid #555;
}

.payment-methods h3 {
    color: #fff;
    font-size: 16px;
    margin-bottom: 15px;
    text-align: center;
    font-weight: 600;
}

.payment-icons {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    align-items: center;
    gap: 20px;
}

.payment-icon {
    height: 30px;
    width: auto;
    filter: grayscale(100%) brightness(2);
    transition: all 0.3s ease;
}

.payment-icon:hover {
    filter: none;
    transform: scale(1.1);
}

/* Responsive adjustments */
@media (max-width: 480px) {
    .payment-icons {
        gap: 15px;
    }
    
    .payment-icon {
        height: 25px;
    }
}
/* Payment Methods Styles */
.payment-methods {
    width: 100%;
    margin: 20px 0;
    padding: 15px 0;
    border-top: 1px solid #555;
    border-bottom: 1px solid #555;
}

.payment-methods h3 {
    color: #fff;
    font-size: 16px;
    margin-bottom: 15px;
    text-align: center;
    font-weight: 600;
}

.payment-icons {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    align-items: center;
    gap: 20px;
}

.payment-icon {
    height: 30px;
    width: auto;
    filter: grayscale(100%) brightness(2);
    transition: all 0.3s ease;
}

.payment-icon:hover {
    filter: none;
    transform: scale(1.1);
}

/* Responsive adjustments */
@media (max-width: 480px) {
    .payment-icons {
        gap: 15px;
    }
    
    .payment-icon {
        height: 25px;
    }
}
 #loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #f557ab, #e04d99);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            z-index: 99999;
            transition: opacity 0.8s ease, visibility 0.8s ease;
        }
        
        #loading-overlay.hidden {
            opacity: 0;
            visibility: hidden;
        }
        
        .loading-content {
            text-align: center;
            max-width: 90%;
        }
        
        .loading-spinner {
            width: 120px;
            height: 120px;
            margin: 0 auto 20px;
            border-radius: 50%;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: pulse 2s infinite;
        }

        .loading-spinner video {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .loading-text {
            color: white;
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 10px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.2);
            letter-spacing: 1px;
        }
        
        .loading-subtext {
            color: rgba(255, 255, 255, 0.8);
            font-size: 1rem;
            max-width: 500px;
            margin: 0 auto;
        }
        
        .progress-container {
            width: 300px;
            max-width: 90%;
            height: 8px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 4px;
            margin: 20px auto;
            overflow: hidden;
        }
        
        .progress-bar {
            height: 100%;
            width: 0%;
            background: white;
            border-radius: 4px;
            transition: width 0.3s ease;
        }
        
        @keyframes pulse {
            0% { transform: scale(0.95); }
            50% { transform: scale(1.05); }
            100% { transform: scale(0.95); }
        }
        
        /* Hide page content while loading */
        .page-content {
            opacity: 0;
            transition: opacity 0.5s ease;
        }
        
        .page-content.visible {
            opacity: 1;
        }
    </style>
</head>
<body>
      <div id="loading-overlay">
        <div class="loading-content">
            <div class="loading-spinner">
                <video autoplay loop muted playsinline>
                    <source src="assets/loading.mp4" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            </div>
            <div class="loading-text">GONE WAISTLESS</div>
            <div class="loading-subtext">Checking out...</div>
            <div class="progress-container">
                <div class="progress-bar" id="progress-bar"></div>
            </div>
        </div>
    </div>
<div class="top-bar">
        <div class="top-bar-container">
            <div class="logo">
                <img src="assets/logo.png" alt="Company Logo">
            </div>
            
            <div class="opening-hours">
                <div class="opening-hours-content">
                    <?php echo $openingHoursText; ?>
                </div>
            </div>
            
            <div class="social-icons">
                <a href="https://www.instagram.com/gone_waistless/" target="_blank"><i class="fab fa-instagram"></i></a>
                <a href="https://www.facebook.com/p/Gone-Waistless-61555954654905/?locale=eo_EO" target="_blank"><i class="fab fa-facebook-f"></i></a>
            </div>
        </div>
    </div>
    
    <div class="nav-bar">
        <div class="nav-container">
            <button class="menu-toggle">
                <i class="fas fa-bars"></i>
            </button>
            
            <ul class="nav-links">
                <li><a href="index.php">HOME</a></li>
                <li class="dropdown">
                    <a href="#">SHOP BY <i class="fas fa-chevron-down"></i></a>
                    <div class="dropdown-content">
                        <a href="products.php">All products</a>
                    <?php
                    // Fetch featured categories from database
                    try {
                        $featured_query = "SELECT id, name, slug FROM categories WHERE is_featured = 1 ORDER BY name ASC";
                        $stmt = $pdo->query($featured_query);
                        $featured_categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        
                        foreach($featured_categories as $cat) {
                            echo '<a href="category_products.php?category_id='.$cat['id'].'">'.htmlspecialchars($cat['name']).'</a>';
                        }
                    } catch (PDOException $e) {
                        // Fallback if there's an error
                        
                        echo '<a href="category_products.php?category_id=1">Jumpsuits</a>';
                        echo '<a href="category_products.php?category_id=2">Waist Trainers</a>';
                        echo '<a href="category_products.php?category_id=3">Tea</a>';
                        echo '<a href="category_products.php?category_id=4">Bodysuits</a>';
                        echo '<a href="category_products.php?category_id=5">Shapewear</a>';
                    }
                    ?>
                </div>
                </li>
                <li><a href="about.php">About Us</a></li>
                <li><a href="faq.php">FAQS</a></li>
                
            </ul>
            
            <div class="nav-icons">
                <div class="dropdown user-dropdown">
                    <a href="#" class="user-icon"><i class="fas fa-user"></i></a>
                    <div class="dropdown-content user-menu">
                        <?php if(isset($_SESSION['user_id'])): ?>
                            <div class="user-info">
                                <span class="username"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                            </div>
                            <a href="#" class="menu-item">
                               <a href="account.php"><i class="fas fa-cog"></i> Account Settings</a>
                            </a>
                            <a href="login.php" class="menu-item logout">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </a>
                        <?php else: ?>
                            <a href="login.php" class="menu-item">
                                <i class="fas fa-sign-in-alt"></i> Login
                            </a>
                            <a href="register.php" class="menu-item">
                                <i class="fas fa-user-plus"></i> Register
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
                 <a href="cart.php" class="cart-icon">
                    <i class="fas fa-shopping-cart"></i>
                    <?php if (!empty($_SESSION['cart'])): ?>
                        <span class="cart-count"><?php echo count($_SESSION['cart']); ?></span>
                    <?php endif; ?>
                </a>
            </div>
        </div>
    </div>
    <div class="checkout-container">
        <div class="checkout-form">
            <h2>Checkout Information</h2>
            
            <!-- Alert Messages -->
            <div id="alertContainer"></div>
            
            <form id="checkoutForm">
                <!-- CSRF Token -->
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                
                <div class="form-group">
                    <label for="name">Full Name *</label>
                    <input type="text" id="name" name="name" required>
                    <div class="invalid-feedback">Please provide your full name</div>
                </div>
                
                <div class="form-group">
                    <label for="email">Email *</label>
                    <input type="email" id="email" name="email" required>
                    <div class="invalid-feedback">Please provide a valid email address</div>
                </div>
                
                <div class="form-group">
                    <label for="phone">Phone Number *</label>
                    <input type="tel" id="phone" name="phone" required 
       placeholder="e.g. 0123456789 or +27123456789"
       pattern="(\+27|0)[0-9]{9}">
<div class="phone-help">Enter South African number (10 digits starting with 0, or 11 digits starting with 27)</div>
                    <div class="invalid-feedback">Please provide a valid South African phone number</div>
                </div>
                
                <div class="form-group">
                    <label for="province">Province *</label>
                    <select id="province" name="province" required>
                        <option value="">Select Province</option>
                        <option value="Gauteng">Gauteng</option>
                        <option value="Out of Gauteng">Out of Gauteng</option>
                    </select>
                    <div class="invalid-feedback">Please select a province</div>
                </div>
                
                <div class="form-group" id="city-group">
                    <label for="city">City</label>
                    <select id="city" name="city">
                        <option value="">Select City</option>
                        <option value="Johannesburg">Johannesburg</option>
                        <option value="Pretoria">Pretoria</option>
                    </select>
                    <div class="invalid-feedback">Please select a city</div>
                </div>
                
                <div class="form-group">
                    <label for="address">Street Address *</label>
                    <textarea id="address" name="address" rows="4" required placeholder="Street name, building number, apartment/unit number (if applicable)"></textarea>
                    <div class="invalid-feedback">Please provide your address</div>
                </div>
                
                <input type="hidden" id="shipping_fee" name="shipping_fee" value="0">
                <input type="hidden" id="total_amount" name="total_amount" value="<?php echo $subtotal; ?>">
                
                
            </form>
        </div>
        
        <div class="order-summary">
            <h3>Order Summary</h3>
            <?php foreach ($cart_items as $item): ?>
                <div class="order-item">
                    <span><?php echo htmlspecialchars($item['name']); ?> × <?php echo htmlspecialchars($item['quantity']); ?></span>
                    <span>R<?php echo number_format($item['price'] * $item['quantity'], 2); ?></span>
                </div>
            <?php endforeach; ?>
            
            <div class="order-subtotal">
                <span>Subtotal:</span>
                <span id="subtotal-display">R<?php echo number_format($subtotal, 2); ?></span>
            </div>
            
            <div class="shipping-fee">
                <span>Shipping Fee:</span>
                <span id="shipping-fee-display">R0.00</span>
            </div>
            
            <div class="order-total">
                <span>Total:</span>
                <span id="total-display">R<?php echo number_format($subtotal, 2); ?></span>
            </div>
            <button type="button" id="payNowBtn" class="btn-checkout">
                    <span id="btn-text">Pay Now</span>
                    <span id="loading-spinner" class="spinner-border spinner-border-sm"></span>
            </button>
        </div>
    </div>

    <!-- Hidden form for PayFast submission -->
    <form id="payfast-form" method="POST" action="https://sandbox.payfast.co.za/eng/process">
        <!-- PayFast merchant details -->
        <input type="hidden" name="merchant_id" value="">
        <input type="hidden" name="merchant_key" value="">
        
        <!-- Order details -->
        <input type="hidden" name="amount" value="">
        <input type="hidden" name="item_name" value="">
        <input type="hidden" name="item_description" value="">
        <input type="hidden" name="custom_int1" value="">
        <input type="hidden" name="custom_str1" value="">
        
        <!-- Customer details -->
        <input type="hidden" name="name_first" value="">
        <input type="hidden" name="name_last" value="">
        <input type="hidden" name="email_address" value="">
        <input type="hidden" name="cell_number" value="">
        
        <!-- URLs -->
        <input type="hidden" name="return_url" value="">
        <input type="hidden" name="cancel_url" value="">
        <input type="hidden" name="notify_url" value="">
    </form>
    <footer class="footer">
    <div class="footer-container">
        <div class="footer-links">
            <div class="footer-column">
                <h3>Shop</h3>
                <ul>
                    <li><a href="products.php">All Products</a></li>
                    <li><a href="category_products.php?category_id=1">Jumpsuits</a></li>
                    <li><a href="category_products.php?category_id=2">Waist Trainers</a></li>
                    <li><a href="category_products.php?category_id=3">Tea</a></li>
                    <li><a href="category_products.php?category_id=4">Bodysuits</a></li>
                </ul>
            </div>
            
            <div class="footer-column">
                <h3>Information</h3>
                <ul>
                    <li><a href="about.php">About Us</a></li>
                    <li><a href="faq.php">FAQs</a></li>
                    <li><a href="reseller.php">Reseller Application</a></li>
                </ul>
            </div>
            
            <div class="footer-column">
                <h3>Customer Service</h3>
                <ul>
                    <li><a href="delivery.php">Delivery & Returns</a></li>
                    <li><a href="terms.php">Terms & Conditions</a></li>
                    <li><a href="privacy.php">Privacy Policy</a></li>
                    
                </ul>
            </div>
        </div>
        
        <div class="footer-contact">
            <div class="contact-info">
                <a href="tel:+27798118465" class="contact-item">
                    <span class="contact-icon"><i class="fas fa-phone"></i></span>
                    <span>+27 (79) 811-8465</span>
                </a>
                <a href="mailto:sales@gonewaistless.co.za" class="contact-item">
                    <span class="contact-icon"><i class="fas fa-envelope"></i></span>
                    <span>sales@gonewaistless.co.za</span>
                </a>
            </div>
        </div>
        <div class="payment-methods">
    <h3>We Accept</h3>
    <div class="payment-icons">
    <!-- PayFast (official white logo) -->
   
    
    <!-- MasterCard -->
    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/2a/Mastercard-logo.svg/1280px-Mastercard-logo.svg.png" 
         alt="MasterCard" class="payment-icon" title="MasterCard">
    
    
    <!-- Visa -->
    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/5e/Visa_Inc._logo.svg/1280px-Visa_Inc._logo.svg.png" 
         alt="Visa Card" class="payment-icon" title="Visa Card">
</div>
        
        <div class="footer-bottom">
            <div class="copyright">© Copyright <?php echo date('Y'); ?> Gone Waistless</div>
            <div class="footer-legal-links">
                <a href="privacy.php">Privacy Policy</a>
            </div>
        </div>
    </div>
</footer>
<a href="https://wa.me/+27798118465" class="whatsapp-button" target="_blank">
        <i class="fab fa-whatsapp"></i>
    </a>

    <!-- Chatbot Container -->
    <div class="chatbot-container">
        <div class="chatbot-header">
            <h3>Chat with us</h3>
            <button class="chatbot-close"><i class="fas fa-times"></i></button>
        </div>
        <div class="chatbot-messages">
            <!-- Messages will appear here -->
        </div>
        <div class="chatbot-input">
            <input type="text" placeholder="Type your message...">
            <button class="chatbot-send"><i class="fas fa-paper-plane"></i></button>
        </div>
    </div>
    <button class="chatbot-toggle">
        <i class="fas fa-comment-dots"></i>
    </button>
    <!-- Include jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
<script>
        
    $(document).ready(function() {
        // Cache DOM elements
        const $provinceSelect = $('#province');
        const $cityGroup = $('#city-group');
        const $citySelect = $('#city');
        const $subtotalDisplay = $('#subtotal-display');
        const $shippingFeeDisplay = $('#shipping-fee-display');
        const $totalDisplay = $('#total-display');
        const $shippingFeeInput = $('#shipping_fee');
        const $totalAmountInput = $('#total_amount');
        const $payNowBtn = $('#payNowBtn');
        const $form = $('#checkoutForm');
        const $alertContainer = $('#alertContainer');
        const $payfastForm = $('#payfast-form');
        
        const subtotal = <?php echo $subtotal; ?>;
        
        // PayFast configuration
        const PAYFAST_CONFIG = {
            merchant_id: '11396923',
            merchant_key: 'lvogh401laeea',
            sandbox: false  // Set to true for testing
        };
        
        // Utility functions
        function showAlert(message, type = 'danger') {
            const alertHtml = `
                <div class="alert alert-${type}" role="alert">
                    ${message}
                    <button type="button" class="close" style="float: right; border: none; background: none; font-size: 1.2em; cursor: pointer;" onclick="$(this).parent().fadeOut()">&times;</button>
                </div>
            `;
            $alertContainer.html(alertHtml).find('.alert').fadeIn();
            
            // Auto-hide after 5 seconds for success messages
            if (type === 'success') {
                setTimeout(() => {
                    $alertContainer.find('.alert').fadeOut();
                }, 5000);
            }
        }
        
        function hideAlert() {
            $alertContainer.find('.alert').fadeOut();
        }
        
        function validateSouthAfricanPhone(phone) {
            const cleanPhone = phone.replace(/\D/g, '');
            
            // Valid formats:
            // 0123456789 (10 digits, starts with 0)
            // 27123456789 (11 digits, starts with 27)
            // 123456789 (9 digits - already correct for PayFast)
            return (cleanPhone.length === 10 && cleanPhone.startsWith('0')) ||
                (cleanPhone.length === 11 && cleanPhone.startsWith('27')) ||
                (cleanPhone.length === 9);
        }

        function formatPhoneForPayFast(phone) {
            const cleanPhone = phone.replace(/\D/g, '');
            
            if (cleanPhone.length === 10 && cleanPhone.startsWith('0')) {
                return '27' + cleanPhone.substring(1); // Convert 0123456789 to 27123456789
            }
            if (cleanPhone.length === 11 && cleanPhone.startsWith('27')) {
                return cleanPhone; // Already in correct format
            }
            if (cleanPhone.length === 9) {
                return '27' + cleanPhone; // Add country code
            }
            
            // Fallback - try to format any 9-digit number
            if (cleanPhone.length >= 9) {
                const lastNine = cleanPhone.slice(-9);
                return '27' + lastNine;
            }
            
            return '27' + cleanPhone; // Add country code as fallback
        }
        
        function updateShippingAndTotals() {
            let shippingFee = 0;
            
            if ($provinceSelect.val() === 'Gauteng') {
                $cityGroup.show();
                $citySelect.prop('required', true);
                shippingFee = 100;
            } else if ($provinceSelect.val() === 'Out of Gauteng') {
                $cityGroup.hide();
                $citySelect.prop('required', false).val('');
                shippingFee = 150;
            } else {
                $cityGroup.hide();
                $citySelect.prop('required', false).val('');
                shippingFee = 0;
            }
            
            $shippingFeeDisplay.text('R' + shippingFee.toFixed(2));
            $totalDisplay.text('R' + (subtotal + shippingFee).toFixed(2));
            $shippingFeeInput.val(shippingFee);
            $totalAmountInput.val(subtotal + shippingFee);
        }
        
        function validateForm() {
            let isValid = true;
            
            // Clear previous validation states
            $form.find('.is-invalid').removeClass('is-invalid');
            $form.find('.invalid-feedback').removeClass('show');
            
            // Check required fields
            $form.find('[required]').each(function() {
                const $field = $(this);
                if (!$field.val().trim()) {
                    $field.addClass('is-invalid');
                    $field.siblings('.invalid-feedback').addClass('show');
                    isValid = false;
                }
            });
            
            // Special validation for Gauteng city
            if ($provinceSelect.val() === 'Gauteng' && !$citySelect.val()) {
                $citySelect.addClass('is-invalid');
                $citySelect.siblings('.invalid-feedback').addClass('show');
                isValid = false;
            }
            
            // Email validation
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            const email = $('#email').val().trim();
            if (email && !emailRegex.test(email)) {
                $('#email').addClass('is-invalid');
                $('#email').siblings('.invalid-feedback').addClass('show');
                isValid = false;
            }
            
            // Phone validation
            const phone = $('#phone').val().trim();
            if (!phone || !validateSouthAfricanPhone(phone)) {
                $('#phone').addClass('is-invalid');
                $('#phone').siblings('.invalid-feedback')
                        .text('Please use a valid SA number: 0123456789 or +27123456789')
                        .addClass('show');
                isValid = false;
            }

            return isValid;
        }
        
        function setLoadingState(loading) {
            if (loading) {
                $('#btn-text').hide();
                $('#loading-spinner').show();
                $payNowBtn.prop('disabled', true);
            } else {
                $('#btn-text').show();
                $('#loading-spinner').hide();
                $payNowBtn.prop('disabled', false);
            }
        }
        
        function generateOrderId() {
            // Generate a numeric order ID for PayFast custom_int1
            return Date.now().toString() + Math.floor(Math.random() * 1000).toString().padStart(3, '0');
        }
        
        function createOrderDescription() {
            const items = <?php echo json_encode($_SESSION['cart']); ?>;
            return items.map(item => `${item.name} x${item.quantity}`).join(', ');
        }
        
        function submitToPayFast(orderData) {
            const fullName = orderData.name.trim();
            const nameParts = fullName.split(' ');
            const firstName = nameParts[0] || '';
            const lastName = nameParts.slice(1).join(' ') || '';
            
            // First store the order data
            $.ajax({
                url: 'store_order_data.php',
                type: 'POST',
                dataType: 'json',
                data: {
                    csrf_token: orderData.csrf_token,
                    name: orderData.name,
                    email: orderData.email,
                    phone: orderData.phone,
                    address: orderData.address,
                    province: orderData.province,
                    city: orderData.city || '',
                    shipping_fee: orderData.shipping_fee,
                    total_amount: orderData.total_amount,
                    cart_items: orderData.cart_items
                },
                success: function(response) {
                    if (response.success) {
                        // Order stored successfully, now proceed to PayFast
                        const orderId = response.order_id;
                        const itemDescription = createOrderDescription();
                        const amount = parseFloat(orderData.total_amount).toFixed(2);
                        const formattedPhone = formatPhoneForPayFast(orderData.phone);
                        
                        // Get current domain for URLs
                        const currentDomain = window.location.origin;
                        
                        // Populate PayFast form
                        $payfastForm.find('input[name="merchant_id"]').val(PAYFAST_CONFIG.merchant_id);
                        $payfastForm.find('input[name="merchant_key"]').val(PAYFAST_CONFIG.merchant_key);
                        $payfastForm.find('input[name="amount"]').val(amount);
                        $payfastForm.find('input[name="item_name"]').val('Gonewaistless Order #' + orderId);
                        $payfastForm.find('input[name="item_description"]').val(itemDescription);
                        $payfastForm.find('input[name="custom_int1"]').val(orderId);
                        $payfastForm.find('input[name="custom_str1"]').val(JSON.stringify({
                            province: orderData.province,
                            city: orderData.city,
                            address: orderData.address,
                            shipping_fee: orderData.shipping_fee
                        }));
                        $payfastForm.find('input[name="name_first"]').val(firstName);
                        $payfastForm.find('input[name="name_last"]').val(lastName);
                        $payfastForm.find('input[name="email_address"]').val(orderData.email);
                        $payfastForm.find('input[name="cell_number"]').val(formattedPhone);
                        $payfastForm.find('input[name="return_url"]').val(currentDomain + '/thankyou.php');
                        $payfastForm.find('input[name="cancel_url"]').val(currentDomain + '/products.php');
                        $payfastForm.find('input[name="notify_url"]').val(currentDomain + '/payfast_notify.php');
                        
                        // Update form action for production
                        const payfastUrl = PAYFAST_CONFIG.sandbox ? 
                            'https://sandbox.payfast.co.za/eng/process' : 'https://www.payfast.co.za/eng/process';
                        $payfastForm.attr('action', payfastUrl);
                        
                        // Submit to PayFast
                        $payfastForm.submit();
                    } else {
                        // Order storage failed
                        showAlert('Failed to create order: ' + response.message, 'danger');
                        setLoadingState(false);
                    }
                },
                error: function(xhr, status, error) {
                    showAlert('Error processing order. Please try again.', 'danger');
                    setLoadingState(false);
                    console.error('Order storage error:', error);
                }
            });
        }
        
        // Event handlers
        $provinceSelect.change(updateShippingAndTotals);
        
        // Real-time validation
        $form.find('input, select, textarea').on('input change', function() {
            $(this).removeClass('is-invalid');
            $(this).siblings('.invalid-feedback').removeClass('show');
            hideAlert();
        });
        
        // Phone number formatting
        $('#phone').on('input', function() {
            let value = $(this).val().replace(/\D/g, '');
            if (value.length > 10 && !value.startsWith('27')) {
                value = value.substring(0, 10);
            }
            if (value.length > 11) {
                value = value.substring(0, 11);
            }
            $(this).val(value);
        });
        
        // Main payment button click handler
        $payNowBtn.click(function(e) {
        e.preventDefault();
        hideAlert();
        
        // Validate form
        if (!validateForm()) {
            showAlert('Please correct the errors below and try again.');
            return;
        }
        
        setLoadingState(true);
        
        // Prepare order data
        const orderData = {
            csrf_token: $('input[name="csrf_token"]').val(),
            name: $('#name').val().trim(),
            email: $('#email').val().trim(),
            phone: $('#phone').val().trim(),
            province: $('#province').val(),
            city: $('#city').val(),
            address: $('#address').val().trim(),
            shipping_fee: $('#shipping_fee').val(),
            total_amount: $('#total_amount').val(),
            cart_items: JSON.stringify(<?php echo json_encode($_SESSION['cart']); ?>)
        };
        
        // Submit to PayFast via order storage
        submitToPayFast(orderData);
    });
        
        // Initialize
        updateShippingAndTotals();
    });
    document.addEventListener('DOMContentLoaded', function() {
            const content = document.querySelector('.opening-hours-content');
            const container = document.querySelector('.opening-hours');
            
            // Double the content to create seamless looping
            const originalContent = content.textContent.trim();
            content.textContent = originalContent + ' • ' + originalContent;
            
            // Calculate duration based on content width for consistent speed
            const contentWidth = content.scrollWidth / 2;
            const scrollSpeed = 100;
            const duration = contentWidth / scrollSpeed;
            
            // Apply the animation
            content.style.animation = `scroll ${duration}s linear infinite`;
            content.style.animationDelay = '0s';
            
            // Reset the animation periodically to prevent jumps
            setInterval(() => {
                content.style.animation = 'none';
                void content.offsetWidth;
                content.style.animation = `scroll ${duration}s linear infinite`;
                content.style.animationDelay = '-0.1s';
            }, duration * 1000 / 2);

            const userIcon = document.querySelector('.user-icon');
            const userMenu = document.querySelector('.user-menu');

            // Toggle menu on click
            userIcon.addEventListener('click', function(e) {
                e.stopPropagation();
                userMenu.classList.toggle('open');
            });

            // Close when clicking outside
            document.addEventListener('click', function() {
                userMenu.classList.remove('open');
            });

            // Ensure menu items are clickable
            document.querySelectorAll('.menu-item').forEach(item => {
                item.addEventListener('click', e => e.stopPropagation());
            });
            
            // Sticky Navigation
            const navBar = document.querySelector('.nav-bar');
            const topBar = document.querySelector('.top-bar');
            
            window.addEventListener('scroll', function() {
                if (window.scrollY > topBar.offsetHeight) {
                    navBar.classList.add('sticky');
                } else {
                    navBar.classList.remove('sticky');
                }
            });
            
            // Mobile menu toggle
            const menuToggle = document.querySelector('.menu-toggle');
            const navLinks = document.querySelector('.nav-links');

            menuToggle.addEventListener('click', function(e) {
                e.stopPropagation();
                navLinks.classList.toggle('active');
                this.classList.toggle('active');
            });

            // Close menu when clicking outside
            document.addEventListener('click', function() {
                if (navLinks.classList.contains('active')) {
                    navLinks.classList.remove('active');
                    menuToggle.classList.remove('active');
                }
            });

            // Prevent clicks inside the menu from closing it
            navLinks.addEventListener('click', function(e) {
                e.stopPropagation();
            });
            
            // Dropdown functionality
            const dropdowns = document.querySelectorAll('.dropdown');
            
            dropdowns.forEach(dropdown => {
                const link = dropdown.querySelector('a');
                const content = dropdown.querySelector('.dropdown-content');
                
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    // Close all other dropdowns first
                    document.querySelectorAll('.dropdown-content').forEach(dd => {
                        if (dd !== content) dd.classList.remove('open');
                    });
                    
                    // Toggle this dropdown
                    content.classList.toggle('open');
                });
            });
            
            // Close dropdowns when clicking outside
            document.addEventListener('click', function() {
                document.querySelectorAll('.dropdown-content').forEach(dd => {
                    dd.classList.remove('open');
                });
            });
            
            // Chatbot functionality
            const chatbotToggle = document.querySelector('.chatbot-toggle');
        const chatbotContainer = document.querySelector('.chatbot-container');
        const chatbotClose = document.querySelector('.chatbot-close');
        const chatbotSend = document.querySelector('.chatbot-send');
        const chatbotInput = document.querySelector('.chatbot-input input');
        const chatbotMessages = document.querySelector('.chatbot-messages');

        // Toggle chatbot visibility
        chatbotToggle.addEventListener('click', function() {
            chatbotContainer.classList.toggle('open');
        });

        // Close chatbot
        chatbotClose.addEventListener('click', function() {
            chatbotContainer.classList.remove('open');
        });

        // Add message to chat
        function addMessage(text, sender) {
            const messageDiv = document.createElement('div');
            messageDiv.classList.add('message', sender);
           
            // Add timestamp
            const now = new Date();
            const timeString = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
           
            // Create links for any URLs in the text
            let formattedText = text.replace(
                /<a href='(.*?)' style='color: #f557ab;'>(.*?)<\/a>/g, 
                '<a href="$1" style="color: #f557ab; text-decoration: underline;">$2</a>'
            );
            
            messageDiv.innerHTML = `
                ${formattedText}
                <span class="message-time">${timeString}</span>
            `;
           
            chatbotMessages.appendChild(messageDiv);
            chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
        }

        // Function to generate bot responses
        function getBotResponse(message) {
            // Greetings
            if (message.includes('hi') || message.includes('hello') || message.includes('hey')) {
                return "Hello there! Welcome to Gone Waistless. How can I help you today?";
            }
            
            // Help requests
            if (message.includes('help') || message.includes('support')) {
                return "I can help with:\n1. Product information\n2. Order status\n3. Sizing questions\n4. Shipping details\n5. Returns/exchanges\nWhat would you like to know?";
            }
            
            // Product categories
            if (message.includes('categories') || message.includes('products') || message.includes('shop')) {
                return "We offer:\n1. Waist Trainers\n2. Jumpsuits\n3. Bodysuits\n4. Shapewear\n5. Detox Tea\n6. Bras\nWould you like more info on any of these?";
            }
            
            // Waist trainers
            if (message.includes('waist') || message.includes('trainer') || message.includes('slimming')) {
                return "Our waist trainers help with:\n- Instant waist reduction\n- Posture support\n- Workout enhancement\n- Thermal technology for increased perspiration\nCheck out our collection: <a href='category_products.php?category_id=2' style='color: #f557ab;'>Waist Trainers</a>";
            }
            
            // Jumpsuits
            if (message.includes('jumpsuit') || message.includes('one piece')) {
                return "We have stylish jumpsuits in various colors and sizes. They're perfect for any occasion! View our collection: <a href='category_products.php?category_id=1' style='color: #f557ab;'>Jumpsuits</a>";
            }
            
            // Sizing questions
            if (message.includes('size') || message.includes('fit') || message.includes('measurement')) {
                return "Our size guide:\nXS (0-2), S (4-6), M (8-10), L (12-14), XL (16-18), 2XL (20-22), 3XL (24-26)\nFor bras: AA, A, B, C, D, DD, E, F, FF\nNeed help choosing? Just let me know your measurements!";
            }
            
            // Shipping info
            if (message.includes('ship') || message.includes('delivery') || message.includes('arrive')) {
                return "Shipping info:\n- Processing: 1-2 business days\n- Delivery: 2-5 business days (SA)\n- Cost: R100 nationwide\n- Tracking provided for all orders";
            }
            
            // Returns/exchanges
            if (message.includes('return') || message.includes('exchange') || message.includes('refund')) {
                return "Our policy:\n- 7 days for exchanges\n- Items must be unworn with tags\n- No refunds, only exchanges\n- Contact us at sales@gonewaistless.co.za";
            }
            
            // Contact info
            if (message.includes('contact') || message.includes('email') || message.includes('phone')) {
                return "You can reach us at:\n📞 +27 (79) 811-8465\n✉️ sales@gonewaistless.co.za\n🕒 Mon-Fri: 9am-5pm, Sat: 9am-1pm";
            }
            
            // About the company
            if (message.includes('about') || message.includes('company') || message.includes('story')) {
                return "Gone Waistless offers premium shapewear and waist trainers to help you look and feel your best. Our products combine style with functionality for real results!";
            }
            
            // Price questions
            if (message.includes('price') || message.includes('cost') || message.includes('how much')) {
                return "Our products range from R550 to R1650. Specific prices are listed on each product page. Is there a particular item you're interested in?";
            }
            
            // Payment options
            if (message.includes('pay') || message.includes('credit') || message.includes('method')) {
                return "We accept:\n- Credit/Debit Cards (Visa, Mastercard)\n- EFT payments\n- PayFast secure checkout\nAll transactions are encrypted for security.";
            }
            
            // Sale/discount questions
            if (message.includes('sale') || message.includes('discount') || message.includes('promo')) {
                return "We occasionally run promotions! Sign up for our newsletter or follow us on Instagram @gonewaistless to stay updated on special offers.";
            }
            
            // Order status
            if (message.includes('order') || message.includes('track') || message.includes('status')) {
                return "To check your order status, please provide your order number or email address used for purchase. You can also email us at sales@gonewaistless.co.za";
            }
            
            // Tea products
            if (message.includes('tea') || message.includes('detox') || message.includes('belly')) {
                return "Our Belly Buster Detox Tea helps with:\n- Weight management\n- Digestion\n- Bloating reduction\n- Natural detoxification\nCheck it out: <a href='product.php?id=31' style='color: #f557ab;'>Belly Buster Tea</a>";
            }
            
            // Default responses
            const defaultResponses = [
                "I'm not sure I understand. Could you rephrase that?",
                "I'd be happy to help with that! Could you provide more details?",
                "For immediate assistance, you can WhatsApp us at +27 79 811 8465.",
                "Would you like me to connect you with our customer service team?",
                "You can browse our products here: <a href='products.php' style='color: #f557ab;'>Shop All Products</a>"
            ];
            
            return defaultResponses[Math.floor(Math.random() * defaultResponses.length)];
        }

        // Send message function
        function sendMessage() {
            const message = chatbotInput.value.trim().toLowerCase();
            if (message) {
                // Add user message
                addMessage(message, 'user');
                chatbotInput.value = '';
                
                // Show typing indicator
                const typingDiv = document.createElement('div');
                typingDiv.classList.add('typing-indicator');
                typingDiv.innerHTML = `
                    <span></span>
                    <span></span>
                    <span></span>
                `;
                chatbotMessages.appendChild(typingDiv);
                chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
                
                // Simulate bot response after a short delay
                setTimeout(() => {
                    // Remove typing indicator
                    typingDiv.remove();
                    
                    let response = getBotResponse(message);
                    addMessage(response, 'bot');
                }, 1500 + Math.random() * 2000);
            }
        }

        // Send message on button click or Enter key
        chatbotSend.addEventListener('click', sendMessage);
        chatbotInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                sendMessage();
            }
        });

        // Add welcome message when chatbot is opened
        chatbotToggle.addEventListener('click', function() {
            if (chatbotContainer.classList.contains('open') && chatbotMessages.children.length === 0) {
                setTimeout(() => {
                    addMessage("Hello! Welcome to Gone Waistless. I can help with:\n1. Product info\n2. Sizing questions\n3. Order status\n4. Shipping details\nHow can I assist you today?", 'bot');
                }, 500);
            }
        });
        function incrementQuantity() {
                const input = document.querySelector('.quantity-input');
                if (parseInt(input.value) < 10) {
                    input.value = parseInt(input.value) + 1;
                }
            }
            
            function decrementQuantity() {
                const input = document.querySelector('.quantity-input');
                if (parseInt(input.value) > 1) {
                    input.value = parseInt(input.value) - 1;
                }
            }

        // Add welcome message when page loads
        window.addEventListener('load', function() {
            setTimeout(() => {
                addMessage("Hello! How can we help you today?", 'bot');
            }, 1500);
        });
    });
         document.addEventListener('DOMContentLoaded', function() {
            const loadingOverlay = document.getElementById('loading-overlay');
            const pageContent = document.querySelector('.page-content');
            const progressBar = document.getElementById('progress-bar');
            
            // Minimum display time for loading overlay (10 seconds)
            const minDisplayTime = 2500;
            let startTime = Date.now();
            
            // Update progress bar every 100ms
            const progressInterval = setInterval(() => {
                const elapsed = Date.now() - startTime;
                const progress = Math.min(25, (elapsed / minDisplayTime) * 25);
                progressBar.style.width = progress + '%';
            }, 25);
            
            // Function to hide loading overlay
            function hideLoadingOverlay() {
                // Clear progress interval
                clearInterval(progressInterval);
                
                // Ensure we've shown the loader for at least 10 seconds
                const elapsed = Date.now() - startTime;
                const remaining = Math.max(0, minDisplayTime - elapsed);
                
                setTimeout(() => {
                    loadingOverlay.classList.add('hidden');
                    pageContent.classList.add('visible');
                }, remaining);
            }
            
// Hide loading overlay when page is fully loaded
window.addEventListener('load', hideLoadingOverlay);
            
            // Safety net - hide overlay after max 15 seconds no matter what
            setTimeout(hideLoadingOverlay, 2500);
        });
    </script>
</body>
</html>