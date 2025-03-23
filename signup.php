<?php
// Enable error reporting for development
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Database connection settings
$servername = "localhost";  // Or the server IP/host
$username = "root";         // Database username
$password = "";             // Database password
$dbname = "ngo_website";    // Database name

// Create a connection to MySQL
$conn = new mysqli($servername, $username, $password, $dbname);

// Check for a connection error
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize form inputs
    $userName = htmlspecialchars(trim($_POST['username']));
    $email = htmlspecialchars(trim($_POST['email']));
    $password = htmlspecialchars(trim($_POST['password']));
    $confirmPassword = htmlspecialchars(trim($_POST['confirm-password']));

    // Validate input
    if (empty($userName) || empty($email) || empty($password) || empty($confirmPassword)) {
        echo "Please fill in all fields.";
    } elseif ($password !== $confirmPassword) {
        echo "Passwords do not match.";
    } else {
        // Hash the password using bcrypt
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Prepare the SQL statement to insert user data into the database
        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $userName, $email, $hashedPassword);

        // Execute the query and check if the insertion is successful
        if ($stmt->execute()) {
            echo "Signup successful. You can now <a href='login.html'>Login</a>.";
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close the statement and connection
        $stmt->close();
    }
}

// Close the database connection
$conn->close();
?>
