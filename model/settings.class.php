<?php

class Settings {

    /**
     * const usertable
     * When you have an existing database with a table that has also the name 'users', 
     * you can define another name for the calendar's users table. 
     * Do this in /model/users.class.php, /model/settings.class.php and /lib/utils.class.php.
     * 
     * It is not for using your own users table in the calendar
     */
    const usertable = 'users';
    
    public static function getSetting($name = '', $user_id = -1) {
        global $obj_db;
        $int_user_id = -1;
        $setting = '';

        if ($user_id > 0) {
            $int_user_id = $user_id;
        } else {
            $arr_user = User::getUser();

            if (!is_null($arr_user['user_id'])) {
                $int_user_id = $arr_user['user_id'];
            }
        }

        if ($int_user_id > 0) {
            $str_query = 'SELECT `value` FROM `settings` s LEFT JOIN `' . self::usertable . '` u ON(u.user_id = s.user_id) ' .
                    ' WHERE `s`.`name` = "' . $name . '"';
            $str_query .= ' AND `s`.`user_id` = "' . $int_user_id . '" AND `u`.`active`= 1';

            $obj_result = mysqli_query($obj_db, $str_query);

            if ($obj_result !== false) {
                $arr_line = mysqli_fetch_array($obj_result, MYSQLI_ASSOC);

                if ($arr_line !== false && !empty($arr_line) && !empty($arr_line['value'])) {
                    $setting = $arr_line['value'];
                }
            }
        }
        if (empty($setting)) {
            $setting = self::getAdminSetting($name);
        }


        return $setting;
    }

    /**
     * 
     * @global type $obj_db
     * @param type $user_id
     * @return type
     */
    public static function getSettings($user_id = '') {
        global $obj_db;
        $arr_result = array();

        if (!empty($user_id)) {
            $str_query = 'SELECT s.*,u.user_id FROM `settings` s LEFT JOIN `' . self::usertable . '` u ON(u.user_id = s.user_id) ' .
                    ' WHERE `s`.`user_id` = "' . $user_id . '" ';
            if ($user_id == 1000000) {
                // do not check on active, 1000000 is not a real user
            } else {
                $str_query .= 'AND `u`.`active`= 1';
            }

            $obj_result = mysqli_query($obj_db, $str_query);

            while ($arr_line = mysqli_fetch_array($obj_result, MYSQLI_ASSOC)) {
                $arr_result[$arr_line['name']] = $arr_line['value'];
            }
        }

        $arr_settings = array('language', 'other_language', 'timezone', 'show_am_pm', 'default_view', 'show_view_type',
            'week_view_type', 'day_view_type',
            'show_delete_confirm_dialog', 'show_weeknumbers', 'truncate_title',
            'truncate_length', 'editdialog_colorpicker_type', 'show_notallowed_messages',
            'editdialog_timepicker_type', 'hourcalculation_workday_hours',
            'hourcalculation_default_period', 'send_activation_mail', //'users_can_register',
            'show_public_and_private_separately',
//                        'pdf_table_look','pdf_show_time_columns',
//                        'pdf_show_date_on_every_line','pdf_show_logo','pdf_fontweight_bold',
//                        'lang_page','lang_of','date_header','starttime_header','endtime_header','title_header'
        );

        foreach ($arr_settings as &$name) {
            if ($name == 'other_language' && !empty($arr_result[$name])) {

                if (!file_exists(FULLCAL_URL . '/script/lang' . strtoupper($arr_result[$name]) . '.js')) {
                    $arr_result['language'] = "EN";
                } else {
                    $arr_result['language'] = $arr_result['other_language'];
                }
            }

            if (!array_key_exists($name, $arr_result) || empty($arr_result[$name])) {
                $arr_result[$name] = self::getAdminSetting($name);
            }
        }

        $arr_result['dropdown_1'] = CustomFields::getCustomDropdown1();
        $arr_result['dropdown_2'] = CustomFields::getCustomDropdown2();
        $arr_result['dropdown1_label'] = CustomFields::getDropdown1Label();
        $arr_result['dropdown2_label'] = CustomFields::getDropdown2Label();
        
        // for now the dropdowns are global, same for all admins, so we can get the label from the custom_fields table
        
        // else from the settings table, like it is done by default
        
        //custom_dropdown_1_label
        
        return $arr_result;
    }

