<?php

/*
 * Created on 14-sep-2014
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */


require_once '../include/default.inc.php';


if (User::isLoggedIn()) {
    header("Cache-Control: no-cache, must-revalidate");

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

    if (User::isAdmin()) {

        $arr_users = User::getAdminUsers(true);  // users of this admin
        $obj_smarty->assign('users', $arr_users);

        $arr_current_event = Events::getCurrentEvent();
        $obj_smarty->assign('current_events', $arr_current_event);

        $arr_last_added_events = Events::getLastAddedEvents(5);
        $obj_smarty->assign('last_added_events', $arr_last_added_events);

        $arr_last_loggedin_users = Utils::getLastLoggedinUsers(5);
        $obj_smarty->assign('last_loggedin_users', $arr_last_loggedin_users);

        $obj_smarty->assign('active', 'admin');

        $obj_smarty->display(FULLCAL_DIR . '/view/admin_panel.tpl');
    } else {
        header('location: ' . FULLCAL_URL);
        exit;
    }
} else {
    if (ADMIN_CAN_LOGIN_FROM_ADMIN_URL === true && ALLOW_ACCESS_BY == 'free') {
        $language = Settings::getLanguage();
        $obj_smarty->assign('language', $language);

        $obj_smarty->display(FULLCAL_DIR . '/login.html');
    } else {
        header('location: ' . FULLCAL_URL);
        exit;
    }
}




if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'importUsersFromCsv':
            Utils::importUsersFromCsv();
            break;
        default:
            die('no such action available');
    }


    exit;
} else {
    
}
exit;
?>
