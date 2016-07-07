<?php

class User {

    /**
     * const usertable
     * When you have an existing database with a table that has also the name 'users', 
     * you can define another name for the calendar's users table. 
     * Do this in /model/users.class.php, /model/settings.class.php and /lib/utils.class.php.
     * 
     * It is not for using your own users table in the calendar
     */
    const table = 'users';

    public static function ipAllowed() {
        if (($_SERVER['REMOTE_ADDR'] == CAL_IP || $_SERVER['REMOTE_ADDR'] == '127.0.0.1')) {
            return true;
        }
        return false;
    }

    public static function canEdit($event_user = 0, $cal_id = 0) {
        $arr_calendar = array();
        if ($cal_id > 0) {
            $arr_calendar = Calendar::getCalendar($cal_id);
        }

        // admin
        if (User::isLoggedIn()) {
            $arr_user = User::getUser();

            if (defined('ADMIN_HAS_FULL_CONTROL') && ADMIN_HAS_FULL_CONTROL && (User::isAdmin() || User::isSuperAdmin())) {
                return true;
            }
        }

        if (!empty($arr_calendar)) {
            if (User::isLoggedIn()) {
                if ($_SESSION['calendar-uid']['uid'] == $event_user) {
                    return true;
                }

                if (isset($arr_calendar['share_type']) && $arr_calendar['share_type'] == 'private_group') {
                    if (Calendar::UserInGroup($arr_calendar, $arr_user['user_id']) && $arr_calendar['can_edit']) {
                        return true;
                    }
                }
            }
            if ($arr_calendar['share_type'] == 'public' && $arr_calendar['can_edit']) {
                return true;
            }
            if ($arr_calendar['share_type'] == 'private_group' && $arr_calendar['can_edit'] && (isset($arr_user['user_id']) && Calendar::UserInGroup($arr_calendar, $arr_user['user_id']))) {
                return true;
            }
            if (!$arr_calendar['can_edit']) {
                return false;
            }
        }

        if (ALLOW_ACCESS_BY == 'ip') {
            if ((defined('CAL_IP') && $_SERVER['REMOTE_ADDR'] == CAL_IP) || $_SERVER['REMOTE_ADDR'] == '127.0.0.1') {
                return true;
            }
        } else if (ALLOW_ACCESS_BY == 'login') {
            
        } else if (ALLOW_ACCESS_BY == 'free') {

            if ($event_user > 0 && ($event_user == IP_AND_FREE_ACCESS_SAVED_USER_ID || $event_user == $_SESSION['calendar-uid']['uid'])) {
                return true;
            }
        } else {
            
        }
        return false;
    }

    public static function canChangeColor($event_user = 0, $cal_id = 0) {
        $arr_calendar = array();
        if ($cal_id > 0) {
            $arr_calendar = Calendar::getCalendar($cal_id);
        }
        if (isset($arr_calendar['share_type']) && $arr_calendar['share_type'] == 'public' && $arr_calendar['can_change_color']) {
            return true;
        }
        if (User::isLoggedIn()) {
            $arr_user = User::getUser();

            if (defined('ADMIN_HAS_FULL_CONTROL') && ADMIN_HAS_FULL_CONTROL && (User::isAdmin() || User::isSuperAdmin())) {
                return true;
            }

            if ($cal_id > 0) {
                if (Calendar::isOwner($cal_id)) {
                    return true;
                }
            }

            if ($arr_calendar['can_change_color'] && isset($arr_calendar['share_type']) && $arr_calendar['share_type'] == 'private_group' && isset($_SESSION['calendar-uid']) && Calendar::UserInGroup($arr_calendar, $_SESSION['calendar-uid']['uid'])) {
                return true;
            }
        } else {
            if ($arr_calendar['can_change_color']) {
                return true;
            }
        }
        return false;
    }

    public static function canDelete($event_user = 0, $cal_id = 0) {
        $arr_calendar = array();
        if ($cal_id > 0) {
            $arr_calendar = Calendar::getCalendar($cal_id);
        }

        if (User::isLoggedIn()) {
            $arr_user = User::getUser();

            if (defined('ADMIN_HAS_FULL_CONTROL') && ADMIN_HAS_FULL_CONTROL && (User::isAdmin() || User::isSuperAdmin())) {
                return true;
            }
        }

        if (!empty($arr_calendar)) {
            if (User::isLoggedIn()) {
                if ($_SESSION['calendar-uid']['uid'] == $event_user) {
                    return true;
                }
            
                if ($arr_calendar['share_type'] == 'private_group' && $arr_calendar['can_delete'] && Calendar::UserInGroup($arr_calendar, $arr_user['user_id'])) {
                    return true;
                }
            }
            if ($arr_calendar['share_type'] == 'public' && $arr_calendar['can_delete']) {
                return true;
            }
            if (!$arr_calendar['can_delete']) {
                return false;
            }
        }

        if (isset($_SESSION['calendar-uid']) && $event_user == $_SESSION['calendar-uid']['uid']) {
            return true;
        } else {
            return false;
        }

        return false;
    }

