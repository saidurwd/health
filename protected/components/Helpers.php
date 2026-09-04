<?php

/**
 * Description of Helpers
 *
 * @author Administrator
 */
class Helpers {

    public static function backupDatabase($filepath, $tables = '*') {
        if ($tables == '*') {
            $tables = array();
            $tables = Yii::app()->db->schema->getTableNames();
        } else {
            $tables = is_array($tables) ? $tables : explode(',', $tables);
        }
        $return = '';
        $return .= "-- \n";
        $return .= "-- Generation Time: " . date('l jS \of F Y h:i:s A') . " \n";
        $return .= "-- \n\n";
        $return .= "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\"; \n";
        $return .= "SET AUTOCOMMIT = 0; \n";
        $return .= "START TRANSACTION; \n";
        $return .= "SET time_zone = \"+00:00\"; \n\n";

        foreach ($tables as $table) {
            $result = Yii::app()->db->createCommand('SELECT * FROM ' . $table)->query();
            $return .= "-- \n";
            $return .= "-- Table structure for table `' . $table . '` \n";
            $return .= "-- \n\n";
            $return .= 'DROP TABLE IF EXISTS ' . $table . ';';
            $row2 = Yii::app()->db->createCommand('SHOW CREATE TABLE ' . $table)->queryRow();
            $return .= "\n\n" . $row2['Create Table'] . ";\n\n";
            foreach ($result as $row) {
                $return .= 'INSERT INTO ' . $table . ' VALUES(';
                foreach ($row as $data) {
                    $data = addslashes($data);
                    // Updated to preg_replace to suit PHP5.3 +
                    $data = preg_replace("/\n/", "\\n", $data);
                    if (isset($data)) {
                        $return .= '"' . $data . '"';
                    } else {
                        $return .= '""';
                    }
                    $return .= ',';
                }
                $return = substr($return, 0, strlen($return) - 1);
                $return .= ");\n";
            }
            $return .= "\n\n\n";
//    }
            //save file
            $handle = fopen($filepath, 'w+');
            fwrite($handle, $return);
            fclose($handle);
        }
    }

}
