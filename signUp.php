<?php

header('Access-Control-Allow-Origin: https://gaetan-hts.github.io/quai-antique/#/');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

try {
    // Connect to the database
    require_once 'dbConnect.php';

    // Retrieve JSON data
    $input = file_get_contents('php://input');
    $_POST = json_decode($input, true);

    // Sanitize user inputs
    $name = htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8');
    $surname = htmlspecialchars($_POST['surname'], ENT_QUOTES, 'UTF-8');
    $allergies = htmlspecialchars($_POST['allergies'], ENT_QUOTES, 'UTF-8');
    $people = htmlspecialchars($_POST['people'], ENT_QUOTES, 'UTF-8');
    $password = htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8');
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    $email = htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8');
    $admin = 0;

    // Check if any field is empty
    if (empty($name) || empty($surname) || empty($allergies) || empty($people) || empty($password) || empty($email)) {
        echo json_encode(['missingFields' => true]);
        exit();
    }

    // Check if user already exists
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch();

    if ($user) {
        echo json_encode(['usedEmail' => true]);
    } else {
        // Prepare SQL statement for insertion
        $sql = "INSERT INTO users (name, surname, email, password, allergies, people, admin) VALUES (:name, :surname, :email, :password, :allergies, :people, :admin)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':surname', $surname);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password_hash);
        $stmt->bindParam(':allergies', $allergies);
        $stmt->bindParam(':people', $people);
        $stmt->bindParam(':admin', $admin);
        $stmt->execute();

        // Return response
        $res['isAdmin'] = false;
        $res['email'] = $_POST['email'];
        echo json_encode($res);
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

$pdo = null;
?>
