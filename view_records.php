<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "ngo_donations");

// Check for connection error
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set pagination limit and calculate start index
$limit = 10;  // Number of records per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Current page from URL
$start_from = ($page - 1) * $limit;  // Start index for the SQL query

// Fetch Donations with pagination
echo "<h1>Donation Records</h1>";
$sql = "SELECT * FROM donations LIMIT $start_from, $limit";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table border='1' cellpadding='10'>
            <tr>
                <th>ID</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Amount</th>
                <th>Payment Method</th>
                <th>Submitted At</th>
            </tr>";

    while ($row = $result->fetch_assoc()) {
        $created_at = isset($row['created_at']) ? $row['created_at'] : 'N/A';  // Handle missing created_at
        echo "<tr>
                <td>" . $row['id'] . "</td>
                <td>" . $row['full_name'] . "</td>
                <td>" . $row['email'] . "</td>
                <td>₹" . number_format($row['donation_amount'], 2) . "</td>
                <td>" . $row['payment_method'] . "</td>
                <td>" . $created_at . "</td>
              </tr>";
    }

    echo "</table>";
} else {
    echo "<p>No donations found.</p>";
}

// Pagination Links for Donations
$total_result = $conn->query("SELECT COUNT(*) FROM donations");
$total_row = $total_result->fetch_row();
$total_donations = $total_row[0];
$total_pages = ceil($total_donations / $limit);  // Calculate total pages

echo "<div class='pagination'>";
for ($i = 1; $i <= $total_pages; $i++) {
    echo "<a href='?page=$i'>$i</a> ";
}
echo "</div>";

// Check if the 'volunteers' table exists
$check_table_query = "SHOW TABLES LIKE 'volunteers'";
$table_result = $conn->query($check_table_query);

if ($table_result->num_rows > 0) {
    // Fetch Volunteers
    echo "<h1>Volunteer Records</h1>";
    $volunteers_sql = "SELECT * FROM volunteers";
    $volunteers_result = $conn->query($volunteers_sql);

    if ($volunteers_result->num_rows > 0) {
        echo "<table border='1' cellpadding='10'>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Skills</th>
                    <th>Availability</th>
                    <th>Joined At</th>
                </tr>";

        while ($row = $volunteers_result->fetch_assoc()) {
            $created_at = isset($row['created_at']) ? $row['created_at'] : 'N/A';
            echo "<tr>
                    <td>" . $row['id'] . "</td>
                    <td>" . $row['name'] . "</td>
                    <td>" . $row['email'] . "</td>
                    <td>" . $row['phone'] . "</td>
                    <td>" . $row['skills'] . "</td>
                    <td>" . $row['availability'] . "</td>
                    <td>" . $created_at . "</td>
                  </tr>";
        }

        echo "</table>";
    } else {
        echo "<p>No volunteers found.</p>";
    }
} else {
    echo "<p>The 'volunteers' table does not exist.</p>";
}

// Close the connection
$conn->close();
?>
