<?php

class Events {

    public static function updateEvents($cal_id, $color = '') {
        global $obj_db;

        $str_query = 'UPDATE events SET ' . (!empty($color) ? 'color = "' . $color . '"' : '') . ' WHERE calendar_id = ' . $cal_id;
        $obj_result = mysqli_query($obj_db, $str_query);

        if ($obj_result !== false) {
            return true;
        }
        return false;
    }

    public static function startEdit($int_event_id) {
        global $obj_db;

        $bln_can_edit = false;
        $str_token = null;

        $str_query = 'SELECT *, DAY(edit_date) FROM `current_editing`' .
                ' WHERE `active` = 1 AND DAY(edit_date) = ' . date('d') . ' AND `event_id` =' . $int_event_id;

        $res1 = mysqli_query($obj_db, $str_query);

        if ($res1 !== false) {
            $arr_file = mysqli_fetch_assoc($res1);

            $current_user_id = 1000000;

            if (is_null($arr_file) || $arr_file === false || empty($arr_file)) {

                // no one else is editing , you can edit
                if (User::isLoggedIn()) {
                    $arr_user = User::getUser();
                    if (!is_null($arr_user) && !empty($arr_user) && is_array($arr_user)) {
                        $current_user_id = $arr_user['user_id'];
                    }
                } else if (isset($_SESSION['calendar-uid']['uid'])) {
                    $current_user_id = $_SESSION['calendar-uid']['uid'];
                }

                $str_token = sha1('randomtoken_' . $int_event_id . $current_user_id);

                // insert a row
                $str_query = 'INSERT INTO `current_editing` (`event_id`, `user_id`, `token`, `edit_date`, `active`) ' .
                        ' VALUES (' . $int_event_id . ', ' . $current_user_id . ',"' . $str_token . '", "' . date('Y-m-d H:i:s') . '", 1) ';

                $res2 = mysqli_query($obj_db, $str_query);

                $bln_can_edit = true;
            } else {
                if (User::isLoggedIn()) {
                    // if it is me, then it's okay
                    if ($arr_file['user_id'] == $current_user_id) {
                        $bln_can_edit = true;

                        $str_token = $arr_file['token'];
                    }
                } else if (strtotime($arr_file['edit_date']) + 3600 < time()) {
                    // if it's longer than an hour ago, then it's okay
                    $bln_can_edit = true;

                    $str_token = $arr_file['token'];
                }
            }
        } else {
            $bln_can_edit = true;
        }

        return array('bln_can_edit' => $bln_can_edit, 'token' => $str_token);
    }

    public static function stopEdit($event_id = -1, $str_token = '') {
        global $obj_db;

        if (!is_null($str_token) && !empty($str_token)) {
            $str_query = 'UPDATE current_editing SET `active` = 0 WHERE event_id = ' . $event_id . ' AND `token` = "' . $str_token . '" AND `active` = 1';
            $obj_result = mysqli_query($obj_db, $str_query);

            if ($obj_result !== false) {
                return true;
            }
        }
    }

    public static function stopEditAfterCalendarRefresh() {
        global $obj_db;

        $str_query2 = 'UPDATE `current_editing` SET `active` = 0 WHERE `active` = 1';

        mysqli_query($obj_db, $str_query2);
    }

    public static function getCurrentEvent($ajax = false) {
        global $obj_db;
        $arr_return = array();

        $str_query = 'SELECT c.*, e.title, e.time_end, e.date_end, e.allDay, concat_ws(" ",e.date_start,e.time_start) as start,concat_ws(" ",e.date_end,e.time_end) as end FROM events e LEFT JOIN calendars c ON(c.calendar_id = e.calendar_id) WHERE "' . date('Y-m-d') . '" BETWEEN `date_start` AND `date_end` ';

        $res = mysqli_query($obj_db, $str_query);

        if ($res !== false && !empty($res)) {
            while ($arr_line = mysqli_fetch_array($res, MYSQLI_ASSOC)) {
                if ($arr_line['allDay'] || (time() > strtotime($arr_line['start']) && time() < strtotime($arr_line['end']))) {
                    $arr_line['end_is_today'] = false;
                    if ($arr_line['date_end'] == date('Y-m-d')) {
                        $arr_line['end_is_today'] = true;
                    }
                    $arr_return[] = $arr_line;
                }
            }
            if ($ajax) {
                echo json_encode(array('current' => $arr_return));
                exit;
            } else {
                return $arr_return;
            }
        }
    }

