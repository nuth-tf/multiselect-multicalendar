<?php

/*
 * Created on 17-okt-2014
 * author Paul Wolbers
 */

require_once 'include/default.inc.php';

//global $obj_db;
//mysqli_set_charset( $obj_db , 'utf8' );

if (!isset($_GET['action']) || (isset($_GET['action']) && ($_GET['action'] !== 'mobile_add' && $_GET['action'] !== 'mobile_addstoredtitles'))) {
    if (ALLOW_ACCESS_BY == 'ip') {
        if (defined('CAL_IP')) {
            if (($_SERVER['REMOTE_ADDR'] !== CAL_IP && $_SERVER['REMOTE_ADDR'] !== '127.0.0.1')) {
                header('location: ' . FULLCAL_URL . '/noaccess.html');  // fill in a website where you want to redirect
                exit;
            }
        } else {
            echo 'Check your config.php! When ALLOW_ACCESS_BY = ip, you have to define CAL_IP also';
            exit;
        }
    }
}

if (defined('SAVE_CURRENT_EDITING') && SAVE_CURRENT_EDITING) {
    if (!isset($_GET['action'])) {
        Events::stopEditAfterCalendarRefresh();
    }
    
}

global $current_languages;

$arr_settings = Settings::getSettings();

$bln_found = false;
foreach ($current_languages as $code => $lang) {
    if (strtoupper($arr_settings['language']) == $code) {
        $bln_found = true;
        $obj_smarty->assign('language', $code);
    }
}
if (!$bln_found) {
    if (!file_exists(FULLCAL_URL . '/script/lang' . strtoupper($arr_settings['language']) . '.js')) {
        $obj_smarty->assign('language', 'EN');
    }
}

if (isset($_SESSION['ews_imported_users'])) {
    $_SESSION['ews_imported_users'] = array();
}

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'login':
            login();
            break;
        case 'logoff':
            logoff();
            break;
        case 'search':
            search();
            break;
        case 'get_tag':
            return getTag();
            break;
        case 'mobileadd':
            mobileAdd();
            break;
        case 'mobile_addstoredtitles':
            mobileAddStoredTitles();
            break;
        case 'admin_register':
            adminRegister();
            break;
        case 'activate':
            activate();
            break;
        case 'reset_password':
            resetPassword();
            break;
        case 'change_password':
            changePassword();
            break;
        case 'add_user':
            addUser();
            break;
        case 'get_profile':
            getProfile();
            break;
        case 'save_profile':
            saveProfile();
            break;
        case 'get_settings':
            getSettings();
            break;
        case 'save_settings':
            saveSettings();
            break;
        case 'get_current_event':
            return Events::getCurrentEvent(true);
            break;
        case 'new':
            newCalendarItem();
            break;
        case 'upload':
            upload();
            break;
        case 'get_files':
            getFiles();
            break;
        case 'remove_file':
            removeFile();
            break;
        case 'save_token':
            saveToken();
            break;
        case 'clearsession':
            unset($_SESSION['ews_calendars']);
            break;
        case 'create_pdf':
            createPdf();
            break;
        case 'agenda':
            showPeriodListView();
            break;
        default:
            die('no such action available');
    }

    exit;
} else {

    $arr_calendars = Calendar::getCalendars();

    // check if there is at least 1 calendar present
    // if(empty($arr_calendars) || !isset($arr_calendars[0])) {
    if (Calendar::noCalendarsCreated()) {
        Calendar::insertFirstCalendar();
        $arr_calendars = Calendar::getCalendars();
    }

    $str_initial_calendars = Calendar::getDefaultCalendars($arr_calendars);

    $bln_no_initial_calendars = false;
    if(empty($str_initial_calendars)) {
        $str_first_calendar_id = $arr_calendars[0]['calendar_id'];
    }
    
    $bln_initial_cal_show_dropdown1 = false;
    $bln_initial_cal_show_dropdown2 = false;
    
    $arr_user = User::getUser();

    $cnt_public = 0;
    $cnt_private = 0;
    $arr_movable_to = array();
    foreach ($arr_calendars as $def) {
        $bln_public = false;
        if ($def['share_type'] == 'public') {
            $cnt_public ++;
            $bln_public = true;
        }
        if ($def['share_type'] == 'private' || $def['share_type'] == 'private_group') {
            $cnt_private ++;
        }
        $bln_owner = false;
        $bln_in_group = false;

        if (!is_null($arr_user)) {
            if ($def['creator_id'] == $arr_user['user_id']) {
                // owner
                $bln_owner = true;
            }
            if (Calendar::UserInGroup($def, $arr_user['user_id'])) {
                $bln_in_group = true;
            }
        }

        if (User::isAdmin() || $bln_owner || $bln_in_group || $bln_public) {    // || $def['can_add']
            $arr_movable_to[] = $def;
        }
        
//        if((bool) $def['initial_show'] || (empty($str_initial_calendars) && $str_first_calendar_id == $def['calendar_id'])) {
//            $bln_cal_show_dropdown1 = (bool) CalendarOption::getOption('show_dropdown_1_field', $def['calendar_id'], false);
//            $bln_cal_show_dropdown2 = (bool) CalendarOption::getOption('show_dropdown_2_field', $def['calendar_id'], false);
//            
//            if($bln_cal_show_dropdown1) {
                $bln_initial_cal_show_dropdown1 = true;
//            }
//            if($bln_cal_show_dropdown2) {
                $bln_initial_cal_show_dropdown2 = true;
//            }
//        }
    }

    if($bln_initial_cal_show_dropdown1) {
       $obj_smarty->assign('bln_initial_cal_show_dropdown1', true); 
       
       
    }
    if($bln_initial_cal_show_dropdown2) {
       $obj_smarty->assign('bln_initial_cal_show_dropdown2', true); 
       
    }
  
    $obj_smarty->assign('dropdown2_label', CustomFields::getDropdown2Label()); 
    $obj_smarty->assign('dropdown1_label', CustomFields::getDropdown1Label()); 
         
    $obj_smarty->assign('show_custom_dropdown1_filter', Settings::getSetting('show_custom_dropdown1_filter') == 'on');    // from settings
    $obj_smarty->assign('show_custom_dropdown2_filter', Settings::getSetting('show_custom_dropdown2_filter') == 'on');    // from settings
    
   // $obj_smarty->assign('current_dropdown_options', true); 
    
    $obj_smarty->assign('dropdown1', CustomFields::getCustomDropdown1());
    $obj_smarty->assign('dropdown2', CustomFields::getCustomDropdown2());
    
            
    $obj_smarty->assign('cnt_public', $cnt_public);
    $obj_smarty->assign('cnt_private', $cnt_private);

    if(!is_null($arr_user) && User::isAdmin()) {
        $obj_smarty->assign('show_users_filter', Settings::getSetting('show_user_filter', $arr_user['user_id']) == 'on');
    }
    
    
    $first_default_calendar = array();

    if (isset($arr_calendars[0])) {
        $obj_smarty->assign('default_calendar_color', $arr_calendars[0]['calendar_color']);

        $first_default_calendar = $arr_calendars[0];

        $arr_permissions = Calendar::getPermissions($first_default_calendar['calendar_id']);
    } else {
        $obj_smarty->assign('default_calendar_color', '#3366CC');

        $arr_permissions = array('can_edit' => false,
            'can_delete' => false,
            'can_see_dditems' => false,
            'can_add' => false);
    }

    $obj_smarty->assign('my_active_calendars', $arr_calendars);
    
    $obj_smarty->assign('movable_to', $arr_movable_to);

    if (!empty($str_initial_calendars)) {
        // one or more calendars have initial_show set to true
        $obj_smarty->assign('default_calendars', $str_initial_calendars);

        if (!strstr($str_initial_calendars, ',')) {
            // 1 initial calendar
            $arr_cal = Calendar::getCalendar($str_initial_calendars);
            $obj_smarty->assign('default_calendar_color', $arr_cal['calendar_color']);
        } else {
            
        }
    } else {
        if (!empty($first_default_calendar)) {
            // no calendars have initial_show set to true, so use first calendar as default
            $first_default_calendar['initial_show'] = true;
            $obj_smarty->assign('default_calendars', $first_default_calendar['calendar_id']);
            $obj_smarty->assign('default_calendar_color', $first_default_calendar['calendar_color']);
        }
    }

    $obj_smarty->assign('default_calendar', $first_default_calendar);

    $arr_cal = $first_default_calendar;

    if (!empty($arr_cal)) {
        if (User::isLoggedIn()) {
            $arr_cal['isOwner'] = Calendar::isOwner($arr_cal['calendar_id']);
            $obj_smarty->assign('isOwner', $arr_cal['isOwner']);
        }

        $arr_users = User::getUsers(true);
        
        $obj_smarty->assign('users', $arr_users);
        
          
        $obj_smarty->assign('cal_can_edit', $arr_permissions['can_edit']);
        $obj_smarty->assign('cal_can_delete', $arr_permissions['can_delete']);
        $obj_smarty->assign('can_drag_dd_items', $arr_permissions['can_see_dditems']);
        $obj_smarty->assign('cal_can_change_color', (bool) $arr_cal['can_change_color']);
        $obj_smarty->assign('cal_alterable_startdate', $arr_cal['alterable_startdate']);
        $obj_smarty->assign('cal_alterable_enddate', $arr_cal['alterable_enddate']);
        $obj_smarty->assign('cal_can_mail', Calendar::calCanMail($arr_cal));
        $obj_smarty->assign('only_viewable', $arr_permissions['only_viewable']);
        $obj_smarty->assign('usergroup_dditems_viewtype', $arr_cal['usergroup_dditems_viewtype']);

        $obj_smarty->assign('arr_calendar', $arr_cal);
    
        
    }

            
    // determine how many intitial show 
    $arr_default_calendars = explode(',', $str_initial_calendars);
    if (count($arr_default_calendars) > 1) {
        $obj_smarty->assign('cal_can_add', false);
    } else {
        $obj_smarty->assign('cal_can_add', $arr_permissions['can_add']);
    }

    //$obj_smarty->display(FULLCAL_DIR.'/view/cal.html');
    //exit;
}


