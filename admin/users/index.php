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

    $obj_smarty->assign('title', $arr_user['title']);
    $obj_smarty->assign('name', $arr_user['firstname'] . ' ' . (!empty($arr_user['infix']) ? $arr_user['infix'] : '') . $arr_user['lastname']);
    $obj_smarty->assign('user', $_SESSION['calendar-uid']['username']);
    $obj_smarty->assign('user_id', $_SESSION['calendar-uid']['uid']);
    $obj_smarty->assign('is_user', $bln_user);
    $obj_smarty->assign('is_admin', $bln_admin);
    $obj_smarty->assign('is_super_admin', $bln_superadmin);

    $language = Settings::getLanguage($arr_user['user_id']);
    $obj_smarty->assign('language', $language);

    if ($bln_superadmin) {
        $arr_users = User::getAdminsAndUsers();  // admins and users of this superadmin
        $obj_smarty->assign('users', $arr_users);
    } else if ($bln_admin) {
        $arr_users = User::getAdminUsers(true, true);  // users of this admin
        $obj_smarty->assign('users', $arr_users);
    }
} else {
    $obj_smarty->display(FULLCAL_DIR . '/login.html');
    exit;
}

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'get_profile':
            getProfile();
            break;
        case 'get_availibility':
            getAvailibility();
            break;
        case 'save_profile':
            saveProfile();
            break;
        case 'new_user':
            newUser();
            break;
        case 'quick_new_user':
        case 'quick_new_admin':
            quickNewUser();
            break;
        case 'import_users':
            importUsers();
            break;
        case 'upload_file_and_import':
            uploadAndImport();
            break;
        case 'add_user':
        case 'add_admin':
            addUser();
            break;
        case 'quick_add_user':
        case 'quick_add_admin':
            quickAddUser();
            break;
        case 'new_admin':
            newUser();
            break;
        case 'delete':
            deleteUser();
            break;
        case 'undelete':
            undeleteUser();
            break;
        default:
            die('no such action available');
    }

    exit;
} else {
    $obj_smarty->assign('active', 'users');

    $obj_smarty->display(FULLCAL_DIR . '/view/admin_panel.tpl');
    exit;
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

function getProfile() {
    $arr_submit = array(
        array('uid', 'int', true, ''),
    );

    $frm_submitted = validate_var($arr_submit);

    global $obj_smarty;

    //if(User::isAdminUser($frm_submitted['uid'])) {

    $arr_user = User::getUserById($frm_submitted['uid']);

    $arr_birthdate = explode('-', $arr_user['birth_date']);

    $arr_user['birthdate_month'] = $arr_user['birth_date'] !== '0000-00-00' ? $arr_birthdate[1] : '';
    $arr_user['birthdate_day'] = $arr_user['birth_date'] !== '0000-00-00' ? $arr_birthdate[2] : '';
    $arr_user['birthdate_year'] = $arr_user['birth_date'] !== '0000-00-00' ? ($arr_birthdate[0] > 0 ? $arr_birthdate[0] : '') : '';

    unset($arr_user['password']);
    unset($arr_user['birth_date']);


    $obj_smarty->assign('active', 'profile');
    $obj_smarty->assign('subactive', 'profile');
    $obj_smarty->assign('profile', $arr_user);

    $obj_smarty->display(FULLCAL_DIR . '/view/admin_panel.tpl');
    exit;

//	} else {
//		$obj_smarty->assign('active', 'users');
//		$obj_smarty->assign('error', 'NO rights to change this user');
//
//		$obj_smarty->display(FULLCAL_DIR.'/view/admin_panel.tpl');
//		exit;
//	}
}

function getAvailibility() {
    
    $arr_submit = array(
        array('uid', 'int', true, ''),
    );

    $frm_submitted = validate_var($arr_submit);

    global $obj_smarty;
    
    $arr_user = User::getUserById($frm_submitted['uid']);

    $arr_birthdate = explode('-', $arr_user['birth_date']);

    $arr_user['birthdate_month'] = $arr_user['birth_date'] !== '0000-00-00' ? $arr_birthdate[1] : '';
    $arr_user['birthdate_day'] = $arr_user['birth_date'] !== '0000-00-00' ? $arr_birthdate[2] : '';
    $arr_user['birthdate_year'] = $arr_user['birth_date'] !== '0000-00-00' ? ($arr_birthdate[0] > 0 ? $arr_birthdate[0] : '') : '';

    unset($arr_user['password']);
    unset($arr_user['birth_date']);
    
    
    $obj_smarty->assign('active', 'profile');
    $obj_smarty->assign('subactive', 'availibility');
    $obj_smarty->assign('profile', $arr_user);

    $obj_smarty->display(FULLCAL_DIR . '/view/admin_panel.tpl');
    exit;
}

function saveProfile() {

    global $error;
    global $obj_smarty;

    $arr_submit = array(
        array('user_id', 'int', true, ''),
        array('title', 'string', false, ''),
        array('firstname', 'string', false, ''),
        array('infix', 'string', false, ''),
        array('lastname', 'string', true, ''),
        array('country', 'string', false, ''),
        array('username', 'string', true, ''),
        array('email', 'email', true, ''),
        array('birthdate_day', 'int', false, ''),
        array('birthdate_month', 'int', false, ''),
        array('birthdate_year', 'int', false, ''),
        array('password', 'string', false, ''),
        array('confirm', 'string', false, ''),
        array('user_info', 'string', false, ''),
        array('active', 'bool', false, 0),
    );

    $frm_submitted = validate_var($arr_submit);

    if (User::isAdmin() || User::isAdminUser($frm_submitted['user_id'])) {

        if (!$error || is_null($error)) {
            $bln_success = User::adminSaveProfile($frm_submitted);

            if (is_string($bln_success)) {
                echo json_encode(array('success' => false, 'error' => $bln_success));
                exit;
            }

            if (!empty($frm_submitted['password']) && !empty($frm_submitted['confirm'])) {
                if ($frm_submitted['password'] === $frm_submitted['confirm']) {

                    $frm_submitted['passw1'] = $frm_submitted['password'];
                    $frm_submitted['uid'] = $frm_submitted['user_id'];

                    $bln_success = User::changePassword($frm_submitted);
                } else {

                    $obj_smarty->assign('save_profile_error', 'Passwords do not match');

                    exit;
                }
            }
        } else {
            $obj_smarty->assign('save_profile_error', $error);
        }
    } else {
        $error = 'NO rights to change this user';
        $obj_smarty->assign('save_profile_error', $error);
    }

    if (!is_null($error) && $error !== false) {
        // give feedback about the error
        $arr_user = User::getUserById($frm_submitted['user_id']);

        $arr_birthdate = explode('-', $arr_user['birth_date']);

        $arr_user['birthdate_month'] = $arr_user['birth_date'] !== '0000-00-00' ? $arr_birthdate[1] : '';
        $arr_user['birthdate_day'] = $arr_user['birth_date'] !== '0000-00-00' ? $arr_birthdate[2] : '';
        $arr_user['birthdate_year'] = $arr_user['birth_date'] !== '0000-00-00' ? $arr_birthdate[0] : '';

        unset($arr_user['password']);
        unset($arr_user['birth_date']);

        $obj_smarty->assign('active', 'profile');
        $obj_smarty->assign('profile', $arr_user);

        $obj_smarty->display(FULLCAL_DIR . '/view/admin_panel.tpl');
        exit;
    } else {
        header('location: ' . FULLCAL_URL . '/admin/users');
        exit;
    }
}

function newUser() {
    global $obj_smarty;

    $obj_smarty->assign('active', 'new_user');

    $obj_smarty->display(FULLCAL_DIR . '/view/admin_panel.tpl');
}

function importUsers() {
    global $obj_smarty;

    $obj_smarty->assign('active', 'import_users');

    $obj_smarty->display(FULLCAL_DIR . '/view/admin_panel.tpl');
}

function uploadAndImport() {
    Utils::importUsersFromCsv();
}

function quickNewUser() {
    global $obj_smarty;

    $obj_smarty->assign('active', 'quick_new_user');

    $obj_smarty->display(FULLCAL_DIR . '/view/admin_panel.tpl');
}

function quickAddUser() {
    if (User::isLoggedIn() && (User::isAdmin() || User::isSuperAdmin())) {

        global $error;
        $use_captcha = true;
        global $obj_smarty;
        $bln_success = false;
        $msg = '';

        $arr_submit = array(
            array('title', 'string', false, ''),
            array('firstname', 'string', false, ''),
            array('infix', 'string', false, ''),
            array('lastname', 'string', true, ''),
            array('password', 'string', true, ''),
            array('username', 'string', true, ''),
            array('email', 'email', true, '')
        );

        $frm_submitted = validate_var($arr_submit);

        if (!$error || is_null($error)) {

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
                $error = 'Username already exists';
            } else {
                // check mailaddress
                $str_query = 'SELECT * FROM `users` ' .
                        ' WHERE `email` = "' . $frm_submitted['email'] . '"';

                $res2 = mysqli_query($obj_db, $str_query);

                if ($res2 !== false) {
                    $arr_user2 = mysqli_fetch_array($res2, MYSQLI_ASSOC);
                }
                if (!is_null($arr_user2) && !empty($res2)) {
                    $error = 'Email already exists';
                } else {
                    $bln_success = User::adminRegister($frm_submitted, true);

                    if ($bln_success === false) {
                        $error = 'Admin must be logged in';
                    }
                }
            }
        } else {
            //echo $error;
        }

        if ($bln_success) {
            $msg = 'User inserted successfully';
        }
    } else {
        $error = 'No admin is logged in or you have no rights to do this';
    }

    if (!empty($error)) {
        $obj_smarty->assign('active', 'quick_new_user');
        $obj_smarty->assign('error', $error);
        $obj_smarty->assign('values', $frm_submitted);

        $obj_smarty->display(FULLCAL_DIR . '/view/admin_panel.tpl');
        exit;
    } else {
        header('location: ' . FULLCAL_URL . '/admin/users');
        exit;
    }
}

