<?php
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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_name = $_POST["student_name"];
    $pcontact = $_POST["contact1"];
    $altcontact = $_POST["contact2"];
    $gender = $_POST["gender"];
    $saddress = $_POST["saddress"];
    $class = $_POST["classdropdown"];
    $preference = isset($_POST["preference"]) ? implode(", ", $_POST["preference"]) : "";
    $sbranch = $_POST["branch"];
    $susername = $_POST["semail"];
    $spassword = $_POST["spassword"];
    

    // Insert form data into the database
    $sql = "INSERT INTO sregister (student_name, pcontact, altcontact, gender, saddress, class, preference, sbranch, semail, spassword) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssss", $student_name, $pcontact, $altcontact, $gender, $saddress, $class, $preference, $sbranch, $susername, $spassword);
    $stmt->execute();

    $stmt->close();
    echo "Registration successful. <a href='Slogin.html'>Login here</a>";
}

// Close the database connection
$conn->close();
?>
