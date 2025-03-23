<?php
session_start();

// Check if the user is logged in, if not redirect to login page
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}

$servername = "localhost";
$username = "root"; 
$password = "";     
$dbname = "ngo_website"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$userId = $_SESSION['user_id'];

$sql = "SELECT * FROM tasks WHERE assigned_to = ? AND status != 'completed' ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId); 
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Volunteer Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <div class="container">
            <div class="logo">
                <img src="images/logo.png" alt="NGO logo">
            </div>
            <nav>
                <ul>
                    <li><a href="index.html">Home</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="dashboard">
        <h1>Welcome, <?php echo $_SESSION['username']; ?></h1>

        <?php if ($result->num_rows > 0) { ?>
            <h2>Your Assigned Tasks</h2>
            <?php while ($task = $result->fetch_assoc()) { ?>
                <div class="task">
                    <h3><?php echo htmlspecialchars($task['task_name']); ?></h3>
                    <p><?php echo htmlspecialchars($task['description']); ?></p>
                    <p>Status: <?php echo htmlspecialchars($task['status']); ?></p>
                </div>
            <?php } ?>
        <?php } else { ?>
            <h2>No tasks assigned yet.</h2>
        <?php } ?>

    </div>
</body>
</html>

<?php
$conn->close();
?>
