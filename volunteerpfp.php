<!DOCTYPE html>
<html>
<head>
<title>Volunter Profile</title>
<style>
form {
  border-radius: 5px;
  background-color: #f2f2f2;
  padding: 20px;
  width: 100%;
  max-width: 560px;
  margin: 30px auto;          
  border: 1px solid var(--primary);
  padding: 40px 30px;
  box-shadow: var(--shadow);
}
.columns{
  display: flex;
  align-items: center;
  gap: 40px;
}
.checkdrop{
  flex: 1;
}
select{
  width: 100%;
  padding: 12px;
  margin: 8px 0;
  display: inline-block;
  border: 1px solid #ccc;
  border-radius: 4px;
  box-sizing: border-box;
}
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
input[type=submit] {
  width: 100%;
  background-color: green;
  color: white;
  padding: 14px;
  margin: 8px 0;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

input[type=submit]:hover {
  background-color:  #45a049;
}
</style>
</head>
<body>
<ul>
  <img src="UniHELP logo.png" alt="logo">
  <li><a href="volunteer.php">Home</a></li>
  <li><a href="volunteerpfp.php">Profile</a></li>
  <li><a href="">Session History</a></li>
  <li><a href="login.php">Logout</a></li>
</ul>

<h1>Volunteer Profile</h1>
<form method="POST" action="update_pfp.php">
<div class="columns">
  <div class="checkdrop">
  <label for="skill">Select your skills:</label><br>
 <input type="checkbox" id="skill1" name="skills[]" value="SK01">
  <label for="skill1"> IT Support</label><br>
  <input type="checkbox" id="skill2" name="skills[]" value="SK02">
  <label for="skill2"> Assignment Consultation</label><br>
  <input type="checkbox" id="skill3" name="skills[]" value="SK03">
  <label for="skill3"> Mathematics</label><br><br>

  <label for="day">Day Available:</label><br>
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
  </div>

  <div class="checkdrop">
  <label for="mode">Prefer Support Mode:</label>
  <select name="mode" id="mode">
    <option value="Physical">Physical</option>
    <option value="Online">Online</option>
  </select>
  <br><br>
  
  <label for="time">Time Avaliable:</label>
  <select name="time" id="time">
    <option value="09:00-10:00">9am to 10am</option>
    <option value="10:00-11:00">10am to 11am</option>
    <option value="11:00-12:00">11am to 12pm</option>
    <option value="12:00-13:00">12pm to 1pm</option>
    <option value="13:00-14:00">1pm to 2pm</option>
    <option value="14:00-15:00">2pm to 3pm</option>
    <option value="15:00-16:00">3pm to 4pm</option>
    <option value="16:00-17:00">4pm to 5pm</option>
    <option value="17:00-18:00">5pm to 6pm</option>
  </select>
  <br><br>
    </div>
</div>
  <input type="submit" value="Update">

</form>
</body>
</html>