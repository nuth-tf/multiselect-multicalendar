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

if (!defined('SEND_ACTIVATION_MAIL')) {
    define('SEND_ACTIVATION_MAIL', false);
}


if (User::isLoggedIn()) {
    header("Cache-Control: no-cache, must-revalidate");

    global $obj_smarty;

    $arr_user = User::getUser();
    $bln_user = User::isUser();
    $bln_admin = User::isAdmin();
    $bln_superadmin = User::isSuperAdmin();

    if ($bln_user) {
        header('location: ' . FULLCAL_URL . '/user');
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

        //if($bln_superadmin) {
        //	$arr_users = User::getAdmins(true, true);		// admins of this superadmin
        //	$obj_smarty->assign('users', $arr_users);
        //} else if($bln_admin) {
        $arr_groups = Group::getGroups();
        $obj_smarty->assign('groups', $arr_groups);

        //}
    }
} else {
    $obj_smarty->display(FULLCAL_DIR . '/login.html');
    exit;
}

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'get_group':
            getGroup();
            break;

        case 'save_group':
            saveGroup();
            break;
        case 'new':
            newAvailability();
            break;
        case 'delete':
            deleteGroup();
            break;
        case 'undelete':
            undeleteGroup();
            break;
        case 'create_groups':
            createGroups();
            break;
        default:
            die('no such action available');
    }

    exit;
} else {
    $obj_smarty->assign('active', 'groups');

    $obj_smarty->display(FULLCAL_DIR . '/view/admin_panel.tpl');
    exit;
}

function getGroup() {
    $arr_submit = array(
        array('gid', 'int', true, ''),
    );

    $frm_submitted = validate_var($arr_submit);

    global $obj_smarty;

    $arr_group = Group::getGroup($frm_submitted['gid']);

    $obj_smarty->assign('active', 'group');
    $obj_smarty->assign('group', $arr_group);

    $obj_smarty->display(FULLCAL_DIR . '/view/admin_panel.tpl');
    exit;
}

function saveGroup() {

    global $error;
    global $obj_smarty;

    $arr_submit = array(
        array('group_id', 'int', false, -1),
        array('name', 'string', true, ''),
        array('groupusers', 'string', false, ''),
        array('active', 'bool', false, 0),
        array('description', 'string', false, ''),
    );

    $frm_submitted = validate_var($arr_submit);

    if (!$error || is_null($error)) {
        $bln_success = Group::saveGroup($frm_submitted);

        if (is_string($bln_success)) {
            echo json_encode(array('success' => false, 'save_group_error' => $bln_success));
            exit;
        }
    } else {
        $obj_smarty->assign('save_group_error', $error);
    }

    if (!is_null($error) && $error !== false) {
        // give feedback about the error
        $arr_group = Group::getGroup($frm_submitted['group_id'], true);


        $obj_smarty->assign('active', 'group');
        $obj_smarty->assign('group', $arr_group);

        $obj_smarty->display(FULLCAL_DIR . '/view/admin_panel.tpl');
        exit;
    } else {
        header('location: ' . FULLCAL_URL . '/admin/groups');
        exit;
    }
}

function getUsers() {
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

    if ($bln_superadmin) {
        $arr_users = User::getAdmins(true, true);  // admins of this superadmin
    } else if ($bln_admin) {
        $arr_users = User::getAdminUsers(true, true);  // users of this admin
    }
    return $arr_users;
}

function newAvailability() {
    global $obj_smarty;

    $obj_smarty->assign('active', 'availability');

    $obj_smarty->display(FULLCAL_DIR . '/view/admin_panel.tpl');
}

function deleteGroup() {
    global $error;
    global $obj_smarty;

    $arr_submit = array(
        array('gid', 'int', true, ''),
    );

    $frm_submitted = validate_var($arr_submit);

    $bln_admin = User::isAdmin();
    $bln_superadmin = User::isSuperAdmin();

    if ($bln_admin) {
        // if the user is in the admingroup of the logged in admin
        if (Group::isMyGroup($frm_submitted['gid'])) {
            $bln_success = Group::deleteGroup($frm_submitted['gid']);

            if ($bln_success) {
                $obj_smarty->assign('msg', 'Group deleted succesfully - <a href="' . FULLCAL_URL . '/admin/groups/?action=undelete&gid=' . $frm_submitted['gid'] . '">Undo</a>');
            }
        } else {
            $obj_smarty->assign('error', 'NO rights to delete this group');
        }
    }
    $arr_groups = Group::getGroups();
    $obj_smarty->assign('groups', $arr_groups);
    $obj_smarty->assign('active', 'groups');

    $obj_smarty->display(FULLCAL_DIR . '/view/admin_panel.tpl');
    exit;
}

function undeleteGroup() {
    global $error;
    global $obj_smarty;

    $arr_submit = array(
        array('gid', 'int', true, ''),
    );

    $frm_submitted = validate_var($arr_submit);

    $bln_admin = User::isAdmin();

    if ($bln_admin) {
        if (Group::isMyGroup($frm_submitted['gid'])) {
            $bln_success = Group::undeleteGroup($frm_submitted['gid']);

            if ($bln_success) {
                $obj_smarty->assign('msg', 'Group is back again');
            }

            $arr_groups = Group::getGroups();
            $obj_smarty->assign('groups', $arr_groups);
        } else {
            $obj_smarty->assign('error', 'NO rights to undelete this group');
        }
    }

    $obj_smarty->assign('active', 'groups');

    $obj_smarty->display(FULLCAL_DIR . '/view/admin_panel.tpl');
    exit;
}

exit;
?>
