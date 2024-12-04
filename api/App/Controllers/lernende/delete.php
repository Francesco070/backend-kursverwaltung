<?php

use App\Core\DatabaseConnection;
use App\Core\Response;

// Get the database connection
$db = DatabaseConnection::getDatabase();

// Check if 'id' is provided
if (isset($params['id']) && ctype_digit($params['id'])) {
    $id = (int) $params['id'];

    // SQL Query to delete the Lernende by ID
    $query = 'DELETE FROM tbl_lernende WHERE id_lernende = :id';

    try {
        $stmt = $db->query($query);
        $stmt->execute([':id' => $id]);

        // Check if row was deleted
        if ($stmt->rowCount() > 0) {
            Response::json([
                'status' => 'success',
                'message' => 'Lernende deleted successfully'
            ], Response::OK);
        } else {
            Response::json([
                'status' => 'error',
                'message' => 'Lernende not found'
            ], Response::NOT_FOUND);
        }
    } catch (Exception $e) {
        Response::json([
            'status' => 'error',
            'message' => 'An error occurred while deleting the Lernende',
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
