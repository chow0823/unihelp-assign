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
  <link rel="stylesheet" href="style.css"><meta name="viewport" content="width=device-width, initial-scale=1.0">
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

<h1>Welcome to UniHELP</h1>
<h2>Volunteer Dashboard</h2>    
<button type="button" onclick="window.location.href='request_details.php'">Request Details</button><br><br>
<button type="button" onclick="window.location.href='schedule_session.php'">Schedule Session</button>

</form>
</body>
</html>