function addUser() {
    if (User::isLoggedIn() && (User::isAdmin() || User::isSuperAdmin())) {
        global $error;
        global $obj_smarty;

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
    } else {
        $error = 'No admin is logged in or you have no rights to do this';
    }
    header('location: ' . FULLCAL_URL . '/admin/users');
    exit;
}

function deleteUser() {
    global $error;
    global $obj_smarty;

    $arr_submit = array(
        array('uid', 'int', true, ''),
    );

    $frm_submitted = validate_var($arr_submit);

    $bln_admin = User::isAdmin();
    $bln_superadmin = User::isSuperAdmin();

    if ($bln_superadmin) {
        if (User::isAdmin($frm_submitted['uid'])) {
            $bln_success = User::deleteAdmin($frm_submitted['uid']);

            if ($bln_success) {
                $obj_smarty->assign('msg', 'Admin deleted succesfully - <a href="' . FULLCAL_URL . '/admin/users/?action=undelete&uid=' . $frm_submitted['uid'] . '">Undo</a>');
            }

            $arr_users = User::getAdmins(true, true);  // admins of this superadmin
            $obj_smarty->assign('users', $arr_users);
        } else {
            $obj_smarty->assign('error', 'NO rights to delete this user');
        }
    } else if ($bln_admin) {
        // if the user is in the admingroup of the logged in admin
        if (User::isAdminUser($frm_submitted['uid'])) {
            $bln_success = User::deleteUser($frm_submitted['uid']);

            if ($bln_success) {
                $obj_smarty->assign('msg', 'User deleted succesfully - <a href="' . FULLCAL_URL . '/admin/users/?action=undelete&uid=' . $frm_submitted['uid'] . '">Undo</a>');
            }

            
            $arr_users = User::getAdminUsers(true, true);  // users of this admin
            $obj_smarty->assign('users', $arr_users);
        } else {
            $obj_smarty->assign('error', 'NO rights to delete this user');
        }
    }
    $obj_smarty->assign('active', 'users');

    $obj_smarty->display(FULLCAL_DIR . '/view/admin_panel.tpl');
    exit;
}

