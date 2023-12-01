<?php
$servername = "localhost";
$username = "cse20191659";
$password = "8230-mille";
$dbname = "db_cs20191659";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM Internet_programming_users";

// List of allowed fields for filtering to prevent SQL injection
$allowedFields = ['name', 'major', 'gender', 'userid', 'interests', 'mobile_number', 'address', 'grade', 'mbti', 'age', 'org', 'email_address'];

if (isset($_GET['filterField']) && isset($_GET['filterValue']) && in_array($_GET['filterField'], $allowedFields)) {
    $field = mysqli_real_escape_string($conn, $_GET['filterField']);
    $value = mysqli_real_escape_string($conn, $_GET['filterValue']);
    $sql .= " WHERE $field = '$value'";
}

$result = $conn->query($sql);

$userData = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $userData[] = $row;
    }
}

echo json_encode($userData);
$conn->close();

?>
