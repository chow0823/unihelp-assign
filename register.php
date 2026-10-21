<?php 
require 'dbcon.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $role = $_POST['role'];
    $bfpw = $_POST['pw'];
    $pw = password_hash($bfpw, PASSWORD_DEFAULT);
    $cpw = $_POST['cpw'];

    $emailcheck = "SELECT account_id FROM account WHERE account_email = ?";
    $stmt1= $conn->prepare($emailcheck);
    $stmt1->bind_param("s",$email);
    $stmt1->execute();
    $rstmt1 = $stmt1->get_result();



    $acnumcheck = "SELECT account_id FROM account WHERE account_ID LIKE 'STU%' ORDER BY account_ID DESC LIMIT 1";

    $stmt2=$conn->prepare($acnumcheck);
    $stmt2->execute();
    $rstmt2=$stmt2->get_result();
    $frstmt2=$rstmt2->fetch_assoc();

    //Cut away STU and give the amount
    if ($frstmt2){
    $lastid = $frstmt2['account_id'];
    $assignedid = (int) substr($lastid,3);
    $assignedid++;
    } else {
      $assignedid = 1;
    }
    $frid = "STU". str_pad($assignedid,5,"0",STR_PAD_LEFT);
    

    if($rstmt1->num_rows> 0) {
      $msg = "Email is already registered.";
      }elseif($bfpw !== $cpw){
        $msg = "Passwords do not match.";
      }
    else {
      $insert = "INSERT INTO account (account_ID, account_email, account_role, account_pw)
    VALUES (?,?,?,?)";
    $stmt3=$conn ->prepare($insert);
    $stmt3->bind_param("ssss", $frid, $email, $role, $pw);
    $stmt3->execute();

    if($role=="Volunteer"){

    $vacnumcheck = "SELECT volunteer_id FROM volunteer WHERE volunteer_id LIKE 'VOL%' ORDER BY volunteer_id DESC LIMIT 1";

    $stmt4=$conn->prepare($vacnumcheck);
    $stmt4->execute();
    $rstmt3=$stmt4->get_result();
    $frstmt3=$rstmt3->fetch_assoc();

    //Cut away STU and give the amount
    if ($frstmt3){
    $vlastid = $frstmt3['volunteer_id'];
    $vassignedid = (int) substr($vlastid,3);
    $vassignedid++;
    } else {
      $vassignedid = 1;
    }
    $vfrid = "VOL". str_pad($vassignedid,5,"0",STR_PAD_LEFT);
    $insert2 = "INSERT INTO volunteer (account_id, volunteer_id)
    VALUES (?,?)";
    $stmt3=$conn->prepare($insert2);
    $stmt3->bind_param("ss", $frid, $vfrid);
    $stmt3->execute();
    }

    }
}
?>




<!DOCTYPE html>
<html>
<head>
<title>Register Page</title>
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
<h1>Register</h1>
 <!--Login field for registered email and password-->
 <form method="POST" action="register.php">
    <div class="input_group">
  <label for="email">Email</label><br>
  <input type="email" id="email" name="email" required><br><br>
</div>

<div class="input_group">
  <label for="pw">Password</label><br>
  <input type="password" id="pw" name="pw" required>
  </div>

<div class="input_group">
  <label for="cpw">Confirm Password</label><br>
  <input type="password" id="cpw" name="cpw" required>
  </div>

<div class="input_group">
  <label for="role">Role</label><br>
  <select name="role" id="role">
  <option value="student">Student</option>
  <option value="Volunteer">Volunteer</option>
  </select>
  </div>
</div>
  <?php if (isset($error)) echo "<p>" . htmlspecialchars($error) . "</p>"; ?>
 <input type="submit" value="Submit">
</form>
</body>
</html>