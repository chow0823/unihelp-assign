<?php

// Create connection with unihelp database in phpmyadmin
$conn = new mysqli("localhost", "root", "", "unihelp");

// Check connection with unihelp database in phpmyadmin
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>