    public static function getLastAddedEvents($amount = 5, $ajax = false) {
        global $obj_db;
        $arr_return = array();

        if (User::isLoggedIn()) {
            $arr_user = User::getUser();

            $arr_cal_ids = array();
            $arr_admin_calendars = Calendar::getCalendarsOfAdmin($arr_user['user_id']);
            foreach ($arr_admin_calendars as $cal) {
                $arr_cal_ids[] = $cal['calendar_id'];
            }

            $str_query = 'SELECT e.*,c.*, concat_ws(" ",e.date_start,e.time_start) as start,concat_ws(" ",e.date_end,e.time_end) as end FROM events e LEFT JOIN calendars c ON(c.calendar_id = e.calendar_id) ';
            $str_query .= ' WHERE 1 ';

            if ((User::isSuperAdmin() || User::isAdmin()) && ADMIN_HAS_FULL_CONTROL) {
                
            } else {

                // $str_query .= ' AND c.share_type = "public" OR c.creator_id = '. $arr_user['user_id'];
            }
            if (!User::isSuperAdmin()) {
                $str_query .= ' AND c.calendar_id IN(' . implode(',', $arr_cal_ids) . ')';
            }


            $str_query .= ' ORDER BY e.`create_date` DESC LIMIT ' . $amount;

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

    public static function getEventsFromExchange($str_start, $str_end, $int_calendar_id = -1, $str_calendar_name = '') {
        $arr_events = array();

        // require_once EXTERNAL_DIR.'/php-ews-master/Smarty.class.php';
        if ($int_calendar_id == -1) {
            return array();
        }
        $key = sha1($str_calendar_name);
        $str_username = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode(CalendarOption::getOption('exchange_username', $int_calendar_id)), MCRYPT_MODE_CBC, md5($key)), "\0");
        $str_password = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode(CalendarOption::getOption('exchange_password', $int_calendar_id)), MCRYPT_MODE_CBC, md5($key)), "\0");

        require_once EXTERNAL_DIR . '/php-ews-master/EWSType.php';
        require_once EXTERNAL_DIR . '/php-ews-master/EWSType/ItemType.php';

        require_once(EXTERNAL_DIR . '/php-ews-master/EWSType/RecurrencePatternBaseType.php' );

        $files = glob(EXTERNAL_DIR . "/php-ews-master/EWSType/*.php");
        foreach ($files as $filename) {
            if ($filename != '.' && $filename != '..') {
                require_once($filename);
            }
        }

        // $files = glob(EXTERNAL_DIR."/php-ews-master/EWSType/*.php", GLOB_BRACE);
        //  foreach($files as $filename) {
        //   require_once( $filename );
        // }
        require_once EXTERNAL_DIR . '/php-ews-master/EWSType/CalendarItemType.php';
        require_once EXTERNAL_DIR . '/php-ews-master/EWSType/BodyType.php';
        require_once EXTERNAL_DIR . '/php-ews-master/ExchangeWebServices.php';
        require_once EXTERNAL_DIR . '/php-ews-master/NTLMSoapClient.php';
        require_once EXTERNAL_DIR . '/php-ews-master/NTLMSoapClient/Exchange.php';
        require_once EXTERNAL_DIR . '/php-ews-master/EWS_Exception.php';


        $ews = new ExchangeWebServices('outlook.office365.com', $str_username, $str_password);  //outlook.office365.com/EWS/Exchange.asmx

        try {

            // Set init class
            $request = new EWSType_FindItemType();
            // Use this to search only the items in the parent directory in question or use ::SOFT_DELETED
            // to identify "soft deleted" items, i.e. not visible and not in the trash can.
            $request->Traversal = EWSType_ItemQueryTraversalType::SHALLOW;
            // This identifies the set of properties to return in an item or folder response
            $request->ItemShape = new EWSType_ItemResponseShapeType();
            $request->ItemShape->BaseShape = EWSType_DefaultShapeNamesType::DEFAULT_PROPERTIES;

            // Define the timeframe to load calendar items
            $request->CalendarView = new EWSType_CalendarViewType();
            $request->CalendarView->StartDate = $str_start . 'T00:00:01'; // an ISO8601 date e.g. 2012-06-12T15:18:34+03:00
            $request->CalendarView->EndDate = $str_end . 'T23:59:59'; // an ISO8601 date later than the above
            // Only look in the "calendars folder"
            $request->ParentFolderIds = new EWSType_NonEmptyArrayOfBaseFolderIdsType();
            $request->ParentFolderIds->DistinguishedFolderId = new EWSType_DistinguishedFolderIdType();
            $request->ParentFolderIds->DistinguishedFolderId->Id = EWSType_DistinguishedFolderIdNameType::CALENDAR;

            // Send request
            $response = $ews->FindItem($request);

            // Loop through each item if event(s) were found in the timeframe specified
            if ($response->ResponseMessages->FindItemResponseMessage->RootFolder->TotalItemsInView > 0) {
                $events = $response->ResponseMessages->FindItemResponseMessage->RootFolder->Items->CalendarItem;
                foreach ($events as $event) {
                    $arr_event = array();

                    $arr_event['id'] = $event->ItemId->Id;
                    $arr_event['change_key'] = $event->ItemId->ChangeKey;
                    $arr_event['start'] = date('Y-m-d H:i:s', strtotime($event->Start));
                    $arr_event['end'] = date('Y-m-d H:i:s', strtotime($event->End));
                    $arr_event['title'] = $event->Subject;
                    $arr_event['location'] = isset($event->Location) ? $event->Location : '';
                    $arr_event['phone'] = '';
                    $arr_event['myurl'] = '';
                    $arr_event['description'] = '';

                    $arr_event['allowEdit'] = false;
                    $arr_event['editable'] = false;
                    $arr_event['deletable'] = false;
                    $arr_event['canChangeColor'] = false;

                    $arr_events[] = $arr_event;
                }
            } else {
                // No items returned
            }




//            $request = new EWSType_FindItemType();
//            $request->Traversal = EWSType_ItemQueryTraversalType::SHALLOW;
//
//            $request->FolderShape = new EWSType_FolderResponseShapeType();
//            $request->FolderShape->BaseShape = EWSType_DefaultShapeNamesType::ALL_PROPERTIES;
//
//            $request->ItemShape = new EWSType_ItemResponseShapeType();
//            $request->ItemShape->BaseShape = EWSType_DefaultShapeNamesType::DEFAULT_PROPERTIES;
//
//            $request->CalendarView = new EWSType_CalendarViewType();
//            $request->CalendarView->StartDate = date('c', strtotime('01/01/2011 -00'));
//            $request->CalendarView->EndDate = date('c', strtotime('01/31/2011 -00'));
//
//            $request->ParentFolderIds = new EWSType_NonEmptyArrayOfBaseFolderIdsType();
//            $request->ParentFolderIds->DistinguishedFolderId = new EWSType_DistinguishedFolderIdType();
//            $request->ParentFolderIds->DistinguishedFolderId->Id = EWSType_DistinguishedFolderIdNameType::CALENDAR;
//            
//            // make the actual call
//            $response = $ews->FindFolder($request);
//            echo '<pre>'.print_r((array)$response, true).'</pre>';
//            
//            exit;
//            // start building the find folder request
//            $request = new EWSType_FindFolderType();
//            $request->Traversal = EWSType_FolderQueryTraversalType::SHALLOW;
//            $request->FolderShape = new EWSType_FolderResponseShapeType();
//            $request->FolderShape->BaseShape = EWSType_DefaultShapeNamesType::ALL_PROPERTIES;
//
//            // configure the view
//            $request->IndexedPageFolderView = new EWSType_IndexedPageViewType();
//            $request->IndexedPageFolderView->BasePoint = 'Beginning';
//            $request->IndexedPageFolderView->Offset = 0;
//
//            // set the starting folder as the inbox
//            $request->ParentFolderIds = new EWSType_NonEmptyArrayOfBaseFolderIdsType();
//            $request->ParentFolderIds->DistinguishedFolderId = new EWSType_DistinguishedFolderIdType();
//            $request->ParentFolderIds->DistinguishedFolderId->Id = EWSType_DistinguishedFolderIdNameType::INBOX;
//
//            // make the actual call
//            $response = $ews->FindFolder($request);
//            echo '<pre>'.print_r((array)$response, true).'</pre>';
        } catch (EWS_Exception $ex) {
            echo json_encode(array('success' => false, 'error' => $ex->getMessage()));
            exit;
        }

        return $arr_events;
    }

    /**
     * 
     * @global type $obj_db
     * @param type $frm_submitted
     * @return string
     */
    public static function getEvents($frm_submitted) {
        global $obj_db;
        $arr_content = array();

        $bln_loggedin = User::isLoggedIn();
        
        if (isset($frm_submitted['start'])) {
            if (is_numeric($frm_submitted['start'])) {
                $str_start = date("Y-m-d", $frm_submitted['start']);
            } else {
                $str_start = substr($frm_submitted['start'], 0, 10);
            }
        }
        if (isset($frm_submitted['end'])) {
            if (is_numeric($frm_submitted['end'])) {
                $str_end = date("Y-m-d", $frm_submitted['end']);
            } else {
                $str_end = substr($frm_submitted['end'], 0, 10);
            }
        }

        if (!empty($frm_submitted['cal_id'])) {
            if(strstr($frm_submitted['cal_id'], ',')) {
                $arr_calendars = explode(',', $frm_submitted['cal_id']);
                $arr_calendars = array_unique($arr_calendars);
            } else {
                $arr_calendars = array($frm_submitted['cal_id']);
            }
            
            foreach($arr_calendars as $calendar_id) {
                
                if (isset($_SESSION['ews_calendars']) && isset($_SESSION['ews_calendars'][$calendar_id]) && !empty($_SESSION['ews_calendars'][$calendar_id])) {
                    $arr_calendar = $_SESSION['ews_calendars'][$calendar_id];
                } else {
                    $arr_calendar = Calendar::getCalendar($calendar_id);
                }


                if (isset($arr_calendar['origin']) && $arr_calendar['origin'] == 'exchange') {
                    /**
                     * GET EVENTS FROM EXCHANGE
                     */
                    $is_owner = false;
                    $owner_allowed_to_see_without_token = false;

                    if ($bln_loggedin) {
                        $is_owner = Calendar::isOwner($calendar_id);
                        $owner_allowed_to_see_without_token = defined('OWNER_EXCHANGE_CAL_ALLOWED_WITHOUT_TOKEN') && OWNER_EXCHANGE_CAL_ALLOWED_WITHOUT_TOKEN === true;
                    }

                    if ($is_owner && $owner_allowed_to_see_without_token) {
                        // the owner of the calendar doesn't have to fill in the token to see the events from the exchange calendar
                        $arr_content[] = Events::getEventsFromExchange($str_start, $str_end, $calendar_id, $arr_calendar['name']);

                        return $arr_content;
                    } else {
                        if ($arr_calendar['exchange_extra_secure'] == 1) {
                            if (!isset($_SESSION['ews_exchange_private_token']) || $_SESSION['ews_exchange_private_token'] !== CalendarOption::getOption('exchange_token', $calendar_id)) {
                                return array();
                            }
                        } else {
                            $arr_content[] = Events::getEventsFromExchange($str_start, $str_end, $calendar_id, $arr_calendar['name']);

                            return $arr_content;
                        }
                    }
                }
            }
            
        }


        // get startdate and enddate from repeating_events table
        $arr_rep_events = array();

        $str_query_r = 'SELECT * FROM repeating_events ';

        $res1 = mysqli_query($obj_db, $str_query_r);

        while ($arr_line = mysqli_fetch_array($res1, MYSQLI_ASSOC)) {
            $arr_rep_events[$arr_line['rep_event_id']] = $arr_line;
        }



        $str_query = 'SELECT *, event_id as id, concat_ws(" ",date_start,time_start) as start,concat_ws(" ",date_end,time_end) as end FROM events e ' .
                ' LEFT JOIN `calendars` c ON(e.calendar_id = c.calendar_id)' .
                ' WHERE c.calendar_id IS NOT NULL AND ((date_start BETWEEN "' . $str_start . '" AND "' . $str_end . '") OR ( date_end BETWEEN "' . $str_start . '" AND "' . $str_end . '")' .
                '  OR (("' . $str_start . '" BETWEEN date_start AND date_end) AND ("' . $str_end . '" BETWEEN date_start AND date_end) ) )';

        if (!empty($frm_submitted['cal_id'])) {
            $str_query .= ' AND c.calendar_id IN ( ' . $frm_submitted['cal_id'] . ')';
        }

        if (!empty($frm_submitted['uid'])) {
            $str_query .= ' AND (e.user_id = ' . $frm_submitted['uid'] . ' OR e.team_member_id = ' . $frm_submitted['uid'] . ')';
        }
        
        // split option_ids in dropdown1 and dropdown2
      //  $frm_submitted['option_id']
       // $arr_drd1_fields = CustomFields::getCustomDropdown1(true);
       // $arr_drd2_fields = CustomFields::getCustomDropdown2(true);
                
    
        $bln_linked_dropdowns = Settings::getSetting('dropdowns_are_linked');
   
        if(!empty($frm_submitted['option_id'])) {    
            if($bln_linked_dropdowns == 'on') {
                $str_query .= ' AND ((e.dropdown1_option_id IN (' . $frm_submitted['option_id'] . ')) AND (e.dropdown2_option_id IN (' . $frm_submitted['option_id'] . ')))';
            } else {
                $str_query .= ' AND ((e.dropdown1_option_id IN (' . $frm_submitted['option_id'] . ')) OR (e.dropdown2_option_id IN (' . $frm_submitted['option_id'] . ')))';
            }
        }
        
            
        
        $str_query .= ' order by start';
//echo $str_query;
        $arr_calendar_options = CalendarOption::getAllOptions();
      
        $obj_result = mysqli_query($obj_db, $str_query);

        while ($arr_line = mysqli_fetch_array($obj_result, MYSQLI_ASSOC)) {
           
            if(isset($arr_calendar_options[$arr_line['calendar_id']]) && isset($arr_calendar_options[$arr_line['calendar_id']]['next_days_visible']) && !empty($arr_calendar_options[$arr_line['calendar_id']]['next_days_visible'])) {
                $next_days_visible = (int)$arr_calendar_options[$arr_line['calendar_id']]['next_days_visible'];
        
                if(!$bln_loggedin || ($bln_loggedin && User::isUser())) {
                    if(strtotime($arr_line['start']) > strtotime('+ '.$next_days_visible.' DAY')) {
                        continue;
                    }
                }
                
            }    
           
            $arr_line['allDay'] = $arr_line['allDay'] == 0 ? false : true;
            $arr_line['allowEdit'] = User::canEdit($arr_line['user_id'], $arr_line['calendar_id']);
            $arr_line['editable'] = User::canEdit($arr_line['user_id'], $arr_line['calendar_id']);
            $arr_line['deletable'] = User::canDelete($arr_line['user_id'], $arr_line['calendar_id']);
            $arr_line['canChangeColor'] = User::canChangeColor($arr_line['user_id'], $arr_line['calendar_id']);
            //   $arr_line['borderColor'] = 'lightgray';

            if (isset($_SESSION['ews_calendars']) && isset($_SESSION['ews_calendars'][$arr_line['calendar_id']]) && !empty($_SESSION['ews_calendars'][$arr_line['calendar_id']])) {
                $arr_cal = $_SESSION['ews_calendars'][$arr_line['calendar_id']];
            } else {
                $arr_cal = Calendar::getCalendar($arr_line['calendar_id']);
            }

            $arr_line['canMail'] = Calendar::calCanMail($arr_cal);
            
      
            $arr_line['show_description_field'] = (bool) $arr_calendar_options[$arr_line['calendar_id']]['show_description_field']; //CalendarOption::getOption('show_description_field', $arr_line['calendar_id'], true);
            $arr_line['show_location_field'] = (bool) $arr_calendar_options[$arr_line['calendar_id']]['show_location_field']; //CalendarOption::getOption('show_location_field', $arr_line['calendar_id'], true);
            $arr_line['show_phone_field'] = (bool) $arr_calendar_options[$arr_line['calendar_id']]['show_phone_field']; //CalendarOption::getOption('show_phone_field', $arr_line['calendar_id'], false);
            $arr_line['show_team_member_field'] = isset($arr_calendar_options[$arr_line['calendar_id']]['show_team_member_field']) ? (bool) $arr_calendar_options[$arr_line['calendar_id']]['show_team_member_field'] : false; //CalendarOption::getOption('show_team_member_field', $arr_line['calendar_id'], false);
            $arr_line['show_url_field'] = (bool) $arr_calendar_options[$arr_line['calendar_id']]['show_url_field']; //CalendarOption::getOption('show_url_field', $arr_line['calendar_id'], false);
            $arr_line['show_dropdown_1_field'] = isset($arr_calendar_options[$arr_line['calendar_id']]['show_dropdown_1_field']) ? (bool) $arr_calendar_options[$arr_line['calendar_id']]['show_dropdown_1_field'] : false; //CalendarOption::getOption('show_dropdown_1_field', $arr_line['calendar_id'], false);
            $arr_line['show_dropdown_2_field'] = isset($arr_calendar_options[$arr_line['calendar_id']]['show_dropdown_2_field']) ? (bool) $arr_calendar_options[$arr_line['calendar_id']]['show_dropdown_2_field'] : false; //CalendarOption::getOption('show_dropdown_2_field', $arr_line['calendar_id'], false);
            
            $arr_line['add_team_member_to_title'] = isset($arr_calendar_options[$arr_line['calendar_id']]['add_team_member_to_title']) ? (bool) $arr_calendar_options[$arr_line['calendar_id']]['add_team_member_to_title'] : true; //CalendarOption::getOption('show_team_member_field', $arr_line['calendar_id'], false);
            $arr_line['add_custom_dropdown1_to_title'] = isset($arr_calendar_options[$arr_line['calendar_id']]['add_custom_dropdown1_to_title']) ? (bool) $arr_calendar_options[$arr_line['calendar_id']]['add_custom_dropdown1_to_title'] : true; //CalendarOption::getOption('show_team_member_field', $arr_line['calendar_id'], false);
            $arr_line['add_custom_dropdown2_to_title'] = isset($arr_calendar_options[$arr_line['calendar_id']]['add_custom_dropdown2_to_title']) ? (bool) $arr_calendar_options[$arr_line['calendar_id']]['add_custom_dropdown2_to_title'] : true; //CalendarOption::getOption('show_team_member_field', $arr_line['calendar_id'], false);
            
            
            
            if (defined('SORT_ALL_CALENDARS_BY_CAL_ORDERID') && SORT_ALL_CALENDARS_BY_CAL_ORDERID) {
                $arr_line['sorter'] = $arr_line['order_id'];
            } else if (defined('SORT_ALL_CALENDARS_BY_CALENDARID') && SORT_ALL_CALENDARS_BY_CALENDARID) {
                $arr_line['sorter'] = $arr_line['calendar_id'];
            }

            // i already do this while opening an event
            // $arr_line['files'] = Events::getFiles($arr_line['event_id']);

            if ($arr_line['repeating_event_id'] > 0) {
                // repeating events must have the same id
                $arr_line['id'] = $arr_line['repeating_event_id'];

                $arr_line['textColor'] = 'black';
                if (defined('RECURRING_EVENT_TEXT_COLOR')) {
                    $recurring_event_text_color = RECURRING_EVENT_TEXT_COLOR;
                    if (!empty($recurring_event_text_color)) {
                        $arr_line['textColor'] = $recurring_event_text_color;
                    }
                }

                $arr_line['editable'] = false; // to unable dragging
                if ($arr_line['allowEdit']) {
                    $arr_line['className'] = 'not-draggable cursorhand';
                }


                if (isset($arr_rep_events[$arr_line['repeating_event_id']])) {
                    $arr_line['rep_start'] = $arr_rep_events[$arr_line['repeating_event_id']]['startdate'];
                    $arr_line['rep_end'] = $arr_rep_events[$arr_line['repeating_event_id']]['enddate'];
                    $arr_line['rep_event'] = $arr_rep_events[$arr_line['repeating_event_id']];

                    if ($arr_line['rep_event']['rep_interval'] == 'Y') {
                        // get 'until' year
                        $end_year = date('Y', strtotime($arr_line['rep_end']));

                        $recur_yearmonthday = str_pad($arr_line['rep_event']['yearmonthday'], 2, 0, STR_PAD_LEFT);
                        $recur_yearmonth = str_pad($arr_line['rep_event']['yearmonth'] + 1, 2, 0, STR_PAD_LEFT);

                        // recurring event in endyear
                        $endyear_recurring_date = $end_year . '-' . $recur_yearmonth . '-' . $recur_yearmonthday;

                        if (strtotime($endyear_recurring_date) <= strtotime($arr_line['rep_end'])) {
                            $arr_line['rep_event']['until'] = $end_year;
                        } else {
                            $arr_line['rep_event']['until'] = $end_year - 1;
                        }
                    }
                }

                if (isset($arr_line['rep_start'])) {
                    $arr_line['rep_start_day'] = (int) date('d', strtotime($arr_line['rep_start']));
                }
            }

            if (isset($_SESSION['employee-work-schedule-sd']) && isset($_SESSION['employee-work-schedule-ft'])) {
                if (substr($arr_line['start'], 0, 10) == $_SESSION['employee-work-schedule-sd'] && $arr_line['title'] == $_SESSION['employee-work-schedule-ft']) {
                    $arr_line['textColor'] = 'red';
                    $arr_line['backgroundColor'] = 'white';
                    $arr_line['borderColor'] = 'red';
                }
            }
            $arr_line['assigned_by_name'] = '';
            if (isset($arr_line['assigned_by']) && $arr_line['assigned_by'] > 0) {
                if (isset($_SESSION['ews_users']) && isset($_SESSION['ews_users'][$arr_line['assigned_by']]) && !empty($_SESSION['ews_users'][$arr_line['assigned_by']])) {
                    $arr_user = $_SESSION['ews_users'][$arr_line['assigned_by']];
                } else {
                    $arr_user = User::getUserById($arr_line['assigned_by']);
                    $_SESSION['ews_users'][$arr_line['assigned_by']] = $arr_user;
                }
                $arr_line['assigned_by_name'] = $arr_user['firstname'] . ' ' . (!empty($arr_user['infix']) ? $arr_user['infix'] . ' ' : '') . $arr_user['lastname'];
            }
            $arr_line['team_member'] = '';
            if (isset($arr_line['team_member_id']) && $arr_line['team_member_id'] > 0) {
                if (isset($_SESSION['ews_users']) && isset($_SESSION['ews_users'][$arr_line['team_member_id']]) && !empty($_SESSION['ews_users'][$arr_line['team_member_id']])) {
                    $arr_team_member = $_SESSION['ews_users'][$arr_line['team_member_id']];
                } else {
                    $arr_team_member = User::getUserById($arr_line['team_member_id']);
                }
                $arr_line['team_member'] = $arr_team_member['firstname'] . ' ' . (!empty($arr_team_member['infix']) ? $arr_team_member['infix'] : '') . $arr_team_member['lastname'];
                
            }
        
            $arr_line['dropdown1'] = '';
            if($arr_line['show_dropdown_1_field']) {
                if(isset($arr_line['dropdown1_option_id']) && $arr_line['dropdown1_option_id'] > 0) {
                    if (isset($_SESSION['ews_custom_field_options']) && isset($_SESSION['ews_custom_field_options'][$arr_line['dropdown1_option_id']]) && !empty($_SESSION['ews_custom_field_options'][$arr_line['dropdown1_option_id']])) {
                        $arr_option = $_SESSION['ews_custom_field_options'][$arr_line['dropdown1_option_id']];
                    } else {
                        $arr_option = CustomFields::getCustomDropdownOption($arr_line['dropdown1_option_id']);
                        $_SESSION['ews_custom_field_options'][$arr_line['dropdown1_option_id']] = $arr_option;
                    }
                    $arr_line['dropdown1'] = $arr_option['text'];
                    $arr_line['dropdown1_color'] = $arr_option['color'];
                    
                }
            }
            
            $arr_line['dropdown2'] = '';
            if($arr_line['show_dropdown_2_field']) {
                if(isset($arr_line['dropdown2_option_id']) && $arr_line['dropdown2_option_id'] > 0) {
                    if (isset($_SESSION['ews_custom_field_options']) && isset($_SESSION['ews_custom_field_options'][$arr_line['dropdown2_option_id']]) && !empty($_SESSION['ews_custom_field_options'][$arr_line['dropdown2_option_id']])) {
                        $arr_option = $_SESSION['ews_custom_field_options'][$arr_line['dropdown2_option_id']];
                    } else {
                        $arr_option = CustomFields::getCustomDropdownOption($arr_line['dropdown2_option_id']);
                        $_SESSION['ews_custom_field_options'][$arr_line['dropdown2_option_id']] = $arr_option;
                    }
                    $arr_line['dropdown2'] = $arr_option['text'];
                    $arr_line['dropdown2_color'] = $arr_option['color'];
                }
            }
            
            $arr_line['nightshift'] = false;

            if ((int) (ltrim(date('H', strtotime($arr_line['start'])), '0')) > (int) (ltrim(date('H', strtotime($arr_line['end'])), '0'))) {
                $arr_line['nightshift'] = true;
            }
            //  $arr_line['phone'] = '<a href="tel:'.$arr_line['phone'].'">'.$arr_line['phone'].'</a>';

            
            $arr_content[] = $arr_line;
        }
        //mysqli_close($obj_db);    // turned off, gave problems with pdf export 
        //unset($obj_db);

        unset($_SESSION['employee-work-schedule-sd']);
        unset($_SESSION['employee-work-schedule-ft']);

        return $arr_content;
    }

    public static function getFiles($int_event_id, $current_user_id = '') {
        $arr_return = array();
        global $obj_db;

        if (User::isLoggedIn()) {
            $arr_user = User::getUser();
        }

        $str_query = 'SELECT * FROM event_files WHERE `event_id` = ' . $int_event_id;

        $obj_result = mysqli_query($obj_db, $str_query);

        while ($arr_line = mysqli_fetch_array($obj_result, MYSQLI_ASSOC)) {
            // only possible to delete your own files
            if (User::isLoggedIn() && $arr_line['create_id'] == $arr_user['user_id']) {
                $arr_line['loggedin_user_can_delete'] = true;
            } else {
                $arr_line['loggedin_user_can_delete'] = false;
            }

            $arr_return[] = $arr_line;
        }
        return $arr_return;
    }

    public static function removeFile($int_event_id, $int_event_file_id) {
        $arr_return = array();
        global $obj_db;

        if (User::isLoggedIn()) {
            $arr_user = User::getUser();

            $str_query1 = 'SELECT * FROM event_files WHERE `event_id` = ' . $int_event_id . ' AND `event_file_id` = ' . $int_event_file_id;


            $res1 = mysqli_query($obj_db, $str_query1);

            if ($res1 !== false) {
                $arr_file = mysqli_fetch_array($res1, MYSQLI_ASSOC);
            }

            if (!is_null($arr_file)) {
                $str_query = 'DELETE FROM event_files WHERE `event_id` = ' . $int_event_id . ' AND `event_file_id` = ' . $int_event_file_id;

                $obj_result = mysqli_query($obj_db, $str_query);

                if ($obj_result) {
                    // delete file from uploads folder
                    unlink(FULLCAL_DIR . '/uploads/' . $arr_file['filename'] . '.' . $arr_file['file_extension']);

                    return true;
                }
            }
        }
        return false;
    }

    public static function copyEvent($event_id, $new_startdate) {
        global $obj_db;

        $cols = Utils::getColumnsOfTable('events', array('event_id'));

        $result1 = $obj_db->query('SELECT * FROM `events` WHERE `event_id` = ' . $event_id);

        $arr_event = mysqli_fetch_array($result1, MYSQLI_ASSOC);

        /**
         * normal event or recurring event
         */
        if ($arr_event['repeating_event_id'] > 0) {
            $result2 = $obj_db->query('SELECT * FROM `repeating_events` WHERE `rep_event_id` = ' . $arr_event['repeating_event_id']);

            $arr_pattern = mysqli_fetch_array($result2, MYSQLI_ASSOC);

            $arr_event = array_merge($arr_event, $arr_pattern);

            $arr_event['str_date_start'] = $new_startdate;

            // how many days was the original pattern
            $arr_days_between = Utils::getDaysBetween($arr_pattern['startdate'], $arr_pattern['enddate']);
            $cnt_days_between = count($arr_days_between);

            // new repeating event needs the same amount of days in the pattern
            $new_enddate = date('Y-m-d', strtotime($new_startdate . ' +' . ($cnt_days_between - 1) . 'days'));

            $arr_event['str_date_start'] = $new_startdate . ' ' . $arr_event['time_start'];
            $arr_event['date_start'] = $new_startdate . ' ' . $arr_event['time_start'];

            $arr_event['str_date_end'] = $new_enddate . ' ' . $arr_event['time_end'];
            $arr_event['date_end'] = $new_enddate . ' ' . $arr_event['time_end'];

            $arr_event['interval'] = $arr_event['rep_interval'];
            $arr_event['cal_id'] = $arr_event['calendar_id'];

            // insert events following the pattern
            $arr_days = Utils::getDaysInPattern($arr_event);

            $bln_success = self::insertRepeatingEvent($arr_days, $arr_event, $arr_event['user_id']);
            if ($bln_success) {
                return true;
            }
        } else {

            $arr_event['create_date'] = date('Y-m-d H:i:s');


            $arr_days_between = Utils::getDaysBetween($arr_event['date_start'], $arr_event['date_end']);
            $cnt_days_between = count($arr_days_between);

            $new_enddate = date('Y-m-d', strtotime($new_startdate . ' +' . ($cnt_days_between - 1) . 'days'));

            $arr_event['date_start'] = $new_startdate;

            $arr_event['date_end'] = $new_enddate;

            $arr_event = array_values($arr_event);
            unset($arr_event[0]);

            $insertSQL = 'INSERT INTO `events` (`' . implode('`, `', $cols) . '`) VALUES ("' . implode('","', $arr_event) . '")';

            $obj_result = mysqli_query($obj_db, $insertSQL);

            if ($obj_result !== false) {
                return true;
            }
        }
    }

    public static function insertEvent($frm_submitted, $current_user_id = '', $from_usergroup_dditem=false) {

        global $obj_db;
        $arr_user = null;
        $arr_event_user = null;
        $arr_calendar = array();
        $str_error = '';

        if ($frm_submitted['cal_id'] > 0) {
            $arr_calendar = Calendar::getCalendar($frm_submitted['cal_id']);
        }

        if(stristr($frm_submitted['color'], 'hsv')) {
            $frm_submitted['color'] = $arr_calendar['calendar_color'];
        }
        
        $bln_isloggedin = false;
        if (User::isLoggedIn()) {
            $bln_isloggedin = true;
            $arr_user = User::getUser();
        }

        $is_repeating_event = isset($frm_submitted['rep_event_id']) && $frm_submitted['rep_event_id'] > 0;

        $assigned_by = null;

        // if the add is done with a usergroup dd-item then check if it should be assigned to the user linked to the dd-item
        if (!$bln_isloggedin && $arr_calendar['share_type'] == 'public') {
            // no assigning
            $current_user_id = '';
        } else if (!empty($current_user_id) && !empty($arr_calendar) && isset($arr_calendar['dditems_usergroup_id']) && $arr_calendar['dditems_usergroup_id'] > 0 && isset($arr_calendar['assign_dditem_to_user']) && (bool) $arr_calendar['assign_dditem_to_user']) {
            // do nothing, $current_user_id is used to assign to the correct user
            $assigned_by = $arr_user['user_id'];
        } else if ($bln_isloggedin && $frm_submitted['team_member_id'] > 0 && (bool)$frm_submitted['assign'] && !empty($arr_calendar) && isset($arr_calendar['show_team_member_field']) && (bool)$arr_calendar['show_team_member_field'] && isset($arr_calendar['notify_assign_teammember']) && (bool) $arr_calendar['notify_assign_teammember']) {
            $current_user_id = $frm_submitted['team_member_id'];
            $assigned_by = $arr_user['user_id'];
        } else {
            // do not assign to the linked user but to the admin that is logged in
            $current_user_id = '';
        }

        
        if ($bln_isloggedin || (!$bln_isloggedin && $arr_calendar['share_type'] == 'public' && $arr_calendar['can_add']) || ($is_repeating_event && ($arr_calendar['can_edit'] || $arr_calendar['can_add']))) {

            if (empty($current_user_id)) {

                if (!is_null($arr_user) && !empty($arr_user) && is_array($arr_user)) {
                    $current_user_id = $arr_user['user_id'];
                } else if (isset($_SESSION['calendar-uid']['uid'])) {
                    $current_user_id = $_SESSION['calendar-uid']['uid'];
                }
            }

            if (IGNORE_TIMEZONE) {
                $str_startdate = substr($frm_submitted['str_date_start'], 0, 10);
                $str_enddate = substr($frm_submitted['str_date_end'], 0, 10);
                $str_starttime = substr($frm_submitted['str_date_start'], 11);
                $str_endtime = substr($frm_submitted['str_date_end'], 11);
            } else {
                $str_startdate = date('Y-m-d', $frm_submitted['date_start']);
                $str_enddate = date('Y-m-d', $frm_submitted['date_end']);
                $str_starttime = date('H:i:s', $frm_submitted['date_start']);
                $str_endtime = date('H:i:s', $frm_submitted['date_end']);
            }

            $bln_nightshift = false;

            if (!$frm_submitted['allDay'] && strtotime(date('Y-m-d') . ' ' . $str_starttime) > strtotime(date('Y-m-d') . ' ' . $str_endtime)) {
                // nightshift
                $bln_nightshift = true;
                if ($str_startdate == $str_enddate) {
                    $str_enddate = date('Y-m-d', strtotime($str_enddate . ' +1 day'));
                }
            }

            $str_query = 'INSERT INTO events ( title, description, calendar_id, location, phone, myurl, repeating_event_id, user_id, assigned_by, color, team_member_id, dropdown1_option_id, dropdown2_option_id, date_start, time_start, date_end, time_end, create_date, allday) ' .
                    'VALUES ("' . mysqli_real_escape_string($obj_db, $frm_submitted['title']) . '",' .
                    '"' . mysqli_real_escape_string($obj_db, $frm_submitted['description']) . '",' .
                    '"' . $frm_submitted['cal_id'] . '",' .
                    '"' . mysqli_real_escape_string($obj_db, $frm_submitted['location']) . '",' .
                    '"' . mysqli_real_escape_string($obj_db, $frm_submitted['phone']) . '",' .
                    '"' . mysqli_real_escape_string($obj_db, $frm_submitted['myurl']) . '",' .
                    (!empty($frm_submitted['rep_event_id']) ? $frm_submitted['rep_event_id'] : 0) . ',' .
                    '"' . $current_user_id . '",' .
                    (empty($assigned_by) ? 0 : $assigned_by) . ',' .
                    '"' . $frm_submitted['color'] . '",' .
                    '"' . $frm_submitted['team_member_id'] . '",' .
                    '"' . $frm_submitted['dropdown1_option_id'] . '",' .
                    '"' . $frm_submitted['dropdown2_option_id'] . '",' .
                    '"' . $str_startdate . '",' .
                    '"' . $str_starttime . '",' .
                    '"' . $str_enddate . '",' .
                    '"' . $str_endtime . '",' .
                    '"' . date('Y-m-d H:i:s') . '"' .
                    ($frm_submitted['allDay'] === true || $frm_submitted['allDay'] === 1 ? ' ,1' : ' ,0') . ')';
            //((date('H:i:s', $frm_submitted['date_start']) == '00:00:00' && date('H:i:s', $frm_submitted['date_end']) == '00:00:00') || $frm_submitted['allDay'] === true ? ' ,1' : ' ,0') . ')';

            $obj_result = mysqli_query($obj_db, $str_query);

            if ($obj_result !== false) {
                $str_query = 'SELECT *, event_id as id, concat_ws(" ",date_start,time_start) as start,concat_ws(" ",date_end,time_end) as end ' .
                        'FROM events WHERE event_id = ' . mysqli_insert_id($obj_db);

                $obj_result2 = mysqli_query($obj_db, $str_query);

                $arr_event = mysqli_fetch_array($obj_result2, MYSQLI_ASSOC);

                if (empty($arr_calendar)) {
                    $arr_calendar = Calendar::getCalendar($arr_event['calendar_id']);
                }
                $arr_event['allDay'] = $arr_event['allDay'] == 0 ? false : true;
                $arr_event['allowEdit'] = true; //	= User::canEdit($arr_event['user_id']);
                $arr_event['editable'] = true; //= User::canEdit($arr_event['user_id']);
                $arr_event['deletable'] = User::canDelete($arr_event['user_id']);
                $arr_event['canChangeColor'] = User::canChangeColor($arr_event['user_id'], $arr_event['calendar_id']);
                $arr_event['canMail'] = Calendar::calCanMail($arr_calendar);

                $arr_event['show_description_field'] = (bool) CalendarOption::getOption('show_description_field', $arr_event['calendar_id'], false);
                $arr_event['show_location_field'] = (bool) CalendarOption::getOption('show_location_field', $arr_event['calendar_id'], false);
                $arr_event['show_phone_field'] = (bool) CalendarOption::getOption('show_phone_field', $arr_event['calendar_id'], false);
                $arr_event['show_team_member_field'] = (bool) CalendarOption::getOption('show_team_member_field', $arr_event['calendar_id'], false);
                $arr_event['show_url_field'] = (bool) CalendarOption::getOption('show_url_field', $arr_event['calendar_id'], false);
                $arr_event['show_dropdown_1_field'] = (bool) CalendarOption::getOption('show_dropdown_1_field', $arr_event['calendar_id'], false);
                $arr_event['show_dropdown_2_field'] = (bool) CalendarOption::getOption('show_dropdown_2_field', $arr_event['calendar_id'], false);
            
            
                if (!empty($arr_calendar)) {
                    // notification mail to admin
                    if (Calendar::calMailEventModsToAdmin($arr_calendar)) {
                        $arr_event_user = User::getUserById($arr_event['user_id']);

                        $to_mail = Calendar::getCalendarAdminEmail($arr_calendar);
                        if (!empty($to_mail)) {
                            $bln_send = Utils::sendMail('mail_event', $to_mail, '', $frm_submitted, $arr_event_user);
                        }
                    }
                    // notification mail to the assigned user
                    if (!empty($current_user_id) && ($from_usergroup_dditem || $frm_submitted['assign'])) {
                        if (Calendar::notifyAssignedUser($arr_calendar, $current_user_id) || Calendar::notifyAssignedTeamMember($arr_calendar, $frm_submitted)) {
                            // get the user and email address
                            $arr_user = User::getUserById($current_user_id);

                            if (isset($arr_user['email']) && !empty($arr_user['email']) && Utils::checkEmail($arr_user['email'])) {
                                $str_email = $arr_user['email'];
                                $arr_event_user = $arr_user;
                                $arr_admin = array();

                                if (!is_null($assigned_by)) {
                                    $arr_admin = User::getUserById($assigned_by);
                                }

                                // $arr_user is the logged in admin
                                $bln_send = Utils::sendMail('mail_assigned_event', $str_email, '', $frm_submitted, $arr_event_user, '', $arr_admin);

                                if (!$bln_send) {
                                    $str_error = 'Failed to send the notification. Check if the PHP mail function is working.';
                                }
                            } else {
                                // incorrect or no email address
                                $str_error = 'Problem while sending the notification. Check the email address of the user.';
                            }
                        }
                    }
                }
            } else {
                return false;
            }
            $arr_event['error'] = $str_error;

            $arr_event['nightshift'] = false;

            if ((int) (ltrim(date('H', strtotime($arr_event['start'])), '0')) > (int) (ltrim(date('H', strtotime($arr_event['end'])), '0'))) {
                $arr_event['nightshift'] = true;
            }

            $arr_event['team_member'] = '';
            if (isset($arr_event['team_member_id']) && $arr_event['team_member_id'] > 0) {
                if (isset($_SESSION['ews_users']) && isset($_SESSION['ews_users'][$arr_event['team_member_id']]) && !empty($_SESSION['ews_users'][$arr_event['team_member_id']])) {
                    $arr_team_member = $_SESSION['ews_users'][$arr_event['team_member_id']];
                } else {
                    $arr_team_member = User::getUserById($arr_event['team_member_id']);
                }
                $arr_event['team_member'] = $arr_team_member['firstname'] . ' ' . (!empty($arr_team_member['infix']) ? $arr_team_member['infix'] : '') . $arr_team_member['lastname'];
                
            }
            
            $arr_event['dropdown1'] = '';
            if($arr_event['show_dropdown_1_field']) {
                if(isset($arr_event['dropdown1_option_id']) && $arr_event['dropdown1_option_id'] > 0) {
                    if (isset($_SESSION['ews_custom_field_options']) && isset($_SESSION['ews_custom_field_options'][$arr_event['dropdown1_option_id']]) && !empty($_SESSION['ews_custom_field_options'][$arr_event['dropdown1_option_id']])) {
                        $arr_option = $_SESSION['ews_custom_field_options'][$arr_event['dropdown1_option_id']];
                    } else {
                        $arr_option = CustomFields::getCustomDropdownOption($arr_event['dropdown1_option_id']);
                        $_SESSION['ews_custom_field_options'][$arr_event['dropdown1_option_id']] = $arr_option;
                    }
                    $arr_event['dropdown1'] = $arr_option['text'];
                }
            }
            
            $arr_event['dropdown2'] = '';
            if($arr_event['show_dropdown_2_field']) {
                if(isset($arr_event['dropdown2_option_id']) && $arr_event['dropdown2_option_id'] > 0) {
                    if (isset($_SESSION['ews_custom_field_options']) && isset($_SESSION['ews_custom_field_options'][$arr_event['dropdown2_option_id']]) && !empty($_SESSION['ews_custom_field_options'][$arr_event['dropdown2_option_id']])) {
                        $arr_option = $_SESSION['ews_custom_field_options'][$arr_event['dropdown2_option_id']];
                    } else {
                        $arr_option = CustomFields::getCustomDropdownOption($arr_event['dropdown2_option_id']);
                        $_SESSION['ews_custom_field_options'][$arr_event['dropdown2_option_id']] = $arr_option;
                    }
                    $arr_event['dropdown2'] = $arr_option['text'];
                }
            }
            
            return $arr_event;
        } else {
            return false;
        }
    }

    public static function updateEvent($frm_submitted) {
        global $obj_db;
        $arr_event = array();

//        if($frm_submitted['cal_id'] != $frm_submitted['calendar_id']) {
//            $frm_submitted['cal_id'] = 
//        }
        $arr_calendar = array();
        if ($frm_submitted['cal_id'] > 0) {
            $arr_calendar = Calendar::getCalendar($frm_submitted['cal_id']);
        } else {
            $int_calendar_id = Calendar::getCalendarIdByEventId($frm_submitted['event_id']);
            $arr_calendar = Calendar::getCalendar($int_calendar_id);
        }

        if(stristr($frm_submitted['color'], 'hsv')) {
            $frm_submitted['color'] = $arr_calendar['calendar_color'];
        }
        
        $bln_change_cal_id = false;

        if (defined('MOVE_EVENT_TO_OTHER_CALENDAR_POSSIBLE') && MOVE_EVENT_TO_OTHER_CALENDAR_POSSIBLE === true) {
            if ($frm_submitted['calendar_id'] > 0 && $frm_submitted['calendar_id'] != $frm_submitted['cal_id']) {
                $bln_change_cal_id = true;

                // set color to color of new calendar
                $arr_calendar = Calendar::getCalendar($frm_submitted['calendar_id']);
                $frm_submitted['color'] = $arr_calendar['calendar_color'];
            }
        }

        $bln_logged_in = false;
        if (User::isLoggedIn()) {
            $bln_logged_in = true;
            $arr_user = User::getUser();
        }

        $current_user_id = '';
        $assigned_by = '';
        if ($frm_submitted['team_member_id'] > 0) {
            // teammember selected

            if (User::isLoggedIn()) {
                // assign teammember checkbox was checked
                if ((bool)$frm_submitted['assign']) {
                    $current_user_id = $frm_submitted['team_member_id'];
                    $assigned_by = $arr_user['user_id'];
                } else if ((bool)$frm_submitted['unassign']) {
                    // unassign 
                    $current_user_id = $arr_user['user_id'];
                    $assigned_by = 0;
                }
            }
        }

        if (IGNORE_TIMEZONE) {
            $str_startdate = substr($frm_submitted['str_date_start'], 0, 10);
            $str_enddate = substr($frm_submitted['str_date_end'], 0, 10);
            $str_starttime = substr($frm_submitted['str_date_start'], 11);
            $str_endtime = substr($frm_submitted['str_date_end'], 11);
        } else {
            $str_startdate = date('Y-m-d', $frm_submitted['date_start']);
            $str_enddate = date('Y-m-d', $frm_submitted['date_end']);
            $str_starttime = date('H:i:s', $frm_submitted['date_start']);
            $str_endtime = date('H:i:s', $frm_submitted['date_end']);
        }

        $str_query = 'UPDATE events SET `change_date` = "' . date('Y-m-d H:i:s') . '" ' .
                (!empty($frm_submitted['title']) ? ', `title` = "' . $frm_submitted['title'] . '"' : '') .
                (!empty($frm_submitted['team_member_id']) && $frm_submitted['team_member_id'] > 0 ? ', `team_member_id` = "' . $frm_submitted['team_member_id'] . '"' : '') .
                (!empty($frm_submitted['dropdown1_option_id']) && $frm_submitted['dropdown1_option_id'] > 0 ? ', `dropdown1_option_id` = "' . $frm_submitted['dropdown1_option_id'] . '"' : '') .
                (!empty($frm_submitted['dropdown2_option_id']) && $frm_submitted['dropdown2_option_id'] > 0 ? ', `dropdown2_option_id` = "' . $frm_submitted['dropdown2_option_id'] . '"' : '') .
                (!empty($current_user_id) ? ', `user_id` = "' . $current_user_id . '"' : '') .
                (!empty($current_user_id) && (!empty($assigned_by) || $assigned_by == 0) ? ', `assigned_by` = "' . $assigned_by . '"' : '') .
                (!empty($frm_submitted['location']) ? ', `location` = "' . $frm_submitted['location'] . '"' : '') .
                (!empty($frm_submitted['phone']) ? ', `phone` = "' . $frm_submitted['phone'] . '"' : '') .
                (!empty($frm_submitted['myurl']) ? ', `myurl` = "' . $frm_submitted['myurl'] . '"' : '') .
                (!empty($frm_submitted['description']) ? ', `description` = "' . addslashes($frm_submitted['description']) . '"' : '') .
                (isset($frm_submitted['color']) && $frm_submitted['color'] != 'undefined' ? ', `color` = "' . $frm_submitted['color'] . '"' : '') ;
                
        if($frm_submitted['updateThisItem'] === true) {
            // do not update dates, calendar_id
        } else {
            $str_query .= ', `date_end` = "' . $str_enddate . '", `date_start` = "' . $str_startdate . '" ' ;
            $str_query .= ($bln_change_cal_id ? ', `calendar_id` = "' . $frm_submitted['calendar_id'] . '"' : '');
                
        }   
       
        if(isset($frm_submitted['repeating_divergent']) && $frm_submitted['repeating_divergent'] === true) {
            $str_query .= ', `repeating_divergent` = 1 ' ;
        }
        if(isset($frm_submitted['repeating_disconnect']) && $frm_submitted['repeating_disconnect'] === true) {
            $str_query .= ' , `repeating_event_id` = 0 ';
            
            // todo : what do we expect when pressed ' restore pattern'  ? 
            // in repeating event table I have to remember that this item must not be restored
        }
        
        
        $str_query .= ', `time_start` = "' . (isset($frm_submitted['allDay']) && $frm_submitted['allDay'] ? '00:00:00' : $str_starttime) . '" ' .
                ', `time_end` = "' . (isset($frm_submitted['allDay']) && $frm_submitted['allDay'] ? '00:00:00' : $str_endtime) . '" ' .
                ((isset($frm_submitted['allDay']) && $frm_submitted['allDay']) || ($str_starttime == '00:00:00' && $str_endtime == '00:00:00') ? ' ,allDay = 1' : ' ,allDay = 0') .
                ' WHERE `event_id` = ' . $frm_submitted['event_id'];

//        if(isset($_SESSION['calendar-uid']['uid']) && $_SESSION['calendar-uid']['uid'] > 0) {
//            $bln_users_can_change_items_from_others = Settings::getAdminSetting('users_can_change_items_from_others', $_SESSION['calendar-uid']['uid']);
//        } else {
//            $bln_users_can_change_items_from_others = USERS_CAN_CHANGE_ITEMS_FROM_OTHERS;
//        }
        //if(($bln_users_can_change_items_from_others) || (User::isLoggedIn() && (User::isAdmin() || User::isSuperAdmin()))) {
        $bln_admin_and_full_control = ADMIN_HAS_FULL_CONTROL && (User::isAdmin() || User::isSuperAdmin());
        $bln_public_cal_and_edit_allowed = $arr_calendar['share_type'] == 'public' && $arr_calendar['can_edit'];


        $bln_user_in_group = $bln_logged_in && $arr_calendar['share_type'] == 'private_group' && Calendar::UserInGroup($arr_calendar, $arr_user['user_id']);

        if ($bln_logged_in && ($bln_admin_and_full_control || $bln_user_in_group)) {
            // don't check on user_id
        } else if ($bln_public_cal_and_edit_allowed) {
            // don't check on user_id
        } else if (isset($_SESSION['calendar-uid']) && isset($_SESSION['calendar-uid']['uid'])) {
            $str_query .= ' AND user_id = ' . $_SESSION['calendar-uid']['uid'];
        }

        $obj_result = mysqli_query($obj_db, $str_query);

        if ($obj_result !== false) {
            $str_query = 'SELECT *, event_id as id, concat_ws(" ",date_start,time_start) as start,concat_ws(" ",date_end,time_end) as end ' .
                    'FROM events WHERE event_id = ' . $frm_submitted['event_id'];
            $obj_result = mysqli_query($obj_db, $str_query);
            $arr_event = mysqli_fetch_array($obj_result, MYSQLI_ASSOC);

            $arr_event['allDay'] = $arr_event['allDay'] == 0 ? false : true;
            $arr_event['allowEdit'] = User::canEdit($arr_event['user_id']);
            $arr_event['deletable'] = User::canDelete($arr_event['user_id']);

            $arr_event['bln_change_cal_id'] = $bln_change_cal_id ? true : false;

            $arr_event['nightshift'] = false;

            if ((int) (ltrim(date('H', strtotime($arr_event['start'])), '0')) > (int) (ltrim(date('H', strtotime($arr_event['end'])), '0'))) {
                $arr_event['nightshift'] = true;
            }
            
       
            if(isset($arr_event['dropdown1_option_id']) && $arr_event['dropdown1_option_id'] > 0) {
                if (isset($_SESSION['ews_custom_field_options']) && isset($_SESSION['ews_custom_field_options'][$arr_event['dropdown1_option_id']]) && !empty($_SESSION['ews_custom_field_options'][$arr_event['dropdown1_option_id']])) {
                    $arr_option = $_SESSION['ews_custom_field_options'][$arr_event['dropdown1_option_id']];
                } else {
                    $arr_option = CustomFields::getCustomDropdownOption($arr_event['dropdown1_option_id']);
                    $_SESSION['ews_custom_field_options'][$arr_event['dropdown1_option_id']] = $arr_option;
                }
                $arr_event['add_custom_dropdown1_to_title'] = isset($arr_calendar['add_custom_dropdown1_to_title']) ? (bool) $arr_calendar['add_custom_dropdown1_to_title'] : true; 
                $arr_event['dropdown1'] = $arr_option['text'];
                $arr_event['dropdown1_color'] = $arr_option['color'];
            }
            if(isset($arr_event['dropdown2_option_id']) && $arr_event['dropdown2_option_id'] > 0) {
                if (isset($_SESSION['ews_custom_field_options']) && isset($_SESSION['ews_custom_field_options'][$arr_event['dropdown2_option_id']]) && !empty($_SESSION['ews_custom_field_options'][$arr_event['dropdown2_option_id']])) {
                    $arr_option = $_SESSION['ews_custom_field_options'][$arr_event['dropdown2_option_id']];
                } else {
                    $arr_option = CustomFields::getCustomDropdownOption($arr_event['dropdown2_option_id']);
                    $_SESSION['ews_custom_field_options'][$arr_event['dropdown2_option_id']] = $arr_option;
                }
                $arr_event['add_custom_dropdown2_to_title'] = isset($arr_calendar['add_custom_dropdown2_to_title']) ? (bool) $arr_calendar['add_custom_dropdown2_to_title'] : true; 
                $arr_event['dropdown2'] = $arr_option['text'];
                $arr_event['dropdown2_color'] = $arr_option['color'];
            }
            
            if (empty($arr_calendar)) {
                $arr_calendar = Calendar::getCalendar($arr_event['calendar_id']);
            }

            // notification mail to admin
            if (Calendar::calMailEventModsToAdmin($arr_calendar)) {
                $arr_user = User::getUserById($arr_event['user_id']);

                $to_mail = Calendar::getCalendarAdminEmail($arr_calendar);
                if (!empty($to_mail)) {
                    $bln_send = Utils::sendMail('mail_event', $to_mail, '', $frm_submitted, $arr_user);
                }
            }
            // notification to user
            $bln_send_assigned_email = false;
            $bln_send_unassigned_email = false;
        
            if ($frm_submitted['assign']) {
                $bln_send_assigned_email = Calendar::notifyAssignedTeamMember($arr_calendar, $frm_submitted);
     
                $arr_event['assigned_by_name'] = '';
                if (!is_null($assigned_by) && $assigned_by > 0) {
                    if (isset($_SESSION['ews_users']) && isset($_SESSION['ews_users'][$assigned_by]) && !empty($_SESSION['ews_users'][$assigned_by])) {
                        $arr_user = $_SESSION['ews_users'][$assigned_by];
                    } else {
                        $arr_user = User::getUserById($assigned_by);
                        $_SESSION['ews_users'][$assigned_by] = $arr_user;
                    }
                    $arr_event['assigned_by_name'] = $arr_user['firstname'] . ' ' . (!empty($arr_user['infix']) ? $arr_user['infix'] . ' ' : '') . $arr_user['lastname'];
                }
            } else if ($frm_submitted['unassign']) {
                $bln_send_unassigned_email = Calendar::notifyUnAssignedTeamMember($arr_calendar, $frm_submitted);
                $arr_event['assigned_by_name'] = '';
                $arr_event['assigned_by'] = '';
            }
            
            if ($bln_send_assigned_email) {
                // get the user and email address
                $arr_user = User::getUserById($current_user_id);

                if (isset($arr_user['email']) && !empty($arr_user['email']) && Utils::checkEmail($arr_user['email'])) {
                    $str_email = $arr_user['email'];
                    $arr_event_user = $arr_user;
                    $arr_admin = array();

                    if (!is_null($assigned_by)) {
                        $arr_admin = User::getUserById($assigned_by);
                    }

                    // $arr_user is the logged in admin
                    $bln_send = Utils::sendMail('mail_assigned_event', $str_email, '', $frm_submitted, $arr_event_user, '', $arr_admin);

                    if (!$bln_send) {
                        $str_error = 'Failed to send the notification. Check if the PHP mail function is working.';
                    }
                } else {
                    // incorrect or no email address
                    $str_error = 'Problem while sending the notification. Check the email address of the user.';
                }
            }
            
            if ($bln_send_unassigned_email) {
                // get the user and email address
                $arr_user = User::getUserById($current_user_id);

                if (isset($arr_user['email']) && !empty($arr_user['email']) && Utils::checkEmail($arr_user['email'])) {
                    $str_email = $arr_user['email'];
                    $arr_event_user = $arr_user;
                    $arr_admin = array();

                    if (!is_null($assigned_by)) {
                        $arr_admin = User::getUserById($assigned_by);
                    }

                    // $arr_user is the logged in admin
                    $bln_send = Utils::sendMail('mail_unassigned_event', $str_email, '', $frm_submitted, $arr_event_user, '', $arr_admin);

                    if (!$bln_send) {
                        $str_error = 'Failed to send the notification. Check if the PHP mail function is working.';
                    }
                } else {
                    // incorrect or no email address
                    $str_error = 'Problem while sending the notification. Check the email address of the user.';
                }
            }
                        
            
        }
        return $arr_event;
    }

    public static function resizeEvent($frm_submitted) {
        global $obj_db;

        $arr_calendar = array();
        if ($frm_submitted['cal_id'] > 0) {
            $arr_calendar = Calendar::getCalendar($frm_submitted['cal_id']);
        } else {
            $int_calendar_id = Calendar::getCalendarIdByEventId($frm_submitted['event_id']);
            $arr_calendar = Calendar::getCalendar($int_calendar_id);
        }
        if (IGNORE_TIMEZONE) {
            $str_startdate = substr($frm_submitted['str_date_start'], 0, 10);
            $str_enddate = substr($frm_submitted['str_date_end'], 0, 10);
            $str_starttime = substr($frm_submitted['str_date_start'], 11);
            $str_endtime = substr($frm_submitted['str_date_end'], 11);
        } else {
            $frm_submitted['date_start'] -= TIME_OFFSET;
            $frm_submitted['date_end'] -= TIME_OFFSET;

            $str_startdate = date('Y-m-d', $frm_submitted['date_start']);
            $str_enddate = date('Y-m-d', $frm_submitted['date_end']);
            $str_starttime = date('H:i:s', $frm_submitted['date_start']);
            $str_endtime = date('H:i:s', $frm_submitted['date_end']);
        }
        
        $str_query = 'UPDATE events SET date_start = "' . $str_startdate . '" ' .
                ', date_end = "' . $str_enddate . '" ' .
                ', time_start = "' . $str_starttime . '" ' .
                ', time_end = "' . $str_endtime . '" ' .
                ' WHERE event_id = ' . $frm_submitted['event_id'];

//        if(isset($_SESSION['calendar-uid']['uid']) && $_SESSION['calendar-uid']['uid'] > 0) {
//            $bln_users_can_change_items_from_others = Settings::getAdminSetting('users_can_change_items_from_others', $_SESSION['calendar-uid']['uid']);
//        } else {
//            $bln_users_can_change_items_from_others = USERS_CAN_CHANGE_ITEMS_FROM_OTHERS;
//        }
        //if($bln_users_can_change_items_from_others) {

        $bln_admin_and_full_control = ADMIN_HAS_FULL_CONTROL && (User::isAdmin() || User::isSuperAdmin());
        $bln_public_cal_and_edit_allowed = $arr_calendar['share_type'] == 'public' && $arr_calendar['can_edit'];

        if (User::isLoggedIn() && $bln_admin_and_full_control) {
            // don't check on user_id
        } else if (!User::isLoggedIn() && $bln_public_cal_and_edit_allowed) {
            // don't check on user_id
        } else {
            $str_query .= ' AND user_id = ' . $_SESSION['calendar-uid']['uid'];
        }

        $obj_result = mysqli_query($obj_db, $str_query);

        if ($obj_result !== false) {
            $str_query = 'SELECT *, event_id as id, concat_ws(" ",date_start,time_start) as start,concat_ws(" ",date_end,time_end) as end ' .
                    'FROM events WHERE event_id = ' . $frm_submitted['event_id'];
            $obj_result = mysqli_query($obj_db, $str_query);
            $arr_event = mysqli_fetch_array($obj_result, MYSQLI_ASSOC);

            $arr_event['allDay'] = $arr_event['allDay'] == 0 ? false : true;
            $arr_event['allowEdit'] = User::canEdit($arr_event['user_id']);
            $arr_event['deletable'] = User::canDelete($arr_event['user_id']);

            if(isset($arr_event['dropdown1_option_id']) && $arr_event['dropdown1_option_id'] > 0) {
                if (isset($_SESSION['ews_custom_field_options']) && isset($_SESSION['ews_custom_field_options'][$arr_event['dropdown1_option_id']]) && !empty($_SESSION['ews_custom_field_options'][$arr_event['dropdown1_option_id']])) {
                    $arr_option = $_SESSION['ews_custom_field_options'][$arr_event['dropdown1_option_id']];
                } else {
                    $arr_option = CustomFields::getCustomDropdownOption($arr_event['dropdown1_option_id']);
                    $_SESSION['ews_custom_field_options'][$arr_event['dropdown1_option_id']] = $arr_option;
                }
                $arr_event['add_custom_dropdown1_to_title'] = isset($arr_calendar['add_custom_dropdown1_to_title']) ? (bool) $arr_calendar['add_custom_dropdown1_to_title'] : true; 
                $arr_event['dropdown1'] = $arr_option['text'];
                $arr_event['dropdown1_color'] = $arr_option['color'];
            }
            if(isset($arr_event['dropdown2_option_id']) && $arr_event['dropdown2_option_id'] > 0) {
                if (isset($_SESSION['ews_custom_field_options']) && isset($_SESSION['ews_custom_field_options'][$arr_event['dropdown2_option_id']]) && !empty($_SESSION['ews_custom_field_options'][$arr_event['dropdown2_option_id']])) {
                    $arr_option = $_SESSION['ews_custom_field_options'][$arr_event['dropdown2_option_id']];
                } else {
                    $arr_option = CustomFields::getCustomDropdownOption($arr_event['dropdown2_option_id']);
                    $_SESSION['ews_custom_field_options'][$arr_event['dropdown2_option_id']] = $arr_option;
                }
                $arr_event['add_custom_dropdown2_to_title'] = isset($arr_calendar['add_custom_dropdown2_to_title']) ? (bool) $arr_calendar['add_custom_dropdown2_to_title'] : true; 
                $arr_event['dropdown2'] = $arr_option['text'];
                $arr_event['dropdown2_color'] = $arr_option['color'];
            }
            
            return $arr_event;
        }
        return false;
    }

    public static function getEventByEventId($event_id) {
        global $obj_db;
        $arr_event = array();

        $str_query = 'SELECT *, event_id as id, concat_ws(" ",date_start,time_start) as start,concat_ws(" ",date_end,time_end) as end ' .
                'FROM events WHERE event_id = ' . $event_id;
        $obj_result = mysqli_query($obj_db, $str_query);

        if ($obj_result !== false) {
            $arr_event = mysqli_fetch_array($obj_result, MYSQLI_ASSOC);
        }

        return $arr_event;
    }

