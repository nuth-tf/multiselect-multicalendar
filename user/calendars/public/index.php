<?php

/*
 * Created on 14-sep-2014
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */


require_once '../../../include/default.inc.php';

if (isset($_SESSION['add_user_error'])) {
    $_SESSION['add_user_error'] = '';
}

if (!defined('USERS_CAN_ADD_CALENDARS') || !USERS_CAN_ADD_CALENDARS) {
    header('location:' . FULLCAL_URL);
    exit;
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

    $language = Settings::getSetting('language', $arr_user['user_id']);
    $obj_smarty->assign('language', $language);

    $arr_calendars = Calendar::getOtherPublicCalendars($arr_user['user_id']);
    $obj_smarty->assign('calendars', $arr_calendars);
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
        case 'add_calendar':
            addCalendar();
            break;
        case 'delete':
            deleteCalendar();
            break;
        case 'undelete':
            undeleteCalendar();
            break;
        default:
            die('no such action available');
    }

    exit;
} else {
    $obj_smarty->assign('active', 'public_calendars');

    $obj_smarty->display(FULLCAL_DIR . '/view/user_panel.tpl');
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

function getCalendar() {
    $arr_submit = array(
        array('cid', 'int', true, ''),
    );

    $frm_submitted = validate_var($arr_submit);

    global $obj_smarty;

    //if(User::isAdminUser($frm_submitted['cid'])) {

    $arr_calendar = Calendar::getCalendar($frm_submitted['cid'], true);

    //$arr_birthdate = explode('-', $arr_calendar['birth_date']);

    $obj_smarty->assign('active', 'public_calendar');
    $obj_smarty->assign('calendar', $arr_calendar);

    $obj_smarty->display(FULLCAL_DIR . '/view/user_panel.tpl');
    exit;

//	} else {
//		$obj_smarty->assign('active', 'calendars');
//		$obj_smarty->assign('error', 'NO rights to change this calendar');
//
//		$obj_smarty->display(FULLCAL_DIR.'/view/user_panel.tpl');
//		exit;
//	}
}

function saveCalendar() {

    global $error;
    global $obj_smarty;

    $arr_submit = array(
        array('calendar_id', 'int', false, -1),
        array('name', 'string', true, ''),
        array('dditems', 'string', true, ''),
        array('calendar_color', 'string', true, ''),
        array('can_add', 'bool', false, 0),
        array('can_edit', 'bool', false, 0),
        array('can_delete', 'bool', false, 0),
        array('can_change_color', 'bool', false, 0),
        array('checkbox_use_color_for_all_events', 'bool', false, 0),
        array('initial_show', 'bool', false, 0),
        array('share_type', 'string', true, 'private_group'),
        array('active', 'bool', false, 0),
        array('description_field_required', 'bool', false, 0),
        array('location_field_required', 'bool', false, 0),
        array('phone_field_required', 'bool', false, 0),
        array('url_field_required', 'bool', false, 0),
        array('show_description_field', 'bool', false, 0),
        array('show_location_field', 'bool', false, 0),
        array('show_phone_field', 'bool', false, 0),
        array('show_team_member_field', 'bool', false, 0),
        array('show_url_field', 'bool', false, 0),
    );

    $frm_submitted = validate_var($arr_submit);

    if (!$error) {
        $bln_success = Calendar::saveCalendar($frm_submitted);

        if (is_string($bln_success)) {
            echo json_encode(array('success' => false, 'error' => $bln_success));
            exit;
        }
    } else {
        $obj_smarty->assign('error', $error);
    }

    header('location: ' . FULLCAL_URL . '/user/calendars/public');
    exit;
}

function newCalendar() {
    global $obj_smarty;

    $arr_calendar = array('calendar_color' => '#3366CC');
    $obj_smarty->assign('calendar', $arr_calendar);

    $obj_smarty->assign('active', 'public_calendar');

    $obj_smarty->display(FULLCAL_DIR . '/view/user_panel.tpl');
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
            $obj_smarty->assign('msg', 'Calendar deleted succesfully - <a href="' . FULLCAL_URL . '/user/calendars/?action=undelete&cid=' . $frm_submitted['cid'] . '">Undo</a>');
        }

        $arr_calendars = Calendar::getCalendars();
        $obj_smarty->assign('calendars', $arr_calendars);
        $obj_smarty->assign('active', 'public_calendars');
    } else {
        $obj_smarty->assign('error', 'NO rights to delete this calendar');
    }

    $obj_smarty->display(FULLCAL_DIR . '/view/user_panel.tpl');
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

        $arr_calendars = Calendar::getCalendars();
        $obj_smarty->assign('calendars', $arr_calendars);
        $obj_smarty->assign('active', 'public_calendars');
    } else {
        $obj_smarty->assign('error', 'NO rights to undelete this calendar');
    }

    $obj_smarty->display(FULLCAL_DIR . '/view/user_panel.tpl');
    exit;
}

exit;
?>
