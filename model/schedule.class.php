<?php

//require_once CLASSES_DIR.'/config.class.php';

class Schedule {

    const MINUTE = 60;
    const HOUR = 3600;
    const DAY = 86400;
    const WEEK = 604800;

    public static function fetchValues($date) {
        global $obj_db;
        $arr_values = array();
        $arr_schedule = array();

        $str_query_r = 'SELECT * FROM schedule WHERE DATE(`last_exec_date`) = "' . $date . '" ORDER BY last_exec_date DESC LIMIT 1';

        $res1 = mysqli_query($obj_db, $str_query_r);

        if ($res1 !== false) {
            while ($arr_line = mysqli_fetch_array($res1, MYSQLI_ASSOC)) {
                $arr_schedule[] = $arr_line;
            }
        }

        if (!is_null($arr_schedule) && is_array($arr_schedule)) {
            foreach ($arr_schedule as $c) {
                $arr_values[$c['jobname']] = $c['last_exec_date'];
                $_SESSION['ews_schedule_' . $c['jobname']] = $c['last_exec_date'];
            }
            return $arr_values;
        } else {
            return false;
        }
    }

    public static function run($bln_execute = false) {

        if (((!defined('SCHEDULE_SQLDUMP')) || (defined('SCHEDULE_SQLDUMP') && SCHEDULE_SQLDUMP == 'never') ) && ((!defined('SAVE_CURRENT_EDITING') || (defined('SAVE_CURRENT_EDITING') && SAVE_CURRENT_EDITING === false)))) {
            return;
            exit;
        }


        /**
         * SQLDUMP
         */
        if (defined('SCHEDULE_SQLDUMP') && (SCHEDULE_SQLDUMP == 'day' || SCHEDULE_SQLDUMP == 'hour')) {
            $bln_execute = false;

            if (SCHEDULE_SQLDUMP == 'day') {
                if (file_exists(FULLCAL_DIR . '/system/sqldump.txt')) {
                    $str_date = file_get_contents(FULLCAL_DIR . '/system/sqldump.txt');

                    if (trim($str_date) != date('Y-m-d')) {
                        $bln_execute = true;
                    }
                } else {
                    $bln_execute = true;
                }
            } else {
                if (file_exists(FULLCAL_DIR . '/system/sqldump.txt')) {
                    $str_date_and_hour = file_get_contents(FULLCAL_DIR . '/system/sqldump.txt');

                    if (trim($str_date_and_hour) != date('Y-m-d-H')) {
                        $bln_execute = true;
                    }
                } else {
                    $bln_execute = true;
                }
            }

            if ($bln_execute) {
                error_log('saveSqlDumpToFile');
                $bln_success = self::makeMysqldump('file');
                error_log($bln_success ? 'saveSqlDumpToFile success' : 'saveSqlDumpToFile failure');

                if (SCHEDULE_SQLDUMP == 'day') {
                    file_put_contents(FULLCAL_DIR . '/system/sqldump.txt', date('Y-m-d'));
                } else {
                    file_put_contents(FULLCAL_DIR . '/system/sqldump.txt', date('Y-m-d-H'));
                }
            }
        }

        /**
         * CURRENT EDITING
         */
        if (defined('SAVE_CURRENT_EDITING') && SAVE_CURRENT_EDITING === true) {
            error_log('deleteOldCurrentEditingRows');
            $bln_execute = false;

            if (file_exists(FULLCAL_DIR . '/system/current_editing.txt')) {
                $str_hour = file_get_contents(FULLCAL_DIR . '/system/current_editing.txt');

                if (trim($str_hour) != date('H')) {
                    $bln_execute = true;
                }
            } else {
                $bln_execute = true;
            }

            if ($bln_execute) {
                global $obj_db;

                $str_query2 = 'UPDATE `current_editing` SET `active` = 0 WHERE `edit_date` < NOW() - INTERVAL 30 MINUTE';

                mysqli_query($obj_db, $str_query2);

                $str_query3 = 'REPLACE INTO `schedule` SET `jobname` = "current_editing" , `last_exec_date` = NOW() ';

                mysqli_query($obj_db, $str_query3);

                file_put_contents(FULLCAL_DIR . '/system/current_editing.txt', date('H'));
            }
        }
    }

    static public function makeMysqldump($type = 'text') {

        //connect with database
        $obj_db = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAAM);
        if ($obj_db === FALSE) {
            $error = "Database connection failed";
            printf("Connect failed: %s\n", mysqli_connect_error());
            exit();
        }

//        $tables = array();
//        $result = mysqli_query($obj_db, 'SHOW TABLES');
//        while ($row = mysqli_fetch_row($result)) {
//            $tables[] = $row[0];
//        }

        $return = '';

        $tables = array('calendars','calendar_dditems','calendar_options','calendar_locations','current_editing','events','event_files',
                        'groups','group_users','repeating_events','schedule','settings','users');
        
        //cycle through
        foreach ($tables as $table) {
            $result = mysqli_query($obj_db, 'SELECT * FROM ' . $table);
            $num_fields = mysqli_num_fields($result);

            $arr_create_table = mysqli_fetch_row(mysqli_query($obj_db, 'SHOW CREATE TABLE ' . $table));

            $str_create_table = "\n\n" . $arr_create_table[1] . ";\n\n";

            $return .= str_replace('CREATE TABLE `' . $table . '` (', 'CREATE TABLE IF NOT EXISTS `' . $table . '` (', $str_create_table);

            for ($i = 0; $i < $num_fields; $i++) {
                while ($row = mysqli_fetch_row($result)) {
                    $return.= 'INSERT INTO `' . $table . '` VALUES(';
                    for ($j = 0; $j < $num_fields; $j++) {
                        $row[$j] = addslashes($row[$j]);
                        $row[$j] = str_replace("\n", "\\n", $row[$j]);
                        if (isset($row[$j])) {
                            $return.= '"' . $row[$j] . '"';
                        } else {
                            $return.= '""';
                        }
                        if ($j < ($num_fields - 1)) {
                            $return.= ',';
                        }
                    }
                    $return.= ");\n";
                }
            }
            $return.="\n\n\n";
        }

        $int_result = file_put_contents(FULLCAL_DIR . '/system/dbdump/caldump_' . date("Y-m-d_H-i") . '.txt', $return);

        if ($int_result > 0) {
            // met bijgewerkte datum in schedule tabel zetten
            $str_query = 'REPLACE INTO `schedule` SET `jobname` = "sqldump" , `last_exec_date` = NOW() ';

            global $obj_db;

            $obj_result = mysqli_query($obj_db, $str_query);
            if ($obj_result !== false) {
                return true;
            }
        }
        return false;
    }

}

?>
