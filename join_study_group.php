<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    die("Please <a href='login_secure.html'>login</a> to join a group.");
}

$participant_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $group_id = $_POST['group_id'];

    if (!empty($group_id)) {
        try {
            $sql_insert = "INSERT INTO Study_Group_Participant (participant_id, group_id) 
                           VALUES ('$participant_id', '$group_id')";

            if ($conn->query($sql_insert) === TRUE) {
                $sql_update = "UPDATE Study_Groups 
                               SET current_participants = current_participants + 1 
                               WHERE group_id = '$group_id'";
                $conn->query($sql_update);

                $message = "Successfully joined the group!";
                $color = "green";
            }
        } catch (mysqli_sql_exception $e) {
            if ($e->getCode() == 1062) {
                $message = "You are already a member of this group.";
                $color = "orange";
            } else {
                $message = "Error: " . $e->getMessage();
                $color = "red";
            }
        }
    } else {
        $message = "Error: No group selected.";
        $color = "red";
    }
} else {
    header("Location: search_study_groups.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="refresh" content="3;url=search_study_groups.php" />
    <title>Join Status</title>
</head>
<body>
    <h2 style="color: <?php echo $color; ?>"><?php echo $message; ?></h2>
    <p>Redirecting back to search results in 3 seconds...</p>
    <p><a href="search_study_groups.php">Click here if not redirected.</a></p>
</body>
</html>