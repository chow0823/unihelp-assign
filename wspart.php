<?php
//shared php file for all the pages in the schedule module
session_start();
require_once("unihelpdb.php");

function require_login(): void {
    if (!isset($_SESSION['id'])) {
        header("Location: login.php");
        exit();
    }
}

//escape html special characters to prevent XSS attacks
function escape($v): string {
    return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
}

//display date as "Month Day, Year" format
function format_date($date): string {
    return $date ? date('d/m/y H:i', strtotime($date)) : '-';
}

//SELECT query with bound parameters and return all rows as an associative array
function fetch_all(mysqli $conn, string $query, string $types = '', array $params = []): array {
    $stmt = $conn->prepare($query);
    if($types !== '') $stmt->bind_param($types, ...$params);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

//Function to geenrate ID for the schedule & feedback table
function generate_id(mysqli $conn, string $table, string $col, string $prefix): string {
    $start = strlen($prefix) + 1;
    $row = $conn->query("SELECT COALESCE(MAX(CAST(SUBSTRING($col, $start) AS UNSIGNED)), 0) + 1 AS n FROM `$table`")->fetch_assoc();
    return $prefix . str_pad($row['n'], 5, '0', STR_PAD_LEFT);
}

//Notification function to display a message and redirect to a page after a delay
function notification(): void {
    $msgs = [
        'scheduled' => 'Session Scheduled Successfully!',
        'completed' => 'Session Mark As Completed',
        'cancelled' => 'Session Cancelled',
        'feedback' => 'Thanks for your feedback!',
    ];
    if(isset($_GET['msg']) && $msgs[$_GET['msg']]) echo "<p class='notif'>".escape($msgs[$_GET['msg']])."</p>";
    if (isset($_GET['error'])) echo "<p class='notif error'>".escape($_GET['error'])."</p>";
}

//Function for print the header of html and css for all use case 3 codes
function start_page(string $title): void {
    $home = ($_SESSION['role'] === 'student') ? 'student.php' : 'volunteer.php';
    $profile = ($_SESSION['role'] === 'student') ? 'studentpfp.php' : 'volunteerpfp.php';
    echo "
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset='UTF-8'><meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>" . escape($title) . "</title>   
        <link rel='stylesheet' href='style.css'>
    </head>
    <body>
    <ul>
    <img src='UniHELP logo.png' alt='logo'>
    <li><a href='volunteer.php'>Home</a></li>
    <li><a href='volunteerpfp.php'>Profile</a></li>
    <li><a href='session_history.php'>Session History</a></li>
    <li><a href='logout.php'>Logout</a></li>
    </ul><main>";
}

//Function for the end of all pages in use case 3 codes
function end_page(): void {
    echo "</main></body></html>";
}
        
        