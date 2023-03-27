<?php

// Set CORS headers and content type
header('Access-Control-Allow-Origin: https://gaetan-hts.github.io/quai-antique/#/');
header('Access-Control-Allow-Methods: POST, DELETE');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

try {
    // Connect to the database
    require_once 'dbConnect.php';

    // Get input from request and decode JSON
    $input = file_get_contents('php://input');
    $_POST = json_decode($input, true);

    // Secure and retrieve ID
    $id = htmlspecialchars($_POST["id"], ENT_QUOTES, 'UTF-8');

    // Prepare SQL statement to delete picture with given ID
    $sql = "DELETE FROM pictures WHERE id = :id";

    // Prepare and execute statement with bound parameters
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Respond with success message
    echo json_encode(array('success' => true));
} catch (PDOException $e) {
    // Respond with error message
    echo "Error: " . $e->getMessage();
}

// Close database connection
$pdo = null;

?>
