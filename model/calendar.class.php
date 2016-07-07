<?php

class Calendar {

    private $cal_id;

    function __construct($cal_id) {
        $this->cal_id = $cal_id;
    }

    public static function insertFirstCalendar() {
        global $obj_db;

        $str_query = "INSERT INTO `calendars` (`name`, `share_type`, `calendar_color`, `login_required`, `cal_startdate`, `cal_enddate`, `alterable_startdate`, `alterable_enddate`, `creator_id`, `can_view`, `can_add`, `can_edit`, `can_delete`, `can_change_color`, `can_dd_drag`, `initial_show`, `active`, `deleted`, `calendar_admin_email`, `users_can_email_event`, `all_event_mods_to_admin`) VALUES " .
                "('Cal 1', 'public', '#FF5F3F', 0, NULL, NULL, NULL, NULL, 2, 0, 1, 1, 1, 0, 'everyone', 0, 'yes', 0, NULL, 0, 0)";

        $res = mysqli_query($obj_db, $str_query);
    }

    public static function getUsers() {
//        global $obj_db;
//        
//        $str_query = 'SELECT * FROM calendars WHERE calendar_id = ' . $int_cal_id;
//        $obj_result1 = mysqli_query($obj_db, $str_query);
//
//        if ($obj_result1 !== false) {
//            $arr_calendar = mysqli_fetch_array($obj_result1, MYSQLI_ASSOC);
//
//            $order_id2 = $arr_calendar['order_id'];
//        }
    }
    
    /**
     * 
     * @param type $arr_cal
     * @return boolean
     */
    public static function calCanMail($arr_cal) {
        if (User::isLoggedIn()) {
            if (isset($arr_cal['users_can_email_event']) && (bool) $arr_cal['users_can_email_event']) {
                if (isset($arr_cal['calendar_admin_email']) && !empty($arr_cal['calendar_admin_email'])) {
                    if (Utils::checkEmail($arr_cal['calendar_admin_email'])) {
                        return true;
                    }
                }
                if (defined('MAIL_EVENT_MAILADDRESS')) {
                    $mailaddress = MAIL_EVENT_MAILADDRESS;
                }
                if (!empty($mailaddress)) {
                    if (Utils::checkEmail($mailaddress)) {
                        return true;
                    }
                }
            }
        }

        return false;
    }

    /**
     * 
     * @param type $arr_cal
     * @return boolean
     */
    public static function calMailEventModsToAdmin($arr_cal) {
        if (User::isLoggedIn()) {
            if (isset($arr_cal['all_event_mods_to_admin']) && (bool) $arr_cal['all_event_mods_to_admin']) {
                if (isset($arr_cal['calendar_admin_email']) && !empty($arr_cal['calendar_admin_email'])) {
                    if (Utils::checkEmail($arr_cal['calendar_admin_email'])) {
                        return true;
                    }
                }
                if (defined('MAIL_EVENT_MAILADDRESS')) {
                    $mailaddress = MAIL_EVENT_MAILADDRESS;
                }
                if (!empty($mailaddress)) {
                    if (Utils::checkEmail($mailaddress)) {
                        return true;
                    }
                }
            }
        }

        return false;
    }

    /**
     * 
     * @param type $arr_cal
     * @param type $int_user_id
     * @return boolean
     */
    public static function calMailAssignedEventToUser($arr_cal, $int_user_id = -1) {
        // get the user and email address
        $arr_user = User::getUserById($int_user_id);

        if (isset($arr_user['email']) && !empty($arr_user['email']) && Utils::checkEmail($arr_user['email'])) {
            $str_email = $arr_user['email'];
            $arr_event_user = $arr_user;
            return true;
        }

        return false;
    }

    /**
     * 
     * @param type $arr_calendar
     * @param type $int_user_id
     * @return boolean
     */
    public static function notifyAssignedUser($arr_calendar, $int_user_id) {
        if (self::hasUsergroupDDItems($arr_calendar) && self::assignAddedEventToUser($arr_calendar) && User::isLoggedIn() && $int_user_id > 0 && isset($arr_calendar['mail_assigned_event_to_user']) && (bool) $arr_calendar['mail_assigned_event_to_user']) {
            return true;
        }
        return false;
    }
    
    public static function notifyAssignedTeamMember($arr_calendar, $frm_submitted) {
        if((bool)$arr_calendar['show_team_member_field'] && (bool)$arr_calendar['notify_assign_teammember'] && $frm_submitted['assign'] && $frm_submitted['team_member_id'] > 0) {
            return true;
        }
        return false;
    }
    
    public static function notifyUnAssignedTeamMember($arr_calendar, $frm_submitted) {return true;
        if((bool)$arr_calendar['show_team_member_field'] && (bool)$arr_calendar['notify_unassign_teammember'] && $frm_submitted['unassign'] && $frm_submitted['team_member_id'] > 0) {
            return true;
        }
        return false;
    }
    

    /**
     * 
     * @param type $arr_calendar
     * @return boolean
     */
    public static function hasUsergroupDDItems($arr_calendar) {
        if (isset($arr_calendar['dditems_usergroup_id']) && $arr_calendar['dditems_usergroup_id'] > 0) {
            return true;
        }
        return false;
    }

    /**
     * 
     * @param type $arr_calendar
     * @return boolean
     */
    public static function assignAddedEventToUser($arr_calendar) {
        if (isset($arr_calendar['dditems_usergroup_id']) && $arr_calendar['dditems_usergroup_id'] > 0 && isset($arr_calendar['assign_dditem_to_user']) && (bool) $arr_calendar['assign_dditem_to_user']) {
            return true;
        }
        return false;
    }

    /**
     * 
     * @global type $obj_db
     * @param type $from
     * @param type $int_cal_id
     */
    public static function changeOrder($arr_cal_ids) {
        global $obj_db;
        
        $order_id = 1;
        foreach($arr_cal_ids as $int_cal_id) {
            $str_update_query = 'UPDATE `calendars` SET `order_id` = ' . $order_id . ' WHERE `calendar_id` = ' . $int_cal_id;
            $res = mysqli_query($obj_db, $str_update_query);

            $order_id ++;
        }
           
        
    }
    
    /**
     * @param type $arr_cal
     * @return string 
     */
    public static function getCalendarAdminEmail($arr_cal) {
        if (isset($arr_cal['calendar_admin_email']) && !empty($arr_cal['calendar_admin_email'])) {
            if (Utils::checkEmail($arr_cal['calendar_admin_email'])) {
                return $arr_cal['calendar_admin_email'];
            }
        } else {
            if (defined('MAIL_EVENT_MAILADDRESS')) {
                $mailaddress = MAIL_EVENT_MAILADDRESS;
                if (!empty($mailaddress)) {
                    if (Utils::checkEmail($mailaddress)) {
                        return $mailaddress;
                    }
                }
            }
        }
        return '';
    }

