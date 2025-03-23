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

// Pagination setup
$limit = 10;  // Number of records per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;  // Current page number
$start_from = ($page - 1) * $limit;  // Calculate starting point for SQL query

// Query to fetch donations
$sql = "SELECT * FROM donations ORDER BY submitted_at DESC LIMIT $start_from, $limit";
$result = $conn->query($sql);

// Query to count total number of donations
$total_sql = "SELECT COUNT(*) FROM donations";
$total_result = $conn->query($total_sql);
$total_row = $total_result->fetch_row();
$total_donations = $total_row[0];  // Total donations in the table
$total_pages = ceil($total_donations / $limit);  // Calculate total number of pages
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Donations</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        header {
            background-color: #4CAF50;
            color: white;
            padding: 10px 0;
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background-color: white;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .pagination {
            text-align: center;
            margin-top: 20px;
        }
        .pagination a {
            margin: 0 5px;
            padding: 10px 15px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .pagination a:hover {
            background-color: #45a049;
        }
        .pagination .active {
            background-color: #45a049;
        }
        .no-records {
            text-align: center;
            color: red;
            font-size: 18px;
        }
    </style>
</head>
<body>

<header>
    <h1>View Donations</h1>
</header>

<div class="container">
    <h2 style="text-align:center;">Donations List</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Contact</th>
                <th>State</th>
                <th>City</th>
                <th>Address</th>
                <th>PIN</th>
                <th>PAN</th>
                <th>Amount</th>
                <th>Payment Method</th>
                <th>Submitted At</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['full_name']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                        <td><?php echo $row['contact_number']; ?></td>
                        <td><?php echo $row['state']; ?></td>
                        <td><?php echo $row['city']; ?></td>
                        <td><?php echo $row['address']; ?></td>
                        <td><?php echo $row['pin_code']; ?></td>
                        <td><?php echo $row['pan_number']; ?></td>
                        <td><?php echo "₹" . number_format($row['donation_amount'], 2); ?></td>
                        <td><?php echo $row['payment_method']; ?></td>
                        <td><?php echo $row['submitted_at']; ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="12" class="no-records">No donations found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Pagination Links -->
    <div class="pagination">
        <?php
        // Previous page link
        if ($page > 1) {
            echo "<a href='?page=" . ($page - 1) . "'>Previous</a>";
        }

        // Display pages dynamically
        for ($i = 1; $i <= $total_pages; $i++) {
            $active = ($i == $page) ? 'active' : '';
            echo "<a href='?page=$i' class='$active'>$i</a>";
        }

        // Next page link
        if ($page < $total_pages) {
            echo "<a href='?page=" . ($page + 1) . "'>Next</a>";
        }
        ?>
    </div>
</div>

</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
