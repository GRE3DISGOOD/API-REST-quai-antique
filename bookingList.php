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
    $input = file_get_contents('php://input');
    $_POST = json_decode($input, true);

    // Sanitize the input values before binding them to the SQL statement
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $email = $_POST['email'];
    $people = $_POST['people'];
    $allergies = $_POST['allergies'];
    $date = $_POST['date'];
    $time = $_POST['time'];


        // Prepare a SQL statement to insert a new booking into the database
        $stmt = $pdo->prepare("INSERT INTO bookinglist (name, surname, email, people, allergies, date, time) VALUES (:name, :surname, :email, :people, :allergies, :date, :time)");

        // Bind the values from the request to the placeholders in the SQL statement
        $stmt->bindParam(':name', $_POST['name']);
        $stmt->bindParam(':surname', $_POST['surname']);
        $stmt->bindParam(':email', $_POST['email']);
        $stmt->bindParam(':people', $_POST['people']);
        $stmt->bindParam(':allergies', $_POST['allergies']);
        $stmt->bindParam(':date', $_POST['date']);
        $stmt->bindParam(':time', $_POST['time']);

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
