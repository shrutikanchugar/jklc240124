<?php
$servername = "localhost";  // Change to your database server
$username = "root";         // Change to your database username
$password = "";             // Change to your database password
$database = "jklc";         // Change to your database name

// Create a database connection
$conn = new mysqli($servername, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process the form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {

    $studentName = $_POST['studentName'];
    $entryDate = $_POST['entryDate'];
    $feesPaid = $_POST['feesPaid'];

    // Retrieve the student ID from sregister based on the student name
    $query = "SELECT id FROM sregister WHERE student_name = '$studentName'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $studentID = $row['id'];

        // Insert data into the fees_entry table with the student ID
        $sql = "INSERT INTO fees_entry (student_id, student_name, entry_date, fees_paid) 
                VALUES ('$studentID', '$studentName', '$entryDate', '$feesPaid')";

        if ($conn->query($sql) === TRUE) {
            header("Location: TFees_confirmation.html");
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "Error: Student not found.";
    }
}
?>
