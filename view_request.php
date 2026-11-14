<?php
require "wspart.php";
require_login();

$accid = $_SESSION['id'];

$sql = "SELECT 
            sr.supportreq_ID,
            sr.preferred_datetime,
            sr.preferred_supportmode,
            sr.supportreq_status,
            sr.request_desc,
            s.skill_name,
            acc.account_email AS volunteer_email
        FROM Support_Requests sr
        JOIN Skills s ON sr.required_skill_id = s.skill_ID
        JOIN Account acc ON sr.Volunteer_volunteer_ID = acc.account_ID
        WHERE sr.Account_account_ID = ?
        ORDER BY sr.preferred_datetime DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $accid);
$stmt->execute();
$result = $stmt->get_result();
?>

<style>
  body {
      font-family: Arial, sans-serif;
      max-width: 1000px;
      margin: 20px auto;
      padding: 0 15px;
  }

  table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
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

  .top-link {
    display: inline-block;
    margin-top: 10px;
    padding: 8px 20px;
    border: 2px solid #000;
    border-radius: 25px;
    text-decoration: none;
    color: #000;
  }

  .top-link:hover {
      background: #000;
      color: #fff;
  }
</style>

<h1>My Support Requests</h1>
<a href="schedule_request.php" class="top-link">+ New Request</a>

<?php if ($result && $result->num_rows > 0): ?>
  <table>
    <thead>
      <tr>
        <th>Request ID</th>
        <th>Skill</th>
        <th>Support Mode</th>
        <th>Date & Time</th>
        <th>Description</th>
        <th>Volunteer Email</th>
        <th>Status</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?= htmlspecialchars($row['supportreq_ID']) ?></td>
          <td><?= htmlspecialchars($row['skill_name']) ?></td>
          <td><?= htmlspecialchars($row['preferred_supportmode']) ?></td>
          <td><?= date('d M Y, h:i A', strtotime($row['preferred_datetime'])) ?></td>
          <td><?= nl2br(htmlspecialchars($row['request_desc'])) ?></td>
          <td><?= htmlspecialchars($row['volunteer_email']) ?></td>
          <td><?= htmlspecialchars($row['supportreq_status']) ?></td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
<?php else: ?>
  <p style="margin-top: 20px;">No support requests found.</p>
<?php endif; ?>

<?php
$stmt->close();
?>