    public static function saveSetting($name, $value, $section = '', $user_id = '') {
        global $obj_db;

        $str_query = 'REPLACE INTO settings (`value`, `name`, `section`, `user_id`, `update_date`) ' .
                'VALUES ("' . $value . '", "' . $name . '", "' . $section . '", "' . $user_id . '", "' . date('Y-m-d H:i:s') . '") ';

        $obj_result = mysqli_query($obj_db, $str_query);
        return $obj_result;
    }

    public static function saveSettings($arr_settings, $section = '', $user_id = '') {
        global $obj_db;

        if(isset($_SESSION['ews_custom_field_options'])) {
            $_SESSION['ews_custom_field_options'] = array();
        }
        foreach ($arr_settings as $key => $value) {
            if($key == 'dropdown_1_options') {
                // save dropdown fields
                CustomFields::saveCustomDropdown1($value);
            } else if($key == 'dropdown_2_options') {
                // save dropdown fields
                CustomFields::saveCustomDropdown2($value);
            } else if($key == 'dropdown1_label') {
                // save dropdown label
                CustomFields::saveDropdown1Label($value);
            } else if($key == 'dropdown2_label') {
                // save dropdown label
                CustomFields::saveDropdown2Label($value);
            } else {
                if (!empty($value)) {
                    $str_query = 'REPLACE INTO settings (`value`, `name`, `section`, `user_id`, `update_date`) ' .
                            'VALUES ("' . $value . '", "' . $key . '", "' . $section . '", "' . $user_id . '", "' . date('Y-m-d H:i:s') . '") ';

                    $obj_result = mysqli_query($obj_db, $str_query);
                }
                if ($key == 'other_language' && $value == '') {
                    $str_query = 'DELETE FROM settings WHERE `name` = "' . $key . '" AND `section` = "' . $section . '" AND `user_id` = "' . $user_id . '"';

                    $obj_result = mysqli_query($obj_db, $str_query);
                }
            }
            
        }
        return true;
    }

    public static function saveDefaultSettings($user_id = -1) {
        if ($user_id > 0) {
            //     self::saveSetting('show_description_field', 'on', '', $user_id);
            //     self::saveSetting('show_location_field', 'on', '', $user_id);
            //     self::saveSetting('show_phone_field', 'on', '', $user_id);
            //    self::saveSetting('show_url_field', 'on', '', $user_id);
            self::saveSetting('show_am_pm', 'off', '', $user_id);
            self::saveSetting('show_delete_confirm_dialog', 'on', '', $user_id);
            self::saveSetting('truncate_title', 'off', '', $user_id);
            self::saveSetting('show_notallowed_messages', 'off', '', $user_id);
            self::saveSetting('show_weeknumbers', 'on', '', $user_id);
            self::saveSetting('default_view', 'month', '', $user_id);
            self::saveSetting('week_view_type', 'agendaWeek', '', $user_id);
            self::saveSetting('day_view_type', 'agendaDay', '', $user_id);
            self::saveSetting('language', 'EN', '', $user_id);
            self::saveSetting('truncate_length', '50', '', $user_id);
            self::saveSetting('editdialog_colorpicker_type', 'spectrum', '', $user_id);
            self::saveSetting('editdialog_timepicker_type', 'ui', '', $user_id);
            self::saveSetting('hourcalculation_workday_hours', '8', '', $user_id);
            self::saveSetting('hourcalculation_default_period', '6', '', $user_id);
            self::saveSetting('show_view_type', 'none', '', $user_id);
            self::saveSetting('timezone', '', '', $user_id);
            self::saveSetting('send_activation_mail', 'on', '', $user_id);
            //self::saveSetting('users_can_register', 'off', '', $user_id);
            self::saveSetting('show_public_and_private_separately', 'on', '', $user_id);
        }
    }