function undeleteUser() {
    global $error;
    global $obj_smarty;

    $arr_submit = array(
        array('uid', 'int', true, ''),
    );

    $frm_submitted = validate_var($arr_submit);

    $bln_admin = User::isAdmin();
    $bln_superadmin = User::isSuperAdmin();

    if ($bln_superadmin) {
        if (User::isAdmin($frm_submitted['uid'])) {
            $bln_success = User::undeleteAdmin($frm_submitted['uid']);

            if ($bln_success) {
                $obj_smarty->assign('msg', 'Admin is back again');
            }

            $arr_users = User::getAdmins(true, true);  // admins of this superadmin
            $obj_smarty->assign('users', $arr_users);
        } else {
            $obj_smarty->assign('error', 'NO rights to undelete this user');
        }
    } else if ($bln_admin) {
        if (User::isAdminUser($frm_submitted['uid'])) {
            $bln_success = User::undeleteUser($frm_submitted['uid']);

            if ($bln_success) {
                $obj_smarty->assign('msg', 'User is back again');
            }

            $arr_users = User::getAdminUsers(true, true);  // users of this admin
            $obj_smarty->assign('users', $arr_users);
        } else {
            $obj_smarty->assign('error', 'NO rights to undelete this user');
        }
    }

    $obj_smarty->assign('active', 'users');

    $obj_smarty->display(FULLCAL_DIR . '/view/admin_panel.tpl');
    exit;
}

exit;
?>
