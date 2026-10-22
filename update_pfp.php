<?php 
session_start();
require "unihelpdb.php";

if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'volunteer') {
    header("Location: login.php");
    exit;
}

$id = $_SESSION['id'];
$skill = $_POST['skills'] ?? [];
$day = $_POST['days'] ?? [];
$mode = $_POST['mode'];
$time = $_POST['time'];

$availableDays = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];
$preferMode = ['Physical', 'Online'];
$availableTime = ['09:00-10:00','10:00-11:00','11:00-12:00','12:00-13:00','13:00-14:00','15:00-16:00','16:00-17:00','17:00-18:00'];

if(!in_array($mode, $preferMode) || !in_array($time, $availableTime)) {
  die("Invalid input!");
}

$day = array_intersect($day, $availableDays);
[$start, $end] = explode('-', $time);

$conn->begin_transaction();
try{
  $vid = 'V_' . $id;
  $stmt = $conn->prepare(
    "INSERT INTO volunteer (account_id, volunteer_id, volunteer_supportmode)
    VALUES (?, ?, ?)
    ON DUPLICATE KEY UPDATE volunteer_supportmode = VALUES(volunteer_supportmode)"
  );
  $stmt->bind_param("sss", $id, $vid, $mode);
  $stmt->execute();

  $stmt = $conn->prepare("DELETE FROM possesses WHERE Volunteer_account_ID = ?");
  $stmt->bind_param("s", $id);
  $stmt->execute();

  $stmt = $conn->prepare("INSERT INTO possesses (Volunteer_account_ID, Skills_skill_ID) VALUES (?, ?)");
  foreach($skill as $sid) {
    $stmt->bind_param("ss", $id, $sid);
    $stmt->execute();
  }

  $stmt = $conn->prepare("DELETE FROM availability WHERE Volunteer_account_ID = ?");
  $stmt->bind_param("s", $id);
  $stmt->execute();

  $ins = $conn->prepare(
    "INSERT IGNORE INTO `available times` (availability_ID, availability_day, start_time, end_time) VALUES (?, ?, ?, ?)"
  );
  $link = $conn->prepare(
    "INSERT INTO availability (Volunteer_account_ID, Availability_availability_ID) VALUES (?, ?)"
  );
  foreach($day as $d){
    $slotID = strtoupper(substr($d, 0, 3)) . '_' . str_replace(':', '', $start);
    $ins->bind_param("ssss", $slotID, $d, $start, $end);
    $ins->execute();
    $link->bind_param("ss", $id, $slotID);
    $link->execute();
  }

  $conn->commit();
  header("Location: volunteer.php?updated=1");
  exit;
} catch(Throwable $e){
  $conn->rollback();
  die("Update failed" . $e ->getMessage());
}