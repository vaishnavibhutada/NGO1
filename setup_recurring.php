<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "ngo_donations");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$donor_name = $_POST['donor_name'];
$donation_amount = $_POST['donation_amount'];
$frequency = $_POST['frequency'];

// Insert the recurring donation data into the database
$sql = "INSERT INTO recurring_donations (donor_name, donation_amount, frequency) 
        VALUES ('$donor_name', $donation_amount, '$frequency')";

if ($conn->query($sql) === TRUE) {
    echo "Recurring donation set up successfully!";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Close the connection
$conn->close();
?>
