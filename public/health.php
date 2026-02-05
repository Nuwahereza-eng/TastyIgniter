<?php
/**
 * Health Check Endpoint for Railway/Docker deployments
 * Returns 200 OK if the server is running
 * Kept extremely simple to ensure fast response
 */

// Disable error display to ensure clean JSON response
error_reporting(0);
ini_set('display_errors', 0);

// Simple health check - just return OK immediately
http_response_code(200);
header('Content-Type: application/json');
header('Cache-Control: no-cache, no-store, must-revalidate');
echo json_encode([
    'status' => 'ok',
    'timestamp' => date('Y-m-d H:i:s'),
    'service' => 'ugaeats'
]);
exit;
