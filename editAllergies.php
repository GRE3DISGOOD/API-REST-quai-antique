<?php
// Set headers to allow cross-origin resource sharing
header('Access-Control-Allow-Origin: https://quai-antique-ecf.herokuapp.com');
header('Access-Control-Allow-Methods: POST, PUT');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

// Get input data from the request body and sanitize it to prevent SQL injection attacks
$data = json_decode(file_get_contents("php://input"));
$email = htmlspecialchars($data->email, ENT_QUOTES, 'UTF-8');
$allergies = htmlspecialchars($data->allergies, ENT_QUOTES, 'UTF-8');

try {
    // Connect to the database
    require_once 'dbConnect.php';

    // Prepare and execute SQL query
    $query = "UPDATE users SET allergies = :allergies WHERE email = :email";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":allergies", $allergies);
    $stmt->bindParam(":email", $email);
    $stmt->execute();
    
    // Check if update was successful and send the response
    $affected_rows = $stmt->rowCount();
    if ($affected_rows == 1) {
        echo json_encode(array("message" => "update successful"));
    } else {
        echo json_encode(array("message" => "update failed"));
    }
    
} catch (PDOException $e) {
    echo json_encode(array("message" => "update failed"));
}


// Close the database connection
$pdo = null;
?>