    /**
     * 
     * @global type $obj_db
     * @param type $cal_id
     * @param type $dditems_as_string
     * @return type
     */
    public static function getCalendar($cal_id, $dditems_as_string = false, $locations_as_string = false) {
        global $obj_db;

        //   unset($_SESSION['ews_calendars']);    // little help for the programmer :)

        if (!isset($_SESSION['ews_calendars'])) {
            $_SESSION['ews_calendars'] = array();
        }

        if (isset($_SESSION['ews_calendars']) && isset($_SESSION['ews_calendars'][$cal_id]) && !empty($_SESSION['ews_calendars'][$cal_id])) {
            return $_SESSION['ews_calendars'][$cal_id];
        }

        $arr_calendar = array();

        // if(!empty($cal_id)) {
        // get calendar
        $str_query = 'SELECT * FROM calendars WHERE calendar_id = ' . $cal_id;
        $obj_result1 = mysqli_query($obj_db, $str_query);

        if ($obj_result1 !== false) {
            $arr_calendar = mysqli_fetch_array($obj_result1, MYSQLI_ASSOC);

            // get calendar drag and drop items
            $str_query = 'SELECT * FROM calendar_dditems WHERE calendar_id = ' . $cal_id;
            $obj_result = mysqli_query($obj_db, $str_query);

            $arr_dd_items = array();
            $str_dditems = '';

            while ($arr_line = mysqli_fetch_array($obj_result, MYSQLI_ASSOC)) {
                if (!isset($arr_line['starttime'])) {
                    $arr_line['starttime'] = '';
                }
                if (!isset($arr_line['endtime'])) {
                    $arr_line['endtime'] = '';
                }
                if (isset($arr_line['allDay']) && $arr_line['allDay'] == '1') {
                    $arr_line['starttime'] = '';
                    $arr_line['endtime'] = '';
                }

                if (!is_null($arr_line['color']) && !empty($arr_line['color'])) {
                    $str_dditems .= $arr_line['dditem_id'] . '|' . $arr_line['title'] . '|' . $arr_line['info'] . '|' . $arr_line['starttime'] . '|' . $arr_line['endtime'] . '|' . $arr_line['color'] . ', ';
                } else {
                    $str_dditems .= $arr_line['dditem_id'] . '|' . $arr_line['title'] . '|' . $arr_line['info'] . '|' . $arr_line['starttime'] . '|' . $arr_line['endtime'] . ', ';
                }

                $arr_dd_items[] = $arr_line;
            }

            // get calendar drag and drop items
            $str_query2 = 'SELECT * FROM calendar_locations WHERE calendar_id = ' . $cal_id;
            $obj_result2 = mysqli_query($obj_db, $str_query2);

            $arr_locations = array();
            $str_locations = '';

            while ($arr_line = mysqli_fetch_array($obj_result2, MYSQLI_ASSOC)) {
                if ($locations_as_string) {
                    $str_locations .= $arr_line['name'] . ', ';
                } else {
                    $arr_locations[] = $arr_line;
                }
            }

            //mysqli_close($obj_db);
            //if($dditems_as_string) {
            $arr_calendar['str_dditems'] = $str_dditems;
            // } else {
            $arr_calendar['dditems'] = $arr_dd_items;
            // }

            if ($locations_as_string) {
                $arr_calendar['locations'] = $str_locations;
            } else {
                $arr_calendar['locations'] = $arr_locations;
            }

            // get options
            $arr_calendar['show_description_field'] = (bool) CalendarOption::getOption('show_description_field', $cal_id, true);
            $arr_calendar['show_location_field'] = (bool) CalendarOption::getOption('show_location_field', $cal_id, true);
            $arr_calendar['show_phone_field'] = (bool) CalendarOption::getOption('show_phone_field', $cal_id, false);
            $arr_calendar['show_team_member_field'] = (bool) CalendarOption::getOption('show_team_member_field', $cal_id, false);
            $arr_calendar['notify_assign_teammember'] = (bool) CalendarOption::getOption('notify_assign_teammember', $cal_id, false);
            
            
            $arr_calendar['show_dropdown_1_field'] = (bool) CalendarOption::getOption('show_dropdown_1_field', $cal_id, false);
            $arr_calendar['show_dropdown_2_field'] = (bool) CalendarOption::getOption('show_dropdown_2_field', $cal_id, false);
            $arr_calendar['show_url_field'] = (bool) CalendarOption::getOption('show_url_field', $cal_id, false);
            $arr_calendar['description_field_required'] = (bool) CalendarOption::getOption('description_field_required', $cal_id, false);
            $arr_calendar['location_field_required'] = (bool) CalendarOption::getOption('location_field_required', $cal_id, false);
            $arr_calendar['phone_field_required'] = (bool) CalendarOption::getOption('phone_field_required', $cal_id, false);
            $arr_calendar['url_field_required'] = (bool) CalendarOption::getOption('url_field_required', $cal_id, false);
            $arr_calendar['dditems_usergroup_id'] = CalendarOption::getOption('dditems_usergroup_id', $cal_id, 0);
            $arr_calendar['usergroup_dditems_viewtype'] = CalendarOption::getOption('usergroup_dditems_viewtype', $cal_id, 'buttons');
            $arr_calendar['assign_dditem_to_user'] = (bool) CalendarOption::getOption('assign_dditem_to_user', $cal_id, false);
            $arr_calendar['mail_assigned_event_to_user'] = (bool) CalendarOption::getOption('mail_assigned_event_to_user', $cal_id, false);
            $arr_calendar['next_days_visible'] = CalendarOption::getOption('next_days_visible', $cal_id, '');

            $arr_calendar['add_team_member_to_title'] = (bool) CalendarOption::getOption('add_team_member_to_title', $cal_id, true);
            $arr_calendar['add_custom_dropdown1_to_title'] = (bool) CalendarOption::getOption('add_custom_dropdown1_to_title', $cal_id, true);
            $arr_calendar['add_custom_dropdown2_to_title'] = (bool) CalendarOption::getOption('add_custom_dropdown2_to_title', $cal_id, true);
            
            
            if (!empty($arr_calendar['cal_startdate']) && !empty($arr_calendar['cal_enddate'])) {
                // put dates in format the datepicker understands
                $arr_startdate_tmp = explode('-', $arr_calendar['cal_startdate']);
                $arr_enddate_tmp = explode('-', $arr_calendar['cal_enddate']);

                if (substr(DATEPICKER_DATEFORMAT, 0, 2) == 'mm') {
                    $arr_calendar['cal_startdate'] = $arr_startdate_tmp[1] . '/' . $arr_startdate_tmp[2] . '/' . $arr_startdate_tmp[0];
                    $arr_calendar['cal_enddate'] = $arr_enddate_tmp[1] . '/' . $arr_enddate_tmp[2] . '/' . $arr_enddate_tmp[0];
                } else {
                    $arr_calendar['cal_startdate'] = $arr_startdate_tmp[2] . '/' . $arr_startdate_tmp[1] . '/' . $arr_startdate_tmp[0];
                    $arr_calendar['cal_enddate'] = $arr_enddate_tmp[2] . '/' . $arr_enddate_tmp[1] . '/' . $arr_enddate_tmp[0];
                }
            }

            if (!empty($arr_calendar['alterable_startdate']) && !empty($arr_calendar['alterable_enddate'])) {
                // put dates in format the datepicker understands
                $arr_startdate_tmp2 = explode('-', $arr_calendar['alterable_startdate']);
                $arr_enddate_tmp2 = explode('-', $arr_calendar['alterable_enddate']);

                if (substr(DATEPICKER_DATEFORMAT, 0, 2) == 'mm') {
                    $arr_calendar['alterable_startdate'] = $arr_startdate_tmp2[1] . '/' . $arr_startdate_tmp2[2] . '/' . $arr_startdate_tmp2[0];
                    $arr_calendar['alterable_enddate'] = $arr_enddate_tmp2[1] . '/' . $arr_enddate_tmp2[2] . '/' . $arr_enddate_tmp2[0];
                } else {
                    $arr_calendar['alterable_startdate'] = $arr_startdate_tmp2[2] . '/' . $arr_startdate_tmp2[1] . '/' . $arr_startdate_tmp2[0];
                    $arr_calendar['alterable_enddate'] = $arr_enddate_tmp2[2] . '/' . $arr_enddate_tmp2[1] . '/' . $arr_enddate_tmp2[0];
                }
            }

            if (isset($arr_calendar['origin']) && $arr_calendar['origin'] == 'exchange') {
                $key = sha1($arr_calendar['name']);
                $arr_calendar['exchange_username'] = substr(rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode(CalendarOption::getOption('exchange_username', $arr_calendar['calendar_id'])), MCRYPT_MODE_CBC, md5($key)), "\0"), 0, 5) . '......';
                $arr_calendar['exchange_password'] = '12345678910';
                $arr_calendar['exchange_extra_secure'] = CalendarOption::getOption('exchange_extra_secure', $arr_calendar['calendar_id']);

                if ($arr_calendar['exchange_extra_secure'] == 1) {
                    $arr_calendar['exchange_token'] = '12345678910';    //CalendarOption::getOption('exchange_token', $arr_calendar['calendar_id']);
                }
            }
        }

        //  }

        $_SESSION['ews_calendars'][$cal_id] = $arr_calendar;

        return $arr_calendar;
    }

    /**
     * 
     * @param type $arr_calendar
     * @param type $user_id
     * @return type
     */
    public static function UserInGroup($arr_calendar, $user_id = -1) {

        //$arr_user = User::getUserById($user_id);  // old, when an admin could only have 1 group

        if ($user_id > 0 && isset($arr_calendar['usergroup_id'])) {
            $arr_group_ids = Group::getGroupsOfUser($user_id, true);

            return in_array($arr_calendar['usergroup_id'], $arr_group_ids);
        } else {
            return false;
        }


        //return (bool) $arr_calendar['creator_id'] == $arr_user['admin_group'];    // old, when an admin could only have 1 group
    }

    /**
     * 
     * @global type $obj_db
     * @param type $cal_ids
     * @return type
     */
    public static function getCalendarsByIds($cal_ids) {
        global $obj_db;
        $arr_calendars = array();

        $str_query = 'SELECT * FROM calendars c ' .
                ' WHERE calendar_id IN(' . $cal_ids . ')';

        $obj_result = mysqli_query($obj_db, $str_query);

        if ($obj_result !== false) {
            while ($arr_line = mysqli_fetch_array($obj_result, MYSQLI_ASSOC)) {
                $arr_calendars[] = $arr_line;
            }
        }
        return $arr_calendars;
    }

    public static function getCalendarsICanSee($int_user_id = -1) {
        global $obj_db;
        $arr_calendars_ids = array();

        if ($int_user_id == 1000000) {
            // only public calendars
            $str_query = 'SELECT * FROM calendars  WHERE `share_type` = "public" AND `deleted` = 0';
        } else {

            $str_query = 'SELECT * FROM calendars c  ' .
                    ' WHERE (`share_type` = "public"' .
                    ' OR (`creator_id` = ' . $int_user_id . ' )';

            // get groups of current logged in user
            $str_query2 = 'SELECT * FROM `group_users` gu LEFT JOIN `groups` g ON ( gu.group_id = g.group_id ) WHERE g.`deleted` = 0 AND user_id = ' . $int_user_id;

            $obj_result2 = mysqli_query($obj_db, $str_query2);

            $arr_groups = array();

            if ($obj_result2 !== false) {
                while ($arr_line = mysqli_fetch_array($obj_result2, MYSQLI_ASSOC)) {
                    $arr_groups[] = $arr_line['group_id'];
                }
            }

            if (!empty($arr_groups)) {
                $str_query .= ' OR (IF (`usergroup_id` IS NOT NULL, (`share_type` = "private_group" AND `usergroup_id` IN( ' . implode(',', $arr_groups) . ') ), "" ))';    //`share_type` != "private_group"
            }
    
            $str_query .= ' OR (`share_type` = "private_group" AND `can_view` = 1)';
                
            $str_query .= ' )';

            $str_query .= ' AND c.`deleted` =0 ';
        }

        $obj_result = mysqli_query($obj_db, $str_query);

        if ($obj_result !== false) {
            while ($arr_line = mysqli_fetch_array($obj_result, MYSQLI_ASSOC)) {
                $arr_calendars_ids[] = $arr_line['calendar_id'];
            }
        }

        return $arr_calendars_ids;
    }

    /**
     * 
     * @global type $obj_db
     * @param type $from_admin_area
     * @param type $deleted
     * @return type
     * @throws Exception
     */
    public static function getCalendars($from_admin_area = false, $deleted = false, $bln_only_ids = false) {
        global $obj_db;
        $arr_calendars = array();

        if (User::isLoggedIn()) {
            $arr_user = User::getUser();


            $is_admin = User::isAdmin();
            $is_superadmin = User::isSuperAdmin();

            if (($is_superadmin && $from_admin_area)) {
                $str_query = 'SELECT c.*, concat_ws(" ",firstname,infix,lastname) as fullname FROM calendars c LEFT JOIN `users` u ON(u.user_id = c.creator_id) ' .
                        ' WHERE 1 ';
            } else if ($is_superadmin && ADMIN_HAS_FULL_CONTROL) {
                $str_query = 'SELECT * FROM calendars c ' .
                        ' WHERE 1';
            } else if ($is_superadmin) {
                // only public calendars
                $str_query = 'SELECT * FROM calendars c ' .
                        ' WHERE `share_type` = "public"';
            } else if ($is_admin) {
                $str_query = 'SELECT * FROM calendars c ';

                if (ADMIN_HAS_FULL_CONTROL && !$from_admin_area) {
                    $str_query .= ' WHERE (`creator_id` = ' . $arr_user['user_id'] . ' OR `share_type` = "public")';
                } else {
                    $str_query .= ' WHERE `creator_id` = ' . $arr_user['user_id'];
                }
            } else {
                $str_query = 'SELECT * FROM calendars c  ' .
                        ' WHERE (`share_type` = "public"' .
                        ' OR (`creator_id` = ' . $arr_user['user_id'] . ' )'; //`share_type` = "private" AND 
                // get groups of current logged in user
                $arr_groups = Group::getMyGroups(true);

                if (!empty($arr_groups)) {
                    $str_query .= ' OR (IF (`usergroup_id` IS NOT NULL, (`share_type` = "private_group" AND `usergroup_id` IN( ' . implode(',', $arr_groups) . ') ), "" ))';    //`share_type` != "private_group"
                } 
                
                $str_query .= ' OR (`share_type` = "private_group" AND `can_view` = 1)';
                

                $str_query .= ' )';

                // old, when an admin only had 1 group
//                if(!empty($arr_user['admin_group'])) {
//                    $str_query .= ' OR (`share_type` = "private_group" AND `creator_id` = '.$arr_user['admin_group'].')';
//                } else {
//                     $str_query .= ' AND `share_type` != "private_group"';
//                }
            }
        } else {
            if (ALLOW_ACCESS_BY == 'ip' && User::ipAllowed()) {  // show all the calendars
                $str_query = 'SELECT * FROM calendars c ' .
                        ' WHERE 1 ';
            } else {
                $str_query = 'SELECT * FROM calendars c ' .
                        //   ' LEFT JOIN `users` `u` ON ( u.admin_group = c.calendar_id ) '.
                        ' WHERE ((`share_type` != "private" AND `share_type` != "private_group") ' .
                        ' OR (`share_type` = "private_group" AND c.can_view = 1))'; //GROUP BY c.calendar_id';
            }
        }

        if ($deleted) {
            $str_query .= ' AND c.`deleted` =1';
        } else {
            $str_query .= ' AND c.`deleted` =0 ';

            if (!$from_admin_area) {
                $str_query .= ' AND (c.`active` = "yes" ' .
                        ' OR (c.`active` = "period" AND `cal_startdate` <= CURDATE() AND `cal_enddate` >= CURDATE())' .
                        ')';
            }
        }
        
        if(defined('SORT_ALL_CALENDARS_BY_CAL_ORDERID') && SORT_ALL_CALENDARS_BY_CAL_ORDERID) {
            $str_query .= '  ORDER BY `order_id` ';
        }
        
        // echo $str_query; exit;
        $obj_result = mysqli_query($obj_db, $str_query);

        try {

            $arr_dditems_usergroup = CalendarOption::getOptionsByName('dditems_usergroup_id');

            if ($obj_result !== false) {
                while ($arr_line = mysqli_fetch_array($obj_result, MYSQLI_ASSOC)) {

                    if ($bln_only_ids) {
                        $arr_calendars_ids[] = $arr_line['calendar_id'];
                    }
                    $arr_calendars[] = $arr_line;
                }

                if ($bln_only_ids) {
                    return $arr_calendars_ids;
                }

                foreach ($arr_calendars as &$cal) {
                    // get calendar drag and drop items
                    $str_query = 'SELECT * FROM calendar_dditems WHERE calendar_id = ' . $cal['calendar_id'];
                    $obj_result = mysqli_query($obj_db, $str_query);

                    $arr_dd_items = array();

                    while ($arr_line2 = mysqli_fetch_array($obj_result, MYSQLI_ASSOC)) {
                        $arr_dd_items[] = $arr_line2;
                    }
                    $cal['dditems'] = $arr_dd_items;


                    if (array_key_exists($cal['calendar_id'], $arr_dditems_usergroup)) {
                        $int_dditems_usergroup_id = $arr_dditems_usergroup[$cal['calendar_id']];

                        if ($int_dditems_usergroup_id > 0) {
                            // get the users from the usergroup
                            $cal['usergroup_dditems'] = Group::getGroupUsers($int_dditems_usergroup_id);

                            // get name of user-group
                            $arr_usergroup = Group::getGroup($int_dditems_usergroup_id);
                            $cal['usergroup_name'] = $arr_usergroup['name'];

                            $cal['assign_dditem_to_user'] = (bool) CalendarOption::getOption('assign_dditem_to_user', $cal['calendar_id'], false);
                            $cal['mail_assigned_event_to_user'] = (bool) CalendarOption::getOption('mail_assigned_event_to_user', $cal['calendar_id'], false);
                        }
                    }

                    $cal['usergroup_dditems_viewtype'] = CalendarOption::getOption('usergroup_dditems_viewtype', $cal['calendar_id'], 'buttons');

                    // if active == 'period', check if period is now
                    if ($cal['active'] == 'period') {
                        if (strtotime($cal['cal_startdate'] . ' 00:00:01') < time() && (strtotime($cal['cal_enddate'] . ' 00:00:01') + 86400) > time()) {
                            $cal['active'] = 'yes';
                        } else {
                            $cal['active'] = 'no';
                        }
                    }
                }
            } else {
                throw new Exception('Something wrong with the "calendars" table, compare the table with the table in the file: calendar_complete_db_for_new_users.sql');
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
            exit;
        }
        return $arr_calendars;
    }

    /**
     * 
     * @global type $obj_db
     * @param type $int_user_id
     * @return type
     */
    public static function getOtherPublicCalendars($int_user_id) {
        global $obj_db;
        $arr_calendars = array();

        $str_query = 'SELECT * FROM calendars WHERE `deleted` = 0 AND creator_id != ' . $int_user_id . ' AND `share_type` = "public"';

        $obj_result = mysqli_query($obj_db, $str_query);

        if ($obj_result !== false) {
            while ($arr_line = mysqli_fetch_array($obj_result, MYSQLI_ASSOC)) {

                $arr_calendars[] = $arr_line;
            }
        }
        return $arr_calendars;
    }

    /**
     * 
     * @global type $obj_db
     * @param type $int_admin_id
     * @param type $deleted
     * @param type $bln_include_dditems
     * @param type $bln_return_ids
     * @return string
     */
    public static function getCalendarsOfAdmin($int_admin_id = -1, $deleted = false, $bln_include_dditems = true, $bln_return_ids = false) {
        global $obj_db;
        $arr_calendars = array();

        $str_query = 'SELECT * FROM calendars WHERE `deleted` = ' . ($deleted ? '1' : '0') . ' AND creator_id = ' . $int_admin_id;

        if(defined('SORT_ALL_CALENDARS_BY_CAL_ORDERID') && SORT_ALL_CALENDARS_BY_CAL_ORDERID) {
            $str_query .= '  ORDER BY `order_id` ';
        }
        
        $obj_result = mysqli_query($obj_db, $str_query);

        if ($obj_result !== false) {
            while ($arr_line = mysqli_fetch_array($obj_result, MYSQLI_ASSOC)) {
                if ($bln_return_ids) {
                    $arr_calendars[] = $arr_line['calendar_id'];
                } else {
                    if ($bln_include_dditems) {
                        // get calendar drag and drop items
                        $str_query2 = 'SELECT * FROM calendar_dditems WHERE calendar_id = ' . $arr_line['calendar_id'];
                        $obj_result2 = mysqli_query($obj_db, $str_query2);

                       $arr_dd_items = array();

                        while ($arr_line2 = mysqli_fetch_array($obj_result2, MYSQLI_ASSOC)) {
                              $arr_dd_items[] = $arr_line2;
                        }
                        $arr_line['dditems'] = $arr_dd_items;
                 }

                    // if active == 'period', check if period is now
                    if ($arr_line['active'] == 'period') {
                        if (strtotime($arr_line['cal_startdate'] . ' 00:00:01') < time() && (strtotime($arr_line['cal_enddate'] . ' 00:00:01') + 86400) > time()) {
                            $arr_line['active'] = 'yes';
                        } else {
                            $arr_line['active'] = 'no';
                        }
                    }
                    $arr_calendars[] = $arr_line;
                }
            }

//                    foreach($arr_calendars as &$cal) {
//                       
//                    }
        }

        return $arr_calendars;
    }

    /**
     * 
     * @global type $obj_db
     * @param type $int_user_id
     * @return type
     */
    public static function getCalendarsOfUser($int_user_id) {
        global $obj_db;
        $arr_calendars = array();

        $str_query = 'SELECT * FROM calendars WHERE `deleted` = 0 AND creator_id = ' . $int_user_id;

        $obj_result = mysqli_query($obj_db, $str_query);

        if ($obj_result !== false) {
            while ($arr_line = mysqli_fetch_array($obj_result, MYSQLI_ASSOC)) {

                $arr_calendars[] = $arr_line;
            }

            foreach ($arr_calendars as &$cal) {
                // get calendar drag and drop items
                $str_query = 'SELECT * FROM calendar_dditems WHERE calendar_id = ' . $cal['calendar_id'];
                $obj_result = mysqli_query($obj_db, $str_query);

                $arr_dd_items = array();

                while ($arr_line2 = mysqli_fetch_array($obj_result, MYSQLI_ASSOC)) {
                    $arr_dd_items[] = $arr_line2;
                }
                $cal['dditems'] = $arr_dd_items;
            }
        }

        return $arr_calendars;
    }

    /**
     * 
     * @param type $arr_calendars
     * @return type
     */
    public static function getDefaultCalendars($arr_calendars) {
        //if(User::isLoggedIn()) {
        //   	$arr_user 		= User::getUser();
        //}

        $str_return = '';
        foreach ($arr_calendars as $calendar) {
            if ($calendar['initial_show']) {
                if (!empty($str_return)) {
                    $str_return .= ',';
                }
                $str_return .= $calendar['calendar_id'];
            }
        }
        return $str_return;
    }

    /**
     * 
     * @global type $obj_db
     * @return type
     */
    public static function getCalendarId() {
        global $obj_db;
        $arr_calendars = array();

        $str_query = 'SELECT * FROM calendars WHERE `deleted` = 0';
        $obj_result = mysqli_query($obj_db, $str_query);

        while ($arr_line = mysqli_fetch_array($obj_result, MYSQLI_ASSOC)) {

            $arr_calendars[] = $arr_line;
        }
        if (isset($arr_calendars[0])) {
            return $arr_calendars[0]['calendar_id'];
        } else {
            return $arr_calendars['calendar_id'];
        }
    }

    /**
     * 
     * @global type $obj_db
     * @param type $user_id
     * @return type
     */
    public static function getCalendarsByUserId($user_id = -1, $bln_only_ids = false) {
        global $obj_db;
        $arr_calendars = array();
        $arr_calendar_ids = array();

        $str_query = 'SELECT e.*, c.*, c.calendar_id as c_calendar_id FROM events e ' .
                ' LEFT JOIN `calendars` c ON(e.calendar_id = c.calendar_id) ' .
                ' WHERE user_id = ' . $user_id . ' AND c.deleted = 0 AND c.calendar_id IS NOT NULL';

        $obj_result = mysqli_query($obj_db, $str_query);

        if ($obj_result !== false) {
            while ($arr_line = mysqli_fetch_array($obj_result, MYSQLI_ASSOC)) {

                if (!in_array($arr_line['calendar_id'], $arr_calendar_ids)) {
                    $arr_calendar_ids[] = $arr_line['calendar_id'];
                    $arr_calendars[] = $arr_line;
                }
            }
        }
        if ($bln_only_ids) {
            return $arr_calendar_ids;
        }
        return $arr_calendars;
    }

    public static function getCalendarIdByEventId($event_id = -1) {
        global $obj_db;

        if ($event_id > 0) {
            $str_query = 'SELECT calendar_id FROM events WHERE event_id = ' . $event_id;
            $obj_result = mysqli_query($obj_db, $str_query);

            $arr_event = mysqli_fetch_row($obj_result);

            if (!is_null($arr_event) && $arr_event !== false && is_array($arr_event) && !empty($arr_event)) {
                return $arr_event[0];
            }
        }

        return false;
    }

    /**
     * 
     * @global type $obj_db
     * @return type
     */
    public static function getCalendersOfAllUsers() {
        global $obj_db;
        $arr_calendars = array();
        $arr_calendar_ids = array();

        $str_query = 'SELECT * FROM `events` group by calendar_id';

        $obj_result = mysqli_query($obj_db, $str_query);

        if ($obj_result !== false) {
            while ($arr_line = mysqli_fetch_array($obj_result, MYSQLI_ASSOC)) {

                if (!in_array($arr_line['calendar_id'], $arr_calendar_ids)) {
                    $arr_calendar_ids[] = $arr_line['calendar_id'];
                    $arr_calendars[] = $arr_line;
                }
            }
        }

        return $arr_calendars;
    }

    // TODO continue with this function
    public static function convertDDitemStringToArray($str_dditems = '') {
        $arr_dditems = array();

        if (!empty($str_dditems)) {
            $arr_dditems = explode(',', $str_dditems);

            foreach ($arr_dditems as $dd) {
                $dd = trim($dd);
                $dd = trim($dd, ',');
                if (!empty($dd)) {
                    if (substr($dd, 0, 1) == '|') {
                        continue;
                    }
                    $color = '';
                    if (strstr($dd, '|')) {
                        // also color defined
                        $arr_dditem = explode('|', $dd);
                        $dditem_id = $arr_dditem[0];
                        $info = isset($arr_dditem[2]) ? $arr_dditem[2] : '';
                        $color = isset($arr_dditem[3]) ? $arr_dditem[3] : '';
                        $dd = $arr_dditem[1];

                        //$arr_return[] = array('dditem_id' => );
                    }
                }
            }
        }
    }

    public static function getHighestOrderId() {
        global $obj_db;
        
        $str_query = 'SELECT MAX(order_id) as highest FROM `calendars`';
        
        $obj_result = mysqli_query($obj_db, $str_query);
        
        if ($obj_result !== false) {
            $arr_line = mysqli_fetch_array($obj_result, MYSQLI_ASSOC);
            
            return $arr_line['highest'];
        }
    }
    
    public static function getHighestDDItemId() {
        global $obj_db;
        
        $str_query = 'SELECT MAX(dditem_id) as highest FROM `calendar_dditems`';
        
        $obj_result = mysqli_query($obj_db, $str_query);
        
        if ($obj_result !== false) {
            $arr_line = mysqli_fetch_array($obj_result, MYSQLI_ASSOC);
            
            return $arr_line['highest'];
        }
    }
    
    /**
     * 
     * @global type $obj_db
     * @param type $frm_submitted
     * @return type
     */
    public static function saveCalendar($frm_submitted) {
        global $obj_db;

        if (substr($frm_submitted['calendar_color'], 0, 1) != '#') {
            $frm_submitted['calendar_color'] = '#' . $frm_submitted['calendar_color'];
        }

        if (!isset($frm_submitted['locationfield']) || $frm_submitted['locationfield'] == 'text') {
            $frm_submitted['locations'] = '';
        }

        if ($frm_submitted['active'] == 'period') {
            // reformat the dates to mysql
            $arr_startdate = explode('/', $frm_submitted['cal_startdate']);
            $arr_enddate = explode('/', $frm_submitted['cal_enddate']);

            if (substr(DATEPICKER_DATEFORMAT, 0, 2) == 'mm') {
                $period_startdate = $arr_startdate[2] . '-' . $arr_startdate[0] . '-' . $arr_startdate[1];
                $period_enddate = $arr_enddate[2] . '-' . $arr_enddate[0] . '-' . $arr_enddate[1];
            } else {
                $period_startdate = $arr_startdate[2] . '-' . $arr_startdate[1] . '-' . $arr_startdate[0];
                $period_enddate = $arr_enddate[2] . '-' . $arr_enddate[1] . '-' . $arr_enddate[0];
            }
        } else {
            $period_startdate = '';
            $period_enddate = '';
        }

        if (!empty($frm_submitted['alterable_startdate']) && !empty($frm_submitted['alterable_enddate'])) {
            // reformat the dates to mysql
            $arr_startdate = explode('/', $frm_submitted['alterable_startdate']);
            $arr_enddate = explode('/', $frm_submitted['alterable_enddate']);

            if (substr(DATEPICKER_DATEFORMAT, 0, 2) == 'mm') {
                $alterable_startdate = $arr_startdate[2] . '-' . $arr_startdate[0] . '-' . $arr_startdate[1];
                $alterable_enddate = $arr_enddate[2] . '-' . $arr_enddate[0] . '-' . $arr_enddate[1];
            } else {
                $alterable_startdate = $arr_startdate[2] . '-' . $arr_startdate[1] . '-' . $arr_startdate[0];
                $alterable_enddate = $arr_enddate[2] . '-' . $arr_enddate[1] . '-' . $arr_enddate[0];
            }
        }

        if ($frm_submitted['calendar_id'] > 0) {
            $str_query = 'UPDATE calendars SET `name` = "' . $frm_submitted['name'] . '", ' .
                    '`calendar_color` = "' . $frm_submitted['calendar_color'] . '", ' .
                    '`origin` = "' . $frm_submitted['origin'] . '", ' .
                    '`can_add` = ' . $frm_submitted['can_add'] . ', ' .
                    '`can_view` = ' . (isset($frm_submitted['share_type']) && $frm_submitted['share_type'] == 'private' ? 0 : (isset($frm_submitted['share_type']) && $frm_submitted['share_type'] == 'public' ? 1 : $frm_submitted['can_view'])) . ', ' .
                    '`can_edit` = ' . $frm_submitted['can_edit'] . ', ' .
                    '`can_delete` = ' . $frm_submitted['can_delete'] . ', ' .
                    '`can_change_color` = ' . $frm_submitted['can_change_color'] . ', ' .
                    '`can_dd_drag` = "' . $frm_submitted['can_dd_drag'] . '", ' .
                    '`calendar_admin_email` = "' . $frm_submitted['calendar_admin_email'] . '", ' .
                    '`initial_show` = ' . $frm_submitted['initial_show'] . ', ' .
                    '`users_can_email_event` = ' . $frm_submitted['users_can_email_event'] . ', ' .
                    '`all_event_mods_to_admin` = ' . $frm_submitted['all_event_mods_to_admin'] . ', ' .
                    '`active` = "' . $frm_submitted['active'] . '", ';

            if (!empty($period_startdate) && !empty($period_enddate)) {
                $str_query .= '`cal_startdate` = "' . $period_startdate . '", ' .
                        '`cal_enddate` = "' . $period_enddate . '", ';
            } else {
                $str_query .= '`cal_startdate` = NULL, ' .
                        '`cal_enddate` = NULL, ';
            }

            if (!empty($alterable_startdate) && !empty($alterable_enddate)) {
                $str_query .= '`alterable_startdate` = "' . $alterable_startdate . '", ' .
                        '`alterable_enddate` = "' . $alterable_enddate . '", ';
            } else {
                $str_query .= '`alterable_startdate` = NULL, ' .
                        '`alterable_enddate` = NULL, ';
            }

            if (isset($frm_submitted['share_type']) && $frm_submitted['share_type'] == 'private_group') {
                if ($frm_submitted['usergroup_id'] > 0) {
                    $str_query .= '`usergroup_id` = ' . $frm_submitted['usergroup_id'] . ', ';
                } else {
                    $frm_submitted['share_type'] = 'private';
                }
            } else {
                $str_query .= '`usergroup_id` = null, ';
            }
            $str_query .= ' `share_type` = "' . $frm_submitted['share_type'] . '"' .
                    ' WHERE `calendar_id` = ' . (int) $frm_submitted['calendar_id'];

            $res = mysqli_query($obj_db, $str_query);

            if ($frm_submitted['checkbox_use_color_for_all_events']) {
                self::setEventColor($frm_submitted['calendar_color'], $frm_submitted['calendar_id']);
            }
        } else {
            $arr_user = User::getUser();

            $highest_order_id = Calendar::getHighestOrderId() + 1;
            
            $str_query = 'INSERT INTO calendars SET `name` = "' . $frm_submitted['name'] . '", ' .
                    '`calendar_color` = "' . $frm_submitted['calendar_color'] . '", ' .
                    '`origin` = "' . $frm_submitted['origin'] . '", ' .
                    '`creator_id` = ' . $arr_user['user_id'] . ', ' .
                    '`can_add` = ' . $frm_submitted['can_add'] . ', ' .
                    '`can_edit` = ' . $frm_submitted['can_edit'] . ', ' .
                    '`can_delete` = ' . $frm_submitted['can_delete'] . ', ' .
                    '`can_view` = ' . $frm_submitted['can_view'] . ', ' .
                    '`can_change_color` = ' . $frm_submitted['can_change_color'] . ', ' .
                    '`can_dd_drag` = "' . $frm_submitted['can_dd_drag'] . '", ' .
                    '`calendar_admin_email` = "' . $frm_submitted['calendar_admin_email'] . '", ' .
                    '`initial_show` = ' . $frm_submitted['initial_show'] . ', ' .
                    '`users_can_email_event` = ' . $frm_submitted['users_can_email_event'] . ', ' .
                    '`all_event_mods_to_admin` = ' . $frm_submitted['all_event_mods_to_admin'] . ', ' .
                    '`order_id` = ' . $highest_order_id . ', ' .
                    '`active` = "' . $frm_submitted['active'] . '", ';

            if (!empty($period_startdate) && !empty($period_enddate)) {
                $str_query .= '`cal_startdate` = "' . $period_startdate . '", ' .
                        '`cal_enddate` = "' . $period_enddate . '", ';
            } else {
                $str_query .= '`cal_startdate` = NULL, ' .
                        '`cal_enddate` = NULL, ';
            }

            if (!empty($alterable_startdate) && !empty($alterable_enddate)) {
                $str_query .= '`alterable_startdate` = "' . $alterable_startdate . '", ' .
                        '`alterable_enddate` = "' . $alterable_enddate . '", ';
            } else {
                $str_query .= '`alterable_startdate` = NULL, ' .
                        '`alterable_enddate` = NULL, ';
            }

            $str_query .= '`share_type` = "' . $frm_submitted['share_type'] . '"';

            $res = mysqli_query($obj_db, $str_query);

            $frm_submitted['calendar_id'] = mysqli_insert_id($obj_db);
        }

        if ($frm_submitted['calendar_id'] > 0) {
            // save dditems
            if (!empty($frm_submitted['dditems'])) {



                $arr_dditems = explode(',', $frm_submitted['dditems']);

                $arr_done_items = array();

                foreach ($arr_dditems as $dd) {   // example $dd: id|title|info|#ABABAB
                    $dd = trim($dd);
                    $dd = trim($dd, ',');
                    if (!empty($dd)) {
                        $color = '';
                        $starttime = '';
                        $endtime = '';
                        if (strstr($dd, '|')) {
                            // also color defined
                            $arr_dditem = explode('|', $dd);
                            $dditem_id = $arr_dditem[0];
                            $title = $arr_dditem[1];
                            $info = isset($arr_dditem[2]) ? $arr_dditem[2] : '';
                            $starttime = isset($arr_dditem[3]) ? $arr_dditem[3] : '';
                            $endtime = isset($arr_dditem[4]) ? $arr_dditem[4] : '';
                            $night_shift = isset($arr_dditem[5]) && $arr_dditem[5] == 'true'  ? 1 : 0;
                            $color = isset($arr_dditem[6]) ? $arr_dditem[6] : '';
                            $allDay = 0;
                            if (empty($starttime) || empty($endtime)) {
                                $allDay = 1;
                                $starttime = '';
                                $endtime = '';
                            }
                        }

                        if ($dditem_id > 1000000) {
                            if (!empty($title)) {
                                // newly added
                                if (!in_array($dditem_id, $arr_done_items)) {
                                    // check if night shift
                                    
                                    $str_query3 = 'INSERT INTO calendar_dditems (`title`,`calendar_id`, `info`,`starttime`,`endtime`, `nightshift`, `allDay`, `color`) ' .
                                        'VALUES ("' . $title . '", ' . (int) $frm_submitted['calendar_id'] . ', "' . $info . '", "' . $starttime . '", "' . $endtime . '", ' .$night_shift. ', '. $allDay . ', "' . $color . '") ';

                                    $res3 = mysqli_query($obj_db, $str_query3);
                                    
                                    $arr_done_items[] = $dditem_id;
                                }
                                
                            }
                        } else {
                            if (empty($title)) {
                                // delete dditem
                                $str_query2 = 'DELETE FROM calendar_dditems ' .
                                        'WHERE `dditem_id` = ' . $dditem_id;

                                $res = mysqli_query($obj_db, $str_query2);
                            } else {
                                if (!in_array($dditem_id, $arr_done_items)) {
                                    // doubles are extracted through the table index (calendar_id, title)
                                    $str_query = 'UPDATE calendar_dditems SET `title` = "' . $title . '",' .
                                            ' `info` = "' . $info . '",' .
                                            ' `starttime` = "' . $starttime . '",' .
                                            ' `endtime` = "' . $endtime . '",' .
                                            ' `nightshift` = "' . $night_shift . '",' .
                                            ' `allDay` = "' . $allDay . '",' .
                                            ' `color` = "' . $color . '" ' .
                                            ' WHERE `dditem_id` = ' . $dditem_id;

                                    $arr_done_items[] = $dditem_id;

                                    $res2 = mysqli_query($obj_db, $str_query);
                                }
                            }
                        }
                    }
                }
            }

            // save locations
            if (!empty($frm_submitted['locations'])) {
                // delete locations
                $str_query3 = 'DELETE FROM calendar_locations ' .
                        'WHERE `calendar_id` = ' . (int) $frm_submitted['calendar_id'];

                $res = mysqli_query($obj_db, $str_query3);

                $arr_locations = explode(',', $frm_submitted['locations']);

                foreach ($arr_locations as $loc) {
                    $loc = trim($loc);
                    $loc = trim($loc, ',');
                    if (!empty($loc)) {
                        if (substr($loc, 0, 1) == '|') {
                            // no title given, do not save
                            continue;
                        }
                        $color = '';
                        if (strstr($loc, '|')) {
                            // also color defined
                            $arr_location = explode('|', $loc);
                            $info = $arr_location[1];
                            $color = $arr_location[2];
                            $loc = $arr_location[0];
                        }
                        $str_query = 'REPLACE INTO calendar_locations (`name`, `calendar_id`) ' .
                                'VALUES ("' . $loc . '", ' . (int) $frm_submitted['calendar_id'] . ') ';

                        $res2 = mysqli_query($obj_db, $str_query);
                    }
                }
            }

            if ($frm_submitted['origin'] == 'exchange') {
                require_once LIB_DIR . '/encryption.class.php';

                $key = sha1($frm_submitted['name']);

                if (!strstr($frm_submitted['exchange_username'], '.....')) {
                    $encrypted_username = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $frm_submitted['exchange_username'], MCRYPT_MODE_CBC, md5($key)));

                    CalendarOption::saveOption('exchange_username', $encrypted_username, '', $frm_submitted['calendar_id']);
                }

                if ($frm_submitted['exchange_password'] == '12345678910') {
                    // password wasn't changed
                } else {
                    $encrypted_password = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $frm_submitted['exchange_password'], MCRYPT_MODE_CBC, md5($key)));

                    CalendarOption::saveOption('exchange_password', $encrypted_password, '', $frm_submitted['calendar_id']);
                }

                CalendarOption::saveOption('exchange_extra_secure', $frm_submitted['exchange_extra_secure'], '', $frm_submitted['calendar_id']);

                if ($frm_submitted['exchange_extra_secure']) {
                    if ($frm_submitted['exchange_token'] == '12345678910') {
                        // token wasn't changed
                    } else {
                        CalendarOption::saveOption('exchange_token', sha1(md5($frm_submitted['calendar_id'] . $frm_submitted['exchange_token'])), '', $frm_submitted['calendar_id']);
                    }
                }
            }

            $arr_options = array('description_field_required' => $frm_submitted['description_field_required'],
                'location_field_required' => $frm_submitted['location_field_required'],
                'phone_field_required' => $frm_submitted['phone_field_required'],
                'url_field_required' => $frm_submitted['url_field_required'],
                'show_description_field' => $frm_submitted['show_description_field'],
                'show_location_field' => $frm_submitted['show_location_field'],
                'show_phone_field' => $frm_submitted['show_phone_field'],
                'show_team_member_field' => $frm_submitted['show_team_member_field'],
                'notify_assign_teammember' => $frm_submitted['notify_assign_teammember'],
                'show_dropdown_1_field' => $frm_submitted['show_dropdown_1_field'],
                'show_dropdown_2_field' => $frm_submitted['show_dropdown_2_field'],
                'add_team_member_to_title' => $frm_submitted['add_team_member_to_title'],
                'add_custom_dropdown1_to_title' => $frm_submitted['add_custom_dropdown1_to_title'],
                'add_custom_dropdown2_to_title' => $frm_submitted['add_custom_dropdown2_to_title'],
                'show_url_field' => $frm_submitted['show_url_field'],
                'next_days_visible' => $frm_submitted['next_days_visible'],
                'usergroup_dditems_viewtype' => $frm_submitted['usergroup_dditems_viewtype']);

            CalendarOption::saveOptions($arr_options, '', $frm_submitted['calendar_id']);


            if ($frm_submitted['dditems_usergroup_id'] > 0) {
                CalendarOption::saveOption('dditems_usergroup_id', $frm_submitted['dditems_usergroup_id'], '', $frm_submitted['calendar_id']);
                CalendarOption::saveOption('assign_dditem_to_user', $frm_submitted['assign_dditem_to_user'], '', $frm_submitted['calendar_id']);
            }
            CalendarOption::saveOption('mail_assigned_event_to_user', $frm_submitted['mail_assigned_event_to_user'], '', $frm_submitted['calendar_id']);
        }

        // reset the session
        if (isset($_SESSION['ews_calendars']) && isset($_SESSION['ews_calendars'][$frm_submitted['calendar_id']])) {
            unset($_SESSION['ews_calendars'][$frm_submitted['calendar_id']]);
        }
        return $res;
    }

    /**
     * 
     * @global type $obj_db
     * @param type $str_color
     * @param type $int_calendar_id
     * @return boolean
     */
    private static function setEventColor($str_color, $int_calendar_id) {
        global $obj_db;

        if ($int_calendar_id > 0) {
            if (User::isLoggedIn() && (User::isAdmin() || User::isSuperAdmin())) {

                $str_query = 'UPDATE events SET `color` = "' . $str_color . '" ' .
                        ' WHERE calendar_id = ' . $int_calendar_id;

                $obj_result = mysqli_query($obj_db, $str_query);

                return $obj_result;
            }
        }
        return false;
    }

    /**
     * 
     * @global type $obj_db
     * @param type $int_calendar_id
     * @return type
     */
    public static function deleteCalendar($int_calendar_id) {
        global $obj_db;

        $str_query = 'UPDATE calendars SET `deleted` = 1 WHERE `calendar_id` = ' . $int_calendar_id;

        $res = mysqli_query($obj_db, $str_query);

        return $res;
    }

    /**
     * 
     * @global type $obj_db
     * @param type $int_calendar_id
     * @return boolean
     */
    public static function undeleteCalendar($int_calendar_id) {
        global $obj_db;

        $str_query = 'UPDATE calendars SET `deleted` = 0 WHERE `calendar_id` = ' . $int_calendar_id;

        $obj_result = mysqli_query($obj_db, $str_query);

        if ($obj_result !== false) {
            return true;
        }
        return false;
    }

