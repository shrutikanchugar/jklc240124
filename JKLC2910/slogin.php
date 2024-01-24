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

// Initialize an error message
$error = "";

// Get user input from the login form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["lemail"];
    $password = $_POST["lpassword"];

    // Retrieve the user's data from the database
    $sql = "SELECT semail, spassword FROM sregister WHERE semail = ?";

    // Create a prepared statement
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        // Bind parameters and execute the statement
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($dbEmail, $dbPassword);

        if ($stmt->fetch() && $password === $dbPassword) {
            // Authentication successful
            // You can set session variables here for user identification
            session_start();
            $_SESSION['email'] = $email;
            
            // Redirect to a welcome page or dashboard
            header("Location: SMainScreen.html");
            exit();
        } else {
            $error = "Login failed. Invalid email or password.";
        }

        $stmt->close();
    } else {
        session_start();
        $_SESSION['error'] = "Login failed. Invalid email or password.";
        header("Location: SLogin.html"); // Redirect back to the login page
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
          /*  border: 1px solid black;*/
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
                <h2 class="heading btn btn-primary">Hello Student! Please Login</h2>
        <form action="slogin.php" method="post">
            <label>Enter Email : </label>
            <input type="email" name="lemail" required>
            <br>

            <label>Password : </label>
            <input type="password" name="lpassword">
            <br>
            <input type="submit" value="Log In" class="btn btn-success">
        </form>

 <!-- Display error message -->
 <?php if (!empty($error)): ?>
    <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>

        <br>
    <button class="btn btn-danger" onclick="back()" style="font-weight: bold; margin-bottom: 20px;">Back</button>
    </div>
    
    </center>
    </body>
      
    <script>
       
        function back(){
            window.location = "index.html"
        }
    </script>
</html>