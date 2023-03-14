<?php

// Set headers to allow cross-origin resource sharing (CORS)
header('Access-Control-Allow-Origin: https://quai-antique-ecf.herokuapp.com');
header('Access-Control-Allow-Methods: POST, PUT');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

// Get JSON input and decode it
$input = file_get_contents('php://input');
$_POST = json_decode($input, true);

// Check if maxPeople parameter is set
if (isset($_POST['maxPeople'])) {
    // Get maxPeople value from POST data and save it to the database
    $maxPeople = htmlspecialchars($_POST['maxPeople'], ENT_QUOTES, 'UTF-8'); // Sanitize the input using htmlspecialchars
    
    // Insert maxPeople value into the maxpeople table
    require_once 'dbConnect.php';
    
    try {
        $stmt = $pdo->prepare("INSERT INTO maxpeople (value) VALUES (:maxPeople)");
        $stmt->bindParam(':maxPeople', $maxPeople);
        $stmt->execute();
        
        // Return success message as JSON
        echo json_encode(array('success' => true));
    } catch (PDOException $e) {
        // Return error message as JSON if there is an exception
        echo json_encode(array('success' => false));
    }
} else {
    // Return error message as JSON if maxPeople parameter is not set
    echo json_encode(array('success' => false));
}
