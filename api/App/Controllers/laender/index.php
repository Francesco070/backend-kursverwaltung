<?php

use App\Core\DatabaseConnection;
use App\Core\Response;

// Get the database connection
$db = DatabaseConnection::getDatabase();

try {
    $query = 'SELECT * FROM tbl_countries';
    $stmt = $db->query($query);
    $stmt->execute();

    $countries = $stmt->fetchAll();

    Response::json([
        'status' => 'success',
        'message' => 'Countries retrieved successfully.',
        'data' => $countries
    ], Response::OK);
} catch (Exception $e) {
    Response::json([
        'status' => 'error',
        'message' => 'An error occurred while retrieving countries.',
        'data' => ['error' => $e->getMessage()]
    ], Response::SERVER_ERROR);
}
