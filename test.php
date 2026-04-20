<?php
require_once 'config.php';

function respondOK(): void
{
    echo "OK";
    exit;
}

$rawBody = file_get_contents("php://input");
$entry = "-----\n";
$entry .= "time=" . date('Y-m-d H:i:s') . "\n";
$entry .= "method=" . ($_SERVER['REQUEST_METHOD'] ?? 'UNKNOWN') . "\n";
$entry .= "query=" . ($_SERVER['QUERY_STRING'] ?? '') . "\n";
$entry .= "body:\n";
$entry .= $rawBody;
$entry .= "\n";

file_put_contents(TEST_RAW_LOG_FILE, $entry, FILE_APPEND);

respondOK();
