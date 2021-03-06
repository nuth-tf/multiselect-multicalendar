<?php

/*
 * Created on 17-okt-2011
 * author Paul Wolbers
 */


require_once '../../include/default.inc.php';


if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'trial':
            trial();
            break;
        case 'convert_trial':
            convertTrial();
            break;
        
    }
} else {

    $letters = 'ABCDEFGHKLMNPRSTUVWYZ';
    $_SESSION['cptch'] = rand(10, 99) . substr($letters, rand(1, 20), 1) . substr($letters, rand(1, 20), 1) . rand(10, 99);
    $_SESSION['c_s_id'] = md5($_SESSION['cptch']);

    $obj_smarty->assign('active', 'register');
    $obj_smarty->display(FULLCAL_DIR . '/register/trial/index.tpl');

    exit;
}

function convertTrial() {
    if(User::isLoggedIn()) {
        $arr_user = User::getUser();
    
        global $obj_smarty;
        
        $obj_smarty->assign('user_id', $arr_user['user_id']);
        $obj_smarty->display(FULLCAL_DIR . '/register/trial/convert_trial.tpl');
    }
    
}

function trial() {
    global $error;
    $use_captcha = true;
    global $obj_smarty;
    $bln_success = false;

    $arr_submit = array(
        array('firstname', 'textonly', true, ''),
        array('lastname', 'textonly', true, ''),
        array('email', 'email', true, ''),
        array('username', 'string', true, ''),
        array('password', 'string', false, ''),
        array('agree_conditions', 'on', true, 'You have to agree to the terms of use'),
    );

    if ($use_captcha) {
        $arr_submit[] = array('captchacode', 'captcha', true, '');
    }
    
    $frm_submitted = validate_var($arr_submit);
    
    if (!$error) {
        // create a user
        
        global $obj_db;
        $arr_user = null;

        // check if username does not exist
        $str_query = 'SELECT * FROM `users` ' .
                ' WHERE `username` = "' . $frm_submitted['username'] . '"';

        $res1 = mysqli_query($obj_db, $str_query);

        if ($res1 !== false) {
            $arr_user_username = mysqli_fetch_array($res1, MYSQLI_ASSOC);
        }

        // check if email does not exist
        $str_query = 'SELECT * FROM `users` ' .
                ' WHERE `email` = "' . $frm_submitted['email'] . '"';

        $res2 = mysqli_query($obj_db, $str_query);

        if ($res2 !== false) {
            $arr_user_email = mysqli_fetch_array($res2, MYSQLI_ASSOC);
        }

        if ((!is_null($arr_user_username) && !empty($res1)) || (!is_null($arr_user_email) && !empty($res2))) {
            if (!is_null($arr_user_username) && !empty($res1)) {
                $obj_smarty->assign('msg', 'Username already exists');
            } else {
                $obj_smarty->assign('msg', 'Email already exists');
            }

            $obj_smarty->assign('form', $frm_submitted);
        } else {
            $added_user = -1;
            $frm_submitted['trial'] = true;
            $bln_success = User::register($frm_submitted, $added_user);
            
            if ($bln_success) {
                if ($added_user > 0) {
                    // set to trial account and set start date
                    $str_query = 'INSERT INTO `subscriptions` ( `user_id` ,`startdate` ,`enddate` ,`subscription_type` ' .
                                ' ) VALUES (' .
                                '"' . $added_user . '",' .
                                '"' . date('Y-m-d H:i:s') . '",' .
                                '"' . date('Y-m-d H:i:s', strtotime('+ 1 MONTH')) . '",' .
                                '"trial" )';

                        $res = mysqli_query($obj_db, $str_query);
    
                }
            }
        }
    } else {
        $obj_smarty->assign('msg', $error);
        $obj_smarty->assign('form', $frm_submitted);
    }

    if ($bln_success) {
        if ($added_user > 0) {
            
        }
        // TODO ? the user is not added to an admingroup

        if (SEND_ACTIVATION_MAIL) {
            $obj_smarty->assign('msg', 'You received an email to activate your account');
        } else {
            header('location:' . FULLCAL_URL);
            exit;
        }

        $obj_smarty->assign('success', true);
    }
    $obj_smarty->display(FULLCAL_DIR . '/register/trial/index.tpl');
    exit;
   
    
    
    
    
    // things to remember
    // when logging in check if the user has a trial account, if so, check if 30 days are over
    // or do this on schedule
    
    // send email when for example 1 week of trial is left
    
    
    
    
}


