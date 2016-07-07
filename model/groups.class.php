<?php

class Group {

    public static function getGroups() {
        global $obj_db;

        $bln_superadmin = User::isSuperAdmin();
        $bln_admin = User::isAdmin();

        $arr_groups = array();

        if (!isset($_SESSION['calendar-uid']) && !isset($_SESSION['calendar-uid']['uid'])) {
            return null;
        }

        if ($bln_superadmin) {
            $str_query = 'SELECT g.*, concat_ws(" ",firstname,infix,lastname) as fullname FROM `groups` g LEFT JOIN `users` u ON(u.user_id = g.admin_id) WHERE g.`deleted` = 0';
        } else if ($bln_admin) {
            $str_query = 'SELECT * FROM `groups` WHERE admin_id = ' . (int) $_SESSION['calendar-uid']['uid'] . ' AND `deleted` = 0';
        }

        $obj_result = mysqli_query($obj_db, $str_query);

        if ($obj_result === false) {
            return array();
        }
        while ($arr_line = mysqli_fetch_array($obj_result, MYSQLI_ASSOC)) {
            $arr_groups[] = $arr_line;
        }

        if (!empty($arr_groups)) {
            foreach ($arr_groups as &$group) {
                $str_query2 = 'SELECT * FROM group_users gu LEFT JOIN `users` u ON(u.user_id = gu.user_id) WHERE u.deleted = 0 AND gu.group_id = ' . $group['group_id'];
                $obj_result2 = mysqli_query($obj_db, $str_query2);

                if ($obj_result2 !== false) {
                    $cnt_rows = $obj_result2->num_rows;
                    $group['cnt_users'] = !empty($cnt_rows) && $cnt_rows > 0 ? $cnt_rows : 0;
                }
            }

            return $arr_groups;
        }
        return null;
    }

    public static function saveGroup($frm_submitted) {
        global $obj_db;

        if (User::isLoggedIn()) {
            if ($frm_submitted['group_id'] > 0) {

                $str_query = 'UPDATE `groups` SET `name` = "' . $frm_submitted['name'] . '", ' .
                        '`description` = "' . $frm_submitted['description'] . '", ' .
                        '`active` = ' . $frm_submitted['active'] .
                        ' WHERE `group_id` = ' . (int) $frm_submitted['group_id'];

                $int_group_id = $frm_submitted['group_id'];
            } else {
                $arr_user = User::getUser();

                $str_query = 'INSERT INTO `groups` ( `name` ,`description`, `active`, `admin_id`) VALUES (' .
                        '"' . $frm_submitted['name'] . '",' .
                        '"' . $frm_submitted['description'] . '",' .
                        $frm_submitted['active'] . ',' .
                        $arr_user['user_id'] . ')';
            }

            $res = mysqli_query($obj_db, $str_query);

            if ($frm_submitted['group_id'] == -1) {
                $int_group_id = mysqli_insert_id($obj_db);
            }

            if (!empty($frm_submitted['groupusers'])) {
                // save groupusers
                $arr_user_ids = explode(',', $frm_submitted['groupusers']);

                $arr_groupusers = Group::getUserIds($int_group_id);

                if (!empty($arr_groupusers) && is_array($arr_groupusers)) {
                    $arr_deleted = array_diff($arr_groupusers, $arr_user_ids);

                    foreach ($arr_deleted as $del_user_id) {
                        $str_query2 = 'DELETE FROM `group_users` WHERE `user_id` = ' . $del_user_id . ' AND `group_id` = ' . $int_group_id;

                        $res2 = mysqli_query($obj_db, $str_query2);
                    }
                }

                foreach ($arr_user_ids as $user_id) {
                    $str_query3 = 'REPLACE INTO `group_users` SET `user_id` = ' . $user_id . ', `group_id` = ' . $int_group_id;

                    $res3 = mysqli_query($obj_db, $str_query3);
                }
            }

            return $res;
        }
    }

    public static function getUserIds($int_group_id = -1) {
        global $obj_db;

        $str_query = 'SELECT * FROM group_users WHERE group_id = ' . $int_group_id;

        $obj_result = mysqli_query($obj_db, $str_query);

        $arr_groupuser_ids = array();

        while ($arr_line = mysqli_fetch_array($obj_result, MYSQLI_ASSOC)) {
            $arr_groupuser_ids[] = $arr_line['user_id'];
        }

        return $arr_groupuser_ids;
    }

    public static function getGroupUsers($int_group_id = -1, $bln_simple = false) {
        global $obj_db;

        $str_query = 'SELECT gu.*, CONCAT_WS(" ", u.firstname,u.infix,u.lastname) as fullname FROM group_users gu LEFT JOIN `users` u ON(gu.user_id = u.user_id) WHERE u.deleted = 0 AND u.`active` = 1 AND `group_id` = ' . $int_group_id;

        $obj_result = mysqli_query($obj_db, $str_query);

        $arr_groupusers = array();

        while ($arr_line = mysqli_fetch_array($obj_result, MYSQLI_ASSOC)) {
            if ($bln_simple) {
                $arr_groupusers[] = array('user_id' => $arr_line['user_id'], 'fullname' => $arr_line['fullname']);
            } else {
                $arr_groupusers[] = $arr_line;
            }
        }

        return $arr_groupusers;
    }

