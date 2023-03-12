<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

// Retrieve JSON input data from the request body and parse it into a PHP associative array
$input = file_get_contents('php://input');
$_POST = json_decode($input, true);

// Get values of input variables and sanitize them using htmlspecialchars to prevent potential XSS attacks
$day = htmlspecialchars($_POST["day"], ENT_QUOTES, 'UTF-8');
$lunchtime = htmlspecialchars($_POST["lunchTime"], ENT_QUOTES, 'UTF-8');
$dinnertime = htmlspecialchars($_POST["dinnerTime"], ENT_QUOTES, 'UTF-8');

try {
    // Connect to the database
    require_once 'dbConnect.php';

    // Construct an SQL query to update the lunchTime and dinnerTime columns of the schedules table for a specific day
    $query = "UPDATE schedules SET lunchTime = :lunchTime, dinnerTime = :dinnerTime WHERE day = :day";

    // Prepare the query statement with PDO
    $stmt = $pdo->prepare($query);

    // Bind the sanitized input variables to the named parameters in the query
    $stmt->bindParam(':lunchTime', $lunchtime);
    $stmt->bindParam(':dinnerTime', $dinnertime);
    $stmt->bindParam(':day', $day);

    // Execute the prepared statement to update the database
    $stmt->execute();

    // Return a JSON response indicating success
    echo json_encode(array('success' => true));
    
} catch (PDOException $e) {
    // Return a JSON response indicating failure if there is an error with the database connection or update
    echo json_encode(array("message" => "update failed"));
}

// Close the PDO connection
$pdo = null;
?>
