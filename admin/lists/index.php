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

    if ($bln_user) {
        header('location: ' . FULLCAL_URL . '/user/lists');
        exit;
    } else if ($bln_superadmin) {
        $obj_smarty->assign('active', 'admin');

        $obj_smarty->display(FULLCAL_DIR . '/view/admin_panel.tpl');
        exit;
    } else {
        $obj_smarty->assign('title', $arr_user['title']);
        $obj_smarty->assign('name', $arr_user['firstname'] . ' ' . (!empty($arr_user['infix']) ? $arr_user['infix'] : '') . $arr_user['lastname']);
        $obj_smarty->assign('user', $_SESSION['calendar-uid']['username']);
        $obj_smarty->assign('user_id', $_SESSION['calendar-uid']['uid']);
        $obj_smarty->assign('is_user', $bln_user);
        $obj_smarty->assign('is_admin', $bln_admin);
        $obj_smarty->assign('is_super_admin', $bln_superadmin);

        $language = Settings::getLanguage($arr_user['user_id']);
        $obj_smarty->assign('language', $language);

        if ($bln_admin) {
            $arr_calendars = Calendar::getCalendarsOfAdmin($arr_user['user_id']);
            $obj_smarty->assign('calendars', $arr_calendars);
            $obj_smarty->assign('selected_calendar', 'all');
        }
    }
} else {
    $obj_smarty->display(FULLCAL_DIR . '/login.html');
    exit;
}

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'get_list':
            getList();
            break;
        case 'get_list_ajax':
            getListAjax();
            break;
//		case 'save_calendar':
//			saveCalendar();
//			break;
        
        default:
            die('no such action available');
    }

    exit;
} else {
    global $error;
    $arr_submit = array(
        array('cid', 'string', false, 'all'),
        array('st', 'string', false, ''),
        array('end', 'string', false, ''),
    );

    $frm_submitted = validate_var($arr_submit);

    $obj_smarty->assign('active', 'lists');

    $arr_mixed_list = Lists::getLists($frm_submitted);

    $obj_smarty->assign('list', $arr_mixed_list['users']);

    if (!empty($frm_submitted['cid'])) {
        $obj_smarty->assign('selected_calendar', $frm_submitted['cid']);
    }
    //$period_startdate = date('Y-m-d', strtotime('-1 YEAR'));
    //$period_enddate = date('Y-m-d');

    $obj_smarty->assign('startdate', $arr_mixed_list['startdate']);
    $obj_smarty->assign('enddate', $arr_mixed_list['enddate']);


    // get calendars of all users
    //$arr_calendars = Calendar::getCalendersOfAllUsers();
    //$obj_smarty->assign('calendars', $arr_calendars);

    $obj_smarty->display(FULLCAL_DIR . '/view/admin_panel.tpl');
    exit;
}

function getList() {
    global $error;
    $arr_submit = array(
        array('uid', 'int', true, -1),
        array('cid', 'string', false, ''),
        array('st', 'string', false, ''),
        array('end', 'string', false, ''),
    );

    $frm_submitted = validate_var($arr_submit);

    global $obj_smarty;

    if (!User::isLoggedIn()) {
        return array();
    }
    $arr_admin = User::getUser();

    if (!is_null($error) || !empty($error)) {
        echo ($error);
    } else {
        //if(User::isAdminUser($frm_submitted['cid'])) {

        $arr_mixed_list = Lists::getList($frm_submitted);
        $arr_list = $arr_mixed_list['list'];

        $arr_user = User::getUserById($frm_submitted['uid']);

        $arr_calendars = Calendar::getCalendarsByUserId($frm_submitted['uid']);

        // check if admin has right to see this calendar
        $arr_calendar_ids = Calendar::getCalendarsOfAdmin($arr_admin['user_id'], false, false, true);
        $arr_admin_calendars = array();

        foreach ($arr_calendars as $cal) {
            if (in_array($cal['calendar_id'], $arr_calendar_ids)) {
                $arr_admin_calendars[] = $cal;
            }
        }

        $obj_smarty->assign('calendars', $arr_admin_calendars);

        if (!empty($frm_submitted['cid']) && $frm_submitted['cid'] !== 'all') {
            $obj_smarty->assign('selected_calendar', $frm_submitted['cid']);
        } else {
            $obj_smarty->assign('selected_calendar', 'all');
        }

        $obj_smarty->assign('active', 'list');
        $obj_smarty->assign('list', $arr_list);
        $obj_smarty->assign('user', $arr_user);
        $obj_smarty->assign('total_day_count', $arr_mixed_list['total_day_count']);
        $obj_smarty->assign('total_hour_count', round($arr_mixed_list['total_hour_count'], 2));
        $obj_smarty->assign('startdate', $arr_mixed_list['startdate']);
        $obj_smarty->assign('enddate', $arr_mixed_list['enddate']);

        $obj_smarty->display(FULLCAL_DIR . '/view/admin_panel.tpl');
        exit;
    }
}

