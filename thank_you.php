<?php
session_start();
require_once 'includes/config.php';

$order_id = $_GET['order_id'] ?? 0;

$currentDay = date('w');
// In your PHP code, make sure the days array is correct:
$days = [
    0 => 'Sunday Closed',
    1 => 'Monday 09:00 - 17:00',
    2 => 'Tuesday 09:00 - 17:00',
    3 => 'Wednesday 09:00 - 17:00',
    4 => 'Thursday 09:00 - 17:00',
    5 => 'Friday 09:00 - 17:00',
    6 => 'Saturday 09:00 - 13:00',
    7 => 'Sunday Closed', // Duplicated for full two weeks if needed, but only 0-6 are valid for date('w')
    8 => 'Monday 09:00 - 17:00',
    9 => 'Tuesday 09:00 - 17:00',
    10 => 'Wednesday 09:00 - 17:00',
    11 => 'Thursday 09:00 - 17:00',
    12 => 'Friday 09:00 - 17:00',
    13 => 'Saturday 09:00 - 13:00'

];

// Reorder array to start with current day
$orderedDays = [];
for ($i = 0; $i < 14; $i++) {
    $dayIndex = ($currentDay + $i) % 7;
    $orderedDays[] = $days[$dayIndex];
}

$openingHoursText = implode(' • ', $orderedDays);
// You might want to verify the order belongs to the current user in a real application
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You | Gone Waistless</title>
    <link rel="shortcut icon" href="assets/logogw.png">
     <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Parisienne&family=Montserrat:wght@400;600&display=swap" rel="stylesheet" media="print" onload="this.media='all'">
     
    <!-- Load Font Awesome asynchronously -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" media="print" onload="this.media='all'">
   
    <!-- Include your existing CSS styles -->
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
        /* Categories Section Styles */
        .categories-section {
            padding: 40px 20px;
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

        .categories-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
            padding: 20px 0;
        }

        .category-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .category-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.15);
        }

        .category-image {
            height: 180px;
            overflow: hidden;
        }

        .category-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .category-card:hover .category-image img {
            transform: scale(1.05);
        }

        .category-name {
            padding: 15px;
            text-align: center;
            color: #333;
            font-size: 1.1rem;
            margin: 0;
        }

        .view-all-container {
            text-align: center;
            margin-top: 30px;
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

        /* Mobile Styles for Categories */
        @media (max-width: 768px) {
            .categories-grid {
                display: flex;
                overflow-x: auto;
                scroll-snap-type: x mandatory;
                -webkit-overflow-scrolling: touch;
                padding: 20px 10px;
                gap: 15px;
            }
            
            .category-card {
                min-width: 80%;
                scroll-snap-align: start;
            }
            
            .category-image {
                height: 150px;
            }
        }

        /* Categories Page Styles */
        .categories-page .categories-grid {
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 30px;
        }

        .categories-page .category-card {
            box-shadow: 0 6px 12px rgba(0,0,0,0.15);
        }

        .categories-page .category-image {
            height: 220px;
        }

        .categories-page .category-name {
            font-size: 1.2rem;
            padding: 20px;
        }
         .full-width-image-section {
        width: 100%;
        height: 100vh;
        overflow: hidden;
        position: relative;
    }
    
    .full-width-image {
        width: 100%;
        height: auto;
        object-position: center;
    }
    @media (max-width: 768px) {
    .full-width-image-section {
        height: auto; /* Remove fixed height */
        max-height: 50vh; /* Limit maximum height */
        margin-bottom: 0; /* Remove any default margin */
    }
    
    .full-width-image {
        height: auto;
        width: 100%;
    }
    
    .featured-products {
        padding-top: 20px; /* Reduce top padding */
        margin-top: 0; /* Remove any default margin */
    }
}

@media (max-width: 480px) {
    .full-width-image-section {
        max-height: 40vh; /* Even smaller on very small screens */
    }
    
    .featured-products {
        padding-top: 10px; /* Minimal padding */
    }
}
   
    .featured-products {
        padding: 50px 20px;
        background-color: #f9f9f9;
        position: relative;
    }
    
    .featured-products .container {
        max-width: 1200px;
        margin: 0 auto;
        position: relative;
    }
    
    .products-slider {
    position: relative;
    padding: 20px 40px; /* Added horizontal padding */
    overflow: hidden;
    }

    @media (max-width: 768px) {
        .products-slider {
            padding: 20px 30px;
        }
    }

    @media (max-width: 480px) {
        .products-slider {
            padding: 20px 25px;
        }
    }
    
    .products-track {
        display: flex;
        transition: transform 0.5s ease;
        gap: 20px;
        padding: 10px 0;
    }
    
    .product-card {
        background: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        position: relative;
        flex: 0 0 calc(33.333% - 20px);
        min-width: 280px;
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
    text-align: center;
    text-decoration: none;
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
    }
    
    /* Slider Controls */
    /* Slider Controls */
.slider-control {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background-color: #f557ab; /* Pink background */
    color: white; /* White icon */
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    border: none;
    z-index: 10;
    transition: all 0.3s ease;
    opacity: 0.9;
}

.slider-control:hover {
    background-color: white; /* White background on hover */
    color: #f557ab; /* Pink icon on hover */
    transform: translateY(-50%) scale(1.1);
    opacity: 1;
}

.slider-prev {
    left: 20px; /* Increased from 0px to ensure full visibility */
}

.slider-next {
    right: 20px; /* Increased from 0px to ensure full visibility */
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .slider-control {
        width: 40px;
        height: 40px;
    }
    
    .slider-prev {
        left: 10px;
    }
    
    .slider-next {
        right: 10px;
    }
}

@media (max-width: 480px) {
    .slider-control {
        width: 35px;
        height: 35px;
        font-size: 16px;
    }
    
    .slider-prev {
        left: 5px;
    }
    
    .slider-next {
        right: 5px;
    }
}
    .slider-dots {
        display: flex;
        justify-content: center;
        margin-top: 20px;
        gap: 10px;
    }
    
    .slider-dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background-color: #ddd;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .slider-dot.active {
        background-color: #f557ab;
        transform: scale(1.2);
    }
    
    /* Responsive styles */
    @media (max-width: 1024px) {
        .product-card {
            flex: 0 0 calc(50% - 15px);
        }
    }
    
    @media (max-width: 768px) {
        .product-card {
            flex: 0 0 calc(50% - 10px);
            min-width: 220px;
        }
        
        .product-image {
            height: 250px;
        }
        
        .slider-control {
            width: 35px;
            height: 35px;
        }
        
        
    }
    
    @media (max-width: 480px) {
        .product-card {
            flex: 0 0 100%;
            min-width: 100%;
        }
        
        .section-title {
            font-size: 1.5rem;
            margin-bottom: 30px;
        }
        
        .slider-control {
            width: 30px;
            height: 30px;
            font-size: 12px;
        }
    }
    .category-card-link {
    display: block;
    text-decoration: none;
    color: inherit;
}

