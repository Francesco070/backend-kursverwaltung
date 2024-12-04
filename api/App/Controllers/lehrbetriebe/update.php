<?php

use App\Core\DatabaseConnection;
use App\Core\Response;

// Get the database connection
$db = DatabaseConnection::getDatabase();

// Check if 'id' is set in $params and is a valid integer
if (isset($params['id']) && ctype_digit($params['id'])) {
    $id = (int) $params['id'];

    // Read the raw JSON input
    $input = json_decode(file_get_contents('php://input'), true);

    // Sanitize inputs using htmlspecialchars
    $firma = isset($input['firma']) ? htmlspecialchars($input['firma'], ENT_QUOTES, 'UTF-8') : null;
    $strasse = isset($input['strasse']) ? htmlspecialchars($input['strasse'], ENT_QUOTES, 'UTF-8') : null;
    $plz = isset($input['plz']) ? htmlspecialchars($input['plz'], ENT_QUOTES, 'UTF-8') : null;
    $ort = isset($input['ort']) ? htmlspecialchars($input['ort'], ENT_QUOTES, 'UTF-8') : null;

    // Initialize an array to store the fields to update
    $fieldsToUpdate = [];
    $params = [];

    // Add the fields that are provided in the input to the update list
    if ($firma !== null) {
        $fieldsToUpdate[] = 'firma = :firma';
        $params[':firma'] = $firma;
    }
    if ($strasse !== null) {
        $fieldsToUpdate[] = 'strasse = :strasse';
        $params[':strasse'] = $strasse;
    }
    if ($plz !== null) {
        $fieldsToUpdate[] = 'plz = :plz';
        $params[':plz'] = $plz;
    }
    if ($ort !== null) {
        $fieldsToUpdate[] = 'ort = :ort';
        $params[':ort'] = $ort;
    }

    // If no fields are provided, send a 400 Bad Request response
    if (empty($fieldsToUpdate)) {
        Response::json([
            'status' => 'error',
            'message' => 'No fields to update'
        ], Response::BAD_REQUEST);
        exit;
    }

    // Add the ID to the params for the WHERE clause
    $params[':id'] = $id;

    // Construct the SQL query with dynamic SET clause
    $query = 'UPDATE tbl_lehrbetrieb SET ' . implode(', ', $fieldsToUpdate) . ' WHERE id_lehrbetrieb = :id';

    try {
        // Prepare and execute the query
        $stmt = $db->query($query);
        $stmt->execute($params);

        // Send a success response
        Response::json([
            'status' => 'success',
            'message' => 'Lehrbetrieb updated successfully!'
        ], Response::OK);

    } catch (Exception $e) {
        // Handle unexpected errors with a 500 Internal Server Error response
        Response::json([
            'status' => 'error',
            'message' => 'An error occurred while updating the Lehrbetrieb',
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
?>
