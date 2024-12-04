<?php

use App\Core\DatabaseConnection;
use App\Core\Response;

// Get the database connection
$db = DatabaseConnection::getDatabase();

// SQL Query to get all Lernende
$query = 'SELECT * FROM tbl_lernende';

try {
    $stmt = $db->query($query);
    $stmt->execute();

    // Fetch all records
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    Response::json([
        'status' => 'success',
        'data' => $data
    ], Response::OK);

} catch (Exception $e) {
    Response::json([
        'status' => 'error',
        'message' => 'An error occurred while fetching the Lernende records',
        'data' => ['error' => $e->getMessage()]
    ], Response::SERVER_ERROR);
}
?>