    public static function getLanguage($user_id = -1) {
        $language = '';
        if ($user_id > 0) {
            $language = Settings::getSetting('other_language', $user_id);
            if (empty($language)) {
                $language = Settings::getSetting('language', $user_id);
            } else {
                if (!file_exists(FULLCAL_URL . '/script/lang' . strtoupper($language) . '.js')) {
                    $language = "EN";
                }
            }
        }
        if (empty($language)) {
            if (defined('LANGUAGE')) {
                $language = LANGUAGE;
            } else {
                $language = 'EN';
            }
        }
        return $language;
    }

    public static function getAdminSetting($name, $int_user_id = -1) {
        global $obj_db;

        $arr_user = array();
        $str_query = '';

        if ($int_user_id > 0) {
            // get user
            $arr_user = User::getUserById($int_user_id);
        } else {
            $arr_user = User::getUser();
        }

        $setting = '';
        $bln_one_admin = User::onlyOneAdmin();

        if (!empty($arr_user)) {
            $str_query = ' SELECT * ' .
                    ' FROM `settings` s' .
                    ' LEFT JOIN `' . self::usertable . '` u ON ( s.user_id = u.user_id )' .
                    ' WHERE s.name = "' . $name . '"' .
                    ' AND u.usertype = "admin"';
            if (User::isAdmin($int_user_id)) {
                $str_query .= ' AND user_id = ' . $arr_user['user_id'];
            } else if (User::isUser($int_user_id)) {
                $str_query .= ' AND user_id = ' . $arr_user['admin_group'];
            }
            $str_query .= ' LIMIT 1 ';
        } else if ($bln_one_admin) {
            $str_query = ' SELECT * ' .
                    ' FROM `settings` s' .
                    ' LEFT JOIN `' . self::usertable . '` u ON ( s.user_id = u.user_id )' .
                    ' WHERE s.name = "' . $name . '"' .
                    ' AND u.usertype = "admin"';

            $str_query .= ' LIMIT 1 ';
        }


        if (!empty($str_query)) {
            $obj_result = mysqli_query($obj_db, $str_query);

            if ($obj_result !== false) {
                $arr_line = mysqli_fetch_array($obj_result, MYSQLI_ASSOC);

                if ($arr_line !== false && !empty($arr_line)) {
                    if (!empty($arr_line['value'])) {
                        $setting = $arr_line['value'];
                    }
                }
            }
        }

        if (empty($setting)) {
            switch ($name) {
                case 'language':
                    if (defined('LANGUAGE')) {
                        $setting = LANGUAGE;
                    } else {
                        $setting = 'EN';
                    }
                    break;
                case 'timezone':
                    if (defined('TIMEZONE')) {
                        $setting = TIMEZONE;
                    } else {
                        $setting = '';
                    }
                    break;
                case 'show_am_pm':
                    if (defined('SHOW_AM_PM')) {
                        $setting = SHOW_AM_PM;
                    } else {
                        $setting = 'off';
                    }
                    break;
                case 'default_view':
                    if (defined('DEFAULT_VIEW')) {
                        $setting = DEFAULT_VIEW;
                    } else {
                        $setting = 'month';
                    }
                    break;
                case 'show_view_type':
                    if (defined('SHOW_VIEW_TYPE')) {
                        $setting = SHOW_VIEW_TYPE;
                    } else {
                        $setting = 'none';
                    }
                    break;
                case 'show_description_field':
                    $setting = 'on';
                    break;
                case 'show_location_field':
                    $setting = 'on';
                    break;
                case 'show_phone_field':
                    $setting = 'off';
                    break;
                case 'show_team_member_field':
                    $setting = 'off';
                    break;
                case 'show_dropdown_1_field':
                    $setting = 'off';
                    break;
                case 'show_dropdown_2_field':
                    $setting = 'off';
                    break;
                case 'show_url_field':
                    $setting = 'off';
                    break;
                case 'dropdowns_are_linked':
                    $setting = 'off';
                    break;
                case 'week_view_type':
                    if (defined('WEEK_VIEW_TYPE')) {
                        $setting = WEEK_VIEW_TYPE;
                    } else {
                        $setting = 'agendaWeek';
                    }
                    break;
                case 'day_view_type':
                    if (defined('DAY_VIEW_TYPE')) {
                        $setting = DAY_VIEW_TYPE;
                    } else {
                        $setting = 'agendaDay';
                    }
                    break;
                case 'show_delete_confirm_dialog':
                    if (defined('SHOW_DELETE_CONFIRM_DIALOG')) {
                        $setting = SHOW_DELETE_CONFIRM_DIALOG;
                    } else {
                        $setting = 'on';
                    }
                    break;
                case 'show_weeknumbers':
                    if (defined('SHOW_WEEKNUMBERS')) {
                        $setting = SHOW_WEEKNUMBERS;
                    } else {
                        $setting = 'on';
                    }
                    break;
                case 'truncate_title':
                    if (defined('TRUNCATE_TITLE')) {
                        $setting = TRUNCATE_TITLE;
                    } else {
                        $setting = 'off';
                    }
                    break;
                case 'truncate_length':
                    if (defined('TRUNCATE_LENGTH')) {
                        $setting = TRUNCATE_LENGTH;
                    } else {
                        $setting = '50';
                    }
                    break;
                case 'editdialog_colorpicker_type':
                    if (defined('EDITDIALOG_COLORPICKER_TYPE')) {
                        $setting = EDITDIALOG_COLORPICKER_TYPE;
                    } else {
                        $setting = 'spectrum';
                    }
                    break;
                case 'show_notallowed_messages':
                    if (defined('SHOW_NOTALLOWED_MESSAGES')) {
                        $setting = SHOW_NOTALLOWED_MESSAGES;
                    } else {
                        $setting = 'off';
                    }
                    break;
                case 'editdialog_timepicker_type':
                    if (defined('EDITDIALOG_TIMEPICKER_TYPE')) {
                        $setting = EDITDIALOG_TIMEPICKER_TYPE;
                    } else {
                        $setting = 'ui';
                    }
                    break;
                case 'hourcalculation_workday_hours':
                    if (defined('HOURCALCULATION_WORKDAY_HOURS')) {
                        $setting = HOURCALCULATION_WORKDAY_HOURS;
                    } else {
                        $setting = 8;
                    }
                    break;
                case 'hourcalculation_default_period':
                    if (defined('HOURCALCULATION_DEFAULT_PERIOD')) {
                        $setting = HOURCALCULATION_DEFAULT_PERIOD;
                    } else {
                        $setting = 6;
                    }
                    break;
                case 'send_activation_mail':
                    if (defined('SEND_ACTIVATION_MAIL')) {
                        $setting = SEND_ACTIVATION_MAIL;
                    } else {
                        $setting = 'on';
                    }
                    break;
//                case 'users_can_register':
//                    if(defined('USERS_CAN_REGISTER')) {
//                        $setting = USERS_CAN_REGISTER;
//                    } else {
//                        $setting = 'off';
//                    }
//                    break;
                case 'show_public_and_private_separately':
                    if (defined('SHOW_PUBLIC_AND_PRIVATE_SEPARATELY')) {
                        $setting = SHOW_PUBLIC_AND_PRIVATE_SEPARATELY;
                    } else {
                        $setting = 'on';
                    }
                    break;
//                case 'pdf_table_look':
//                    $setting = 'off';
//                    break;
//                case 'pdf_show_time_columns':
//                    $setting = 'on';
//                    break;
//                case 'pdf_show_date_on_every_line':
//                    $setting = 'off';
//                    break;
//                case 'pdf_show_logo':
//                    $setting = 'on';
//                    break;
//                case 'pdf_fontweight_bold':
//                    $setting = 'off';
//                    break;
//                case 'lang_page':
//                    $setting = 'Page';
//                    break;
//                case 'lang_of':
//                    $setting = 'of';
//                    break;
//                case 'date_header':
//                    $setting = 'Date';
//                    break;
//                case 'starttime_header':
//                    $setting = 'start';
//                    break;
//                case 'endtime_header':
//                    $setting = 'end';
//                    break;
//                case 'title_header':
//                    $setting = 'Event';
//                    break;

                default:
                    break;
            }
        }
        return $setting;
    }

    public static function getTimezone($user_id) {
        $default_view = Settings::getSetting('timezone', $user_id);
        if (empty($default_view)) {
            if (defined('TIMEZONE')) {
                $default_view = TIMEZONE;
            } else {
                $default_view = '';
            }
        }
        return $default_view;
    }

}

?>
