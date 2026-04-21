# ADMS Attendance Receiver (ZKTeco)

This project receives attendance data from ZKTeco devices using ADMS push mode and stores logs in local files.

## Live Base URL

Current deployment is configured on subdomain root:

- `https://adms.jtech.so/`

That means endpoints are:

- `https://adms.jtech.so/iclock/cdata` (ADMS receiver)
- `https://adms.jtech.so/attendance` (attendance table view)
- `https://adms.jtech.so/test` (raw capture endpoint for testing)
- `https://adms.jtech.so/raw` (raw log display page)

## Project Structure

```text
adms/
|- .htaccess
|- index.php
|- attendance.php
|- test.php
|- raw.php
|- config.php
|- db.php
|- logs/
   |- attendance.json
   |- test-raw.log
```

## How ADMS Works in This Setup

1. Device sends `GET`/`POST` requests to ADMS endpoint (`/iclock/cdata`).
2. `index.php` responds with `OK` (required by device).
3. On `POST`, attendance lines are parsed and appended to `logs/attendance.json`.
4. `attendance.php` reads `attendance.json` and shows records in table format.
5. `test.php` stores request payloads into `logs/test-raw.log` for debugging.
6. `raw.php` shows `test-raw.log` as plain text.

## Device Configuration (ZKTeco Cloud Server Setting)

Use these values:

- **Server Mode**: `ADMS`
- **Enable Domain Name**: `ON`
- **Server Address**: `https://adms.jtech.so/`
- **HTTPS**: `ON`
- **Enable Proxy Server**: `OFF` (unless your network requires a proxy)

Notes:

- On many firmware versions, entering the root server URL works best because device appends ADMS paths internally.
- If your firmware requires a full path, use `https://adms.jtech.so/iclock/cdata`.

## Apache Rewrite Rules

Current `.htaccess` routes:

- `/iclock/cdata` -> `index.php`
- `/attendance` -> `attendance.php`
- `/test` -> `test.php`
- `/raw` -> `raw.php`

## Data Files

- Parsed attendance logs: `logs/attendance.json`
- Raw test logs: `logs/test-raw.log`

## Quick Validation

Open these URLs:

- `https://adms.jtech.so/` (should return `OK`)
- `https://adms.jtech.so/iclock/cdata` (should return `OK`)
- `https://adms.jtech.so/attendance` (attendance page)
- `https://adms.jtech.so/raw` (raw log view)

## Troubleshooting

- If device cannot connect:
  - confirm DNS resolves to server IP
  - confirm SSL certificate is valid
  - keep HTTPS enabled on device
  - ensure firewall allows inbound 443
- If logs show many `OPLOG` or `~DeviceName` entries:
  - receiver is also getting non-attendance packets
  - add filtering rules in parser if you want only real punches
- If nothing is written:
  - verify file permissions for `logs/`
  - verify Apache/PHP can write files

## If Deployed in a Subfolder

If this project is deployed under a path like `/adms` (not domain root), endpoints become:

- `/adms/iclock/cdata`
- `/adms/attendance`
- `/adms/test`
- `/adms/raw`

Update device server address accordingly.