    public static function getUser() {
        global $obj_db;

        if (!isset($_SESSION['calendar-uid']) && !isset($_SESSION['calendar-uid']['uid'])) {
            return null;
        }
        $str_query = 'SELECT * FROM `' . self::table . '` WHERE user_id = ' . (int) $_SESSION['calendar-uid']['uid'];
        $obj_result = mysqli_query($obj_db, $str_query);
        $arr_user = mysqli_fetch_array($obj_result, MYSQLI_ASSOC);
        if (is_array($arr_user)) {
            unset($arr_user['password']);
            return $arr_user;
        }
        return null;
    }

    public static function getUserById($user_id) {
        global $obj_db;

        $str_query = 'SELECT * FROM `' . self::table . '` WHERE user_id = ' . $user_id;
        $obj_result = mysqli_query($obj_db, $str_query);
        $arr_user = mysqli_fetch_array($obj_result, MYSQLI_ASSOC);

        return $arr_user;
    }

    public static function isOwner() {
        if (User::isLoggedIn()) {

            $arr_user = User::getUser();

            global $obj_db;

            $str_query = 'SELECT * FROM calendars WHERE `creator_id` = ' . $arr_user['user_id'];
            $obj_result = mysqli_query($obj_db, $str_query);

            $arr_calendar = mysqli_fetch_row($obj_result);

            return !empty($arr_calendar);
        }
    }

    public static function isAdminUser($user_id) {
        global $obj_db;

        $str_query = 'SELECT * FROM `' . self::table . '`';

        $str_query .= ' WHERE (`admin_group` = ' . $_SESSION['calendar-uid']['uid'] . ' AND `usertype` = "user" AND `user_id` = ' . $user_id . ') ';

        $obj_result = mysqli_query($obj_db, $str_query);
        $arr_line = mysqli_fetch_array($obj_result, MYSQLI_ASSOC);

        if ($arr_line !== false && !empty($arr_line)) {
            return true;
        } else {
            return false;
        }
    }

    public static function onlyOneAdmin() {
        global $obj_db;

        $str_query = 'SELECT count( * ) FROM `' . self::table . '` WHERE `usertype` = "admin" AND `active` =1';

        $obj_result = mysqli_query($obj_db, $str_query);

        $arr_line = mysqli_fetch_row($obj_result);

        if ($arr_line !== false && !empty($arr_line)) {
            return $arr_line[0] == 1;
        } else {
            return false;
        }
    }

    public static function saveProfile($frm_submitted) {
        global $obj_db;

        $str_query = 'UPDATE `' . self::table . '` SET `firstname` = "' . $frm_submitted['firstname'] . '" ' .
                ' ,`title` = "' . $frm_submitted['title'] . '"' .
                ' ,`infix` = "' . $frm_submitted['infix'] . '"' .
                ' ,`lastname` = "' . $frm_submitted['lastname'] . '"' .
                ' ,`username` = "' . $frm_submitted['username'] . '"' .
                ' ,`country` = "' . $frm_submitted['country'] . '"' .
                ' ,`birth_date` = "' . $frm_submitted['birthdate_year'] . '-' . $frm_submitted['birthdate_month'] . '-' . $frm_submitted['birthdate_day'] . '"' .
                ' ,`email` = "' . $frm_submitted['email'] . '"' .
                ' ,`user_info` = "' . $frm_submitted['user_info'] . '"' .
                ' WHERE `user_id` = ' . (int) $_SESSION['calendar-uid']['uid'];

        $res = mysqli_query($obj_db, $str_query);


        return $res;
    }

    public static function adminSaveProfile($frm_submitted) {
        global $obj_db;

        $str_query = 'UPDATE `' . self::table . '` SET `firstname` = "' . $frm_submitted['firstname'] . '" ' .
                ' ,`infix` = "' . $frm_submitted['infix'] . '"' .
                ' ,`title` = "' . $frm_submitted['title'] . '"' .
                ' ,`lastname` = "' . $frm_submitted['lastname'] . '"' .
                ' ,`username` = "' . $frm_submitted['username'] . '"' .
                ' ,`country` = "' . $frm_submitted['country'] . '"' .
                ' ,`birth_date` = "' . $frm_submitted['birthdate_year'] . '-' . $frm_submitted['birthdate_month'] . '-' . $frm_submitted['birthdate_day'] . '"' .
                ' ,`email` = "' . $frm_submitted['email'] . '"' .
                ' ,`user_info` = "' . $frm_submitted['user_info'] . '"' .
                ' ,`active` = ' . $frm_submitted['active'] .
                ' WHERE `user_id` = ' . (int) $frm_submitted['user_id'];

        $res = mysqli_query($obj_db, $str_query);


        return $res;
    }

