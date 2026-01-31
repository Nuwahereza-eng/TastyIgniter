<?php
/**
 * Health Check Endpoint for Railway/Docker deployments
 * Returns 200 OK if the server is running
 */

// Simple health check - just return OK
http_response_code(200);
header('Content-Type: application/json');
echo json_encode([
    'status' => 'ok',
    'timestamp' => date('Y-m-d H:i:s'),
    'service' => 'ugaeats'
]);
