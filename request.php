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
<title>Request Support</title>
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
<ul>
  <img src="UniHELP logo.png" alt="logo">
  <li><a href="student.php">Home</a></li>
  <li><a href="">My Request</a></li>
  <li><a href="">Session History</a></li>
  <li><a href="logout.php">Logout</a></li>
</ul>

<h1>Support Requests</h1>  

<div class="supporycategory">
<label for="supportcat">Support Category</label><br>
  <select name="supportcat" id="supportcat">
  <option value="">Student</option>
  <option value="Volunteer">Volunteer</option>
  </select>
  </div>


<div class="preferredsupmod">
  <label for="role">Role</label><br>
  <select name="role" id="role">
  <option value="student">Student</option>
  <option value="Volunteer">Volunteer</option>
  </select>
  </div>
</form>
</body>
</html>