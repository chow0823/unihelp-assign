<?php
require "wspart.php";
require_login();

$id = $_SESSION['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reqID  = $_POST['req_id'] ?? '';
    $action = $_POST['action'] ?? '';

    if (in_array($action, ['Accepted', 'Rejected'])) {
        $stmt = $conn->prepare("UPDATE support_requests SET supportreq_status = ? 
                            WHERE supportreq_ID = ? 
                           AND Volunteer_volunteer_ID = ? 
                            AND supportreq_status = 'Pending'");
        $stmt->bind_param("sss", $action, $reqID, $id);
        $stmt->execute();
        $stmt->close();

        header("Location: " . basename($_SERVER['PHP_SELF']));
        exit();
    }
}

$requests = fetch_all($conn, 
    "SELECT r.*, 
            sa.account_email AS student_email, va.account_email AS volunteer_email, 
            rs.skill_name AS required_skill, rs.skill_category AS category
      FROM support_requests r
     JOIN account sa ON r.Account_account_ID = sa.account_id
       JOIN account va ON r.Volunteer_volunteer_ID = va.account_id
       LEFT JOIN skills rs ON r.required_skill_ID = rs.skill_ID
      WHERE r.Account_account_ID = ? OR r.Volunteer_volunteer_ID = ?
      ORDER BY r.preferred_datetime DESC","ss", [$id, $id]
);

start_page("My Support Requests");
?>

<style>
  body {
    margin: 0;
    padding: 0 0 30px 0;
    font-family: Arial, sans-serif;
    background-image: url('campus.png');
    background-repeat: no-repeat;
    background-attachment: fixed;
    background-size: cover;
  }
ul {
    list-style-type: none;
    margin: 0;
    padding: 0;
    overflow: hidden;
    background-color: #333333;
    display: flex;
    align-items: center;
    height: 70px;
    width: 100%;
    justify-content: right;
  }

  ul li {
    float: right;
  }

  ul li a {
    display: block;
    color: white;
    padding: 14px 16px;
    text-decoration: none;
  }

  ul li a:hover {
    background-color: #111111;
  }

  ul img {
    height: 190%;
    width: 15%;
    margin-right: auto;
  }

  h1 {
    text-align: center;
  }

  .table-box {
    max-width: 1100px;
    margin: 20px auto;
    padding: 25px 30px;
    background-color: rgba(255, 255, 255, 0.9);
    border: 2px solid #000;
    border-radius: 10px;
    box-sizing: border-box;
  }

  table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
    background: #fff;
  }

  th, td {
    border: 2px solid #000;
    padding: 12px 10px;
    text-align: center;
    word-break: break-word;
  }

  th {
    background-color: #f4f4f4;
    font-weight: bold;
  }

  tr:hover td {
    background-color: #f9f9f9;
  }

  .action-btn {
    display: inline-block;
    padding: 7px 16px;
    font-size: 14px;
    border-radius: 25px;
    border: 2px solid #000;
    background: #fff;
    color: #000;
    text-decoration: none;
    cursor: pointer;
    margin: 2px;
  }

  .action-btn:hover {
    background: #000;
    color: #fff;
  }
</style>

<h1>My Support Requests</h1>

<div class="table-box">
  <a href="schedule_request.php" class="action-btn">+ New Request</a>

  <?php if ($requests): ?>
    <table>
      <thead>
        <tr>
          <th>Request ID</th>
          <th>Role</th>
          <th>Skill</th>
          <th>Support Mode</th>
          <th>Preferred Date &amp; Time</th>
          <th>Description</th>
          <th>Student / Volunteer</th>
          <th>Status</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($requests as $r): ?>
          <?php $isAssignedVolunteer = ($r['Volunteer_volunteer_ID'] === $id); ?>
          <tr>
            <td><?= escape($r['supportreq_ID']) ?></td>
            <td><?= $isAssignedVolunteer ? "Volunteer (Incoming)" : "Student (Outgoing)" ?></td>
            <td><?= escape($r['required_skill'] ?? 'Not specified') ?></td>
            <td><?= escape($r['preferred_supportmode']) ?></td>
            <td><?= escape(format_date($r['preferred_datetime'])) ?></td>
            <td><?= nl2br(escape($r['request_desc'])) ?></td>
            <td>
              <?php if ($isAssignedVolunteer): ?>
                Student: <?= escape($r['student_email']) ?>
              <?php else: ?>
                Volunteer: <?= escape($r['volunteer_email']) ?>
              <?php endif; ?>
            </td>
            <td><?= escape($r['supportreq_status']) ?></td>
            <td>
              <?php if ($isAssignedVolunteer && $r['supportreq_status'] === 'Pending'): ?>
                <form method="POST" action="<?= escape(basename($_SERVER['PHP_SELF'])) ?>" style="display:inline; margin:0; padding:0; border:none; background:none;">
                  <input type="hidden" name="req_id" value="<?= escape($r['supportreq_ID']) ?>">
                  <button type="submit" name="action" value="Accepted" class="action-btn">Accept</button>
                  <button type="submit" name="action" value="Rejected" class="action-btn">Reject</button>
                </form>

              <?php elseif ($r['supportreq_status'] === 'Accepted'): ?>
                <a href="schedule_session.php?req_id=<?= urlencode($r['supportreq_ID']) ?>" class="action-btn">
                  Schedule Session
                </a>

              <?php elseif ($r['supportreq_status'] === 'Scheduled'): ?>
                <a href="session_history.php" class="action-btn">
                  View Session
                </a>

              <?php else: ?>
               <span><?= $r['supportreq_status'] === 'Pending' ? 'Waiting for Volunteer' : '-' ?></span>
            <?php endif; ?>
          </td>
         </tr>
     <?php endforeach; ?>
   </tbody>
  </table>
  <?php else: ?>
    <p style="margin-top: 20px;">No support requests found.</p>
  <?php endif; ?>
</div>

<?php end_page(); ?>
