<?php
require_once 'config.php';

function respondOK(): void
{
    echo "OK";
    exit;
}

$method = $_SERVER['REQUEST_METHOD'] ?? 'UNKNOWN';

// GET is handshake only.
if ($method === 'GET') {
    respondOK();
}

if ($method === 'POST') {
    $table = strtoupper((string) ($_GET['table'] ?? ''));

    // Save only attendance payloads.
    if ($table !== 'ATTLOG') {
        respondOK();
    }

    $sn = $_GET['SN'] ?? 'UNKNOWN';
    $stamp = $_GET['Stamp'] ?? '';
    $body = file_get_contents("php://input");
    $lines = explode("\n", trim((string) $body));
    $records = [];

    // Example row:
    // 1    2026-04-27 18:00:38    0    4    0...
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '') {
            continue;
        }

        $parts = preg_split('/\s+/', $line);
        if (!is_array($parts) || count($parts) < 5) {
            continue;
        }

        $records[] = [
            "device_sn" => (string) $sn,
            "stamp" => (string) $stamp,
            "user_id" => (string) $parts[0],
            "timestamp" => $parts[1] . ' ' . $parts[2],
            "status" => (int) $parts[3],
            "punch" => (int) $parts[4],
            "received_at" => date('Y-m-d H:i:s')
        ];
    }

    if (!is_dir(dirname(ATTLOG_FILE))) {
        mkdir(dirname(ATTLOG_FILE), 0777, true);
    }

    if (!file_exists(ATTLOG_FILE)) {
        file_put_contents(ATTLOG_FILE, json_encode([], JSON_PRETTY_PRINT));
    }

    $existing = json_decode((string) file_get_contents(ATTLOG_FILE), true);
    if (!is_array($existing)) {
        $existing = [];
    }

    $existing = array_merge($existing, $records);
    file_put_contents(ATTLOG_FILE, json_encode($existing, JSON_PRETTY_PRINT));

    respondOK();
}

respondOK();
