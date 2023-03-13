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
    // Get maxPeople value from POST data and save it to a file
    $maxPeople = htmlspecialchars($_POST['maxPeople'], ENT_QUOTES, 'UTF-8'); // Sanitize the input using htmlspecialchars
    file_put_contents('max_people.txt', $maxPeople);

    // Return success message as JSON
    echo json_encode(array('success' => true));
} else {
    // Return error message as JSON if maxPeople parameter is not set
    echo json_encode(array('success' => false));
}