    public static function getGroup($group_id) {
        global $obj_db;

        $arr_group = array();

        // get group
        $str_query = 'SELECT * FROM `groups` WHERE group_id = ' . $group_id;
        $obj_result = mysqli_query($obj_db, $str_query);

        if ($obj_result !== false) {
            $arr_group = mysqli_fetch_array($obj_result, MYSQLI_ASSOC);

            $arr_users = User::getUsers(true);
            $arr_groupusers_ids = array();

            // get groupusers
            $str_query2 = 'SELECT * FROM `group_users` gu ' .
                    ' LEFT JOIN `users` u ON(u.user_id = gu.user_id) ' .
                    ' WHERE u.deleted = 0 AND group_id = ' . $arr_group['group_id'] . ' ORDER BY `lastname` ';

            $obj_result2 = mysqli_query($obj_db, $str_query2);

            if ($obj_result2 !== false) {
                while ($arr_line = mysqli_fetch_array($obj_result2, MYSQLI_ASSOC)) {
                    //$arr_group['users'][] = $arr_line;
                    $arr_groupusers_ids[] = $arr_line['user_id'];
                }
            }

            $int_selected = 0;
            foreach ($arr_users as &$user) {
                if (in_array($user['user_id'], $arr_groupusers_ids)) {
                    $user['selected'] = true;
                    $int_selected ++;
                } else {
                    $user['selected'] = false;
                }
            }
            $arr_group['users'] = $arr_users;
            $arr_group['cnt_users'] = $int_selected;
        }


        return $arr_group;
    }

    public static function getMyGroups($bln_only_ids = false) {
        global $obj_db;
        $arr_groups = array();

        if (User::isLoggedIn()) {
            $arr_user = User::getUser();

            if (User::isSuperAdmin()) {
                // get all groups
                $str_query = 'SELECT * FROM `groups` WHERE `deleted` = 0 ';
            } else if (User::isAdmin()) {
                // get group of this admin
                $str_query = 'SELECT * FROM `groups` WHERE `deleted` = 0 AND admin_id = ' . $arr_user['user_id'];
            } else {
                // get groups of user
                $str_query = 'SELECT * FROM `group_users` gu LEFT JOIN `groups` g ON ( gu.group_id = g.group_id ) WHERE g.`deleted` = 0 AND user_id = ' . $arr_user['user_id'];
            }

            $obj_result = mysqli_query($obj_db, $str_query);

            if ($obj_result !== false) {
                while ($arr_line = mysqli_fetch_array($obj_result, MYSQLI_ASSOC)) {
                    if ($bln_only_ids) {
                        $arr_groups[] = $arr_line['group_id'];
                    } else {
                        $arr_groups[] = $arr_line;
                    }
                }
            }
        }
        return $arr_groups;
    }

    public static function getGroupsOfUser($user_id = -1, $bln_only_ids = false) {
        global $obj_db;
        $arr_groups = array();

        // get groups of user
        $str_query = 'SELECT * FROM `group_users` WHERE user_id = ' . $user_id;

        $obj_result = mysqli_query($obj_db, $str_query);

        if ($obj_result !== false) {
            while ($arr_line = mysqli_fetch_array($obj_result, MYSQLI_ASSOC)) {
                if ($bln_only_ids) {
                    $arr_groups[] = $arr_line['group_id'];
                } else {
                    $arr_groups[] = $arr_line;
                }
            }
        }

        return $arr_groups;
    }

    public static function isMyGroup($int_group_id) {
        global $obj_db;
        if (User::isLoggedIn()) {
            $arr_user = User::getUser();

            if (User::isAdmin()) {


                $str_query = 'SELECT * FROM `groups` WHERE admin_id = ' . $arr_user['user_id'] . ' AND `group_id` = ' . $int_group_id;

                $obj_result = mysqli_query($obj_db, $str_query);

                $arr_row = mysqli_fetch_array($obj_result, MYSQLI_ASSOC);

                if ($arr_row !== false && !is_null($arr_row) && !empty($arr_row)) {
                    return true;
                }
            }
        }
        return false;
    }

    public static function deleteGroup($int_group_id) {
        global $obj_db;
        if (User::isLoggedIn()) {
            $arr_user = User::getUser();

            if (User::isAdmin()) {
                // delete group
                $str_query = 'UPDATE `groups` SET `deleted` = 1 WHERE `group_id` = ' . $int_group_id;

                $obj_result = mysqli_query($obj_db, $str_query);

                if ($obj_result !== false) {
                    // delete group_users, added this part so that we can undo the deleting of the group

                    $str_query2 = 'DELETE FROM `group_users` WHERE `group_id` = ' . $int_group_id;

                    $obj_result = mysqli_query($obj_db, $str_query2);


                    return true;
                }
            }
        }
        return false;
    }

    public static function undeleteGroup($int_group_id) {
        global $obj_db;
        if (User::isLoggedIn()) {
            $arr_user = User::getUser();

            if (User::isAdmin()) {
                // delete group
                $str_query = 'UPDATE `groups` SET `deleted` = 0 WHERE `group_id` = ' . $int_group_id;

                $obj_result = mysqli_query($obj_db, $str_query);

                if ($obj_result !== false) {
                    return true;
                }

                // group users are automatically deleted by the constraint
            }
        }
        return false;
    }

}

?>