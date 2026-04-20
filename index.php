<?php
require_once 'config.php';

// Always respond OK to device.
function respondOK(): void
{
    echo "OK";
    exit;
}

// HANDSHAKE (GET)
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    respondOK();
}

// RECEIVE DATA (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sn = $_GET['SN'] ?? 'UNKNOWN';
    $body = file_get_contents("php://input");

    // Example row:
    // 12345    2026-04-20 08:30:00    0    1    0
    $lines = explode("\n", trim($body));
    $records = [];

    foreach ($lines as $line) {
        $parts = preg_split('/\s+/', trim($line));

        if (count($parts) >= 5) {
            $user_id = $parts[0];
            $time = $parts[1] . ' ' . $parts[2];
            $status = (int) $parts[3];
            $punch = (int) $parts[4];

            $records[] = [
                "device_sn" => $sn,
                "user_id" => $user_id,
                "timestamp" => $time,
                "status" => $status,
                "punch" => $punch
            ];
        }
    }

    // Save to JSON file
    if (!file_exists(LOG_FILE)) {
        file_put_contents(LOG_FILE, json_encode([], JSON_PRETTY_PRINT));
    }

    $existing = json_decode(file_get_contents(LOG_FILE), true);
    if (!is_array($existing)) {
        $existing = [];
    }
    $existing = array_merge($existing, $records);

    file_put_contents(LOG_FILE, json_encode($existing, JSON_PRETTY_PRINT));

    respondOK();
}

// Fallback for unsupported methods.
respondOK();
