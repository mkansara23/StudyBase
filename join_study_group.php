<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Join Study Group</title>
</head>
<body>
    <h1>Join a Study Group</h1>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        include 'db.php';

        $participant_id = $_POST['participant_id'];
        $group_id = $_POST['group_id'];

        if (empty($participant_id) || empty($group_id)) {
             echo "<p style='color:red'>Error: User ID and Group ID are required.</p>";
        } else {
            // 1. Insert into Study_Group_Participant
            $sql_insert = "INSERT INTO Study_Group_Participant (participant_id, group_id) 
                           VALUES ('$participant_id', '$group_id')";

            if ($conn->query($sql_insert) === TRUE) {
                // 2. Update current_participants count in Study_Groups
                // This is a simple update
                $sql_update = "UPDATE Study_Groups 
                               SET current_participants = current_participants + 1 
                               WHERE group_id = '$group_id'";
                
                $conn->query($sql_update); // We assume this works if the insert worked

                echo "<p style='color:green'>Successfully joined the group!</p>";
            } else {
                echo "<p style='color:red'>Error: " . $conn->error . "</p>";
                if (strpos($conn->error, 'Duplicate entry') !== false) {
                    echo "<p>You are already a member of this group.</p>";
                }
            }
        }
        $conn->close();
    }
    ?>

    <form method="post" action="join_study_group.php">
        <label for="participant_id">User ID:</label>
        <input type="text" id="participant_id" name="participant_id" maxlength="10" required><br><br>

        <label for="group_id">Group ID:</label>
        <input type="text" id="group_id" name="group_id" maxlength="10" required><br><br>

        <button type="submit">Join Group</button>
    </form>

    <br>
    <a href="index.html">Back to Home</a>
</body>
</html>
