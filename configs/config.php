<?php

/*
 * Created on 17-okt-2011
 * author Paul Wolbers
 */

$protocol = (isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') ? 'https://' : 'http://';
$http_host = $protocol . $_SERVER["HTTP_HOST"];

//error_reporting(E_ALL);
//ini_set('display_errors', 1);

/**
 * When you don't know what you should fill in for FULLCAL_DIR
 * use "echo getcwd()"
 * 
 * for the FULLCAL_DIR path you need to know the path to your personal folder on your website you can check your settings/configurations with your provider
 * OR get it with getcwd(), remove the 2 slashes on line 20 and refresh the calendar
 */
// echo getcwd();

if ($_SERVER["HTTP_HOST"] == 'localhost' OR $_SERVER["HTTP_HOST"] == '127.0.0.1') {
    // local webserver on your computer
    // set the correct path to the files
    // for windows it would be something like this: d:/xampp/htdocs/employee-work-schedule
    define('FULLCAL_DIR', '/Applications/XAMPP/xamppfiles/htdocs/employee-work-schedule');

    // set the correct folder name where put the calendar in (don't remove $http_host)
    define('FULLCAL_URL', $http_host . '/employee-work-schedule');
} else {
    // online use, so when you have the calendar on the online website 
    // set the correct path for FULLCAL_DIR 
    // if you don't know what this should be, you can check it with getcwd()
    define('FULLCAL_DIR', '/home/paul/public_html/employee-work-schedule');    // example: /home/paul/public_html/employee-work-schedule
    // set the correct folder name where put the calendar in (don't remove $http_host)
    define('FULLCAL_URL', $http_host . '/employee-work-schedule');
}

if (substr(FULLCAL_DIR, 0, 4) == 'http') {
    echo 'FULLCAL_DIR is not set correctly';
    exit;
}

// don't change these
define('CONFIG_DIR', FULLCAL_DIR . '/configs');
define('INCLUDE_DIR', FULLCAL_DIR . '/include');
define('CLASSES_DIR', FULLCAL_DIR . '/model');
define('LIB_DIR', FULLCAL_DIR . '/lib');
define('EXTERNAL_DIR', FULLCAL_DIR . '/external');
define('EXTERNAL_URL', FULLCAL_URL . '/external');
define('IMAGES_URL', FULLCAL_URL . '/images');

//define('CAL_ID', 1);	// used in case there is 1 calendar

/*
 * options you can change as you wish
 */

// ACCESS OPTIONS
define('LANGUAGE', 'EN');    // supported NL, EN, FR, DE, PL, ES, NO, IT, CZ  - used when nobody is logged in

global $current_languages;
$current_languages = array('CZ' => 'Czech',
    'NL' => 'Dutch',
    'EN' => 'English',
    'FR' => 'French',
    'DE' => 'German',
    'IT' => 'Italian',
    'NO' => 'Norwegian',
    'PL' => 'Polish',
    'ES' => 'Spanish');    // used to set the languages in the dropdownlists

define('CAL_IP', '');     // ip of calendaruser
define('ALLOW_ACCESS_BY', 'free');   // def. login. ip or free (public can view) or login. If ip users get user_id 1000000
define('IP_AND_FREE_ACCESS_SAVED_USER_ID', 1000000); // def. 1000000. 1000000 or another user_id
define('ADMIN_USER_ID', 2); // 2 is the default admin


define('ADMIN_HAS_FULL_CONTROL', true); // when true the calendar options (like can-add and can-edit) are ignored
// ACCESS TYPE 'LOGIN' AND 'FREE' OPTIONS
define('ADMIN_CAN_LOGIN_FROM_ADMIN_URL', true); // when accesstype = free AND SHOW_SMALL_LOGIN_LINK = false the admin can login with ../admin url
define('SHOW_SMALL_LOGIN_LINK', true);    // true means that a login link is visible above the calendar.
// false: depends on ALLOW_ACCESS_BY what happens: When ALLOW_ACCESS_BY is �free� then visitors will see the calendar, when ALLOW_ACCESS_BY is �login� visitors will be redirected to the login page.
// ACCESS TYPE 'LOGIN' OPTIONS
define('USERS_CAN_REGISTER', false);
define('SEND_ACTIVATION_MAIL', false);   // true, user is activated after click on activationlink in mail. false, user is immediately activated, password is in registerform
define('ACTIVATION_MAIL_SUBJECT', 'Welcome %USERNAME%');
define('RESET_PASSWORD_MAIL_SUBJECT', 'Reset your password');

// MAIL

define('FROM_EMAILADDRESS', 'your_email_address'); // used as from address when mails are send to users
define('ADMIN_EMAILADDRESS', 'your_email_address'); // used to send copy of add_user mail

define('IGNORE_TIMEZONE', true);
// If you or users have problems with timezone or daylight saving and have time issues in the calendar, 
// you can set this to false or if you or your users are all in the same country/area, 
// you can set your TIMEZONE. Problems can occur when the calendar in on a server in another country 
// and you or users are in a country with daylight saving time.
// optional, in case you want other than the clienttimezone or there are problems
define('TIMEZONE', '');
// example: America/New_York, if left empty the clients timezone will be used.



/**
 * CALENDAR OPTIONS
 */
define('SHOW_WEEKNUMBERS', true);
define('SHOW_MONTH_VIEW_BUTTON', true);
define('SHOW_WEEK_VIEW_BUTTON', true);
define('SHOW_DAY_VIEW_BUTTON', true);
define('SHOW_AGENDA_VIEW_BUTTON', true);
define('WEEK_VIEW_TYPE', 'agendaWeek'); //basicWeek or agendaWeek
define('DAY_VIEW_TYPE', 'agendaDay'); //basicDay or agendaDay
define('DEFAULT_VIEW', 'month');  //month, basicWeek, agendaWeek, basicDay, agendaDay, agendaList

define('SHOW_TITLE_FIRST', false);

define('FIRSTDAY_OF_WEEK', 1);  // The day that each week begins. (0 = sunday, 1 = monday etc.)
define('FIRST_SCROLL_HOUR', 6); // Determines the first hour that will be visible in the scroll pane.
define('MIN_VISIBLE_TIME', 0);  // Determines the first hour/time that will be displayed, even when the scrollbars have been scrolled all the way up.
define('MAX_VISIBLE_TIME', 24);  // Determines the last hour/time that will be displayed.
define('SHOW_WEEKENDS', true);   // show weekenddays or not


define('ONLY_SHOW_CALENDAR', true);  // if true, calendar is visible and logout link when ALLOW_ACCESS_BY is login


define('SHOW_SEARCH_BOX', true);


//	define('CALENDAR_CAN_ADD', true);
//	define('CALENDAR_CAN_RESIZE', false);
// password hash for admin: af41d89626f1dc9dfef36870cc9d24f6
// password for superadmin: d4558c97495b2c3954f73bae761c6d0f

/**
 * EDIT DIALOG
 */
define('EDITDIALOG_COLORPICKER_TYPE', 'spectrum');  // simple, spectrum


define('SHOW_VIEW_TYPE', 'mouseover'); // mouseover or none
define('MOUSEOVER_WIDTH', 250);
define('SHOW_PHONE_IN_MOUSEOVER', true);
define('SHOW_URL_IN_MOUSEOVER', true);
define('SHOW_LOCATION_IN_MOUSEOVER', true);
define('ONLY_SHOW_MOUSEOVER_IN_MONTH_VIEW', true);

define('SHOW_DESCRIPTION_IN_WDL_VIEW', true);  // show description in weekview, dayview and listview
define('SHOW_LOCATION_IN_WDL_VIEW', true);
define('SHOW_PHONE_IN_WDL_VIEW', true);
define('SHOW_URL_IN_WDL_VIEW', true);

define('DATEPICKER_DATEFORMAT', 'mm/dd/yy'); // dd/mm/yy or 'mm/dd/yy'

define('DIALOGS_RESIZABLE', true);
define('DEFAULT_TIME_FOR_EVENT', '09:00');


define('SHOW_NOTALLOWED_MESSAGES', true); // if true, a message will be shown when someone tries to add or edit an event, but has no rights to do so
define('SHOW_DELETE_CONFIRM_DIALOG', true);

// TIMEPICKER
define('EDITDIALOG_TIMEPICKER_TYPE', 'ui');  // simple or ui
define('MINHOUR', 0);      // the earliest hour that will be showed in the timepicker
define('MAXHOUR', 23);      // the latest hour that will be showed in the timepicker
define('SHOW_AM_PM', false);
define('MINUTE_INTERVAL', 15);
define('SIMPLE_TIME_SELECT', false);  // if true -> smarty time_select combos else jQuery timepicker (http://fgelinas.com/code/timepicker/)


/**
 * ADD USER FORM and profile form
 */
define('SHOW_USERNAME_IN_FORM', true);
define('SHOW_INFIX_IN_FORM', false);
define('SHOW_CHECKBOX_COPY_TO_ADMIN', true);


/**
 * ADD USER THROUGH ADD USER DIALOG (ONLY ADMIN CAN DO THIS)
 */
define('SHOW_CREATED_PASSWORD_WHEN_ADMIN_ADDS_USER', true); // password will be shown so the admin knows it


define('ONLY_ADMIN_CAN_SEE_DRAG_DROP_ITEMS', false);
/**
 * CALENDAR ITEMS
 */
define('DEFAULT_COLOR', '#3366cc');
define('SHOW_MOUSEOVER_DELETE_BUTTON', false);
define('TRUNCATE_TITLE', true);
define('TRUNCATE_LENGTH', 50);

define('SCHEDULE_SQLDUMP', 'day'); // day, hour, never

define('LOGINSESSION_NOT_EXPIRES', false);
define('HOURCALCULATION_DEFAULT_PERIOD', 6);    // months
define('HOURCALCULATION_WORKDAY_HOURS', 8);    // hours

define('USERS_CAN_ADD_CALENDARS', true);
define('SHOW_PUBLIC_AND_PRIVATE_SEPARATELY', true);


define('MASK_UNALTERABLE_DAYS', true);  // the days that can not be altered (setting for every calendar) are gray - could be a little slower, because the dayRender function is called ofter

define('MAIL_EVENT_MAILADDRESS', '');
define('MAIL_EVENT_MAILSUBJECT', 'Event notification');
define('MAIL_EVENT_MAILBODY', 'Employee: %FIRSTNAME% %INFIX% %LASTNAME%<br />'
        . 'Title: %TITLE%<br />'
        . '<p>Description: %DESCRIPTION%</p><br />'
        . 'Location: %LOCATION%<br />'
        . 'Phone: %PHONE%<br />'
        . 'Url: %URL%<br />'
        . 'Startdate: %STARTDATE%<br />'
        . 'Enddate: %ENDDATE%<br />');


define('MOVE_EVENT_TO_OTHER_CALENDAR_POSSIBLE', true);

define('CALENDAR_TITLE', 'Employee Work Schedule');

define('TOUCHFRIENDLY_DRAG_EVENTS', true);
define('TOUCHFRIENDLY_SELECT_DAYCELLS', false);

define('SHOW_FILE_UPLOAD', true);   // writable uploads folder needed
define('MAX_EVENT_FILE_UPLOAD', 5);

define('OWNER_EXCHANGE_CAL_ALLOWED_WITHOUT_TOKEN', false);   // if true the owner of the calendar doesn't have to fill in the token to see the events

define('SAVE_CURRENT_EDITING', false);   // set to true if you don't want 2 people editing an event at the same time

define('RECURRING_EVENT_TEXT_COLOR', 'white');  // white or black or colorcode like #FFFFFF, if white or black the icon is also white or black
define('SHOW_RECURRING_EVENT_ICON', true);

define('DEFAULT_EVENT_DURATION', 120); // 30, 60,120  (minutes)

define('SHOW_ASSIGNED_BY_ICON', true);

define('SHOW_PHONE_ICON_FOR_MOBILE_CALLS', true);  // to show the phone icon for quick calling on mobile devices

define('COPY_EVENT_POSSIBLE', true);

define('SORT_ALL_CALENDARS_BY_CALENDARID', false); // if true, when clicked on 'Show all', the events are sorted by calendarID
define('SORT_ALL_CALENDARS_BY_CAL_ORDERID', true);  // orderid can be set in calendars table. in a later update it will be changeable in the calendars setcion in the dashboard

define('SHOW_CUSTOM_LISTVIEW_BUTTON', true);

/* not implemented yet
define('SUBSCRIPTIONS', true);
define('FREE_TRIAL', true);
define('PAYPAL', true);
*/

