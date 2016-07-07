<?php

class Utils {

    /**
     * const usertable
     * When you have an existing database with a table that has also the name 'users', 
     * you can define another name for the calendar's users table. 
     * Do this in /model/users.class.php, /model/settings.class.php and /lib/utils.class.php.
     * 
     * It is not for using your own users table in the calendar
     */
    const usertable = 'users';

    public static function getColumnsOfTable($table, $arr_exclude_cols = array()) {
        global $obj_db;

        $cols = array();
        $result = $obj_db->query("SHOW COLUMNS FROM `" . $table . "`");

        while ($r = $result->fetch_array(MYSQLI_ASSOC)) {
            if (!in_array($r["Field"], $arr_exclude_cols)) {
                $cols[] = $r["Field"];
            }
        }

        return $cols;
    }

    public static function addLog($logtype='', $int_user_id=-1) {
        global $obj_db;
        
        $str_query = 'INSERT INTO `log` (`logtype`, `user_id`,`date`, `ip`) VALUES (' .
                    '"' . $logtype . '",' .
                    '"' . $int_user_id . '",' .
                    '"' . date('Y-m-d H:i:s') . '",' .
                    '"' . $_SERVER['REMOTE_ADDR'] . '"' .
                    ')';

            $res = mysqli_query($obj_db, $str_query);

    }
    
    public static function getLastLoggedinUsers($amount = 5, $ajax = false) {
        global $obj_db;
        $arr_return = array();

        if (User::isLoggedIn()) {
            //$arr_user = User::getUser();

            $str_query = 'SELECT * FROM `log` l LEFT JOIN `users` u ON(u.user_id = l.user_id) ';
            
            $str_query .= ' WHERE `logtype` = "login" ORDER BY `date` DESC LIMIT ' . $amount;

            $res = mysqli_query($obj_db, $str_query);

            if ($res !== false && !empty($res)) {
                while ($arr_line = mysqli_fetch_array($res, MYSQLI_ASSOC)) {
                    $arr_return[] = $arr_line;
                }
                if ($ajax) {
                    echo json_encode(array('current' => $arr_return));
                    exit;
                } else {
                    return $arr_return;
                }
            }
        }
    }
    
    public static function getPlaatsbewijs($arr_subscriptions, $arr_person, $arr_provider, $output = 'attachment') {
        require_once ANNE_EXT_DIR . 'fpdf' . DS . 'fpdf.php';
        require_once APP_LIB_DIR . 'cms_generate/fpdf.class.php';

        ini_set("memory_limit", "64M");

        $pdf = new PDF();
        $pdf->AliasNbPages();
        $pdf->SetFont('Arial', '', 12);
        $pdf->SetLeftMargin(15);

        foreach ($arr_subscriptions as $subscription) {
            $i = 1;
            while ($i <= $subscription['aantal_kaarten']) {
                self::generateTicket($pdf, $subscription, $i, $arr_person, $arr_provider);
                $i++;
            }
        }

        $bestandsnaam = 'plaatsbewijs_' . substr($arr_subscriptions[0]['name'], 0, 20) . '.pdf'; //substr($arr_subscriptions[0]['name'],0,20).'_'.$type.'_'.date("Y-m-d").'_'.date("H-i").'.pdf';

        if ($output == 'attachment') {
            return $pdf->Output($bestandsnaam, 'S');
        } else { //if($output == 'print') {
            return $pdf->Output($bestandsnaam, 'D');
        }
    }

    private function generateTicket($pdf, $subscription, $i, $arr_person, $arr_provider) {
        $pdf->AddPage();

        $pdf->write(10, 'Ticket ' . $pdf->PageNo() . ' van {nb}');

        $pdf->SetFillColor(0);
        $pdf->SetTextColor(0);

        // barcode
        $pdf->EAN13(15, 30, str_pad($subscription['subscription_id'], 10, 0, STR_PAD_LEFT) . str_pad($i, 3, 0, STR_PAD_LEFT), 10, .35, true);

        $image = file_get_contents(WEB_ROOT . 'index.php?cmd=file&file=' . $arr_provider['image_id']);

        require_once Module::getAMP(MOD_FILE) . 'model' . DS . 'repository_file.class.php';
        $str_extension = '';

        $obj_repos_file = new RepositoryFile($arr_provider['image_id']);

        if ($obj_repos_file->find(true)) {
            $str_extension = $obj_repos_file->getFile()->getFile_extension();
        }

        $filename = TMP_DIR . 'provider_logo' . $arr_provider['image_id'] . (!empty($str_extension) ? '.' . $str_extension : '.jpg');

        $image_path = file_put_contents($filename, $image);

        $pdf->Image($filename, 160, 10, 40); // met 40 is de afbeelding scherp, fpdf gaat de afbeelding resizen (omdat de default van depagina mm is ..)

        $pdf->SetY(80);

//		if($subscription['payment_method'] == 'free') {
//			$pdf->SetFont('','B',20);
//			$pdf->Cell(30,10,'Vrijkaart',0);
//			$pdf->SetFont('','',12);
//			$pdf->Ln(30);

        if (empty($arr_person)) {
            // leave empty
            $pdf->Ln(50);
        } else {
            $pdf->Cell(40, 6, 'Naam:', 0);
            $pdf->Cell(40, 6, $arr_person['fullname'], 0);
            $pdf->Ln();
            $pdf->Cell(40, 6, 'Adres:', 0);
            $pdf->Cell(40, 6, $arr_person['address'] . ' ' . $arr_person['housenumber'], 0);
            $pdf->Ln();
            $pdf->Cell(40, 6, 'Postcode/plaats:', 0);
            $pdf->Cell(40, 6, $arr_person['postcode'] . ' ' . $arr_person['city'], 0);
            $pdf->Ln(30);
        }

        $pdf->Cell(40, 6, 'Titel:', 0);
        $pdf->Cell(40, 6, $subscription['name'], 0);
        $pdf->Ln();

        if ($subscription['type'] !== 'online_cursus') {
            $pdf->Cell(40, 6, 'Datum:', 0);
            $pdf->Cell(40, 6, date('d-m-Y', strtotime($subscription['startdate'])), 0);
            $pdf->Ln();
            $pdf->Cell(40, 6, 'Aanvang:', 0);
            $pdf->Cell(40, 6, date('H:i', strtotime($subscription['startdate'])) . ' uur', 0);
            $pdf->Ln();
            $pdf->Cell(40, 6, 'Locatie:', 0);
            $pdf->Cell(40, 6, $subscription['location'], 0);
            $pdf->Ln();
            $pdf->Cell(40, 6, '', 0);
            $pdf->Cell(40, 6, $subscription['address'] . ' ' . $subscription['housenumber'], 0);
            $pdf->Ln();
            $pdf->Cell(40, 6, '', 0);
            $pdf->Cell(40, 6, $subscription['postcode'] . ' ' . $subscription['city'], 0);
            $pdf->Ln();
            $pdf->Cell(40, 6, '', 0);
            $pdf->Cell(40, 6, $subscription['telephone'], 0);
            $pdf->Ln();
        }

        $pdf->SetXY(15, 200);
        $pdf->Cell(50, 6, $arr_provider['name'], 0);
        $pdf->Ln();
        $pdf->Cell(50, 6, $arr_provider['address'], 0);
        $pdf->Ln();
        $pdf->write(6, str_ireplace(array('<br />', '<br>'), "\n", $arr_provider['info']));


        //self::voegDatumEnPaginanummerToe($pdf);
        // paginering
        $pdf->SetXY(15, 240);

        if (stristr($arr_provider['colofon'], '<br>')) {
            $arr_lines = explode('<br>', $arr_provider['colofon']);
            foreach ($arr_lines as $line) {
                $pdf->Cell(50, 6, $line, 0);
                $pdf->Ln();
            }
        } else {
            $str_colofon = str_ireplace(array('<br />', '<br>'), "\n", $arr_provider['colofon']);
            $pdf->Cell(50, 6, $str_colofon, 0);
        }
    }

