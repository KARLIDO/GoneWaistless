<?php
session_start();
require_once 'includes/config.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Get current day of week (0=Sunday, 1=Monday, etc.)
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

// Reorder array to start with current day
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
    <title>Frequently Asked Questions - Gone Waistless</title>  
    <link rel="shortcut icon" href="assets/logogw.png">
     <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Parisienne&family=Montserrat:wght@400;600&display=swap" rel="stylesheet" media="print" onload="this.media='all'">
     
    <!-- Load Font Awesome asynchronously -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" media="print" onload="this.media='all'">
   
    <style>
        /* Reuse styles from index2.php */
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
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
            display: none;
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

        .nav-bar.sticky + .banner-container {
            margin-top: 60px;
        }
        
        /* Main content */
        .main-content {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }
        
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
        
        /* FAQ Styles */
        .faq-section {
            max-width: 900px;
            margin: 0 auto;
            padding: 40px 20px;
        }
        
        .faq-header {
            text-align: center;
            margin-bottom: 50px;
        }
        
        .faq-header h1 {
            color: #f557ab;
            font-size: 2.5rem;
            margin-bottom: 15px;
            font-weight: 600;
        }
        
        .faq-header p {
            color: #666;
            font-size: 1.1rem;
            max-width: 700px;
            margin: 0 auto;
            line-height: 1.6;
        }
        
        .faq-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
            overflow: hidden;
        }
        
        .faq-item {
            border-bottom: 1px solid #eee;
            transition: all 0.3s ease;
        }
        
        .faq-item:last-child {
            border-bottom: none;
        }
        
        .faq-question {
            padding: 20px 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .faq-question:hover {
            background-color: #fff8fb;
        }
        
        .faq-question h3 {
            margin: 0;
            font-size: 1.2rem;
            color: #333;
            font-weight: 600;
            flex: 1;
        }
        
        .faq-toggle {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: #f557ab;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            transition: all 0.3s ease;
        }
        
        .faq-answer {
            padding: 0 25px;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
            background: #fff;
        }
        
        .faq-answer-content {
            padding: 0 0 25px;
            color: #555;
            line-height: 1.7;
        }
        
        .faq-item.active .faq-answer {
            max-height: 500px;
        }
        
        .faq-item.active .faq-toggle {
            transform: rotate(45deg);
            background: #e04d99;
        }
        
        .size-chart-container {
            margin-top: 20px;
            overflow-x: auto;
        }
        
        .size-chart {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        
        .size-chart th {
            background-color: #f557ab;
            color: white;
            padding: 12px 15px;
            text-align: center;
            font-weight: 600;
        }
        
        .size-chart td {
            padding: 10px 15px;
            border: 1px solid #eee;
            text-align: center;
        }
        
        .size-chart tr:nth-child(even) {
            background-color: #fcf5f9;
        }
        
        .size-chart tr:hover {
            background-color: #fff8fb;
        }
        
        .size-chart .size-label {
            font-weight: 600;
            color: #f557ab;
        }
        
        .contact-promo {
            background: linear-gradient(135deg, #f557ab, #ff7eb9);
            border-radius: 10px;
            padding: 30px;
            margin-top: 50px;
            color: white;
            text-align: center;
        }
        
        .contact-promo h3 {
            margin-top: 0;
            font-size: 1.8rem;
        }
        
        .contact-promo p {
            font-size: 1.1rem;
            max-width: 700px;
            margin: 15px auto 25px;
        }
        
        .contact-btn {
            display: inline-block;
            padding: 12px 30px;
            background: white;
            color: #f557ab;
            border-radius: 30px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .contact-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        }
        
        /* Footer Styles */
        .footer {
            background-color: #383838;
            color: #fff;
            padding: 40px 20px 20px;
            font-size: 14px;
            margin-top: 60px;
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
        
        /* WhatsApp Button */
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
        
        /* Chatbot */
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
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .faq-header h1 {
                font-size: 2rem;
            }
            
            .faq-question h3 {
                font-size: 1.1rem;
            }
            
            .contact-promo {
                padding: 20px;
            }
            
            .contact-promo h3 {
                font-size: 1.5rem;
            }
            
            .nav-links {
                display: none;
                flex-direction: column;
                width: 100%;
                background-color: #f557ab;
                position: fixed;
                top: 60px;
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
        }

        @media (max-width: 480px) {
            .faq-header h1 {
                font-size: 1.8rem;
            }
            
            .faq-question {
                padding: 15px;
            }
            
            .faq-question h3 {
                font-size: 1rem;
            }
            
            .contact-promo h3 {
                font-size: 1.3rem;
            }
            
            .contact-promo p {
                font-size: 1rem;
            }
            
            .contact-btn {
                padding: 10px 25px;
            }
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
    
    <div class="main-content">
        <section class="faq-section">
            <div class="faq-header">
                <h1>Frequently Asked Questions</h1>
                <p>Find answers to common questions about our waist trainers, sizing, and more. If you can't find what you're looking for, feel free to contact us.</p>
            </div>
            
            <div class="faq-container">
                <div class="faq-item">
                    <div class="faq-question">
                        <h3>What is waist training?</h3>
                        <div class="faq-toggle">+</div>
                    </div>
                    <div class="faq-answer">
                        <div class="faq-answer-content">
                            <p>Waist training is the process of slimming and reshaping your waist by wearing a garment known as a waist trainer, corset, waist cincher, girdle and body shaper. Waist training can be done with any normal daily activity as long as you have the right waist training garment. We at Gone Waistless define waist training as one of the best means to tackle your body image goals and to introduce you to the Snatched doll inside you.</p>
                        </div>
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question">
                        <h3>How does a waist trainer work?</h3>
                        <div class="faq-toggle">+</div>
                    </div>
                    <div class="faq-answer">
                        <div class="faq-answer-content">
                            <p>Our waist trainers work by using a scientific method known as thermogenics (increasing heat at a certain area through metabolic stimulation). Thermogenics is initiated by compressing the waist with a certain type of heat-storing material. We make this material by a certain combination of the right percentage of latex and polyester. Combining this action with your daily activity for a period time is what leads your body to mold into the shape of an hourglass.</p>
                        </div>
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question">
                        <h3>Does waist training work, and how fast will I see results?</h3>
                        <div class="faq-toggle">+</div>
                    </div>
                    <div class="faq-answer">
                        <div class="faq-answer-content">
                            <p>The question is, will you work and how willing are you to see results. The garment in itself works best as a supplement that makes you gain results rapidly. Given that it's a supplement, you need to be doing other things that work towards achieving your goals. Wearing a waist trainer by itself will only show short-term results. Other companies will blatantly lie, stating that you will lose over 10 centimeters permanently, just by wearing the garment, but this is not true. We at Gone Waistless have worked hard to create the ideal waist trainer. You will lose some inches by wearing it by itself but we highly recommend you to also be active. This is because we do not just want to sell you a garment, we want you to be successful enough to unleash the snatched doll in you!</p>
                        </div>
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question">
                        <h3>How do I find my size?</h3>
                        <div class="faq-toggle">+</div>
                    </div>
                    <div class="faq-answer">
                        <div class="faq-answer-content">
                            <p>Please refer to our comprehensive size chart below to find your perfect fit:</p>
                            <div class="size-chart-container">
                                <table class="size-chart">
                                    <tr>
                                        <th>SIZE</th>
                                        <th colspan="2">WAIST</th>
                                        <th colspan="2">CENTER FRONT</th>
                                        <th colspan="2">CENTER BACK</th>
                                    </tr>
                                    <tr>
                                        <th></th>
                                        <th>INCH</th>
                                        <th>CM</th>
                                        <th>INCH</th>
                                        <th>CM</th>
                                        <th>INCH</th>
                                        <th>CM</th>
                                    </tr>
                                    <tr>
                                        <td class="size-label">S</td>
                                        <td>26-28.5</td>
                                        <td>66-72</td>
                                        <td>12.5</td>
                                        <td>32.5</td>
                                        <td>10.5</td>
                                        <td>27</td>
                                    </tr>
                                    <tr>
                                        <td class="size-label">M</td>
                                        <td>28.5-31</td>
                                        <td>72-78</td>
                                        <td>12.5</td>
                                        <td>32.5</td>
                                        <td>10.5</td>
                                        <td>27</td>
                                    </tr>
                                    <tr>
                                        <td class="size-label">L</td>
                                        <td>31-33.5</td>
                                        <td>78-84</td>
                                        <td>13</td>
                                        <td>33.5</td>
                                        <td>11</td>
                                        <td>28.5</td>
                                    </tr>
                                    <tr>
                                        <td class="size-label">XL</td>
                                        <td>33.5-36</td>
                                        <td>84-90</td>
                                        <td>13</td>
                                        <td>33.5</td>
                                        <td>11</td>
                                        <td>28.5</td>
                                    </tr>
                                    <tr>
                                        <td class="size-label">2XL</td>
                                        <td>36-38</td>
                                        <td>90-96</td>
                                        <td>13</td>
                                        <td>33.5</td>
                                        <td>11</td>
                                        <td>28.5</td>
                                    </tr>
                                    <tr>
                                        <td class="size-label">3XL</td>
                                        <td>38-40</td>
                                        <td>96-102</td>
                                        <td>13</td>
                                        <td>33.5</td>
                                        <td>11</td>
                                        <td>28.5</td>
                                    </tr>
                                    <tr>
                                        <td class="size-label">4XL</td>
                                        <td>40-42.5</td>
                                        <td>102-108</td>
                                        <td>13</td>
                                        <td>33.5</td>
                                        <td>11</td>
                                        <td>28.5</td>
                                    </tr>
                                    <tr>
                                        <td class="size-label">5XL</td>
                                        <td>42.5-45</td>
                                        <td>108-114</td>
                                        <td>13</td>
                                        <td>33.5</td>
                                        <td>11</td>
                                        <td>28.5</td>
                                    </tr>
                                    <tr>
                                        <td class="size-label">6XL</td>
                                        <td>45-47.5</td>
                                        <td>114-120</td>
                                        <td>13</td>
                                        <td>33.5</td>
                                        <td>11</td>
                                        <td>28.5</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question">
                        <h3>How do I put on my waist trainer?</h3>
                        <div class="faq-toggle">+</div>
                    </div>
                    <div class="faq-answer">
                        <div class="faq-answer-content">
                            <p>Putting on a waist trainer can be challenge. First you have to ensure that the curvy area is facing downwards because that's where the hips connect to the waist. Put the garment behind your back and STRETCH it out both sides. Start hooking the garment from the bottom to the top, to your comfort.</p>
                        </div>
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question">
                        <h3>Is my waist trainer too tight?</h3>
                        <div class="faq-toggle">+</div>
                    </div>
                    <div class="faq-answer">
                        <div class="faq-answer-content">
                            <p>First of all, if you are feeling uncomfortable the first time, understand that it takes time for your body to get used to the garment. It's almost as if you just dove into cold water. Your body needs to adjust to the temperature. With that being said, the first day may seem overwhelming but that is normal. However, if you feel to uncomfortable, and you are having some pains on your waist then your garment is too tight. Also, if breathing is too much of a problem, that is also a sign that its too tight. The good thing is, all our waist trainers are adjustable. So you can hook on to an outer row/adjust tightening straps so as to reduce the tightness. The waist trainer needs to smoothly and perfectly mold your waist. It needs to feet like a glove.</p>
                        </div>
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question">
                        <h3>Does a waist trainer only work on my waist?</h3>
                        <div class="faq-toggle">+</div>
                    </div>
                    <div class="faq-answer">
                        <div class="faq-answer-content">
                            <p>No. Our waist trainers have a lot of benefits apart from just slimming your waist. It also helps your posture, reduce your lower back fat, love handles and reduce your lower tummy. If you get a waist trainer with straps, it will do all the things a normal waist trainer does plus lifting up your bust, aligning your shoulders and upper back properly to better help your posture even more.</p>
                        </div>
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question">
                        <h3>Can I sleep in my waist trainer?</h3>
                        <div class="faq-toggle">+</div>
                    </div>
                    <div class="faq-answer">
                        <div class="faq-answer-content">
                            <p>We do not recommend you sleep in your waist trainer. We always emphasize that over doing it is not a good idea.</p>
                        </div>
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question">
                        <h3>Is waist training unhealthy and does it affect my organs?</h3>
                        <div class="faq-toggle">+</div>
                    </div>
                    <div class="faq-answer">
                        <div class="faq-answer-content">
                            <p>Absolutely not. There has been a lot of criticism on the negative effects of waist training but it is not true. The media is confusing modern day latex waist training to old school corsets. These are two very different garments. You cannot find any evidence on the negative effects of latex waist training but you can find thousands of proof of the negative effects of corset training. However, we do recommend that you do no over do it. Too much of everything is poison. Do not do over 12 hours a day of waist training because that is not good whatsoever.</p>
                        </div>
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question">
                        <h3>I just had a baby, how long should I wait until I try one of your products?</h3>
                        <div class="faq-toggle">+</div>
                    </div>
                    <div class="faq-answer">
                        <div class="faq-answer-content">
                            <p>Congratulations! You can typically wear a waist trainer as soon as one week after giving birth. If you had a c-section, it is often advisable to wait two weeks. However, everybody is different, so please consult a doctor for a professional opinion.</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="contact-promo">
                <h3>Still have questions?</h3>
                <p>Our customer support team is ready to help you with any additional questions you may have about our products or services.</p>
                <a href="tel:+27798118465" class="contact-btn">
                    <i class="fas fa-phone"></i> Contact Us Now
                </a>
            </div>
        </section>
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
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/2a/Mastercard-logo.svg/1280px-Mastercard-logo.svg.png" 
                         alt="MasterCard" class="payment-icon" title="MasterCard">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/5e/Visa_Inc._logo.svg/1280px-Visa_Inc._logo.svg.png" 
                         alt="Visa Card" class="payment-icon" title="Visa Card">
                </div>
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

    <script>
        // FAQ Toggle Functionality
        document.querySelectorAll('.faq-question').forEach(question => {
            question.addEventListener('click', () => {
                const faqItem = question.parentElement;
                faqItem.classList.toggle('active');
            });
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
    
        
        // Opening hours scrolling animation
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
        
    </script>
</body>
</html>