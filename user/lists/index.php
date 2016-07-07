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

    $obj_smarty->assign('title', $arr_user['title']);
    $obj_smarty->assign('name', $arr_user['firstname'] . ' ' . (!empty($arr_user['infix']) ? $arr_user['infix'] : '') . $arr_user['lastname']);
    $obj_smarty->assign('user', $_SESSION['calendar-uid']['username']);
    $obj_smarty->assign('user_id', $_SESSION['calendar-uid']['uid']);
    $obj_smarty->assign('is_user', $bln_user);

    $language = Settings::getLanguage($arr_user['user_id']);
    $obj_smarty->assign('language', $language);

    // $arr_calendars = Calendar::getCalendarsOfUser($arr_user['user_id']);
    //$obj_smarty->assign('calendars', $arr_calendars);
    $obj_smarty->assign('selected_calendar', 'all');
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

    // HAS TO BE DIFFERENT, WHEN A USER IS LOGGED IN
    // NO CALENDAR DROPDOWNLIST BUT IN THE TABLE THE SEPARATE CALENDARS

    $arr_mixed_list = Lists::getLists($frm_submitted);

    $obj_smarty->assign('list', $arr_mixed_list['users']);

    if (!empty($frm_submitted['cid'])) {
        $obj_smarty->assign('selected_calendar', $frm_submitted['cid']);
    }
    //$period_startdate = date('Y-m-d', strtotime('-1 YEAR'));
    //$period_enddate = date('Y-m-d');

    $obj_smarty->assign('startdate', $arr_mixed_list['startdate']);
    $obj_smarty->assign('enddate', $arr_mixed_list['enddate']);


    $arr_calendars = Calendar::getCalendarsOfUser($arr_user['user_id']);
    $obj_smarty->assign('calendars', $arr_calendars);

    $obj_smarty->display(FULLCAL_DIR . '/view/user_panel.tpl');
    exit;
}

function getList() {
    global $error;
    $arr_submit = array(
        array('cid', 'string', false, ''),
        array('st', 'string', false, ''),
        array('end', 'string', false, ''),
    );

    $frm_submitted = validate_var($arr_submit);

    global $obj_smarty;



    if (!is_null($error) || !empty($error)) {
        echo ($error);
    } else {

        if (User::isLoggedIn()) {
            $arr_user = User::getUser();

            $arr_mixed_list = Lists::getList($frm_submitted);
            $arr_list = $arr_mixed_list['list'];

            $arr_user = User::getUserById($arr_user['user_id']);

            $arr_calendars = Calendar::getCalendarsByUserId($arr_user['user_id']);
            $obj_smarty->assign('calendars', $arr_calendars);

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

            $obj_smarty->display(FULLCAL_DIR . '/view/user_panel.tpl');
            exit;
        }
    }
}

function getListAjax() {
    global $error;
    $arr_submit = array(
        array('uid', 'int', true, ''),
        array('cid', 'int', false, ''),
    );

    $frm_submitted = validate_var($arr_submit);

    header("location:" . FULLCAL_URL . "/user/lists/?action=get_list&uid=" . $frm_submitted['uid'] . "&cid=" . $frm_submitted['cid']);
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



exit;
?>
