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
  <li><a href="logout.php">Logout</a></li>
</ul>

<h1>Volunteer Profile</h1>
<form method="POST" action="update_pfp.php">
  
  <input type="submit" value="Confirm">

</form>
</body>
</html>