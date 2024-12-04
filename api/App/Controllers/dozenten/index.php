<?php

use App\Core\DatabaseConnection;
use App\Core\Response;

// Get the database connection
$db = DatabaseConnection::getDatabase();

try {
    $query = 'SELECT * FROM tbl_dozenten';
    $stmt = $db->query($query);
    $stmt->execute();

    $dozenten = $stmt->fetchAll();

    Response::json([
        'status' => 'success',
        'message' => 'Dozenten retrieved successfully.',
        'data' => $dozenten
    ], Response::OK);
} catch (Exception $e) {
    Response::json([
        'status' => 'error',
        'message' => 'An error occurred while retrieving dozenten.',
        'data' => ['error' => $e->getMessage()]
    ], Response::SERVER_ERROR);
}
