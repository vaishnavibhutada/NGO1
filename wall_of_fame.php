<?php
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

// Include CSS styling
echo '<link rel="stylesheet" href="wall_of_fame.css">';

// Main heading for the Wall of Fame page
echo "<h1>Wall of Fame</h1>";

// Fetch top 10 donors
$sql = "SELECT full_name, donation_amount FROM donations ORDER BY donation_amount DESC LIMIT 10";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<div class='container'>";
    echo "<table>";
    echo "<tr><th>Name</th><th>Donation Amount (₹)</th></tr>";

    // Highlight the current donor
    $currentName = isset($_GET['name']) ? htmlspecialchars($_GET['name']) : '';
    $currentAmount = isset($_GET['donationAmount']) ? htmlspecialchars($_GET['donationAmount']) : '';

    while ($row = $result->fetch_assoc()) {
        $isCurrent = ($row['full_name'] === $currentName && $row['donation_amount'] == $currentAmount);
        echo "<tr" . ($isCurrent ? " class='highlight'" : "") . ">";
        echo "<td>" . htmlspecialchars($row['full_name']) . "</td>";
        echo "<td>₹" . htmlspecialchars($row['donation_amount']) . "</td>";
        echo "</tr>";
    }

    echo "</table>";
    echo "</div>";
} else {
    echo "<div class='container'><p>No donations found.</p></div>";
}

$conn->close();
?>
