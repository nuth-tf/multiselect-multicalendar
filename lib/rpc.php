<?php
	$current_path = dirname ( realpath ( __FILE__ ) );

	require_once '../include/default.inc.php';

	/**
	 * 	echo Date::getOtherDate("Y-m-d H:i:s", "+2 days"); //would print date/time two days ahead of today
		echo Date::getOtherDate("Y-m-d H:i:s", "-5 days"); //print date/time 5 days ago
		echo Date::getOtherDate("Y-m-d H:i:s", "+2 hours"); //print date/time 2 hours from now
	 */
	 function getOtherDate($format, $fromNow){
		return date($format, strtotime($fromNow, strtotime(date($format))));
	}

	global $conn;

	if(!isset($_POST['action'])) {
		$action = 'startCalendar';
	} else {
		$action = $_POST['action'];
	}
	if(isset($_GET['action']) AND $_GET['action'] == 'logout') {
		$action = $_GET['action'];
	}

	switch($action) {
		case 'startCalendar':
			doStartCal();
			break;
		case 'listEvents':
			doListEvents();
			break;
		default:
			echo 'Whoops.';
			break;
	}

	function doStartCal() {

		$weekendColor = '#FFFFFF';
		$dayColor = '#E3E3E3';

		$month = (isset($_POST['month']) ? $_POST['month'] : date('m'));
		$year = (isset($_POST['year']) ? $_POST['year'] : date('Y'));
		if(isset($_POST['back']) && $_POST['back'] !== '') {
			if($_POST['back'] == 'true') {
				$month = $month -1;
				if($month == 0) {
					$month = 12;
					$year --;
				}
			} elseif($_POST['back'] == 'false') {
				$month = $month + 1;
				if($month == 13) {
					$month = 1;
					$year ++;
				}
			}
		}

		$today = mktime(0, 0, 0, date("m"), date("d"), date("Y"));

		if(($month == 0) || ($year == 0)) {
			$thisDate = mktime(0, 0, 0, date("n"), date("d"), date("Y"));
		} else {
			$thisDate = mktime(0, 0, 0, $month, 1, $year);
		}

		$str_html = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">' .
				'<html><head></head><body>';

		$str_html .= '<div style="margin-bottom: 3px;">
					<div><form name="changeCalendarDate"> <span id="arrow_left">&nbsp;<img src="images/arrow_left_3.png" onmouseover="this.style.cursor=\'pointer\'"  /></span>
						&nbsp;<span style="vertical-align:2px;"><select id="ccMonth" >';

						for($i=0; $i<=11; $i++)
						{
							$monthNumber = ($i+1);
							$monthMaker = mktime(0, 0, 0, $monthNumber, 1, 2006);
							if($month > 0) {
								if($month == $monthNumber) {
									$sel = 'selected';
								} else {
									$sel = '';
								}
							} else {
								if(date("m", $thisDate) == $monthNumber) {
									$sel = 'selected';
								} else {
									$sel = '';
								}
							}

							/*
							********************************************************************************************************
								Change the names in here to your language - DO NOT CHANGE ANYTHING ELSE UNLESS YOU UNDERSTAND IT
							********************************************************************************************************
							*/
							$monthName = array('januari',
												'februari',
												'maart',
												'april',
												'mei',
												'juni',
												'juli',
												'augustus',
												'september',
												'oktober',
												'november',
												'december');


							$str_html .= '<option value="'. $monthNumber .'" '. $sel .'>'. $monthName[$i] .'</option>';
						}

				$str_html .= '</select></span>
						&nbsp;<span style="vertical-align:2px;">
						<select id="ccYear" >';

						$yStart = 2005;
						$yEnd = ($yStart + 15);
						for($i=$yStart; $i<$yEnd; $i++)
						{
							if($year > 0) {
								if($year == $i) {
									$sel = 'selected';
								} else {
									$sel = '';
								}
							} else {
								if(date("Y", $thisDate) == $i) {
									$sel = 'selected';
								} else {
									$sel = '';
								}
							}
							$str_html .= '<option value="'. $i .'" '. $sel .'>'. $i .'</option>';
						}

				$str_html .= '</select></span> <span id="arrow_right">&nbsp;<img id="rightarrow" src="images/arrow_right_3.png" onmouseover="this.style.cursor=\'pointer\'" /></span></div>';

				$str_html .=  '</form>
				</div>';

		// Display the week days.
		$str_html .= '<div class="calendarFloat" style="text-align: center; background-color: #BFBFBF;"><span style="position: relative; top: 4px;">M</span></div>
				<div class="calendarFloat" style="text-align: center; background-color: #BFBFBF;"><span style="position: relative; top: 4px;">D</span></div>
				<div class="calendarFloat" style="text-align: center; background-color: #BFBFBF;"><span style="position: relative; top: 4px;">W</span></div>
				<div class="calendarFloat" style="text-align: center; background-color: #BFBFBF;"><span style="position: relative; top: 4px;">D</span></div>
				<div class="calendarFloat" style="text-align: center; background-color: #BFBFBF;"><span style="position: relative; top: 4px;">V</span></div>
				<div class="calendarFloat" style="text-align: center; background-color: #BFBFBF;"><span style="position: relative; top: 4px;">Z</span></div>
				<div class="calendarFloat" style="text-align: center; background-color: #BFBFBF;"><span style="position: relative; top: 4px;">Z</span></div>';

		$cal_id = 1;
		if(!empty($_POST['cal_id'])) {
			$cal_id = $_POST['cal_id'];
		}
		$_SESSION['cal_id'] = $cal_id;

		if(isset($_REQUEST['example']) && !is_null($_REQUEST['example'])) {
		//	$arr_events = Array ( [0] => Array ( [event_id] => 1 [title] => jngfsjgs [date_start] => 2014-07-31 [time_start] => 00:00:00 [date_end] => 2014-07-31 [time_end] => 00:00:00 [allDay] => 1 [calendartype] => [user_id] => 1000000 [color] => #3366CC [START] => 2014-07-31 00:00:00 [END] => 2014-07-31 00:00:00 ) )

			if($_REQUEST['examplestart'] == 1 || ($month == date('m') && $year == date('Y'))) {
				$arr_res = array (

				ltrim(date('d', strtotime('-1DAY')), '0') => array( array (
				    'event_id' => 101,
				    'title' => 'Walking in the Belgian hills near Spa',
				    'date_start' => date('Y-m-d', strtotime('-1DAY')),
				    'time_start' => '10:00:00',
				    'date_end' => date('Y-m-d', strtotime('-1DAY')),
				    'time_end' => '10:00:00',
				    'allDay' => 1,
				    'calendartype' => '',
				    'user_id' => '2',
				    'START' => date('Y-m-d 00:00:00', strtotime('-1DAY')),
				    'END' => date('Y-m-d 00:00:00', strtotime('-1DAY')),
				    'color' => '#FFBB00',
				  )),

				ltrim(date('d'), '0') => array( array (
				    'event_id' => 102,
				    'title' => 'Back home',
				    'date_start' => date('Y-m-d'),
				    'time_start' => '08:00:00',
				    'date_end' => date('Y-m-d'),
				    'time_end' => '08:00:00',
				    'allDay' => 1,
				    'calendartype' => '',
				    'user_id' => '2',
				    'START' => date('Y-m-d 00:00:00'),
				    'END' => date('Y-m-d 00:00:00'),
				    'color' => '#FFBB00',
				  ),
				  array (
				    'event_id' => 103,
				    'title' => 'event',
				    'date_start' => date('Y-m-d', strtotime('-1DAY')),
					'time_start' => '18:00:00',
					'date_end' => date('Y-m-d', strtotime('-1DAY')),
					'time_end' => '22:00:00',
				    'allDay' => '1',
				    'calendartype' => '',
				    'user_id' => '2',
				    'START' => date('Y-m-d', strtotime('-1DAY')),
				    'END' => date('Y-m-d', strtotime('-1DAY')),
					'color' => '#FFBB00',
				 ))


				);
			} else {
				$arr_res = array ();
			}

		} else {
			$arr_events = Events::getSmallCalEvents($cal_id,($year > 0 ? $year : date('Y')),($month > 0 ? $month : (int)date('m')));

			$arr_res 	= Events::getSmallCalItems($arr_events);

		}


		// Show the calendar.
		for($i=0; $i<date("t", $thisDate); $i++)
		{
			$thisDay = ($i + 1);
			if(($month == 0) || ($year == 0)) {
				$finalDate = mktime(0, 0, 0, date("m"), $thisDay, date("Y"));
				$today = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
				$fdf = mktime(0, 0, 0, date("m"), 1, date("Y"));
				$month = date("n");
				$year = date("Y");
			} else {
				$finalDate = mktime(0, 0, 0, $month, $thisDay, $year);
				$fdf = mktime(0, 0, 0, $month, 1, $year);
				$fdl = mktime(0,0,0, $month+1, -1, $year);
			}


			// Skip some cells to take into account for the weekdays.
			if($i == 0) {
				$firstDay = date("w", $fdf);
				$skip = ($firstDay - 1);
				if($skip < 0) { $skip = 6; }

				for($s=0; $s<$skip; $s++)
				{
					$str_html .= '<div class="calendarFloat" style="border: 1px solid darkgray;">&nbsp;</div>';
				}
			}

			// Make the weekends another colour.
			if((date("w", $finalDate) == 0) || (date("w", $finalDate) == 6)) {
				$bgColor = $weekendColor;
			} else {
				$bgColor = $dayColor;
			}


			// displayEvents(day, $F('ccMonth'), $F('ccYear'));
			$onClick = 'displayEvents('. $thisDay .', '. $month .', '. $year .')';


			// Check the database for any events on this day.
			$tmp_day = (strlen($thisDay) == 2) ? $thisDay : '0'.$thisDay;
			$tmp_month = (strlen($month) == 2) ? $month : '0'.$month;

			$cnt_events = false;

			if(count($arr_res[$i+1]) > 0) {
				$str_event = '';
				foreach($arr_res[$i+1] as $k => $arr_event) {

					if($k == 0) {
						$str_event 	= $arr_event['title'];
						$cnt_events = false;
					} else {
						$cnt_events = true;

						$str_event 	.= ' + '.$arr_event['title'];
					}
				}

				$bgColor = $arr_event['color'];
				$bgColor = '#aabbaa';
			} else {
				// Check if this day is today and change it to the today color.
				if($finalDate == $today) {
					//$bgColor = $todayColor .'';
				} else {
					// Dont change it.
				}
				$str_event = '';
				$onClick = '';
			}


			// Display the day.
			if($finalDate == $today) {
				$str_html .= '<div class="calendarFloat todayday" id="calendarDay_'. $thisDay .'" style="width:22px;height:22px;border:solid 2px red;'.(($cnt_events) ? 'background: '. $bgColor.' url(images/driehoek_grijs.png) no-repeat bottom right;' : 'background-color: '. $bgColor .';').((!empty($str_event)) ? 'cursor: pointer' : 'cursor: default').';"
									onMouseOver="highlightTodayCell(\'calendarDay_'. $thisDay .'\')"'
									.((!empty($str_event)) ? 'title="'.substr($str_event,0,40).'"' : '').'
									onMouseOut="resetTodayCell(\'calendarDay_'. $thisDay .'\')"
									onClick="'. $onClick .'">
								<span style="position: relative; top: 3px; left: 2px;">'. $thisDay .'</span>
					</div>';
			} else {
				$str_html .= '<div class="calendarFloat" id="calendarDay_'. $thisDay .'" style="'.(($cnt_events) ? 'background: '. $bgColor.' url(images/driehoek_grijs.png) no-repeat bottom right;' : 'background-color: '. $bgColor .';').((!empty($str_event)) ? 'cursor: pointer' : 'cursor: default').';"
									onMouseOver="highlightCalendarCell(\'calendarDay_'. $thisDay .'\')"'
									.((!empty($str_event)) ? 'title="'.substr($str_event,0,40).'"' : '').'
									onMouseOut="resetCalendarCell(\'calendarDay_'. $thisDay .'\')"
									onClick="'. $onClick .'">
								<span style="position: relative; top: 4px; left: 1px;">'. $thisDay .'</span>
					</div>';
			}
			$cnt_events = false;

			if($i == (date("t", $thisDate)-1)) {
				$lastDay = date("w", $fdl);
				$fill = (6-$lastDay );

				for($s=0; $s<$fill; $s++)
				{
					$str_html .= '<div class="calendarFloat" style="border: 1px solid darkgray;">&nbsp;</div>';
				}

			}
		}

		$str_html .= '</body></html>';

		echo $str_html;

		//$todayevents =  listevents(true);
		//echo $todayevents;
	}

	function doListEvents() {

		$day = $_POST['d'];
		$month = $_POST['m'];
		$year = $_POST['y'];

		$timeStamp 	= mktime(0,0,0, $month, $day, $year);
		$tmp_day 		= (strlen($day) == 2) ? $day : '0'.$day;
		$tmp_month 		= (strlen($month) == 2) ? $month : '0'.$month;

		$str_html = '';


		if(isset($_REQUEST['example'])) {

			if($day == date('d')) {
				$arr_events = array (

				 array (
			    'event_id' => 101,
			    'title' => 'Back home',
			    'date_start' => date('Y-m-d', strtotime('-1DAY')),
				'time_start' => '10:00:00',
				'date_end' => date('Y-m-d', strtotime('-1DAY')),
				'time_end' => '15:00:00',
			    'allDay' => '1',
			    'calendartype' => '',
			    'user_id' => '2',
			    'color' => '#FFBB00',
			 ),  array (
			    'event_id' => 103,
			    'title' => 'event',
			    'date_start' => date('Y-m-d', strtotime('-1DAY')),
				'time_start' => '18:00:00',
				'date_end' => date('Y-m-d', strtotime('-1DAY')),
				'time_end' => '22:00:00',
			    'allDay' => '1',
			    'calendartype' => '',
			    'user_id' => '2',
			    'color' => '#FFBB00',
			 ));

			} else {
				$arr_events = array (

				 array (
			    'event_id' => 102,
			    'title' => 'Walking in the Belgian hills near Spa',
			    'date_start' => date('Y-m-d'),
			    'time_start' => '00:00:00',
			    'date_end' => date('Y-m-d'),
			    'time_end' => '00:00:00',
			    'allDay' => '1',
			    'calendartype' => '',
			    'user_id' => '2',
			    'color' => '#FFBB00',
			 ));
			}

		} else {
			$arr_events = Events::getSmallCalEvents($_SESSION['cal_id'],$year,$month,$day);
		}

		if(count($arr_events) >0) {

			if(date('d-m',$timeStamp) == date('d-m',mktime(0,0,0, date('m'), date('d'), date('Y')))) {
				$str_html .= '<br><b>today</b>';
			} else {
				$str_html .= '<br><b>'. date("d", $timeStamp) .'-'. date("m", $timeStamp) .'-'. date("Y", $timeStamp) .'</b>';
			}
			foreach($arr_events as $i => $event) {
				if($i % 2) { $bgColor = '#fff8dc' .''; } else { $bgColor='#dcdcdc' .''; }

				$str_html .= '<div style="background-color: '. $bgColor .'; margin-bottom: 4px; padding: 1px;">
						'. nl2br($event['title']) .($event['time_start'] !== '00:00:00' && $event['time_end'] !== '00:00:00' ? '<br />'.substr($event['time_start'], 0, 5).'-'.substr($event['time_end'], 0, 5) : '').'
					</div>';

			}
		} else {
			//echo 'There are no events here yet';
			$str_html .= '';
		}
 		echo $str_html;
	}
?>