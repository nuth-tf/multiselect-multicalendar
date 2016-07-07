<?php

/*
 * Created on 14-sep-2014
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

require_once '../../include/default.inc.php';

if (isset($_SESSION['add_user_error'])) {
    $_SESSION['add_user_error'] = '';
}


if (User::isLoggedIn()) {
    header("Cache-Control: no-cache, must-revalidate");

    global $obj_smarty;

    $arr_user = User::getUser();
    $bln_user = User::isUser();
    $bln_admin = User::isAdmin();
    $bln_superadmin = User::isSuperAdmin();

    $obj_smarty->assign('title', $arr_user['title']);
    $obj_smarty->assign('name', $arr_user['firstname'] . ' ' . (!empty($arr_user['infix']) ? $arr_user['infix'] : '') . $arr_user['lastname']);
    $obj_smarty->assign('user', $_SESSION['calendar-uid']['username']);
    $obj_smarty->assign('user_id', $_SESSION['calendar-uid']['uid']);
    $obj_smarty->assign('is_user', $bln_user);
    $obj_smarty->assign('is_admin', $bln_admin);
    $obj_smarty->assign('is_super_admin', $bln_superadmin);

    $language = Settings::getLanguage($arr_user['user_id']);
    $obj_smarty->assign('language', $language);

    $arr_settings = Settings::getSettings($arr_user['user_id']);
    $obj_smarty->assign('settings', $arr_settings);

    if ($bln_user) {
        header('location: ' . FULLCAL_URL . '/user');
        exit;
    } else if ($bln_superadmin) {
        $arr_calendars = Calendar::getCalendars(true);
        $obj_smarty->assign('calendars', $arr_calendars);
    } else if ($bln_admin) {
        $arr_calendars = Calendar::getCalendarsOfAdmin($arr_user['user_id']);
        $obj_smarty->assign('calendars', $arr_calendars);
    }

    $arr_cal_ids = array();
    foreach($arr_calendars as $cal) {
        $arr_cal_ids[] = $cal['calendar_id'];
    }
    $obj_smarty->assign('cal_ids', implode(',', $arr_cal_ids));
    
    $arr_deleted_calendars = Calendar::getCalendarsOfAdmin($arr_user['user_id'], true);

    $obj_smarty->assign('cnt_deleted_calendars', count($arr_deleted_calendars));
} else {
    $obj_smarty->display(FULLCAL_DIR . '/login.html');
    exit;
}

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'get_calendar':
            getCalendar();
            break;
        case 'save_calendar':
            saveCalendar();
            break;
        case 'new_calendar':
            newCalendar();
            break;
        case 'delete':
            deleteCalendar();
            break;
        case 'undelete':
            undeleteCalendar();
            break;
        case 'get_deleted':
            getDeletedCalendars();
            break;
        case 'up':
            orderUp();
            break;
        case 'down':
            orderDown();
            break;

        default:
            die('no such action available');
    }

    exit;
} else {
    $obj_smarty->assign('active', 'calendars');

    $obj_smarty->display(FULLCAL_DIR . '/view/admin_panel.tpl');
    exit;
}

function getCalendars() {
    global $obj_smarty;
    $arr_users = array();

    $arr_user = User::getUser();
    $bln_admin = User::isAdmin();
    $bln_superadmin = User::isSuperAdmin();

    $obj_smarty->assign('title', $arr_user['title']);
    $obj_smarty->assign('name', $arr_user['firstname'] . ' ' . (!empty($arr_user['infix']) ? $arr_user['infix'] : '') . $arr_user['lastname']);
    $obj_smarty->assign('user', $_SESSION['calendar-uid']['username']);
    $obj_smarty->assign('user_id', $_SESSION['calendar-uid']['uid']);
    $obj_smarty->assign('is_admin', $bln_admin);
    $obj_smarty->assign('is_super_admin', $bln_superadmin);

    if ($bln_admin) {
        $arr_calendars = Calendar::getCalendars();
    }
    return $arr_calendars;
}

function orderUp() {
    $arr_submit = array(
        array('cid', 'int', true, ''),
        array('ids', 'string', true, ''),
    );

    $frm_submitted = validate_var($arr_submit);

    global $obj_smarty;

    // get the cal_id in front of the one we want to move up
    $arr_cal_ids = explode(',', $frm_submitted['ids']);
    $foundkey = array_search($frm_submitted['cid'], $arr_cal_ids);
    $the_one_before = $arr_cal_ids[$foundkey-1];
   
    $arr_cal_ids[$foundkey] = $the_one_before;
    $arr_cal_ids[$foundkey-1] = $frm_submitted['cid'];
    
    Calendar::changeOrder($arr_cal_ids);  
    
    header('location: ' . FULLCAL_URL . '/admin/calendars');
    exit;

}

function orderDown() {
    $arr_submit = array(
        array('cid', 'int', true, ''),
        array('ids', 'string', true, ''),
    );

    $frm_submitted = validate_var($arr_submit);

    global $obj_smarty;

    // get the cal_id in front of the one we want to move up
    $arr_cal_ids = explode(',', $frm_submitted['ids']);
    $foundkey = array_search($frm_submitted['cid'], $arr_cal_ids);
    $the_one_after = $arr_cal_ids[$foundkey+1];
   
    $arr_cal_ids[$foundkey+1] = $frm_submitted['cid'];
    $arr_cal_ids[$foundkey] = $the_one_after;
  
    Calendar::changeOrder($arr_cal_ids);  
 
    
     header('location: ' . FULLCAL_URL . '/admin/calendars');
    exit;

}

function getCalendar() {
    $arr_submit = array(
        array('cid', 'int', true, ''),
    );

    $frm_submitted = validate_var($arr_submit);

    global $obj_smarty;

    //if(User::isAdminUser($frm_submitted['cid'])) {

    $arr_calendar = Calendar::getCalendar($frm_submitted['cid'], false);    // when dditems must be string -> true

    $arr_calendar['dropdown1_label'] = CustomFields::getDropdown1Label();
    $arr_calendar['dropdown2_label'] = CustomFields::getDropdown2Label();
        
    $str_dditems = $arr_calendar['str_dditems'];

    $str_locations = '';
    foreach ($arr_calendar['locations'] as $location) {
        $str_locations .= $location['name'] . ', ';
    }
    //$arr_birthdate = explode('-', $arr_calendar['birth_date']);

    $last_dditem = Calendar::getHighestDDItemId() + 1;
    
   // $last_dditem = isset($arr_calendar['dditems'][count($arr_calendar['dditems']) - 1]) ? (int) $arr_calendar['dditems'][count($arr_calendar['dditems']) - 1]['dditem_id'] : 1;
    $last_location = isset($arr_calendar['locations'][count($arr_calendar['locations']) - 1]) ? (int) $arr_calendar['locations'][count($arr_calendar['locations']) - 1]['location_id'] : 1;

    $obj_smarty->assign('active', 'calendar');
    $obj_smarty->assign('calendar', $arr_calendar);
    $obj_smarty->assign('my_groups', Group::getMyGroups());

    $obj_smarty->assign('cnt_dditems', count($arr_calendar['dditems']));
    $obj_smarty->assign('cnt_locations', count($arr_calendar['locations']));
    $obj_smarty->assign('last_dditem', $last_dditem);
    $obj_smarty->assign('last_location', $last_location);
    $obj_smarty->assign('str_dditems', $str_dditems);
    $obj_smarty->assign('str_locations', $str_locations);

    $obj_smarty->display(FULLCAL_DIR . '/view/admin_panel.tpl');
    exit;

}

function saveCalendar() {

    global $error;
    global $obj_smarty;

    $arr_submit = array(
        array('calendar_id', 'int', false, -1),
        array('name', 'string', true, ''),
        array('dditems', 'string', false, ''),
        array('dditems_usergroup_id', 'int', false, -1),
        array('usergroup_dditems_viewtype', 'string', false, ''),
        array('assign_dditem_to_user', 'bool', false, 0),
        array('locations', 'string', false, ''),
        array('locationfield', 'string', false, ''),
        array('calendar_color', 'string', true, ''),
        array('can_add', 'bool', false, 0),
        array('can_edit', 'bool', false, 0),
        array('can_delete', 'bool', false, 0),
        array('can_view', 'bool', false, 0),
        array('can_change_color', 'bool', false, 0),
        array('can_dd_drag', 'string', false, 0),
        array('checkbox_use_color_for_all_events', 'bool', false, 0),
        array('initial_show', 'bool', false, 0),
        array('users_can_email_event', 'bool', false, 0),
        array('all_event_mods_to_admin', 'bool', false, 0),
        array('mail_assigned_event_to_user', 'bool', false, 0),
        array('active', 'string', true, ''),
        array('cal_startdate', 'string', false, ''),
        array('cal_enddate', 'string', false, ''),
        array('alterable_startdate', 'string', false, ''),
        array('alterable_enddate', 'string', false, ''),
        array('share_type', 'string', true, 'private_group'),
        array('usergroup_id', 'int', false, -1),
        array('calendar_admin_email', 'email', false, ''),
        array('origin', 'string', false, ''),
        array('exchange_username', 'string', false, ''),
        array('exchange_password', 'string', false, ''),
        array('exchange_token', 'string', false, ''),
        array('exchange_extra_secure', 'bool', false, 0),
        array('description_field_required', 'bool', false, 0),
        array('location_field_required', 'bool', false, 0),
        array('phone_field_required', 'bool', false, 0),
        array('url_field_required', 'bool', false, 0),
        array('show_description_field', 'bool', false, 1),
        array('show_location_field', 'bool', false, 0),
        array('show_phone_field', 'bool', false, 0),
        array('show_url_field', 'bool', false, 0),
        array('show_team_member_field', 'bool', false, 0),
        array('notify_assign_teammember', 'bool', false, 0),
        array('show_dropdown_1_field', 'bool', false, 0),
        array('show_dropdown_2_field', 'bool', false, 0),
        array('next_days_visible', 'string', false, ''),
        array('add_team_member_to_title', 'bool', false, 0),
        array('add_custom_dropdown1_to_title', 'bool', false, 0),
        array('add_custom_dropdown2_to_title', 'bool', false, 0),
        
        
        
    );

    $frm_submitted = validate_var($arr_submit);
    
    if (!$error || is_null($error)) {
        $bln_success = Calendar::saveCalendar($frm_submitted);

        if (is_string($bln_success)) {
            echo json_encode(array('success' => false, 'save_calendar_error' => $bln_success));
            exit;
        }
    } else {
        $obj_smarty->assign('save_calendar_error', $error);
    }

    if (!is_null($error) && $error !== false) {
        $arr_calendar = array();

        // give feedback about the error
        if ($frm_submitted['calendar_id'] > 0) {
            $arr_calendar = Calendar::getCalendar($frm_submitted['calendar_id'], true);
        } else {
            $arr_calendar['dditems'] = array();

            $obj_smarty->assign('cnt_dditems', 0);
            $obj_smarty->assign('cnt_locations', 0);
            $obj_smarty->assign('last_dditem', '');
            $obj_smarty->assign('last_location', '');
            $obj_smarty->assign('str_dditems', '');
            $obj_smarty->assign('str_locations', '');

            $arr_calendar = array_merge($arr_calendar, $frm_submitted);
        }

        $obj_smarty->assign('active', 'calendar');
        $obj_smarty->assign('calendar', $arr_calendar);
        $obj_smarty->assign('my_groups', Group::getMyGroups());

        $obj_smarty->display(FULLCAL_DIR . '/view/admin_panel.tpl');
        exit;
    } else {
        header('location: ' . FULLCAL_URL . '/admin/calendars');
        exit;
    }
}

function newCalendar() {
    global $obj_smarty;

    $arr_calendar = array('calendar_color' => '#3366CC');
    $obj_smarty->assign('calendar', $arr_calendar);

    $obj_smarty->assign('active', 'calendar');
    $obj_smarty->assign('my_groups', Group::getMyGroups());

    $obj_smarty->display(FULLCAL_DIR . '/view/admin_panel.tpl');
}

function addUser() {
    global $error;
    global $obj_smarty;

    $arr_submit = array(
        array('title', 'string', false, ''),
        array('firstname', 'string', false, ''),
        array('infix', 'string', false, ''),
        array('lastname', 'string', true, ''),
        array('username', 'string', false, ''),
        array('email', 'email', true, ''),
    );

    $frm_submitted = validate_var($arr_submit);
    $_SESSION['add_user_error'] = '';

    if (!$error || is_null($error)) {
        $mixed_success = User::addUser($frm_submitted);

        if (is_string($mixed_success)) {
            $_SESSION['add_user_error'] = $mixed_success;
        } else {
            if ($mixed_success['insert'] === false) {
                $_SESSION['add_user_error'] = 'Failure while inserting the user';
            } else {
                if ($mixed_success['mail'] == 'notsend') {
                    $_SESSION['add_user_error'] = 'User inserted succesfully, failure while sending email';
                } else {
//					$obj_smarty->assign('active', 'users');
//					$obj_smarty->display(FULLCAL_DIR.'/view/admin_panel.tpl');
//					exit;
                }
            }
        }
    } else {
        $obj_smarty->assign('active', 'new_user');
        $obj_smarty->assign('error', $error);
        $obj_smarty->display(FULLCAL_DIR . '/view/admin_panel.tpl');
        exit;
    }
    header('location: ' . FULLCAL_URL . '/admin/users');
    exit;
}

function deleteCalendar() {
    global $error;
    global $obj_smarty;

    $arr_submit = array(
        array('cid', 'int', true, ''),
    );

    $frm_submitted = validate_var($arr_submit);

    if (User::isAdmin() || User::isSuperAdmin()) {
        $bln_success = Calendar::deleteCalendar($frm_submitted['cid']);

        if ($bln_success) {
            $obj_smarty->assign('msg', 'Calendar deleted succesfully - <a href="' . FULLCAL_URL . '/admin/calendars/?action=undelete&cid=' . $frm_submitted['cid'] . '">Undo</a>');
        }

        $arr_user = User::getUser();

        if (User::isSuperAdmin()) {
            $arr_calendars = Calendar::getCalendars(true);
        } else if (User::isAdmin()) {
            $arr_calendars = Calendar::getCalendarsOfAdmin($arr_user['user_id']);
        }
        //$arr_calendars = Calendar::getCalendars();
        $obj_smarty->assign('calendars', $arr_calendars);
        $obj_smarty->assign('active', 'calendars');




        $arr_calendars = Calendar::getCalendarsOfAdmin($arr_user['user_id'], true);
        $obj_smarty->assign('cnt_deleted_calendars', count($arr_calendars));
    } else {
        $obj_smarty->assign('error', 'NO rights to delete this calendar');
    }

    $obj_smarty->display(FULLCAL_DIR . '/view/admin_panel.tpl');
    exit;
}


function undeleteCalendar() {
    global $error;
    global $obj_smarty;

    $arr_submit = array(
        array('cid', 'int', true, ''),
    );

    $frm_submitted = validate_var($arr_submit);

    if (User::isAdmin() || User::isSuperAdmin()) {
        $bln_success = Calendar::undeleteCalendar($frm_submitted['cid']);

        if ($bln_success) {
            $obj_smarty->assign('msg', 'Calendar is back again');
        }

        $arr_user = User::getUser();

        if (User::isSuperAdmin()) {
            $arr_calendars = Calendar::getCalendars(true);
        } else if (User::isAdmin()) {
            $arr_calendars = Calendar::getCalendarsOfAdmin($arr_user['user_id']);
        }
        //$arr_calendars = Calendar::getCalendars();
        $obj_smarty->assign('calendars', $arr_calendars);
        $obj_smarty->assign('active', 'calendars');
    } else {
        $obj_smarty->assign('error', 'NO rights to undelete this calendar');
    }

    $obj_smarty->display(FULLCAL_DIR . '/view/admin_panel.tpl');
    exit;
}

function getDeletedCalendars() {
    global $obj_smarty;

    if (User::isLoggedIn()) {
        $arr_user = User::getUser();

        if (User::isSuperAdmin()) {
            $arr_calendars = Calendar::getCalendars(true, true);
            $obj_smarty->assign('calendars', $arr_calendars);
        } else if (User::isAdmin()) {
            $arr_calendars = Calendar::getCalendarsOfAdmin($arr_user['user_id'], true);
            $obj_smarty->assign('calendars', $arr_calendars);
        }
    }

    $obj_smarty->assign('active', 'calendars');

    $obj_smarty->display(FULLCAL_DIR . '/view/admin_panel.tpl');
    exit;
}

exit;
?>