    private function voegDatumEnPaginanummerToe($pdf) {
        // datum
        $pdf->SetTextColor(45, 59, 116);
        $pdf->SetXY(35, 5);
        $pdf->SetFont('', '', 10);
        $pdf->Cell(30, 10, 'Gegenereerd op ' . date("d-m-Y"), 0);

        // paginering
        $pdf->SetXY(242, 183);
        $pdf->Cell(0, 5, 'Pagina ' . $pdf->PageNo() . ' van {nb}', 0, 0, 'C');
    }

    /**
     * 
     * @param string $type
     * @param string $email
     * @param string $password
     * @param array $frm_submitted
     * @param mixed $int_user_id
     * @param string $hash_code
     * @return boolean
     */
    public static function sendMail($type = '', $email = '', $password = '', $frm_submitted = array(), $int_user_id = 0, $hash_code = '', $arr_admin = array()) {
        if (!empty($type) && !empty($email)) {
            switch ($type) {
                case 'mail_event':
                    if (is_array($int_user_id)) {
                        $arr_user = $int_user_id;
                    }

                    $subject = defined('MAIL_EVENT_MAILSUBJECT') ? MAIL_EVENT_MAILSUBJECT : 'Event notification';
                    $mailtext = defined('MAIL_EVENT_MAILBODY') ? MAIL_EVENT_MAILBODY :
                            'Employee: %FIRSTNAME% %INFIX% %LASTNAME%<br />'
                            . 'Title: %TITLE%<br />'
                            . '<p>Description: %DESCRIPTION%</p><br />'
                            . 'Location: %LOCATION%<br />'
                            . 'Phone: %PHONE%<br />'
                            . 'Url: %URL%<br />'
                            . 'Startdate: %STARTDATE%<br />'
                            . 'Enddate: %ENDDATE%<br />';

                    if (is_array($arr_user) && !empty($arr_user)) {
                        $mailtext = str_replace('%FIRSTNAME%', $arr_user['firstname'], $mailtext);
                        $mailtext = str_replace('%INFIX%', $arr_user['infix'], $mailtext);
                        $mailtext = str_replace('%LASTNAME%', $arr_user['lastname'], $mailtext);
                    } else {
                        // no user logged in
                        $mailtext = str_replace('%FIRSTNAME%', '', $mailtext);
                        $mailtext = str_replace('%INFIX%', '', $mailtext);
                        $mailtext = str_replace('%LASTNAME%', '', $mailtext);
                    }

                    $mailtext = str_replace('%TITLE%', $frm_submitted['title'], $mailtext);
                    $mailtext = str_replace('%DESCRIPTION%', $frm_submitted['description'], $mailtext);
                    $mailtext = str_replace('%LOCATION%', $frm_submitted['location'], $mailtext);
                    $mailtext = str_replace('%PHONE%', $frm_submitted['phone'], $mailtext);
                    $mailtext = str_replace('%URL%', $frm_submitted['myurl'], $mailtext);
                    $mailtext = str_replace('%STARTDATE%', $frm_submitted['str_date_start'], $mailtext);
                    $mailtext = str_replace('%ENDDATE%', $frm_submitted['str_date_end'], $mailtext);

                    break;
                case 'mail_assigned_event':
                case 'mail_unassigned_event':
                    if (is_array($int_user_id)) {
                        $arr_user = $int_user_id;
                    }
                    
                    if($type == 'mail_assigned_event') {
                        $subject = 'Task/event assigned to you';
                        $mailtext = 'Employer/admin %FIRSTNAME% %INFIX% %LASTNAME% has assigned a task/event to you<br />'
                               . '%TITLE%<br />';
                    
                        
                    } else if($type == 'mail_unassigned_event') {
                        $subject = 'Task/event is unassigned';
                        $mailtext = 'Employer/admin %FIRSTNAME% %INFIX% %LASTNAME% has unassigned a task/event<br />'
                                . '%TITLE%<br />';
                    }
                    
                    if (!empty($frm_submitted['description']) && !is_null($frm_submitted['description'])) {
                        $mailtext .= '<p>%DESCRIPTION%</p>';
                    }

                    if (!empty($frm_submitted['location']) && !is_null($frm_submitted['location'])) {
                        $mailtext .= 'Location: %LOCATION%<br />';
                    }

                    if (!empty($frm_submitted['myurl']) && !is_null($frm_submitted['myurl'])) {
                        $mailtext .= 'Url: %URL%<br />';
                    }

                    if (!empty($frm_submitted['phone']) && !is_null($frm_submitted['phone'])) {
                        $mailtext .= 'Phone: %PHONE%<br />';
                    }

                    $str_startdate = date('d-m-Y', strtotime($frm_submitted['str_date_start']));
                    $str_enddate = date('d-m-Y', strtotime($frm_submitted['str_date_end']));

                    if ($str_startdate == $str_enddate) {
                        $mailtext .= 'Date: %STARTDATE%<br />';
                    } else {
                        $mailtext .= 'Startdate: %STARTDATE%<br />Enddate: %ENDDATE%<br />';
                    }


                    if (is_array($arr_admin) && !empty($arr_admin)) {
                        $mailtext = str_replace('%FIRSTNAME%', $arr_admin['firstname'], $mailtext);
                        $mailtext = str_replace('%INFIX%', $arr_admin['infix'], $mailtext);
                        $mailtext = str_replace('%LASTNAME%', $arr_admin['lastname'], $mailtext);
                    } else {
                        // no user logged in
                        $mailtext = str_replace('%FIRSTNAME%', '', $mailtext);
                        $mailtext = str_replace('%INFIX%', '', $mailtext);
                        $mailtext = str_replace('%LASTNAME%', '', $mailtext);
                    }

                    $mailtext = str_replace('%TITLE%', $frm_submitted['title'], $mailtext);
                    $mailtext = str_replace('%DESCRIPTION%', $frm_submitted['description'], $mailtext);
                    $mailtext = str_replace('%LOCATION%', $frm_submitted['location'], $mailtext);
                    $mailtext = str_replace('%PHONE%', $frm_submitted['phone'], $mailtext);
                    $mailtext = str_replace('%URL%', $frm_submitted['myurl'], $mailtext);


                    if ($str_startdate == $str_enddate) {
                        if ($frm_submitted['allDay']) {
                            $mailtext = str_replace('%STARTDATE%', date('d-m-Y', strtotime($frm_submitted['str_date_start'])), $mailtext);
                        } else {
                            $mailtext = str_replace('%STARTDATE%', date('d-m-Y H:i:s', strtotime($frm_submitted['str_date_start'])), $mailtext);
                        }
                    } else {
                        if ($frm_submitted['allDay']) {
                            $mailtext = str_replace('%STARTDATE%', date('d-m-Y', strtotime($frm_submitted['str_date_start'])), $mailtext);
                            $mailtext = str_replace('%ENDDATE%', date('d-m-Y', strtotime($frm_submitted['str_date_end'])), $mailtext);
                        } else {
                            $mailtext = str_replace('%STARTDATE%', date('d-m-Y H:i:s', strtotime($frm_submitted['str_date_start'])), $mailtext);
                            $mailtext = str_replace('%ENDDATE%', date('d-m-Y H:i:s', strtotime($frm_submitted['str_date_end'])), $mailtext);
                        }
                    }


                    break;
                case 'add_user':
                    $subject = 'Your new account';
                    $mailtext = 'The admin created an account for you. <br /><br />';

                    $send_activation_mail = Settings::getAdminSetting('send_activation_mail', $int_user_id);
                    $bln_send_activation_mail = $send_activation_mail == 'on';

                    if ($bln_send_activation_mail) {

                        if (defined('ACTIVATION_MAIL_SUBJECT') && ACTIVATION_MAIL_SUBJECT !== '') {
                            $subject = ACTIVATION_MAIL_SUBJECT;

                            if (stristr($subject, '%USERNAME%')) {
                                if (isset($frm_submitted['username']) && !empty($frm_submitted['username'])) {
                                    $subject = str_replace('%USERNAME%', $frm_submitted['username'], $subject);
                                } else {
                                    $subject = str_replace('%USERNAME%', '', $subject);
                                }
                            }
                        }

                        $mailtext .= 'To confirm the registration click on this link: <br />' .
                                '<a href="' . FULLCAL_URL . '/?action=activate&uid=' . $int_user_id . '&hash=' . $hash_code . '">' . FULLCAL_URL . '/?action=activate&uid=' . $int_user_id . '&hash=' . $hash_code . '</a><br /><br />' .
                                '<br />If your browser doesn\'t automatically open, paste the link in your browser ';
                    } else {
                        if (!isset($frm_submitted['username']) || empty($frm_submitted['username'])) {
                            $mailtext .= 'You can login with your emailaddress as username. ';
                        }

                        $mailtext .= '<br />Your password is: ' . $password;
                    }

                    break;
                case 'add_admin':
                    $subject = 'Your new admin account';
                    $mailtext = 'The admin created an admin account for you. <br /><br />';

                    if (!isset($frm_submitted['username']) || empty($frm_submitted['username'])) {
                        $mailtext .= 'You can login with your emailaddress as username ';
                    }

                    $mailtext .= '<br />Your password is: ' . $password;
                    break;
                case 'copy_to_admin_admin_created':
                    $subject = 'New account';
                    $mailtext = 'You created a new admin account for: ' . $frm_submitted['firstname'] . ' ' . $frm_submitted['infix'] . ' ' . $frm_submitted['lastname'] . '. <br /><br />' .
                            'Username: ' . $frm_submitted['username'] . '<br />Password: ' . $password;
                    break;
                case 'copy_to_admin_user_created':
                    $subject = 'New account';
                    $mailtext = 'You created a new user account for: ' . $frm_submitted['firstname'] . ' ' . $frm_submitted['infix'] . ' ' . $frm_submitted['lastname'] . '. <br /><br />' .
                            'Username: ' . $frm_submitted['username'] . '<br />Password: ' . $password;
                    break;
            }

            $message = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">' .
                    '<html>' .
                    '<head></head>' .
                    '<body>';

            $message .= $mailtext;

            $message .= '</body></html>';

            $headers = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
            $headers .= 'From: ' . FROM_EMAILADDRESS . "\r\n";

            if (mail($email, $subject, $message, $headers)) {
                return true;
            } else {
                return false;
            }
        }
        return false;
    }

