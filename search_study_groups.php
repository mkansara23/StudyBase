<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    die("Please <a href='login_secure.html'>login</a> to search groups.");
}

$courses_result = $conn->query("SELECT course_id, course_code, course_name FROM Courses");
$locations_result = $conn->query("SELECT location_id, location_code, building FROM Location");
$professors_result = $conn->query("SELECT DISTINCT professor FROM Sections ORDER BY professor");

$results = null;
$search_performed = false;

if ($_GET) { 
    $search_performed = true;
    $course_id = isset($_GET['course_id']) ? $_GET['course_id'] : '';
    $professor = isset($_GET['professor']) ? $_GET['professor'] : '';
    $location_id = isset($_GET['location_id']) ? $_GET['location_id'] : '';
    $day = isset($_GET['day']) ? $_GET['day'] : '';

    $sql = "SELECT g.*, c.course_code, c.course_name, l.building, l.room, u.user_name as host_name 
            FROM Study_Groups g
            JOIN Courses c ON g.course_id = c.course_id
            JOIN Location l ON g.location_id = l.location_id
            JOIN Users u ON g.host_id = u.user_id
            WHERE 1=1";

    if (!empty($course_id)) {
        $sql .= " AND g.course_id = '$course_id'";
    }
    
    if (!empty($location_id)) {
        $sql .= " AND g.location_id = '$location_id'";
    }
    
    if (!empty($day)) {
        $sql .= " AND g.day = '$day'";
    }

    if (!empty($professor)) {
        $sql .= " AND g.course_id IN (SELECT section_of FROM Sections WHERE professor = '$professor')";
    }

    $results = $conn->query($sql);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Study Groups</title>
</head>
<body>
    <h1>Search Study Groups</h1>
    <p>Use the filters below to find the perfect study group.</p>

    <form method="get" action="search_study_groups.php">
        
        <label for="course_id">Course:</label>
        <select id="course_id" name="course_id">
            <option value="">-- Any Course --</option>
            <?php
            if ($courses_result->num_rows > 0) {
                while($row = $courses_result->fetch_assoc()) {
                    $selected = (isset($_GET['course_id']) && $_GET['course_id'] == $row['course_id']) ? 'selected' : '';
                    echo "<option value='" . $row['course_id'] . "' $selected>" . $row['course_code'] . " - " . $row['course_name'] . "</option>";
                }
            }
            ?>
        </select>
        <br><br>

        <label for="professor">Professor:</label>
        <select id="professor" name="professor">
            <option value="">-- Any Professor --</option>
            <?php
            if ($professors_result->num_rows > 0) {
                while($row = $professors_result->fetch_assoc()) {
                    $selected = (isset($_GET['professor']) && $_GET['professor'] == $row['professor']) ? 'selected' : '';
                    echo "<option value='" . $row['professor'] . "' $selected>" . $row['professor'] . "</option>";
                }
            }
            ?>
        </select>
        <br><br>

        <label for="location_id">Location:</label>
        <select id="location_id" name="location_id">
            <option value="">-- Any Location --</option>
            <?php
            if ($locations_result->num_rows > 0) {
                while($row = $locations_result->fetch_assoc()) {
                    $selected = (isset($_GET['location_id']) && $_GET['location_id'] == $row['location_id']) ? 'selected' : '';
                    echo "<option value='" . $row['location_id'] . "' $selected>" . $row['location_code'] . " - " . $row['building'] . "</option>";
                }
            }
            ?>
        </select>
        <br><br>

        <label for="day">Day:</label>
        <select id="day" name="day">
            <option value="">-- Any Day --</option>
            <?php
            $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
            foreach ($days as $d) {
                $selected = (isset($_GET['day']) && $_GET['day'] == $d) ? 'selected' : '';
                echo "<option value='$d' $selected>$d</option>";
            }
            ?>
        </select>
        <br><br>

        <button type="submit">Search</button>
        <a href="search_study_groups.php"><button type="button">Clear Filters</button></a>
    </form>

    <hr>

    <?php if ($search_performed): ?>
        <h2>Results</h2>
        <?php if ($results && $results->num_rows > 0): ?>
            <table border="1" cellpadding="5">
                <thead>
                    <tr>
                        <th>Group ID</th>
                        <th>Course</th>
                        <th>Description</th>
                        <th>Host</th>
                        <th>Location</th>
                        <th>Day/Time</th>
                        <th>Participants</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $results->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['group_id']; ?></td>
                            <td><?php echo $row['course_code']; ?></td>
                            <td><?php echo $row['description']; ?></td>
                            <td><?php echo $row['host_name']; ?></td>
                            <td><?php echo $row['building'] . " " . $row['room']; ?></td>
                            <td><?php echo $row['day'] . " " . substr($row['start_time'], 0, 5) . "-" . substr($row['end_time'], 0, 5); ?></td>
                            <td><?php echo $row['current_participants'] . " / " . $row['max_participants']; ?></td>
                            <td>
                                <form action="join_study_group.php" method="post" style="display:inline;">
                                    <input type="hidden" name="group_id" value="<?php echo $row['group_id']; ?>">
                                    <button type="submit">Join</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No study groups found matching your criteria.</p>
        <?php endif; ?>
    <?php endif; ?>

    <br>
    <a href="dashboard.php">Back to Dashboard</a>
</body>
</html>