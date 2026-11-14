<?php
require "wspart.php";
require_login();

$supcat = "SELECT skill_ID, skill_name FROM skills ORDER BY skill_name ASC";
$resupcat = $conn->query($supcat);


?>


<h1>Schedule Request</h1>
<div class="details-container">
</div>

<form action="schedule_request.php" method="POST">
  <div id="p1">
    <label for="support_category">Support Category:</label>
    
    <select name="support_category" id="support_category" required>
        <option value="">-- Select a Part --</option>
        <?php if ($resupcat && $resupcat->num_rows > 0): ?>
          <?php while ($rowsupcat = $resupcat->fetch_assoc()): ?>
            <option value="<?= $rowsupcat['skill_ID'] ?>">
                <?= htmlspecialchars($rowsupcat['skill_name']) ?>
            </option>
          <?php endwhile; ?>
        <?php endif;?>
    </select>

    <label for="support_mode">Prefer Support Mode:</label>
    <select name="support_mode" id="support_mode" required>
      <option value="">-- Select a Part --</option>
            <option value="Physical">
                Physical
            </option>
            <option value="Online">
                Online
            </option>
    </select>

     <label for="issue_desc">Assistance Required:</label><br>
    <textarea name="issue_description" id="issue_desc" rows="5" cols="40" required></textarea>
    <br><br>

    <label for="datetime">Date & Time:</label><br>
    <input type="datetime-local" name="datetime" id="datetime" required>
    <br><br>
     <button type="button" id="nextBtn">Next</button>
  </div>

  <div id="p2">
  

  </div>  




<button type="submit"> 

<script>
function filterVolunteers() {

    // Retrieve selected filter values
    const day = document.getElementById("day").value;
    const time = document.getElementById("time").value;
    const skill = document.getElementById("skill").value;
    const mode = document.getElementById("mode").value;
 const filtered = volunteers.filter(v => {

        return (
            (!day || v.availability_day === day) &&

            (!time || (
                v.start_time <= time &&
                v.end_time >= time
            )) &&

            (!skill || v.skill_name === skill) &&

            (!mode || v.volunteer_supportmode === mode)
        );

    });

    displayVolunteers(filtered);
}   
</script> 