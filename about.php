<?php
session_start();
require_once 'includes/config.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Get current day of week for opening hours
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
    <title>About Us | Gone Waistless</title>  

    <link rel="shortcut icon" href="assets/logogw.png">
    <link rel="preload" href="assets/logo.png" as="image">
    <!-- Load fonts asynchronously -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Parisienne&family=Montserrat:wght@400;600&display=swap" rel="stylesheet" media="print" onload="this.media='all'">
     
    <!-- Load Font Awesome asynchronously -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" media="print" onload="this.media='all'">
   
    <style>
        /* Reuse all styles from index2.php */
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

        /* About page specific styles */
        .about-hero {
            position: relative;
            height: 70vh;
            background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('assets/about.jpeg'); /* Changed image */
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
            padding: 0 20px;
        }
        
        .about-hero-content {
            max-width: 800px;
        }
        
        .about-hero h1 {
            font-family: 'Parisienne', cursive;
            font-size: 4rem;
            margin-bottom: 20px;
            text-shadow: 0 2px 10px rgba(0,0,0,0.5);
        }
        
        .about-hero p {
            font-size: 1.5rem;
            margin-bottom: 30px;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .about-section {
            padding: 80px 20px;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .about-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 50px;
            align-items: center;
        }
        
        .about-content h2 {
            color: #f557ab;
            font-size: 2.5rem;
            margin-bottom: 20px;
        }
        
        .about-content p {
            font-size: 1.1rem;
            line-height: 1.8;
            margin-bottom: 20px;
            color: #555;
        }
        
        .about-image {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        }
        
        .about-image img {
            width: 100%;
            height: auto;
            display: block;
            transition: transform 0.5s ease;
        }
        
        .about-image:hover img {
            transform: scale(1.05);
        }
        
        .mission-section {
            background-color: #f9f9f9;
            padding: 100px 20px;
            text-align: center;
            position: relative; /* Added for pseudo-element positioning */
            overflow: hidden; /* Ensure pseudo-element doesn't overflow */
        }

        /* Decoration image */
        .mission-section::before {
            content: '';
            position: absolute;
            top: 20px;
            left: 20px;
            width: 150px; /* Adjust size as needed */
            height: 150px; /* Adjust size as needed */
            background-image: url('assets/3.jpeg'); /* Decoration image */
            background-size: cover;
            background-position: center;
            border-radius: 50%; /* Make it round */
            opacity: 0.3; /* Adjust opacity */
            z-index: 0;
            pointer-events: none; /* Allow clicks through */
            transform: rotate(-15deg); /* Add some rotation */
        }
        
        .mission-section h2 {
            color: #f557ab;
            font-size: 2.5rem;
            margin-bottom: 50px;
            position: relative; /* Bring text above pseudo-element */
            z-index: 1;
        }
        
        .mission-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            max-width: 1200px;
            margin: 0 auto;
            position: relative; /* Bring cards above pseudo-element */
            z-index: 1;
        }
        
        .mission-card {
            background: white;
            border-radius: 15px;
            padding: 40px 30px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            transition: transform 0.3s ease;
        }
        
        .mission-card:hover {
            transform: translateY(-10px);
        }
        
        .mission-icon {
            font-size: 3rem;
            color: #f557ab;
            margin-bottom: 20px;
        }
        
        .mission-card h3 {
            font-size: 1.5rem;
            margin-bottom: 15px;
            color: #333;
        }
        
        .mission-card p {
            color: #666;
            line-height: 1.7;
        }
        
        /* Removed team-section */
        
        .cta-section {
            padding: 100px 20px;
            background: linear-gradient(135deg, #f557ab, #ff7eb9);
            text-align: center;
            color: white;
        }
        
        .cta-section h2 {
            font-size: 2.8rem;
            margin-bottom: 20px;
            text-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }
        
        .cta-section p {
            font-size: 1.2rem;
            max-width: 700px;
            margin: 0 auto 40px;
        }
        
        .cta-button {
            display: inline-block;
            background: white;
            color: #f557ab;
            padding: 15px 40px;
            border-radius: 30px;
            font-size: 1.1rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
        }
        
        .cta-button:hover {
            background: #f0f0f0;
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
        }
        
        /* Footer styles same as index */
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
        
        .message-time {
            display: block;
            font-size: 11px;
            margin-top: 4px;
            opacity: 0.7;
            text-align: right;
        }
        
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
        /* Responsive adjustments */
        @media (max-width: 992px) {
            .about-grid {
                grid-template-columns: 1fr;
                gap: 30px;
            }
            
            .about-image {
                max-width: 600px;
                margin: 0 auto;
            }
            
            .about-hero h1 {
                font-size: 3.5rem;
            }
            /* Adjust decoration image for tablets */
            .mission-section::before {
                top: 40px;
                left: 40px;
                width: 120px;
                height: 120px;
            }
        }
        
        @media (max-width: 768px) {
            .about-hero {
                height: 50vh;
            }
            
            .about-hero h1 {
                font-size: 3rem;
            }
            
            .about-hero p {
                font-size: 1.2rem;
            }
            
            .mission-section, .cta-section { /* Removed .team-section */
                padding: 70px 20px;
            }
            
            .cta-section h2 {
                font-size: 2.3rem;
            }

            /* Adjust decoration image for smaller tablets */
            .mission-section::before {
                top: 30px;
                left: 30px;
                width: 100px;
                height: 100px;
            }
        }
        
        @media (max-width: 576px) {
            .about-hero h1 {
                font-size: 2.5rem;
            }
            
            .about-hero p {
                font-size: 1rem;
            }
            
            .about-content h2, .mission-section h2 { /* Removed .team-section h2 */
                font-size: 2rem;
            }
            
            .cta-section h2 {
                font-size: 2rem;
            }
            
            .cta-section p {
                font-size: 1rem;
            }

            /* Further adjust decoration image for mobile */
            .mission-section::before {
                top: 15px;
                left: 15px;
                width: 80px;
                height: 80px;
                opacity: 0.2;
            }
            
            /* CSS for very small phone screens (e.g., iPhone 5/SE, smaller Androids) */
            @media (max-width: 375px) {
                .top-bar-container, .nav-container, .footer-container {
                    padding: 0 10px;
                }
                .logo img {
                    height: 60px; /* Smaller logo for tiny screens */
                }
                .opening-hours {
                    max-width: 250px; /* Limit width to prevent overflow */
                    font-size: 0.8rem;
                }
                .social-icons a {
                    font-size: 16px;
                    margin-left: 10px;
                }
                .nav-links li {
                    margin-right: 10px; /* Reduce spacing */
                }
                .nav-links a {
                    padding: 8px 0;
                }
                .nav-icons a {
                    font-size: 18px;
                    margin-left: 10px;
                }
                .about-hero h1 {
                    font-size: 2rem; /* Smaller hero title */
                }
                .about-hero p {
                    font-size: 0.9rem;
                }
                .about-section {
                    padding: 50px 15px; /* Reduce padding */
                }
                .about-content h2, .mission-section h2, .cta-section h2 {
                    font-size: 1.8rem;
                }
                .about-content p, .mission-card p, .cta-section p {
                    font-size: 0.95rem;
                }
                .mission-card {
                    padding: 30px 20px; /* Smaller padding for cards */
                }
                .mission-icon {
                    font-size: 2.5rem;
                }
                .mission-card h3 {
                    font-size: 1.3rem;
                }
                .cta-button {
                    padding: 12px 30px;
                    font-size: 1rem;
                }
                .footer {
                    padding: 30px 10px 15px;
                }
                .footer-column {
                    min-width: 120px;
                }
                .contact-item {
                    font-size: 14px;
                }
                .contact-icon {
                    width: 20px;
                    height: 20px;
                    font-size: 16px;
                }
                .payment-icon {
                    height: 25px;
                }
                .whatsapp-button, .chatbot-toggle {
                    width: 50px;
                    height: 50px;
                    font-size: 24px;
                    bottom: 20px;
                    right: 20px;
                }
                .chatbot-toggle {
                    bottom: 80px; /* Adjust if WhatsApp button is larger */
                }
                .chatbot-container {
                    width: 90%;
                    height: 400px;
                    right: 5%;
                    bottom: 80px;
                }
                .chatbot-header h3 {
                    font-size: 16px;
                }
                .message {
                    font-size: 13px;
                }
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
            <div class="loading-subtext">About Us...</div>
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
    
    
    <div class="about-hero">
        <div class="about-hero-content">
            <h1>About Gone Waistless</h1>
            <p>Discover our journey to redefine shapewear and help you feel confident in your own skin</p>
        </div>
    </div>
    
    <section class="about-section">
        <div class="container">
            <div class="about-grid">
                <div class="about-content">
                    <h2>Our Story</h2>
                    <p>Gone Waistless was founded with a simple mission: to help people look and feel their best through high-quality shapewear and waist training solutions. What started as a small passion project has grown into a beloved South African brand known for its innovative designs and commitment to customer satisfaction.</p>
                    <p>Our journey began when our founder noticed a gap in the market for waist trainers that combined effectiveness with comfort. After months of research and development, we created our first collection of thermal waist trainers that not only sculpted the waistline but also enhanced workouts through increased perspiration.</p>
                    <p>Today, we've expanded our range to include jumpsuits, bodysuits, detox teas, and other premium shapewear that empowers our customers to feel confident in their own skin. We're proud to have helped thousands of South Africans achieve their body goals with minimal workouts.</p>
                </div>
                <div class="about-image">
                    <img src="assets/2.jpeg" alt="Gone Waistless Team"> </div>
            </div>
        </div>
    </section>
    
    <section class="mission-section">
        <div class="container">
            <h2>Our Mission & Values</h2>
            <div class="mission-cards">
                <div class="mission-card">
                    <div class="mission-icon">
                        <i class="fas fa-heart"></i>
                    </div>
                    <h3>Empowerment</h3>
                    <p>We believe everyone deserves to feel confident and beautiful in their own body. Our products are designed to enhance your natural shape and boost your self-esteem.</p>
                </div>
                
                <div class="mission-card">
                    <div class="mission-icon">
                        <i class="fas fa-award"></i>
                    </div>
                    <h3>Quality First</h3>
                    <p>From fabric selection to final stitching, we never compromise on quality. Each product undergoes rigorous testing to ensure durability, comfort, and effectiveness.</p>
                </div>
                
                <div class="mission-card">
                    <div class="mission-icon">
                        <i class="fas fa-leaf"></i>
                    </div>
                    <h3>Sustainable Practices</h3>
                    <p>We're committed to reducing our environmental impact through sustainable manufacturing practices and eco-friendly packaging solutions.</p>
                </div>
                
                <div class="mission-card">
                    <div class="mission-icon">
                        <i class="fas fa-hands-helping"></i>
                    </div>
                    <h3>Community Focus</h3>
                    <p>We actively support body positivity initiatives and local charities that empower women and promote health education in South African communities.</p>
                </div>
            </div>
        </div>
    </section>
    
    <section class="cta-section">
        <div class="container">
            <h2>Ready to Transform Your Silhouette?</h2>
            <p>Discover our premium collection of waist trainers, shapewear, and detox teas designed to help you achieve your body goals with confidence.</p>
            <a href="products.php" class="cta-button">Shop Our Collection</a>
        </div>
    </section>
    
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
            const minDisplayTime = 5000;
            let startTime = Date.now();
            
            // Update progress bar every 100ms
            const progressInterval = setInterval(() => {
                const elapsed = Date.now() - startTime;
                const progress = Math.min(50, (elapsed / minDisplayTime) * 50);
                progressBar.style.width = progress + '%';
            }, 50);
            
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
            setTimeout(hideLoadingOverlay, 5000);
        });
    </script>
</body>
</html>