function getListAjax() {
    global $error;
    $arr_submit = array(
        array('uid', 'int', true, ''),
        array('cid', 'int', false, ''),
    );

    $frm_submitted = validate_var($arr_submit);

    header("location:" . FULLCAL_URL . "/admin/lists/?action=get_list&uid=" . $frm_submitted['uid'] . "&cid=" . $frm_submitted['cid']);
    exit;

    $arr_mixed_list = Lists::getList($frm_submitted);
    $arr_list = $arr_mixed_list['list'];

    $arr_user = User::getUserById($frm_submitted['uid']);

    $arr_calendars = Calendar::getCalendarsByUserId($frm_submitted['uid']);

    echo json_encode(array('list' => $arr_list));
    exit;
}

//function saveCalendar() {
//
//	global $error;
//	global $obj_smarty;
//
//	$arr_submit 		= array(
//		array('calendar_id',    	'int',   		false, 	-1),
//		array('name',    			'string',   	true, 	''),
//		array('dditems',    		'string',   	true, 	''),
//		array('calendar_color',    	'string',   	true, 	''),
//		array('can_add',    		'bool',   		false, 	0),
//		array('can_edit',    		'bool',   		false, 	0),
//		array('can_change_color', 	'bool',   		false, 	0),
//		array('can_delete',    		'bool',   		false, 	0),
//		array('checkbox_use_color_for_all_events', 'bool', false, 0),
//		array('initial_show',    	'bool',   		false, 	0),
//        array('active',         	'bool',   		false, 	0),
//        
//
//	);
//
//   	$frm_submitted      = validate_var($arr_submit);
//
//	//if(User::isAdminUser($frm_submitted['user_id'])) {
//
//		if(!$error)	 {
//			$bln_success = Calendar::saveCalendar($frm_submitted);
//
//			if(is_string($bln_success)) {
//				echo json_encode(array('success'=>false, 'error'=>$bln_success));exit;
//			}
//
//
//		} else {
//			$obj_smarty->assign('error', $error);
//		}
//	//} else {
//	//	$obj_smarty->assign('error', 'NO rights to change this user');
//	//}
//
//	header('location: '.FULLCAL_URL.'/admin/calendars');
//	exit;
//}

function newCalendar() {
    global $obj_smarty;

    $arr_calendar = array('calendar_color' => '#3366CC');
    $obj_smarty->assign('calendar', $arr_calendar);

    $obj_smarty->assign('active', 'calendar');

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

        $arr_calendars = Calendar::getCalendars();
        $obj_smarty->assign('calendars', $arr_calendars);
        $obj_smarty->assign('active', 'calendars');
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

        $arr_calendars = Calendar::getCalendars();
        $obj_smarty->assign('calendars', $arr_calendars);
        $obj_smarty->assign('active', 'calendars');
    } else {
        $obj_smarty->assign('error', 'NO rights to undelete this calendar');
    }

    $obj_smarty->display(FULLCAL_DIR . '/view/admin_panel.tpl');
    exit;
}

exit;
?>
