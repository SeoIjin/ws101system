<?php
session_start();
// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: sign-in.php");
    exit();
}
// Handle logout from header
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['logout'])) {
    session_destroy();
    header("Location: sign-in.php");
    exit();
}
// Database connection
$servername = "localhost";
$username = "root";  // Replace with your DB username
$password = "";      // Replace with your DB password
$dbname = "users";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$user_id = $_SESSION['user_id'];
$search_result = null;
$search_error = "";
// Handle search
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ticket_id']) && !isset($_POST['logout'])) {
    $ticket_id = trim($_POST['ticket_id']);
    if (!empty($ticket_id)) {
        $stmt = $conn->prepare("SELECT ticket_id, requesttype, status, submitted_at FROM requests WHERE ticket_id = ? AND user_id = ?");
        $stmt->bind_param("si", $ticket_id, $user_id);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($ticket_id, $requesttype, $status, $submitted_at);
            $stmt->fetch();
            $search_result = ['ticket_id' => $ticket_id, 'requesttype' => $requesttype, 'status' => $status, 'submitted_at' => $submitted_at];
        } else {
            $search_error = "No request found for that ticket ID.";
        }
        $stmt->close();
    } else {
        $search_error = "Please enter a ticket ID.";
    }
}
// Fetch recent requests for the user
$recent_requests = [];
$stmt = $conn->prepare("SELECT ticket_id, requesttype, status, submitted_at FROM requests WHERE user_id = ? ORDER BY submitted_at DESC LIMIT 10");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $recent_requests[] = $row;
}
$stmt->close();
$conn->close();
// Helper function for status color
function statusColor($status) {
    switch (strtoupper($status)) {
        case 'READY': return '#064b38';
        case 'UNDER REVIEW': return '#f39c12';
        case 'COMPLETED': return '#1ea2a8';
        case 'IN PROGRESS': return '#ff6b4a';
        default: return '#6b6f72';
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Track Request — eBCsH</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
<style>
    :root { 
      --bg-color: #DAF1DE; 
      --logout-color: #FD7E7E; 
      --font: 'Poppins',sans-serif 
    }
    * { 
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: var(--font) 
    }
    body {
      background: var(--bg-color);
      color: #223;
    }
    header{
      background: #fff;
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 1rem 2rem;
      box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }
    .header-left {
      display: flex;
      align-items: center;
    }
    .header-left img {
      height: 50px;
      margin-right: 10px;
    }
    .header-left .title {
      font-size: 16px;
      font-weight: 600;
    }
    .logout-btn {
      background: var(--logout-color);
      color: #fff;
      border: none;
      padding: 0.6rem 1.2rem;
      border-radius: 6px;
      font-weight: 600;
      cursor: pointer;
    }
    .frame { 
      max-width: 960px; 
      margin: 28px auto; 
      background: #fff; 
      border-radius: 12px; 
      box-shadow: 0 6px 30px rgba(0,0,0,0.08); 
      overflow: hidden; 
    }
    .container { 
      display: grid; 
      grid-template-columns: 1fr 300px; 
      gap: 20px; 
      padding: 22px 
    }
    .track { 
      background: linear-gradient(180deg,#fafafa,#f6f8f7); 
      border-radius: 10px; 
      padding: 18px 
    }
    .track h2 { 
      margin: 0 0 8px 0; 
      font-size: 18px; 
      color: #064b38; 
    }
    .muted { 
      color: #6b6f72; 
      font-size: 13px; 
    }
    .input-row { 
      display: flex; 
      gap: 10px; 
      margin-top: 10px; 
    }
    .input-row input[type=text] { 
      flex: 1; 
      padding: 10px 12px; 
      border-radius: 8px; 
      border: 1px solid #e1e6e4; 
      background: #fff; 
    }
    .btn { 
      background:#064b38; 
      color: #fff; 
      border: none; 
      padding: 10px 14px; 
      border-radius: 8px;
      cursor: pointer; 
      font-weight: 600; 
    }
    .help { 
      padding: 18px; 
      border-radius: 10px; 
      background: linear-gradient(180deg,#fff,#f7fbf9); 
      border: 1px solid #e9f0ed; 
      text-align: center;
    }
    .icon { 
      width: 56px;
      height: 56px;
      border-radius: 999px;
      background: #f0f4f2;
      display: inline-grid;
      place-items: center;
      font-weight: 700;
      color: #064b38;
      margin: 0 auto 6px;
    }
    .recent { 
      padding: 20px 22px 28px 22px; 
      border-top: 1px solid #eef3ef;
    }
    .tabs { 
      display: flex; 
      gap: 8px; 
    }
    .tab { 
      background: #f2f6f4; 
      padding: 6px 10px; 
      border-radius: 20px; 
      font-weight: 600; 
      color: #064b38; 
      cursor: pointer; 
    }
    .tab.active { 
      background: #064b38; 
      color: #fff;
    }
    .cards { 
      display: grid; 
      grid-template-columns: repeat(2,1fr); 
      gap: 16px; 
      margin-top: 8px; 
    }
    .card { 
      background: linear-gradient(180deg,#fff,#fbfffd); 
      border-radius: 10px; 
      padding: 14px; 
      border: 1px solid #eef4f1;
    }
    .ticket { 
      font-weight: 700; 
      margin-bottom: 8px; 
      display: flex; 
      align-items: center; 
      gap: 8px;
    }
    .dot { 
      width: 10px; 
      height: 10px; 
      border-radius: 99px; 
    }
    .status { 
      font-size: 13px; 
      font-weight: 700; 
    }
    .meta { 
      font-size: 13px; 
      color: #6b6f72; 
      margin-top: 6px; 
    }
    .search-result { 
      margin-top: 14px; 
      padding: 12px; 
      border-radius: 8px; 
      background: #f0f8f0; 
      border: 1px solid #d0e0d0; 
    }
    .search-error { 
      margin-top: 14px; 
      padding: 12px; 
      border-radius: 8px; 
      background: #ffeaea; 
      border: 1px solid #ffdddd; 
      color: #d9534f; 
    }
    @media(max-width:880px) { 
      .container { 
        grid-template-columns: 1fr 
      } 
      .cards { 
        grid-template-columns: 1fr 
      } 
    }
  </style>
</head>
<body>
  <header>
    <div class="header-left">
      <img class="logo-img" src="logo.jfif" alt="Logo">
      <div class="title">Barangay 170-E<br><span style="font-weight:400">Community Portal</span></div>
    </div>
    <form method="POST" action="trackreq.php" style="display:inline">
      <button type="submit" name="logout" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</button>
    </form>
  </header>
  <div class="frame">
    <div class="container">
      <div>
        <div class="track">
          <h2>Track New Request</h2>
          <div class="muted">Ticket ID</div>
          <form method="POST" action="trackreq.php">
            <div class="input-row">
              <input id="searchInput" type="text" name="ticket_id" placeholder="Enter your ticket ID (e.g., BHR-2024-001234)" required />
              <button type="submit" class="btn">Track Request</button>
            </div>
          </form>
          <?php if ($search_result): ?>
            <div class="search-result">
              <strong>Ticket ID:</strong> <?php echo htmlspecialchars($search_result['ticket_id']); ?><br>
              <strong>Type:</strong> <?php echo htmlspecialchars($search_result['requesttype']); ?><br>
              <strong>Status:</strong> <?php echo htmlspecialchars($search_result['status']); ?><br>
              <strong>Submitted:</strong> <?php echo htmlspecialchars($search_result['submitted_at']); ?>
            </div>
          <?php elseif ($search_error): ?>
            <div class="search-error"><?php echo htmlspecialchars($search_error); ?></div>
          <?php endif; ?>
          <div style="margin-top:14px;display:flex;gap:10px;align-items:center">
            <div class="badge" id="liveBadge">In Ready</div>
            <div class="muted" style="font-size:13px">Enter a ticket ID and click "Track Request" to find its status.</div>
          </div>
        </div>
      </div>
      <aside class="help">
        <div class="icon">?</div>
        <div style="font-weight:700">Need Help?</div>
        <div class="muted">• Lost your ID?<br>• Contact Support</div>
        <a href="#" id="contactLink">Contact Support</a>
      </aside>
    </div>
    <div class="recent">
      <div class="recent-top">
        <div style="font-weight:800">My Recent Requests</div>
        <div class="tabs">
          <div class="tab active" data-filter="all">All</div>
          <div class="tab" data-filter="ready">Ready</div>
          <div class="tab" data-filter="completed">Completed</div>
        </div>
      </div>
      <div class="cards" id="cardsContainer">
        <?php foreach ($recent_requests as $request): ?>
          <div class="card">
            <div class="ticket">
              <span class="dot" style="background:<?php echo statusColor($request['status']); ?>"></span>
              <div style="flex:1"><?php echo htmlspecialchars($request['ticket_id']); ?></div>
              <div class="status"><?php echo htmlspecialchars($request['status']); ?></div>
            </div>
            <div style="font-weight:700;margin-top:6px"><?php echo htmlspecialchars($request['requesttype']); ?></div>
            <div class="meta">Submitted: <?php echo htmlspecialchars(date('Y-m-d', strtotime($request['submitted_at']))); ?></div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
  <script>
    // Tabs functionality
    document.querySelectorAll('.tab').forEach(t => {
      t.addEventListener('click', () => {
        document.querySelectorAll('.tab').forEach(x => x.classList.remove('active'));
        t.classList.add('active');
        const filter = t.dataset.filter;
        const cards = document.querySelectorAll('.card');
        cards.forEach(card => {
          const status = card.querySelector('.status').textContent.toLowerCase();
          if (filter === 'all' || status.includes(filter)) {
            card.style.display = 'block';
          } else {
            card.style.display = 'none';
          }
        });
      });
    });
    // Contact link
    document.getElementById('contactLink').addEventListener('click', (e) => {
      e.preventDefault();
      alert('Contacting support... (this demo does not actually send a message)');
    });
  </script>
</body>
</html>