<?php
require_once 'config.php';

$rows = [];
$error = '';

if (!file_exists(LOG_FILE)) {
    $error = 'Log file not found.';
} else {
    $decoded = json_decode(file_get_contents(LOG_FILE), true);
    if (is_array($decoded)) {
        $rows = array_reverse($decoded);
    } else {
        $error = 'Log file is not valid JSON.';
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Attendance Logs</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f7f7f9; color: #222; }
        h1 { margin-bottom: 12px; }
        .meta { margin-bottom: 16px; color: #555; }
        .error { padding: 10px; background: #ffe7e7; border: 1px solid #ffb3b3; margin-bottom: 12px; }
        table { width: 100%; border-collapse: collapse; background: #fff; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; font-size: 14px; }
        th { background: #efefef; }
        tr:nth-child(even) { background: #fafafa; }
        .empty { padding: 12px; background: #fff; border: 1px solid #ddd; }
    </style>
</head>
<body>
    <h1>Attendance Logs</h1>
    <div class="meta">Total records: <?php echo count($rows); ?></div>

    <?php if ($error !== ''): ?>
        <div class="error"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
    <?php elseif (count($rows) === 0): ?>
        <div class="empty">No attendance records yet.</div>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Device SN</th>
                    <th>User ID</th>
                    <th>Timestamp</th>
                    <th>Status</th>
                    <th>Punch</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rows as $index => $row): ?>
                    <tr>
                        <td><?php echo $index + 1; ?></td>
                        <td><?php echo htmlspecialchars((string) ($row['device_sn'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars((string) ($row['user_id'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars((string) ($row['timestamp'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars((string) ($row['status'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars((string) ($row['punch'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>
