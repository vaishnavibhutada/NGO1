<?php
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
    
    // Capture the public acknowledgment checkbox value
    $publicAcknowledgment = isset($_POST['public_acknowledgment']) ? 'Yes' : 'No';

    // Validate required fields
    if (
        empty($fullName) || empty($email) || empty($contactNumber) ||
        empty($state) || empty($city) || empty($address) ||
        empty($pinCode) || empty($panNumber) || $donationAmount <= 0 ||
        empty($paymentMethod)
    ) {
        echo "<p style='color: red; text-align: center;'>Please fill in all fields correctly.</p>";
    } else {
        // Insert data into the database
        $stmt = $conn->prepare("
            INSERT INTO donations (full_name, email, contact_number, state, city, address, pin_code, pan_number, donation_amount, payment_method, public_acknowledgment)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param(
            "ssssssssdsd",
            $fullName, $email, $contactNumber, $state, $city, $address,
            $pinCode, $panNumber, $donationAmount, $paymentMethod, $publicAcknowledgment
        );

        if ($stmt->execute()) {
            // Generate the donation receipt PDF
            require('fpdf/fpdf.php');
            $pdf = new FPDF();
            $pdf->AddPage();
            $pdf->SetFont('Arial', 'B', 16);
            $pdf->Cell(0, 10, "Donation Receipt", 0, 1, 'C');
            $pdf->Ln(10);
            $pdf->SetFont('Arial', '', 12);

            // NGO Logo Section (optional)
            $pdf->Image('images/logo.png', 10, 10, 40); // Adjust size and position
            $pdf->Ln(20); // Adjust space for logo

            // NGO Information (Address, Contact, etc.)
            $pdf->Cell(0, 10, "NGO Name: XYZ Foundation", 0, 1);
            $pdf->Cell(0, 10, "Address: 123, NGO Street, City, State, PIN", 0, 1);
            $pdf->Cell(0, 10, "Contact: +91-XXXX-XXXXXX | Email: info@ngo.org", 0, 1);
            $pdf->Ln(10);

            // Receipt Heading
            $pdf->SetFont('Arial', 'B', 14);
            $pdf->Cell(0, 10, "Receipt Details", 0, 1, 'L');
            $pdf->SetFont('Arial', '', 12);
            $pdf->Cell(0, 10, "-----------------------------------------------------", 0, 1, 'L');
            $pdf->Ln(5);

            // Donation Details
            $pdf->Cell(0, 10, "Donor Name: $fullName", 0, 1);
            $pdf->Cell(0, 10, "Email: $email", 0, 1);
            $pdf->Cell(0, 10, "Phone: $contactNumber", 0, 1);
            $pdf->Cell(0, 10, "Donation Amount: ₹$donationAmount", 0, 1);
            $pdf->Cell(0, 10, "Payment Method: $paymentMethod", 0, 1);
            $pdf->Cell(0, 10, "PAN Number: $panNumber", 0, 1);
            $pdf->Ln(10);

            // Acknowledgement and Sign-off
            $pdf->SetFont('Arial', 'I', 12);
            $pdf->MultiCell(0, 10, "Thank you for your generous contribution to the XYZ Foundation. Your donation is greatly appreciated and will help us continue our work. Please keep this receipt for your records. Donations are tax-deductible as per Indian tax laws.\n\nWarm regards,\nXYZ Foundation Team");

            // Footer (Optional)
            $pdf->SetY(-15);
            $pdf->SetFont('Arial', 'I', 8);
            $pdf->Cell(0, 10, 'www.ngo.org | NGO Registration No: 123456789 | GSTIN: ABC123456789', 0, 0, 'C');
            
            // Output PDF with a dynamic name to avoid overwrite
            $receiptFile = "donation_receipt_" . time() . ".pdf";  // Unique file name using timestamp
            // Save PDF to file
            $pdf->Output('F', 'donation_receipt.pdf'); 

            // Include the thank_you.php file
            $_GET['donationAmount'] = $donationAmount;
            $_GET['name'] = $fullName;
            include 'thank_you.php';


        }  else {
            echo "<p style='color: red; text-align: center;'>Error: Unable to process your donation. Please try again later.</p>";
        }

        $stmt->close();
    }
}

$conn->close();
?>