Schedule::run();

global $current_languages;

$arr_submit = array(
    array('sq', 'string', false, ''),
    array('sd', 'string', false, ''),
    array('ft', 'string', false, ''),
    array('cid', 'string', false, ''),
);

$frm_submitted_initial = validate_var($arr_submit);

if (!empty($frm_submitted_initial['sq'])) {
    if (!empty($frm_submitted_initial['sq'])) {
        $q = $frm_submitted_initial['sq'];
    }
    $obj_smarty->assign('sq', $q);
}

if (!empty($frm_submitted_initial['sd']) && !empty($frm_submitted_initial['ft'])) {

    $_SESSION['employee-work-schedule-sd'] = $frm_submitted_initial['sd'];
    $_SESSION['employee-work-schedule-ft'] = $frm_submitted_initial['ft'];

    $arr_goto_date = explode('-', $frm_submitted_initial['sd']);

    $month = $arr_goto_date[1] - 1;
    if ($month == -1) {
        $month = 11;
    }
    $obj_smarty->assign('gotoYear', $arr_goto_date[0]);
    $obj_smarty->assign('gotoMonth', $month);
    $obj_smarty->assign('gotoDay', $arr_goto_date[2]);


    if (!empty($frm_submitted_initial['cid'])) {
        if ($frm_submitted_initial['cid'] == 'all') {
            //$arr_calendars = Calendar::getCalendars();    // already available

            $cal_ids = array();

            foreach ($arr_calendars as $cal) {
                $cal_ids[] = $cal['calendar_id'];
            }
            $obj_smarty->assign('default_calendars', 'all'); //implode(',', $cal_ids));
        } else {
            $obj_smarty->assign('default_calendars', $frm_submitted_initial['cid']);
        }
    }
} else {

    //  $arr_calendars = Calendar::getCalendars();    // already available

    $cal_ids = array();

    foreach ($arr_calendars as $cal) {
        $cal_ids[] = $cal['calendar_id'];
    }

    if (!empty($frm_submitted_initial['cid']) && in_array($frm_submitted_initial['cid'], $cal_ids)) {
        $obj_smarty->assign('default_calendars', $frm_submitted_initial['cid']);
    }
}
if (!User::isLoggedIn() && isset($_SESSION['calendar-uid'])) {
    unset($_SESSION['calendar-uid']);
}
if (User::isLoggedIn()) {
    header("Cache-Control: no-cache, must-revalidate");

    $arr_user = User::getUser();

    if(defined('SUBSCRIPTIONS')) {
        if(SUBSCRIPTIONS === true) {
            $bln_trial = User::isTrial($arr_user['user_id']);
  
            $arr_subscription = Subscription::getSubscriptionByUserId($arr_user['user_id']);

            if(!empty($arr_subscription) && isset($arr_subscription['enddate'])) {
                if($bln_trial) {
                    // get enddate
                    $obj_smarty->assign('trial_expire_date', date('d-m-Y', strtotime($arr_subscription['enddate'])));

                } else {
                    // if 1 month left show message
                    if(strtotime('- 1 MONTH', strtotime($arr_subscription['enddate'])) < time()) {
                        $obj_smarty->assign('subscription_expire_date', date('d-m-Y', strtotime($arr_subscription['enddate'])));
                    }

                }
            }
            $obj_smarty->assign('is_trial', $bln_trial);
    
        }
    }
    
    
    
    $obj_smarty->assign('title', $arr_user['title']);
    $obj_smarty->assign('name', $arr_user['firstname'] . ' ' . (!empty($arr_user['infix']) ? $arr_user['infix'] : '') . $arr_user['lastname']);
    $obj_smarty->assign('user', $_SESSION['calendar-uid']['username']);
    $obj_smarty->assign('user_id', $arr_user['user_id']);
    $obj_smarty->assign('is_admin', User::isAdmin());
    $obj_smarty->assign('is_super_admin', User::isSuperAdmin());


    $arr_settings = Settings::getSettings($arr_user['user_id']);

    $bln_found = false;
    foreach ($current_languages as $code => $lang) {
        if (strtoupper($arr_settings['language']) == $code) {
            $bln_found = true;
        }
    }
    if (!$bln_found) {
        if (!file_exists(FULLCAL_URL . '/script/lang' . strtoupper($arr_settings['language']) . '.js')) {
            $arr_settings['language'] = "EN";
        }
    }


    $obj_smarty->assign('settings', $arr_settings);

    // determine how many intitial show 
    $arr_default_calendars = explode(',', $str_initial_calendars);
    if (count($arr_default_calendars) > 1) {
        $obj_smarty->assign('cal_can_add', false);
        $obj_smarty->assign('is_owner', false);
    } else {
        $obj_smarty->assign('cal_can_add', true);

        if (isset($arr_calendars[0])) {
            $obj_smarty->assign('is_owner', Calendar::isOwner($arr_calendars[0]['calendar_id']));
        } else {
            $obj_smarty->assign('is_owner', false);
        }
    }

//	$obj_smarty->assign('cal_can_edit', true);
//	$obj_smarty->assign('cal_can_delete', true);

    $obj_smarty->assign('cal_can_view', false);

    $obj_smarty->display(FULLCAL_DIR . '/view/cal.html');
} else if (ALLOW_ACCESS_BY == 'login') {
    $obj_smarty->display(FULLCAL_DIR . '/login.html');
} else {

    if (ADMIN_CAN_LOGIN_FROM_ADMIN_URL === true && ALLOW_ACCESS_BY == 'free' && !stristr($_SERVER['SCRIPT_NAME'], '/admin') && SHOW_SMALL_LOGIN_LINK === false) {
//		unset($_SESSION['calendar-uid']);
    }

    $obj_smarty->assign('is_admin', false);
    $obj_smarty->assign('is_super_admin', false);
    $obj_smarty->assign('is_owner', false);

    $arr_settings = Settings::getSettings();

    $bln_found = false;
    foreach ($current_languages as $code => $lang) {
        if (strtoupper($arr_settings['language']) == $code) {
            $bln_found = true;
        }
    }
    if (!$bln_found) {
        if (!file_exists(FULLCAL_URL . '/script/lang' . strtoupper($arr_settings['language']) . '.js')) {
            $arr_settings['language'] = "EN";
        }
    }

    $obj_smarty->assign('settings', $arr_settings);

    //print_r($arr_settings);exit; 
    if (!isset($_SESSION['calendar-uid'])) {
        if (defined('IP_AND_FREE_ACCESS_SAVED_USER_ID') && IP_AND_FREE_ACCESS_SAVED_USER_ID > 0) {
            $_SESSION['calendar-uid']['uid'] = IP_AND_FREE_ACCESS_SAVED_USER_ID;
        } else {
            $_SESSION['calendar-uid']['uid'] = 1000000;
        }
    }

    $obj_smarty->assign('user_id', $_SESSION['calendar-uid']['uid']);

    $obj_smarty->display(FULLCAL_DIR . '/view/cal.html');
}



exit;

function logoff() {
    unset($_SESSION['calendar-uid']);
    unset($_SESSION['ews_exchange_private_token']);

    header('location: ' . FULLCAL_URL);
    exit;
}

function login() {

    if ((defined('ALLOW_ACCESS_BY') && ALLOW_ACCESS_BY == 'login') || (ALLOW_ACCESS_BY == 'free' && ADMIN_CAN_LOGIN_FROM_ADMIN_URL)) {
        global $error;
        global $obj_smarty;

        $arr_submit = array(
            array('passw', 'string', true, ''),
            array('usern', 'string', true, ''),
        );

        $frm_submitted = validate_var($arr_submit);

        if (SHOW_SMALL_LOGIN_LINK && !isset($frm_submitted['passw']) && !isset($frm_submitted['usern'])) {
            $obj_smarty->display(FULLCAL_DIR . '/login.html');
            exit;
        }

        if (!$error) {
            $msg = User::login($frm_submitted);
        }

        if (!empty($msg)) {

            $obj_smarty->assign('user', '');
            $obj_smarty->assign('msg', $msg);
            $obj_smarty->display(FULLCAL_DIR . '/login.html');
        } else {
            //$obj_smarty->display(CAL_DIR.'/view/cal.html');
            header('location:' . FULLCAL_URL);
        }
    } else {
        header('location:' . FULLCAL_URL);
    }
    exit;
}

