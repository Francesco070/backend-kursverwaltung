<?php

use App\Core\DatabaseConnection;
use App\Core\Response;

// Get the database connection
$db = DatabaseConnection::getDatabase();

// Check if 'id' is set in $params and is a valid integer
if (isset($params['id']) && ctype_digit($params['id'])) {
    $id = (int) $params['id'];

    try {
        // Prepare the query to retrieve the kurs_lernende by id
        $query = 'SELECT * FROM tbl_kurse_lernende WHERE id_kurs_lernende = :id';
        $stmt = $db->query($query); // Use prepare() instead of query()
        $stmt->execute([':id' => $id]);

        $kursLernende = $stmt->fetch();

        if ($kursLernende) {
            // Send success response with the found record
            Response::json([
                'status' => 'success',
                'message' => 'Kurs-Lernende record found',
                'data' => $kursLernende
            ], Response::OK);
        } else {
            // Send a 404 Not Found response if no record is found
            Response::json([
                'status' => 'error',
                'message' => 'Kurs-Lernende record not found'
            ], Response::NOT_FOUND);
        }
    } catch (Exception $e) {
        // Handle unexpected errors with a 500 Internal Server Error response
        Response::json([
            'status' => 'error',
            'message' => 'An error occurred while retrieving the Kurs-Lernende record',
            'data' => ['error' => $e->getMessage()]
        ], Response::SERVER_ERROR);
    }
} else {
    // Send a 400 Bad Request response if 'id' is missing or invalid
    Response::json([
        'status' => 'error',
        'message' => 'Invalid ID parameter'
    ], Response::BAD_REQUEST);
}
