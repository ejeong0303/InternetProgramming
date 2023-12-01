<?php
$servername = "localhost";
$username = "cse20191659";
$password = "8230-mille";
$dbname = "db_cs20191659";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $userid = $_POST["userid"];
    $password = $_POST["password"];
    $email_address = $_POST["email_address"];
    $org = $_POST["org"];
    $major = $_POST["major"];
    $gender = $_POST["gender"];
    $grade = $_POST["grade"];
    $age = $_POST["age"];
    $address = $_POST["address"];
    $mobile_number = $_POST["mobile_number"];
    $birthday = $_POST["birthday"];
    $favcolor = $_POST["favcolor"];
    $mbti = $_POST["mbti"];
    $interests = isset($_POST["interests"]) ? implode(", ", $_POST["interests"]) : "";
    $other_interests = $_POST["other_interests"];
    $introduction = $_POST["introduction"];
    $satisfaction_level = $_POST["satisfaction_level"];
    $contact_time = $_POST["contact_time"];
    $registration_date = date("Y-m-d H:i:s");

    $sql = "INSERT INTO Internet_programming_users (name, userid, password, email_address, org, major, gender, grade, age, address, mobile_number, birthday, favcolor, mbti, interests, other_interests, introduction, satisfaction_level, contact_time, registration_date)
            VALUES ('$name', '$userid', '$password', '$email_address', '$org', '$major', '$gender', '$grade', '$age', '$address', '$mobile_number', '$birthday', '$favcolor', '$mbti', '$interests', '$other_interests', '$introduction', '$satisfaction_level', '$contact_time', '$registration_date')"; 

    if ($conn->query($sql) === TRUE) {
        echo "New data added to the database successfully :)";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>