function addUser() {
    User::checkLoggedIn();

    global $error;

    $arr_submit = array(
        array('title', 'string', false, ''),
        array('firstname', 'string', false, ''),
        array('infix', 'string', false, ''),
        array('lastname', 'string', true, ''),
        array('username', 'string', false, ''),
        array('email', 'email', true, ''),
        array('copy_to_admin', 'bool', false, false),
    );

    $frm_submitted = validate_var($arr_submit);

    if (!$error) {
        $mixed_success = User::addUser($frm_submitted);

        if (is_string($mixed_success)) {
            echo json_encode(array('success' => false, 'error' => $mixed_success));
            exit;
        } else {
            if ($mixed_success['insert'] === false) {
                echo json_encode(array('success' => false, 'error' => 'Failure while inserting the user'));
                exit;
            } else {
                $password = '';
                if (defined('SHOW_CREATED_PASSWORD_WHEN_ADMIN_ADDS_USER') && SHOW_CREATED_PASSWORD_WHEN_ADMIN_ADDS_USER) {
                    if (isset($mixed_success['password'])) {
                        $password = $mixed_success['password'];
                    }
                }
                if ($mixed_success['mail'] == 'notsend') {
                    echo json_encode(array('success' => false, 'password' => $password, 'error' => 'User inserted succesfully, failure while sending email'));
                    exit;
                } else {
                    echo json_encode(array('success' => true, 'password' => $password, 'error' => 'User inserted succesfully and email send successfully'));
                    exit;
                }
            }

            echo json_encode(array('success' => $mixed_success));
            exit;
        }
    }

    echo json_encode(array('success' => false, 'error' => $error));
    exit;
}

function getProfile() {
    User::checkLoggedIn();

    $arr_user = User::getUserById($_SESSION['calendar-uid']['uid']);

    $arr_birthdate = explode('-', $arr_user['birth_date']);

    $arr_user['birthdate_month'] = $arr_birthdate[1];
    $arr_user['birthdate_day'] = $arr_birthdate[2];
    $arr_user['birthdate_year'] = $arr_birthdate[0];

    unset($arr_user['password']);
    unset($arr_user['birth_date']);

    echo json_encode(array('success' => true, 'profile' => $arr_user));
    exit;
}

function saveProfile() {
    User::checkLoggedIn();

    global $error;

    $arr_submit = array(
        array('title', 'string', false, ''),
        array('firstname', 'string', false, ''),
        array('infix', 'string', false, ''),
        array('lastname', 'string', true, ''),
        array('country', 'string', false, ''),
        array('username', 'string', false, ''),
        array('email', 'email', true, ''),
        array('birthdate_day', 'int', false, ''),
        array('birthdate_month', 'int', false, ''),
        array('birthdate_year', 'int', false, ''),
        array('password', 'string', false, ''),
        array('confirm', 'string', false, ''),
    );

    $frm_submitted = validate_var($arr_submit);

    if (!$error) {
        $bln_success = User::saveProfile($frm_submitted);

        if (!empty($frm_submitted['password']) && !empty($frm_submitted['confirm'])) {
            if ($frm_submitted['password'] === $frm_submitted['confirm']) {

                $frm_submitted['passw1'] = $frm_submitted['password'];
                $frm_submitted['uid'] = $_SESSION['calendar-uid']['uid'];

                $bln_success = User::changePassword($frm_submitted);
                // TODO ? naar inlogpagina ?
            } else {
                echo json_encode(array('success' => false, 'error' => 'Passwords do not match'));
                exit;
            }
        }
    }

    echo json_encode(array('success' => $bln_success));
    exit;
}

function search() {
    global $error;
    global $obj_smarty;
    global $obj_db;

    $arr_return = array();

    $arr_submit = array(
        array('sq', 'string', true, ''),
        array('cal_id', 'string', true, ''),
    );

    $frm_submitted = validate_var($arr_submit);

    if (!empty($frm_submitted)) {
        if (isset($_SESSION['calendar-uid']) && $_SESSION['calendar-uid']['uid']) {
            $user_id = $_SESSION['calendar-uid']['uid'];
        } else {
            $user_id = 0;
        }

        $arr_calendars = array();

        if (!empty($frm_submitted['cal_id'])) {
            $arr_calendars = Calendar::getCalendars($frm_submitted['cal_id']);
        }

        $arr_days = array(1 => 'Monday', 2 => 'Tuesday', 3 => 'Wednesday', 4 => 'Thursday', 5 => 'Friday', 6 => 'Saturday', 7 => 'Sunday');
        $arr_events = array();

        if (!empty($arr_calendars)) {
            foreach ($arr_calendars as $calendar) {
                $str_query = 'SELECT e.*, re.*, event_id as id, concat_ws(" ",date_start,time_start) as start,concat_ws(" ",date_end,time_end) as end FROM events e' .
                        ' LEFT JOIN `repeating_events` re ON(re.rep_event_id = e.repeating_event_id)  WHERE (`title` LIKE  "%' . $frm_submitted['sq'] . '%" OR `description` LIKE  "%' . $frm_submitted['sq'] . '%" OR `location` LIKE  "%' . $frm_submitted['sq'] . '%")' .
                        ($user_id > 0 && $calendar['share_type'] != "public" && ALLOW_ACCESS_BY !== 'free' && !Calendar::UserInGroup($calendar, $user_id) ? ' AND user_id = ' . $user_id : '');


                $str_query .= ' AND calendar_id = ' . $calendar['calendar_id'];

                $str_query .= ' ORDER BY date_start';

                $obj_result = mysqli_query($obj_db, $str_query);

                while ($arr_line = mysqli_fetch_array($obj_result, MYSQLI_ASSOC)) {
                    $arr_events[] = $arr_line;
                }
            }
        }


        foreach ($arr_events as $event) {

            $arr_weekdays = explode(',', $event['weekdays']);
            $str_weekdays = '';
            $str_monthdays = '';

            if ($event['rep_interval'] == 'W') {
                foreach ($arr_weekdays as $day) {
                    if (!empty($day)) {
                        $str_weekdays .= $arr_days[$day] . ', ';
                    }
                }
            }

            $event['weekdays'] = rtrim($str_weekdays, ', ');

            if ($event['rep_interval'] == 'M') {
                if ($event['monthday'] == 'dom') {
                    $str_monthdays = date('d', strtotime($event['date_start'] . ' 12:00:00'));
                } else if ($event['monthday'] == 'dow') {
                    $str_monthdays = $arr_days[date('w', strtotime($event['date_start'] . ' 12:00:00'))];
                }
            }

            $event['monthdays'] = rtrim($str_monthdays, ', ');

            $arr_return[] = $event;
        }
        $str_events = '';



        //	$_SESSION['employee-work-schedule-sq'] = $frm_submitted['sq'];
    } else {
        $arr_return = array();
        $frm_submitted['sq'] = '';
    }


    $obj_smarty->assign('results', $arr_return);
    $obj_smarty->assign('q', $frm_submitted['sq']);
    if (isset($frm_submitted['cal_id'])) {
        $obj_smarty->assign('cal_id', $frm_submitted['cal_id']);
    }


    $obj_smarty->display(FULLCAL_DIR . '/view/search_results.html');
}

function resetPassword() {
    if (defined('ALLOW_ACCESS_BY') && ALLOW_ACCESS_BY == 'login') {

        global $error;
        $use_captcha = true;

        $arr_submit = array(
            array('uid', 'int', true, ''),
            array('hash', 'string', true, ''),
        );

        $frm_submitted = validate_var($arr_submit);

        if (!$error) {
            $bln_success = User::activate($frm_submitted, true);
        }
    } else {
        header('location:' . FULLCAL_URL);
        exit;
    }
}

function changePassword() {
    if (defined('ALLOW_ACCESS_BY') && (ALLOW_ACCESS_BY == 'login' || ALLOW_ACCESS_BY == 'free')) {
        global $error;
        $use_captcha = true;

        $arr_submit = array(
            array('passw1', 'string', true, ''),
            array('passw2', 'string', true, ''),
            array('uid', 'int', true, ''),
        );

        $frm_submitted = validate_var($arr_submit);

        if (!$error) {
            $bln_success = User::changePassword($frm_submitted);
        }

        if ($bln_success) {
            header('location:' . FULLCAL_URL);
            exit;
        }
    } else {
        header('location:' . FULLCAL_URL);
        exit;
    }
}

function activate() {
    global $error;
    $use_captcha = true;

    $arr_submit = array(
        array('uid', 'int', true, ''),
        array('hash', 'string', true, ''),
    );

    $frm_submitted = validate_var($arr_submit);

    if (!$error) {
        $bln_success = User::activate($frm_submitted);
    }
}

function adminRegister() {
    if (User::isLoggedIn() && (User::isAdmin() || User::isSuperAdmin())) {

        global $error;
        $use_captcha = true;
        global $obj_smarty;
        $bln_success = false;

        $arr_submit = array(
            array('title', 'string', false, ''),
            array('lastname', 'string', true, ''),
            array('password', 'string', true, ''),
            array('username', 'string', true, ''),
            array('email', 'string', true, '')
        );

        $frm_submitted = validate_var($arr_submit);

        if (!$error) {

            global $obj_db;
            $arr_user = null;

            // check if username does not exist
            $str_query = 'SELECT * FROM `users` ' .
                    ' WHERE `username` = "' . $frm_submitted['username'] . '"';

            $res1 = mysqli_query($obj_db, $str_query);

            if ($res1 !== false) {
                $arr_user = mysqli_fetch_array($res1, MYSQLI_ASSOC);
            }

            if (!is_null($arr_user) && !empty($res1)) {
                echo 'Username already exists';
            } else {
                // check mailaddress
                $str_query = 'SELECT * FROM `users` ' .
                        ' WHERE `email` = "' . $frm_submitted['email'] . '"';

                $res2 = mysqli_query($obj_db, $str_query);

                if ($res2 !== false) {
                    $arr_user2 = mysqli_fetch_array($res2, MYSQLI_ASSOC);
                }
                if (!is_null($arr_user2) && !empty($res2)) {
                    echo 'Email already exists';
                } else {
                    $bln_success = User::adminRegister($frm_submitted, true);
                }
                if ($bln_success === false) {
                    echo 'Admin must be logged in';
                }
            }
        } else {
            echo $error;
        }

        if ($bln_success) {
            echo 'User inserted successfully';
        }
    } else {
        echo 'No admin is logged in or you have no rights to do this';
    }
}