    public static function addUser($frm_submitted) {
        global $obj_db;

        $str_query = 'SELECT * FROM `' . self::table . '` WHERE username = "' . (!empty($frm_submitted['username']) ? $frm_submitted['username'] : $frm_submitted['email']) . '"';
        $obj_result = mysqli_query($obj_db, $str_query);
        $arr_user = mysqli_fetch_array($obj_result, MYSQLI_ASSOC);

        if ($obj_result !== false && !empty($arr_user) && $arr_user !== false) {
            if (SHOW_USERNAME_IN_FORM && !empty($frm_submitted['username'])) {
                return 'Username already exists';
            } else {
                return 'Username (emailaddress) already exists';
            }
        }

        $str_query = 'SELECT * FROM `' . self::table . '` WHERE email = "' . $frm_submitted['email'] . '"';
        $obj_result2 = mysqli_query($obj_db, $str_query);
        $arr_user2 = mysqli_fetch_array($obj_result2, MYSQLI_ASSOC);

        if ($obj_result2 !== false && !empty($arr_user2) && $arr_user2 !== false) {
            return 'Emailaddress already exists';
        }

        // generate random password
        $password = Utils::generatePassword();

        $bln_insert = false;

        if (User::isSuperAdmin()) {
            $usertype = 'admin';
            $admin_group = $_SESSION['calendar-uid']['uid'];
            $bln_insert = true;
        } else if (User::isAdmin()) {
            $usertype = 'user';
            $admin_group = $_SESSION['calendar-uid']['uid'];
            $bln_insert = true;
        }

        if ($bln_insert) {
            $send_activation_mail = Settings::getSetting('send_activation_mail', $admin_group);
            $bln_send_activation_mail = $send_activation_mail == 'on';

            $str_query = 'INSERT INTO `' . self::table . '` ( `title`, `firstname` ,`infix` ,`lastname` ,`username`,`password` ,`email` ,`registration_date` ,' .
                    '`birth_date`, `active`, `ip`, `country`, `country_code`, `usertype`, `admin_group`) VALUES (' .
                    '"' . $frm_submitted['title'] . '",' .
                    '"' . $frm_submitted['firstname'] . '",' .
                    '"' . $frm_submitted['infix'] . '",' .
                    '"' . $frm_submitted['lastname'] . '",' .
                    '"' . (isset($frm_submitted['username']) && !empty($frm_submitted['username']) ? $frm_submitted['username'] : $frm_submitted['email']) . '",' .
                    '"' . self::getPasswordHashcode($password) . '",' .
                    '"' . $frm_submitted['email'] . '",' .
                    'NOW(),' .
                    '"",' .
                    ($bln_send_activation_mail ? '0, ' : '1,') .
                    '"",' .
                    '"",' .
                    '"",' .
                    '"' . $usertype . '",' .
                    $admin_group . ')';

            $res = mysqli_query($obj_db, $str_query);

            $int_user_id = mysqli_insert_id($obj_db);

            $hash_code = self::getUserHashcode($int_user_id);

            if ($bln_send_activation_mail) {
                $str_query = 'UPDATE `' . self::table . '` SET user_hash = "' . $hash_code . '" ' .
                        ' WHERE `user_id` = ' . $int_user_id;

                $res2 = mysqli_query($obj_db, $str_query);
            }

            if ($res !== false) {
                $admin_mail = '';
                if (defined('ADMIN_EMAILADDRESS')) {
                    $admin_mail = ADMIN_EMAILADDRESS;
                }

                if (User::isSuperAdmin()) {
                    $bln_send = Utils::sendMail('add_admin', $frm_submitted['email'], $password, $frm_submitted);

                    if ($frm_submitted['copy_to_admin'] && !empty($admin_mail)) {
                        Utils::sendMail('copy_to_admin_admin_created', $admin_mail, $password, $frm_submitted);
                    }

                    // insert default settings
                    settings::saveDefaultSettings($int_user_id);
                } else if (User::isAdmin()) {
                    $bln_send = Utils::sendMail('add_user', $frm_submitted['email'], $password, $frm_submitted, $int_user_id, $hash_code);

                    if ($frm_submitted['copy_to_admin'] && !empty($admin_mail)) {
                        Utils::sendMail('copy_to_admin_user_created', $admin_mail, $password, $frm_submitted);
                    }
                }

                if ($bln_send) {
                    if (defined('SHOW_CREATED_PASSWORD_WHEN_ADMIN_ADDS_USER') && SHOW_CREATED_PASSWORD_WHEN_ADMIN_ADDS_USER) {
                        return array('insert' => true, 'mail' => 'send', 'password' => $password);
                    } else {
                        return array('insert' => true, 'mail' => 'send');
                    }
                } else {
                    if (defined('SHOW_CREATED_PASSWORD_WHEN_ADMIN_ADDS_USER') && SHOW_CREATED_PASSWORD_WHEN_ADMIN_ADDS_USER) {
                        return array('insert' => true, 'mail' => 'notsend', 'password' => $password);
                    } else {
                        return array('insert' => true, 'mail' => 'notsend');
                    }
                }
            } else {
                return array('insert' => false, 'mail' => 'notsend');
            }
        } else {
            return array('insert' => false, 'mail' => 'notsend', 'error' => 'You have no admin rights!');
        }
    }

