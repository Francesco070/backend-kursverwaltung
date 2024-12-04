<?php

use App\Core\DatabaseConnection;
use App\Core\Response;

// Get the database connection
$db = DatabaseConnection::getDatabase();

// Check if 'id' is set in $params and is a valid integer
if (isset($params['id']) && ctype_digit($params['id'])) {
    $id = (int) $params['id'];

    try {
        // Prepare and execute the query to find the lehrbetrieb by id
        $query = 'SELECT * FROM tbl_lehrbetrieb WHERE id_lehrbetrieb = :id';
        $stmt = $db->query($query);
        $stmt->execute([':id' => $id]);

        $lehrbetrieb = $stmt->fetch();

        if ($lehrbetrieb) {
            Response::json([
                'status' => 'success',
                'message' => 'Lehrbetrieb found',
                'data' => $lehrbetrieb
            ], Response::OK);
        } else {
            Response::json([
                'status' => 'error',
                'message' => 'Lehrbetrieb not found'
            ], Response::NOT_FOUND);
        }
    } catch (Exception $e) {
        Response::json([
            'status' => 'error',
            'message' => 'An error occurred while retrieving the Lehrbetrieb',
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
