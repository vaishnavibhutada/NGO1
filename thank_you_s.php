<?php
// Check if the donationAmount and name are passed in the URL
if (isset($_GET['donationAmount']) && isset($_GET['name'])) {
    // Sanitize and retrieve the values
    $donationAmount = htmlspecialchars($_GET['donationAmount']);
    $fullName = htmlspecialchars($_GET['name']);

    // Display the thank you message
    echo "<h1>Thank you for your scholarship donation!</h1>";
    echo "<p>Dear $fullName, your donation of ₹$donationAmount has been successfully processed. We appreciate your generosity!</p>";
    echo "<p>A PDF receipt for your donation has been generated. <a href='scholarship_receipt.pdf' download>Download your receipt here</a>.</p>";
} else {
    // Error message if the expected parameters are not found in the URL
    echo "<p>Error: Donation details not found.</p>";
}
?>
