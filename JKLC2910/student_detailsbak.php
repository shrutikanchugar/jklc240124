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
    </style>
</head>
<body>

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

        
        ?>

        <h2>Student Details</h2>

        <!-- Display student details -->
        <h3>Student Details</h3>
        <table>
             <tr>
                <th>Student ID</th>
                <td><?php echo $studentDetails['id']; ?></td>
            </tr>
            <tr>
                <th>Student Name</th>
                <td><?php echo $studentDetails['student_name']; ?></td>
            </tr>
        </table>
        <?php
    } else {
        echo '<p>No student details found.</p>';
    }
    

     // Fetch test results from test_data table
     $testResultsQuery = "SELECT * FROM test_data WHERE student_id = '$studentID'";
     $testResultsResult = $conn->query($testResultsQuery);
 
     if ($testResultsResult->num_rows > 0) {
         ?>

        <!-- Display test results graph -->
        <h3>Test Results</h3>
        <canvas id="testResultsChart" width="400" height="200"></canvas>
        <script>
            // Prepare data for the test results chart
            var testResultsLabels = [];
            var testResultsData = [];

            <?php while ($row = $testResultsResult->fetch_assoc()) { ?>
                testResultsLabels.push('<?php echo $row['test_name']; ?>');
                testResultsData.push(<?php echo $row['marks']; ?>);
            <?php } ?>

            var testResultsChart = new Chart(document.getElementById('testResultsChart').getContext('2d'), {
                type: 'bar',
                data: {
                    labels: testResultsLabels,
                    datasets: [{
                        label: 'Test Results',
                        data: testResultsData,
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
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

        <?php
    } else {
        echo '<p>No test results found.</p>';
    }

 // Fetch fees details from fees_entry table
 $feesDetailsQuery = "SELECT * FROM fees_entry WHERE student_name = '$studentName'";
 $feesDetailsResult = $conn->query($feesDetailsQuery);

 if ($feesDetailsResult->num_rows > 0) {
     ?>

        <!-- Display fees details -->
        <h3>Fees Details</h3>
        <table>
            <!-- Add rows for fees details -->
            <tr>
                <th>Entry ID</th>
                <th>Entry Date</th>
                <th>Fees Paid</th>
            </tr>

            <?php while ($row = $feesDetailsResult->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['entry_id']; ?></td>
                    <td><?php echo $row['entry_date']; ?></td>
                    <td><?php echo $row['fees_paid']; ?></td>
                </tr>
            <?php } ?>
        </table>

        <?php
    } else {
        echo '<p>No fees details found.</p>';
    }

    // Fetch month-wise attendance from student_attendance table
    $attendanceQuery = "SELECT * FROM student_attendance WHERE id = '$studentID'";
    $attendanceResult = $conn->query($attendanceQuery);

    if ($attendanceResult->num_rows > 0) {
        ?>


        <!-- Display month-wise attendance graph -->
        <h3>Month-wise Attendance</h3>
        <canvas id="attendanceChart" width="400" height="200"></canvas>

        <script>
            // Implement JavaScript to fetch data from the server and display graphs
            var attendanceLabels = [];
            var attendanceData = [];

            <?php while ($row = $attendanceResult->fetch_assoc()) { ?>
                attendanceLabels.push('<?php echo $row['attendance_date']; ?>');
                attendanceData.push('<?php echo $row['status']; ?>' === 'Present' ? 1 : 0);
            <?php } ?>

            var attendanceChart = new Chart(document.getElementById('attendanceChart').getContext('2d'), {
                type: 'bar',
                data: {
                    labels: attendanceLabels,
                    datasets: [{
                        label: 'Attendance',
                        data: attendanceData,
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 1
                        }
                    }
                }
            });
        </script>
  <?php
    } else {
        echo '<p>No attendance data found.</p>';
    }
} else {
    echo '<p>No student selected.</p>';
}

$conn->close();
?>
</body>
</html>
