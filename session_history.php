<?php
require "wspart.php";
require_login();
$id = $_SESSION['id'];
$role = $_SESSION['role'];

$rows = fetch_all($conn, 
    "SELECT r.supportreq_ID, r.preferred_datetime, r.supportreq_status, r.request_desc, rs.skill_name AS required_skill, 
    rs.skill_category AS category, sa.account_email AS student_email, va.account_email AS volunteer_email, 
    s.supportses_ID, s.session_datetime, s.session_status, f.rating, f.comments 
    FROM support_requests r
    JOIN account sa ON r.Account_account_ID = sa.account_id
    JOIN account va ON r.Volunteer_volunteer_ID = va.account_id
    LEFT JOIN skills rs ON r.required_skill_ID = rs.skill_ID
    LEFT JOIN support_sessions s ON r.supportreq_ID = s.Support_Requests_supportreq_ID
    LEFT JOIN feedback f ON s.supportses_ID = f.Support_Sessions_supportses_ID
    WHERE r.Account_account_ID = ? OR r.Volunteer_volunteer_ID = ?
    ORDER BY COALESCE(s.session_datetime, r.preferred_datetime) DESC", "ss", [$id, $id]);

start_page("Session History");
notification();
?>
<h1>Session History</h1>
<?php if (!$rows): ?>
    <p>No support session history yet.</p>
<?php else: ?>
    <table>
        <tr>
            <th>Email</th>
            <th>Support Category</th>
            <th>Assistance Required</th>
            <th>Preferred Date &amp; Time</th>
            <th>Skill Required</th>
            <th>Session Status</th>
            <th>Feedback Rating</th>
        </tr>
        <?php foreach ($rows as $row): ?>
        <tr>
            <td><?= escape($role === 'student' ? $row['volunteer_email'] : $row['student_email']) ?></td>
            <td><?= escape($row['category']) ?></td>
            <td><?= escape($row['request_desc']) ?></td>
            <td><?= escape($row['session_datetime'] ?? $row['preferred_datetime']) ?></td>
            <td><?= escape($row['required_skill'] ?? '-') ?></td>
            <td>
                <?php if($row['supportses_ID'] === null && $row['supportreq_status'] === 'Accepted'):  ?>
                    <a class="schedule-link" href="schedule_session.php?req_id=<?= urlencode($row['supportreq_ID']) ?>">Schedule</a>
                
                <?php elseif ($row['session_status'] === 'Scheduled'): ?>
                    <?php if ($role === 'volunteer'): ?>
                        <form method="POST" action="session_message.php">
                            <input type="hidden" name="sess" value="<?= escape($row['supportses_ID']) ?>">
                            <button class="mark-btn" name="action" value="complete">Mark as Completed</button>
                        </form>
                    <?php else: ?>
                        <span>Scheduled</span>
                    <?php endif; ?>
                    <form method="POST" action="session_message.php">
                            <input type="hidden" name="sess" value="<?= escape($row['supportses_ID']) ?>">
                            <button class="cancel-btn" name="action" value="cancel" onclick="return confirm('Are you sure you want to cancel this session?');">Cancel Session</button>
                    </form>

                <?php else: $st = $row['session_status'] ?? $row['supportreq_status']; ?>
                    <span <?= escape(strtolower($st)) ?>><?= escape($st) ?></span>
                <?php endif; ?>
            </td>
            <td>
                <?php if ($row['rating'] !== null): ?>
                    <?= escape($row['rating']) ?>/5<?= $row['comments'] !== '' ? '<br><span>' . escape($row['comments']) . '</span>' : '' ?>
                <?php elseif ($role === 'student' && $row['session_status'] === 'Completed'): ?>
                    <a href="feedback.php?sess=<?= urlencode($row['supportses_ID']) ?>">Provide Feedback</a>
                    <?php else: ?>No Rating Yet<?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    <?php endif; ?>
    <?php end_page(); ?>