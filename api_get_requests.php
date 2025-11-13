<?php
// Lightweight API to return requests as JSON for the admin dashboard
header('Content-Type: application/json; charset=utf-8');
session_start();

// Basic auth: require admin session (optional - still return empty if not admin)
if (!isset($_SESSION['user_id']) || empty($_SESSION['is_admin'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Forbidden']);
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "users";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['error' => 'DB connection failed']);
    exit();
}

$sql = "SELECT id, ticket_id, user_id, fullname, contact, requesttype, description, priority, status, submitted_at FROM requests ORDER BY submitted_at DESC";
$res = $conn->query($sql);
$rows = [];
if ($res) {
    while ($r = $res->fetch_assoc()) {
        // Normalize fields expected by the frontend
        $rows[] = [
            'id' => $r['ticket_id'] ?? $r['id'],
            'name' => $r['fullname'],
            'type' => $r['requesttype'],
            'priority' => $r['priority'] ?? 'Medium',
            'status' => $r['status'] ?? 'New',
            'submitted' => date('M j, Y', strtotime($r['submitted_at'] ?? date('Y-m-d'))),
            'raw' => $r,
        ];
    }
}
$conn->close();

echo json_encode($rows);
exit();

?>