    public static function deleteUser($int_user_id) {
        global $obj_db;

        $str_query = 'UPDATE `' . self::table . '` SET `deleted` = 1, `active` = 0 WHERE `user_id` = ' . $int_user_id . ' AND `usertype` = "user" AND `admin_group` = ' . $_SESSION['calendar-uid']['uid'];

        $obj_result = mysqli_query($obj_db, $str_query);

        if ($obj_result !== false) {
            return true;
        }
        return false;
    }

    public static function undeleteUser($int_user_id) {
        global $obj_db;

        $str_query = 'UPDATE `' . self::table . '` SET `deleted` = 0 WHERE `user_id` = ' . $int_user_id . ' AND `usertype` = "user" AND `admin_group` = ' . $_SESSION['calendar-uid']['uid'];

        $obj_result = mysqli_query($obj_db, $str_query);

        if ($obj_result !== false) {
            return true;
        }
        return false;
    }

    public static function deleteAdmin($int_user_id) {
        global $obj_db;

        $str_query = 'UPDATE `' . self::table . '` SET `deleted` = 1, `active` = 0 WHERE `user_id` = ' . $int_user_id . ' AND `usertype` = "admin"';

        $obj_result = mysqli_query($obj_db, $str_query);

        if ($obj_result !== false) {
            return true;
        }
        return false;
    }

    public static function undeleteAdmin($int_user_id) {
        global $obj_db;

        $str_query = 'UPDATE `' . self::table . '` SET `deleted` = 0 WHERE `user_id` = ' . $int_user_id . ' AND `usertype` = "admin"';

        $obj_result = mysqli_query($obj_db, $str_query);

        if ($obj_result !== false) {
            return true;
        }
        return false;
    }

    public static function getUsers($exclude_admins = false, $bln_active=true) {
        global $obj_db;

        $arr_users = array();

        $str_query = 'SELECT user_id,title, concat_ws(" ",firstname,infix,lastname) as fullname FROM `' . self::table . '` WHERE 1';

        if ($exclude_admins) {
            $str_query .= ' AND `usertype` = "user"';
        }
        if($bln_active) {
            $str_query .= ' AND `deleted` = 0 AND `active` = 1';
        }
        
        $str_query .= ' ORDER BY `lastname`';

        $obj_result = mysqli_query($obj_db, $str_query);

        while ($arr_line = mysqli_fetch_array($obj_result, MYSQLI_ASSOC)) {
            $arr_users[] = $arr_line;
        }
        return $arr_users;
    }

    public static function getAdminsAndUsers() {
        global $obj_db;

        $arr_users = array();

        $str_query = 'SELECT `' . self::table . '`.*, concat_ws(" ",firstname,infix,lastname) as fullname FROM `' . self::table . '`';

        $str_query .= ' WHERE `deleted` = 0 ORDER BY `usertype`,`lastname`';

        $obj_result = mysqli_query($obj_db, $str_query);

        while ($arr_line = mysqli_fetch_array($obj_result, MYSQLI_ASSOC)) {
            if (User::isSuperAdmin($arr_line['user_id'])) {
                $arr_line['superadmin'] = true;
            }
            if (User::isAdmin($arr_line['user_id'])) {
                $arr_line['admin'] = true;
            }

            if(defined('SUBSCRIPTIONS')) {
                if(SUBSCRIPTIONS === true) {
                    $arr_subscription = User::getSubscriptionByUserId($arr_line['user_id']);
            
                    if(!empty($arr_subscription)) {
                        $arr_line['has_subscription'] = true;
                        $arr_line['subscription'] = $arr_subscription;
                    } 
                }
            }
            
            
            $arr_users[] = $arr_line;
        }
        return $arr_users;
    }

    /**
     * 
     * @global type $obj_db
     * @param type $bln_include_myself
     * @param type $bln_complete
     * @return boolean
     */
    public static function getAdminUsers($bln_include_myself = false, $bln_complete = false) {
        global $obj_db;

        $arr_users = array();

        if ($bln_complete) {
            $str_query = 'SELECT `' . self::table . '`.*, concat_ws(" ",firstname,infix,lastname) as fullname FROM `' . self::table . '`';
        } else {
            $str_query = 'SELECT user_id, concat_ws(" ",firstname,infix,lastname) as fullname FROM `' . self::table . '`';
        }

        $str_query .= ' WHERE (`admin_group` = ' . $_SESSION['calendar-uid']['uid'] . ' AND `usertype` = "user" AND `deleted` = 0) ';

        if ($bln_include_myself) {
            $str_query .= ' OR user_id = ' . $_SESSION['calendar-uid']['uid'];
        }

        $obj_result = mysqli_query($obj_db, $str_query);

        while ($arr_line = mysqli_fetch_array($obj_result, MYSQLI_ASSOC)) {
            if (User::isAdmin($arr_line['user_id'])) {
                $arr_line['admin'] = true;
            }

            $arr_users[] = $arr_line;
        }
        return $arr_users;
    }

