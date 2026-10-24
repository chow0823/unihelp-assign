<?php
require "wspart.php";
require_login();
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    header("Location: session_history.php");
    exit();
}

$id = $_SESSION['id'];
$role = $_SESSION['role'];
$ses = $_POST['sess'] ?? '';
$action = $_POST['action'] ?? '';

function back(string $query): void {
    header("Location: session_history.php? . $query");
    exit();
}

$rows = fetch_all($conn, 
    "SELECT s.session_status, r.supportreq_ID
    FROM support_sessions s 
    JOIN support_requests r ON s.support_requests_supportreq_ID = r.supportreq_ID
    WHERE s.supportses_ID = ? AND (r.Account_account_ID = ? OR r.Volunteer_volunteer_ID = ?)", "sss", [$ses, $id, $id]);
$row = $rows[0] ?? null;
if($row) die("Session not found or you do not have permission to modify it.");

if($row['session_status'] !== 'Scheduled') 
    back("err=".urlencode("Only scheduled sessions can be modified."));

if($action === 'complete'){
    if($role !== 'volunteer') back("err=".urlencode("Only volunteers can mark sessions as completed."));
    $status = 'Completed';
    $msg = 'completed';
    $completion = date('Y-m-d H:i:s');
}
elseif($action === 'cancel'){
    $status = 'Cancelled';
    $msg = 'cancelled';
    $completion = null;
}
else {
    die("Invalid action.");
}

$conn->begin_transaction();
try{
    $stmt = $conn->prepare("UPDATE support_sessions SET session_status = ?, session_completion = ? WHERE supportses_ID = ?");
    $stmt->bind_param("sss", $status, $completion, $ses);
    $stmt->execute();

    $stmt = $conn->prepare("UPDATE support_requests SET supportreq_status = ? WHERE supportreq_ID = ?");
    $stmt->bind_param("ss", $status, $row['supportreq_ID']);
    $stmt->execute();

    $conn->commit();
    back("msg=$msg");
}
catch(Exception $e){
    $conn->rollback();
    error_log("Error updating session: " . $e->getMessage());
    back("err=".urlencode("An error occurred while updating the session."));
}