<?php

/*
 * Created on 14-sep-2014
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */


require_once '../../include/default.inc.php';

if (User::isLoggedIn()) {
    header("Cache-Control: no-cache, must-revalidate");

    $arr_user = User::getUser();
    $bln_user = User::isUser();
    $bln_admin = User::isAdmin();
    $bln_superadmin = User::isSuperAdmin();

    $language = Settings::getLanguage($arr_user['user_id']);
    $obj_smarty->assign('language', $language);

    if ($bln_user) {
        header('location: ' . FULLCAL_URL . '/user');
        exit;
    } else if ($bln_superadmin) {
        $obj_smarty->assign('active', 'admin');

        $obj_smarty->display(FULLCAL_DIR . '/view/admin_panel.tpl');
        exit;
    }

    $obj_smarty->assign('title', $arr_user['title']);
    $obj_smarty->assign('name', $arr_user['firstname'] . ' ' . (!empty($arr_user['infix']) ? $arr_user['infix'] : '') . $arr_user['lastname']);
    $obj_smarty->assign('user', $_SESSION['calendar-uid']['username']);
    $obj_smarty->assign('user_id', $_SESSION['calendar-uid']['uid']);
    $obj_smarty->assign('is_user', $bln_user);
    $obj_smarty->assign('is_admin', $bln_admin);
    $obj_smarty->assign('is_super_admin', $bln_superadmin);



    $obj_smarty->assign('current_languages', $current_languages);   // global var

    if (User::isAdmin()) {

        $arr_users = User::getAdminUsers(true);  // users of this admin
        $obj_smarty->assign('users', $arr_users);
    }
} else {
    $obj_smarty->display(FULLCAL_DIR . '/login.html');
    exit;
}

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'get_settings':
            getSettings();
            break;
        case 'save_settings':
            saveSettings();
            break;
        default:
            die('no such action available');
    }

    exit;
} else {
    $arr_user = User::getUser();

    $obj_smarty->assign('active', 'settings');
    $obj_smarty->assign('settings', Settings::getSettings($arr_user['user_id']));
    $obj_smarty->assign('user_id', $arr_user['user_id']);

    $obj_smarty->display(FULLCAL_DIR . '/view/admin_panel.tpl');
    exit;
}

function saveSettings() {
    global $error;
    global $obj_smarty;

    // add the checkbox fields here
    $arr_submit = array(
        //array('show_description_field',             'string',   	false, 	'off'),
        //array('show_location_field',                'string',   	false,  'off'),
        //array('show_phone_field',                   'string',   	false, 	'off'),
        //array('show_url_field',                   'string',   	false, 	'off'),
        array('show_am_pm', 'string', false, 'off'),
        array('show_delete_confirm_dialog', 'string', false, 'off'),
        array('truncate_title', 'string', false, 'off'),
        array('show_notallowed_messages', 'string', false, 'off'),
        array('show_weeknumbers', 'string', false, 'off'),
        array('send_activation_mail', 'string', false, 'off'),
        //array('users_can_register',                 'string',   	false, 	'off'),
        array('show_public_and_private_separately', 'string', false, 'off'),
        array('user_id', 'int', true, -1),
        array('pdf_table_look', 'string', false, 'off'),
        array('pdf_show_time_columns', 'string', false, 'off'),
        array('pdf_show_date_on_every_line', 'string', false, 'off'),
        array('pdf_show_logo', 'string', false, 'off'),
        array('pdf_show_custom_dropdown_values', 'string', false, 'off'),
        array('pdf_show_calendarname_each_line', 'string', false, 'off'),
       
        array('pdf_fontweight_bold', 'string', false, 'off'),
        array('pdf_colored_rows', 'string', false, 'off'),
        array('pdf_sorting', 'string', false, 'off'),
        array('show_custom_dropdown1_filter', 'string', false, 'off'),
        array('show_custom_dropdown2_filter', 'string', false, 'off'),
        array('dropdowns_are_linked', 'string', false, 'off'),
        
    );


    foreach ($_POST as $key => $param) {
        if (!empty($key) && $key != 'save-settings' && $key != 'user_id' 
                && $key != 'dropdown_1_item' && $key != 'dropdown_2_item'
                && $key != 'dropdown_1_color' && $key != 'dropdown_2_color') {
            $arr_submit[] = array($key, 'string', false, $param);
            $_REQUEST[$key] = $param;
        }
    }

    unset($_REQUEST['params']);

    $frm_submitted = validate_var($arr_submit);

    $arr_user = User::getUser();

    if (!$error || is_null($error)) {


        if ($frm_submitted['user_id'] == $arr_user['user_id']) {
            unset($frm_submitted['user_id']);

            Settings::saveSettings($frm_submitted, '', $arr_user['user_id']);

            $obj_smarty->assign('save_settings_success', 'Saved succesfully');

            //header('location: '.FULLCAL_URL.'/admin/settings');
            //exit;
        } else {
            $obj_smarty->assign('save_settings_error', 'NO rights to do that');
        }
    } else {
        $obj_smarty->assign('save_settings_error', $error);
    }
    $language = Settings::getLanguage($arr_user['user_id']);
    $obj_smarty->assign('language', $language);
    $obj_smarty->assign('active', 'settings');
    $obj_smarty->assign('settings', Settings::getSettings($arr_user['user_id']));
    $obj_smarty->display(FULLCAL_DIR . '/view/admin_panel.tpl');
    exit;
}

exit;
?>
