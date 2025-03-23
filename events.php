<?php
$conn = new mysqli("localhost", "root", "", "ngo_donations");

$result = $conn->query("SELECT * FROM events");
$events = [];
while ($row = $result->fetch_assoc()) {
    $events[] = $row;
}

echo json_encode($events);
?>