function getSettings() {
    global $obj_smarty;

    $arr_user = User::getUser();

    echo json_encode(array('success' => true,
        'user_id' => $arr_user['user_id'],
        'settings' => Settings::getSettings($arr_user['user_id'])
    ));
    exit;
}

function saveSettings() {
    global $error;
    User::checkLoggedIn();

    global $error;

    $arr_submit = array(
        array('language', 'string', false, ''),
        array('other_language', 'string', false, ''),
        array('default_view', 'string', false, ''),
        array('timezone', 'string', false, ''),
        array('user_id', 'int', true, ''),
    );

    $frm_submitted = validate_var($arr_submit);

    if (!$error) {

        $arr_user = User::getUser();

        if ($frm_submitted['user_id'] == $arr_user['user_id']) {
            unset($frm_submitted['user_id']);

            Settings::saveSettings($frm_submitted, '', $arr_user['user_id']);
        } else {
            echo json_encode(array('success' => false, 'error' => 'NO rights to do that'));
            exit;
        }
    } else {
        echo json_encode(array('success' => false, 'error' => $error));
        exit;
    }

    echo json_encode(array('success' => true));
    exit;

    //$obj_smarty->assign('active', 'settings');
    //$obj_smarty->assign('settings', Settings::getSettings($arr_user['user_id']));
    //$obj_smarty->display(FULLCAL_DIR.'/view/user_panel.tpl');
    //exit;
}

function getFiles() {
    $arr_submit = array(
        array('event_id', 'int', true, ''),
    );

    $frm_submitted = validate_var($arr_submit);

    $arr_files = Events::getFiles($frm_submitted['event_id']);

    echo json_encode(array('success' => true, 'files' => $arr_files));
    exit;
}

function removeFile() {
    $arr_submit = array(
        array('event_id', 'int', true, ''),
        array('event_file_id', 'int', true, ''),
    );

    $frm_submitted = validate_var($arr_submit);

    Events::removeFile($frm_submitted['event_id'], $frm_submitted['event_file_id']);

    $arr_files = Events::getFiles($frm_submitted['event_id']);

    echo json_encode(array('success' => true, 'files' => $arr_files));
    exit;
}

function saveToken() {
    global $error;

    $arr_submit = array(
        array('cal_id', 'int', true, ''),
        array('exchange_token', 'string', true, ''),
    );

    $frm_submitted = validate_var($arr_submit);

    if (!$error) {
        $_SESSION['ews_exchange_private_token'] = sha1(md5($frm_submitted['cal_id'] . $frm_submitted['exchange_token']));

        echo json_encode(array('success' => true));
        exit;
    } else {
        echo json_encode(array('success' => false, 'error' => $error));
        exit;
    }
}

function upload() {

    $arr_submit = array(
        array('upload_event_id', 'int', true, ''),
    );

    $frm_submitted = validate_var($arr_submit);

    $file_error = '';
    switch ($_FILES['file']['error']) {
        case 0: //no error; 

            break;
        case 1: //uploaded file exceeds the upload_max_filesize directive in php.ini
            $file_error = 'TOO_BIG';   //"The file you are trying to upload is too big.";
            break;
        case 2: //uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the html form
            $file_error = 'TOO_BIG';   //"The file you are trying to upload is too big.";
            break;
        case 3: //uploaded file was only partially uploaded
            $file_error = 'PARTIALLY_UPLOADED';   //"The file you are trying upload was only partially uploaded.";
            break;
        case 4: //no image file was uploaded
            $file_error = 'NO_FILE_SELECTED';   //"You must select a file for upload.";
            break;
        default: //a default error, just in case!  :)
            $file_error = 'PROBLEM_WITH_UPLOAD';   //"There was a problem with your upload.";
            break;
    }

    if (!empty($file_error)) {
        echo json_encode(array('success' => false, 'error' => $file_error));
        exit;
    }

    // get extension
    $arr_type = explode('/', $_FILES['file']['type']);

    $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);   // for doc, docx and other Office extensions

    $arr_file['extension'] = $ext;  //$arr_type[1];
    $arr_file['orig_filename'] = $_FILES['file']['name'];
    $arr_file['type'] = $_FILES['file']['type'];
    $arr_file['filename'] = sha1_file($_FILES['file']['tmp_name']);
    $arr_file['size'] = $_FILES['file']['size'];
    $arr_file['event_id'] = $frm_submitted['upload_event_id'];

    if ($arr_file['extension'] == 'jpeg') {
        $arr_file['extension'] = 'jpg';
    }
    if ($arr_file['extension'] == 'x-log') {
        $arr_file['extension'] = 'log';
    }
    //rejects all .exe, .com, .bat, .zip, .doc and .txt files
    if (preg_match('/\\.(exe|com|bat|zip|apk|js|jsp)$/i', $arr_file['orig_filename'])) {
        echo json_encode(array('success' => false, 'error' => 'FILE_NOT_ALLOWED'));
        exit;
    }

    if ((int) $arr_file['size'] <= 5000000) {
        if (move_uploaded_file($_FILES['file']['tmp_name'], FULLCAL_DIR . '/uploads/' . $arr_file['filename'] . '.' . $arr_file['extension'])) {
            // save the new file in the event_files table
            $bln_success = Events::insertUploadedFile($arr_file);

            if ($bln_success) {
                $arr_file = Events::getEventFile($frm_submitted['upload_event_id'], $arr_file['filename']);
                $cnt_files = Events::getCntFiles($frm_submitted['upload_event_id']);

                echo json_encode(array('success' => true, 'file' => $arr_file, 'cnt_files' => $cnt_files));
                exit;
            } else {
                echo json_encode(array('success' => false, 'error' => 'File is already uploaded'));
                exit;
            }
        }
    } else {
        echo json_encode(array('success' => false, 'error' => 'The file is too big'));
        exit;
    }
}

function newCalendarItem() {
    global $obj_smarty;

    // $obj_smarty->assign('results', $arr_return);
    //$obj_smarty->assign('q', $frm_submitted['sq']);


    $obj_smarty->display(FULLCAL_DIR . '/view/new.tpl');
}

function FancyTable($header, $data) {
    //Colors, line width and bold font
    $pdf->SetFillColor(255, 0, 0);
    $pdf->SetTextColor(255);
    $pdf->SetDrawColor(128, 0, 0);
    $pdf->SetLineWidth(.3);
    $pdf->SetFont('', 'B');
    //Header
    $w = array(40, 35, 40, 45);
    for ($i = 0; $i < count($header); $i++)
        $pdf->Cell($w[$i], 7, $header[$i], 1, 0, 'C', 1);
    $pdf->Ln();
    //Color and font restoration
    $pdf->SetFillColor(224, 235, 255);
    $pdf->SetTextColor(0);
    $pdf->SetFont('');
    //Data
    $fill = 0;
    foreach ($data as $row) {
        $pdf->Cell($w[0], 6, $row[0], 'LR', 0, 'L', $fill);
        $pdf->Cell($w[1], 6, $row[1], 'LR', 0, 'L', $fill);
        $pdf->Cell($w[2], 6, number_format($row[2]), 'LR', 0, 'R', $fill);
        $pdf->Cell($w[3], 6, number_format($row[3]), 'LR', 0, 'R', $fill);
        $pdf->Ln();
        $fill = !$fill;
    }
    $pdf->Cell(array_sum($w), 0, '', 'T');
}

function includeTableHeader($pdf, $header, $w) {
    //Colors, line width and bold font
    $pdf->SetFillColor(0, 0, 0);        // red: 255,0,0     blue: 0,0,255
    $pdf->SetTextColor(255);
    $pdf->SetDrawColor(0, 0, 0);      // red/brown: 128,0,0
    $pdf->SetLineWidth(.3);
    $pdf->SetFont('', 'B');

    $pdf->Ln();
    $pdf->Ln();
    $pdf->Ln();

    //Header
    for ($i = 0; $i < count($header); $i++) {
        $pdf->Cell($w[$i], 7, $header[$i], 1, 0, 'C', 1);
    }
    $pdf->Ln();
//$pdf->Ln();
    //Color and font restoration
    $pdf->SetFillColor(230);        // lightblue : 224,235,255    gray: 230
    $pdf->SetTextColor(0);
    $pdf->SetFont('');

    return $pdf;
}

function startNewPage($pdf, $header, $w, $table_look, $logo, $show_logo) {

    $pdf->AddPage();
    $pdf->setXY(0, 0);
    if ($show_logo && !empty($logo) && file_exists($logo)) {
        $pdf->Image($logo, 160, 10);
    }
    if ($show_logo) {
        $pdf->Ln();
        $pdf->Ln();
        $pdf->Ln();
        $pdf->Ln();
    }

    // $pdf->Ln();

    if ($table_look) {
        $pdf = includeTableHeader($pdf, $header, $w);
        //$pdf->Ln();
    } else {
        $pdf->Ln();
        $pdf->Ln();
    }
}

