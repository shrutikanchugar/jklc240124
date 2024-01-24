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

// Process student actions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    foreach ($_POST as $key => $value) {
        if (strpos($key, "action_") === 0) {
            $studentId = substr($key, 7); // Extract the student ID from the key
            $action = $value; // Get the selected action (add or reject)

            // Perform the appropriate action (add, reject, or remove)
            if ($action === "add") {
                // Add student to the teacher's class (you'll need to implement this)
                // For example, update the student record to associate them with the teacher's class
                $actionDate = date("Y-m-d H:i:s"); // Record the date and time of the action
                $notes = "Student accepted"; // Optional notes regarding the action
                
                $sql = "INSERT INTO student_actions ( student_id, action, action_date, notes)
                        VALUES ( ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("iiss", $studentId, $action, $actionDate, $notes);
                
                if ($stmt->execute()) {
                    // Student action successfully recorded
                } else {
                    // An error occurred while recording the action
                }
                $stmt->close();
            } elseif ($action === "reject") {
                // Add a record to the student_actions table to track the teacher's action
               // $teacherId = $_SESSION['teacher_id']; // Replace with your session variable name
                $actionDate = date("Y-m-d H:i:s"); // Record the date and time of the action
                $notes = "Student rejected"; // Optional notes regarding the action
                
                $sql = "INSERT INTO student_actions ( student_id, action, action_date, notes)
                        VALUES ( ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("iiss", $studentId, $action, $actionDate, $notes);
                
                if ($stmt->execute()) {
                    // Student action successfully recorded
                } else {
                    // An error occurred while recording the action
                }
                $stmt->close();
            }
        }
    }

    // Redirect back to the teacher dashboard or any other page
    header("Location: TMainScreen.html");
    exit();
}

// Close the database connection
$conn->close();
