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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve user input
    $userInput = $_POST["user-input"];

    // Encryption key and initialization vector (IV)
    $encryptionKey = "!@#$%^&*()"; // Replace with your actual secret key
    $iv = openssl_random_pseudo_bytes(16);

    // Encrypt user input
    $encryptedInput = openssl_encrypt($userInput, 'aes-256-cbc', $encryptionKey, 0, $iv);

    // Store the encrypted input in the database
    $sql = "INSERT INTO encrypted_data (userinput) VALUES (?)";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("s", $encryptedInput);
    $stmt->execute();
    $stmt->close();

    // Decrypt the input to verify
    $decryptedInput = openssl_decrypt($encryptedInput, 'aes-256-cbc', $encryptionKey, 0, $iv);

    // Return the response as JSON
    $response = array(
        "userInput" => $userInput,
        "decryptedInput" => $decryptedInput,
        "encryptedOutput" => $encryptedInput
    );

    echo json_encode($response);
}

// // Retrieve data from the database and display it as a table
// $sql = "SELECT id, userinput, time FROM encrypted_data";
// $result = $mysqli->query($sql);

// if ($result->num_rows > 0) {
//     echo "<table class='table'>";
//     echo "<thead><tr><th>ID</th><th>User Input</th><th>Decrypted Input</th><th>Time</th></tr></thead>";
//     echo "<tbody>";
//     while ($row = $result->fetch_assoc()) {
//         $id = $row["id"];
//         $userInput = openssl_decrypt($row["userinput"], 'aes-256-cbc', $encryptionKey, 0, $iv);
//         $time = $row["time"];

//         echo "<tr><td>$id</td><td>{$row["userinput"]}</td><td>$userInput</td><td>$time</td></tr>";
//     }
//     echo "</tbody>";
//     echo "</table>";
// } else {
//     echo "No data available.";
// }

// $mysqli->close();
// ?>
