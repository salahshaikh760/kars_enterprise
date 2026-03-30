🚗 KARS ENTERPRISE — Car Rental Web Application
A full-stack car rental website built with HTML, CSS, JavaScript, and PHP, backed by a MySQL database. KARS ENTERPRISE lets users browse vehicles, register/login, and submit reviews — all through a sleek, animated UI.
✨ Features

User Authentication — Secure Sign Up & Login with password_hash() / password_verify()
Responsive Design — Mobile-first layout using CSS Grid & Flexbox
Interactive Carousel — Swiper.js coverflow effect for vehicle selection
Scroll Animations — ScrollReveal.js for smooth section reveals
Review System — Users can submit reviews stored directly in MySQL
Auto-scrolling Brand Banner — Infinite CSS animation showcasing car brands
Clean Redirects — Custom styled redirect pages instead of native browser alerts


🛠️ Tech Stack
LayerTechnologyFrontendHTML5, CSS3, JavaScript (ES6)BackendPHP 8+DatabaseMySQL (MySQLi with prepared statements)LibrariesSwiper.js, ScrollReveal.js, RemixIconFontsGoogle Fonts (Poppins, Syncopate)

📁 Project Structure
kars-enterprise/
│
├── index.html          # Main landing page
├── login.html          # Login page UI
├── signup.html         # Sign up page UI
│
├── login.php           # Handles login authentication
├── signup.php          # Handles user registration
├── submit_review.php   # Handles review form submissions
├── db_connect.php      # MySQL database connection
│
├── styles.css          # Global stylesheet
├── main.js             # JS — nav toggle, Swiper, ScrollReveal
│
└── assets/             # Images (car photos, banners, logos)

🗄️ Database Setup

Create a MySQL database named kars_enterprise.
Run the following SQL to create the required tables:

sqlCREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    review_text TEXT NOT NULL,
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

Update db_connect.php with your local credentials if needed:

php$servername = "localhost";
$username   = "root";
$password   = "";
$database   = "kars_enterprise";
$port       = 3306;

🚀 Getting Started
Prerequisites

XAMPP / WAMP or any local PHP + MySQL server
PHP 8.0+
MySQL 5.7+

Installation

Clone the repository:

bash   git clone https://github.com/salahshaikh760/kars-enterprise.git

Move the project folder into your server's web root:

XAMPP → htdocs/kars-enterprise/
WAMP → www/kars-enterprise/


Set up the database using the SQL above (via phpMyAdmin or MySQL CLI).
Start Apache & MySQL from your XAMPP/WAMP control panel.
Open in browser:

   http://localhost/kars-enterprise/index.html

🔐 Security Highlights

Passwords hashed using PHP's password_hash() with PASSWORD_DEFAULT (bcrypt)
All database queries use prepared statements to prevent SQL injection
User input is validated and sanitized server-side before any DB operation
Email format validated with filter_var() using FILTER_VALIDATE_EMAIL
