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

$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST['logout'])) {
    $fullname = trim($_POST['fullname']);
    $contact = trim($_POST['contact']);
    $requesttype = $_POST['requesttype'];
    $description = trim($_POST['description']);
    $user_id = $_SESSION['user_id'];

    // Validation
    if (empty($fullname) || empty($contact) || empty($requesttype) || empty($description)) {
        $error = "All fields are required.";
    } elseif (!preg_match('/^[0-9]{11}$/', $contact)) {
        $error = "Contact number must be 11 digits.";
    } else {
        // Generate unique ticket ID
        $year = date('Y');
        $stmt = $conn->prepare("SELECT COUNT(*) FROM requests WHERE YEAR(submitted_at) = ?");
        $stmt->bind_param("i", $year);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();
        $ticket_id = 'BHR-' . $year . '-' . str_pad($count + 1, 6, '0', STR_PAD_LEFT);

        // Insert request
        $stmt = $conn->prepare("INSERT INTO requests (ticket_id, user_id, fullname, contact, requesttype, description) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sissss", $ticket_id, $user_id, $fullname, $contact, $requesttype, $description);
        if ($stmt->execute()) {
            $success = "Request submitted successfully! Your Ticket ID is: <strong>$ticket_id</strong>. Use it to track your request.";
        } else {
            $error = "Error submitting request. Please try again.";
        }
        $stmt->close();
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Submit Request — eBCsH</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <style>
    :root {
      --bg-color: #DAF1DE;
      --new-request-color: #007BFF;
      --track-request-color: #00B050;
      --logout-color: #FD7E7E;
      --font: 'Poppins', sans-serif;
    }
    * { 
      margin: 0; 
      padding: 0; 
      box-sizing: border-box; 
      font-family: var(--font); 
    }
    body { 
      background-color: var(--bg-color); 
      color: #333; 
    }
    header { 
      background-color: #fff; 
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
      background-color: var(--logout-color); 
      color: #fff; 
      border: none; 
      padding: 0.6rem 1.2rem; 
      border-radius: 6px; 
      font-weight: 600; 
      cursor: pointer; 
    }
    .logout-btn i { 
      margin-right: 6px; 
    }
    .page-wrap { 
      max-width: 1000px;
      margin: 28px auto; 
      padding: 0 16px; 
    }
    .container { 
      max-width: 700px; 
      margin: 20px auto; 
      padding: 30px; 
      border-radius: 20px; 
      background: #fff; 
      box-shadow: 0 6px 18px rgba(0,0,0,0.06); 
    }
    .title { 
      font-size: 32px; 
      font-weight: 700; 
      color: #2e7d3a; 
      margin-bottom: 6px; 
    }
    .description { 
      color: #556; 
      margin-bottom: 18px; 
    }
    form { 
      display: flex; 
      flex-direction: column; 
      gap: 18px; 
    }
    .row { 
      display: flex; 
      gap: 20px; 
    }
    .form-group { 
      flex: 1; 
      display: flex; 
      flex-direction: column; 
    }
    .form-label { 
      font-weight: 700; 
      color: #249c3b; 
      margin-bottom: 6px;
    }
    input, select, textarea { 
      padding: 10px; 
      border-radius: 8px; 
      border: 1px solid #e6efe6; 
      background: #fbfffb; 
      outline: none; 
    }
    textarea { 
      min-height: 120px; 
    }
    .submit-button { 
      background: var(--track-request-color); 
      color: #fff; 
      padding: 12px; 
      border-radius: 10px; 
      border: none; 
      font-weight: 700; 
      cursor: pointer;
    }
    .message { 
      padding: 12px; 
      border-radius: 8px;
    }
    .success { 
      background: #e6f8ec; 
      color: #1b6b2b; 
    }
    .error { 
      background: #fff0f0; 
      color: #942020;
    }
    @media (max-width:768px) { 
      .row { 
        flex-direction: column 
      } 
      .page-wrap { 
        padding: 12px 
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
    <form method="POST" action="submitreq.php" style="display:inline">
      <button type="submit" name="logout" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</button>
    </form>
  </header>

  <div class="page-wrap">
    <div class="container">
      <h1 class="title">Submit New Request</h1>
      <p class="description">Please provide detailed information about your request or concern. The barangay will process it and notify you when ready.</p>

      <?php if ($success): ?>
        <div class="message success"><?php echo $success; ?></div>
      <?php elseif ($error): ?>
        <div class="message error"><?php echo $error; ?></div>
      <?php endif; ?>

      <form method="POST" action="submitreq.php">
        <div class="row">
          <div class="form-group">
            <label class="form-label" for="fullname">Full Name</label>
            <input type="text" id="fullname" name="fullname" placeholder="Enter your full name" required />
          </div>
          <div class="form-group">
            <label class="form-label" for="contact">Contact Number</label>
            <input type="tel" id="contact" name="contact" placeholder="09123456789" oninput="this.value=this.value.replace(/[^0-9]/g,'');" required />
          </div>
        </div>

        <div class="form-group">
          <label class="form-label" for="requesttype">Request Type</label>
          <select id="requesttype" name="requesttype" required>
            <option value="" disabled selected>Select the type of request</option>
            <option value="ID">Barangay ID</option>
            <option value="Clearance">Barangay Business Clearance</option>
            <option value="indigency">Certificate of Indigency</option>
            <option value="Residency">Certificate of Residency</option>
            <option value="No Objection">Clearance of No Objection</option>
          </select>
        </div>

        <div class="form-group">
          <label class="form-label" for="description">Detailed Description</label>
          <textarea id="description" name="description" placeholder="Provide details, symptoms, timeline, or assistance needed." required></textarea>
        </div>

        <button type="submit" class="submit-button">SUBMIT</button>
      </form>
    </div>
  </div>
</body>
</html>