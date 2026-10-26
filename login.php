<?php
session_start();
require "unihelpdb.php";

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
<link rel="stylesheet" href="login_register_style.css"><meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<div class="forms">
<h1>Login</h1>
<p>No Account? <a href="register.php">Sign Up</a> Now!</p><br>
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
  <?php if (isset($error)) echo "<p class='error_msg'>" . htmlspecialchars($error) . "</p>"; ?><br>
  <p> <a href="forgot_pw.php">Forgot Password?</a> </p><br>
  <button class="button-33" role="button" type="submit">Login</button>
</div>
</form>
</body>
</html>