    public static function checkEmail($var_to_check) {
        $host = explode('@', $var_to_check);

        if (preg_match('/^(([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5}){1,25})+([;.](([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5}){1,25})+)*$/', $var_to_check)) {

            if (function_exists('checkdnsrr')) {
                if (checkdnsrr($host[1] . '.', 'MX'))
                    return true;
                if (checkdnsrr($host[1] . '.', 'A'))
                    return true;

                return false;
            } else {
                $result = array();
                @exec('nslookup -type=MX ' . escapeshellcmd($host[1]) . '.', $result);
                foreach ($result as $line) {
                    if (eregi("^$host[1].", $line)) {
                        return true;
                    }
                }

                return false;
            }
        } else {
            return false;
        }
    }

    public static function getDaysBetween($sStartDate, $sEndDate) {
        if (is_numeric($sStartDate) && is_numeric($sEndDate)) {
            $sStartDate = date("Y-m-d", $sStartDate);
            $sEndDate = date("Y-m-d", $sEndDate);
        } else {
            $sStartDate = date("Y-m-d", strtotime($sStartDate));
            $sEndDate = date("Y-m-d", strtotime($sEndDate));
        }

        $aDays[] = $sStartDate;
        $sCurrentDate = $sStartDate;

        while ($sCurrentDate < $sEndDate) {
            $sCurrentDate = date("Y-m-d", strtotime("+1 day", strtotime($sCurrentDate)));
            $aDays[] = $sCurrentDate;
        }
        return $aDays;
    }

