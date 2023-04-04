<?php
// Set headers to allow cross-origin resource sharing
header("Access-Control-Allow-Origin: https://gaetan-hts.github.io");
header('Access-Control-Allow-Methods: POST, PUT');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

// Get input data from the request body and sanitize it to prevent SQL injection attacks
$data = json_decode(file_get_contents("php://input"));
$email = htmlspecialchars($data->email, ENT_QUOTES, 'UTF-8');
$people = htmlspecialchars($data->people, ENT_QUOTES, 'UTF-8');

try {
    // Connect to the database
    require_once 'dbConnect.php';

    // Update the user's record in the database with the new number of people
    $query = "UPDATE users SET people = :people WHERE email = :email";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":people", $people);
    $stmt->bindParam(":email", $email);
    $stmt->execute();
    
    // Check if the update was successful and return a JSON response with a success or failure message
    $affected_rows = $stmt->rowCount();
    if ($affected_rows == 1) {
        echo json_encode(array("message" => "update successful"));
    } else {
        echo json_encode(array("message" => "update failed"));
    }
    
} catch (PDOException $e) {
    // Return a JSON response with a failure message if there was an error with the database connection or query
    echo json_encode(array("message" => "update failed"));
}


// Close the database connection
$pdo = null;
?>
