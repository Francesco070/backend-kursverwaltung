<?php

use App\Core\DatabaseConnection;
use App\Core\Response;

// Get the database connection
$db = DatabaseConnection::getDatabase();

try {
    // Query to join tbl_kurse_lernende, tbl_lernende, and tbl_kurse
    $query = '
        SELECT 
            kl.id_kurs_lernende, 
            CONCAT(l.vorname, \' \', l.nachname) AS lernende, 
            k.kursthema, 
            kl.role
        FROM tbl_kurse_lernende kl
        JOIN tbl_lernende l ON kl.fk_lernende = l.id_lernende
        JOIN tbl_kurse k ON kl.fk_kurs = k.id_kurs
    ';

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
