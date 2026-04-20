<?php
require_once 'config.php';

header('Content-Type: text/plain; charset=UTF-8');

if (!file_exists(TEST_RAW_LOG_FILE)) {
    echo '';
    exit;
}

readfile(TEST_RAW_LOG_FILE);
