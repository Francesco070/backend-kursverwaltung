<?php

use App\Core\DatabaseConnection;
use App\Core\Response;

// Get the database connection
$db = DatabaseConnection::getDatabase();

// Check if 'id' is provided
if (isset($params['id']) && ctype_digit($params['id'])) {
    $id = (int) $params['id'];

    // SQL Query to get the Lernende by ID
    $query = 'SELECT * FROM tbl_lernende WHERE id_lernende = :id';

    try {
        $stmt = $db->query($query);
        $stmt->execute([':id' => $id]);

        // Check if the record exists
        if ($stmt->rowCount() > 0) {
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            Response::json([
                'status' => 'success',
                'data' => $data
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
            'message' => 'An error occurred while fetching the Lernende',
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
