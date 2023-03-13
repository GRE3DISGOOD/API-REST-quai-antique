<?php

// Headers
header('Access-Control-Allow-Origin: https://quai-antique-ecf.herokuapp.com/');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

try {
    // Connect to the database
    require_once 'dbConnect.php';
    
    // Get data from the users table
    $stmt = $pdo->prepare("SELECT * FROM users");
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Get data from the schedules table
    $stmt = $pdo->prepare("SELECT * FROM schedules");
    $stmt->execute();
    $schedules = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Get data from the dishes table
    $stmt = $pdo->prepare("SELECT * FROM dishes");
    $stmt->execute();
    $dishes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Get data from the pictures table
    $stmt = $pdo->prepare("SELECT * FROM pictures");
    $stmt->execute();
    $pictures = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Get data from the calendar table
    $stmt = $pdo->prepare("SELECT * FROM calendar");
    $stmt->execute();
    $calendar = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Get data from the bookinglist table
    $stmt = $pdo->prepare("SELECT * FROM bookinglist");
    $stmt->execute();
    $bookinglist = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Store the retrieved data in a JSON object
    $data = array(
        'users' => $users,
        'schedules' => $schedules,
        'dishes' => $dishes,
        'pictures' => $pictures,
        'calendar' => $calendar
    );

    // Use the retrieved data
    if (!empty($data)) {
        $json_data = json_encode($data);
        echo $json_data;
    } else {
        echo "No data found";
    }

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

$pdo = null;

?>
