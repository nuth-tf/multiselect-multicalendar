<?php


class Subscription {
    
    public static function getSubscriptionByUserId($int_user_id) {
        global $obj_db;

        $str_query = 'SELECT * FROM subscriptions WHERE user_id = ' . $int_user_id;

        $obj_result = mysqli_query($obj_db, $str_query);

        if ($obj_result !== false) {
            $arr_line = mysqli_fetch_array($obj_result, MYSQLI_ASSOC);
            
            if($arr_line !== false && !empty($arr_line)) {
                return $arr_line;
            }
        }
        return array();
    
    }
    
    public static function saveSubscription($added_user, $amount) {
        $arr_subscription = self::getSubscriptionByUserId($int_user_id);
        
        if(!empty($arr_subscription)) {
            $enddate = $arr_subscription['enddate'];
        } else {
            $enddate = date('Y-m-d H:i:s');
        }

        // how many months
        if($amount == 10) {
            // add 1 year subscription
            $new_enddate = strtotime(' + 1 YEAR', strtotime($enddate)); 
        } else {
            // add x months
            $int_months = (int)$amount;
            $new_enddate = strtotime(' + '.$int_months.' MONTH', strtotime($enddate)); 

        }
                        
        if(empty($arr_subscription)) {
            $str_query = 'INSERT INTO `subscriptions` ( `user_id` ,`startdate` ,`enddate` ,`subscription_type` ' .
                            ' ) VALUES (' .
                            '"' . $added_user . '",' .
                            '"' . date('Y-m-d H:i:s') . '",' .
                            '"' . $new_enddate . '",' .
                            '"standard" )';

            $res = mysqli_query($obj_db, $str_query);
        } else {
            $str_query = 'UPDATE `subscriptions` SET `enddate` = "' . $new_enddate . '" WHERE `user_id` = ' . $added_user;

            $res = mysqli_query($obj_db, $str_query);
        }
        
    }
    
}
