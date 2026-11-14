<?php
require "wspart.php";
require_login();




?>


<h1>Schedule Request</h1>
<div class="details-container">
<p>Volunteer Email: <?= escape($req['volunteer_email']) ?></p>
<p>Volunteer Skills: <?= escape($skills ? implode(', ', $skills) : 'Not specified') ?></p>
<p>Volunteer Available Slots:
  <?= $slots ? escape(implode('; ', array_map(fn($s) => $s['availability_day'] . ' ' . substr($s['start_time'], 0, 5) . '-' . substr($s['end_time'], 0, 5), $slots))) : 'No available slots' ?></p>
<p>Student Email: <?= escape($req['student_email']) ?></p>
<p>Support Category: <?= escape($req['category'] ?? 'Not specified') ?></p>
<p>Skill Required: <?= escape($req['required_skill'] ?? 'Not specified') ?></p>
<p>Assistance Required: <?= escape($req['request_desc'] ?? 'Not specified') ?></p>
<p>Student Preferred Date &amp; Time: <?= escape(format_date($req['preferred_datetime'] )) ?> (<?= escape($req['preferred_supportmode']) ?>)</p>
</div>
<?php if($req['supportreq_status'] !== 'Accepted'): ?>
  <p class="notif error">This request is "<?= escape($req['supportreq_status']) ?>". You cannot schedule a session for it.</p>
  <a href="session_history.php">Back</a>
<?php elseif (!$slots): ?>
  <p class="notif error">The volunteer has not set any available slots. You cannot schedule a session for this request.</p>
  <a href="session_history.php">Back</a>
<?php else: ?>
  <form method="POST" action="schedule_session.php">
    <h2>Choose Session Details</h2>
    <input type ="hidden" name="req_id" value="<?= escape($req['supportreq_ID']) ?>">
     <?php if($error) echo "<p class='notif error'>".escape($error)."</p>"; ?>
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
        <option value="<?=$m ?>" <?= $m === $req['preferred_supportmode'] ? 'selected' : '' ?>><?=$m ?></option>
      <?php endforeach; ?>
    </select><br><br>

    <button type="submit">Schedule Session</button>
    <a class="back-btn" href="session_history.php">Back</a>
  </form>
<?php endif; ?>
<?php end_page(); ?>