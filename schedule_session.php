<?php
require "wspart.php";
require_login();

$id = $_SESSION['id'];
$reqID = $_GET['req_id'] ?? $_POST['req_id'] ?? '';

$rows = fetch_all($conn, 
    "SELECT r.*, sa.account_email AS student_email, va.account_email AS volunteer_email, 
    rs.skill_name AS required_skill, rs.skill_category AS category
    FROM support_requests r
    JOIN account sa ON r.Account_account_ID = sa.account_id
    JOIN account va ON r.Volunteer_volunteer_ID = va.account_id
    LEFT JOIN skills rs ON r.required_skill_ID = rs.skill_ID
    WHERE r.supportreq_ID = ? AND (r.Account_account_ID = ? OR r.Volunteer_volunteer_ID = ?)", "sss", [$reqID, $id, $id]);

$req = $rows[0] ?? null;
if (!$req){
  http_response_code(404);
  die("Request not found or you do not have permission to schedule a session for it.");
}

$vid = $req['Volunteer_volunteer_ID'];
$skills = array_column(fetch_all($conn, 
      "SELECT sk.skill_name FROM possesses p 
      JOIN skills sk ON p.Skills_skill_ID = sk.skill_ID
      WHERE p.Volunteer_volunteer_ID = ?", "s", [$vid]), 'skill_name');
$slots = fetch_all($conn, 
      "SELECT t.vailability_ID, t.availability_day, t.start_time, t.end_time 
      FROM availability a 
      JOIN `available times` t ON t.availability_ID = a.Availability_availability_ID
      WHERE a.Volunteer_account_ID = ?
      ORDER BY FIELD(t.availability_day, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'), 
      t.start_time", "s", [$vid]);

$error = '';
if($_SERVER['REQUEST_METHOD'] === 'POST' && $req['supportreq_status'] === 'Accepted' ){
  $date = $_POST['session_date'] ?? '';
  $slot_id = $_POST['slot'] ?? '';
  $mode = $_POST['mode'] ?? '';

  $slot = null;
  foreach($slots as $s){
    if($s['availability_ID'] === $slot_id)
      $slot = $s;
  }
  $d = DATETIME::createFromFormat('Y-m-d', $date);

  if(!$slot) $error = "Please select a valid volunteer's available time slot.";
  elseif(!$d || $d->format('Y-m-d') !== $date) $error = "Please select a valid date.";
  elseif($date < date('Y-m-d')) $error = "The date cannot be in the past.";
  elseif($d->format('l') !== $slot['availability_day']) $error = "The slot only available on " . $slot['availability_day'] . ".";
  elseif(!in_array($mode, ['Physical', 'Online'])) $error = "Please select a valid session mode.";
  else{
    $dt = $date . ' ' . $slot['start_time'];
    $clash = fetch_all($conn, 
      "SELECT * FROM support_sessions s 
      JOIN support_requests r ON s.support_requests_supportreq_ID = r.supportreq_ID
      WHERE r.Volunteer_volunteer_ID = ? AND s.session_datetime = ? AND s.session_status = 'Scheduled'", "ss", [$vid, $dt]);
      if($clash){
        $error = "The volunteer already has a session scheduled at this time. Please choose another slot or date.";
      }
      else{
        $conn->begin_transaction();
        try{
          $sessionID = generate_id($conn, 'support_sessions', 'supportses_ID', 'SES');
          $stmt = $conn->prepare("INSERT INTO support_sessions 
          (supportses_ID, session_datetime, session_supportmode, session_status, session_completion, support_requests_supportreq_ID) 
          VALUES (?, ?, ?, 'Scheduled', NULL, ?)");
          $stmt->bind_param("ssss", $sessionID, $dt, $mode, $reqID);
          $stmt->execute();

          $stmt = $conn->prepare("UPDATE support_requests SET supportreq_status = 'Scheduled' 
          WHERE supportreq_ID = ? AND supportreq_status = 'Accepted'");
          $stmt->bind_param("s", $reqID);
          $stmt->execute();

          $conn->commit();
          header("Location: session_history.php?msg=scheduled");
          exit();
        }
        catch(Exception $e){
          $conn->rollback();
          error_log("Error scheduling session: " . $e->getMessage());
          $error = "An error occurred while scheduling the session. Please try again.";
        }
}
  }
}

page_start("Schedule Session");
?>
<h1>Schedule Session</h1>
<?php if($error) echo "<p class='notif error'>".escape($error)."</p>"; ?>

<p>Volunteer Email: <?= escape($req['volunteer_email']) ?></p>
<p>Volunteer Skills: <?= escape($skills ? implode(', ', $skills) : 'Not specified') ?></p>
<p>Volunteer Available Slots:
  <?= $slots ? escape(implode('; ', array_map(fn($s) => $s['availability_day'] . ' ' . substr($s['start_time'], 0, 5) . '-' . substr($s['end_time'], 0, 5), $slots))) : 'No available slots' ?></p>
<p>Student Email: <?= escape($req['student_email']) ?></p>
<p>Support Category: <?= escape($req['category'] ?? 'Not specified') ?></p>
<p>Skill Required: <?= escape($req['required_skill'] ?? 'Not specified') ?></p>
<p>Assistance Required: <?= escape($req['request_desc'] ?? 'Not specified') ?></p>
<p>Student Preferred Date &amp; Time: <?= escape(format_datetime($req['preferred_datetime'] )) ?> (<?= escape($req['preferred_support_mode']) ?>)</p>

<?php if($req['supportreq_status'] !== 'Accepted'): ?>
  <p class="notif error">This request is "<?= escape($req['supportreq_status']) ?>". You cannot schedule a session for it.</p>
  <a href="session_history.php">Back</a>
<?php elseif (!$slots): ?>
  <p class="notif error">The volunteer has no set any available slots. You cannot schedule a session for this request.</p>
  <a href="session_history.php">Back</a>
<?php else: ?>
  <h2>Choose Session Details</h2>
  <form method="POST" action="schedule_session.php">
    <input type ="hidden" name="req" value="<?= escape($reqID) ?>">

    <label for="session_date">Time slot:</label>
    <select id="slot" name="slot" required>
      <?php foreach($slots as $s): ?>
        <option value="<?= escape($s['availability_ID']) ?>">
          <?= escape($s['availability_day'] . ', ' . substr($s['start_time'], 0, 5) . ' to ' . substr($s['end_time'], 0, 5)) ?></option>
      <?php endforeach; ?>
    </select><br><br>

    <label for="session_date">Date (must follow slot's day):</label>
    <input type="date" id="date" name="session_date"  min="<?= date('Y-m-d') ?>" required><br><br>

    <label for="mode">Session Mode:</label>
    <select id="mode" name="mode">
      <?php foreach(['Physical', 'Online'] as $m): ?>
        <option value="<?=$m ?>" <?= $m === $req['preferred_support_mode'] ? 'selected' : '' ?>><?=$m ?></option>
      <?php endforeach; ?>
    </select><br><br>

    <button type="submit">Schedule Session</button>
    <a href="session_history.php">Back</a>
  </form>
<?php endif; ?>
<?php page_end(); ?>