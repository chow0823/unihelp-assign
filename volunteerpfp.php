<!DOCTYPE html>
<html>
<head>
<title>Volunter Profile</title>
<style>
body {
  margin: 0;
  padding: 0;
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
  <li><a href="volunteer.html">Home</a></li>
  <li><a href="volunteerpfp.html">Profile</a></li>
  <li><a href="">Session History</a></li>
  <li><a href="login.html">Logout</a></li>
</ul>

<h1>Volunteer Profile</h1>
<form method="POST" action="update_pfp.php">    
 <input type="checkbox" id="skill1" name="skills[]" value="SK01">
  <label for="skill1"> IT Support</label><br>
  <input type="checkbox" id="skill2" name="skills[]" value="SK02">
  <label for="skill2"> Assignment Consultation</label><br>
  <input type="checkbox" id="skill3" name="skills[]" value="SK03">
  <label for="skill3"> Mathematics</label><br><br>

  <input type="checkbox" id="mon" name=days[] value="Monday">
  <label for="mon"> Monday</label><br>
  <input type="checkbox" id="tuesday" name=days[] value="Tuesday">
  <label for="tuesday">Tuesday</label><br>
  <input type="checkbox" id="wed" name=days[] value="Wednesday">
  <label for="wed">Wednesday</label><br>
  <input type="checkbox" id="thur"name=days[] value="Thursday">
  <label for="thur">Thursday</label><br>
  <input type="checkbox" id="fri" name=days[] value="Friday">
  <label for="fri">Friday</label><br>
  <input type="checkbox" id="sat" name=days[] value="Saturday">
  <label for="sat">Saturday</label><br>
  <input type="checkbox" id="sun" name=days[] value="Sunday">
  <label for="sun">Sunday</label><br><br>

  <label for="mode">Prefer Support Mode:</label>
  <select name="mode" id="mode">
    <option value="phy">Physical</option>
    <option value="onl">Online</option>
  </select>
  <br><br>
  
  <label for="time">Time Avaliable:</label>
  <select name="time" id="time">
    <option value="morning1">9am to 10am</option>
    <option value="morning2">10am to 11am</option>
    <option value="morning3">11am to 12pm</option>
    <option value="noon1">12pm to 1pm</option>
    <option value="noon2">1pm to 2pm</option>
    <option value="noon3">3pm to 4pm</option>
    <option value="noon4">4pm to 5pm</option>
    <option value="noon5">5pm to 6pm</option>
  </select>
  <br><br>
  <input type="submit" value="Update">

</form>
</body>
</html>