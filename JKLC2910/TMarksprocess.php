<?php
$servername = "localhost";  // Change to your database server
$username = "root";     // Change to your database username
$password = "";     // Change to your database password
$database = "jklc"; // Change to your database name

// Create a database connection
$conn = new mysqli($servername, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process the test details submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submitTestDetails'])) {
    $testName = $_POST['testName'];
    $testDate = $_POST['testDate'];
    $class = $_POST['class'];
    $totalMarks = $_POST['totalMarks'];

    // Display student names for the selected class
    $sql = "SELECT id, student_name FROM sregister WHERE class = '$class'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo '<h2>Enter Marks for Students</h2>';
        echo '<form action="TMarksprocess.php" method="post">';
        echo '<input type="hidden" name="testName" value="' . $testName . '">';
        echo '<input type="hidden" name="testDate" value="' . $testDate . '">';
        echo '<input type="hidden" name="class" value="' . $class . '">';
        echo '<input type="hidden" name="totalMarks" value="' . $totalMarks . '">';
        echo '<table>';
        echo '<thead><tr><th>Student Name</th><th>Marks</th><th>Total Marks</th></tr></thead>';
        echo '<tbody>';
        while ($row = $result->fetch_assoc()) {
            echo '<tr>';
            echo '<td>' . $row['student_name'] . '</td>';
            echo '<td><input type="number" name="marks[' . $row['id'] . ']" required></td>';
           # echo '<span>Total Marks: ' . $totalMarks . '</span>';
            echo '<td>'  . $totalMarks . '</td>';
            echo '</td>';
            echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';
        echo '<button type="submit" name="submitMarks">Submit Marks</button>';
        echo '</form>';
    } else {
        echo 'No students found for the selected class.';
    }
}

// Process the marks submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submitMarks'])) {
    $testName = $_POST['testName'];
    $testDate = $_POST['testDate'];
    $class = $_POST['class'];
    $totalMarks = $_POST['totalMarks'];
    $marks = $_POST['marks'];

    // Insert marks into the student_actions table
    foreach ($marks as $studentID => $mark) {
       

        // Insert test data into the new test_data table
        $sqlTestData = "INSERT INTO test_data (student_id, test_name, test_date, marks, total_marks) 
                        VALUES ('$studentID', '$testName', '$testDate', '$mark', '$totalMarks')";
        $conn->query($sqlTestData);
    }

    header("Location: TMarks_confirmation.html");
}
?>
