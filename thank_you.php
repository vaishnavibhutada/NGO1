<?php
// Check if the donationAmount and name are passed in the URL
if (isset($_GET['donationAmount']) && isset($_GET['name'])) {
    // Sanitize and retrieve the values
    $donationAmount = htmlspecialchars($_GET['donationAmount']);
    $fullName = htmlspecialchars($_GET['name']);

    // Display the thank you message
    echo "<h1>Thank you for your donation!</h1>";
    echo "<p>Dear $fullName, your donation of ₹$donationAmount has been successfully processed. We appreciate your generosity!</p>";
    
    // Check if the receipt file exists
    if (file_exists('donation_receipt.pdf')) {
        echo "<p>A PDF receipt for your donation has been generated. <a href='donation_receipt.pdf' download>Download your receipt here</a>.</p>";
    } else {
        echo "<p>Unfortunately, we couldn't generate the receipt at this time. Please try again later.</p>";
    }

    // JavaScript to redirect to wall_of_fame.php after 5 seconds
    echo "<p>You will be redirected to the Wall of Fame in 5 seconds.</p>";
    echo "<script>
            setTimeout(function() {
                window.location.href = 'wall_of_fame.php';
            }, 5000);
          </script>";

} else {
    // Error message if the expected parameters are not found in the URL
    echo "<p>Error: Donation details not found.</p>";
}
?>