function createPdf() {
    global $error;

    $arr_submit = array(
        array('start', 'string', true, ''),
        array('end', 'string', true, ''),
        array('cal_id', 'string', true, ''),
        array('df', 'string', false, 'd-m-y'),
        array('m', 'string', false, ''),
        array('cv', 'string', false, ''),
    );

    $frm_submitted = validate_var($arr_submit);

    $int_user_id = 1000000;

    if (User::isLoggedIn()) {
        $arr_user = User::getUser();

        $int_user_id = $arr_user['user_id'];
    }

    if (strstr($frm_submitted['cal_id'], ',')) {
        $arr_calendar_ids = explode(',', $frm_submitted['cal_id']);
    } else if ($frm_submitted['cal_id'] == 'all') {
        $arr_calendar_ids = Calendar::getCalendars(false, false, true); //getCalendarsByUserId($int_user_id, true)
    } else {
        $arr_calendar_ids = array($frm_submitted['cal_id']);

        $arr_my_calendars = Calendar::getCalendarsICanSee($int_user_id);

        if (!in_array($frm_submitted['cal_id'], $arr_my_calendars)) {
            die('You have no rights to export this calendar');
        }
    }


    // get settings of the logged in user
    // because every user can change pdf settings in settings section in dashboard
    $arr_user_settings = Settings::getSettings($int_user_id);

    $int_cal_id = $arr_calendar_ids[0];
    $arr_calendar = Calendar::getCalendar($int_cal_id);

    if(!empty($arr_calendar)) {
        $int_admin_id = $arr_calendar['creator_id'];

        $arr_settings = Settings::getSettings($int_admin_id);
    }
    
        
    if (isset($arr_user_settings['pdf_table_look']) && !empty($arr_user_settings['pdf_table_look'])) {
        $arr_settings['pdf_table_look'] = $arr_user_settings['pdf_table_look'];
    }

    $table_look = isset($arr_settings['pdf_table_look']) && $arr_settings['pdf_table_look'] == 'on' ? true : false;
    $show_times = !isset($arr_settings['pdf_show_time_columns']) || (isset($arr_settings['pdf_show_time_columns']) && $arr_settings['pdf_show_time_columns'] == 'on') ? true : false;
    $date_on_every_line = isset($arr_settings['pdf_show_date_on_every_line']) && $arr_settings['pdf_show_date_on_every_line'] == 'on' ? true : false;
    $show_logo = !isset($arr_settings['pdf_show_logo']) || (isset($arr_settings['pdf_show_logo']) && $arr_settings['pdf_show_logo'] == 'on') ? true : false;
    $show_custom_dropdown_values = !isset($arr_settings['pdf_show_custom_dropdown_values']) || (isset($arr_settings['pdf_show_custom_dropdown_values']) && $arr_settings['pdf_show_custom_dropdown_values'] == 'on') ? true : false;
    $show_calendarname_each_line = !isset($arr_settings['pdf_show_calendarname_each_line']) || (isset($arr_settings['pdf_show_calendarname_each_line']) && $arr_settings['pdf_show_calendarname_each_line'] == 'on') ? true : false;
    $bold = isset($arr_settings['pdf_fontweight_bold']) && $arr_settings['pdf_fontweight_bold'] == 'on' ? true : false;
    $colored_rows = isset($arr_settings['pdf_colored_rows']) && $arr_settings['pdf_colored_rows'] == 'on' ? true : false;
    $sort_by_calendars_order = isset($arr_settings['pdf_sorting']) && $arr_settings['pdf_sorting'] == 'on' ? true : false;
    $lang_page = isset($arr_settings['lang_page']) && !empty($arr_settings['lang_page']) ? $arr_settings['lang_page'] : 'Page';
    $lang_of = isset($arr_settings['lang_of']) && !empty($arr_settings['lang_of']) ? $arr_settings['lang_of'] : 'of';
    $label_date_column = isset($arr_settings['date_header']) && !empty($arr_settings['date_header']) ? $arr_settings['date_header'] : 'Date';
    $label_starttime_column = isset($arr_settings['starttime_header']) && !empty($arr_settings['starttime_header']) ? $arr_settings['starttime_header'] : 'From';
    $label_endtime_column = isset($arr_settings['endtime_header']) && !empty($arr_settings['endtime_header']) ? $arr_settings['endtime_header'] : 'Until';
    $label_event_title_column = isset($arr_settings['title_header']) && !empty($arr_settings['title_header']) ? $arr_settings['title_header'] : 'Title';

    $dateformat = $frm_submitted['df'] == 'm-d-y' ? 'm/d/Y' : 'd-m-Y';
    $current_month = $frm_submitted['m'];
    $current_view = $frm_submitted['cv'];
    $page_nr_x = 14;
    $page_nr_y = 270;
    $logo = FULLCAL_DIR . '/uploads/pdf_logo.png';

// get the events

    require_once EXTERNAL_DIR . '/fpdf18/fpdf.php';

    $pdf = new FPDF();

    $pdf->AliasNbPages();
    $pdf->SetFont('Arial', '', 12);
    $pdf->SetLeftMargin(15);
    $pdf->SetFillColor(0);
    $pdf->SetTextColor(0);
    $pdf->AddPage();

    //  $pdf->Ln();
    $pdf->SetFont('Arial', 'B', 16);


    $arr_calendar_ids = array_unique($arr_calendar_ids);

    $arr_events = array();
    foreach ($arr_calendar_ids as $cal_id) {
        if (isset($_SESSION['ews_calendars']) && isset($_SESSION['ews_calendars'][$cal_id]) && !empty($_SESSION['ews_calendars'][$cal_id])) {
            $calendar_name = $_SESSION['ews_calendars'][$cal_id]['name'];
            $calendar_color = $_SESSION['ews_calendars'][$cal_id]['calendar_color'];
        } else {
            $arr_calendar = Calendar::getCalendar($cal_id);
            $_SESSION['ews_calendars'][$cal_id] = $arr_calendar;
            $calendar_name = $arr_calendar['name'];
            $calendar_color = $arr_calendar['calendar_color'];
        }

        $arr_rgb = Utils::hex2rgb($calendar_color);

        $pdf->SetTextColor($arr_rgb[0], $arr_rgb[1], $arr_rgb[2]);

        $pdf->Cell(40, 6, $calendar_name, 0);

        $pdf->SetTextColor(0);
        $pdf->Ln();

        $frm_submitted['cal_id'] = $cal_id;

        $arr_events = array_merge($arr_events, Events::getEvents($frm_submitted));
    }

    //$arr_events = Utils::sortTwodimArrayByKey($arr_events, 'time_start');
    //$arr_events = Utils::sortTwodimArrayByKey($arr_events, 'allDay', 'DESC');
    //$arr_events = Utils::sortTwodimArrayByKey($arr_events, 'sorter');
    $arr_events = Utils::sortTwodimArrayByKey($arr_events, 'date_start');

    //print_r($arr_events);  
    $pdf->Ln();


    if (!empty($current_month)) {
        $pdf->Cell(40, 6, $current_month . ' ' . (date('Y', strtotime($frm_submitted['start']))), 0);
    } else {
        if ($current_view == 'day') {
            // do nothing
        } else {
            $pdf->Cell(40, 6, date($dateformat, strtotime($frm_submitted['start'])) . ' - ' . date($dateformat, strtotime($frm_submitted['end'])), 0);
        }
    }
    //  if($date_on_every_line) {
    if (!$table_look) {
        $pdf->Ln();
    }
    //  }

    if ($table_look) {
        $truncate_title = $show_times ? 40 : 65;
    } else {
        if (!$show_times && $date_on_every_line) {
            $truncate_title = 65;
        } else {
            $truncate_title = 55;
        }
    }

    if ($show_logo && !empty($logo) && file_exists($logo)) {
        $pdf->Image($logo, 160, 10);
    }

    if ($show_times) {
        
        if ($table_look) {
            if($show_calendarname_each_line) {
                if($date_on_every_line) {
                    $header = array($label_date_column, '', $label_starttime_column, $label_endtime_column, $label_event_title_column);
                    $w = array(30, 30, 25, 25, 75);
                } else {
                    $header = array($label_date_column, $label_starttime_column, $label_endtime_column, $label_event_title_column);
                    $w = array(40, 25, 25, 95);
                }
                
            } else {
                $header = array($label_date_column, $label_starttime_column, $label_endtime_column, $label_event_title_column);
                $w = array(30, 25, 25, 105);
            }
            
        } else {
            if($show_calendarname_each_line) {
                $header = array($label_date_column, '', $label_starttime_column, $label_endtime_column, $label_event_title_column);
                $w = array(30, 155);
            } else {
                $header = array($label_date_column, $label_starttime_column, $label_endtime_column, $label_event_title_column);
                $w = array(30, 155);
            }
            
        }
    } else {
        $header = array($label_date_column, $label_event_title_column);
        $w = array(30, 155);
    }
    $totalwidth = array_sum($w);

    if ($date_on_every_line) {
        if (!$table_look) {
            $pdf->Ln();
            $pdf->Ln();
            $pdf->Ln();
        }


        $pdf->SetFont('Arial', $bold ? 'B' : '', 12);


        if ($table_look) {
            $pdf = includeTableHeader($pdf, $header, $w);
        }

        $fill = 0;

        foreach ($arr_events as $key => $row) {
            $bln_am_pm = Settings::getSetting('show_am_pm', $row['user_id']) == 'on';

            if ($bln_am_pm) {
                //AM-PM
                $start_time = date('h:i A', strtotime($row['time_start']));
                $end_time = date('h:i A', strtotime($row['time_end']));
            } else {
                $start_time = date('H:i', strtotime($row['time_start']));
                $end_time = date('H:i', strtotime($row['time_end']));
            }
            $title = substr($row['title'], 0, $truncate_title) . (strlen($row['title']) > 40 ? '...' : '');

            if (function_exists('iconv')) {
                $title = iconv('UTF-8', 'windows-1252', $title);
            } else {
                $title = utf8_decode($title);
            }

            if($show_custom_dropdown_values) {
                if((isset($row['dropdown1']) && !empty($row['dropdown1'])) || (isset($row['dropdown2']) && !empty($row['dropdown2']))) {
                    $title .= ' (';
                }
                if(isset($row['dropdown1']) && !empty($row['dropdown1'])) {
                    $title .= $row['dropdown1'];

                    if(!isset($row['dropdown2']) || empty($row['dropdown2'])) {
                        $title .= ')';
                    }
                }

                if(isset($row['dropdown1']) && !empty($row['dropdown1']) && isset($row['dropdown2']) && !empty($row['dropdown2'])) {
                    $title .= ', ';
                }
                if(isset($row['dropdown2']) && !empty($row['dropdown2'])) {
                    $title .= $row['dropdown2'].')';
                }
            }
            
            if ($key == 35) {
                if ($table_look) {
                    $pdf->Cell(array_sum($w), 0, '', 'T');
                }
                $pdf->setXY($page_nr_x, $page_nr_y);
                $pdf->SetTextColor(0);
                $pdf->cell($totalwidth, 6, $lang_page . ' ' . $pdf->PageNo() . ' ' . $lang_of . ' {nb}', 0);
                startNewPage($pdf, $header, $w, $table_look, $logo, $show_logo);
                $count = 0;
                $fill = 0;
            }
            if ($table_look) {

//$show_calendarname_each_line
                
                $pdf->Cell($w[0], 6, date($dateformat, strtotime($row['date_start'])), 'LR', 0, 'L', $fill);

                if($show_calendarname_each_line) {
                    $pdf->Cell($w[0], 6, $_SESSION['ews_calendars'][$row['calendar_id']]['name'], 'L', 0, 'L', $fill);
                }
                
                if ($show_times) {
                    if ($row['allDay']) {
                        $pdf->Cell($w[1] + $w[2], 6, 'allDay', 'L', 0, 'L', $fill);
                    } else {
                        $pdf->Cell($w[1], 6, $start_time . ' - ', 'L', 0, '', $fill);
                        $pdf->Cell($w[2], 6, $end_time, 0, 0, '', $fill);
                    }
                    $pdf->Cell($w[3], 6, $title, 'LR', 0, 'L', $fill);
                } else {
                    $pdf->Cell($w[1], 6, $title, 'LR', 0, 'L', $fill);
                }
            } else {
                
                  
                $pdf->Cell($w[0], 6, date($dateformat, strtotime($row['date_start'])), '', 0);

                if($show_calendarname_each_line) {
                    $pdf->Cell(35, 6, $_SESSION['ews_calendars'][$row['calendar_id']]['name'], 'L', 0);
                }
                
                if ($show_times) {
                    if ($row['allDay']) {
                        $pdf->Cell(45, 6, 'allDay', 'L', 0, 'L');
                    } else {
                        $pdf->Cell(20, 6, $start_time . ' - ', 'L');
                        $pdf->Cell(25, 6, $end_time, 0);
                    }
                }

                $pdf->Cell($w[1], 6, $title, 'L', 0);
            }

            //$pdf->Cell($w[2],6,number_format($row[2]),'LR',0,'R',$fill);
            //$pdf->Cell($w[3],6,number_format($row[3]),'LR',0,'R',$fill);
            $pdf->Ln();

            if ($table_look) {
                $fill = !$fill;
            }
            if (!$colored_rows) {
                $fill = 0;
            }
        }
        if ($table_look) {
            $pdf->Cell(array_sum($w), 0, '', 'T');
        }
    } else {
        $arr_sorted_events = Events::getAgendaItems($arr_events, array(), $sort_by_calendars_order);
        $count = 0;
        // print_r($arr_sorted_events);     
        for ($i = 0; $i <= count($arr_calendar_ids); $i++) {
            $count ++;
        }

        if ($table_look) {
            $pdf = includeTableHeader($pdf, $header, $w);
        } else {
            $pdf->Ln();
            $pdf->Ln();
        }
        $count ++;
        $count ++;

        if (!$colored_rows) {
            $fill = 0;
        } else {
            $fill = 1;
        }
        $last_date = '';


        foreach ($arr_sorted_events as $date => $arr_event) {
            //  $pdf->Image($logo,160,10,40);
            //  $pdf->Ln(3);
            $pdf->SetFont('Arial', $bold ? 'B' : '', 12);

            if ($table_look) {
                $max_lines = 44;
            } else {
                $max_lines = 37;
            }

            if ((count($arr_event) + $count) > $max_lines || $count > $max_lines) {
                $pdf->setXY($page_nr_x, $page_nr_y);
                $pdf->SetTextColor(0);
                $pdf->cell($totalwidth, 6, $lang_page . ' ' . $pdf->PageNo() . ' ' . $lang_of . ' {nb}', 0);
                startNewPage($pdf, $header, $w, $table_look, $logo, $show_logo);
                $count = 0;
            }
            $pdf->Ln(1);

            // set color to normal black
            $pdf->SetTextColor(0);

            if ($last_date == date($dateformat, strtotime($date))) {
                $pdf->Cell($totalwidth, 6, '', 0);
            } else {
                $pdf->Cell($totalwidth, 6, date($dateformat, strtotime($date)), 'B');
            }



            $last_date = date($dateformat, strtotime($date));

            $pdf->Ln();

            //$fill=0;

            $count ++;
            $count ++;

            $fill = 0;
            $date_first_col_added = false;

            foreach ($arr_event as $key => $row) {
                $bln_am_pm = $arr_settings['show_am_pm'] == 'on'; //Settings::getSetting('show_am_pm', $arr_user['user_id']) == 'on';

                $arr_rgb = Utils::hex2rgb($row['calendar_color']);

                $pdf->SetTextColor($arr_rgb[0], $arr_rgb[1], $arr_rgb[2]);

                if ($bln_am_pm) {
                    //AM-PM
                    $start_time = date('h:i A', strtotime($row['time_start']));
                    $end_time = date('h:i A', strtotime($row['time_end']));
                } else {
                    $start_time = date('H:i', strtotime($row['time_start']));
                    $end_time = date('H:i', strtotime($row['time_end']));
                }

                $title = substr($row['title'], 0, $truncate_title) . (strlen($row['title']) > 40 ? '...' : '');
                if (function_exists('iconv')) {
                    $title = iconv('UTF-8', 'windows-1252', $title);
                } else {
                    $title = utf8_decode($title);
                }

                if($show_custom_dropdown_values) {
                    if((isset($row['dropdown1']) && !empty($row['dropdown1'])) || (isset($row['dropdown2']) && !empty($row['dropdown2']))) {
                        $title .= ' (';
                    }
                    if(isset($row['dropdown1']) && !empty($row['dropdown1'])) {
                        $title .= $row['dropdown1'];
                        
                        if(!isset($row['dropdown2']) || empty($row['dropdown2'])) {
                            $title .= ')';
                        }
                    }
                    
                    if(isset($row['dropdown1']) && !empty($row['dropdown1']) && isset($row['dropdown2']) && !empty($row['dropdown2'])) {
                        $title .= ', ';
                    }
                    if(isset($row['dropdown2']) && !empty($row['dropdown2'])) {
                        $title .= $row['dropdown2'].')';
                    }
                }
                
                
                $count ++;
                if ($table_look) {
                   

                    if($show_calendarname_each_line) {
                        $pdf->Cell(40, 6, $_SESSION['ews_calendars'][$row['calendar_id']]['name'], 'L', 0, 'L', $fill);
                    } else {
                        $pdf->Cell($w[0], 6, "", 'LR', 0, 'L', $fill);
                    }
                        // $pdf->Cell($w[0],6,date('d-m-Y', strtotime($row['date_start'])),'LR',0,'L',$fill);

                    if ($show_times) {
                        if ($row['allDay']) {
                            $pdf->Cell($w[1] + $w[2], 6, 'allDay', 'LR', 0, '', $fill);
                        } else {
                            $pdf->Cell($w[1], 6, $start_time . ' - ', 'L', 0, '', $fill);
                            $pdf->Cell($w[2], 6, $end_time, '', 0, '', $fill);
                        }
                        $pdf->Cell($w[3], 6, $title, 'LR', 0, 'L', $fill);
                    } else {
                        $pdf->Cell($w[1], 6, $title, 'LR', 0, 'L', $fill);
                    }
                } else {
                    $pdf->SetFont('Arial', $bold ? 'B' : '', 12);

                    if($show_calendarname_each_line) {
                        $pdf->Cell(50, 6, $_SESSION['ews_calendars'][$row['calendar_id']]['name'], 0);
                    }
                    
                    if ($show_times) {
                        if ($row['allDay']) {
                            $pdf->Cell(40, 6, 'allDay', 0);
                            //$pdf->Image(FULLCAL_DIR.'/images/clock_allday.png',10,10,12);
                        } else {


                            $pdf->Cell(15, 6, $start_time . ' - ', 0);
                            $pdf->Cell(25, 6, $end_time, 0);
                        }
                    } else {
                        $pdf->Cell(45, 6, '', 0);
                    }

                    $pdf->Cell(40, 6, $title, 0);
                }

                //$pdf->Cell(40, 6, $row['title'],0);
                $pdf->Ln();

                if ($table_look) {
                    $fill = !$fill;
                }
                if (!$colored_rows) {
                    $fill = 0;
                }
            }

            if ($table_look) {
                $pdf->Cell(array_sum($w), 0, '', 'T');
            }
            $pdf->Ln();
        }
    }
    $pdf->setXY($page_nr_x, $page_nr_y);
    $pdf->SetTextColor(0);
    $pdf->cell($totalwidth, 6, $lang_page . ' ' . $pdf->PageNo() . ' ' . $lang_of . ' {nb}', 0);

    return $pdf->Output();
}



