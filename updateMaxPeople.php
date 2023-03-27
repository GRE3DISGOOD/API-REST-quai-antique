<?php

// Set headers to allow cross-origin resource sharing (CORS)
header('Access-Control-Allow-Origin: https://gaetan-hts.github.io/quai-antique/#/');
header('Access-Control-Allow-Methods: POST, PUT');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

// Get JSON input and decode it
$input = file_get_contents('php://input');
$_POST = json_decode($input, true);

// Check if maxPeople parameter is set
if (isset($_POST['maxPeople'])) {
    // Get maxPeople value from POST data
    $maxPeople = htmlspecialchars($_POST['maxPeople'], ENT_QUOTES, 'UTF-8'); // Sanitize the input using htmlspecialchars
    
    // Update maxPeople value in the maxpeople table
    require_once 'dbConnect.php';
    
    try {
        $stmt = $pdo->prepare("UPDATE maxpeople SET maxnumber = :maxPeople WHERE id = 1");
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

// Close the database connection
$pdo = null;

?>