<?php
/*
 * Created on 14-sep-2014
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */


require_once '../../include/default.inc.php';


if(User::isLoggedIn()) {
	header("Cache-Control: no-cache, must-revalidate");

	$arr_user 		= User::getUser();
	$bln_user 		= User::isUser();
	$bln_admin 		= User::isAdmin();
	$bln_superadmin = User::isSuperAdmin();

	$obj_smarty->assign('title', $arr_user['title']);
	$obj_smarty->assign('name', $arr_user['firstname'].' '.(!empty($arr_user['infix']) ? $arr_user['infix'] : '').$arr_user['lastname']);
	$obj_smarty->assign('user', $_SESSION['calendar-uid']['username']);
	$obj_smarty->assign('user_id', $_SESSION['calendar-uid']['uid']);
	$obj_smarty->assign('is_user', $bln_user);
	$obj_smarty->assign('is_admin', $bln_admin);
	$obj_smarty->assign('is_super_admin', $bln_superadmin);

	$language = Settings::getSetting('language', $arr_user['user_id']);
	$obj_smarty->assign('language', $language);

    global $current_languages;
    $obj_smarty->assign('current_languages', $current_languages);
    

} else {
    $obj_smarty->display(FULLCAL_DIR.'/login.html');
    exit;
}

if(isset($_GET['action'])) {
  	switch($_GET['action']) {
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
	$arr_user 		= User::getUser();

	$obj_smarty->assign('active', 'settings');
	$obj_smarty->assign('settings', Settings::getSettings($arr_user['user_id']));
	$obj_smarty->assign('user_id', $arr_user['user_id']);

	$obj_smarty->display(FULLCAL_DIR.'/view/user_panel.tpl');

}



function saveSettings() {
	global $error;
	global $obj_smarty;

    // add the checkbox fields here
	$arr_submit 		= array(
        //array('show_description_field',             'string',   	false, 	'off'),
        //array('show_location_field',                'string',   	false,  'off'),
        //array('show_phone_field',                   'string',   	false, 	'off'),
        //array('show_url_field',                   'string',   	false, 	'off'),
        array('show_am_pm',                         'string',   	false, 	'off'),
        array('show_delete_confirm_dialog',         'string',   	false, 	'off'),
        array('truncate_title',                     'string',   	false, 	'off'),
        array('show_notallowed_messages',           'string',   	false, 	'off'),
        array('show_weeknumbers',                   'string',   	false, 	'off'),
        array('show_public_and_private_separately', 'string',   	false, 	'off'),
        array('pdf_table_look', 'string',   	false, 	'off'),
          
        array('user_id', 'int',   	true, 	-1),
	);
   
	foreach($_POST as $key=>$param) {
		if(!empty($key) && $key != 'save-settings' && $key != 'user_id') {
			$arr_submit[] = array($key, 'string', false, $param);
			$_REQUEST[$key] = $param;
		}
	}

	unset($_REQUEST['params']);

    $frm_submitted      = validate_var($arr_submit);

	$arr_user 		= User::getUser();

	if(!$error)	 {

		if($frm_submitted['user_id'] == $arr_user['user_id']) {
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
	$obj_smarty->display(FULLCAL_DIR.'/view/user_panel.tpl');
	exit;

}

exit;
?>
