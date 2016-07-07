<?php


class CustomFields {
    /**
     * 
     * @global type $obj_db
     * @param type $dropdown_1_fields
     */
    public static function saveCustomDropdown1($dropdown_1_fields='') {
        
        if (!empty($dropdown_1_fields)) {

            global $obj_db;
            
            // example: 1|hhh|#3366cc, 2|nnnn|#3366cc,
            
            $arr_custom_fields = explode(',', $dropdown_1_fields);
               
            $arr_done_items = array();

            foreach($arr_custom_fields as $field) {
                $f = trim($field);
                $f = trim($f, ',');
                if (!empty($f)) {

                    if (strstr($f, '|')) {
                        
                        $arr_field = explode('|', $f);
                        $option_id = $arr_field[0];
                        $option = $arr_field[1];
                        $option_val = str_replace(' ', '_', strtolower($arr_field[1]));
                        $color = isset($arr_field[2]) ? $arr_field[2] : '';
                       
                        if ($option_id > 1000000) {
                            // newly added
                            if (!empty($option)) {
                                if (!in_array($option_id, $arr_done_items)) {
                                    
                                    $str_query3 = 'INSERT INTO `custom_fields_options` (`custom_field_id`,`value`, `text`,`color`) ' .
                                        'VALUES ("1", "' . $option_val . '", "' . $option . '", "' . $color . '") ';

                                    $res3 = mysqli_query($obj_db, $str_query3);
                                    
                                    $arr_done_items[] = $option_id;
                                }
                                
                            }
                        } else {
                            if (empty($option)) {
                                // delete dditem
                                $str_query2 = 'DELETE FROM custom_fields_options ' .
                                        'WHERE `option_id` = ' . $option_id;

                                $res = mysqli_query($obj_db, $str_query2);
                            } else {
                                if (!in_array($option_id, $arr_done_items)) {
                                    // doubles are extracted through the table index (calendar_id, title)
                                    $str_query = 'UPDATE custom_fields_options SET `value` = "' . $option_val . '",' .
                                            ' `text` = "' . $option . '",' .
                                            ' `color` = "' . $color . '" ' .
                                            ' WHERE `option_id` = ' . $option_id;

                                    $arr_done_items[] = $option_id;

                                    $res2 = mysqli_query($obj_db, $str_query);
                                }
                            }
                        }
                    }
                }
            } 
        }
    }
    
    /**
     * 
     * @global type $obj_db
     * @param type $dropdown_2_fields
     */
    public static function saveCustomDropdown2($dropdown_2_fields='') {
        
        if (!empty($dropdown_2_fields)) {

            global $obj_db;
            
            // example: 1|hhh|#3366cc, 2|nnnn|#3366cc,
            
            $arr_custom_fields = explode(',', $dropdown_2_fields);
               
            $arr_done_items = array();

            foreach($arr_custom_fields as $field) {
                $f = trim($field);
                $f = trim($f, ',');
                if (!empty($f)) {

                    if (strstr($f, '|')) {
                        
                        $arr_field = explode('|', $f);
                        $option_id = $arr_field[0];
                        $option = $arr_field[1];
                        $option_val = str_replace(' ', '_', strtolower($arr_field[1]));
                        $color = isset($arr_field[2]) ? $arr_field[2] : '';
                       
                        if ($option_id > 1000000) {
                            // newly added
                            if (!empty($option)) {
                                if (!in_array($option_id, $arr_done_items)) {
                                    
                                    $str_query3 = 'INSERT INTO `custom_fields_options` (`custom_field_id`,`value`, `text`,`color`) ' .
                                        'VALUES ("2", "' . $option_val . '", "' . $option . '", "' . $color . '") ';

                                    $res3 = mysqli_query($obj_db, $str_query3);
                                    
                                    $arr_done_items[] = $option_id;
                                }
                                
                            }
                        } else {
                            if (empty($option)) {
                                // delete dditem
                                $str_query2 = 'DELETE FROM custom_fields_options ' .
                                        'WHERE `option_id` = ' . $option_id;

                                $res = mysqli_query($obj_db, $str_query2);
                            } else {
                                if (!in_array($option_id, $arr_done_items)) {
                                    // doubles are extracted through the table index (calendar_id, title)
                                    $str_query = 'UPDATE custom_fields_options SET `value` = "' . $option_val . '",' .
                                            ' `text` = "' . $option . '",' .
                                            ' `color` = "' . $color . '" ' .
                                            ' WHERE `option_id` = ' . $option_id;

                                    $arr_done_items[] = $option_id;

                                    $res2 = mysqli_query($obj_db, $str_query);
                                }
                            }
                        }
                    }
                }
            } 
        }
    }
    
