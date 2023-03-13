<?php

// Set CORS headers to allow cross-origin requests
header('Access-Control-Allow-Origin: https://quai-antique-ecf.herokuapp.com');
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
    $name = htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8');
    $surname = htmlspecialchars($_POST['surname'], ENT_QUOTES, 'UTF-8');
    $email = htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8');
    $people = htmlspecialchars($_POST['people'], ENT_QUOTES, 'UTF-8');
    $allergies = htmlspecialchars($_POST['allergies'], ENT_QUOTES, 'UTF-8');
    $date = htmlspecialchars($_POST['date'], ENT_QUOTES, 'UTF-8');
    $time = htmlspecialchars($_POST['time'], ENT_QUOTES, 'UTF-8');


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