//	public static function deleteEvent($int_event_id) {
//		global $obj_db;
//
//		$str_query = 'DELETE FROM events WHERE event_id = '.$int_event_id;
//
//		if(defined('USERS_CAN_DELETE_ITEMS_FROM_OTHERS') && USERS_CAN_DELETE_ITEMS_FROM_OTHERS) {
//			// don't check on user_id
//		} else {
//			$str_query .= ' AND user_id = '. $_SESSION['calendar-uid']['uid'];
//		}
//
//		$obj_result = mysqli_query($obj_db, $str_query);
//
//		if($obj_result !== false) {
//			return true;
//		}
//		return false;
//	}

    public static function deleteEvent($frm_submitted) {

        global $obj_db;

        if ($frm_submitted['event_id'] > 0) {
            $int_calendar_id = Calendar::getCalendarIdByEventId($frm_submitted['event_id']);
            $arr_calendar = Calendar::getCalendar($int_calendar_id);

            $arr_event = self::getEventByEventId($frm_submitted['event_id']);
            $bln_can_delete = User::canDelete($arr_event['user_id'], $int_calendar_id);

            if (!$bln_can_delete) {
                return false;
            }
        }

        if (isset($frm_submitted['delete_all']) && $frm_submitted['delete_all'] === true && isset($frm_submitted['rep_event_id']) && $frm_submitted['rep_event_id'] > 0) {

            // part of repeat , delete all items
            //$str_query = 'DELETE FROM events WHERE repeating_event_id = ' . $frm_submitted['rep_event_id'] . ' AND user_id = ' . $_SESSION['calendar-uid']['uid'];
            $str_query = 'DELETE FROM events WHERE repeating_event_id = ' . $frm_submitted['rep_event_id'];
            $obj_result = mysqli_query($obj_db, $str_query);

            if ($obj_result !== false) {

                // delete row from repeating_events
                $str_query = 'DELETE FROM repeating_events WHERE rep_event_id = ' . $frm_submitted['rep_event_id'];
                $obj_result = mysqli_query($obj_db, $str_query);
                if ($obj_result !== false) {
                    return true;
                }
            }
        } else if ($frm_submitted['rep_event_id'] > 0) {

            // part of repeat , delete only this one
            //$str_query = 'DELETE FROM events WHERE event_id = ' . $frm_submitted['event_id'] . ' AND user_id = ' . $_SESSION['calendar-uid']['uid'];
            $str_query = 'DELETE FROM events WHERE event_id = ' . $frm_submitted['event_id'];
            $obj_result = mysqli_query($obj_db, $str_query);

            // the pattern is broken, put bln_broken in db,
            // so that we know it that we have to show the repair pattern button
            $str_update_query = 'UPDATE repeating_events SET bln_broken = 1 WHERE rep_event_id = ' . $frm_submitted['rep_event_id'];
            $res = mysqli_query($obj_db, $str_update_query);


            if ($obj_result !== false) {

                // check if there is only one item left in this repeat,
                // if yes then delete row in repeating_events table and set repeating_event_id to 0 in events table
                if (self::OneHasLeftOfThisRepeat($frm_submitted['rep_event_id'])) {
                    $str_query = 'DELETE FROM repeating_events WHERE rep_event_id = ' . $frm_submitted['rep_event_id'];
                    $obj_result = mysqli_query($obj_db, $str_query);
                    if ($obj_result !== false) {

                        // update row
                        //$str_update_query = 'UPDATE events SET repeating_event_id = 0 WHERE event_id = '.$frm_submitted['event_id'];
                        $str_update_query = 'UPDATE events SET repeating_event_id = 0 WHERE repeating_event_id = ' . $frm_submitted['rep_event_id'];
                        $obj_result = mysqli_query($obj_db, $str_query);
                        if ($obj_result !== false) {
                            return true;
                        }
                    } else {
                        echo 'Error while trying to delete the row in repeating_events table';
                    }
                }
                return true;
            } else {
                echo 'Error while trying to delete the event';
            }
        } else {

            /*
             * normal event
             */
            $str_query = 'DELETE FROM events WHERE event_id = ' . $frm_submitted['event_id'];

            $int_calendar_id = Calendar::getCalendarIdByEventId($frm_submitted['event_id']);
            $arr_calendar = Calendar::getCalendar($int_calendar_id);

            $bln_admin_and_full_control = ADMIN_HAS_FULL_CONTROL && (User::isAdmin() || User::isSuperAdmin());

            if (User::isOwner() || $bln_admin_and_full_control || (isset($arr_calendar['share_type']) && $arr_calendar['share_type'] == 'private_group' && isset($_SESSION['calendar-uid']) && Calendar::UserInGroup($arr_calendar, $_SESSION['calendar-uid']['uid'])) || (isset($arr_calendar['share_type']) && $arr_calendar['share_type'] == 'public')) {
                // dont need to search on user_id
            } else if (isset($_SESSION['calendar-uid'])) {
                $str_query.= ' AND user_id = ' . $_SESSION['calendar-uid']['uid'];
            }

            $obj_result = mysqli_query($obj_db, $str_query);

            if ($obj_result !== false) {


                return true;
            }
        }

        return false;
    }

    public static function OneHasLeftOfThisRepeat($rep_event_id) {
        global $obj_db;
        $arr_content = array();

        $str_query = 'SELECT * FROM events WHERE repeating_event_id = ' . $rep_event_id;
        $obj_result = mysqli_query($obj_db, $str_query);

        while ($arr_line = mysqli_fetch_array($obj_result, MYSQLI_ASSOC)) {
            $arr_content[] = $arr_line;
        }
        if (count($arr_content) == 1) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 
     * @global type $obj_db
     * @param type $arr_dates
     * @param type $frm_submitted
     */
    public static function insertRepeatingEvent($arr_dates, $frm_submitted, $int_user_id = -1) {
        global $obj_db;

        if ($int_user_id == -1) {
            $int_user_id = $_SESSION['calendar-uid']['uid'];
        }

        // set the first date as source

        if (IGNORE_TIMEZONE) {
            $str_startdate = substr($frm_submitted['str_date_start'], 0, 10);
            $str_enddate = substr($frm_submitted['str_date_end'], 0, 10);
            $str_starttime = substr($frm_submitted['str_date_start'], 11);
            $str_endtime = substr($frm_submitted['str_date_end'], 11);
        } else {
            $str_startdate = date('Y-m-d', $frm_submitted['date_start']);
            $str_enddate = date('Y-m-d', $frm_submitted['date_end']);
            $str_starttime = date('H:i:s', $frm_submitted['date_start']);
            $str_endtime = date('H:i:s', $frm_submitted['date_end']);
        }
        
        $bln_nightshift = false;

        if (!$frm_submitted['allDay'] && strtotime(date('Y-m-d') . ' ' . $str_starttime) > strtotime(date('Y-m-d') . ' ' . $str_endtime)) {
            // nightshift
            $bln_nightshift = true;
        }

        $str_query = 'INSERT INTO repeating_events ( rep_interval, weekdays, monthday, yearmonthday, yearmonth, every_x_days, every_x_weeks, startdate, enddate) VALUES ' .
                '("' . $frm_submitted['interval'] . '",' .
                '"' . $frm_submitted['weekdays'] . '",' .
                '"' . $frm_submitted['monthday'] . '",' .
                '"' . $frm_submitted['yearmonthday'] . '",' .
                '"' . $frm_submitted['yearmonth'] . '",' .
                '"' . $frm_submitted['every_x_days'] . '",' .
                '"' . $frm_submitted['every_x_weeks'] . '",' .
                '"' . $str_startdate . '",' .
                '"' . $str_enddate . '")';

        $res = mysqli_query($obj_db, $str_query);

        $int_rep_event_id = mysqli_insert_id($obj_db);

        // check if moved to another calendar
        $bln_change_cal_id = false;

        if (defined('MOVE_EVENT_TO_OTHER_CALENDAR_POSSIBLE') && MOVE_EVENT_TO_OTHER_CALENDAR_POSSIBLE === true && isset($frm_submitted['cal_id'])) {
            if (isset($frm_submitted['calendar_id']) && $frm_submitted['calendar_id'] > 0 && $frm_submitted['calendar_id'] != $frm_submitted['cal_id']) {
                $bln_change_cal_id = true;
            }
        }

        $str_query_r = 'INSERT INTO events ( title, location, description, phone, myurl, team_member_id, dropdown1_option_id, dropdown2_option_id, calendar_id, repeating_event_id, user_id, color, date_start, time_start, date_end, time_end, create_date, allday) VALUES ';

        foreach ($arr_dates as $key => $date) {
            if ($key != 0) {
                $str_query_r .= ',';
            }
            $str_query_r .= '("' . $frm_submitted['title'] . '",' .
                    '"' . $frm_submitted['location'] . '",' .
                    '"' . $frm_submitted['description'] . '",' .
                    '"' . $frm_submitted['phone'] . '",' .
                    '"' . $frm_submitted['myurl'] . '",' .
                    '"' . $frm_submitted['team_member_id'] . '",' .
                    '"' . $frm_submitted['dropdown1_option_id'] . '",' .
                    '"' . $frm_submitted['dropdown2_option_id'] . '",' .
                    ($bln_change_cal_id ? $frm_submitted['calendar_id'] : $frm_submitted['cal_id']) . ',' .
                    $int_rep_event_id . ',' .
                    $int_user_id . ',' .
                    '"' . $frm_submitted['color'] . '",' .
                    '"' . $date . '",' .
                    '"' . $str_starttime . '",';
            if ($bln_nightshift) {
                // enddate +1 day
                $enddate = date('Y-m-d', strtotime($date . ' +1 day'));
                $str_query_r .= '"' . $enddate . '",';
            } else {
                $str_query_r .= '"' . $date . '",';
            }

            $str_query_r .= '"' . $str_endtime . '",' .
                    '"' . date('Y-m-d H:i:s') . '"' .
                    (($str_starttime == '00:00:00' && $str_endtime == '00:00:00') || $frm_submitted['allDay'] == 1 ? ' ,1' : ' ,0') . ')';
        }

        $res2 = mysqli_query($obj_db, $str_query_r);

        if ($res2) {
            $arr_calendar = array();
            if (isset($frm_submitted['cal_id']) && $frm_submitted['cal_id'] > 0) {
                $arr_calendar = Calendar::getCalendar($frm_submitted['cal_id']);
            }
            if (!empty($arr_calendar)) {
                // notification mail to admin
                if (Calendar::calMailEventModsToAdmin($arr_calendar)) {
                    $arr_user = User::getUserById($int_user_id);

                    $to_mail = Calendar::getCalendarAdminEmail($arr_calendar);
                    if (!empty($to_mail)) {
                        $bln_send = Utils::sendMail('mail_event', $to_mail, '', $frm_submitted, $arr_user);
                    }
                }
            }
            return true;
        }
        return false;
    }

    public static function updateRepeatingEvent($arr_dates, $frm_submitted) {
        global $obj_db;

        $bln_nightshift = false;

        if (IGNORE_TIMEZONE) {
            $str_startdate = substr($frm_submitted['str_date_start'], 0, 10);
            $str_enddate = substr($frm_submitted['str_date_end'], 0, 10);
            $str_starttime = substr($frm_submitted['str_date_start'], 11);
            $str_endtime = substr($frm_submitted['str_date_end'], 11);
        } else {
            $str_startdate = date('Y-m-d', $frm_submitted['date_start']);
            $str_enddate = date('Y-m-d', $frm_submitted['date_end']);
            $str_starttime = date('H:i:s', $frm_submitted['date_start']);
            $str_endtime = date('H:i:s', $frm_submitted['date_end']);
        }
        
        $bln_changed_to_dayshift = false;
        $bln_changed_to_nightshift = false;

        if (!$frm_submitted['allDay'] && strtotime(date('Y-m-d') . ' ' . $str_starttime) > strtotime(date('Y-m-d') . ' ' . $str_endtime)) {
            // nightshift
            $bln_nightshift = true;
            $bln_changed_to_dayshift = false;
            $bln_changed_to_nightshift = true;
        }

        /*
         * check if interval or weekdays have changed
         */

        //TODO other intervals 2weeks 
        // get the pattern
        $str_select_repeating_query = 'SELECT * FROM repeating_events WHERE rep_event_id = ' . $frm_submitted['rep_event_id'];
        $obj_result1 = mysqli_query($obj_db, $str_select_repeating_query);
        $arr_repeat_pattern = mysqli_fetch_array($obj_result1, MYSQLI_ASSOC);

        // update repeating_events table
        $str_update_query = 'UPDATE repeating_events SET rep_interval = "' . $frm_submitted['interval'] . '", ' .
                'weekdays = "' . $frm_submitted['weekdays'] . '",' .
                'monthday = "' . $frm_submitted['monthday'] . '",' .
                'yearmonthday = "' . $frm_submitted['yearmonthday'] . '",' .
                'yearmonth = "' . $frm_submitted['yearmonth'] . '",' .
                'every_x_days = "' . $frm_submitted['every_x_days'] . '",' .
                'every_x_weeks = "' . $frm_submitted['every_x_weeks'] . '",' .
                'startdate = "' . $str_startdate . '",' .
                'enddate = "' . $str_enddate . '" ' .
                'WHERE rep_event_id = ' . $frm_submitted['rep_event_id'];


        $res = mysqli_query($obj_db, $str_update_query);

        // check if moved to another calendar
        $bln_change_cal_id = false;

        if (defined('MOVE_EVENT_TO_OTHER_CALENDAR_POSSIBLE') && MOVE_EVENT_TO_OTHER_CALENDAR_POSSIBLE === true) {
            if ($frm_submitted['calendar_id'] > 0 && $frm_submitted['calendar_id'] != $frm_submitted['cal_id']) {
                $bln_change_cal_id = true;
            }
        }

        /*
         * get all existing items in this pattern
         */
        $arr_events_from_this_pattern = array();

        $str_events_query = 'SELECT * FROM events WHERE repeating_event_id = ' . $frm_submitted['rep_event_id'];
        $obj_result1 = mysqli_query($obj_db, $str_events_query);
        while ($arr_line = mysqli_fetch_array($obj_result1, MYSQLI_ASSOC)) {
            $arr_events_from_this_pattern[] = $arr_line;
        }


        if (is_array($arr_events_from_this_pattern) && isset($arr_events_from_this_pattern[0])) {
            if (!$bln_nightshift) {
                // if not nightshift, check if the pattern was nightshift before this update of the pattern


                if (strtotime(date('Y-m-d') . ' ' . $arr_events_from_this_pattern[0]['time_start']) > strtotime(date('Y-m-d') . ' ' . $arr_events_from_this_pattern[0]['time_end'])) {
                    if ($arr_events_from_this_pattern[0]['date_start'] == $arr_events_from_this_pattern[0]['date_end']) {
                        // do nothing
                        $bln_changed_to_dayshift = false;
                    } else {
                        // it was nightshift, so now we have to make the separate events 1 day again
                        $bln_changed_to_dayshift = true;
                    }
                }
            } else if ($bln_nightshift) {
                // check if the pattern already was nightshift, because we do not have to change the end date again

                if (strtotime(date('Y-m-d') . ' ' . $arr_events_from_this_pattern[0]['time_start']) > strtotime(date('Y-m-d') . ' ' . $arr_events_from_this_pattern[0]['time_end'])) {

                    if ($arr_events_from_this_pattern[0]['date_start'] == $arr_events_from_this_pattern[0]['date_end']) {
                        // do nothing
                        $bln_changed_to_nightshift = true;
                    } else {
                        // it was already nightshift
                        $bln_changed_to_nightshift = false;
                    }
                }
            }
        }



        // update events
        $str_update_events_query = 'UPDATE `events` SET title = "' . $frm_submitted['title'] . '", ' .
                '`color` = "' . $frm_submitted['color'] . '", ';
        if ($bln_change_cal_id) {
            $str_update_events_query .= '`calendar_id` = "' . $frm_submitted['calendar_id'] . '", ';
        }

        $str_update_events_query .= '`location` = "' . $frm_submitted['location'] . '", ' .
                '`description` = "' . $frm_submitted['description'] . '", ' .
                '`phone` = "' . $frm_submitted['phone'] . '", ' .
                '`myurl` = "' . $frm_submitted['myurl'] . '", ' .
                '`change_date` = "' . date('Y-m-d H:i:s') . '", ' .
                '`team_member_id` = "' . $frm_submitted['team_member_id'] . '", ' .
                '`dropdown1_option_id` = "' . $frm_submitted['dropdown1_option_id'] . '", ' .
                '`dropdown2_option_id` = "' . $frm_submitted['dropdown2_option_id'] . '", ' .
                '`time_start` = "' . $str_starttime . '", ' .
                '`time_end` = "' . $str_endtime . '", ';

        $str_update_events_query .= (!empty($frm_submitted['dropdown1_option_id']) && $frm_submitted['dropdown1_option_id'] > 0 ? '`dropdown1_option_id` = "' . $frm_submitted['dropdown1_option_id'] . '", ' : '') ;
        $str_update_events_query .= (!empty($frm_submitted['dropdown2_option_id']) && $frm_submitted['dropdown2_option_id'] > 0 ? '`dropdown2_option_id` = "' . $frm_submitted['dropdown2_option_id'] . '", ' : '') ;
                
        if ($bln_changed_to_nightshift) {
            $str_update_events_query .= ' `date_end` = DATE_ADD(`date_end`, INTERVAL 1 DAY), ';
        } else if ($bln_changed_to_dayshift) {
            $str_update_events_query .= ' `date_end` = DATE_ADD(`date_end`, INTERVAL -1 DAY), ';
        }

        $str_update_events_query .= '`allDay` = ' . (($str_starttime == '00:00:00' && $str_endtime == '00:00:00') || $frm_submitted['allDay'] == 1 ? '1 ' : '0 ') .
                'WHERE `repeating_event_id` = ' . $frm_submitted['rep_event_id'];



        //echo $str_update_events_query;
        $res2 = mysqli_query($obj_db, $str_update_events_query);




        /*
         * find deleted weekdays
         */
        $current_user_id = '';
        foreach ($arr_events_from_this_pattern as $event) {
            if (!in_array($event['date_start'], $arr_dates)) {
                // delete
                $obj_result_del = mysqli_query($obj_db, 'DELETE FROM events WHERE event_id = ' . $event['event_id']);
            } else {
                $search = array_search($event['date_start'], $arr_dates);
                unset($arr_dates[$search]);
            }
            $time_start = $event['time_start'];
            $time_end = $event['time_end'];
            $current_user_id = $event['user_id'];
            $current_calendar_id = $event['calendar_id'];
        }

        /*
         * added/changed weekdays
         */

        if ($frm_submitted['repair_pattern'] || $arr_repeat_pattern['weekdays'] != $frm_submitted['weekdays'] || $arr_repeat_pattern['startdate'] != $str_startdate || $arr_repeat_pattern['enddate'] != $str_enddate
         || $arr_repeat_pattern['every_x_weeks'] != $frm_submitted['every_x_weeks']
        ) {
            // add new items to pattern
            foreach ($arr_dates as $day) {
                if ($bln_nightshift) {
                    //$day = date('Y-m-d', strtotime($day . ' +1 day'));
                }
                if (IGNORE_TIMEZONE) {
                    $frm_submitted['str_date_start'] = $day . ' ' . $time_start;
                    $frm_submitted['str_date_end'] = $day . ' ' . $time_end;
                } else {
                    $frm_submitted['date_start'] = strtotime($day . ' ' . $time_start);
                    $frm_submitted['date_end'] = strtotime($day . ' ' . $time_end);
                }

                self::insertEvent($frm_submitted, $current_user_id);
            }
        }

        if ($frm_submitted['repair_pattern']) {
            // set bln_broken to 0
            $str_update_query = 'UPDATE repeating_events SET bln_broken = 0 WHERE rep_event_id = ' . $frm_submitted['rep_event_id'];
            $res3 = mysqli_query($obj_db, $str_update_query);
        }

        $current_calendar_id = 0;

        if (!$frm_submitted['repair_pattern']) {
            // because then the mail is already send in the insertEevent function
            // notification mail to admin
            if ($current_calendar_id > 0 && !empty($current_user_id)) {
                $arr_calendar = Calendar::getCalendar($current_calendar_id);

                if (Calendar::calMailEventModsToAdmin($arr_calendar)) {
                    $arr_user = User::getUserById($current_user_id);

                    $to_mail = Calendar::getCalendarAdminEmail($arr_calendar);
                    if (!empty($to_mail)) {
                        $bln_send = Utils::sendMail('mail_event', $to_mail, '', $frm_submitted, $arr_user);
                    }
                }
            }
        }
    }

    public static function deleteRepeatingEvent($rep_event_id) {
        global $obj_db;

        // delete row from repeating_events
        $str_query = 'DELETE FROM repeating_events WHERE rep_event_id = ' . $rep_event_id;
        $obj_result = mysqli_query($obj_db, $str_query);
        if ($obj_result !== false) {
            return true;
        }
    }

    public static function setEventToNotRepeating($rep_event_id) {
        global $obj_db;

        $str_update_query = 'UPDATE events SET repeating_event_id = 0 WHERE repeating_event_id = ' . $rep_event_id;
        $obj_result = mysqli_query($obj_db, $str_update_query);
        if ($obj_result !== false) {
            return true;
        }
    }

    public static function isTimeAvailable($frm_submitted) {
        global $obj_db;

//		$str_query = 'SELECT * FROM events WHERE user_id = '. $_SESSION['mylogbook-uid']['uid'].
//				'AND '.$frm_submitted['date_start'].' BETWEEN date_start AND date_end';
        $str_query = 'SELECT * FROM events WHERE user_id = ' . $_SESSION['calendar-uid']['uid'] .
                ' AND "' . date('Y-m-d H:i:s', $frm_submitted['date_start']) . '" BETWEEN concat_ws(" ",date_start,time_start) AND concat_ws(" ",date_end,time_end)';
        $obj_result = mysqli_query($obj_db, $str_query);

        $arr_event = mysqli_fetch_array($obj_result, MYSQLI_ASSOC);
        if (!empty($arr_event)) {
            return false;
        }

        return true;
    }

    public static function getSmallCalEvents($cal_id, $year = null, $month = null, $day = null) {
        global $obj_db;
        $arr_content = array();

        $str_query = 'SELECT * , concat_ws( " ", date_start, time_start ) AS START , concat_ws( " ", date_end, time_end ) AS END FROM events as e
						WHERE 1 ' .
                ($year !== null && $month !== null ? ' and ((MONTH(date_start) = "' . $month . '" AND YEAR(date_start) = "' . $year . '"  ) OR (MONTH(date_end) = "' . $month . '" AND YEAR(date_end) = "' . $year . '" ))' : '') .
                ($day !== null ? ' and ("' . $year . '-' . $month . '-' . $day . '" BETWEEN date_start and date_end)' : '') .
                ' 	group by e.event_id ORDER BY date_start ';
        $obj_result = mysqli_query($obj_db, $str_query);

        while ($arr_line = mysqli_fetch_array($obj_result, MYSQLI_ASSOC)) {
            $arr_content[] = $arr_line;
        }
        return $arr_content;
    }

    public static function getSmallCalItems($arr_content) {
        $arr_result = array();

        foreach ($arr_content as $event) {
            if (!isset($arr_result[substr($event['date_start'], 8, 2)])) {
                $arr_result[ltrim(substr($event['date_start'], 8, 2), '0')] = array();
            }
            // meerdaags event
            if ($event['date_end'] != $event['date_start']) {
                $days_in_between = Utils::getDaysBetween($event['date_start'], $event['date_end']);
                foreach ($days_in_between as $day) {
                    $arr_result[ltrim(substr($day, 8, 2), '0')][] = $event;
                }
            } else {
                $arr_result[ltrim(substr($event['date_start'], 8, 2), '0')][] = $event;
            }
        }
        return $arr_result;
    }

    public static function getListviewEvents($frm_submitted, $amount_days_to_show, $with_period=false, $lang='EN') {
        global $obj_db;
        $arr_content = array();

 // ook kijken naar welke kalenders de ingelogde gebruiker mag zien
        $arr_calendars = Calendar::getCalendars(false,false,true);
     
        $str_query = 'SELECT * ,event_id AS id, concat_ws( " ", date_start, time_start ) AS START , concat_ws( " ", date_end, time_end ) AS END ' .
                'FROM events as e WHERE 1 ';

        $str_query .= ' AND calendar_id IN('.implode(',', $arr_calendars).')';
        
        if($with_period) {
            if(empty($frm_submitted['to']) && !empty($frm_submitted['from']) ) {
                $date_from = date('Y-m-d', strtotime($frm_submitted['from']));
                $date_to = date('Y-m-d', strtotime('+ 1 MONTH', strtotime($date_from)));
            } else if(!empty($frm_submitted['to']) && empty($frm_submitted['from']) ) {
                $date_to = date('Y-m-d', strtotime($frm_submitted['to']));
                $date_from = date('Y-m-d', strtotime('- 1 MONTH', strtotime($frm_submitted['to'])));
            } else if(!empty($frm_submitted['to']) && !empty($frm_submitted['from'])) {
                $date_to = date('Y-m-d', strtotime($frm_submitted['to']));
                $date_from = date('Y-m-d', strtotime($frm_submitted['from']));
            } else {
                $date_from = date('Y-m-d');
                $date_to = date('Y-m-d', strtotime('+ '.$amount_days_to_show.' DAY', strtotime($date_from)));
            }
            
            $str_query .= ' AND ((date_start BETWEEN "' . $date_from . '" AND "' . $date_to . '")
                                                                OR (
                                                                 date_end BETWEEN "' . $date_from . '" AND "' . $date_to . '"
                                                                ))';
                $str_query .= '	ORDER BY date_start ASC';
                $amount_days_to_show = -1;
        } else {
            if (!empty($frm_submitted['from'])) {
                $date_to = date('Y-m-d', strtotime('+6 MONTH', strtotime($frm_submitted['from'])));
                $date_from = $frm_submitted['from'];

                $str_query .= ' AND ((date_start > "' . $date_from . '" AND date_start <= "' . $date_to . '")
                                                            OR (
                                                            date_start < "' . $date_from . '"
                                                            AND (date_end BETWEEN "' . $date_from . '" AND "' . $date_to . '")
                                                            ))';
                $str_query .= '	ORDER BY date_start ASC';
            } else if (!empty($frm_submitted['to'])) {
                $date_from = date('Y-m-d', strtotime('-6 MONTH', strtotime($frm_submitted['to'])));
                $date_to = $frm_submitted['to'];

                $str_query .= ' AND (date_end < "' . $date_to . '" AND date_start >= "' . $date_from . '"	)';
                $str_query .= '	ORDER BY date_start DESC';
            } else {
                $date_from = date('Y-m-d');
                $date_to = date('Y-m-d', strtotime('+6 MONTH', strtotime($date_from)));

                $str_query .= ' AND (date_start >= DATE( NOW( ) )
                                                            OR (
                                                            date_start < DATE( NOW( ) )
                                                            AND date_end >= DATE( NOW( ) )
                                                            ))';
                $str_query .= '	ORDER BY date_start ASC';
            }
        }
        

        // if you want to show a specific amount of items
        //$str_query .= '	LIMIT '.$amount_days_to_show;


        $obj_result = mysqli_query($obj_db, $str_query);

        while ($arr_line = mysqli_fetch_array($obj_result, MYSQLI_ASSOC)) {
            
            if (isset($arr_line['dropdown1_option_id']) && $arr_line['dropdown1_option_id'] > 0) {
                if (isset($_SESSION['ews_custom_field_options']) && isset($_SESSION['ews_custom_field_options'][$arr_line['dropdown1_option_id']]) && !empty($_SESSION['ews_custom_field_options'][$arr_line['dropdown1_option_id']])) {
                    $arr_option = $_SESSION['ews_custom_field_options'][$arr_line['dropdown1_option_id']];
                } else {
                    $arr_option = CustomFields::getCustomDropdownOption($arr_line['dropdown1_option_id']);
                    $_SESSION['ews_custom_field_options'][$arr_line['dropdown1_option_id']] = $arr_option;
                }
                $arr_line['title'] .= ' <span style="color:' .$arr_option['color']. '">' .$arr_option['text'].'</span>';
            }
            
            if (isset($arr_line['dropdown2_option_id']) && $arr_line['dropdown2_option_id'] > 0) {
                if (isset($_SESSION['ews_custom_field_options']) && isset($_SESSION['ews_custom_field_options'][$arr_line['dropdown2_option_id']]) && !empty($_SESSION['ews_custom_field_options'][$arr_line['dropdown2_option_id']])) {
                    $arr_option = $_SESSION['ews_custom_field_options'][$arr_line['dropdown2_option_id']];
                } else {
                    $arr_option = CustomFields::getCustomDropdownOption($arr_line['dropdown2_option_id']);
                    $_SESSION['ews_custom_field_options'][$arr_line['dropdown2_option_id']] = $arr_option;
                }
                $arr_line['title'] .= ' <span style="color:' .$arr_option['color']. '">' .$arr_option['text'].'</span>';
            }
            
            $arr_content[] = $arr_line;
        }

        $sort_by_cal_order = false; // TODO get the real value
        
        $arr_result = self::getAgendaItems($arr_content, $frm_submitted, $sort_by_cal_order, $lang);

        if($amount_days_to_show > 0) {
            // if you want to show a specific amount of days
            $arr_result = array_slice($arr_result, 0, $amount_days_to_show);
            
            // when ->to the order is desc
            // after array_slice we want to sort normal again (asc)
            ksort($arr_result);
        }
        
    
        $arr_return = array();
        $arr_return['results'] = $arr_result;
        $arr_return['hide_from'] = false;
        $arr_return['hide_to'] = false;

        $arr_return['from'] = $date_from;
        $arr_return['to'] = $date_to;
        
                
        //$arr_result = Utils::sortTwodimArrayByKey($arr_result, 'date_start');

        if (!empty($frm_submitted['from'])) {
            if (count($arr_result) < $amount_days_to_show) {
                $arr_return['hide_from'] = true;
            }
        }
        if (!empty($frm_submitted['to'])) {
            if (count($arr_result) < $amount_days_to_show) {
                $arr_return['hide_to'] = true;
            }
        }

        return $arr_return;
    }

    public static function getAgendaItems($arr_content, $frm_submitted = array(), $sort_by_cal_order = false, $lang='EN') {
        $arr_result = array();
        $arr_return = array();

        foreach ($arr_content as &$event) {
            if ($event['allDay']) {
                $event['time_start'] = '00:00:00';
            }
            
            // moreday event
            if ($event['date_end'] != $event['date_start']) {

                if ((defined('COMBINE_MOREDAYS_EVENTS') && COMBINE_MOREDAYS_EVENTS) && $frm_submitted['combine_moreday_events'] !== false) {
                    if (defined('ENDDATE_OF_COMBINED_MOREDAYS_EVENTS_TEXT')) {
                        $str_enddate_and_title = str_replace('%ENDDATE%', strftime("%A, %e %B", strtotime($event['date_end'])), ENDDATE_OF_COMBINED_MOREDAYS_EVENTS_TEXT); // example: 'to %ENDDATE% ,inclusive'
                        $event['title'] = $str_enddate_and_title . ': ' . $event['title'];
                    } else {
                        $event['title'] = '-> ' . date('D, d M', strtotime($event['date_end'])) . ': ' . $event['title'];
                    }

                    $arr_result[$event['date_start']][] = $event;
                } else {
                    $days_in_between = Utils::getDaysBetween($event['date_start'], $event['date_end']);
                
                    foreach ($days_in_between as $day) {
                        if(isset($frm_submitted['from'])) {
                            if($day >= date('Y-m-d', strtotime($frm_submitted['from']))) {
                                $arr_result[$day][] = $event;
                            }
                        } else {
                            $arr_result[$day][] = $event;
                        }
                        
                    }
                }
            } else {
                $arr_result[$event['date_start']][] = $event;
            }
        }
        unset($event);

        Utils::setLocaleLanguage($lang);
        
        foreach ($arr_result as $date => $arr_date) {
            if($lang == 'EN') {
                $date = strftime('%A, %B %e, %Y', strtotime($date));
            } else {
                $date = strftime('%A, %e %B %Y', strtotime($date));
            }
            
            if ($sort_by_cal_order === true) {
                $arr_return[$date] = Utils::sortTwodimArrayByKey($arr_date, 'sorter');
            } else {
                $arr_tmp = Utils::sortTwodimArrayByKey($arr_date, 'time_start');
                
                $arr_return[$date] = $arr_tmp;
            }
        }
        return $arr_return;
    }

    public static function insertUploadedFile($arr_file) {

        global $obj_db;

        // if(User::isLoggedIn()) {
        $current_user_id = 1000000;

        $arr_user = User::getUser();
        if (!empty($arr_user) && is_array($arr_user)) {
            $current_user_id = $arr_user['user_id'];
        }

        if ($arr_file['type'] == 'jpeg') {
            $arr_file['type'] = 'jpg';
        }
        if ($arr_file['type'] == 'x-log') {
            $arr_file['type'] = 'log';
        }
        $str_query = 'INSERT INTO event_files ( filename, original_filename, event_id, file_extension, type, upload_date, create_id) ' .
                'VALUES ("' . mysqli_real_escape_string($obj_db, $arr_file['filename']) . '",' .
                '"' . mysqli_real_escape_string($obj_db, $arr_file['orig_filename']) . '",' .
                $arr_file['event_id'] . ',' .
                '"' . mysqli_real_escape_string($obj_db, $arr_file['extension']) . '",' .
                '"' . mysqli_real_escape_string($obj_db, $arr_file['type']) . '",' .
                '"' . date('Y-m-d H:i:s') . '",' .
                $current_user_id . ')';
        $obj_result = mysqli_query($obj_db, $str_query);

        if ($obj_result !== false) {
            return true;
        } else {
            // probably duplicate entry
            return false;
        }

        //} else {
        //	header('location:'.FULLCAL_URL);exit;
        //}
    }

    public static function getEventFile($int_event_id, $new_filename) {
        global $obj_db;

        $str_query1 = 'SELECT * FROM event_files WHERE `event_id` = ' . $int_event_id . ' AND `filename` = "' . $new_filename . '"';


        $res1 = mysqli_query($obj_db, $str_query1);

        if ($res1 !== false) {
            $arr_file = mysqli_fetch_array($res1, MYSQLI_ASSOC);

            return $arr_file;
        }
    }

    public static function getCntFiles($int_event_id = -1) {
        global $obj_db;

        if ($int_event_id > 0) {
            $str_query = 'SELECT count( `event_file_id` ) as cnt_files' .
                    ' FROM `event_files`' .
                    ' WHERE `event_id` =' . $int_event_id;

            $res1 = mysqli_query($obj_db, $str_query);

            if ($res1 !== false) {
                $arr_file = mysqli_fetch_assoc($res1);

                return $arr_file['cnt_files'];
            }
        }
    }

}