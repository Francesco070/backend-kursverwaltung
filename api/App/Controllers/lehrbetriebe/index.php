<?php

use App\Core\DatabaseConnection;
use App\Core\Response;

// Get the database connection
$db = DatabaseConnection::getDatabase();

try {
    // Query to select all lehrbetriebe
    $query = 'SELECT * FROM tbl_lehrbetrieb';
    $stmt = $db->query($query);
    $stmt->execute();

    // Fetch all records
    $lehrbetriebe = $stmt->fetchAll();

    Response::json([
        'status' => 'success',
        'message' => 'Lehrbetriebe retrieved successfully',
        'data' => $lehrbetriebe
    ], Response::OK);
} catch (Exception $e) {
    Response::json([
        'status' => 'error',
        'message' => 'An error occurred while retrieving the Lehrbetriebe',
        'data' => ['error' => $e->getMessage()]
    ], Response::SERVER_ERROR);
}
?>
