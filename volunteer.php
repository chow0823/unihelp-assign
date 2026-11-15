<?php 
session_start();
if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'volunteer') {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="dashboard_style.css"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Volunter Homepage</title>
</head>
<body>
<ul>
  <img src="UniHELP logo.png" alt="logo">
  <li><a href="volunteer.php">Home</a></li>
  <li><a href="volunteerpfp.php">Profile</a></li>
  <li><a href="session_history.php">Session History</a></li>
  <li><a href="logout.php">Logout</a></li>
</ul>

<h1>Volunteer Dashboard</h1><br>    
<button type="button" onclick="window.location.href='vol_viewreq.php'">Request Details</button><br><br>
<button type="button" onclick="window.location.href='session_history.php'">Schedule Session</button>

</body>
</html>