    public static function getAdmins($bln_include_myself = false, $bln_complete = false) {
        global $obj_db;

        $arr_users = array();

        if ($bln_complete) {
            $str_query = 'SELECT `' . self::table . '`.*, concat_ws(" ",firstname,infix,lastname) as fullname FROM `' . self::table . '`';
        } else {
            $str_query = 'SELECT user_id, concat_ws(" ",firstname,infix,lastname) as fullname FROM `' . self::table . '`';
        }

        $str_query .= ' WHERE `usertype` = "admin" AND `deleted` = 0 ';

        if ($bln_include_myself) {
            $str_query .= ' OR user_id = ' . $_SESSION['calendar-uid']['uid'];
        }

        $obj_result = mysqli_query($obj_db, $str_query);

        while ($arr_line = mysqli_fetch_array($obj_result, MYSQLI_ASSOC)) {
            if (User::isAdmin($arr_line['user_id'])) {
                $arr_line['admin'] = true;
            }
            if (User::isSuperAdmin($arr_line['user_id'])) {
                $arr_line['superadmin'] = true;
            }

            $arr_users[] = $arr_line;
        }
        return $arr_users;
    }

    public static function getUserHashcode($uid) {
        $salt1 = sha1('356432');
        $salt2 = sha1('83455');

        return md5($salt1 . $uid . $salt2);
    }

    public static function UserAuthenticated($username, $password, &$int_user_id) {
        $passwordhash = self::getPasswordHashcode($password);

        global $obj_db;

        $str_query = 'SELECT `user_id`, `firstname`, `lastname` FROM `' . self::table . '` WHERE `username` = "' . mysqli_real_escape_string($obj_db, $username) . '" AND `password` = "' . mysqli_real_escape_string($obj_db, $passwordhash) . '"';

        $obj_result = mysqli_query($obj_db, $str_query);
        $arr_line = mysqli_fetch_array($obj_result, MYSQLI_ASSOC);

        if ($arr_line !== false && !empty($arr_line)) {
            $int_user_id = $arr_line['user_id'];
            return true;
        } else {
            return false;
        }
    }

    public static function getSessionHashcode($uid, $username) {
        $salt1 = sha1('89275');

        return md5($uid . $salt1 . date('w') . $username);
    }

    public static function createHashcode($pw) {
        $salt1 = sha1('356432');
        $salt2 = sha1('83455');

        return md5($salt1 . $pw . $salt2);
    }

    public static function getPasswordHashcode($pw) {
        $salt1 = sha1('376455');
        $salt2 = sha1('34675');

        return md5($salt1 . $pw . $salt2);
    }

    public static function adminRegister($frm_submitted, $bln_activate = false) {
        global $obj_db;

        $bln_insert = false;

        if (User::isSuperAdmin()) {
            $usertype = 'admin';
            $admin_group = $_SESSION['calendar-uid']['uid'];
            $bln_insert = true;
        } else if (User::isAdmin()) {
            $usertype = 'user';
            $admin_group = $_SESSION['calendar-uid']['uid'];
            $bln_insert = true;
        }

        if ($bln_insert) {

            $str_query = 'INSERT INTO `' . self::table . '` (`title`, `firstname` ,`infix` ,`lastname` ,`username`,`password` ,`email` ,`registration_date` ,' .
                    '`birth_date`, `active`, `ip`, `country`, `country_code`, `usertype`, `admin_group`) VALUES (' .
                    '"' . $frm_submitted['title'] . '",' .
                    '"' . $frm_submitted['firstname'] . '",' .
                    '"' . $frm_submitted['infix'] . '",' .
                    '"' . $frm_submitted['lastname'] . '",' .
                    '"' . $frm_submitted['username'] . '",' .
                    '"' . self::getPasswordHashcode($frm_submitted['password']) . '",' .
                    '"' . $frm_submitted['email'] . '",' .
                    'NOW(),' .
                    '"",' .
                    '1,' .
                    '"' . $_SERVER['REMOTE_ADDR'] . '",' .
                    '"",' .
                    '"",' .
                    '"' . $usertype . '",' .
                    $admin_group . ')';

            $res = mysqli_query($obj_db, $str_query);
            return $res;
        }
        return false;
    }

