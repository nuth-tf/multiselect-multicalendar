<?php

class Lists {

    private $cal_id;

    function __construct($cal_id) {
        $this->cal_id = $cal_id;
    }

    public static function getList($frm_submitted) {
        global $obj_db;

        if (!User::isLoggedIn()) {
            return array();
        }
        $arr_user = User::getUser();

        $arr_list = array();
        $calendar_id = $frm_submitted['cid'];
        $user_id = $frm_submitted['uid'];

        // admin
        // $arr_user 		= User::getUser();
        // use the settings of the creator of the calendar
        // getCreatorId
        if (!empty($calendar_id) && $calendar_id !== 'all') {
            $arr_calendar = Calendar::getCalendar($calendar_id);

            $int_creator_id = $arr_calendar['creator_id'];

            $default_period = Settings::getSetting('hourcalculation_default_period', $int_creator_id);
            $workday_hours = Settings::getSetting('hourcalculation_workday_hours', $int_creator_id);
        } else {
            if (defined('HOURCALCULATION_WORKDAY_HOURS')) {
                $workday_hours = HOURCALCULATION_WORKDAY_HOURS;
            } else {
                $workday_hours = 8;
            }


            if (defined('HOURCALCULATION_DEFAULT_PERIOD')) {
                $default_period = HOURCALCULATION_DEFAULT_PERIOD;
            } else {
                $default_period = 6;
            }
        }


        if (!is_numeric($default_period) || $default_period < 0 || $default_period > 100) {
            $default_period = 6;
        }
        if ($workday_hours < 0 || $workday_hours > 24) {
            $workday_hours = 8;
        }


        $period_startdate = date('Y-m-d', strtotime('-' . $default_period . ' MONTHS'));
        $period_enddate = date('Y-m-d');

        if (!empty($frm_submitted['st'])) {
            $arr_startdate = explode('/', $frm_submitted['st']);
            $arr_enddate = explode('/', $frm_submitted['end']);

            if (substr(DATEPICKER_DATEFORMAT, 0, 2) == 'mm') {
                $period_startdate = $arr_startdate[2] . '-' . $arr_startdate[0] . '-' . $arr_startdate[1];
                $period_enddate = $arr_enddate[2] . '-' . $arr_enddate[0] . '-' . $arr_enddate[1];
            } else {
                $period_startdate = $arr_startdate[2] . '-' . $arr_startdate[1] . '-' . $arr_startdate[0];
                $period_enddate = $arr_enddate[2] . '-' . $arr_enddate[1] . '-' . $arr_enddate[0];
            }
        }

        $total_day_count = 0;
        $total_hour_count = 0;

        // find how many days

        $str_query = 'SELECT * FROM events e LEFT JOIN `calendars` c ON(c.calendar_id = e.calendar_id) ';

        $str_query .= ' WHERE user_id = ' . $user_id . ' AND c.deleted = 0' .
                ' AND date_start >= "' . $period_startdate . '" AND date_end <= "' . $period_enddate . '"';


        if ($calendar_id == 'all' || empty($calendar_id)) {
            // get calendars the admin is allowed to see
            $arr_calendar_ids = Calendar::getCalendarsOfAdmin($arr_user['user_id'], false, false, true);

            $str_query .= ' AND e.`calendar_id` IN (' . implode(',', $arr_calendar_ids) . ')';
        } else if ($calendar_id > 0) {
            $str_query .= ' AND e.`calendar_id` = ' . $calendar_id;
        }

        $str_query .= ' ORDER BY `date_start`';
        
        $obj_result = mysqli_query($obj_db, $str_query);

        if ($obj_result !== false) {
            while ($arr_line = mysqli_fetch_array($obj_result, MYSQLI_ASSOC)) {
                $cnt_days = 0;
                $cnt_hours = 0;

                if ($arr_line['date_start'] == $arr_line['date_end']) {
                    // oneday event
                    if ($arr_line['allDay']) {
                        $cnt_hours += $workday_hours;
                    } else {

                        $cnt_hours += (strtotime($arr_line['date_end'] . ' ' . $arr_line['time_end']) - strtotime($arr_line['date_start'] . ' ' . $arr_line['time_start'])) / 3600;
                    }
                    $cnt_days ++;
                } else if (strtotime(date('Y-m-d') . ' ' . $arr_line['time_start']) > strtotime(date('Y-m-d') . ' ' . $arr_line['time_end'])) {
                    // night shift

                    if (strtotime($arr_line['date_end']) - strtotime($arr_line['date_start']) > 86400) {
                        // moreday event
                        $days_in_between = Utils::getDaysBetween($arr_line['date_start'], $arr_line['date_end']);

                        foreach ($days_in_between as $key => $nightshift_event_date) {
                            if ($key == 0) {
                                $evening = $nightshift_event_date;
                                continue;
                            }
                            $cnt_hours += (strtotime($evening . ' 23:59:59') - strtotime($evening . ' ' . $arr_line['time_start'])) / 3600;
                            $cnt_hours += (strtotime($nightshift_event_date . ' ' . $arr_line['time_end']) - strtotime($nightshift_event_date . ' 00:00:01')) / 3600;

                            $evening = $nightshift_event_date;

                            $cnt_days ++;
                        }
                    } else {
                        // 1 night
                        $cnt_hours += (strtotime($arr_line['date_start'] . ' 23:59:59') - strtotime($arr_line['date_start'] . ' ' . $arr_line['time_start'])) / 3600;
                        $cnt_hours += (strtotime($arr_line['date_end'] . ' ' . $arr_line['time_end']) - strtotime($arr_line['date_end'] . ' 00:00:01')) / 3600;

                        $cnt_days ++;
                    }
                } else {
                    // moredays event
                    $days_in_between = Utils::getDaysBetween($arr_line['date_start'], $arr_line['date_end']);

                    foreach ($days_in_between as $event_date) {
                        if ($arr_line['allDay']) {
                            $cnt_hours += $workday_hours;
                        } else {
                            // ignore the nights
                            $cnt_hours += (strtotime($event_date . ' ' . $arr_line['time_end']) - strtotime($event_date . ' ' . $arr_line['time_start'])) / 3600;

                            // else 
                            //$cnt_hours += (strtotime($arr_line['date_end'].' '.$arr_line['time_end']) - strtotime($arr_line['date_start'].' '.$arr_line['time_start'])) / 3600;
                        }

                        $cnt_days ++;
                    }
                }

                $total_day_count += $cnt_days;
                $total_hour_count += $cnt_hours;

                $arr_line['days'] = $cnt_days;
                $arr_line['hours'] = round($cnt_hours, 2);

                $arr_list[] = $arr_line;
            }
        }

        $arr_startdate_tmp = explode('-', $period_startdate);
        $arr_enddate_tmp = explode('-', $period_enddate);

        if (substr(DATEPICKER_DATEFORMAT, 0, 2) == 'mm') {
            $arr_startdate_in_correct_format = $arr_startdate_tmp[1] . '/' . $arr_startdate_tmp[2] . '/' . $arr_startdate_tmp[0];
            $arr_enddate_in_correct_format = $arr_enddate_tmp[1] . '/' . $arr_enddate_tmp[2] . '/' . $arr_enddate_tmp[0];
        } else {
            $arr_startdate_in_correct_format = $arr_startdate_tmp[2] . '/' . $arr_startdate_tmp[1] . '/' . $arr_startdate_tmp[0];
            $arr_enddate_in_correct_format = $arr_enddate_tmp[2] . '/' . $arr_enddate_tmp[1] . '/' . $arr_enddate_tmp[0];
        }

        return array('list' => $arr_list,
            'total_day_count' => $total_day_count,
            'total_hour_count' => $total_hour_count,
            'startdate' => $arr_startdate_in_correct_format,
            'enddate' => $arr_enddate_in_correct_format);
    }

