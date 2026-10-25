<?php 
session_start();
if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="dashboard_style.css"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Student Homepage</title>
</head>
<body>
<ul>
  <img src="UniHELP logo.png" alt="logo">
  <li><a href="student.php">Home</a></li>
  <li><a href="my_requests.php">My Request</a></li>
  <li><a href="session_history.php">Session History</a></li>
  <li><a href="logout.php">Logout</a></li>
</ul>

<h1>Welcome to UniHELP</h1>    
<h2>Student Dashboard</h2>
<button type="button" onclick="window.location.href='request_support.php'">Request Support</button><br><br>
<button type="button" onclick="window.location.href='session_history.php'">Schedule Session</button>

</form>
</body>
</html>