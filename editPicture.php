<?php

// Set response headers
header('Access-Control-Allow-Origin: https://quai-antique-ecf.herokuapp.com');
header('Access-Control-Allow-Methods: POST, PUT');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

try {
    // Connect to the database
    require_once 'dbConnect.php';

    // Retrieve input data
    $input = file_get_contents('php://input');
    $_POST = json_decode($input, true);

    // Sanitize input data
    $id = htmlspecialchars($_POST["id"], ENT_QUOTES, 'UTF-8');
    $title = htmlspecialchars($_POST["title"], ENT_QUOTES, 'UTF-8');

    // Prepare SQL statement and execute
    $sql = "UPDATE pictures SET title = :title WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':title', $title);
    $stmt->execute();

    // Output success message
    echo json_encode(array('success' => true));
} catch (PDOException $e) {
    // Output error message
    echo json_encode(array("message" => "update failed"));
}

// Close database connection
$pdo = null;

?>
