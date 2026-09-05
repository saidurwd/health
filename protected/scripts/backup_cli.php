<?php
/**
 * CLI Database Backup Script
 *
 * Usage:
 *   php protected/scripts/backup_cli.php [type] [days]
 *
 * Arguments:
 *   type   - Backup type: gzip (default), zip, sql
 *   days   - Retention days for cleanup (default: 30, 0 to skip cleanup)
 *
 * Examples:
 *   php protected/scripts/backup_cli.php gzip 30
 *   php protected/scripts/backup_cli.php sql 7
 *   php protected/scripts/backup_cli.php gzip 0
 *
 * Cron example (daily at 2 AM):
 *   0 2 * * * cd /path/to/health && php protected/scripts/backup_cli.php gzip 30 >> protected/runtime/backup_cron.log 2>&1
 */

require_once(dirname(__FILE__) . '/../../index.php');

$type = isset($argv[1]) ? $argv[1] : 'gzip';
$retentionDays = isset($argv[2]) ? (int) $argv[2] : 30;

if (!Yii::app()->user->getIsGuest()) {
    Yii::app()->user->logout();
}

echo "[" . date('Y-m-d H:i:s') . "] Starting database backup...\n";

try {
    $engine = new BackupEngine;
    $engine->setBackupPath(Yii::app()->basePath . '/../uploads/backups');

    $backup = $engine->createBackup($type);

    echo "[" . date('Y-m-d H:i:s') . "] Backup completed successfully.\n";
    echo "  File: " . $backup->attachment . "\n";
    echo "  Size: " . Backup::formatBytes($backup->file_size) . "\n";
    echo "  Tables: " . $backup->tables_count . "\n";
    echo "  Duration: " . $backup->duration . "\n";
    echo "  Checksum: " . $backup->checksum . "\n";

    if ($retentionDays > 0) {
        echo "[" . date('Y-m-d H:i:s') . "] Running cleanup (older than {$retentionDays} days)...\n";
        $deleted = Backup::cleanOldBackups($retentionDays);
        echo "  Deleted {$deleted} old backup(s).\n";
    }

    exit(0);
} catch (Exception $e) {
    echo "[" . date('Y-m-d H:i:s') . "] Backup FAILED: " . $e->getMessage() . "\n";
    exit(1);
}