.category-card-link:hover .category-card {
    transform: translateY(-5px);
    box-shadow: 0 8px 16px rgba(0,0,0,0.15);
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
/* Waist Training Section Styles */
.waist-training-section {
    padding: 60px 20px;
    background-color: #fff;
    text-align: center;
}

.waist-training-container {
    max-width: 1000px;
    margin: 0 auto;
}

.waist-training-title {
    color: #333;
    font-size: 2.2rem;
    margin-bottom: 30px;
    font-weight: 600;
}

.waist-training-content {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 40px;
    margin-bottom: 40px;
}

.waist-training-text {
    flex: 1;
    min-width: 300px;
    text-align: left;
    color: #555;
    line-height: 1.6;
}

.waist-training-benefits {
    flex: 1;
    min-width: 300px;
    text-align: left;
}

.benefit-item {
    display: flex;
    align-items: flex-start;
    margin-bottom: 20px;
}

.benefit-icon {
    color: #f557ab;
    font-size: 24px;
    margin-right: 15px;
    margin-top: 3px;
}

.benefit-text {
    flex: 1;
    color: #333;
    font-weight: 500;
}

.shop-waist-trainers-btn {
    display: inline-block;
    padding: 15px 30px;
    background-color: #f557ab;
    color: white;
    text-decoration: none;
    border-radius: 30px;
    font-weight: 600;
    font-size: 1.1rem;
    transition: all 0.3s ease;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.shop-waist-trainers-btn:hover {
    background-color: #e04d99;
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .waist-training-title {
        font-size: 1.8rem;
    }
    
    .waist-training-content {
        flex-direction: column;
        gap: 30px;
    }
    
    .waist-training-text,
    .waist-training-benefits {
        text-align: center;
    }
    
    .benefit-item {
        justify-content: center;
    }
}

@media (max-width: 480px) {
    .waist-training-title {
        font-size: 1.6rem;
    }
    
    .shop-waist-trainers-btn {
        padding: 12px 25px;
        font-size: 1rem;
    }
}
/* Footer Styles */
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
            background: url('assets/loading.gif') center center no-repeat;
            background-size: contain;
            animation: pulse 2s infinite;
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
        .thank-you-container {
            max-width: 800px;
            margin: 50px auto;
            text-align: center;
            padding: 30px;
        }
        
        .thank-you-icon {
            font-size: 80px;
            color: #2ecc71;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
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
    
    <div class="thank-you-container">
        <div class="thank-you-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        
        <h1>Thank You for Your Order!</h1>
        <p>Your order has been placed successfully with Order ID: #<?php echo htmlspecialchars($order_id); ?></p>
        <p>We've sent a confirmation email to your registered email address.</p>
        
        <a href="products.php" class="btn btn-continue">Continue Shopping</a>
    </div>
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

</div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const content = document.querySelector('.opening-hours-content');
            const container = document.querySelector('.opening-hours');
            
            // Double the content to create seamless looping
            const originalContent = content.textContent.trim();
            content.textContent = originalContent + ' • ' + originalContent;
            
            // Calculate duration based on content width for consistent speed
            const contentWidth = content.scrollWidth / 2;
            const scrollSpeed = 100; // Increased from 50 to make it faster
            const duration = contentWidth / scrollSpeed;
            
            // Apply the animation
            content.style.animation = `scroll ${duration}s linear infinite`;
            content.style.animationDelay = '0s'; // Start immediately
            
            // Reset the animation periodically to prevent jumps
            setInterval(() => {
                content.style.animation = 'none';
                void content.offsetWidth; // Trigger reflow
                content.style.animation = `scroll ${duration}s linear infinite`;
                content.style.animationDelay = '-0.1s';
            }, duration * 1000 / 2); // Reset at half duration for smoother loop

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
        });
        // JavaScript for smooth scrolling of opening hours
        document.addEventListener('DOMContentLoaded', function() {
            // JavaScript for smooth scrolling of opening hours
           
            
            // Sticky Navigation - Simplified Version
            const navBar = document.querySelector('.nav-bar');
            const topBar = document.querySelector('.top-bar');
            
            window.addEventListener('scroll', function() {
                if (window.scrollY > topBar.offsetHeight) {
                    navBar.classList.add('sticky');
                } else {
                    navBar.classList.remove('sticky');
                }
            });
             const carouselItems = document.querySelectorAll('.carousel-item');
            const indicators = document.querySelectorAll('.indicator');
            const prevBtn = document.querySelector('.carousel-control.prev');
            const nextBtn = document.querySelector('.carousel-control.next');
            const blurredFrame = document.querySelector('.blurred-frame');
            
            let currentIndex = 0;
            let interval;
            
            // Set initial blurred background
            updateBlurredBackground();
            
            function updateBlurredBackground() {
                const activeItem = document.querySelector('.carousel-item.active');
                const imgSrc = activeItem.querySelector('img').getAttribute('src');
                blurredFrame.innerHTML = `<img src="${imgSrc}" alt="Blurred Background" class="blurred-bg">`;
            }
            
            function showSlide(index) {
                // Wrap around if at ends
                if (index >= carouselItems.length) {
                    index = 0;
                } else if (index < 0) {
                    index = carouselItems.length - 1;
                }
                
                // Update active class
                carouselItems.forEach(item => item.classList.remove('active'));
                carouselItems[index].classList.add('active');
                
                // Update indicators
                indicators.forEach(ind => ind.classList.remove('active'));
                indicators[index].classList.add('active');
                
                currentIndex = index;
                
                // Update blurred background
                updateBlurredBackground();
            }
            
            function nextSlide() {
                showSlide(currentIndex + 1);
            }
            
            function prevSlide() {
                showSlide(currentIndex - 1);
            }
            
            // Button controls
            nextBtn.addEventListener('click', nextSlide);
            prevBtn.addEventListener('click', prevSlide);
            
            // Indicator controls
            indicators.forEach((indicator, index) => {
                indicator.addEventListener('click', () => {
                    showSlide(index);
                });
            });
            
            // Keyboard navigation
            document.addEventListener('keydown', (e) => {
                if (e.key === 'ArrowRight') {
                    nextSlide();
                } else if (e.key === 'ArrowLeft') {
                    prevSlide();
                }
            });
            
            // Auto-rotate (every 5 seconds)
            function startAutoRotate() {
                interval = setInterval(nextSlide, 5000);
            }
            
            function stopAutoRotate() {
                clearInterval(interval);
            }
            
            // Start auto-rotate
            startAutoRotate();
            
            // Pause on hover
            const carousel = document.querySelector('.carousel');
            carousel.addEventListener('mouseenter', stopAutoRotate);
            carousel.addEventListener('mouseleave', startAutoRotate);
        });
            function updateBlurredBackground() {
            const activeItem = document.querySelector('.carousel-item.active');
            if (activeItem) {
                const imgSrc = activeItem.querySelector('img').getAttribute('src');
                const blurredBg = document.querySelector('.blurred-bg');
                // Only update if the source has changed
                if (blurredBg.getAttribute('src') !== imgSrc) {
                    blurredBg.setAttribute('src', imgSrc);
                }
            }

            }
    // Mobile menu toggle
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
       
        // Add this to your existing JavaScript
        document.addEventListener('DOMContentLoaded', function() {
            // Mobile menu toggle (existing code)
            const menuToggle = document.querySelector('.menu-toggle');
            const navLinks = document.querySelector('.nav-links');
            
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
            
            // Prevent clicks inside dropdown from closing it
            document.querySelectorAll('.dropdown-content').forEach(dd => {
                dd.addEventListener('click', function(e) {
                    e.stopPropagation();
                });
            });
            
            // Existing mobile menu code...
        });
    document.addEventListener('DOMContentLoaded', function() {
            // Mobile swipe functionality for categories
            if (window.innerWidth <= 768) {
                const categoriesGrid = document.querySelector('.categories-grid');
                let isDown = false;
                let startX;
                let scrollLeft;

                categoriesGrid.addEventListener('mousedown', (e) => {
                    isDown = true;
                    startX = e.pageX - categoriesGrid.offsetLeft;
                    scrollLeft = categoriesGrid.scrollLeft;
                });

                categoriesGrid.addEventListener('mouseleave', () => {
                    isDown = false;
                });

                categoriesGrid.addEventListener('mouseup', () => {
                    isDown = false;
                });

                categoriesGrid.addEventListener('mousemove', (e) => {
                    if(!isDown) return;
                    e.preventDefault();
                    const x = e.pageX - categoriesGrid.offsetLeft;
                    const walk = (x - startX) * 2;
                    categoriesGrid.scrollLeft = scrollLeft - walk;
                });

                // Touch events for mobile
                categoriesGrid.addEventListener('touchstart', (e) => {
                    isDown = true;
                    startX = e.touches[0].pageX - categoriesGrid.offsetLeft;
                    scrollLeft = categoriesGrid.scrollLeft;
                });

                categoriesGrid.addEventListener('touchend', () => {
                    isDown = false;
                });

                categoriesGrid.addEventListener('touchmove', (e) => {
                    if(!isDown) return;
                    const x = e.touches[0].pageX - categoriesGrid.offsetLeft;
                    const walk = (x - startX) * 2;
                    categoriesGrid.scrollLeft = scrollLeft - walk;
                });
            }
        
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
        const track = document.getElementById('products-track');
        const products = document.querySelectorAll('.product-card');
        const prevBtn = document.querySelector('.slider-prev');
        const nextBtn = document.querySelector('.slider-next');
        const dotsContainer = document.getElementById('slider-dots');
        
        // Calculate how many products to show at once based on screen width
        function getVisibleCount() {
            if (window.innerWidth < 480) return 1;
            if (window.innerWidth < 768) return 2;
            return 3;
        }
        
        let visibleCount = getVisibleCount();
        let currentIndex = 0;
        let totalSlides = Math.ceil(products.length / visibleCount);
        
        // Create dots
        function createDots() {
            dotsContainer.innerHTML = '';
            for (let i = 0; i < totalSlides; i++) {
                const dot = document.createElement('div');
                dot.classList.add('slider-dot');
                if (i === 0) dot.classList.add('active');
                dot.addEventListener('click', () => goToSlide(i));
                dotsContainer.appendChild(dot);
            }
        }
        
        // Update dots
        function updateDots() {
            const dots = document.querySelectorAll('.slider-dot');
            dots.forEach((dot, index) => {
                dot.classList.toggle('active', index === currentIndex);
            });
        }
        
        // Go to specific slide
        function goToSlide(index) {
            currentIndex = index;
            const slideWidth = products[0].offsetWidth + 20; // 20px gap
            track.style.transform = `translateX(-${currentIndex * visibleCount * slideWidth}px)`;
            updateDots();
        }
        
        // Next slide
        function nextSlide() {
            if (currentIndex < totalSlides - 1) {
                currentIndex++;
            } else {
                currentIndex = 0;
            }
            goToSlide(currentIndex);
        }
        
        // Previous slide
        function prevSlide() {
            if (currentIndex > 0) {
                currentIndex--;
            } else {
                currentIndex = totalSlides - 1;
            }
            goToSlide(currentIndex);
        }
        
        // Initialize slider
        function initSlider() {
            visibleCount = getVisibleCount();
            totalSlides = Math.ceil(products.length / visibleCount);
            createDots();
            goToSlide(0);
        }
        
        // Event listeners
        prevBtn.addEventListener('click', prevSlide);
        nextBtn.addEventListener('click', nextSlide);
        
        // Handle window resize
        window.addEventListener('resize', function() {
            initSlider();
        });
        
        // Initialize on load
        initSlider();
        
        // Auto-rotate slides (optional)
        let slideInterval = setInterval(nextSlide, 5000);
        
        // Pause on hover
        const slider = document.querySelector('.products-slider');
        slider.addEventListener('mouseenter', () => clearInterval(slideInterval));
        slider.addEventListener('mouseleave', () => {
            clearInterval(slideInterval);
            slideInterval = setInterval(nextSlide, 5000);
        });
        document.querySelectorAll('.add-to-cart').forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.getAttribute('data-product-id');
            if (productId) {
                window.location.href = 'product.php?id=' + productId;
            }
        });
    });
    });
     document.addEventListener('DOMContentLoaded', function() {
            const loadingOverlay = document.getElementById('loading-overlay');
            const pageContent = document.querySelector('.page-content');
            const progressBar = document.getElementById('progress-bar');
            
            // Minimum display time for loading overlay (10 seconds)
            const minDisplayTime = 10000;
            let startTime = Date.now();
            
            // Update progress bar every 100ms
            const progressInterval = setInterval(() => {
                const elapsed = Date.now() - startTime;
                const progress = Math.min(100, (elapsed / minDisplayTime) * 100);
                progressBar.style.width = progress + '%';
            }, 100);
            
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
            setTimeout(hideLoadingOverlay, 15000);
        });
    </script>
</body>
</html>