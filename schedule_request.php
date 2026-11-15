<?php
require "wspart.php";
require_login();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
$accid = $_SESSION['id'];
$isupcat = $_POST['support_category'];
$isupmod = $_POST['support_mode'];
$iisuedesc = $_POST['issue_description'];
$idatetime = $_POST['datetime'];
$ivolid = $_POST['selected_volunteer_id'];
$status = "Pending";

$dt = DateTime::createFromFormat('Y-m-d\TH:i', $idatetime);
    $frdt = $dt->format('Y-m-d H:i:s');

$idQuery = "SELECT supportreq_ID FROM Support_Requests ORDER BY supportreq_ID DESC LIMIT 1";
    $idResult = $conn->query($idQuery);

    if ($idResult && $idResult->num_rows > 0) {
        $lastRow = $idResult->fetch_assoc();
        $lastId = $lastRow['supportreq_ID']; 
        $numPart = (int) substr($lastId, 3); 
        $nextNum = $numPart + 1;             
    } else {
        $nextNum = 1;                
    }
    $req = 'REQ' . str_pad($nextNum, 6, '0', STR_PAD_LEFT);

  $instmt = "INSERT INTO Support_Requests (supportreq_ID, preferred_datetime, preferred_supportmode, Account_account_ID, Volunteer_volunteer_ID, supportreq_status, request_desc, required_skill_id)
   VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($instmt);
    $stmt->bind_param("ssssssss",
        $req,
        $frdt,
        $isupmod,
        $accid,
        $ivolid,
        $status,
        $iisuedesc,
        $isupcat
    );
if ($stmt->execute()) {
        $stmt->close();
        echo "<script>alert('Successful insert!'); window.location.href='schedule_request.php';</script>";
        exit;
    } else {
        echo "Database error: " .($stmt->error);
        $stmt->close();
    }


}

$supcat = "SELECT skill_ID, skill_name FROM skills ORDER BY skill_name ASC";
$resupcat = $conn->query($supcat);
$skillsList = [];
if ($resupcat && $resupcat->num_rows > 0) {
    while ($row = $resupcat->fetch_assoc()) {
        $skillsList[] = $row;
    }
}

// fetch volunteer data
$vol = "SELECT 
            v.account_ID, 
            acc.account_email AS volunteer_email,
            v.volunteer_supportmode,
            GROUP_CONCAT(DISTINCT s.skill_name ORDER BY s.skill_name ASC SEPARATOR ', ') AS skills,
            GROUP_CONCAT(DISTINCT CONCAT(avt.availability_day, ' ', TIME_FORMAT(avt.start_time, '%H:%i'), '-', TIME_FORMAT(avt.end_time, '%H:%i')) SEPARATOR ' | ') AS availability_slots
        FROM Volunteer v
        JOIN Account acc ON v.account_ID = acc.account_ID
        JOIN Possesses p ON v.account_ID = p.volunteer_account_ID
        JOIN Skills s ON p.Skills_skill_ID = s.skill_ID
        JOIN Availability a ON v.account_ID = a.volunteer_account_ID
        JOIN `Available Times` avt ON a.Availability_availability_ID = avt.availability_ID
        GROUP BY v.account_ID, acc.account_email, v.volunteer_supportmode
        ORDER BY acc.account_email ASC";


$resvol = $conn->query($vol);

