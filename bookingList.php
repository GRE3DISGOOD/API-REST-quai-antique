<?php

// Set CORS headers to allow cross-origin requests
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');


try {
    // Connect to the database
    require_once 'dbConnect.php';

    // Get the request payload and decode it as JSON
    $data = json_decode(file_get_contents("php://input"));

    // Sanitize the input values before binding them to the SQL statement
    $name = htmlspecialchars($data->name, ENT_QUOTES, 'UTF-8');
    $surname = htmlspecialchars($data->surname, ENT_QUOTES, 'UTF-8');
    $email = htmlspecialchars($data->email, ENT_QUOTES, 'UTF-8');
    $people = htmlspecialchars($data->people, ENT_QUOTES, 'UTF-8');
    $allergies = htmlspecialchars($data->allergies, ENT_QUOTES, 'UTF-8');
    $date = htmlspecialchars($data->date, ENT_QUOTES, 'UTF-8');
    $time = htmlspecialchars($data->time, ENT_QUOTES, 'UTF-8');

    
        // Prepare a SQL statement to insert a new booking into the database
        $stmt = $pdo->prepare("INSERT INTO bookinglist (name, surname, email, people, allergies, date, time) VALUES (:name, :surname, :email, :people, :allergies, :date, :time)");
        
        // Bind the values from the request to the placeholders in the SQL statement
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':surname', $surname);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':people', $people);
        $stmt->bindParam(':allergies', $allergies);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':time', $time);

        // Execute the SQL statement and return a response indicating success or failure
        if ($stmt->execute()) {
            echo json_encode(array("booked" => true));
        } else {
            echo json_encode(array("booked" => false));
        }
    
} catch (PDOException $e) {
    // If there is an error connecting to the database, return an error message
    echo 'Error: ' . $e->getMessage();
}

// Close the database connection
$pdo = null;

?>
