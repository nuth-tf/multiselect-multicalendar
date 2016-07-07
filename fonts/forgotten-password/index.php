<?php

/*
 * Created on 17-okt-2011
 * author Paul Wolbers
 */

$current_path = dirname ( realpath ( __FILE__ ) );

require_once '../include/default.inc.php';


if(isset($_GET['action'])) {
  	switch($_GET['action']) {

		case 'reset':
			forgottenPassword();
			break;
		case 'activate':
			activate();
			break;
		case 'change_password':
			changePassword();
			break;
  	}

} else {

	$letters = 'ABCDEFGHKLMNPRSTUVWYZ';
	$_SESSION['cptch'] = rand(10,99).substr($letters,rand(1,20),1).substr($letters,rand(1,20),1).rand(10,99);
	$_SESSION['c_s_id'] = md5($_SESSION['cptch']);


	$obj_smarty->display(FULLCAL_DIR.'/forgotten-password/index.tpl');

//header('location:'.FULLCAL_DIR.'/get-the-app-and-register');
	exit;
}



function forgottenPassword() {
	global $error;
	global $obj_smarty;

	$use_captcha = true;
	$bln_success = false;

	$arr_submit 		= array(
		array('email',    			'string',   		true, 	''),		//email
	);

   	$frm_submitted      = validate_var($arr_submit);

	if(!$error)	 {

		global $obj_db;
		$arr_user = null;

		if(isset($frm_submitted['email'])) {
        	// check if email does exist
    		$str_query = 'SELECT * FROM `users` ' .
    				' WHERE `email` = "' .$frm_submitted['email'] .'"';

    		$res1 = mysqli_query($obj_db, $str_query);

    		if($res1 !== false) {
    			$arr_user = mysqli_fetch_array($res1, MYSQLI_ASSOC);
    		}

    		if(is_null($arr_user) || empty($res1)) {

    			$obj_smarty->assign('msg', 'Unknown emailaddress');
    			$obj_smarty->assign('form', $frm_submitted);

    		} else {
    			$bln_success = User::forgottenPassword($frm_submitted);
    		}
	    } else {
	        $obj_smarty->assign('msg', 'Email is empty');
			$obj_smarty->assign('form', $frm_submitted);
	    }

	} else {
		$obj_smarty->assign('msg', $error);
		$obj_smarty->assign('form', $frm_submitted);
	}

	if($bln_success) {
		$obj_smarty->assign('msg', 'You received an email with a link to reset your password');
		$obj_smarty->assign('success', true);
	}
	$obj_smarty->display(FULLCAL_DIR.'/forgotten-password/index.tpl');
	exit;
}



function changePassword() {
	global $error;
	$use_captcha = true;

	$arr_submit 		= array(
		array('passw1',    			'string',   	true, 	''),
		array('passw2',    			'string',   	true, 	''),
		array('uid',    			'int',   		true, 	''),

	);

   	$frm_submitted      = validate_var($arr_submit);


	if(!$error)	 {
		$bln_success = User::changePassword($frm_submitted);
	}
}

?>
