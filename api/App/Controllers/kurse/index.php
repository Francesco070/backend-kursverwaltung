<?php

use App\Core\DatabaseConnection;
use App\Core\Response;

// Get the database connection
$db = DatabaseConnection::getDatabase();

try {
    // Query to select all kurse
    $query = 'SELECT k.id_kurs, k.kursnummer, k.kursthema, k.inhalt, k.startdatum, k.enddatum, k.dauer, CONCAT(d.vorname, " ", d.nachname) AS dozent 
          FROM tbl_kurse k
          LEFT JOIN tbl_dozenten d ON k.fk_dozent = d.id_dozent';
    $stmt = $db->query($query);
    $stmt->execute();

    // Fetch all records
    $kurse = $stmt->fetchAll();

    // Send success response with the records
    Response::json([
        'status' => 'success',
        'message' => 'Kurse retrieved successfully',
        'data' => $kurse
    ], Response::OK);
} catch (Exception $e) {
    // Handle unexpected errors with a 500 Internal Server Error response
    Response::json([
        'status' => 'error',
        'message' => 'An error occurred while retrieving kurse',
        'data' => ['error' => $e->getMessage()]
    ], Response::SERVER_ERROR);
}
