<?php
require "unihelpdb.php";

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    $email = trim($_POST['email']);
    $npw = $_POST['npw'];
    $cnpw = $_POST['npw2'];

    if($npw !== $cnpw){
        $message = "Password do no match.";
    }
    elseif (strlen($npw) < 8){
        $message = "Password must be at least 8 characters (including #$)";
    }
    else{
        $stmt = $conn->prepare("SELECT account_ID FROM account WHERE account_email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();

        if(!$user){
            $message = "Email not found!";
        }
        else{
            $hash = password_hash($npw, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE account SET account_pw = ? WHERE account_email = ?");
            $stmt->bind_param("ss", $hash, $email);
            $stmt->execute();

            header("Location: login.php?reset=1");
        }
    }

}
?>
<!DOCTYPE html>
<html>
<head>
<title>Forgot PW Page</title>
</head>
<body>
<style>
:root {
  --primary: #000000;
  --secondary: #FFFFFF;
  --accent: gray;
  --shadow: 8px 8px 0px var(--primary);
}
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  background-color: var(--secondary);
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 100vh;
  padding: 20px;
}
.forms{
    width: 100%;
    max-width: 400px;
    border: 1px solid var(--primary);
    padding: 40px 30px;
    background-color: var(--secondary);
    box-shadow: var(--shadow);
    position: relative;
    border-radius: 12px;
}
.forms::before {
  content: '';
  position: absolute;
  top: 6px;
  left: 6px;
  right: -6px;
  bottom: -6px;
  border: 1px solid var(--primary);
  z-index: -1;
  border-radius: 12px;
}
h1 {
  color: var(--primary);
  margin-bottom: 30px;
  font-size: 28px;
  font-weight: 700;
  text-align: center;
}
.input_group {
  margin-bottom: 20px;
  position: relative;
}
label {
  display: block;
  margin-bottom: 8px;
  font-weight: bold;
  color: var(--primary);
}
input {
  width: 100%;
  padding: 12px 15px;
  border: 2px solid var(--primary);
  background-color: var(--secondary);
  font-size: 16px;
  outline: none;
  transition: all 0.3s;
}
input:focus {
  box-shadow: 4px 4px 0px var(--primary);
}
button {
  width: 100%;
  padding: 12px;
  background-color: var(--accent);
  color: var(--secondary);
  border: 2px solid var(--primary);
  font-size: 16px;
  font-weight: bold;
  cursor: pointer;
  margin-top: 10px;
  transition: all 0.3s;
}
button:hover {
  box-shadow: 4px 4px 0px var(--primary);
  transform: translate(-2px, -2px);
}
</style>
<div class="forms">
<h1>Forgot Password</h1>
<?php if($message) echo "<p>" . htmlspecialchars($message) . "</p>"; ?>    
 <!--Reset password field-->
 <form method= "POST" action="forgot_pw.php">
    <div class="input_group">
  <label for="fname">Email</label><br>
  <input type="text" id="email" name="email" required><br><br>
</div>

  <div class="input_group">
  <label for="npw">Enter New Password</label><br>
  <input type="text" id="npw" name="npw" required><br><br>
</div>
  
  <div class="input_group">
  <label for="lname">New Password Confirmation</label><br>
  <input type="text" id="npw2" name="npw2" required><br><br>
</div>
  <button type="submit">Submit</button>
</form>
</div>
</body>
</html>