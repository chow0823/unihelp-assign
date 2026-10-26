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
<link rel="stylesheet" href="login_register_style.css"><meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<div class="forms">
<h1>Forgot Password</h1>
<p>Remember your password? <a href="login.php">Login</a></p><br>
 <!--Reset password field-->
 <form method= "POST" action="forgot_pw.php">
    <div class="input_group">
  <label for="email">Email</label><br>
  <input type="email" id="email" name="email" required><br><br>
</div>

  <div class="input_group">
  <label for="npw">Enter New Password</label><br>
  <input type="password" id="npw" name="npw" required><br><br>
</div>
  
  <div class="input_group">
  <label for="lname">New Password Confirmation</label><br>
  <input type="password" id="npw2" name="npw2" required><br><br>
  <?php if($message) echo "<p class='error_msg'>" . htmlspecialchars($message) . "</p>"; ?>    
</div>
  <button type="submit">Submit</button>
</form>
</div>
</body>
</html>