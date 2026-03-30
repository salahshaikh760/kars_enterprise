<?php
// signup.php - Handles user registration
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Include the database connection file. This also starts the session.
include 'db_connect.php';

// Function to safely redirect with a custom message (replaces discouraged alerts)
function show_message_and_redirect($message, $location) {
    // This script will replace the body content with a custom styled message before redirecting
    echo "
    <!DOCTYPE html>
    <html lang='en'>
    <head><meta charset='UTF-8'><meta name='viewport' content='width=device-width, initial-scale=1.0'><title>Processing...</title></head>
    <body style='font-family: Poppins, sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; background-color: #f7f7f7; text-align: center;'>
        <div style='padding: 30px; background: white; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);'>
            <h2 style='color: #f5b700;'>" . htmlspecialchars($message) . "</h2>
            <p style='color: #333;'>Redirecting in a moment...</p>
        </div>
        <script>
            setTimeout(function() { window.location.href = '" . $location . "'; }, 2000);
        </script>
    </body>
    </html>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get and sanitize user input from the form (using name attributes from signup.html)
    $fullname = trim($_POST['fullname'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm_password'] ?? '';

    // --- Validation Checks ---
    if ($fullname === '' || $email === '' || $password === '' || $confirm === '') {
        show_message_and_redirect('Please fill in all required fields.', 'signup.html');
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        show_message_and_redirect('Enter a valid email address.', 'signup.html');
    }

    if ($password !== $confirm) {
        show_message_and_redirect('Passwords do not match.', 'signup.html');
    }

    // --- Check if email already exists using prepared statement ---
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $stmt->close();
        show_message_and_redirect('Email already registered. Please login.', 'login.html');
    }
    $stmt->close();

    // --- Insert New User with Hashed Password ---
    // PASSWORD_DEFAULT uses the strongest algorithm available (currently BCRYPT)
    $hashed = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (fullname, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $fullname, $email, $hashed);

    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        // SUCCESS: Redirect to login page
        show_message_and_redirect('Account created successfully! Please login.', 'login.html');
    } else {
        $stmt->close();
        $conn->close();
        // Error handling
        show_message_and_redirect('Registration failed. Database error.', 'signup.html');
    }
} else {
    // Prevent direct access to signup.php
    header('Location: signup.html');
    exit;
}
?>