<?php

use App\Core\DatabaseConnection;
use App\Core\Response;

$db = DatabaseConnection::getDatabase();

if (isset($params['id']) && ctype_digit($params['id'])) {
    $id = (int) $params['id'];

    try {
        $query = 'DELETE FROM tbl_dozenten WHERE id_dozent = :id';
        $stmt = $db->query($query);
        $stmt->execute([':id' => $id]);

        Response::json([
            'status' => 'success',
            'message' => 'Dozent deleted successfully.'
        ], Response::OK);
    } catch (Exception $e) {
        Response::json([
            'status' => 'error',
            'message' => 'An error occurred while deleting the dozent.'
        ], Response::SERVER_ERROR);
    }
} else {
    Response::json([
        'status' => 'error',
        'message' => 'Invalid ID parameter.'
    ], Response::BAD_REQUEST);
}
