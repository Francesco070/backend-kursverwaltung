<?php

use App\Core\DatabaseConnection;
use App\Core\Response;

// Get the database connection
$db = DatabaseConnection::getDatabase();

try {
    // Query to select all kurs_lernende relationships
    $query = 'SELECT * FROM tbl_kurse_lernende';
    $stmt = $db->query($query);
    $stmt->execute();

    // Fetch all records
    $kurseLernende = $stmt->fetchAll();

    // Send success response with the records
    Response::json([
        'status' => 'success',
        'message' => 'Kurs-Lernende relationships retrieved successfully',
        'data' => $kurseLernende
    ], Response::OK);
} catch (Exception $e) {
    // Handle unexpected errors with a 500 Internal Server Error response
    Response::json([
        'status' => 'error',
        'message' => 'An error occurred while retrieving kurs_lernende relationships',
        'data' => ['error' => $e->getMessage()]
    ], Response::SERVER_ERROR);
}
