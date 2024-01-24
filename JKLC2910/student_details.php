<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Details</title>
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        h2 {
            margin-bottom: 20px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        canvas {
            margin-top: 20px;
        }
        a{
            text-decoration: none;
        }

        .home{
            float: right;
        }
    </style>
</head>
<body>

<button class="home"><a href="TMainScreen.html">Home</a></button>
<br> <br>
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


if (isset($_GET['student'])) {
    $studentID = $_GET['student'];
    // Fetch student details from sregister table

    $studentDetailsQuery = "SELECT * FROM sregister WHERE id = '$studentID'";
   # $studentName = "SELECT student_name FROM sregister WHERE id= '$studentID'";
    $studentDetailsResult = $conn->query($studentDetailsQuery);

    if ($studentDetailsResult->num_rows > 0) {
        $studentDetails = $studentDetailsResult->fetch_assoc();
        ?>

    
   
  <!-- Display student details -->
  <hr>
  <center><h3>Student Details</h3></center>
  <hr>
  <br>
        <table>
             <tr>
                <th>Student ID</th>
                <td><?php echo $studentDetails['id']; ?></td>
            </tr>
            <tr>
                <th>Student Name</th>
                <td><?php echo $studentDetails['student_name']; ?></td>
            </tr>
            <tr>
                <th>Primary Contact</th>
                <td><?php echo $studentDetails['pcontact']; ?></td>
            </tr>
            <tr>
                <th>Alternate Contact</th>
                <td><?php echo $studentDetails['altcontact']; ?></td>
            </tr>
            <tr>
                <th>Gender</th>
                <td><?php echo $studentDetails['gender']; ?></td>
            </tr>
            <tr>
                <th>Address</th>
                <td><?php echo $studentDetails['saddress']; ?></td>
            </tr>
            <tr>
                <th>Class</th>
                <td><?php echo $studentDetails['class']; ?></td>
            </tr>
            <tr>
                <th>Branch</th>
                <td><?php echo $studentDetails['sbranch']; ?></td>
            </tr>
            <tr>
                <th>Email Address</th>
                <td><?php echo $studentDetails['semail']; ?></td>
            </tr>
        </table>
        <br><br><hr>
<?php
    }
    else{
        echo '<p>No student details found.</p>';
    }
} else {
    echo '<p>No student selected.</p>';
}



// Fetch test results from test_data table
$testResultsQuery = "SELECT * FROM test_data WHERE student_id = '$studentID'";
$testResultsResult = $conn->query($testResultsQuery);


if($testResultsResult->num_rows >0){

    ?>

    <!--Display Test Results -->
    <center><h3>Test Results</h3></center>
    <hr><br>
    <table>
        <tr>
            <th>Test Name</th>
            <th>Test Date</th>
            <th>Marks</th>
            <th>Total Marks</th>
        </tr>
        <?php while($row = $testResultsResult->fetch_assoc()){ ?>
        <tr>
            <td><?php echo $row['test_name'];?></td>
            <td><?php echo $row['test_date'];?></td>
            <td><?php echo $row['marks'];?></td>
            <td><?php echo $row['total_marks'];?></td>
        </tr>
        <?php
        } ?>
    </table>
    <br><br><hr>
<?php
}
else{
    echo '<p>No test results found.</p>';
}
  

 // Fetch fees details from fees_entry table
 $feesDetailsQuery = "SELECT * FROM fees_entry WHERE student_id = '$studentID'";
 $feesDetailsResult = $conn->query($feesDetailsQuery);

 if ($feesDetailsResult->num_rows > 0) {
     ?>

        <!-- Display fees details -->
       <center> <h3>Fees Details</h3></center>
        <hr><br>
        <table>
            <!-- Add rows for fees details -->
            <tr>
              <!--  <th>Entry ID</th> -->
                <th>Entry Date</th>
                <th>Fees Paid</th>
            </tr>

            <?php while ($row = $feesDetailsResult->fetch_assoc()) { ?>
                <tr>
                 <!--  <td><?php echo $row['entry_id']; ?></td>-->
                    <td><?php echo $row['entry_date']; ?></td>
                    <td><?php echo $row['fees_paid']; ?></td>
                </tr>
            <?php } ?>
        </table>
<br><br><hr>
        <?php
    } else {
        echo '<p>No fees details found.</p>';
    }

    

// Fetch attendance details for the selected month
if (isset($_GET['month'])) {
    $selectedMonth = date('m', strtotime($_GET['month']));
    $selectedYear = date('Y', strtotime($_GET['month']));

    $attendanceDetailsQuery = "SELECT COUNT(*) AS present_days,
                                      (SELECT COUNT(*) FROM student_attendance WHERE student_id = '$studentID' AND MONTH(attendance_date) = '$selectedMonth' AND YEAR(attendance_date) = '$selectedYear' AND status = 'absent') AS absent_days
                               FROM student_attendance
                               WHERE student_id = '$studentID' AND MONTH(attendance_date) = '$selectedMonth' AND YEAR(attendance_date) = '$selectedYear' AND status = 'present'";

    $attendanceDetailsResult = $conn->query($attendanceDetailsQuery);

    if ($attendanceDetailsResult->num_rows > 0) {
        $attendanceDetails = $attendanceDetailsResult->fetch_assoc();
        $presentDays = $attendanceDetails['present_days'];
        $absentDays = $attendanceDetails['absent_days'];
    } else {
        $presentDays = 0;
        $absentDays = 0;
    }
} else {
    $presentDays = 0;
    $absentDays = 0;
}

// Fetch overall total present and total absent days
$totalAttendanceQuery = "SELECT COUNT(*) AS total_present_days,
                                 (SELECT COUNT(*) FROM student_attendance WHERE student_id = '$studentID' AND status = 'absent') AS total_absent_days
                          FROM student_attendance
                          WHERE student_id = '$studentID' AND status = 'present'";

$totalAttendanceResult = $conn->query($totalAttendanceQuery);

if ($totalAttendanceResult->num_rows > 0) {
    $totalAttendance = $totalAttendanceResult->fetch_assoc();
    $totalPresentDays = $totalAttendance['total_present_days'];
    $totalAbsentDays = $totalAttendance['total_absent_days'];
} else {
    $totalPresentDays = 0;
    $totalAbsentDays = 0;
}

$conn->close();
?>
<!-- Add a form for selecting the month -->

<center> <h3>Attendance Details</h3></center>
        <hr><br>
        <br>
        <h4>Please Select the month to view attendance of a particular month</h4>
<form method="get" action="">
    <label for="month">Select Month:</label>
    <input type="month" id="month" name="month" required>
    <input type="hidden" name="student" value="<?php echo $studentID; ?>">
    <button type="submit">Submit</button>
</form>


<br>
<!-- Display attendance details -->
<h3>Attendance Details for <?php echo isset($_GET['month']) ? date('F Y', strtotime($_GET['month'])) : 'Selected Month'; ?></h3>
<hr>
<p>Present Days: <?php echo $presentDays; ?></p>
<p>Absent Days: <?php echo $absentDays; ?></p>

<!-- Display overall total present and total absent days -->
<h3>Overall Attendance</h3>
<hr>
<p>Total Present Days: <?php echo $totalPresentDays; ?></p>
<p>Total Absent Days: <?php echo $totalAbsentDays; ?></p>

<!-- Add a canvas for the attendance chart -->
<canvas id="attendanceChart" width="400" height="200"></canvas>

<!-- JavaScript for Chart.js -->
<script>
    const ctx = document.getElementById('attendanceChart').getContext('2d');
    const attendanceChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Present Days', 'Absent Days'],
            datasets: [{
                label: 'Attendance Chart',
                data: [<?php echo $totalPresentDays; ?>, <?php echo $totalAbsentDays; ?>],
                backgroundColor: [
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(255, 99, 132, 0.2)'
                ],
                borderColor: [
                    'rgba(75, 192, 192, 1)',
                    'rgba(255, 99, 132, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
</body>
</html>