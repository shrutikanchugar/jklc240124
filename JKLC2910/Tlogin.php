<?php
// Database connection settings
$host = "localhost"; // Change this if your MySQL server is running on a different host
$user = "root"; // Replace with your MySQL username
$password = ""; // Replace with your MySQL password
$database = "jklc"; // Replace with your database name

// Create a connection to the database
$conn = new mysqli($host, $user, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get user input from the login form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Retrieve the user's data from the database
    $sql = "SELECT username, password FROM tlogin WHERE username = ?";

    // Create a prepared statement
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        // Bind parameters and execute the statement
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->bind_result($dbUsername, $dbPassword);

        if ($stmt->fetch() && $password === $dbPassword) {
            // Authentication successful
            // You can set session variables here for user identification
            session_start();
            $_SESSION['username'] = $username;

            // Redirect to the next page
            header("Location: TMainScreen.html");
            exit();
        }  else {
            $error = "Login failed. Invalid email or password.";
        }

        $stmt->close();
    } else {
        session_start();
        $_SESSION['error'] = "Login failed. Invalid email or password.";
        header("Location: TLogin.html"); // Redirect back to the login page
        exit();
    }
}
// Close the database connection
$conn->close();
?>

<html>
    <head>
        <title>OTMS</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
    
        <script src="https://unpkg.com/ml5@0.4.3/dist/ml5.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.26/webcam.js"></script>
    
      <!-- <link rel="stylesheet" href="style.css"> -->
         
       <style>
        body{
            margin: 10%;
            background-color: #bccee8;

        }
        label,input{
            margin: 20px;
        }
        div{
            width: 60%;
           /* border: 1px solid black; */
            background-color: white;
        }
        .heading{
            width: 90%;
            border: 1px solid black;
            font-size: large;
            font-weight: bold;
        }
        input{
            width: 30%;
        }
       </style>
      </head>

      <body>
        <center>
            <div style="border: 1px solid black;">
                <h2 class="heading btn btn-primary">Welcome Teacher! Please Login</h2>
        <form id="loginForm" action="Tlogin.php" method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required><br>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required><br>
            <input type="submit" value="Login" class="btn btn-success">
        </form>

         <!-- Display error message -->
 <?php if (!empty($error)): ?>
    <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>
    
        <button class="btn btn-danger" onclick="back()" style="font-weight: bold; margin-bottom: 20px;">Back</button>
    </div>
    <br>
    
    </center>
    </body>
    <script>
     
        function back(){
            window.location = "index.html"
        }
    </script>
</html>