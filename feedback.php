<?php
require "wspart.php";
require_login();
if($_SESSION['role'] !== 'student') 
    die("Only students can provide feedback for sessions.");
$id = $_SESSION['id'];
$ses = $_GET['sess'] ?? $_POST['sess'] ?? '';

$rows = fetch_all($conn,
    "SELECT s.supportses_ID, s.session_datetime, s.session_status, va.account_email AS volunteer_email,
        (SELECT COUNT(*) FROM feedback f WHERE f.Support_sessions_supportses_ID = s.supportses_ID) AS has_feedback
        FROM support_sessions s
        JOIN support_requests r ON s.support_requests_supportreq_ID = r.supportreq_ID
        JOIN account va ON r.Volunteer_volunteer_ID = va.account_id
        WHERE s.supportses_ID = ? AND r.Account_account_ID = ?", "ss", [$ses, $id]);
$s = $rows[0] ?? null;    
if(!$s) die("Session not found or you do not have permission to provide feedback for it.");

$blocked = '';
if($s['session_status'] !== 'Completed') $blocked = "You can only provide feedback for completed sessions.";
elseif($s['has_feedback'] > 0) $blocked = "You have provide feedback for this session already";

$error = '';
if($_SERVER['REQUEST_METHOD'] === 'POST' && !$blocked){
    $rating = (int)($_POST['rating'] ?? 0);
    $comments = trim($_POST['comments'] ?? '');
    if($rating < 1 || $rating > 5) $error="You can only provide rating between 1 to 5";
    elseif(mb_strlen($comments) > 255) $error="Comments provided at most 255 characters";
    else{
        try{
            $fid = generate_id($conn, 'feedback', 'feedback_ID', 'FB');
            $stmt = $conn->prepare("INSERT INTO feedback(feedback_ID, rating, comments, feedback_creation, Support_sessions_supportses_id)
                                    VALUES (?,?,?,NOW(), ?)");
            $stmt->bind_param("siss", $fid, $rating, $comments, $ses);
            $stmt->execute();
            header("Location: session_history.php?msg=feedback");
            exit;
        }
        catch(Throwable $e){
            error_log("feedback: " . $e->getMessage());
            $error = "Your feedback can't be save. Please Try Again.";
        }
    }
}

start_page("Feedback");
?>
<h1>Session Feedback</h1>
<p>Volunteer: <?= escape($s['volunteer_email']) ?></p>
<p>Session: <?= escape(format_date($s['session_datetime']))?></p>

<?php if($blocked): ?>
    <p><?= escape($blocked) ?></p>
    <a href="session_history.php">Back</a>
<?php else: ?>
    <?php if($error) echo "<p>" . escape($error) . "</p>"; ?>
    <form method= "POST" action="feedback.php">
        <input type="hidden" name="sess" value="<?= escape($ses) ?>"> 

    <label for="rating">Rating</label>
    <select id= "rating" name="rating" required>
        <option value="5">5 - Excellent</option >
        <option value="4">4 - Great</option >
        <option value="3">3 - Moderate</option >
        <option value="2">2 - Poor</option >
        <option value="1">1 - Very Poor</option >
    </select>

    <label for="comments">Any additional comments to your session? (Optional)</label>
    <textarea id="comments" name="comments" maxlength="255"></textarea>

    <button type="submit">Submit</button>
    <a href="session_history.php">Back</a>
</form>
<?php endif; ?>
<?php end_page(); ?>


