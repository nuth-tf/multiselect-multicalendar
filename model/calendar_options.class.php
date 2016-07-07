<?php

class CalendarOption {

    /**
     * 
     * @global type $obj_db
     * @param type $name
     * @param type $calendar_id
     * @return type
     */
    public static function getOption($name = '', $calendar_id = -1, $default_value = '') {
        global $obj_db;
        $setting = '';

        if ($calendar_id > 0 && !empty($name)) {
            $str_query = 'SELECT `value` FROM `calendar_options` WHERE `name` = "' . $name . '"';
            $str_query .= ' AND `calendar_id` = "' . $calendar_id . '" ';

            $obj_result = mysqli_query($obj_db, $str_query);

            if ($obj_result !== false) {
                $arr_line = mysqli_fetch_array($obj_result, MYSQLI_ASSOC);

                if (is_null($arr_line)) {
                    return $default_value;
                }
                if ($arr_line !== false && !empty($arr_line) && !empty($arr_line['value'])) {
                    $setting = $arr_line['value'];
                }
            }
        }

        return $setting;
    }

    /**
     * 
     * @global type $obj_db
     * @param type $calendar_id
     * @return type
     */
    public static function getOptions($calendar_id = '') {
        global $obj_db;
        $arr_result = array();
        $arr_return = array();

        if (!empty($calendar_id)) {
            $str_query = 'SELECT * FROM `calendar_options` WHERE `calendar_id` = "' . $calendar_id . '" ';

            $obj_result = mysqli_query($obj_db, $str_query);

            while ($arr_line = mysqli_fetch_array($obj_result, MYSQLI_ASSOC)) {
                $arr_result[$arr_line['name']] = $arr_line['value'];
            }
        }

        return $arr_result;
    }
    
    public static function getAllOptions() {
        global $obj_db;
        $arr_result = array();
        $arr_return = array();

        $str_query = 'SELECT * FROM `calendar_options`';

        $obj_result = mysqli_query($obj_db, $str_query);

        while ($arr_line = mysqli_fetch_array($obj_result, MYSQLI_ASSOC)) {
            if(!isset($arr_result[$arr_line['calendar_id']])) {
                $arr_result[$arr_line['calendar_id']] = array();
            }
            $arr_result[$arr_line['calendar_id']][$arr_line['name']] = $arr_line['value'];
        }
        
        return $arr_result;
    }

    public static function getOptionsByName($key = '') {
        global $obj_db;
        $arr_result = array();
        $arr_return = array();

        $str_query = 'SELECT * FROM `calendar_options` WHERE `name` = "' . $key . '" ';

        $obj_result = mysqli_query($obj_db, $str_query);

        while ($arr_line = mysqli_fetch_array($obj_result, MYSQLI_ASSOC)) {
            $arr_result[$arr_line['calendar_id']] = $arr_line['value'];
        }

        return $arr_result;
    }

    /**
     * 
     * @global type $obj_db
     * @param type $name
     * @param type $value
     * @param type $section
     * @param type $calendar_id
     * @return type
     */
    public static function saveOption($name, $value, $section = '', $calendar_id = '') {
        global $obj_db;

        $str_query = 'REPLACE INTO calendar_options (`value`, `name`, `section`, `calendar_id`, `update_date`) ' .
                'VALUES ("' . $value . '", "' . $name . '", "' . $section . '", "' . $calendar_id . '", "' . date('Y-m-d H:i:s') . '") ';

        $obj_result = mysqli_query($obj_db, $str_query);
        return $obj_result;
    }

    /**
     * 
     * @global type $obj_db
     * @param type $arr_settings
     * @param type $section
     * @param type $calendar_id
     * @return boolean
     */
    public static function saveOptions($arr_settings, $section = '', $calendar_id = '') {
        global $obj_db;

        foreach ($arr_settings as $key => $value) {
            //if(!empty($value)) {
            $str_query = 'REPLACE INTO calendar_options (`value`, `name`, `section`, `calendar_id`, `update_date`) ' .
                    'VALUES ("' . $value . '", "' . $key . '", "' . $section . '", "' . $calendar_id . '", "' . date('Y-m-d H:i:s') . '") ';

            $obj_result = mysqli_query($obj_db, $str_query);
            //}
        }
        return true;
    }

}

?>