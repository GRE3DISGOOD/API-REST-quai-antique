<?php

// Set the response headers to allow cross-origin requests
header("Access-Control-Allow-Origin: https://gaetan-hts.github.io");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header('Content-Type: application/json');

try {
    // Connect to the database
    require_once 'dbConnect.php';

    // Get the input data from the HTTP request body
    $input = file_get_contents('php://input');
    $_POST = json_decode($input, true);

    // Sanitize and validate the input data
    $email = htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8');
    $password = htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8');

    // Prepare a SQL statement to select a user with the given email
    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = :email');
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();

    if ($user) {
        // If a user was found, verify their password
        if (password_verify($password, $user['password'])) {
            // If the password is correct, start a new session and store the user data
            session_start();
            $_SESSION['user'] = $user;
            $_SESSION['email'] = $user['email'];
            $_SESSION['admin'] = ($user['admin'] == 1);

            // Send a response with the user data
            $res['isAdmin'] = $_SESSION['admin'];
            $res['email'] = $_SESSION['email'];
            echo json_encode($res);
        } else {
            // If the password is incorrect, send a response indicating login failure
            echo json_encode(['failLogin' => true]);
        }
    } else {
        // If no user was found with the given email, send a response indicating login failure
        echo json_encode(['failLogin' => true]);
    }
} catch (PDOException $e) {
    // If an error occurred, send a response with the error message
    echo json_encode(['error' => $e->getMessage()]);
}

// Close the database connection
$pdo = null;
