<?php

$uname1 = $_POST['uname1'];
$email = $_POST['email'];
$upswd1 = $_POST['upswd1'];
$upswd2 = $_POST['upswd2'];

if (!empty($uname1) || !empty($email) || !empty($upswd1) || !empty($upswd2)) {

    $host = "localhost";
    $dbusername = "root";
    $dbpassword = "";
    $dbname = "userlogin";

    // Create connection
    $conn = new mysqli($host, $dbusername, $dbpassword, $dbname);

    if (mysqli_connect_error()) {
        die('Connect Error (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());
    } else {
        $SELECT = "SELECT email FROM register WHERE email = ? LIMIT 1";
        $INSERT_REGISTER = "INSERT INTO register (uname1, email, upswd1, upswd2) VALUES (?, ?, ?, ?)";
        $INSERT_LOGIN = "INSERT INTO login (uname2, upswd3) VALUES (?, ?)";

        // Prepare statements
        $stmt = $conn->prepare($SELECT);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($email);
        $stmt->store_result();
        $rnum = $stmt->num_rows;

        // Check if email already exists
        if ($rnum == 0) {
            $stmt->close();

            // Insert data into the register table
            $stmt = $conn->prepare($INSERT_REGISTER);
            $stmt->bind_param("ssss", $uname1, $email, $upswd1, $upswd2);
            $stmt->execute();

            // Insert data into the login table
            $stmt = $conn->prepare($INSERT_LOGIN);
            $stmt->bind_param("ss", $uname1, $upswd1);
            $stmt->execute();

            echo "New record inserted successfully";
        } else {
            echo "Someone already registered using this email";
        }
        $stmt->close();

        // Check login credentials
        $SELECT_LOGIN = "SELECT uname2, upswd3 FROM login WHERE uname2 = ? AND upswd3 = ?";
        $stmt = $conn->prepare($SELECT_LOGIN);
        $stmt->bind_param("ss", $uname1, $upswd2);
        $stmt->execute();
        $stmt->store_result();
        $loginRows = $stmt->num_rows;
        $stmt->close();

        if ($loginRows > 0) {
            echo "Login Successful";
        } else {
            echo "Login Failed";
        }

        $conn->close();
    }
} else {
    echo "All fields are required";
    die();
}
?>
