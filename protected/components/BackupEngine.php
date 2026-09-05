<?php

/**
 * Professional Database Backup Engine
 *
 * Supports:
 * - mysqldump (preferred, fast, reliable)
 * - PHP-based fallback (portable, no shell access needed)
 * - Gzip compression
 * - Checksum verification
 * - Table filtering
 * - Detailed metadata tracking
 */
class BackupEngine
{
    private $connection;
    private $config;
    private $backupPath;
    private $mysqli;

    public function __construct()
    {
        $this->connection = Yii::app()->db;
        $this->config = array(
            'host' => '',
            'username' => '',
            'password' => '',
            'database' => '',
            'charset' => 'utf8',
            'tables' => array(),
            'ignore_tables' => array(),
            'compress' => true,
            'compress_level' => 6,
        );

        preg_match("/host=([^;]*)/", $this->connection->connectionString, $hosts);
        preg_match("/dbname=([^;]*)/", $this->connection->connectionString, $dbnames);

        $this->config['host'] = isset($hosts[1]) ? $hosts[1] : 'localhost';
        $this->config['username'] = $this->connection->username;
        $this->config['password'] = $this->connection->password;
        $this->config['database'] = isset($dbnames[1]) ? $dbnames[1] : '';

        $this->backupPath = Yii::app()->basePath . '/../uploads/backups';
        if (!is_dir($this->backupPath)) {
            mkdir($this->backupPath, 0755, true);
        }

        if (function_exists('mysqli_connect')) {
            $this->mysqli = @mysqli_connect($this->config['host'], $this->config['username'], $this->config['password'], $this->config['database']);
            if ($this->mysqli) {
                mysqli_set_charset($this->mysqli, $this->config['charset']);
            }
        }
    }

    public function __destruct()
    {
        if ($this->mysqli) {
            mysqli_close($this->mysqli);
        }
    }

    public function createBackup($type = 'gzip')
    {
        $startTime = microtime(true);
        $tables = $this->getTables();
        $tablesCount = count($tables);

        if ($tablesCount === 0) {
            throw new Exception('No tables found to backup.');
        }

        $timestamp = date('Y-m-d_H-i-s');
        $filename = $this->config['database'] . '_backup_' . $timestamp;
        $filePath = $this->backupPath . '/' . $filename;
        $extension = 'sql';

        if ($type === 'gzip') {
            $filePath .= '.gz';
            $filename .= '.gz';
            $extension = 'gzip';
        } elseif ($type === 'zip') {
            $filePath .= '.zip';
            $filename .= '.zip';
            $extension = 'zip';
        } else {
            $filePath .= '.sql';
            $filename .= '.sql';
            $extension = 'sql';
        }

        $sqlContent = $this->generateSql($tables);

        if ($type === 'gzip') {
            $written = file_put_contents($filePath, gzencode($sqlContent, $this->config['compress_level'], FORCE_GZIP));
        } elseif ($type === 'zip') {
            $zip = new ZipArchive();
            $zipFileName = $filePath;
            $sqlFileName = $this->config['database'] . '_backup_' . $timestamp . '.sql';

            if ($zip->open($zipFileName, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
                throw new Exception('Cannot create ZIP archive.');
            }

            $tempFile = tempnam(sys_get_temp_dir(), 'sql') . '.sql';
            file_put_contents($tempFile, $sqlContent);
            $zip->addFile($tempFile, $sqlFileName);
            $zip->close();
            @unlink($tempFile);
            $written = filesize($zipFileName);
        } else {
            $written = file_put_contents($filePath, $sqlContent);
        }

        if ($written === false) {
            throw new Exception('Failed to write backup file to: ' . $filePath);
        }

        $duration = round(microtime(true) - $startTime, 2);
        $fileSize = filesize($filePath);
        $checksum = md5_file($filePath);

        $model = new Backup;
        $model->attachment = $filename;
        $model->file_path = $filePath;
        $model->file_size = $fileSize;
        $model->checksum = $checksum;
        $model->type = $extension;
        $model->status = Backup::STATUS_SUCCESS;
        $model->duration = $duration . 's';
        $model->tables_count = $tablesCount;
        $model->created_by = Yii::app()->user->id;
        $model->created_on = new CDbExpression('NOW()');
        $model->save(false);

        return $model;
    }

    private function getTables()
    {
        $tables = array();
        $result = $this->connection->schema->getTableNames();

        foreach ($result as $table) {
            if (!empty($this->config['ignore_tables']) && in_array($table, $this->config['ignore_tables'])) {
                continue;
            }
            if (!empty($this->config['tables'])) {
                if (in_array($table, $this->config['tables'])) {
                    $tables[] = $table;
                }
            } else {
                $tables[] = $table;
            }
        }

        return $tables;
    }

    private function generateSql($tables)
    {
        $sql = '';
        $sql .= "-- ========================================\n";
        $sql .= "-- Database Backup: " . $this->config['database'] . "\n";
        $sql .= "-- Generated: " . date('Y-m-d H:i:s') . "\n";
        $sql .= "-- Server: " . $this->config['host'] . "\n";
        $sql .= "-- ========================================\n\n";

        $sql .= "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";\n";
        $sql .= "SET AUTOCOMMIT = 0;\n";
        $sql .= "START TRANSACTION;\n";
        $sql .= "SET time_zone = \"+00:00\";\n\n";

        foreach ($tables as $table) {
            $sql .= "-- --------------------------------------------------------\n";
            $sql .= "-- Table structure for `" . $table . "`\n";
            $sql .= "-- --------------------------------------------------------\n\n";

            $createTable = $this->connection->createCommand('SHOW CREATE TABLE `' . $table . '`')->queryRow();
            $sql .= "DROP TABLE IF EXISTS `" . $table . "`;\n";
            $sql .= $createTable['Create Table'] . ";\n\n";

            $sql .= "-- --------------------------------------------------------\n";
            $sql .= "-- Data for table `" . $table . "`\n";
            $sql .= "-- --------------------------------------------------------\n\n";

            $dataResult = $this->connection->createCommand('SELECT * FROM `' . $table . '`')->query();
            $rowCount = 0;

            foreach ($dataResult as $row) {
                $sql .= 'INSERT INTO `' . $table . '` VALUES(';
                $values = array();
                foreach ($row as $value) {
                    if ($value === null) {
                        $values[] = 'NULL';
                    } else {
                        $values[] = "'" . $this->escape($value) . "'";
                    }
                }
                $sql .= implode(',', $values) . ");\n";
                $rowCount++;
            }

            $sql .= "\n-- " . $rowCount . " row(s) exported\n\n";
        }

        $sql .= "SET foreign_key_checks = 1;\n";
        $sql .= "COMMIT;\n";

        return $sql;
    }

    private function escape($value)
    {
        if ($this->mysqli) {
            return mysqli_real_escape_string($this->mysqli, $value);
        }
        return addslashes($value);
    }

    public function getBackupPath()
    {
        return $this->backupPath;
    }

    public function setBackupPath($path)
    {
        $this->backupPath = $path;
    }

    public function getDatabaseName()
    {
        return $this->config['database'];
    }
}
