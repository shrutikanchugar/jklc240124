<?php
// Authenticate the teacher (implement your authentication logic here)
/*
if (!isTeacherLoggedIn()) {
    header("Location: login_teacher.php");
    exit();
}
*/
// Database connection settings
$host = "localhost";
$user = "root";
$password = "";
$database = "jklc";

// Create a connection to the database
$conn = new mysqli($host, $user, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve a list of registered students
$sql = "SELECT id, student_name FROM sregister WHERE id NOT IN (SELECT student_id FROM student_actions)"; // Adjust table and column names as needed


$result = $conn->query($sql);

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Teacher Dashboard</title>
</head>
<body>
    <h2>Welcome, Teacher!</h2>
    <h3>Registered Students:</h3>

    <form action="process_student_action.php" method="post">
        <table>
            <tr>
                <th>Student ID</th>
                <th>Student Name</th>
                <th>Action</th>
            </tr>

            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["id"] . "</td>";
                    echo "<td>" . $row["student_name"] . "</td>";
                    echo '<td>
                            <input type="radio" name="action_' . $row["id"] . '" value="add" required> Add
                            <input type="radio" name="action_' . $row["id"] . '" value="reject"> Reject
                        </td>';
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='3'>No students found.</td></tr>";
            }
            ?>
        </table>
        <br>
        <input type="submit" value="Submit Actions">
    </form>
</body>
</html>