    public static function getDaysInPattern($frm_submitted) {
        $arr_return = array();

        if (!defined('IGNORE_TIMEZONE')) {
            define('IGNORE_TIMEZONE', true);
        }
        //$days_in_between = Utils::getDaysBetween($frm_submitted['date_start'], $frm_submitted['date_end']);
        if (IGNORE_TIMEZONE) {
            $date_start = date("Y-m-d", strtotime($frm_submitted['str_date_start']));
            $date_end = date("Y-m-d", strtotime($frm_submitted['str_date_end']));
        } else {
            if (is_numeric($frm_submitted['date_start']) && is_numeric($frm_submitted['date_end'])) {
                $date_start = date("Y-m-d", $frm_submitted['date_start']);
                $date_end = date("Y-m-d", $frm_submitted['date_end']);
            } else {
                $date_start = date("Y-m-d", strtotime($frm_submitted['date_start']));
                $date_end = date("Y-m-d", strtotime($frm_submitted['date_end']));
            }
        }

        $arr_weekdays = array();

        if ($frm_submitted['interval'] == 'D') {
            $every_x_days = $frm_submitted['every_x_days'];

            $current_date = $date_start;

            $arr_return[] = $current_date;

            while ($current_date <= $date_end) {

                $current_date = date("Y-m-d", strtotime("+" . $every_x_days . " day", strtotime($current_date)));

                if ($current_date <= $date_end) {
                    $arr_return[] = $current_date;
                } else {
                    break;
                }
            }
        } else if ($frm_submitted['interval'] == 'W') {
            // which days are selected
            $str_interval_days = substr($frm_submitted['weekdays'], 1); // trim first comma

            $every_x_weeks = $frm_submitted['every_x_weeks'];

            if (strstr($str_interval_days, ',')) {
                $arr_interval_days = explode(',', $str_interval_days);
            } else {
                $arr_interval_days = array($str_interval_days);
            }

            foreach ($arr_interval_days as $day) {
                $arr_weekdays[$day] = $day;
            }

            /*
             * find days that are in the pattern
             */

            $current_date = $date_start;

            if (array_key_exists(date('N', strtotime($current_date)), $arr_weekdays)) {
                $arr_return[] = $current_date;
            }

            while ($current_date <= $date_end) {
                $current_date = date("Y-m-d", strtotime("+1 day", strtotime($current_date)));
                //   $aDays[] = $sCurrentDate;
                if (array_key_exists(date('N', strtotime($current_date)), $arr_weekdays)) {
                    if ($current_date <= $date_end) {
                        $arr_return[] = $current_date;
                    } else {
                        break;
                    }
                }
                if (date('N', strtotime($current_date)) == 7 && $every_x_weeks > 1) {
                    $current_date = date("Y-m-d", strtotime("+" . (($every_x_weeks - 1) * 7) . " day", strtotime($current_date)));
                }
            }
        } else if ($frm_submitted['interval'] == 'M') {

            $current_date = $date_start;

            if ($frm_submitted['monthday'] == 'dom') {

                /*
                 * dom: day of month
                 */

                // what day is startdate?
                $int_monthday = date('d', $frm_submitted['date_start']);
                $arr_return[] = date('Y-m-d', $frm_submitted['date_start']);

                $plus_four_weeks = self::getPlusOneMonthDate($frm_submitted['date_start'], $int_monthday);

                while ($current_date <= $date_end) {
                    $current_date = date("Y-m-d", strtotime("+1 day", strtotime($current_date)));

                    if ($current_date == $plus_four_weeks) {
                        if (date('d', strtotime($plus_four_weeks)) == $int_monthday) {
                            if ($current_date <= $date_end) {
                                $arr_return[] = $current_date;
                            } else {
                                break;
                            }

                            $plus_four_weeks = self::getPlusOneMonthDate($current_date, $int_monthday);
                        }
                    }
                }
            } else if ($frm_submitted['monthday'] == 'dow') {

                /*
                 * dow: day of week
                 */

                // what weekday is startdate?
                $int_weekday = self::getWeekdayFromDate($frm_submitted['date_start']);
                $arr_return[] = date('Y-m-d', $frm_submitted['date_start']);

                $plus_four_weeks = self::getPlusFourWeeksDate($frm_submitted['date_start'], $int_weekday);

                while ($current_date <= $date_end) {
                    $current_date = date("Y-m-d", strtotime("+1 day", strtotime($current_date)));

                    if ($current_date == $plus_four_weeks) {
                        if (date('N', strtotime($plus_four_weeks)) == $int_weekday) {
                            if ($current_date <= $date_end) {
                                $arr_return[] = $current_date;
                            } else {
                                break;
                            }
                            $plus_four_weeks = self::getPlusFourWeeksDate($current_date, $int_weekday);
                        }
                    }
                }
            }
        } else if ($frm_submitted['interval'] == 'Y') {
            // yearly
            $year = date('Y', strtotime($date_start));

            // yearmonthday and yearmonth
            $yearmonthday = str_pad($frm_submitted['yearmonthday'], 2, 0, STR_PAD_LEFT);
            $yearmonth = str_pad($frm_submitted['yearmonth'] + 1, 2, 0, STR_PAD_LEFT);

            $current_date = $year . '-' . $yearmonth . '-' . $yearmonthday;

            if ($current_date >= $date_start) {
                $arr_return[] = $current_date;
            }

            while ($current_date <= $date_end) {
                $year ++;
                $current_date = $year . '-' . $yearmonth . '-' . $yearmonthday;

                if ($current_date <= $date_end) {
                    $arr_return[] = $current_date;
                } else {
                    break;
                }
            }
        }

        return $arr_return;
    }

    public static function getWeekdayFromDate($str_date, $bln_textual = false) {
        if (is_int($str_date)) {
            $ts_date = $str_date;
        } else {
            $ts_date = strtotime($str_date);
        }


        if (!$bln_textual) {
            return (int) date('w', $ts_date);
            ;
        } else {
            $date = strftime('%A', $ts_date);
        }
        return $date;
    }

    public static function getNextWeekDate($start_date, $weekday) {
        //$oneweekfromnow = date('Y-m-d', strtotime("+1 week", strtotime($start_date)));
        $oneweekfromnow = strtotime("+1 week", strtotime($start_date)); //timestamp
        // extra check
        if (date('N', $oneweekfromnow) == $weekday) {
            return $oneweekfromnow;
        }
        return false;
    }