    public static function getLists($frm_submitted) {
        global $obj_db;

        if (!User::isLoggedIn()) {
            return array();
        }
        $arr_user = User::getUser();

        $default_period = -1;
        $workday_hours = -1;

        $calendar_id = $frm_submitted['cid'];


        $default_period = Settings::getSetting('hourcalculation_default_period', $arr_user['user_id']);
        $workday_hours = Settings::getSetting('hourcalculation_workday_hours', $arr_user['user_id']);

        if (!is_numeric($default_period) || $default_period < 0 || $default_period > 100) {
            $default_period = 6;
        }
        if ($workday_hours < 0 || $workday_hours > 24) {
            $workday_hours = 8;
        }


        $arr_users = array();
        $arr_list = array();

        if ($workday_hours < 0) {
            if (!defined('HOURCALCULATION_WORKDAY_HOURS') || HOURCALCULATION_WORKDAY_HOURS < 0 || HOURCALCULATION_WORKDAY_HOURS > 24) {
                define('HOURCALCULATION_WORKDAY_HOURS', 8);
            }
            $workday_hours = HOURCALCULATION_WORKDAY_HOURS;
            if ($workday_hours < 0 || $workday_hours > 24) {
                $workday_hours = 8;
            }
        }
        if ($default_period < 0) {
            if (!defined('HOURCALCULATION_DEFAULT_PERIOD')) {
                define('HOURCALCULATION_DEFAULT_PERIOD', 6);
            }
            $default_period = HOURCALCULATION_DEFAULT_PERIOD;
            if (!is_numeric($default_period) || $default_period < 0 || $default_period > 100) {
                $default_period = 6;
            }
        }


        $period_startdate = date('Y-m-d', strtotime('-' . $default_period . ' MONTHS'));
        $period_enddate = date('Y-m-d');

        if (!empty($frm_submitted['st'])) {
            $arr_startdate = explode('/', $frm_submitted['st']);
            $arr_enddate = explode('/', $frm_submitted['end']);

            if (substr(DATEPICKER_DATEFORMAT, 0, 2) == 'mm') {
                $period_startdate = $arr_startdate[2] . '-' . $arr_startdate[0] . '-' . $arr_startdate[1];
                $period_enddate = $arr_enddate[2] . '-' . $arr_enddate[0] . '-' . $arr_enddate[1];
            } else {
                $period_startdate = $arr_startdate[2] . '-' . $arr_startdate[1] . '-' . $arr_startdate[0];
                $period_enddate = $arr_enddate[2] . '-' . $arr_enddate[1] . '-' . $arr_enddate[0];
            }
        }

        $str_query = '';

        // get users that the admin is allowed to see
        $str_query = 'SELECT user_id, title, active, concat_ws(" ",firstname,infix,lastname) as fullname FROM users WHERE `usertype` = "user" AND `deleted` = 0 AND admin_group = ' . $arr_user['user_id'];
//echo $str_query;


        if (!empty($str_query)) {
            $obj_result = mysqli_query($obj_db, $str_query);

            if ($obj_result !== false) {
                while ($arr_line = mysqli_fetch_array($obj_result, MYSQLI_ASSOC)) {
                    $arr_users[] = $arr_line;
                }
            }
        }
        // get groups of admin
        $arr_admin_groups = Group::getMyGroups(true);

        foreach ($arr_admin_groups as $int_group_id) {
            // get users of groups
            $arr_users = array_merge($arr_users, Group::getGroupUsers($int_group_id));
        }

        $arr_unduplicated_users = array();
        $arr_done_users = array();

        foreach ($arr_users as $user) {
            if (!in_array($user['user_id'], $arr_done_users)) {
                $arr_unduplicated_users[] = $user;
            }
            $arr_done_users[] = $user['user_id'];
        }

        foreach ($arr_unduplicated_users as &$user) {

            $cnt_days = 0;
            $cnt_hours = 0;

            // find how many days
            $str_query = 'SELECT * FROM events WHERE user_id = ' . $user['user_id'] .
                    ' AND date_start >= "' . $period_startdate . '" AND date_end <= "' . $period_enddate . '"';

            if ($calendar_id == 'all') {
                // get calendars the admin is allowed to see
                $arr_calendar_ids = Calendar::getCalendarsOfAdmin($arr_user['user_id'], false, false, true);

                $str_query .= ' AND `calendar_id` IN (' . implode(',', $arr_calendar_ids) . ')';
            } else if ($calendar_id > 0) {
                $str_query .= ' AND `calendar_id` = ' . $calendar_id;
            }
//echo $str_query;
            $obj_result = mysqli_query($obj_db, $str_query);

            if ($obj_result->num_rows > 0) {
                if ($obj_result !== false) {

                    while ($arr_line = mysqli_fetch_array($obj_result, MYSQLI_ASSOC)) {

                        if ($arr_line['date_start'] == $arr_line['date_end']) {
                            // oneday event
                            if ($arr_line['allDay']) {
                                $cnt_hours += $workday_hours;
                            } else {

                                $cnt_hours += (strtotime($arr_line['date_end'] . ' ' . $arr_line['time_end']) - strtotime($arr_line['date_start'] . ' ' . $arr_line['time_start'])) / 3600;
                            }
                            $cnt_days ++;
                        } else if (strtotime(date('Y-m-d') . ' ' . $arr_line['time_start']) > strtotime(date('Y-m-d') . ' ' . $arr_line['time_end'])) {
                            // night shift

                            if (strtotime($arr_line['date_end']) - strtotime($arr_line['date_start']) > 86400) {
                                // moreday event
                                $days_in_between = Utils::getDaysBetween($arr_line['date_start'], $arr_line['date_end']);

                                foreach ($days_in_between as $key => $nightshift_event_date) {
                                    if ($key == 0) {
                                        $evening = $nightshift_event_date;
                                        continue;
                                    }
                                    $cnt_hours += (strtotime($evening . ' 23:59:59') - strtotime($evening . ' ' . $arr_line['time_start'])) / 3600;
                                    $cnt_hours += (strtotime($nightshift_event_date . ' ' . $arr_line['time_end']) - strtotime($nightshift_event_date . ' 00:00:01')) / 3600;

                                    $evening = $nightshift_event_date;

                                    $cnt_days ++;
                                }
                            } else {
                                // 1 night
                                $cnt_hours += (strtotime($arr_line['date_start'] . ' 23:59:59') - strtotime($arr_line['date_start'] . ' ' . $arr_line['time_start'])) / 3600;
                                $cnt_hours += (strtotime($arr_line['date_end'] . ' ' . $arr_line['time_end']) - strtotime($arr_line['date_end'] . ' 00:00:01')) / 3600;

                                $cnt_days ++;
                            }
                        } else {
                            // moredays event
                            $days_in_between = Utils::getDaysBetween($arr_line['date_start'], $arr_line['date_end']);

                            foreach ($days_in_between as $event_date) {
                                if ($arr_line['allDay']) {
                                    $cnt_hours += $workday_hours;
                                } else {
                                    $cnt_hours += (strtotime($event_date . ' ' . $arr_line['time_end']) - strtotime($event_date . ' ' . $arr_line['time_start'])) / 3600;

                                    // else 
                                    //$cnt_hours += (strtotime($arr_line['date_end'].' '.$arr_line['time_end']) - strtotime($arr_line['date_start'].' '.$arr_line['time_start'])) / 3600;
                                }

                                $cnt_days ++;
                            }
                        }
                    }

                    $user['days'] = $cnt_days;
                    $user['hours'] = round($cnt_hours, 2);
                }
                //return $arr_users;
            }
        }


        $arr_startdate_tmp = explode('-', $period_startdate);
        $arr_enddate_tmp = explode('-', $period_enddate);

        if (substr(DATEPICKER_DATEFORMAT, 0, 2) == 'mm') {
            $arr_startdate_in_correct_format = $arr_startdate_tmp[1] . '/' . $arr_startdate_tmp[2] . '/' . $arr_startdate_tmp[0];
            $arr_enddate_in_correct_format = $arr_enddate_tmp[1] . '/' . $arr_enddate_tmp[2] . '/' . $arr_enddate_tmp[0];
        } else {
            $arr_startdate_in_correct_format = $arr_startdate_tmp[2] . '/' . $arr_startdate_tmp[1] . '/' . $arr_startdate_tmp[0];
            $arr_enddate_in_correct_format = $arr_enddate_tmp[2] . '/' . $arr_enddate_tmp[1] . '/' . $arr_enddate_tmp[0];
        }

        return array('users' => $arr_unduplicated_users,
            'startdate' => $arr_startdate_in_correct_format,
            'enddate' => $arr_enddate_in_correct_format);
        //return array();
    }

}

?>