function mobileAdd() {

    global $error;

    $arr_submit = array(
        array('color', 'string', false, '#3366CC'),
        array('date_end', 'int', false, ''),
        array('date_start', 'int', false, ''),
        array('title', 'string', true, ''),
        array('username', 'string', false, ''),
        array('password', 'string', false, ''),
        array('day', 'string', true, ''),
        array('allDay', 'bool', false, ''),
    );

    $frm_submitted = validate_var($arr_submit);

    // check user
    $int_user_id = 0;
    if (User::UserAuthenticated($frm_submitted['username'], $frm_submitted['password'], $int_user_id)) {
        // returned by reference user_id
    } else {
        echo 'username or password wrong';
        exit;
    }

    if (empty($frm_submitted['title'])) {
        echo 'Title can not be empty';
        exit;
    }

    $frm_submitted['title'] = str_replace('_', ' ', $frm_submitted['title']);
    $frm_submitted['color'] = '#' . $frm_submitted['color'];

    if (isset($frm_submitted['day'])) {
        $today = date('Y-m-d');

        if ($frm_submitted['day'] == 'today' || $frm_submitted['day'] == 'vandaag') {
            if ($frm_submitted['allDay']) {
                $frm_submitted['date_start'] = strtotime($today . ' ' . '00:00:00');
            } else {
                $frm_submitted['date_start'] = time();
            }
        } else if ($frm_submitted['day'] == 'yesterday') {
            if ($frm_submitted['allDay']) {
                $frm_submitted['date_start'] = strtotime($today . ' ' . '00:00:00') - 86400;
            } else {
                $frm_submitted['date_start'] = time() - 86400;
            }
        } else if ($frm_submitted['day'] == 'daybeforeyesterday') {
            if ($frm_submitted['allDay']) {
                $frm_submitted['date_start'] = strtotime($today . ' ' . '00:00:00') - (2 * 86400);
            } else {
                $frm_submitted['date_start'] = time() - (2 * 86400);
            }
        } else if ($frm_submitted['day'] == 'tomorrow') {
            if ($frm_submitted['allDay']) {
                $frm_submitted['date_start'] = strtotime($today . ' ' . '00:00:00') + 86400;
            } else {
                $frm_submitted['date_start'] = time() + 86400;
            }
        }
    }

    global $obj_db;
    if (empty($frm_submitted['date_end'])) {
        $frm_submitted['date_end'] = $frm_submitted['date_start'];
    }

    if (!isset($frm_submitted['color'])) {
        $frm_submitted['color'] = '';
    }
    $str_query = 'INSERT INTO events (title, user_id, color, date_start, time_start, date_end, time_end, allday) ' .
            'VALUES ("' . $frm_submitted['title'] . '",' .
            $int_user_id . ',' .
            ' "' . $frm_submitted['color'] . '",' .
            ' "' . date('Y-m-d', $frm_submitted['date_start']) . '",' .
            ' "' . date('H:i:s', $frm_submitted['date_start']) . '",' .
            ' "' . date('Y-m-d', $frm_submitted['date_end']) . '",' .
            ' "' . date('H:i:s', $frm_submitted['date_end']) . '"' .
            ( (date('H:i:s', $frm_submitted['date_start']) == '00:00:00' && date('H:i:s', $frm_submitted['date_end']) == '00:00:00') || $frm_submitted['allDay'] ? ' ,1' : ' ,0') . ')';

    $obj_result = mysqli_query($obj_db, $str_query);


    if ($obj_result !== false) {
        echo 'Saved succesfully';
    } else {
        echo 'failure';
    }
    exit;
}

