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

// Fetch student names based on the selected class
if (isset($_GET['class'])) {
    $selectedClass = $_GET['class'];
    $sql = "SELECT id, student_name FROM sregister WHERE class = '$selectedClass'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $data = array();
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        echo json_encode($data);
    } else {
        echo json_encode(array());
    }
} else {
    echo json_encode(array());
}

$conn->close();
?>
