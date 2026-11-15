<?php 
require "wspart.php";
require_login();

if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    header("Location: login.php");
    exit;
}

start_page("Student Page");
?>
<!DOCTYPE html>
<html>
<head>
<title>Student Homepage</title>
<style>
body {
  margin: 0;
  padding: 0;
  background-image: url('campus.png');
  background-repeat: no-repeat;
  background-attachment: fixed;
  background-size: cover;
}
ul {
  list-style-type: none;
  margin: 0;
  padding: 0;
  overflow: hidden;
  background-color: #333333;
  display: flex;
  align-items: center;
  height: 70px;
  width: 100%;
  justify-content: right;
}
ul li {
  float: right;
}
ul li a {
  display: block;
  color: white;
  padding: 14px 16px;
  text-decoration: none;
}
ul li a:hover {
  background-color: #111111;
}
img{
  height: 190%;
  width: 15%;
  margin-right: auto;
}
h1{
  text-align: center;
}
button{
  display: block;
  margin: 0 auto;
  border-radius: 12px;
  padding: 32px 50px;
  
}
button:hover{
  background-color: lightgray;
  color: black;
}
</style>
</head>
<body>


<h1>Welcome to UniHELP</h1>    
<button type="button" onclick="window.location.href='schedule_request.php'">Request Support</button><br><br>
<button type="button" onclick="window.location.href='student_reqtoses.php'">Schedule Session</button>

</form>
</body>
</html>