    public static function register($frm_submitted, &$added_user = -1) {

        global $obj_db;

        $str_query = 'INSERT INTO `' . self::table . '` ( `title`, `firstname` ,`infix` ,`lastname` ,`username` ,`email` ,`registration_date` ,' .
                '`birth_date`, `ip`, `country`, `country_code`,`trial` ) VALUES (' .
                '"' . $frm_submitted['title'] . '",' .
                '"' . $frm_submitted['firstname'] . '",' .
                '"' . $frm_submitted['infix'] . '",' .
                '"' . $frm_submitted['lastname'] . '",' .
                '"' . $frm_submitted['username'] . '",' .
                '"' . $frm_submitted['email'] . '",' .
                'NOW(),' .
                '"' . date('Y-m-d', strtotime($frm_submitted['year'] . '/' . $frm_submitted['month'] . '/' . $frm_submitted['day'])) . '",' .
                '"' . $_SERVER['REMOTE_ADDR'] . '",' .
                '"",' .
                '"",' .
                (isset($frm_submitted['trial']) && $frm_submitted['trial'] === true ? 1 : 0) . ' )';

        $res = mysqli_query($obj_db, $str_query);

        $int_user_id = mysqli_insert_id($obj_db);

        if ($int_user_id > 0) {

            $added_user = $int_user_id;

            /*
             * generate hash
             */
            $hash_code = self::getUserHashcode($int_user_id);

            $str_query = 'UPDATE `' . self::table . '` SET `user_hash` = "' . $hash_code . '" WHERE user_id = ' . $int_user_id;

            $res = mysqli_query($obj_db, $str_query);

            $send_activation_mail = Settings::getAdminSetting('send_activation_mail', $int_user_id);
            $bln_send_activation_mail = $send_activation_mail == 'on';

            if ($bln_send_activation_mail) {

                /*
                 * send mail to new user
                 */

                if (defined('ACTIVATION_MAIL_SUBJECT') && ACTIVATION_MAIL_SUBJECT !== '') {
                    $subject = ACTIVATION_MAIL_SUBJECT;

                    $subject = str_replace('%USERNAME%', $frm_submitted['username'], $subject);
                } else {
                    $subject = 'Registration for ' . CALENDAR_TITLE;
                }

                $message = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">' .
                        '<html>' .
                        '<head></head>' .
                        '<body>' .
                        $message = 'Thank you for registering. <br /><br />' .
                        'To confirm the registration click on this link: <br />' .
                        '<a href="' . FULLCAL_URL . '/?action=activate&uid=' . $int_user_id . '&hash=' . $hash_code . '">' . FULLCAL_URL . '/?action=activate&uid=' . $int_user_id . '&hash=' . $hash_code . '</a><br /><br />' .
                        '<br />If your browser doesn\'t automatically open, paste the link in your browser ' .
                        '</body>' .
                        '</html>';

                $headers = 'MIME-Version: 1.0' . "\r\n";
                $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                $headers .= 'From: ' . FROM_EMAILADDRESS . "\r\n";
                //$headers .= 'Bcc: your@email.com' . "\r\n";


                if (mail($frm_submitted['email'], $subject, $message, $headers)) {

                    unset($_SESSION['c_s_id']);
                    unset($_SESSION['cptch']);
                    //	error_log('registratiemail succesvol verstuurd naar: '. $frm_submitted['email']);
                    return true;
                } else {

                    echo 'Error while sending the email. You can activate by clicking this link:  <a href="' . FULLCAL_URL . '/?action=activate&uid=' . $int_user_id . '&hash=' . $hash_code . '">' . FULLCAL_URL . '/?action=activate&uid=' . $int_user_id . '&hash=' . $hash_code . '</a>';
                }
            } else {
                $str_query = 'UPDATE `' . self::table . '` SET `password` = "' . self::getPasswordHashcode($frm_submitted['password']) . '", `active` = 1 WHERE user_id = ' . $int_user_id;

                $res = mysqli_query($obj_db, $str_query);

                return true;
            }
        } else {
            echo 'something went wrong, the new user could not be inserted.';
        }
    }

    public static function isTrial($int_user_id) {
        global $obj_db;
        
        $str_query = 'SELECT * FROM `' . self::table . '` WHERE `user_id` = ' . $int_user_id. ' AND `deleted` = 0 AND `active` = 1';
        $obj_result = mysqli_query($obj_db, $str_query);
        $arr_line = mysqli_fetch_array($obj_result, MYSQLI_ASSOC);

        if ($arr_line !== false && !is_null($arr_line) && !empty($arr_line) && !empty($arr_line['user_id'])) {
            if((bool)$arr_line['trial']) {
                return true;
            }
        }
        return false;
    }
    
    public static function login($frm_submitted) {


        global $obj_db;
        $msg = '';

        if (empty($frm_submitted['usern']) && empty($frm_submitted['passw'])) {
            $msg = 'both fields are empty';
        } else if (empty($frm_submitted['usern'])) {
            $msg = 'username is empty';
        } else if (empty($frm_submitted['passw'])) {
            $msg = 'password is empty';
        } else {

            $pasword_hash = self::getPasswordHashcode($frm_submitted['passw']);

            $str_query = 'SELECT `user_id`, `username`, `trial` FROM `' . self::table . '` WHERE `username` = "' . mysqli_real_escape_string($obj_db, $frm_submitted['usern']) . '" AND `password` = "' . mysqli_real_escape_string($obj_db, $pasword_hash) . '" AND `deleted` = 0 AND `active` = 1';
            $obj_result = mysqli_query($obj_db, $str_query);
            $arr_line = mysqli_fetch_array($obj_result, MYSQLI_ASSOC);

            if ($arr_line !== false && !is_null($arr_line) && !empty($arr_line) && !empty($arr_line['user_id'])) {
                
                if(defined('SUBSCRIPTIONS')) {
                    if(SUBSCRIPTIONS === true) {
                        $arr_subscription = Subscription::getSubscriptionByUserId($arr_line['user_id']);
                        
                        if(!empty($arr_subscription) && isset($arr_subscription['enddate'])) {
                            if(time() > strtotime($arr_subscription['enddate'])) {
                                if((bool)$arr_line['trial']) {
                                    $msg = 'Trial has expired';
                                } else {
                                    $msg = 'Subscription has expired';
                                }
                                return $msg;
                            }
                        }
                        
                    }
                }
                
                $_SESSION['calendar-uid'] = array();
                $_SESSION['calendar-uid']['uid'] = $arr_line['user_id'];
                $_SESSION['calendar-uid']['username'] = $arr_line['username'];
                $_SESSION['calendar-uid']['hash'] = md5($arr_line['user_id'] . sha1('89275') . date('w') . $arr_line['username']);
                
                // save to log
                Utils::addLog('login', $arr_line['user_id']);
            } else {
                $msg = 'User not found !';
            }
        }

        return $msg;
    }

