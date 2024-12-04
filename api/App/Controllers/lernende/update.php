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

    // Sanitize inputs
    $fieldsToUpdate = [];
    $params = [];

    // Check each field and add to update list if provided
    if (isset($input['vorname'])) {
        $fieldsToUpdate[] = 'vorname = :vorname';
        $params[':vorname'] = htmlspecialchars($input['vorname'], ENT_QUOTES, 'UTF-8');
    }
    if (isset($input['nachname'])) {
        $fieldsToUpdate[] = 'nachname = :nachname';
        $params[':nachname'] = htmlspecialchars($input['nachname'], ENT_QUOTES, 'UTF-8');
    }

    // Additional fields follow the same structure...
    // ...

    if (empty($fieldsToUpdate)) {
        Response::json([
            'status' => 'error',
            'message' => 'No fields to update'
        ], Response::BAD_REQUEST);
        exit;
    }

    // Add the ID for WHERE clause
    $params[':id'] = $id;

    // Construct the SQL query
    $query = 'UPDATE tbl_lernende SET ' . implode(', ', $fieldsToUpdate) . ' WHERE id_lernende = :id';

    try {
        $stmt = $db->query($query);
        $stmt->execute($params);

        Response::json([
            'status' => 'success',
            'message' => 'Lernende updated successfully!'
        ], Response::OK);
    } catch (Exception $e) {
        Response::json([
            'status' => 'error',
            'message' => 'An error occurred while updating the Lernende',
            'data' => ['error' => $e->getMessage()]
        ], Response::SERVER_ERROR);
    }
} else {
    Response::json([
        'status' => 'error',
        'message' => 'Invalid ID parameter'
    ], Response::BAD_REQUEST);
}
?>
