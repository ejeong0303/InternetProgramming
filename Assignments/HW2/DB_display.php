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

if (isset($_GET['filtered'])) {
    $conditions = [];

    if (isset($_GET['regDate']) && !empty($_GET['regDate'])) {
        $regDate = mysqli_real_escape_string($conn, $_GET['regDate']);
        $conditions[] = "registration_date >= '$regDate'";
    }

    if (isset($_GET['username']) && !empty($_GET['username'])) {
        $username = mysqli_real_escape_string($conn, $_GET['username']);
        $conditions[] = "name = '$username'";
    }

    if (isset($_GET['major']) && !empty($_GET['major'])) {
        $major = mysqli_real_escape_string($conn, $_GET['major']);
        $conditions[] = "major = '$major'";
    }

    if (isset($_GET['other_interests']) && !empty($_GET['other_interests'])) {
        $other_interests = mysqli_real_escape_string($conn, $_GET['other_interests']);
        $conditions[] = "other_interests = '$other_interests'";
    }

    if (isset($_GET['gender']) && !empty($_GET['gender'])) {
        $gender = mysqli_real_escape_string($conn, $_GET['gender']);
        $conditions[] = "gender = '$gender'";
    }

    if (isset($_GET['userid']) && !empty($_GET['userid'])) {
        $userid = mysqli_real_escape_string($conn, $_GET['userid']);
        $conditions[] = "userid = '$userid'";
    }

    if (isset($_GET['interests']) && !empty($_GET['interests'])) {
        $interests = mysqli_real_escape_string($conn, $_GET['interests']);
        $conditions[] = "interests LIKE '%$interests%'";
    }  

    if (isset($_GET['introduction']) && !empty($_GET['introduction'])) {
        $introduction = mysqli_real_escape_string($conn, $_GET['introduction']);
        $conditions[] = "introduction LIKE '%$introduction%'";
    } 
    
    if (isset($_GET['mobile_number']) && !empty($_GET['mobile_number'])) {
        $mobile_number = mysqli_real_escape_string($conn, $_GET['mobile_number']);
        $conditions[] = "mobile_number LIKE '%$mobile_number%'";
    } 

    if (isset($_GET['address']) && !empty($_GET['address'])) {
        $address = mysqli_real_escape_string($conn, $_GET['address']);
        $conditions[] = "address LIKE '%$address%'";
    } 

    if (isset($_GET['grade']) && !empty($_GET['grade'])) {
        $grade = mysqli_real_escape_string($conn, $_GET['grade']);
        $conditions[] = "grade = '$grade'";
    }

    if (isset($_GET['mbti']) && !empty($_GET['mbti'])) {
        $mbti = mysqli_real_escape_string($conn, $_GET['mbti']);
        $conditions[] = "mbti = '$mbti'";
    }

    if (isset($_GET['ageMin']) && !empty($_GET['ageMin'])) {
        $ageMin = mysqli_real_escape_string($conn, $_GET['ageMin']);
        $conditions[] = "age >= $ageMin";
    }

    if (isset($_GET['ageMax']) && !empty($_GET['ageMax'])) {
        $ageMax = mysqli_real_escape_string($conn, $_GET['ageMax']);
        $conditions[] = "age <= $ageMax";
    }

    if (isset($_GET['satisfaction']) && $_GET['satisfaction'] !== "") {
        $satisfaction = mysqli_real_escape_string($conn, $_GET['satisfaction']);
        $conditions[] = "satisfaction_level >= $satisfaction";
    }

    if (isset($_GET['birthDate']) && !empty($_GET['birthDate'])) {
        $birthDate = mysqli_real_escape_string($conn, $_GET['birthDate']);
        $conditions[] = "birthday > '$birthDate'";
    }

    if (isset($_GET['contacttime']) && !empty($_GET['contacttime'])) {
        $contacttime = mysqli_real_escape_string($conn, $_GET['contacttime']);
        $conditions[] = "contact_time > '$contacttime'";
    }

    if (isset($_GET['org']) && !empty($_GET['org'])) {
        $org = mysqli_real_escape_string($conn, $_GET['org']);
        $conditions[] = "org LIKE '%$org%'";
    }
    
    if (isset($_GET['email']) && !empty($_GET['email'])) {
        $email = mysqli_real_escape_string($conn, $_GET['email']);
        $conditions[] = "email_address = '$email'";
    }
    

    if (!empty($conditions)) {
        $sql .= " WHERE " . implode(" AND ", $conditions);
    }
}

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display Users</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #84272740; 
        color: #FFFFFF; 
        padding: 20px;
        
    }

    h2 {
        color: #555;
        font-size: 30px; 
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin: 25px 0;
        font-size: 16px;
        text-align: center;
        color: #555; 
    }

    th, td {
        padding: 9px;
        border-bottom: 1px solid #ddd;
        overflow: hidden; 
        text-overflow: ellipsis; 
        white-space: nowrap; 
    }

    th {
        background-color: #8B0000;
        color: #ddd; 
    }

    tr:hover {
        background-color: #72030322; 
    }

    @media screen and (max-width: 600px) {
        th, td {
            display: block;
            width: auto;
            text-align: center;
        }

        th::before {
            content: attr(data-label);
            float: left;
            font-weight: bold;
        }
    }
    .no-results {
        text-align: center;
        color: #555;
        padding: 20px 0;
    }
</style>

</head>
<body>

<h2>Internet Programming Class User Data</h2>

<table>
    <tr>
        <th>ID</th>
        <th>Username</th>
        <th>User ID</th>
        <th>Email Address</th>
        <th>Organization</th>
        <th>Major</th>
        <th>Gender</th>
        <th>Grade</th>
        <th>Age</th>
        <th>Address</th>
        <th>Mobile Number</th>
        <th>Birthday</th>
        <th>Favorite Color</th>
        <th>MBTI</th>
        <th>Interests</th>
        <th>Other Interests</th>
        <th>Introduction</th>
        <th>Satisfaction Level</th>
        <th>Contact Time</th>
        <th>Registration Date</th>
    </tr>

    <?php
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row["id"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["name"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["userid"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["email_address"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["org"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["major"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["gender"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["grade"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["age"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["address"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["mobile_number"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["birthday"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["favcolor"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["mbti"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["interests"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["other_interests"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["introduction"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["satisfaction_level"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["contact_time"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["registration_date"]) . "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='20' class='no-results'>No results</td></tr>";
    }
    $conn->close();
    ?>

</table>

</body>
</html>
