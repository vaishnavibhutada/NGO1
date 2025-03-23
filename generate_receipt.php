<?php
// Include FPDF library
require('fpdf/fpdf.php');


// Enable error reporting (useful during development)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Database connection settings
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ngo_donations";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize form inputs
    $fullName = htmlspecialchars(trim($_POST['username']));
    $email = htmlspecialchars(trim($_POST['email']));
    $contactNumber = htmlspecialchars(trim($_POST['contact']));
    $state = htmlspecialchars(trim($_POST['state']));
    $city = htmlspecialchars(trim($_POST['city']));
    $address = htmlspecialchars(trim($_POST['address']));
    $pinCode = htmlspecialchars(trim($_POST['pin']));
    $panNumber = htmlspecialchars(trim($_POST['textpan']));
    $donationAmount = (float)$_POST['donationAmount'];
    $paymentMethod = htmlspecialchars(trim($_POST['paymentMethod']));

    // Validate required fields
    if (
        empty($fullName) || empty($email) || empty($contactNumber) ||
        empty($state) || empty($city) || empty($address) ||
        empty($pinCode) || empty($panNumber) || $donationAmount <= 0 ||
        empty($paymentMethod)
    ) {
        echo "<p style='color: red; text-align: center;'>Please fill in all fields correctly.</p>";
    } elseif (!preg_match("/^[0-9]{6}$/", $pinCode)) {
        echo "<p style='color: red; text-align: center;'>Please enter a valid 6-digit PIN code.</p>";
    } elseif (!preg_match("/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/", $panNumber)) {
        echo "<p style='color: red; text-align: center;'>Please enter a valid PAN number.</p>";
    } elseif ($donationAmount <= 0) {
        echo "<p style='color: red; text-align: center;'>Please enter a valid donation amount.</p>";
    } else {
        // Insert data into the database using prepared statement
        $stmt = $conn->prepare("
            INSERT INTO donations (full_name, email, contact_number, state, city, address, pin_code, pan_number, donation_amount, payment_method)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param(
            "ssssssssds",
            $fullName, $email, $contactNumber, $state, $city, $address,
            $pinCode, $panNumber, $donationAmount, $paymentMethod
        );

        if ($stmt->execute()) {
            // Generate PDF receipt after donation is processed successfully
            generate_receipt($fullName, $email, $donationAmount, $paymentMethod);

            echo "<div style='color: green; text-align: center;'>
                    Thank you, $fullName! Your donation of ₹$donationAmount has been successfully recorded.
                  </div>";
        } else {
            // Log the error message to a log file
            error_log("Error: Unable to process donation. SQL Error: " . $stmt->error);
            echo "<p style='color: red; text-align: center;'>Error: Unable to process your donation. Please try again later.</p>";
        }

        $stmt->close();
    }
}

$conn->close();

// Function to generate donation receipt PDF
function generate_receipt($fullName, $email, $donationAmount, $paymentMethod) {
    // Initialize PDF object
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);

    // Title
    $pdf->Cell(0, 10, "Donation Receipt", 0, 1, 'C');
    $pdf->Ln(10);

    // Set font for receipt details
    $pdf->SetFont('Arial', '', 12);

    // Receipt details
    $pdf->Cell(0, 10, "Thank you for your generous donation!", 0, 1);
    $pdf->Ln(5);
    $pdf->Cell(0, 10, "Donor Name: $fullName", 0, 1);
    $pdf->Cell(0, 10, "Email: $email", 0, 1);
    $pdf->Cell(0, 10, "Donation Amount: ₹" . number_format($donationAmount, 2), 0, 1);
    $pdf->Cell(0, 10, "Payment Method: $paymentMethod", 0, 1);
    $pdf->Cell(0, 10, "Date: " . date("Y-m-d"), 0, 1);
    $pdf->Ln(10);
    $pdf->Cell(0, 10, "Warm Regards,", 0, 1);
    $pdf->Cell(0, 10, "NGO Team", 0, 1);

    // Output PDF (this will prompt for download)
    $pdf->Output('D', 'Donation_Receipt_' . $fullName . '.pdf');
}
?>

