<?php
// submit_review.php - Handles user feedback submission

// Include the database connection file.
include 'db_connect.php';

// Function to safely display a message and redirect
function show_message_and_redirect($message, $location) {
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

// Handle POST request from the review form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Get and sanitize user input
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $review_text = trim($_POST['message'] ?? ''); // 'message' is the name attribute in the textarea

    // Basic Validation
    if ($name === '' || $email === '' || $review_text === '') {
        show_message_and_redirect('Please fill in all fields before submitting your review.', 'index.html#reviews-contact');
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        show_message_and_redirect('Please enter a valid email address.', 'index.html#reviews-contact');
    }

    // Prepare SQL statement for insertion
    $stmt = $conn->prepare("INSERT INTO reviews (name, email, review_text) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $review_text);

    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        // Success! Redirect back to the review section of the index page
        show_message_and_redirect('Thank you! Your review has been successfully submitted.', 'index.html#reviews-contact');
    } else {
        $stmt->close();
        $conn->close();
        // Failure
        show_message_and_redirect('Submission failed. Please try again later.', 'index.html#reviews-contact');
    }

} else {
    // If accessed directly without POST, redirect to the main page
    header('Location: index.html');
    exit;
}
?>