<?php
// Database configuration
$dbHost = "localhost";
$dbUser = "root";
$dbPassword = "";
$dbName = "encryptdecrypt";

// Create a database connection
$mysqli = new mysqli($dbHost, $dbUser, $dbPassword, $dbName);

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Retrieve data from the "encrypted_data" table and store it in an array with timestamps
$mirrorData = array();

$sql = "SELECT userinput, time FROM encrypted_data";
$result = $mysqli->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $mirrorData[] = array(
            "userinput" => $row["userinput"],
            "time" => $row["time"]
        );
    }
}

$mysqli->close();

// Return the mirror data as a JSON response
echo json_encode($mirrorData);
?>
