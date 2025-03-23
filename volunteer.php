<?php
// Enable error reporting for debugging (remove in production)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Database connection settings
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ngo_volunteers";

// Create a database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate inputs
    $name = htmlspecialchars(trim($_POST['name']));
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $phone = htmlspecialchars(trim($_POST['phone']));
    $skills = htmlspecialchars(trim($_POST['skills']));
    $availability = htmlspecialchars(trim($_POST['availability']));

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<p style='color: red; text-align: center;'>Invalid email format. Please go back and try again.</p>";
        exit();
    }

    // Insert data into the database
    $stmt = $conn->prepare("
        INSERT INTO volunteers (name, email, phone, skills, availability)
        VALUES (?, ?, ?, ?, ?)
    ");
    $stmt->bind_param("sssss", $name, $email, $phone, $skills, $availability);

    if ($stmt->execute()) {
        echo "
        <div style='text-align: center; padding: 20px;'>
            <h2 style='color: green;'>Thank you, $name! Your application has been submitted successfully.</h2>
        </div>";
    } else {
        echo "<p style='color: red; text-align: center;'>Error: Could not save your application. Please try again later.</p>";
    }

    $stmt->close();
}

$conn->close();
?>
