<?php

use App\Core\DatabaseConnection;
use App\Core\Response;

$db = DatabaseConnection::getDatabase();

if (isset($params['id']) && ctype_digit($params['id'])) {
    $id = (int) $params['id'];
    $input = json_decode(file_get_contents('php://input'), true);
    $country = htmlspecialchars($input['country'] ?? '', ENT_QUOTES, 'UTF-8');

    if (empty($country)) {
        Response::json([
            'status' => 'error',
            'message' => 'Country name is required.'
        ], Response::BAD_REQUEST);
        exit;
    }

    try {
        $query = 'UPDATE tbl_countries SET country = :country WHERE id_countries = :id';
        $stmt = $db->query($query);
        $stmt->execute([':country' => $country, ':id' => $id]);

        Response::json([
            'status' => 'success',
            'message' => 'Country updated successfully.'
        ], Response::OK);
    } catch (Exception $e) {
        Response::json([
            'status' => 'error',
            'message' => 'An error occurred while updating the country.'
        ], Response::SERVER_ERROR);
    }
} else {
    Response::json([
        'status' => 'error',
        'message' => 'Invalid ID parameter.'
    ], Response::BAD_REQUEST);
}