    /**
     * 
     * @global type $obj_db
     * @param type $value
     */
    public static function saveDropdown1Label($value) {
        global $obj_db;
        $str_query = 'UPDATE custom_fields SET `label` = "' . $value . '"' .
                        ' WHERE `field_id` = 1';

                $res2 = mysqli_query($obj_db, $str_query);
    }
    
    /**
     * 
     * @global type $obj_db
     * @param type $value
     */
    public static function saveDropdown2Label($value) {
        global $obj_db;
        $str_query = 'UPDATE custom_fields SET `label` = "' . $value . '"' .
                        ' WHERE `field_id` = 2';

                $res2 = mysqli_query($obj_db, $str_query);
    }
    
    public static function getDropdown1Label() {
        global $obj_db;
        $str_query = 'SELECT * FROM custom_fields ' .
                        ' WHERE `field_id` = 1';

        $obj_result = mysqli_query($obj_db, $str_query);

        if ($obj_result !== false) {
            $arr_line = mysqli_fetch_array($obj_result, MYSQLI_ASSOC);
            
            return $arr_line['label'];
        }
        
              
                
    }
    
    public static function getDropdown2Label() {
        global $obj_db;
        $str_query = 'SELECT * FROM custom_fields ' .
                        ' WHERE `field_id` = 2';

        $obj_result = mysqli_query($obj_db, $str_query);

        if ($obj_result !== false) {
            $arr_line = mysqli_fetch_array($obj_result, MYSQLI_ASSOC);
            
            return $arr_line['label']; 
        }
        
        
    }

    public static function getCustomDropdown1($bln_index=false) {
        global $obj_db;
        $arr_options = array();
        
        $str_query = 'SELECT * FROM custom_fields_options  ' .
                ' WHERE custom_field_id = 1';

        $obj_result = mysqli_query($obj_db, $str_query);

        if ($obj_result !== false) {
            while ($arr_line = mysqli_fetch_array($obj_result, MYSQLI_ASSOC)) {
                if($bln_index) {
                    $arr_options[$arr_line['option_id']] = $arr_line;
                } else {
                    $arr_options[] = $arr_line;
                }
            }
        }
        
        
        return $arr_options;
    }
    
    public static function getCustomDropdown2($bln_index=false) {
        global $obj_db;
        $arr_options = array();
        
        $str_query = 'SELECT * FROM custom_fields_options  ' .
                ' WHERE custom_field_id = 2';

        $obj_result = mysqli_query($obj_db, $str_query);

        if ($obj_result !== false) {
            while ($arr_line = mysqli_fetch_array($obj_result, MYSQLI_ASSOC)) {
                if($bln_index) {
                    $arr_options[$arr_line['option_id']] = $arr_line;
                } else {
                    $arr_options[] = $arr_line;
                }
            }
        }
        
        
        return $arr_options;
    }
    
    public static function getCustomDropdownOption($option_id) {
        global $obj_db;
        $arr_options = array();
    // TODO when dropdown per calendar also check which calendar_id    
        $str_query = 'SELECT * FROM custom_fields_options  ' .
                ' WHERE option_id = ' . $option_id;

        $obj_result = mysqli_query($obj_db, $str_query);

        if ($obj_result !== false) {
            $arr_line = mysqli_fetch_array($obj_result, MYSQLI_ASSOC);
        }
        
        
        return $arr_line;
    }
}