//    public static function getDialogSettings($int_cal_id) {
//        $arr_cal = Calendar::getCalendar($int_cal_id);
//        
//        $description_required   = (bool) $arr_cal['description_field_required'];
//        $location_required   = (bool) $arr_cal['location_field_required'];
//        $phone_required   = (bool) $arr_cal['phone_field_required'];
//        $url_required = (bool) $arr_cal['url_field_required'];
//        
//        return array('description_field_required' => (bool) $arr_cal['description_field_required'], 
//                    'location_field_required' => (bool) $arr_cal['location_field_required'],
//                    'phone_field_required' => (bool) $arr_cal['phone_field_required'],
//                    'url_field_required' => (bool) $arr_cal['url_field_required']);
//    }

    /**
     * 
     * @param type $int_cal_id
     * @return type
     */
    public static function getPermissions($int_cal_id) {
        $arr_cal = Calendar::getCalendar($int_cal_id);

        $can_view = isset($arr_cal['can_view']) ? (bool) $arr_cal['can_view'] : false; // others can view
        $can_add = isset($arr_cal['can_add']) ? (bool) $arr_cal['can_add'] : false;
        $can_edit = isset($arr_cal['can_edit']) ? (bool) $arr_cal['can_edit'] : false;
        $can_delete = isset($arr_cal['can_delete']) ? (bool) $arr_cal['can_delete'] : false;
        $can_change_color = isset($arr_cal['can_change_color']) ? (bool) $arr_cal['can_change_color'] : false;
        $can_see_dditems = $can_add && !ONLY_ADMIN_CAN_SEE_DRAG_DROP_ITEMS; // only_owner , only_loggedin_users of everyone
        $only_viewable = false;

        if (isset($arr_cal['origin']) && $arr_cal['origin'] == 'exchange') {
            // for now exchange items can only be viewed
            return array('can_view' => true,
                'can_add' => false,
                'can_edit' => false,
                'can_delete' => false,
                'can_change_color' => false,
                'can_see_dditems' => false);
        }

        /*
         * IF LOGGED IN
         */

        if (User::isLoggedIn()) {
            $arr_user = User::getUser();

            if (ONLY_ADMIN_CAN_SEE_DRAG_DROP_ITEMS) {
                if (User::isAdmin() || User::isSuperAdmin()) {
                    $can_see_dditems = true;
                } else {
                    $can_see_dditems = false;
                }
            } else {
                if (isset($arr_cal['calendar_id']) && Calendar::isOwner($arr_cal['calendar_id']) || (isset($arr_cal['can_dd_drag']) && $arr_cal['can_dd_drag'] == 'everyone') || (isset($arr_cal['can_dd_drag']) && $arr_cal['can_dd_drag'] == 'only_loggedin_users')) {
                    $can_see_dditems = true;
                } else {
                    if (isset($arr_cal['can_dd_drag']) && $arr_cal['can_dd_drag'] == 'only_owner' && !Calendar::isOwner($arr_cal['calendar_id'])) {
                        $can_see_dditems = false;
                    }
                }
            }

            // if admin with fullcontrol OR calendar owner (creator)
            if ((ADMIN_HAS_FULL_CONTROL && (User::isAdmin() || User::isSuperAdmin())) || (isset($arr_cal['calendar_id']) && Calendar::isOwner($arr_cal['calendar_id']))) {
                $can_view = false; // not neccesary because admin can edit
                $can_add = true;
                $can_edit = true;
                $can_delete = true;
                $can_see_dditems = true;
            } else if (isset($arr_cal['share_type']) && $arr_cal['share_type'] == 'private_group') {

                if (!Calendar::UserInGroup($arr_cal, $arr_user['user_id'])) {
                    // if share_type is private_group and user is not in the group
                    $can_add = false;
                    $can_edit = false;
                    $can_delete = false;
                    $can_see_dditems = false;
                } else {
//                    $can_add            = true;
//                    $can_edit           = true;
//                    $can_delete         = true;
//                    $can_see_dditems    = true;
                }
            }
        } else {
            /*
             * IF NOT LOGGED IN
             */

            if (ONLY_ADMIN_CAN_SEE_DRAG_DROP_ITEMS) {
                $can_see_dditems = false;
            } else if ($arr_cal['can_dd_drag'] == 'everyone') {
                $can_see_dditems = true;
            } else {
                $can_see_dditems = false;
            }

            // if public
            if ($arr_cal['share_type'] == 'public') {
                // use the defaults from the calendar
            }

            // if access allowed by IP and IP mathces with IP in config.php
            if (ALLOW_ACCESS_BY == 'ip' && User::ipAllowed()) {
                // use the defaults from the calendar
            }

            // if private group calendar but public can view
            if ($arr_cal['share_type'] == 'private_group' && $arr_cal['can_view'] == 1) {
                $can_view = true;
                $can_add = false;
                $can_edit = false;
                $can_delete = false;
                $can_change_color = false;
                $can_see_dditems = false;
                $only_viewable = true;
            }
        }

        return array('can_view' => $can_view,
            'can_add' => $can_add,
            'can_edit' => $can_edit,
            'can_delete' => $can_delete,
            'can_change_color' => $can_change_color,
            'can_see_dditems' => $can_see_dditems,
            'only_viewable' => $only_viewable);
    }

    /**
     * 
     * @global type $obj_db
     * @param type $cal_id
     * @return type
     */
    public static function isOwner($cal_id) {
        if (User::isLoggedIn()) {

            $arr_user = User::getUser();

            global $obj_db;

            $arr_calendar = array();

            if ($cal_id > 0) {
                $str_query = 'SELECT calendar_id FROM calendars WHERE calendar_id = ' . $cal_id . ' AND `creator_id` = ' . $arr_user['user_id'];
                $obj_result = mysqli_query($obj_db, $str_query);

                $arr_calendar = mysqli_fetch_row($obj_result);
            }

            return !empty($arr_calendar);
        }
    }

    /**
     * 
     * @global type $obj_db
     * @param type $cal_id
     * @return type
     */
    public static function getColor($cal_id) {
        global $obj_db;

        $str_query = 'SELECT calendar_color FROM calendars WHERE calendar_id = ' . $cal_id;
        $obj_result = mysqli_query($obj_db, $str_query);

        $arr_calendar = mysqli_fetch_row($obj_result);

        return $arr_calendar[0];
    }

    /**
     * 
     * @global type $obj_db
     * @param type $frm_submitted
     * @return boolean
     */
    public static function updateCalendar($frm_submitted) {
        global $obj_db;

        $str_query = 'UPDATE calendars SET ' . ($frm_submitted['bln_color_future_events'] ? 'events_color = "' . $frm_submitted['color'] . '",' : '') . ' calendar_color = "' . $frm_submitted['color'] . '", name = "' . $frm_submitted['title'] . '" WHERE calendar_id = ' . $frm_submitted['cal_id'];
        $obj_result = mysqli_query($obj_db, $str_query);

        if ($obj_result !== false) {

            return true;
        }
        return false;
    }

    /**
     * 
     * @global type $obj_db
     * @return type
     */
    public static function hasOneCalendar() {
        global $obj_db;

        $str_query = 'SELECT * FROM calendars WHERE `deleted` = 0';
        $obj_result = mysqli_query($obj_db, $str_query);

        $arr_calendars = array();

        while ($arr_line = mysqli_fetch_array($obj_result, MYSQLI_ASSOC)) {
            $arr_calendars[] = $arr_line;
        }

        return count($arr_calendars) == 1;
    }

    /**
     * 
     * @global type $obj_db
     * @return type
     */
    public static function noCalendarsCreated() {
        global $obj_db;

        $str_query = 'SELECT * FROM calendars WHERE `deleted` = 0';
        $obj_result = mysqli_query($obj_db, $str_query);

        if ($obj_result !== false) {
            $arr_calendars = array();

            while ($arr_line = mysqli_fetch_array($obj_result, MYSQLI_ASSOC)) {
                $arr_calendars[] = $arr_line;
            }

            return count($arr_calendars) == 0;
        } else {
            echo 'something wrong with the calendars table, check the structure';
            exit;
        }
    }

}

?>