    public static function activate($frm_submitted, $bln_only_forgotten_password = false) {
        global $obj_db;
        global $obj_smarty;

        $str_query = 'SELECT * FROM `' . self::table . '` ' .
                ' WHERE `user_id` = ' . $frm_submitted['uid'] .
                ' AND `user_hash` = "' . $frm_submitted['hash'] . '"';

        $res = mysqli_query($obj_db, $str_query);

        if ($res !== false) {

            if ($bln_only_forgotten_password) {

                $obj_smarty->assign('forgotten_password', true);

                // set active and remove user_hash
                $str_query = 'UPDATE `' . self::table . '` SET user_hash = "" ' .
                        ' WHERE `user_id` = ' . $frm_submitted['uid'] .
                        ' AND `user_hash` = "' . $frm_submitted['hash'] . '"';

                $res5 = mysqli_query($obj_db, $str_query);
            } else {
                // set active and remove user_hash
                $str_query = 'UPDATE `' . self::table . '` SET `active` = 1 , `registration_date` = NOW(), user_hash = "" ' .
                        ' WHERE `user_id` = ' . $frm_submitted['uid'] .
                        ' AND `user_hash` = "' . $frm_submitted['hash'] . '"';

                $res2 = mysqli_query($obj_db, $str_query);

                if ($res2 !== false) {
                    $arr_user = mysqli_fetch_array($res, MYSQLI_ASSOC);
                }
            }

            $obj_smarty->assign('uid', $frm_submitted['uid']);
            $obj_smarty->display(FULLCAL_DIR . '/activation/index.tpl');
            exit;
        }
    }

    public static function forgottenPassword($frm_submitted) {

        $int_user_id = 0;
        global $obj_db;

        $str_query = 'SELECT `user_id`, `firstname`, `lastname` FROM `' . self::table . '` WHERE `email` = "' . mysqli_real_escape_string($obj_db, $frm_submitted['email']) . '"';

        $obj_result = mysqli_query($obj_db, $str_query);
        $arr_line = mysqli_fetch_array($obj_result, MYSQLI_ASSOC);

        if ($arr_line !== false && !empty($arr_line)) {
            $int_user_id = $arr_line['user_id'];
        }

        if ($int_user_id > 0) {

            /*
             * generate hash
             */
            $hash_code = self::getUserHashcode($int_user_id);

            $str_query = 'UPDATE `' . self::table . '` SET `user_hash` = "' . $hash_code . '" WHERE user_id = ' . $int_user_id;

            $res = mysqli_query($obj_db, $str_query);

            $send_activation_mail = Settings::getAdminSetting('send_activation_mail', $int_user_id);
            $bln_send_activation_mail = $send_activation_mail == 'on';

            if ($bln_send_activation_mail) {
                /*
                 * send mail to new user
                 */

                if (defined('RESET_PASSWORD_MAIL_SUBJECT') && RESET_PASSWORD_MAIL_SUBJECT !== '') {
                    $subject = RESET_PASSWORD_MAIL_SUBJECT;
                } else {
                    $subject = 'Reset your password for ' . CALENDAR_TITLE;
                }

                $message = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">' .
                        '<html>' .
                        '<head></head>' .
                        '<body>' .
                        'You requested to reset your password<br /><br /><br />' .
                        'You can do this by clicking on this link:<br />' .
                        '<a href="' . FULLCAL_URL . '/?action=reset_password&uid=' . $int_user_id . '&hash=' . $hash_code . '">' . FULLCAL_URL . '/?action=reset_password&uid=' . $int_user_id . '&hash=' . $hash_code . '</a>' .
                        '<br /><br />If your browser doesn\'t automatically open, paste the link in your browser ' .
                        '</body>' .
                        '</html>';

                $headers = 'MIME-Version: 1.0' . "\r\n";
                $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                $headers .= 'From: ' . FROM_EMAILADDRESS . "\r\n";

                if (mail($frm_submitted['email'], $subject, $message, $headers)) {

                    return true;
                } else {

                    echo 'Something went wrong, check your emailaddress or try again later';
                }
            } else {
                // for testing
                echo 'contact the admin, or check "send activation mail" in the settings to send the email';
                //echo '<a href="'.FULLCAL_URL.'/?action=reset_password&uid='.$int_user_id.'&hash='.$hash_code.'">'.FULLCAL_URL.'/?action=reset_password&uid='.$int_user_id.'&hash='.$hash_code.'</a>';
            }
        }
    }