function mobileAddStoredTitles() {
    global $error;

    $arr_submit = array(
        array('cal_id', 'int', false, ''),
        array('color', 'string', false, ''),
        array('date_end', 'int', false, ''),
        array('date_start', 'int', false, ''),
        array('title', 'string', true, ''),
        array('username', 'string', true, ''),
        array('password', 'string', true, ''),
        array('store', 'string', true, ''),
        array('allDay', 'bool', false, ''),
    );

    $frm_submitted = validate_var($arr_submit);

    // check user
    $int_user_id = 0;
    if (User::UserAuthenticated($frm_submitted['username'], $frm_submitted['password'], $int_user_id)) {
        // returned by reference user_id
    } else {
        echo 'username or password wrong';
        exit;
    }

    if (empty($frm_submitted['store'])) {
        echo 'No items where send';
        exit;
    }
    // setlocale(LC_ALL, 'nl_NL');
    global $obj_db;

    $str_store = str_replace(array('(', ')'), '', $frm_submitted['store']);
    $arr_store = explode('|', $str_store);

    unset($arr_store[0]);

    $arr_maanden = array('jan' => 1, 'feb' => 2, 'mrt' => 3, 'apr' => 4, 'mei' => 5, 'jun' => 6, 'jul' => 7, 'aug' => 8, 'sep' => 9, 'okt' => 10, 'nov' => 11, 'dec' => 12);

    foreach ($arr_store as $title) {
        $arr_title = explode(';', $title);
        $str_title = str_replace('_', ' ', $arr_title[0]);
        $str_date = str_replace('_', '', $arr_title[3]);
        $str_day = str_replace('_', '', $arr_title[1]);
        $str_time = str_replace('_', '', $arr_title[4]);
        $bln_allday = str_replace('_', '', $arr_title[2]) == 'true' ? true : false;
        $arr_date_parts = date_parse_from_format("dMY", $str_date); //echo $arr_title[3];
        $monthname = preg_replace('/[0-9]/', '', $str_date);
        $monthname = str_replace('.', '', $monthname);
        $monthnumber = $arr_maanden[$monthname];

        $inputday = $arr_date_parts['year'] . '-' . $monthnumber . '-' . $arr_date_parts['day'];

        if ($str_day == 'today') {
            if ($bln_allday) {
                $frm_submitted['date_start'] = strtotime($inputday . ' ' . '00:00:00');
            } else {
                $frm_submitted['date_start'] = strtotime($inputday . ' ' . $str_time);
            }
        } else if ($str_day == 'yesterday') {
            if ($bln_allday) {
                $frm_submitted['date_start'] = strtotime($inputday . ' ' . '00:00:00') - 86400;
            } else {
                $frm_submitted['date_start'] = strtotime($inputday . ' ' . $str_time) - 86400;
            }
        } else if ($str_day == 'daybeforeyesterday') {
            if ($bln_allday) {
                $frm_submitted['date_start'] = strtotime($inputday . ' ' . '00:00:00') - (2 * 86400);
            } else {
                $frm_submitted['date_start'] = strtotime($inputday . ' ' . $str_time) - (2 * 86400);
            }
        }
        if (!isset($frm_submitted['date_start'])) {
            $frm_submitted['date_start'] = time();
        }

        $frm_submitted['date_end'] = $frm_submitted['date_start'];

        $str_query = 'INSERT INTO events (title, user_id, color, date_start, time_start, date_end, time_end, allday) ' .
                'VALUES ("' . $str_title . '",' .
                $int_user_id . ',' .
                ' "#' . $frm_submitted['color'] . '",' .
                ' "' . date('Y-m-d', $frm_submitted['date_start']) . '",' .
                ' "' . date('H:i:s', $frm_submitted['date_start']) . '",' .
                ' "' . date('Y-m-d', $frm_submitted['date_end']) . '",' .
                ' "' . date('H:i:s', $frm_submitted['date_end']) . '"' .
                ((date('H:i:s', $frm_submitted['date_start']) == '00:00:00' && date('H:i:s', $frm_submitted['date_end']) == '00:00:00') || $bln_allday ? ' ,1' : ' ,0') . ')';
        $obj_result = mysqli_query($obj_db, $str_query);

        if ($obj_result !== false) {
            echo 'Saved succesfully'; // echo json_encode(array('success'=>true, 'msg'=>'gelukt'	));exit;
        } else {
            echo 'failure';
        }
    }
}