    public static function getPlusFourWeeksDate($start_date, $weekday) {
        //$oneweekfromnow = date('Y-m-d', strtotime("+1 week", strtotime($start_date)));

        if (is_int($start_date)) {
            $ts_date = $start_date;
        } else {
            $ts_date = strtotime($start_date);
        }

        $oneweekfromnow = strtotime("+4 week", $ts_date); //timestamp
        // extra check
        if (date('N', $oneweekfromnow) == $weekday) {
            return date('Y-m-d', $oneweekfromnow);
        }
        return false;
    }

    public static function getPlusOneMonthDate($start_date, $monthday) {
        //$oneweekfromnow = date('Y-m-d', strtotime("+1 week", strtotime($start_date)));

        if (is_int($start_date)) {
            $ts_date = $start_date;
        } else {
            $ts_date = strtotime($start_date);
        }

        $onemonthfromnow = strtotime("+1 month", $ts_date); //timestamp
        // extra check
        if (date('d', $onemonthfromnow) == $monthday) {
            return date('Y-m-d', $onemonthfromnow);
        }
        return false;
    }

    public static function generatePassword($length = 10) {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!#$%*_+|';

        $str = '';
        $max = strlen($chars) - 1;

        for ($i = 0; $i < $length; $i++)
            $str .= $chars[mt_rand(0, $max)];

        return $str;
    }

    public static function setLocaleLanguage($lang = '') {
        //setlocale(LC_ALL, '');
        
        if (!empty($lang)) {
            $language = strtolower($lang);
        } else {

            if (USE_CLIENTS_LANGUAGE) {
                $language = ''; // the clients language will be set
                $locale = setlocale(LC_ALL, '');
            } else {
                $language = strtolower(LANGUAGE);
            }
        }
//echo $_SERVER['HTTP_ACCEPT_LANGUAGE'];
        if (!empty($language)) {
            switch ($language) {
                case 'en':
                    $locale = array('eng', 'en_EN', 'en_EN.UTF-8');
                    break;
                case 'de':
                    $locale = array('deu', 'de_DE.UTF-8');
                    break;
                case 'fr':
                    $locale = array('fra', 'fr_FR.UTF-8', 'fr_FR');
                    break;
                case 'nl':
                    $locale = array('nld', 'nl_NL@euro, nl_NL.UTF-8, nl_NL, nld_nld, dut, nla, nl, nld, dutch');
                    break;
                case 'pl':
                    $locale = array('pol', 'de_DE.UTF-8');
                    break;
                case 'es':
                    $locale = array('esp', 'es_ES.UTF-8, spanish');
                    break;
                case 'no':
                    $locale = array('nor', 'no_NO.UTF-8, no_NO, Norwegian');
                    break;
                case 'it':
                    $locale = array('ita', 'it_IT.UTF-8, it_IT, Italian');
                    break;
                case 'cz';
                    $locale = array('cze', 'cz_CZ.UTF-8', 'cz_CZ', 'czech');
                    break;
                default:
                    $locale = array('eng', 'en_EN', 'en_EN.UTF-8');
            }
        }

        //setlocale(LC_ALL, NULL);

        setlocale(LC_ALL, $locale);

        header("Content-Type: text/html;charset=UTF-8");
        header("Content-Language: $language");
    }

    /**
     * 
     * @param type $two_dim_array
     * @param type $key_to_sort_with
     * @param type $dir
     * @param type $case_sensitive
     * @return type
     */
    public static function sortTwodimArrayByKey($two_dim_array, $key_to_sort_with, $dir = 'ASC', $case_sensitive = false) {

        if (!empty($two_dim_array)) {

            $arr_result = array();
            $arr_values = array();
            $bln_third_dim = false;

            if (strstr($key_to_sort_with, '/')) {
                $arr_dims = explode('/', $key_to_sort_with);
                $sec_dim_key = $arr_dims[0];
                $third_dim_key = $arr_dims[1];
                $bln_third_dim = true;
            }

            // array maken met de waardes waarop gesorteerd moet worden
            foreach ($two_dim_array as $arr_second_dim) {
                if (is_array($arr_second_dim[$key_to_sort_with])) {
                    echo 'opgegeven key is een array. Gebruik key1/key2';
                    break;
                }
                $arr_values[] = $bln_third_dim ? $arr_second_dim[$sec_dim_key][$third_dim_key] : $arr_second_dim[$key_to_sort_with];
            }

            // sorteren ( de key's krijgen de nieuwe volgorde )
            if ($case_sensitive) {
                sort($arr_values);
            } else {
                natcasesort($arr_values);
            }

            // nieuwe array maken met de juiste volgorde
            foreach ($arr_values as $value) {
                foreach ($two_dim_array as $key => $val) {
                    if ($value == ($bln_third_dim ? $val[$sec_dim_key][$third_dim_key] : $val[$key_to_sort_with])) {
                        $arr_result[] = $two_dim_array[$key];
                        unset($two_dim_array[$key]);
                    }
                }
            }

            if ($dir == 'DESC') {
                $arr_result = array_reverse($arr_result);
            }

            return $arr_result;
        } else {
            return array();
        }
    }

    public static function getSubstring($string, $str_start, $start_plus_or_min = 0, $str_end = '', $end_plus_or_min = 0) {

        $string_min_eerste_gedeelte = stristr($string, $str_start);
        //$str_start_pos = stripos($string, $str_start);

        $string_min_eerste_gedeelte_min_startstring = substr($string_min_eerste_gedeelte, strlen($str_start) + $start_plus_or_min);

        if (empty($str_end)) {
            return $string_min_eerste_gedeelte_min_startstring;
        }
        $eindpositie_karakter = stripos($string_min_eerste_gedeelte_min_startstring, $str_end);

        $str_result = substr($string_min_eerste_gedeelte_min_startstring, 0, $eindpositie_karakter + $end_plus_or_min);

        return $str_result;
    }

