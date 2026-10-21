<?php
session_start();
require "dbcon.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $pw = $_POST['password'];

    $stmt = $conn->prepare("SELECT account_id, account_role, account_pw FROM account WHERE account_email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    if($user && password_verify($pw, $user['account_pw'])) {
        session_regenerate_id(true);
        $_SESSION['id'] = $user['account_id'];
        $_SESSION['role'] = strtolower($user['account_role']);

        if($_SESSION['role'] === 'student'){
            header("Location: student.php");
        }
        else{
            header("Location: volunteer.php");
        }
        exit;
    }
    $error="Wrong email or password";
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Login Page</title>
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
<h1>Login</h1>
<p>No Account? <a href="signup.php">Sign Up</a> Now!</p><br>
 <!--Login field for registered email and password-->
 <form method="POST" action="login.php">
    <div class="input_group">
  <label for="email">Email</label><br>
  <input type="email" id="email" name="email" required><br><br>
</div>

<div class="input_group">
  <label for="pw">Password</label><br>
  <input type="password" id="pw" name="password" required>
  </div>
  <p> <a href="forgot_pw.php">Forgot Password?</a> </p>
  <button class="button-33" role="button" type="submit">Login</button>
</div>
  <?php if (isset($error)) echo "<p>" . htmlspecialchars($error) . "</p>"; ?>
</form>
</body>
</html>