<?php

use App\Core\DatabaseConnection;
use App\Core\Response;

// Get the database connection
$db = DatabaseConnection::getDatabase();

if (isset($params['id']) && ctype_digit($params['id'])) {
    $id = (int) $params['id'];

    try {
        $query = 'SELECT * FROM tbl_dozenten WHERE id_dozent = :id';
        $stmt = $db->query($query);
        $stmt->execute([':id' => $id]);
        $dozent = $stmt->fetch();

        if ($dozent) {
            Response::json([
                'status' => 'success',
                'message' => 'Dozent found.',
                'data' => $dozent
            ], Response::OK);
        } else {
            Response::json([
                'status' => 'error',
                'message' => 'Dozent not found.'
            ], Response::NOT_FOUND);
        }
    } catch (Exception $e) {
        Response::json([
            'status' => 'error',
            'message' => 'An error occurred while retrieving the dozent.'
        ], Response::SERVER_ERROR);
    }
} else {
    Response::json([
        'status' => 'error',
        'message' => 'Invalid ID parameter.'
    ], Response::BAD_REQUEST);
}