    public static function getLanguage() {
        $language = Settings::getSetting('language');
        if (empty($language)) {
            if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
                if (class_exists('Locale')) {
                    $language = Locale::acceptFromHttp($_SERVER['HTTP_ACCEPT_LANGUAGE']);
                } else {
                    $language = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
                }
            } else {
                $language = strtolower(LANGUAGE);
            }
        }
        return $language;
    }

    public static function strip_script($string) {
        if (strstr($string, '<script')) {
            $str_part_after_scripttag = trim(strstr($string, '<script'));
            $str_result = substr($str_part_after_scripttag, 0, strpos($str_part_after_scripttag, '</script>') + 9);
            $string = str_replace($str_result, '', $string);
            $string = self::strip_script($string);
        }
        return $string;
    }

    public static function importUsersFromCsv() {
        global $obj_db;

        if (User::isLoggedIn() && User::isAdmin()) {
            // you have to be admin to import users, so we have a userID to put in the admin_group
            $arr_user = User::getUser();
        } else {
            echo 'No rights to do that';
            exit;
        }

        $arr_submit = array(
            array('type', 'string', false, ''),
            
        );

        $frm_submitted_tmp = validate_var($arr_submit);
        $type = $frm_submitted_tmp['type'];
            
        $users_file = '';
        
        // from dashboard
        if(isset($_FILES['file'])) {
            $file_error = '';
            switch ($_FILES['file']['error']) {
                case 0: //no error; 

                    break;
                case 1: //uploaded file exceeds the upload_max_filesize directive in php.ini
                    $file_error = 'TOO_BIG';   //"The file you are trying to upload is too big.";
                    break;
                case 2: //uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the html form
                    $file_error = 'TOO_BIG';   //"The file you are trying to upload is too big.";
                    break;
                case 3: //uploaded file was only partially uploaded
                    $file_error = 'PARTIALLY_UPLOADED';   //"The file you are trying upload was only partially uploaded.";
                    break;
                case 4: //no image file was uploaded
                    $file_error = 'NO_FILE_SELECTED';   //"You must select a file for upload.";
                    break;
                default: //a default error, just in case!  :)
                    $file_error = 'PROBLEM_WITH_UPLOAD';   //"There was a problem with your upload.";
                    break;
            }

            if (!empty($file_error)) {
                echo $file_error;
                exit;
            }
            
            // upload file to system folder
            // get extension
            $arr_type = explode('/', $_FILES['file']['type']);

            if($arr_type[1] == 'csv' || $arr_type[1] == ' CSV') {
                // correct extension
            } else {
                echo 'Please upload a CSV file'; exit;
            }
            
            global $error;
            global $obj_smarty;

            $arr_submit = array(
                array('user_import_file', 'string', true, ''),
                array('columns_separated_by', 'string', true, ''),
                
            );

            $frm_submitted = validate_var($arr_submit);
            
            $type = $frm_submitted['user_import_file'];
            $column_separator = $frm_submitted['columns_separated_by'] == 'comma' ? ',' : ';';
     
            $arr_file['extension'] = $arr_type[1];
            $arr_file['orig_filename'] = $_FILES['file']['name'];
            $arr_file['type'] = $_FILES['file']['type'];
            $arr_file['filename'] = sha1_file($_FILES['file']['tmp_name']);
            $arr_file['size'] = $_FILES['file']['size'];
            //$arr_file['event_id'] = $frm_submitted['upload_event_id'];

     
            //rejects all .exe, .com, .bat, .zip, .doc and .txt files
            if (preg_match('/\\.(exe|com|bat|zip|apk|js|jsp)$/i', $arr_file['orig_filename'])) {
                echo json_encode(array('success' => false, 'error' => 'FILE_NOT_ALLOWED'));
                exit;
            }

            if (move_uploaded_file($_FILES['file']['tmp_name'], FULLCAL_DIR . '/system/' . $arr_file['filename'] . '.' . $arr_file['extension'])) {
                $users_file = $arr_file['filename'] . '.' . $arr_file['extension'];
            }

        } else {
            if ($type == 'wordpress') {
                $arr_columns = array('ID', 'user_login', 'user_pass', 'user_nicename', 'user_email', 'user_url',
                    'user_registered', 'user_activation_key', 'user_status', 'display_name');

                $users_file = 'wp_users.csv';
            } else if ($type == 'phpbb_3.0.14') {
                 //user_id,	user_type, group_id, user_permissions, user_perm_from 	user_ip 	user_regdate 	username 	username_clean 	user_password 	user_passchg 	user_pass_convert 	user_email 	user_email_hash 	user_birthday 	user_lastvisit 	user_lastmark 	user_lastpost_time 	user_lastpage 	user_last_confirm_key 	user_last_search 	user_warnings 	user_last_warning 	user_login_attempts 	user_inactive_reason 	user_inactive_time 	user_posts 	user_lang 	user_timezone 	user_dst 	user_dateformat 	user_style 	user_rank 	user_colour 	user_new_privmsg 	user_unread_privmsg 	user_last_privmsg 	user_message_rules 	user_full_folder 	user_emailtime 	user_topic_show_days 	user_topic_sortby_type 	user_topic_sortby_dir 	user_post_show_days 	user_post_sortby_type 	user_post_sortby_dir 	user_notify 	user_notify_pm 	user_notify_type 	user_allow_pm 	user_allow_viewonline 	user_allow_viewemail 	user_allow_massemail 	user_options 	user_avatar 	user_avatar_type 	user_avatar_width 	user_avatar_height 	user_sig 	user_sig_bbcode_uid 	user_sig_bbcode_bitfield 	user_from 	user_icq 	user_aim 	user_yim 	user_msnm 	user_jabber 	user_website 	user_occ 	user_interests 	user_actkey 	user_newpasswd 	user_form_salt 	user_new 	user_reminded 	user_reminded_time 
                $users_file = 'phpbb_3014_users.csv';
            } else if ($type == 'phpbb_317') {
                $users_file = 'phpbb_317_users.csv';
            } else if ($type == 'generic') {
                $users_file = 'generic_users.csv';
            }
        }
        
    
        if(empty($users_file)) {
            echo 'No file found';
            exit;
        }
        
        $row = 1;
        if (($handle = fopen(FULLCAL_DIR . "/system/" . $users_file, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 3000, $column_separator)) !== FALSE) {
                $num = count($data);

                if($column_separator == ',' && strstr($data[0], ';')) {
                    echo 'Incorrect CSV format: the columns must be separated by ,';exit;
                }
                if($column_separator == ';' && strstr($data[0], ',')) {
                    echo 'Incorrect CSV format: the columns must be separated by ;';exit;
                }
                    
                $admin_group = $arr_user['user_id'];
                $active = 1;

                $title = '';
                $firstname = '';
                $infix = '';
                $lastname = '';
                $email = '';
                $registration_date = '';
                $birthdate = '';
                $username = '';
                $ip = '';
                $country = '';
                $user_info = '';
                    
                if ($type == 'wordpress') {
                    // ID 	user_login 	user_pass 	user_nicename 	user_email 	user_url 	user_registered 	user_activation_key 	user_status 	display_name
                    
                    if ($num !== 10) {
                        echo 'amount of columns must be 10';
                        exit;
                    }

                    if ($data[0] == 'ID') {
                        // check if columns are as expected
                        continue;
                    }

                    echo 'row ' . $row . ': ';

                    $username = $data[1];
                    $firstname = '';
                    $infix = '';
                    $lastname = $data[9];
                    $email = $data[4];
                    $registration_date = $data[6];
                    
                } else if ($type == 'phpbb_3.0.14') {
                    //  user_id 	user_type 	group_id 	user_permissions  user_perm_from 	
                    //  user_ip(5) 	user_regdate(6) 	username(7) 	username_clean 	user_password 	user_passchg 	user_pass_convert 	
                    //  user_email(12) 	user_email_hash 	user_birthday(14) 	user_lastvisit 	user_lastmark 	user_lastpost_time 	user_lastpage 	user_last_confirm_key 	user_last_search 	user_warnings 	user_last_warning 	user_login_attempts 	user_inactive_reason 	user_inactive_time 	user_posts 	user_lang 	user_timezone 	user_dst 	user_dateformat 	user_style 	user_rank 	user_colour 	user_new_privmsg 	user_unread_privmsg 	user_last_privmsg 	user_message_rules 	user_full_folder 	user_emailtime 	user_topic_show_days 	user_topic_sortby_type 	user_topic_sortby_dir 	user_post_show_days 	user_post_sortby_type 	user_post_sortby_dir 	user_notify 	user_notify_pm 	user_notify_type 	user_allow_pm 	user_allow_viewonline 	user_allow_viewemail 	user_allow_massemail 	user_options 	user_avatar 	user_avatar_type 	user_avatar_width 	user_avatar_height 	user_sig 	user_sig_bbcode_uid 	user_sig_bbcode_bitfield 	
                    //  user_from(61) 	user_icq 	user_aim 	user_yim 	user_msnm 	user_jabber 	user_website 	user_occ 	
                    //  user_interests(69) 	user_actkey 	user_newpasswd 	user_form_salt 	user_new 	user_reminded 	user_reminded_time 
                    
                    if ($num !== 76) {
                        echo 'amount of columns must be 76';
                        exit;
                    }

                    if ($data[0] == 'user_id') {
                        // check if columns are as expected
                        continue;
                    }

                    $ip = $data[5];
                    $registration_date = date('Y-m-d H:i:s', $data[6]);
                    $username = $data[7];
                    $email = $data[12];
                    $birthdate = date('Y-m-d', strtotime($data[14]));
                    $firstname = '';
                    $infix = '';
                    $lastname = $data[7];
                    $country = $data[61];
                    $user_info = $data[69];
                    
                } else if ($type == 'phpbb_3.1.7') {
                    // user_id 	user_type group_id user_permissions user_perm_from user_ip(5) user_regdate(6) username(7) username_clean user_password 	user_passchg user_email(11) user_email_hash 	user_birthday(13) 	user_lastvisit 	user_lastmark 	user_lastpost_time 	user_lastpage 	user_last_confirm_key 	user_last_search 	user_warnings 	user_last_warning 20	user_login_attempts 	user_inactive_reason 	user_inactive_time 	user_posts 	user_lang 	user_timezone 	user_dateformat 	user_style 	user_rank 	user_colour 30	user_new_privmsg 	user_unread_privmsg 	user_last_privmsg 	user_message_rules 	user_full_folder 	user_emailtime 	user_topic_show_days 	user_topic_sortby_type 	user_topic_sortby_dir 	user_post_show_days 40	user_post_sortby_type 	user_post_sortby_dir 	user_notify 	user_notify_pm 	user_notify_type 	user_allow_pm 	user_allow_viewonline 	user_allow_viewemail 	user_allow_massemail 	user_options 50 	user_avatar 	user_avatar_type 	user_avatar_width  user_avatar_height user_sig  user_sig_bbcode_uid user_sig_bbcode_bitfield user_jabber user_actkey user_newpasswd 60   user_form_salt  user_new  user_reminded  user_reminded_time 
                    
                    if ($num !== 66) {
                        echo 'amount of columns must be 66';
                        exit;
                    }

                    if ($data[0] == 'user_id') {
                        // check if columns are as expected
                        continue;
                    }

                    $ip = $data[5];
                    $registration_date = date('Y-m-d H:i:s', $data[6]);
                    $username = $data[7];
                    $email = $data[11];
                    $birthdate = date('Y-m-d', strtotime($data[13]));
                    $firstname = '';
                    $infix = '';
                    $lastname = $data[7];
                    
                } else if ($type == 'generic') {
                    // title firstname infix lastname email registration_date birth_date username ip country user_info
                    
                    
                    if ($num !== 11) {
                        echo 'amount of columns must be 11';
                        exit;
                    }

                    if ($data[0] == 'title') {
                        // check if columns are as expected
                        continue;
                    }
                    
                    $title = $data[0];
                    $firstname = $data[1];
                    $infix = $data[2];
                    $lastname = $data[3];
                    $email = $data[4];
                    $registration_date = $data[5];
                    $birthdate = $data[6];
                    $username = $data[7];
                    $ip = $data[8];
                    $country = $data[9];
                    $user_info = $data[10];
                }






                if (!isset($_SESSION['ews_imported_users'])) {
                    $_SESSION['ews_imported_users'] = array();
                }

                if (!in_array($lastname . '-' . $username . '-' . $registration_date, $_SESSION['ews_imported_users'])) {
                    
                    // insert
                    $str_query2 = 'SELECT * FROM `' . self::usertable . '` WHERE username = "' . $username . '"';
                    $obj_result2 = mysqli_query($obj_db, $str_query2);
                    $arr_user2 = mysqli_fetch_array($obj_result2, MYSQLI_ASSOC);

                    if ($obj_result2 !== false && !empty($arr_user2) && $arr_user2 !== false) {
                        if (SHOW_USERNAME_IN_FORM && !empty($username)) {
                            echo 'Username already exists';
                            exit;
                        } else {
                            echo 'Username (emailaddress) already exists';
                            exit;
                        }
                    }

                    $str_query3 = 'SELECT * FROM `' . self::usertable . '` WHERE registration_date = "' . $registration_date . '" AND username = "' . $username . '" AND lastname = "' . $lastname . '"';
                    $obj_result3 = mysqli_query($obj_db, $str_query3);
                    $arr_user3 = mysqli_fetch_array($obj_result3, MYSQLI_ASSOC);


                    $str_query = 'INSERT INTO `' . self::usertable . '` ( `title`, `firstname` ,`infix` ,`lastname` ,`username`,`password` ,`email` ,`registration_date` ,' .
                            '`birth_date`, `active`, `ip`, `country`, `country_code`, `usertype`, `admin_group`, `user_info`) VALUES (' .
                            '"' . $title . '",' .
                            '"' . $firstname . '",' .
                            '"' . $infix . '",' .
                            '"' . $lastname . '",' .
                            '"' . $username . '",' .
                            '"' . User::getPasswordHashcode('temp-' . $username . '-123') . '",' .
                            '"' . $email . '",' .
                            '"' . $registration_date . '",' .
                            '"' . $birthdate . '",' .
                            '1, ' .
                            '"' . $ip . '",' .
                            '"",' .
                            '"",' .
                            '"user",' .
                            $admin_group .', '.
                            '"' . $user_info . '"' .
                            ')';

                    $res = mysqli_query($obj_db, $str_query);

                    echo 'row inserted';
                    
                    $int_user_id = mysqli_insert_id($obj_db);

                    if (isset($_GET['group']) && $_GET['group'] > 0) {
                        $current_group_id = $_GET['group'];
                    } else {
                        // find the group
                        $str_groups_query = 'SELECT * FROM groups WHERE admin_id = ' . $admin_group . ' LIMIT 1';
                        $obj_group_result = mysqli_query($obj_db, $str_groups_query);
                        $arr_group = mysqli_fetch_array($obj_group_result, MYSQLI_ASSOC);

                        $current_group_id = $arr_group['group_id'];
                    }

                    if (!empty($int_user_id) && !empty($current_group_id)) {
                        // add user in the default admin group
                        $str_insert_query = 'INSERT INTO `group_users` ( user_id, group_id ) VALUES (' .
                                $int_user_id . ',' . $current_group_id . ')';

                        $res2 = mysqli_query($obj_db, $str_insert_query);
                    }


                    $_SESSION['ews_imported_users'][] = $lastname . '-' . $username . '-' . $registration_date;
                } else {
                    echo 'already inserted';
                }

                echo '<br />' . "\n";

                // }
            }
            fclose($handle);
        }
    }

    public static function hex2rgb($hex) {
        $hex = str_replace("#", "", $hex);

        if (strlen($hex) == 3) {
            $r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1));
            $g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1));
            $b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1));
        } else {
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
        }
        $rgb = array($r, $g, $b);
        //return implode(",", $rgb); // returns the rgb values separated by commas
        return $rgb; // returns an array with the rgb values
    }
    
    public static function showPeriodListView($with_period = true) {
        global $error;

        $arr_submit = array(
            array('from', 'string', false, ''),
            array('to', 'string', false, ''),
            array('uid', 'int', false, ''),
            array('c', 'string', false, ''),
            array('w', 'int', false, 200),
            array('hrs', 'int', false, 24),
            array('ebc', 'string', false, 'FFFFCC'), // event background color
            array('bc', 'string', false, 'FFFFCC'), // background color
            array('showec', 'string', false, 'no'), // show event color
            array('lang', 'string', false, ''),
            array('ics', 'string', false, 'no'),
            array('period', 'int', false, ''),
            array('google_calid', 'string', false, ''),
            array('google_privatekey', 'string', false, ''),
        );

        $frm_submitted = validate_var($arr_submit);

        $dateformat = 'd-m-Y';
        
        if(DATEPICKER_DATEFORMAT == 'mm/dd/yy') {
            if(!empty($frm_submitted['from'])) {
                $arr_date_from = explode('-',$frm_submitted['from']);
                $frm_submitted['from'] = date('d-m-Y', strtotime($arr_date_from[1].'-'.$arr_date_from[0].'-'.$arr_date_from[2]));
            }
            
            if(!empty($frm_submitted['to'])) {
                $arr_date_to = explode('-',$frm_submitted['to']);
                $frm_submitted['to'] = date('d-m-Y', strtotime($arr_date_to[1].'-'.$arr_date_to[0].'-'.$arr_date_to[2]));
            }
            $dateformat = 'm-d-Y';
        } 
    
        
        
        $obj_smarty = new Smarty();
        $obj_smarty->compile_dir = 'templates_c/';

        if(User::isLoggedIn()) {
            $arr_user = User::getUser();
            $obj_smarty->assign('user', $arr_user['username']);
            $obj_smarty->assign('user_id', $arr_user['user_id']);
        }
     
        if (!empty($frm_submitted['lang'])) {
            $frm_submitted['lang'] = strtolower($frm_submitted['lang']);

            if ($frm_submitted['lang'] == 'nl' || $frm_submitted['lang'] == 'en' || $frm_submitted['lang'] == 'fr' || $frm_submitted['lang'] == 'de') {
                Utils::setLocaleLanguage($frm_submitted['lang']);
            }
            $lang = $frm_submitted['lang'];
        } else {
            if(User::isLoggedIn()) {
                $lang = Settings::getLanguage($arr_user['user_id']);
              
               
            } else {
                $lang = Settings::getLanguage();
            }
            Utils::setLocaleLanguage($lang);
        }

        
        
        header("Content-Type: text/html;charset=UTF-8");

        $obj_smarty->assign('iframewidth', $frm_submitted['w']);
        $obj_smarty->assign('showeventcolor', $frm_submitted['showec']);
        $obj_smarty->assign('hrs', $frm_submitted['hrs']);

        
        if (defined('AGENDA_VIEW_AMOUNT_DAYS_TO_SHOW') && AGENDA_VIEW_AMOUNT_DAYS_TO_SHOW > 0) {
            $amount_days_to_show = AGENDA_VIEW_AMOUNT_DAYS_TO_SHOW;
        } else {
            $amount_days_to_show = 5;
        }

        $arr_res['hide_from'] = false;
        $arr_res['hide_to'] = false;
        
        
        $arr_res = Events::getListviewEvents($frm_submitted, $amount_days_to_show, $with_period, $lang);
        
        
        if(!$with_period) {
            if (!empty($frm_submitted['from'])) {
                $arr_res['hide_from'] = true;
            }
            if (!empty($frm_submitted['to'])) {

                if (count($arr_res['results']) < $amount_days_to_show) {
                    $arr_res['hide_to'] = true;
                }
            }
            $obj_smarty->assign('from', date($dateformat, strtotime(current(array_keys($arr_res['results'])))));
            $obj_smarty->assign('to', date($dateformat, strtotime(end(array_keys($arr_res['results'])))));
        
            
        } else {
            $obj_smarty->assign('from', date($dateformat, strtotime($arr_res['from'])));
            $obj_smarty->assign('to', date($dateformat, strtotime($arr_res['to'])));
        
        }
        
        if (empty($frm_submitted['from']) && empty($frm_submitted['to'])) {
            $arr_res['hide_from'] = true;
        }

       
        $obj_smarty->assign('items', $arr_res['results']);
        $obj_smarty->assign('hide_from', $arr_res['hide_from']);
        $obj_smarty->assign('hide_to', $arr_res['hide_to']);

        
        
        
        $obj_smarty->display(FULLCAL_DIR . '/view/widgets/agenda.html');
       
    }


}
