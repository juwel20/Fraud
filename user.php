<?php

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Validate and sanitize input data
    $name = htmlspecialchars($_POST["name"]);
    $phone = htmlspecialchars($_POST["phone"]);
    $gender = htmlspecialchars($_POST["gender"]);
    $email = htmlspecialchars($_POST["email"]);
    $password = htmlspecialchars($_POST["password"]);
    $confirm_password = htmlspecialchars($_POST["confirm_password"]);

    // Validate password and confirm password match
    if ($password !== $confirm_password) {
        die("Password and confirm password do not match.");
    }

    // Hash the password before storing in database
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Database connection details
    $servername = "localhost"; // Replace with your server name
    $username = "username"; // Replace with your username
    $password_db = "password"; // Replace with your password
    $dbname = "fraud"; // Replace with your database name

    // Create connection
    $conn = new mysqli($servername, $username, $password_db, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // SQL statement to insert data into users table
    $sql = "INSERT INTO users (photo, name, phone, gender, email, password, paid, refer)
            VALUES ('', ?, ?, ?, ?, ?, 0.00, '')";

    // Prepare and bind the statement
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $name, $phone, $gender, $email, $hashed_password);

    // Execute the statement
    if ($stmt->execute()) {
        // Successful registration, redirect to login.html
        header("Location: login.html");
        exit();
    } else {
        // Error handling
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();

} else {
    // If the form is not submitted, redirect to registration page
    header("Location: registration.html");
    exit();
}
?>
