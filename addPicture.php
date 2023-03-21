<?php
// Set headers to allow cross-origin resource sharing
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, PUT');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

try {
  // Connect to the database
  require_once 'dbConnect.php';

  // Get the input data from the request body and parse it as JSON
  $input = file_get_contents('php://input');
  $_POST = json_decode($input, true);

  // Sanitize the input data to prevent SQL injection and XSS attacks
  $title = htmlspecialchars($_POST['title'], ENT_QUOTES, 'UTF-8');
  $url = htmlspecialchars($_POST['url'], ENT_QUOTES, 'UTF-8');

  // Check that both fields are filled before inserting
  if (!empty($title) && !empty($url)) {

    // Prepare the SQL statement for inserting a new row into the pictures table
    $stmt = $pdo->prepare("INSERT INTO pictures (title, url) VALUES (:title, :url)");
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':url', $url);

    // Execute the SQL statement to insert the new row
    $stmt->execute();

    // Send a success message to the client
    echo json_encode(array('success' => true));
  } else {
    echo json_encode(array('success' => false, 'message' => 'Missing title or URL'));
  }
} catch (PDOException $e) {
  // Send an error message to the client if the insertion failed
  echo json_encode(array("message" => "insertion failed"));
}

// Close the database connection
$pdo = null;
?>