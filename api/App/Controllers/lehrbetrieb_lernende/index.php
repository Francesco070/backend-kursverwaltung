<?php

use App\Core\DatabaseConnection;
use App\Core\Response;

// Get the database connection
$db = DatabaseConnection::getDatabase();

try {
    // Query to join tables and concatenate first and last name as "lernende"
    $query = '
        SELECT 
            ll.id_lehrbetrieb_lernende, 
            CONCAT(l.vorname, " ", l.nachname) AS lernende, 
            lb.firma, 
            ll.start, 
            ll.ende, 
            ll.beruf
        FROM tbl_lehrbetrieb_lernende ll
        JOIN tbl_lernende l ON ll.fk_lernende = l.id_lernende
        JOIN tbl_lehrbetrieb lb ON ll.fk_lehrbetrieb = lb.id_lehrbetrieb
    ';

    $stmt = $db->query($query);
    $stmt->execute();

    // Fetch all records
    $lehrbetriebLernende = $stmt->fetchAll();

    // Send success response with the records
    Response::json([
        'status' => 'success',
        'message' => 'Lehrbetrieb-Lernende relationships retrieved successfully',
        'data' => $lehrbetriebLernende
    ], Response::OK);
} catch (Exception $e) {
    // Handle unexpected errors with a 500 Internal Server Error response
    Response::json([
        'status' => 'error',
        'message' => 'An error occurred while retrieving lehrbetrieb_lernende relationships',
        'data' => ['error' => $e->getMessage()]
    ], Response::SERVER_ERROR);
}