    public static function changePassword($frm_submitted) {
        global $obj_db;

        $str_query = 'UPDATE `' . self::table . '` SET `password` = "' . self::getPasswordHashcode($frm_submitted['passw1']) . '" ' .
                ' WHERE `user_id` = ' . $frm_submitted['uid'];

        $res = mysqli_query($obj_db, $str_query);

        return $res !== false;
    }

    public static function isLoggedIn() {
        $bln_not_expired = false;
        $bln_loggedin = (isset($_SESSION['calendar-uid']) && isset($_SESSION['calendar-uid']['uid']) && isset($_SESSION['calendar-uid']['username']) && isset($_SESSION['calendar-uid']['hash']));
        //&& (!LOGINSESSION_NOT_EXPIRES || self::getSessionHashcode($_SESSION['calendar-uid']['uid'], $_SESSION['calendar-uid']['username']) == $_SESSION['calendar-uid']['hash'])) ;

        if ($bln_loggedin) {
            if (!LOGINSESSION_NOT_EXPIRES) {
                $bln_not_expired = self::getSessionHashcode($_SESSION['calendar-uid']['uid'], $_SESSION['calendar-uid']['username']) == $_SESSION['calendar-uid']['hash'];
            } else {
                $bln_not_expired = true;
            }
        }

        if ($bln_loggedin && !is_null(self::getUser()) && $bln_not_expired) {
            return true;
        }
        return false;
    }

    public static function checkLoggedIn() {
        if (!self::isLoggedIn()) {
            header("location: " . FULLCAL_URL . "/login.html");
            exit;
        }
    }

    public static function isAdmin($int_user_id = -1) {
        global $obj_db;

        if ($int_user_id == -1) {
            if (User::isLoggedIn()) {
                $int_user_id = $_SESSION['calendar-uid']['uid'];
            } else {
                return false;
            }
        }

        $str_query = 'SELECT `user_id` FROM `' . self::table . '` WHERE `user_id` = ' . $int_user_id . ' AND (`usertype` = "admin" OR `usertype` = "superadmin")';

        $obj_result = mysqli_query($obj_db, $str_query);
        $arr_line = mysqli_fetch_array($obj_result, MYSQLI_ASSOC);


        if ($arr_line !== false && !empty($arr_line)) {
            return true;
        }

//  		if(defined('ADMIN_USER_ID')) {
//			$admin_user_id = ADMIN_USER_ID;
//			if(!empty($admin_user_id)) {
//				if(strstr(ADMIN_USER_ID, ',')) {
//					$arr_admin_ids = explode(',', ADMIN_USER_ID);
//					if(in_array($_SESSION['calendar-uid']['uid'], $arr_admin_ids)) {
//						return true;
//					}
//				} else {
//					if( (int) $_SESSION['calendar-uid']['uid'] === (int) ADMIN_USER_ID) {
//						return true;
//					}
//				}
//			}
//
//  		}
        return false;
    }

    public static function isSuperAdmin($int_user_id = -1) {
        global $obj_db;

        if ($int_user_id == -1) {
            if (User::isLoggedIn()) {
                $int_user_id = $_SESSION['calendar-uid']['uid'];
            } else {
                return false;
            }
        }

        $str_query = 'SELECT `user_id` FROM `' . self::table . '` WHERE `user_id` = ' . $int_user_id . ' AND `usertype` = "superadmin"';

        $obj_result = mysqli_query($obj_db, $str_query);
        $arr_line = mysqli_fetch_array($obj_result, MYSQLI_ASSOC);


        if ($arr_line !== false && !empty($arr_line)) {
            return true;
        }
        return false;
    }

    public static function isUser($user_id = -1) {
        global $obj_db;

        if ($user_id == -1) {
            $user_id = $_SESSION['calendar-uid']['uid'];
        }

        $str_query = 'SELECT `user_id` FROM `' . self::table . '` WHERE `user_id` = ' . $user_id . ' AND `deleted` = 0 AND `usertype` = "user"';

        $obj_result = mysqli_query($obj_db, $str_query);
        $arr_line = mysqli_fetch_array($obj_result, MYSQLI_ASSOC);


        if ($arr_line !== false && !empty($arr_line)) {
            return true;
        }
        return false;
    }
    
    public static function getSubscriptionByUserId($user_id = -1) {
        global $obj_db;

        if ($user_id == -1) {
            $user_id = $_SESSION['calendar-uid']['uid'];
        }

        $str_query = 'SELECT * FROM `subscriptions` WHERE `user_id` = ' . $user_id ;

        $obj_result = mysqli_query($obj_db, $str_query);
        
        if($obj_result !== false) {
            $arr_line = mysqli_fetch_array($obj_result, MYSQLI_ASSOC);
            
            if ($arr_line !== false && !empty($arr_line)) {
                return $arr_line;
            }
        }
        
        return array();
    }

}

?>