function getTag() {
    global $obj_db;

    if (isset($_GET['uid'])) {
        $user_id = $_GET['uid'];
    } else if (isset($_SESSION['calendar-uid']) && isset($_SESSION['calendar-uid']['uid']) && $_SESSION['calendar-uid']['uid'] > 0) {

        if (ALLOW_ACCESS_BY == 'free') {
            $user_id = 0;
        } else {
            $user_id = $_SESSION['calendar-uid']['uid'];
        }
    } else {
        $user_id = 0;
    }

    $str_query = 'SELECT *, event_id as id, concat_ws(" ",date_start,time_start) as start,concat_ws(" ",date_end,time_end) as end FROM events ' .
            ' WHERE title LIKE  "%' . $_POST['tag'] . '%" ' .
            ($user_id > 0 ? ' AND user_id = ' . $user_id : '') .
            ' ORDER BY date_start';
    $obj_result = mysqli_query($obj_db, $str_query);

    $str_events = '<span style="font-family:lucida-handwriting;">';

    while ($arr_line = mysqli_fetch_array($obj_result, MYSQLI_ASSOC)) {
        //	$arr_line['title'] = str_replace('<br />', ' ', $arr_line['title']);
        //$arr_line['title'] = str_replace( "\n", ' ', $arr_line['title']);
        $arr_line['title'] = str_replace($_POST['tag'], '<strong>' . $_POST['tag'] . '</strong>', $arr_line['title']);
        $arr_line['title'] = str_replace(ucfirst($_POST['tag']), '<strong>' . ucfirst($_POST['tag']) . '</strong>', $arr_line['title']);

        if ($arr_line['date_start'] == $arr_line['date_end']) {
            $str_events .= '<span style="background-color:#FEFFAF;">' . $arr_line['date_start'] . '</span>:<br /><em>' . $arr_line['title'] . '</em><br />';
        } else {
            $str_events .= '<span style="background-color:#FEFFAF;">' . $arr_line['date_start'] . '</span> - ' . $arr_line['date_end'] . ':<br /><em>' . $arr_line['title'] . '</em><br />';
        }
    }
    $str_events .= '</span>';

    echo json_encode($str_events);
    exit;
    return $str_events;
}

function showPeriodListView($bln_google_like = false) {
    $with_period = true;
    
    Utils::showPeriodListView($with_period);
    exit;
    
   
}

function showAgendaWidget($bln_google_like = false) {
    
    global $error;
    global $current_languages;

    $arr_submit = array(
        array('from', 'string', false, ''),
        array('to', 'string', false, ''),
        array('uid', 'int', false, ''),
        array('c', 'string', false, ''),
        array('w', 'int', false, 200),
        array('hrs', 'int', false, 24),
        array('ebc', 'string', false, 'FFFFCC'), // event background color
        array('bc', 'string', false, 'FFFFCC'), // background color
        array('showec', 'string', false, 'no'), // show event color
        array('lang', 'string', false, ''),
        array('ics', 'string', false, 'no'),
        array('period', 'int', false, ''),
        array('google_calid', 'string', false, ''),
        array('google_privatekey', 'string', false, ''),
    );

    $frm_submitted = validate_var($arr_submit);

    $obj_smarty = new Smarty();
    $obj_smarty->compile_dir = 'templates_c/';

    if (!empty($frm_submitted['lang'])) {
        $frm_submitted['lang'] = strtolower($frm_submitted['lang']);

        $bln_found = false;
        foreach ($current_languages as $code => $lang) {
            if (strtoupper($frm_submitted['lang']) == $code) {
                $bln_found = true;
            }
        }

        if ($bln_found) {
            Utils::setLocaleLanguage($frm_submitted['lang']);
        }
    }

    header("Content-Type: text/html;charset=UTF-8");

    $obj_smarty->assign('iframewidth', $frm_submitted['w']);
    $obj_smarty->assign('showeventcolor', $frm_submitted['showec']);
    $obj_smarty->assign('hrs', $frm_submitted['hrs']);

    $arr_res = array();

    $arr_res['results'] = array(
        date('Y-m-d', strtotime('+2DAY')) => array(array(
                'event_id' => 102,
                'title' => 'Walking in the Belgian hills near Spa',
                'date_start' => date('Y-m-d', strtotime('+2DAY')),
                'time_start' => '12:16:58',
                'date_end' => date('Y-m-d', strtotime('+2DAY')),
                'time_end' => '17:27:45',
                'allDay' => '1',
                'calendartype' => '',
                'user_id' => '2',
                'color' => '#FFBB00',
            ))
        ,
        date('Y-m-d', strtotime('+3DAY')) => array(array(
                'event_id' => 102,
                'title' => 'Luxembourg',
                'date_start' => date('Y-m-d', strtotime('+3DAY')),
                'time_start' => '12:16:58',
                'date_end' => date('Y-m-d', strtotime('+3DAY')),
                'time_end' => '17:27:45',
                'allDay' => '1',
                'calendartype' => '',
                'user_id' => '2',
                'color' => '#FFBB00',
            ))
        ,
        date('Y-m-d', strtotime('+4DAY')) => array(array(
                'event_id' => 102,
                'title' => 'Stayed at the campingsite',
                'date_start' => date('Y-m-d', strtotime('+4DAY')),
                'time_start' => '12:16:58',
                'date_end' => date('Y-m-d', strtotime('+4DAY')),
                'time_end' => '17:27:45',
                'allDay' => '1',
                'calendartype' => '',
                'user_id' => '2',
                'color' => '#3366cc',
            ))
        ,
        date('Y-m-d', strtotime('+5DAY')) => array(array(
                'event_id' => 104,
                'title' => 'another event',
                'date_start' => date('Y-m-d', strtotime('+5DAY')),
                'time_start' => '6:59:52',
                'date_end' => date('Y-m-d', strtotime('+5DAY')),
                'time_end' => '14:50:36',
                'allDay' => '1',
                'calendartype' => '',
                'user_id' => '2',
                'color' => '#3366cc',
            ))
        ,
        date('Y-m-d', strtotime('+6DAY')) => array(array(
                'event_id' => 105,
                'title' => 'yet another event',
                'date_start' => date('Y-m-d', strtotime('+6DAY')),
                'time_start' => '10:58:21',
                'date_end' => date('Y-m-d', strtotime('+6DAY')),
                'time_end' => '14:21:26',
                'allDay' => '1',
                'calendartype' => '',
                'user_id' => '2',
                'color' => '#3366cc',
            ), array(
                'event_id' => 106,
                'title' => 'Back home',
                'date_start' => date('Y-m-d', strtotime('+6DAY')),
                'time_start' => '11:35:28',
                'date_end' => date('Y-m-d', strtotime('+6DAY')),
                'time_end' => '18:15:41',
                'allDay' => '0',
                'calendartype' => '',
                'user_id' => '2',
                'color' => '#3366cc',
            ))
            ,
    );

    $arr_return['hide_from'] = false;
    $arr_return['hide_to'] = false;

    if (defined('AGENDA_VIEW_AMOUNT_DAYS_TO_SHOW') && AGENDA_VIEW_AMOUNT_DAYS_TO_SHOW > 0) {
        $amount_days_to_show = AGENDA_VIEW_AMOUNT_DAYS_TO_SHOW;
    } else {
        $amount_days_to_show = 5;
    }

    if (!empty($frm_submitted['from'])) {
        $arr_res['hide_from'] = true;
    }
    if (!empty($frm_submitted['to'])) {
        $arr_res['results'] = array(
            date('Y-m-d', strtotime('-4DAY')) => array(array(
                    'event_id' => 99,
                    'title' => 'felisc',
                    'date_start' => date('Y-m-d', strtotime('-4DAY')),
                    'time_start' => '9:21:48',
                    'date_end' => date('Y-m-d', strtotime('-4DAY')),
                    'time_end' => '13:54:41',
                    'allDay' => '0',
                    'calendartype' => '',
                    'user_id' => '2',
                    'color' => '#3366cc',
                ))
            ,
            date('Y-m-d', strtotime('-1DAY')) => array(array(
                    'event_id' => 100,
                    'title' => 'felisc',
                    'date_start' => date('Y-m-d', strtotime('-1DAY')),
                    'time_start' => '9:21:48',
                    'date_end' => date('Y-m-d', strtotime('-1DAY')),
                    'time_end' => '13:54:41',
                    'allDay' => '0',
                    'calendartype' => '',
                    'user_id' => '2',
                    'color' => '#3366cc',
                ))
                ,
        );
        if (count($arr_res['results']) < $amount_days_to_show) {
            $arr_res['hide_to'] = true;
        }
    }

    if (empty($frm_submitted['from']) && empty($frm_submitted['to'])) {
        $arr_res['hide_from'] = true;
    }
//print_r($arr_res);
    $obj_smarty->assign('items', $arr_res['results']);
    $obj_smarty->assign('from', current(array_keys($arr_res['results'])));
    $obj_smarty->assign('to', end(array_keys($arr_res['results'])));
    $obj_smarty->assign('hide_from', $arr_res['hide_from']);
    $obj_smarty->assign('hide_to', $arr_res['hide_to']);


    if ($bln_google_like) {
        $obj_smarty->display(FULLCAL_DIR . '/view/examples/agenda_widget_google_like.html');
    } else {
//		$frm_submitted['from'] = date('Y-m-d');
//		unset($frm_submitted['to']);
//		$frm_submitted['combine_moreday_events'] = false;
//
//		$arr_res = Events::getListviewEvents($frm_submitted);
//
//		if(isset($arr_res)) {
//		    $obj_smarty->assign('items', $arr_res['results']);
//			$obj_smarty->assign('from', $arr_res['results']);
//			$obj_smarty->assign('to', $arr_res['results']);
//		}

        $obj_smarty->display(FULLCAL_DIR . '/view/examples/agenda_widget_justtext.html');
    }
}