// one day in advance
$minDateTime = date('Y-m-d\TH:i', strtotime('+1 day'));
?>
<style>
body {
  margin: 0;
  padding: 0;
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
img{
  height: 190%;
  width: 15%;
  margin-right: auto;
}
h1{
  text-align: center;
}
  
  form {
       max-width: 1000px;
  margin: 20px auto;
  padding: 25px 30px;
  background-color: rgba(255, 255, 255, 0.9);
  border: 2px solid #000;
  border-radius: 10px;
  box-sizing: border-box;
  }

  #p1 label {
    font-weight: bold;
    display: inline-block;
    margin-top: 12px;
  }
  #p1 select, #p1 textarea, #p1 input {
      width: 100%;
      max-width: 400px;
      padding: 8px;
      margin-top: 5px;
      box-sizing: border-box;
  }

  #p2 {
    display: none;
  }

  .filter-bar {
      display: flex;
      gap: 10px;
      justify-content: center;
      margin-bottom: 20px;
      flex-wrap: wrap;
  }
  .filter-bar select {
    padding: 8px 12px;
    border: 1px solid #000;
    background: #fff;
    font-size: 14px;
  }

  .table-wrapper {
      display: flex;
      flex-direction: column;
  }
  .volunteer-row {
    display: flex;
    align-items: center;
  }
  .radio-col {
      width: 50px;
      display: flex;
      justify-content: center;
      align-items: center;
  }

  .radio-col input[type="radio"] {
    width: 24px;
    height: 24px;
    accent-color: #000;
    cursor: pointer;
  }
  .grid-cells {
      display: grid;
      grid-template-columns: 1.4fr 1.2fr 1.6fr 0.8fr;
      flex: 1;
      border-left: 2px solid #000;
      border-top: 2px solid #000;
  }
  .volunteer-row:last-child .grid-cells {
    border-bottom: 2px solid #000;
  }
  .grid-cells div {
      padding: 15px 10px;
      border-right: 2px solid #000;
      text-align: center;
      word-break: break-word;
      line-height: 1.5;
      display: flex;
      align-items: center;
      justify-content: center;
  }
  .header-row .grid-cells {
    font-weight: bold;
    background-color: #f4f4f4;
  }
  .data-row:hover .grid-cells {
      background-color: #f9f9f9;
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
 
  .btn-group {
      display: flex;
      justify-content: center;
      gap: 15px;
      margin-top: 25px;
  }
  
  button {
    padding: 10px 30px;
    font-size: 16px;
    border-radius: 25px;
    border: 2px solid #000;
    background: #fff;
    cursor: pointer;
  }
  button:hover {
      background: #000;
      color: #fff;
  }
</style>
<div class="top">
  <ul>
  <img src="UniHELP logo.png" alt="logo">
  <li><a href="student.php">Home</a></li>
  <li><a href="">My Request</a></li>
  <li><a href="">Session History</a></li>
  <li><a href="logout.php">Logout</a></li>
  </ul>
</div>

<h1>Schedule Request</h1>
<div class="details-container"></div>

<form action="schedule_request.php" method="POST">
<div id="p1">
  <label for="support_category">Support Category:</label><br>
  <select name="support_category" id="support_category" required>
      <option value="">-- Select a Category --</option>
      <?php foreach ($skillsList as $rowsupcat): ?>
        <option value="<?= $rowsupcat['skill_ID'] ?>" data-skill-name="<?= $rowsupcat['skill_name'] ?>">
          <?= $rowsupcat['skill_name'] ?>
        </option>
      <?php endforeach; ?>
  </select>
  <br>

  <label for="support_mode">Prefer Support Mode:</label><br>
  <select name="support_mode" id="support_mode" required>
      <option value="">-- Select Mode --</option>
      <option value="Physical">Physical</option>
      <option value="Online">Online</option>
  </select>
  <p>

  <label for="issue_desc">Assistance Required:</label><br>
  <textarea name="issue_description" id="issue_desc" rows="5" cols="40" required></textarea>
  <br>

  <label for="datetime">Date & Time:</label><br>
  <input type="datetime-local" name="datetime" id="datetime" min="<?= $minDateTime ?>" required>
  <br>

  <button type="button" id="nextBtn">Next &raquo;</button>
</div>

<div id="p2">
  <div class="filter-bar">
    <select id="filterDay">
        <option value="">Day (All)</option>
        <option value="Monday">Monday</option>
        <option value="Tuesday">Tuesday</option>
        <option value="Wednesday">Wednesday</option>
        <option value="Thursday">Thursday</option>
        <option value="Friday">Friday</option>
        <option value="Saturday">Saturday</option>
        <option value="Sunday">Sunday</option>
    </select>

    <select id="filterTime">
        <option value="">Time (All)</option>
        <option value="Morning">8AM - 12PM</option>
        <option value="Afternoon">12PM - 5PM</option>
    </select>

    <select id="filterSkill">
        <option value="">Skill (All)</option>
        <?php foreach ($skillsList as $sk): ?>
          <option value="<?= $sk['skill_name'] ?>"><?= $sk['skill_name'] ?></option>
        <?php endforeach; ?>
    </select>

    <select id="filterMode">
        <option value="">Support Mode (All)</option>
        <option value="Physical">Physical</option>
        <option value="Online">Online</option>
    </select>
  </div>

  <div class="table-wrapper">
    <div class="volunteer-row header-row">
      <div class="radio-col"></div>
      <div class="grid-cells">
        <div>Volunteer Email</div>
        <div>Skills</div>
        <div>Available Times</div>
        <div>Support Mode</div>
      </div>
    </div>

    <?php if ($resvol && $resvol->num_rows > 0): ?>
      <?php while ($row = $resvol->fetch_assoc()): ?>
      <?php
        $formattedTimes = str_replace(' | ', '<br>', $row['availability_slots']);
      ?>
      <div class="volunteer-row data-row"
           data-availability="<?= $row['availability_slots'] ?>"
           data-skills="<?= $row['skills'] ?>"
           data-mode="<?= $row['volunteer_supportmode'] ?>">
          
        <div class="radio-col">
          <input type="radio" name="selected_volunteer_id" value="<?= $row['account_ID'] ?>" required>
        </div>

        <div class="grid-cells">
          <div><?= $row['volunteer_email'] ?></div>
          <div><?= $row['skills'] ?></div>
          <div><?= $formattedTimes ?></div>
          <div><?= $row['volunteer_supportmode'] ?></div>
        </div>
      </div>
      <?php endwhile; ?>
    <?php endif; ?>
  </div>

  <p id="noMatchMsg" style="display: none; text-align: center; color: red; margin-top: 15px;">
    No volunteers match your selected filters.
  </p>

  <div class="btn-group">
    <button type="button" id="backBtn">&laquo; Back</button>
    <button type="submit">Submit</button>
  </div>
</div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const p1 = document.getElementById('p1');
  const p2 = document.getElementById('p2');
  const nextBtn = document.getElementById('nextBtn');
  const backBtn = document.getElementById('backBtn');

  const filterDay = document.getElementById('filterDay');
  const filterTime = document.getElementById('filterTime');
  const filterSkill = document.getElementById('filterSkill');
  const filterMode = document.getElementById('filterMode');
  const dataRows = document.querySelectorAll('.data-row');
  const noMatchMsg = document.getElementById('noMatchMsg');

  function toMinutes(hhmm) {
      if (!hhmm || !hhmm.includes(':')) return 0;
      const [h, m] = hhmm.split(':').map(Number);
      return h * 60 + m;
  }

  function matchesAvailability(availString, selectedDay, selectedPeriod) {
      if (!selectedDay && !selectedPeriod) return true;
      if (!availString) return false;

      const periods = {
        Morning: { start: 8 * 60, end: 12 * 60 },
        Afternoon: { start: 12 * 60, end: 17 * 60 }
      };

      const slots = availString.split(' | ');
      return slots.some(slot => {
        const parts = slot.trim().split(' ');
        if (parts.length < 2) return false;

        const slotDay = parts[0];
        const [startStr, endStr] = parts[1].split('-');

        const dayOk = !selectedDay || slotDay.toLowerCase() === selectedDay.toLowerCase();
        if (!dayOk) return false;

        if (!selectedPeriod) return true;

        const slotStart = toMinutes(startStr);
        const slotEnd = toMinutes(endStr);
        const target = periods[selectedPeriod];

        return slotStart < target.end && slotEnd > target.start;
      });
  }

  function filterTable() {
      const dayVal = filterDay.value;
      const timeVal = filterTime.value;
      const skillVal = filterSkill.value;
      const modeVal = filterMode.value;
      let visibleCount = 0;

      dataRows.forEach(row => {
          const rowSkills = (row.dataset.skills || '').split(', ').map(s => s.trim());
          const matchSkill = !skillVal || rowSkills.includes(skillVal);
          const matchMode = !modeVal || row.dataset.mode === modeVal || row.dataset.mode === 'Both';
          const matchAvail = matchesAvailability(row.dataset.availability, dayVal, timeVal);

          if (matchSkill && matchMode && matchAvail) {
              row.style.display = 'flex';
              visibleCount++;
          } else {
              row.style.display = 'none';
              const radio = row.querySelector('input[type="radio"]');
              if (radio) radio.checked = false;
          }
      });

      noMatchMsg.style.display = visibleCount === 0 ? 'block' : 'none';
  }

  nextBtn.addEventListener('click', function() {
    const step1Fields = p1.querySelectorAll('select, textarea, input');
    for (const field of step1Fields) {
        if (!field.checkValidity()) {
            field.reportValidity();
            return;
        }
    }

    const catSelect = document.getElementById('support_category');
    const selectedOption = catSelect.options[catSelect.selectedIndex];
    filterSkill.value = selectedOption.getAttribute('data-skill-name') || '';
    filterMode.value = document.getElementById('support_mode').value;

    const dt = new Date(document.getElementById('datetime').value);
    if (!isNaN(dt.getTime())) {
      const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
      filterDay.value = days[dt.getDay()];

      const hour = dt.getHours();
      if (hour >= 8 && hour < 12) filterTime.value = 'Morning';
      else if (hour >= 12 && hour < 17) filterTime.value = 'Afternoon';
      else filterTime.value = '';
    }

    filterTable();
    p1.style.display = 'none';
    p2.style.display = 'block';
  });

  backBtn.addEventListener('click', function() {
      p2.style.display = 'none';
      p1.style.display = 'block';
  });

  [filterDay, filterTime, filterSkill, filterMode].forEach(el => {
      el.addEventListener('change', filterTable);
  });
});
</script>

