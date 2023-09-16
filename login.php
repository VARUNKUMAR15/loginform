<?php

$uname = $_POST['uname'];
$upswd = $_POST['upswd'];

if (!empty($uname) && !empty($upswd)) {

    $host = "localhost";
    $dbusername = "root";
    $dbpassword = "";
    $dbname = "userlogin";

    // Create connection
    $conn = new mysqli($host, $dbusername, $dbpassword, $dbname);

    if (mysqli_connect_error()) {
        die('Connect Error (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());
    } else {
        $SELECT_LOGIN = "SELECT uname2, upswd3 FROM login WHERE uname2 = ? AND upswd3 = ?";
        $stmt = $conn->prepare($SELECT_LOGIN);
        $stmt->bind_param("ss", $uname, $upswd);
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
    echo "Username and password are required";
    die();
}
?>
