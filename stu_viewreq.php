<?php
require "wspart.php";
require_login();

if (($_SESSION['role'] ?? '') !== 'student') {
    header("Location: volunteer.php");
    exit();
}

$id = $_SESSION['id'];

$requests = fetch_all($conn, 
    "SELECT r.*, 
            va.account_email AS volunteer_email, 
            rs.skill_name AS required_skill, 
            rs.skill_category AS category
     FROM support_requests r
     JOIN account va ON r.Volunteer_volunteer_ID = va.account_id
     LEFT JOIN skills rs ON r.required_skill_ID = rs.skill_ID
     WHERE r.Account_account_ID = ?
     ORDER BY r.preferred_datetime DESC", 
    "s", [$id]
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
    color: #000;
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
  <a href="schedule_request.php" class="action-btn">Add New Request</a>

  <?php if ($requests): ?>
    <table>
      <thead>
        <tr>
          <th>Request ID</th>
          <th>Skill</th>
          <th>Support Mode</th>
          <th>Preferred Date &amp; Time</th>
          <th>Description</th>
          <th>Volunteer Email</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($requests as $r): ?>
          <tr>
            <td><?= escape($r['supportreq_ID']) ?></td>
            <td><?= escape($r['required_skill'] ?? 'Not specified') ?></td>
            <td><?= escape($r['preferred_supportmode']) ?></td>
            <td><?= escape(format_date($r['preferred_datetime'])) ?></td>
            <td><?= nl2br(escape($r['request_desc'])) ?></td>
            <td><?= escape($r['volunteer_email']) ?></td>
            <td><?= escape($r['supportreq_status']) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php else: ?>
    <p style="margin-top: 20px;">No support requests found.</p>
  <?php endif; ?>
</div>

<?php end_page(); ?>
