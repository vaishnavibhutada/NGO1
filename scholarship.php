<?php
// Enable error reporting for debugging (remove in production)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Database connection settings
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ngo_scholarship";

// Create a database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if the connection is successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process the form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate input data
    $fullName = htmlspecialchars(trim($_POST['fullname']));
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $phone = htmlspecialchars(trim($_POST['phone']));
    $scholarshipType = htmlspecialchars(trim($_POST['scholarship-type']));
    $amount = floatval($_POST['amount']);
    $message = htmlspecialchars(trim($_POST['message']));
    $paymentMethod = htmlspecialchars(trim($_POST['payment-method']));

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "
        <div style='color: red; text-align: center;'>
            Invalid email format. Please go back and try again.
        </div>";
        exit();
    }

    // SQL query to insert data into the table
    $stmt = $conn->prepare("
        INSERT INTO scholarship_donations 
        (full_name, email, phone, scholarship_type, amount, message, payment_method) 
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param("sssssss", $fullName, $email, $phone, $scholarshipType, $amount, $message, $paymentMethod);

    // Execute the query and provide feedback
    if ($stmt->execute()) {
        echo "
        <style>
            .success-message {
                background-color: #d4edda; /* Light green background */
                color: #155724;           /* Dark green text */
                border: 1px solid #c3e6cb; /* Green border */
                border-radius: 5px;
                padding: 15px;
                font-size: 16px;
                font-family: Arial, sans-serif;
                margin: 20px auto;
                text-align: center;
                width: 80%; /* Adjust width as needed */
                box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1); /* Optional shadow */
            }
        </style>
        <div class='success-message'>
            Thank you, $fullName! Your scholarship donation of $$amount has been successfully recorded. We appreciate your generosity!
        </div>";
    } else {
        echo "
        <style>
            .error-message {
                background-color: #f8d7da; /* Light red background */
                color: #721c24;           /* Dark red text */
                border: 1px solid #f5c6cb; /* Red border */
                border-radius: 5px;
                padding: 15px;
                font-size: 16px;
                font-family: Arial, sans-serif;
                margin: 20px auto;
                text-align: center;
                width: 80%; /* Adjust width as needed */
                box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1); /* Optional shadow */
            }
        </style>
        <div class='error-message'>
            Error: Could not record your donation. Please try again later.
        </div>";
    }

    $stmt->close();
}

$conn->close();
?>
