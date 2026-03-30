<?php
// login.php - Handles user authentication via MySQLi and password_verify()
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Include the database connection file. This MUST be present.
include 'db_connect.php';

// Function to safely display a message and redirect (to replace discouraged alerts/confirms)
function show_message_and_redirect($message, $location) {
    // This script replaces the body content with a custom styled message before redirecting
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

// Handle POST request from the login form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get user input from the form
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Basic validation
    if ($email === '' || $password === '') {
        show_message_and_redirect('Please enter both email and password.', 'login.html');
    }

    // 1. Prepare SQL statement to safely fetch the user's ID, name, and password hash
    $stmt = $conn->prepare("SELECT id, fullname, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        // 2. Bind the result and fetch the stored hashed password
        $stmt->bind_result($id, $fullname, $hashed_password);
        $stmt->fetch();

        // 3. Verify the submitted password against the stored hash
        if (password_verify($password, $hashed_password)) {
            // Password is correct, start session
            $_SESSION['user_id'] = $id;
            $_SESSION['user_name'] = $fullname;
            $_SESSION['logged_in'] = true;

            $stmt->close();
            $conn->close();

            // Success! Redirect to the main page
            show_message_and_redirect("Login successful! Welcome back, " . htmlspecialchars($fullname), 'index.html');
        } else {
            // Invalid password
            $stmt->close();
            show_message_and_redirect('Invalid email or password.', 'login.html');
        }
    } else {
        // Email not found
        $stmt->close();
        show_message_and_redirect('Invalid email or password. Please sign up if you don\'t have an account.', 'login.html');
    }

    $conn->close();

} else {
    // If accessed directly without POST, redirect to login page
    header('Location: login.html');
    exit;
}
?>