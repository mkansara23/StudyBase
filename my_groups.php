<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    die("Please <a href='login_secure.html'>login</a> to view your groups.");
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action'])) {
        
        if ($_POST['action'] == 'delete') {
            $group_id = $_POST['group_id'];
            $check_sql = "SELECT group_id FROM Study_Groups WHERE group_id = '$group_id' AND host_id = '$user_id'";
            if ($conn->query($check_sql)->num_rows > 0) {
                $conn->query("DELETE FROM Study_Groups WHERE group_id = '$group_id'");
                $message = "<p style='color:green'>Group deleted successfully.</p>";
            } else {
                $message = "<p style='color:red'>Error: You can only delete groups you host.</p>";
            }
        }

        elseif ($_POST['action'] == 'leave') {
            $group_id = $_POST['group_id'];
            $leave_sql = "DELETE FROM Study_Group_Participant WHERE group_id = '$group_id' AND participant_id = '$user_id'";
            if ($conn->query($leave_sql) === TRUE) {
                $conn->query("UPDATE Study_Groups SET current_participants = current_participants - 1 WHERE group_id = '$group_id'");
                $message = "<p style='color:green'>You have left the group.</p>";
            } else {
                $message = "<p style='color:red'>Error leaving group.</p>";
            }
        }

        elseif ($_POST['action'] == 'update') {
            $group_id = $_POST['group_id'];
            $new_desc = $_POST['description'];
            $check_sql = "SELECT group_id FROM Study_Groups WHERE group_id = '$group_id' AND host_id = '$user_id'";
            if ($conn->query($check_sql)->num_rows > 0) {
                $new_desc_safe = $conn->real_escape_string($new_desc);
                $conn->query("UPDATE Study_Groups SET description = '$new_desc_safe' WHERE group_id = '$group_id'");
                $message = "<p style='color:green'>Description updated.</p>";
            } else {
                $message = "<p style='color:red'>Error: You can only update groups you host.</p>";
            }
        }
    }
}

$hosted_sql = "SELECT g.*, c.course_code, l.building, l.room 
               FROM Study_Groups g
               LEFT JOIN Courses c ON g.course_id = c.course_id
               JOIN Location l ON g.location_id = l.location_id
               WHERE g.host_id = '$user_id'";
$hosted_result = $conn->query($hosted_sql);

$joined_sql = "SELECT g.*, c.course_code, l.building, l.room, u.user_name as host_name
               FROM Study_Group_Participant p
               JOIN Study_Groups g ON p.group_id = g.group_id
               LEFT JOIN Courses c ON g.course_id = c.course_id
               JOIN Location l ON g.location_id = l.location_id
               JOIN Users u ON g.host_id = u.user_id
               WHERE p.participant_id = '$user_id'";
$joined_result = $conn->query($joined_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Study Groups</title>
</head>
<body>
    <h1>My Study Groups</h1>
    <p>Welcome, <strong><?php echo $user_name; ?></strong>!</p>
    
    <?php echo $message; ?>

    <hr>

    <h3>Groups I Created (Host)</h3>
    <?php if ($hosted_result->num_rows > 0): ?>
        <table border="1" cellpadding="5">
            <thead>
                <tr>
                    <th>Group ID</th>
                    <th>Course</th>
                    <th>Description</th>
                    <th>Location</th>
                    <th>Participants</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $hosted_result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['group_id']; ?></td>
                        <td><?php echo $row['course_code']; ?></td>
                        <td>
                            <form action="my_groups.php" method="post">
                                <input type="hidden" name="action" value="update">
                                <input type="hidden" name="group_id" value="<?php echo $row['group_id']; ?>">
                                <textarea name="description" rows="2" cols="20"><?php echo $row['description']; ?></textarea>
                                <br><button type="submit" style="font-size:small;">Save</button>
                            </form>
                        </td>
                        <td><?php echo $row['building'] . " " . $row['room']; ?></td>
                        <td><?php echo $row['current_participants'] . " / " . $row['max_participants']; ?></td>
                        <td>
                            <form action="my_groups.php" method="post" onsubmit="return confirm('Are you sure you want to delete this group?');">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="group_id" value="<?php echo $row['group_id']; ?>">
                                <button type="submit" style="color:red;">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>You haven't created any study groups yet.</p>
    <?php endif; ?>

    <hr>

    <h3>Groups I Joined (Participant)</h3>
    <?php if ($joined_result->num_rows > 0): ?>
        <table border="1" cellpadding="5">
            <thead>
                <tr>
                    <th>Group ID</th>
                    <th>Course</th>
                    <th>Description</th>
                    <th>Host</th>
                    <th>Location</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $joined_result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['group_id']; ?></td>
                        <td><?php echo $row['course_code']; ?></td>
                        <td><?php echo $row['description']; ?></td>
                        <td><?php echo $row['host_name']; ?></td>
                        <td><?php echo $row['building'] . " " . $row['room']; ?></td>
                        <td>
                            <form action="my_groups.php" method="post" onsubmit="return confirm('Leave this group?');">
                                <input type="hidden" name="action" value="leave">
                                <input type="hidden" name="group_id" value="<?php echo $row['group_id']; ?>">
                                <button type="submit" style="color:orange;">Leave Group</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>You haven't joined any study groups yet.</p>
    <?php endif; ?>

    <br><br>
    <a href="dashboard.php">Back to Dashboard</a>
</body>
</html>