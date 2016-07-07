<?php
/*
 * Created on 14-sep-2014
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

require_once '../include/default.inc.php';

if(isset($_SESSION['add_user_error'])) {
	$_SESSION['add_user_error'] = '';
}


if(User::isLoggedIn()) {
	header("Cache-Control: no-cache, must-revalidate");

	global $obj_smarty;

	$arr_user 		= User::getUser();
	$bln_user 		= User::isUser();

	$obj_smarty->assign('title', $arr_user['title']);
	$obj_smarty->assign('name', $arr_user['firstname'].' '.(!empty($arr_user['infix']) ? $arr_user['infix'] : '').$arr_user['lastname']);
	$obj_smarty->assign('user', $_SESSION['calendar-uid']['username']);
	$obj_smarty->assign('user_id', $arr_user['user_id']);
	$obj_smarty->assign('is_user', $bln_user);

    $language = Settings::getLanguage($arr_user['user_id']);
	$obj_smarty->assign('language', $language);
    
    
    
} else {
    $obj_smarty->display(FULLCAL_DIR.'/login.html');
    exit;
}


 if(isset($_GET['action'])) {
  	switch($_GET['action']) {
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
		default:
			die('no such action available');
  }

  exit;
} else {
	$obj_smarty->assign('active', 'user');

	$obj_smarty->display(FULLCAL_DIR.'/view/user_panel.tpl');
	exit;
}

function getProfile() {
	$arr_submit 		= array(
		array('uid',    		'int',   	true, 	''),

	);

   	$frm_submitted      = validate_var($arr_submit);

	global $obj_smarty;

	$arr_user 		= User::getUser();

	if($arr_user['user_id'] == $frm_submitted['uid']) {

		$arr_user = User::getUserById($frm_submitted['uid']);

		$arr_birthdate = explode('-', $arr_user['birth_date']);

		$arr_user['birthdate_month'] 	= $arr_user['birth_date'] !== '0000-00-00' ? $arr_birthdate[1] : '';
		$arr_user['birthdate_day'] 		= $arr_user['birth_date'] !== '0000-00-00' ? $arr_birthdate[2] : '';
		$arr_user['birthdate_year'] 	= $arr_user['birth_date'] !== '0000-00-00' ? $arr_birthdate[0] : '';

		unset($arr_user['password']);
		unset($arr_user['birth_date']);


		$obj_smarty->assign('active', 'profile');
		$obj_smarty->assign('profile', $arr_user);

		$obj_smarty->display(FULLCAL_DIR.'/view/user_panel.tpl');
		exit;

	} else {
		$obj_smarty->assign('active', 'profile');
		$obj_smarty->assign('error', 'NO rights to change this user');

		$obj_smarty->display(FULLCAL_DIR.'/view/user_panel.tpl');
		exit;
	}

}

function saveProfile() {

	global $error;
	global $obj_smarty;

	$arr_submit 		= array(
		array('user_id',    		'int',   		true, 	''),
		array('title',              'string',   	false, 	''),
		array('firstname',    		'string',   	false, 	''),
		array('infix',    			'string',   	false, 	''),
		array('lastname',    		'string',   	true, 	''),
		array('country',    		'string',   	false, 	''),
		array('username',    		'string',   	false, 	''),
		array('email',    			'email',   		true, 	''),
		array('birthdate_day',    	'int',   		false, 	''),
		array('birthdate_month',    'int',   		false, 	''),
		array('birthdate_year',    	'int',   		false, 	''),
		array('password',    		'string',   	false, 	''),
		array('confirm',    		'string',   	false, 	''),
	);

   	$frm_submitted      = validate_var($arr_submit);

	$arr_user 		= User::getUser();

	if($arr_user['user_id'] == $frm_submitted['user_id']) {

		if(!$error)	 {
			$bln_success = User::saveProfile($frm_submitted);

			if(is_string($bln_success)) {
				$obj_smarty->assign('save_profile_error', $bln_success);
			} else {
				$obj_smarty->assign('save_profile_success', 'Saved succesfully');
			}

			if(!empty($frm_submitted['password']) && !empty($frm_submitted['confirm'])) {
				if($frm_submitted['password'] === $frm_submitted['confirm']) {

					$frm_submitted['passw1'] = $frm_submitted['password'];
					$frm_submitted['uid'] = $_SESSION['calendar-uid']['uid'];

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
		$obj_smarty->assign('save_profile_error', 'NO rights to change this user');
	}

    $obj_smarty->assign('title', $arr_user['title']);
	$obj_smarty->assign('name', $arr_user['firstname'].' '.(!empty($arr_user['infix']) ? $arr_user['infix'] : '').$arr_user['lastname']);
	
	$obj_smarty->assign('active', 'profile');
	$obj_smarty->assign('profile', $arr_user);
	$obj_smarty->display(FULLCAL_DIR.'/view/user_panel.tpl');

	//header('location: '.FULLCAL_URL.'/user');
	exit;
}

function getSettings() {
	global $obj_smarty;

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
		array('show_notallowed_messages',          'string',   	false, 	'off'),
		array('show_weeknumbers',                   'string',   	false, 	'off'),
		array('send_activation_mail',               'string',   	false, 	'off'),
		//array('users_can_register',                 'string',   	false, 	'off'),
		        
	);
	
	$arr_submit = array();

	foreach($_POST as $key=>$param) {
		if(!empty($key) && $key != 'save-settings' && $key != 'user_id') {
			$arr_submit[] = array($key, 'string', false, $param);
			$_REQUEST[$key] = $param;
		}
	}

	unset($_REQUEST['params']);

    $frm_submitted      = validate_var($arr_submit);



	if(!$error)	 {
		$arr_user 		= User::getUser();

		if($_POST['user_id'] == $arr_user['user_id']) {
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

	$obj_smarty->assign('active', 'settings');
	$obj_smarty->assign('settings', Settings::getSettings($arr_user['user_id']));
	$obj_smarty->display(FULLCAL_DIR.'/view/user_panel.tpl');
	exit;

}

exit;
?>
