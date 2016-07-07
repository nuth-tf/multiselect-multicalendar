<!DOCTYPE html>
<html lang="<?smarty if isset($language) && !empty($language) ?><?smarty $language ?><?smarty else ?><?smarty $smarty.const.LANGUAGE ?><?smarty /if ?>">
	<head>
		<meta charset="utf-8">
		<title><?smarty $smarty.const.CALENDAR_TITLE ?></title>

		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="<?smarty $smarty.const.CALENDAR_TITLE ?>">
		<meta name="author" content="">

		<!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
		<!--[if lt IE 9]>
			<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->

		<link href="<?smarty $smarty.const.EXTERNAL_URL ?>/bootstrap/css/bootstrap.min.css" rel="stylesheet">

		<link rel='stylesheet' type='text/css' href='<?smarty $smarty.const.EXTERNAL_URL ?>/jquery/jqueryui/1.8.17/jquery-ui.css' />
		
		<link rel="shortcut icon" href="/favicon.ico">

		<!-- Added library to header in order to load reports-->
		<script src="<?smarty $smarty.const.EXTERNAL_URL ?>/jquery/jquery.1.11.1.min.js"></script>
		<script src="<?smarty $smarty.const.EXTERNAL_URL ?>/jquery/jquery-ui.1.11.1.min.js" type="text/javascript" charset="utf-8"></script>

                	<link rel='stylesheet' type='text/css' href='<?smarty $smarty.const.EXTERNAL_URL ?>/jquery-timepicker-1.3.2/jquery.timepicker.css'  />
        <script type='text/javascript' src='<?smarty $smarty.const.EXTERNAL_URL ?>/jquery-timepicker-1.3.2/jquery.timepicker.min.js'></script>
		
        <script type='text/javascript' src='<?smarty $smarty.const.EXTERNAL_URL ?>/dateformat.js'></script>
        
		<script src='<?smarty $smarty.const.EXTERNAL_URL ?>/bgrins-spectrum/spectrum.js'></script>
		<link rel='stylesheet' href='<?smarty $smarty.const.EXTERNAL_URL ?>/bgrins-spectrum/spectrum.css' />
		
		<script type='text/javascript' src='<?smarty $smarty.const.FULLCAL_URL ?>/script/listeners.js'></script>
        <script type='text/javascript' src='<?smarty $smarty.const.FULLCAL_URL ?>/script/lang<?smarty if isset($settings.language) ?><?smarty $settings.language ?><?smarty else ?><?smarty $language ?><?smarty /if ?>.js'></script>


		<style>
                    ::-webkit-input-placeholder {
                        color: red;
                    }
                    :-moz-placeholder {
                        color: #acacac !important;
                    }
                    ::-moz-placeholder {
                        color: #acacac !important;
                    } /* for the future */
                    :-ms-input-placeholder {
                        color: #acacac !important;
                    }
                    .btn-primary.disabled, .btn-primary[disabled] {
                        background-color: #7EA2EA !important;
                    }
		</style>

        <script type='text/javascript'>
			MyCalendar = {};
            MyCalendar.FCfirstDay                   = '<?smarty $smarty.const.FIRSTDAY_OF_WEEK ?>';
    
            var tableToExcel = (function() {
                var uri = 'data:application/vnd.ms-excel;base64,'
                  , template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>'
                  , base64 = function(s) { return window.btoa(unescape(encodeURIComponent(s))) }
                  , format = function(s, c) { return s.replace(/{(\w+)}/g, function(m, p) { return c[p]; }) }
                return function(table, name) {
                  if (!table.nodeType) table = document.getElementById(table)
                  var ctx = {worksheet: name || 'Worksheet', table: table.innerHTML}
                  window.location.href = uri + base64(format(template, ctx))
                }
            })();
			
            $(document).ready(function() {
                                
                $('#lists_to_excel_btn').click(function(t) {
                    tableToExcel('lists_table', 'Hour calculation');
                });
                $('#list_to_excel_btn').click(function(t) {
                    tableToExcel('list_table', 'Hour calculation of');
                });
            });
		</script>
	</head>

	<body>

	<div class="navbar navbar-fixed-top">
		<div class="navbar">
			<div class="navbar-inner">
				<div class="container">

                    <a class="brand" href="#">Dashboard</a>
					<a style="float:left;padding-top:17px;color:#777777;" href="<?smarty $smarty.const.FULLCAL_URL ?>">Calendar</a>
                    <span style="float:right;padding-top:17px;"><?smarty $name ?></span>
                 	
				</div>
			</div>
		</div>
	</div>


	<div class="container">
		<div class="row">

			<div class="span12">
				<div class="tabbable tabs-left">

					<ul class="nav nav-tabs">

						<li <?smarty if $active == "profile" ?>class="active"<?smarty /if ?>><a href="<?smarty $smarty.const.FULLCAL_URL ?>/user/?action=get_profile&uid=<?smarty $user_id ?>" ><i class="icon-user"></i> Profile</a></li>

                        <?smarty if $smarty.const.USERS_CAN_ADD_CALENDARS ?>
                            <li <?smarty if $active == "calendars" ?>class="active"<?smarty /if ?>><a href="<?smarty $smarty.const.FULLCAL_URL ?>/user/calendars"><i class="icon-list"></i> My calendars</a></li>
                            <?smarty if $active == "calendar" ?><li class="active"><a href="#calendar" data-toggle="tab"><i class="icon-calendar"></i> Calendar</a></li><?smarty /if ?>

                            <!--<li <?smarty if $active == "public_calendars" ?>class="active"<?smarty /if ?>><a href="<?smarty $smarty.const.FULLCAL_URL ?>/user/calendars/public"><i class="icon-list"></i> Public calendars</a></li>-->
						<?smarty /if ?>
                        
                       <!-- <li <?smarty if $active == "lists" ?>class="active"<?smarty /if ?>><a href="<?smarty $smarty.const.FULLCAL_URL ?>/user/lists?action=get_list"><i class="icon-time"></i> <span id="user_hour_calculation_menu">Hour Calculation</span></a></li>
						-->
                        
						<li <?smarty if $active == "settings" ?>class="active"<?smarty /if ?>><a href="<?smarty $smarty.const.FULLCAL_URL ?>/<?smarty if $is_user ?>user/settings<?smarty /if ?>"><i class="icon-cog"></i> Settings</a></li>
                        
                        <li><a href="<?smarty $smarty.const.FULLCAL_URL ?>?action=logoff"><i class="icon-close"></i><span id="menu_logout">Log out</span></a></li>
                    
                    </ul>

					<div class="tab-content">


					<?smarty if $active == 'user' ?>
						<div id="admin-users"  style="padding-top:20px;padding-left:20px;">
							User dashboard
						</div>

					<?smarty elseif $active == 'profile' ?>
						<div id="admin-user-profile" style="padding-top:20px;padding-left:20px;">
							<legend>Profile</legend>

							<?smarty if !empty($save_profile_error) ?>
							<div style="position:absolute;left:400px;top:60px;color:red;font-weight:bold;">
								<?smarty $save_profile_error ?>
							</div>
							<?smarty /if ?>

							<?smarty if !empty($save_profile_success) ?>
							<div style="position:absolute;left:400px;top:60px;color:green;font-weight:bold;">
								<?smarty $save_profile_success ?>
							</div>
							<?smarty /if ?>

							<form action="<?smarty $smarty.const.FULLCAL_URL ?><?smarty if $is_user ?>/user<?smarty else ?>/admin/users<?smarty /if ?>/?action=save_profile" method="post" class="form-horizontal">

                                <div class="control-group">
									<label for="admin_user_profile_title" class="control-label">Title </label>
									<div class="controls">
										<input type="text" class="input-xlarge" id="profile_title" name="title" value="<?smarty $profile.title ?>">
									</div>
								</div>
                                
								<div class="control-group">
									<label for="admin_user_profile_name" class="control-label">Name </label>
									<div class="controls">
										<input style="width:94px;" type="text" class="input-xlarge" id="profile_firstname" name="firstname" placeholder="Firstname" value="<?smarty $profile.firstname ?>">
										<?smarty if $smarty.const.SHOW_INFIX_IN_USER_FRM ?>
											<input style="width:30px;" type="text" class="input-xlarge" id="profile_infix" name="infix" value="<?smarty $profile.infix ?>">
										<?smarty /if ?>
										<input style="width:<?smarty if $smarty.const.SHOW_INFIX_IN_USER_FRM ?>110<?smarty else ?>152<?smarty /if ?>px;" type="text" class="input-xlarge" id="profile_lastname" name="lastname" placeholder="Lastname" value="<?smarty $profile.lastname ?>">
									</div>
								</div>

								<div class="control-group">
									<label for="admin_email" class="control-label">Birthdate </label>
									<div class="controls">
									<?smarty if $smarty.const.DATEPICKER_DATEFORMAT == 'dd/mm/yy' ?>
										<input style="width:25px;" type="text" placeholder="DD" class="input-xlarge" id="profile_birthdate_day" name="birthdate_day" value="<?smarty $profile.birthdate_day ?>">
										<input style="width:25px;" type="text" placeholder="MM" class="input-xlarge" id="profile_birthdate_month" name="birthdate_month" value="<?smarty $profile.birthdate_month ?>">

									<?smarty else ?>
										<input style="width:25px;" type="text" placeholder="MM" class="input-xlarge" id="profile_birthdate_month" name="birthdate_month" value="<?smarty $profile.birthdate_month ?>">
										<input style="width:25px;" type="text" placeholder="DD" class="input-xlarge" id="profile_birthdate_day" name="birthdate_day" value="<?smarty $profile.birthdate_day ?>">

									<?smarty /if ?>
									<input style="width:45px;" type="text" placeholder="YYYY" class="input-xlarge" id="profile_birthdate_year" name="birthdate_year" value="<?smarty $profile.birthdate_year ?>">

									</div>
								</div>

								<div class="control-group">
									<label for="admin_user_profile_name" class="control-label">Country </label>
									<div class="controls">
										<input type="text" class="input-xlarge" id="profile_country" name="country" value="<?smarty $profile.country ?>">
									</div>
								</div>

								<div class="control-group">
									<label for="admin_user_profile_name" class="control-label">Email </label>
									<div class="controls">
										<input type="text" class="input-xlarge" id="profile_email" name="email" value="<?smarty $profile.email ?>">
									</div>
								</div>

								<div class="control-group">
									<label for="admin_user_profile_name" class="control-label">Username </label>
									<div class="controls">
										<input type="text" class="input-xlarge" id="profile_username" name="username" value="<?smarty $profile.username ?>">
									</div>
								</div>

								<div class="control-group">
									<label for="admin_user_profile_name" class="control-label">New password </label>
									<div class="controls">
										<input type="password" autocomplete="off" class="input-xlarge" id="profile_password" name="password" placeholder="Leave blank for no change">
									</div>
								</div>

								<div class="control-group">
									<label for="admin_user_profile_name" class="control-label">New password again <a tabindex="99" ></label>
									<div class="controls">
										<input type="password" autocomplete="off" class="input-xlarge" id="profile_confirm" name="confirm" placeholder="Leave blank for no change">
									</div>
								</div>
								<input type="hidden" name="user_id" value="<?smarty $profile.user_id ?>" />
									<div class="form-actions">
										<button id="save-profile" class="btn btn-primary" name="save-profile" data-complete-text="Changes saved" data-loading-text="saving..." type="submit">Save changes</button>
									</div>

							</form>


							</div>

                    <?smarty elseif $active == 'lists' ?>
						<div id="admin-users"  style="padding-top:20px;padding-left:20px;">
								<?smarty if !empty($error) ?>
								<div style="position:absolute;left:400px;top:60px;color:red;font-weight:bold;">
									<?smarty $error ?>
								</div>
								<?smarty /if ?>

								<?smarty if !empty($msg) ?>
								<div style="position:absolute;left:400px;top:60px;color:green;font-weight:bold;">
									<?smarty $msg ?>
								</div>
								<?smarty /if ?>

                                <span style="float:right;padding-top: 17px;" id="lists_to_excel_btn" class="not_print">
                                    <span class="dashboard_btn not_print">
                                        <i class="icon-th"></i> To Excel
                                    </span>
                                </span>	
                                
								<form id="calendars-form" action="<?smarty $smarty.const.FULLCAL_URL ?>/user/calendars/?action=new_calendar" method="post" class="form-horizontal">
									<?smarty if isset($smarty.session.add_calendar_error) ?>
									<div style="position:absolute;left:400px;color:red;font-weight:bold;">
										<?smarty $smarty.session.add_calendar_error ?>
									</div>
									<?smarty /if ?>

								</form>
								
								<legend id="admin_settings_hour_calculation_legend">Hour calculation</legend>
								
								
                                <div class="control-group" style="padding: 20px 0 30px 0;">
									<span class="control-label" id="admin_settings_calendars_label" style="width:auto;padding-right:5px;">Calendar </span>
                                    <select id="calendar_selectbox" name="calendar" style="width:150px;margin-bottom: 0;">
                                        <option class="calendar_option" value="all" <?smarty if $selected_calendar == "all" ?>selected="selected"<?smarty /if ?>>All</option>
                                        <?smarty foreach from=$calendars item=item ?>
                                            <option class="calendar_option" value="<?smarty $item.calendar_id ?>" <?smarty if $selected_calendar == $item.calendar_id ?>selected="selected"<?smarty /if ?>><?smarty $item.name ?></option>
                                        <?smarty /foreach ?>
                                    </select>


                                    <span id="month_label_id" style="padding-left:30px;margin-bottom: 0;">From: </span>
                                        <input type="text" id="hourcalc_datepicker_startdate" style="font-size:13px;margin-bottom: 0;width: 80px;padding:3px;z-index:9999;">
                                    <span id="time_label_id">To: </span>
                                        <input type="text" id="hourcalc_datepicker_enddate" style="font-size:13px;margin-bottom: 0;width: 80px;padding:3px;" />

                                    <button id="dates_clear_button" style="padding:3px 12px;" class="btn btn-secondary" name="clear-list" data-complete-text="fields cleared" data-loading-text="saving..." >Clear</button> 
                                    <button id="userpanel_dates_refresh_button" style="float:right;margin-left:50px;padding:3px 12px;" class="btn btn-primary" name="refresh-list" data-complete-text="Changes saved" data-loading-text="saving..." >Refresh</button> 
								</div>

								<div id="calendar_list">
									<table class="table table-striped" id="lists_table" style="font-size:14px;">
										<thead>
											<tr>
												<th><span class="name_label">Name</span></th>
												<th><span class="days_label">Days</span></th>
												<th><span class="hours_label">Hours</span></th>
												
											</tr>
										</thead>
										<tbody>

										<?smarty foreach from=$list item=item ?>

											<tr>
												<td><?smarty $item.fullname ?></td>
												<td><?smarty $item.days ?></td>
												<td><?smarty $item.hours ?></td>
                                                <td class="not_print" style="border-left:1px solid #DDDDDD;width:10px;"><a href="<?smarty $smarty.const.FULLCAL_URL ?>/user/lists?action=get_list&uid=<?smarty $item.user_id ?><?smarty if isset($smarty.get.cid) && !empty($smarty.get.cid) ?>&cid=<?smarty $smarty.get.cid ?><?smarty /if ?>"><img src="<?smarty $smarty.const.FULLCAL_URL ?>/images/view.png" /></a></td>
												
												
											</tr>
										<?smarty foreachelse ?>
											<tr>
												<td>No rows found</td>
												<td>&nbsp;</td>
												<td>&nbsp;</td>
												<td>&nbsp;</td>
											</tr>
										<?smarty /foreach ?>

										</tbody>
									</table>

								<!--	<div class="pagination"><ul><li class="prev disabled"><a href="<?smarty $smarty.const.FULLCAL_URL ?>/user/calendars?to=<?smarty $from ?>"> Previous</a></li><li class="active"><a href="#">1</a></li><li class="next disabled"><a href="#">Next </a></li></ul></div>	</div>-->


							</div>
					<?smarty elseif $active == 'list' ?>
						<div id="admin-users"  style="padding-top:20px;padding-left:20px;">
								<?smarty if !empty($error) ?>
								<div style="position:absolute;left:400px;top:60px;color:red;font-weight:bold;">
									<?smarty $error ?>
								</div>
								<?smarty /if ?>

								<?smarty if !empty($msg) ?>
								<div style="position:absolute;left:400px;top:60px;color:green;font-weight:bold;">
									<?smarty $msg ?>
								</div>
								<?smarty /if ?>

								
								<!--<form id="calendars-form" action="" method="post" class="form-horizontal">-->
									<?smarty if isset($smarty.session.add_calendar_error) ?>
									<div style="position:absolute;left:400px;color:red;font-weight:bold;">
										<?smarty $smarty.session.add_calendar_error ?>
									</div>
									<?smarty /if ?>

                                    <span style="float:right;" id="list_to_excel_btn">
                                        <span class="dashboard_btn">
                                            <i class="icon-th"></i> To Excel
                                        </span>
                                    </span>	
									
								
                                    <legend><span id="admin_settings_user_hour_calculation_legend">Hour calculation of</span> <strong><?smarty $user.title ?> <?smarty $user.lastname ?>, <?smarty $user.firstname ?> <?smarty $user.infix ?></strong></legend>
								
								<div class="control-group" style="padding: 20px 0 30px 0;">
									<span class="control-label" id="admin_settings_calendars_label" style="width:auto;padding-right:5px;">Calendar </span>
                                    <select id="calendar_selectbox" name="calendar" style="width:150px;margin-bottom: 0;">
                                        <option class="calendar_option" value="all" <?smarty if $selected_calendar == "all" ?>selected="selected"<?smarty /if ?>>All</option>
                                        <?smarty foreach from=$calendars item=item ?>
                                            <option class="calendar_option" value="<?smarty $item.calendar_id ?>" <?smarty if $selected_calendar == $item.calendar_id ?>selected="selected"<?smarty /if ?>><?smarty $item.name ?></option>
                                        <?smarty /foreach ?>
                                    </select>


                                    <span id="month_label_id" style="padding-left:30px;margin-bottom: 0;">From: </span>
                                        <input type="text" id="hourcalc_datepicker_startdate" style="font-size:13px;margin-bottom: 0;width: 80px;padding:3px;z-index:9999;">
                                    <span id="time_label_id">To: </span>
                                        <input type="text" id="hourcalc_datepicker_enddate" style="font-size:13px;margin-bottom: 0;width: 80px;padding:3px;" />

                                    <button id="dates_clear_button" style="padding:3px 12px;" class="btn btn-secondary" name="clear-list" data-complete-text="fields cleared" data-loading-text="saving..." >Clear</button> 
                                    <button id="userpanel_user_dates_refresh_button" style="float:right;margin-left:50px;padding:3px 12px;" class="btn btn-primary" name="refresh-list" data-complete-text="Changes saved" data-loading-text="saving..." >Refresh</button> 
								</div>
							

								<!--</form>-->
								
								<div id="calendar_list">
									<table class="table table-striped" id="list_table" style="font-size:14px;">
										<thead>
											<tr>
												<th><span class="date_label">Date</span></th>
												<th><span class="time_label">Time</span></th>
												<th><span class="days_label">Days</span></th>
												<th><span class="hours_label">Hours</span></th>
												<th><span class="calendar_label">Calendar</span></th>

											</tr>
										</thead>
										<tbody>

										<?smarty foreach from=$list item=item ?>

											<tr>
												<td style="width:190px;"><?smarty $item.date_start ?><?smarty if $item.date_end != $item.date_start ?> - <?smarty $item.date_end ?><?smarty /if ?></td>
												<td><?smarty if $item.allDay ?>allday<?smarty else ?><?smarty $item.time_start ?> - <?smarty $item.time_end ?><?smarty /if ?></td>
												<td><?smarty $item.days ?></td>
												<td><?smarty $item.hours ?></td>
												<td><?smarty $item.name ?></td>
											</tr>
										<?smarty /foreach ?>
										
										<tr style="border-top:2px solid #333333;">
											<td>Total</td>
											<td>&nbsp;</td>
											<td><?smarty $total_day_count ?></td>
											<td><?smarty $total_hour_count ?></td>
											<td>&nbsp;</td>
										</tr>
											
										</tbody>
									</table>

								<!--	<div class="pagination"><ul><li class="prev disabled"><a href="<?smarty $smarty.const.FULLCAL_URL ?>/admin/calendars?to=<?smarty $from ?>"> Previous</a></li><li class="active"><a href="#">1</a></li><li class="next disabled"><a href="#">Next </a></li></ul></div>	</div>-->


							</div>
					
					<?smarty elseif $active == 'calendars' ?>
							<div id="admin-users"  style="padding-top:20px;padding-left:20px;">
								<?smarty if !empty($error) ?>
								<div style="position:absolute;left:400px;top:60px;color:red;font-weight:bold;">
									<?smarty $error ?>
								</div>
								<?smarty /if ?>

								<?smarty if !empty($msg) ?>
								<div style="position:absolute;left:400px;top:60px;color:green;font-weight:bold;">
									<?smarty $msg ?>
								</div>
								<?smarty /if ?>


								<form id="calendars-form" action="<?smarty $smarty.const.FULLCAL_URL ?>/user/calendars/?action=new_calendar" method="post" class="form-horizontal">
									<?smarty if isset($smarty.session.add_calendar_error) ?>
									<div style="position:absolute;left:400px;color:red;font-weight:bold;">
										<?smarty $smarty.session.add_calendar_error ?>
									</div>
									<?smarty /if ?>

									<div style="float:right;">
										<button <?smarty if $disable_add_cal_btn ?> disabled="disabled"<?smarty /if ?> id="user-add-calendar-btn" class="btn btn-primary<?smarty if $disable_add_cal_btn ?> disabled<?smarty /if ?>" name="add-calendar" data-complete-text="Changes saved" data-loading-text="saving..." type="submit">Add calendar</button>
									</div>

								</form>

								<legend>My calendars</legend>

								<div id="calendar_list">
									<table class="table table-striped" style="font-size:14px;">
										<thead>
											<tr>
												<th colspan="4"></th>
                                                <th style="text-align:center;" colspan="4"><span class="others_can_label">Others can</span></th>
												<th colspan="2"></th>
											</tr>
										</thead>
                                        <thead>
											<tr>
												<th style="border-top:0 none;"></th>
												<th style="border-top:0 none;"><span class="name_label">Name</span></th>
												<th style="border-top:0 none;"><span class="dditems_label">DD-items</span></th>
												<th style="border-top:0 none;"><span class="type_label">Type</span></th>
												<th style="text-align:center;border-top:1px dotted lightgray;"><span class="add_label">Add</span></th>
												<th style="text-align:center;border-top:1px dotted lightgray;"><span class="edit_label">Edit</span></th>
												<th style="text-align:center;border-top:1px dotted lightgray;"><span class="delete_label">Delete</span></th>
												<th style="text-align:center;border-top:1px dotted lightgray;width:45px;"><span class="changecolor_label">Change color</span></th>
												<th style="text-align:center;border-top:0 none;"><span class="initialshow_label">Initial show</span></th>
												<th style="text-align:center;border-top:0 none;"><span class="active_label">Active</span></th>
												<?smarty if $is_admin ?><th style="border-top:0 none;"><span class="owner_label">Owner</span></th><?smarty /if ?>

											</tr>
										</thead>
										<tbody>

										<?smarty foreach from=$calendars item=item ?>

											<tr>
												<td style="width:10px;background-color:<?smarty $item.calendar_color ?>;"></td>
												<td><?smarty $item.name ?></a></td>
												<td><?smarty $item.dditems|@count ?></td>
												<td><?smarty if $item.share_type == "private" ?>Private (only me)<?smarty else ?><?smarty $item.share_type ?><?smarty /if ?></td>
												<td style="text-align:center;"><img src="<?smarty $smarty.const.FULLCAL_URL ?>/images/<?smarty if $item.can_add ?>checked.png<?smarty else ?>unchecked.png<?smarty /if ?>" /></td>
												<td style="text-align:center;"><img src="<?smarty $smarty.const.FULLCAL_URL ?>/images/<?smarty if $item.can_edit ?>checked.png<?smarty else ?>unchecked.png<?smarty /if ?>" /></td>
												<td style="text-align:center;"><img src="<?smarty $smarty.const.FULLCAL_URL ?>/images/<?smarty if $item.can_delete ?>checked.png<?smarty else ?>unchecked.png<?smarty /if ?>" /></td>
												<td style="text-align:center;"><img src="<?smarty $smarty.const.FULLCAL_URL ?>/images/<?smarty if $item.can_change_color ?>checked.png<?smarty else ?>unchecked.png<?smarty /if ?>" /></td>
												<td style="text-align:center;"><img src="<?smarty $smarty.const.FULLCAL_URL ?>/images/<?smarty if $item.initial_show ?>checked.png<?smarty else ?>unchecked.png<?smarty /if ?>" /></td>
												<td style="text-align:center;"><img src="<?smarty $smarty.const.FULLCAL_URL ?>/images/<?smarty if $item.active == 'yes' ?>checked.png<?smarty else ?>unchecked.png<?smarty /if ?>" /></td>
												<?smarty if $is_admin ?><td><?smarty $item.creator_id ?></td><?smarty /if ?>

                                                <?smarty if $item.deleted == 0 ?>
                                                    <td class="not_print" style="border-left:1px solid #DDDDDD;width:10px;"><a href="<?smarty $smarty.const.FULLCAL_URL ?>/user/calendars?action=get_calendar&cid=<?smarty $item.calendar_id ?>"><img src="<?smarty $smarty.const.FULLCAL_URL ?>/images/edit.png" /></a></td>
                                                    <?smarty if $user_id == $item.creator_id ?><td class="not_print" style="width:10px;"><a href="<?smarty $smarty.const.FULLCAL_URL ?>/user/calendars?action=delete&cid=<?smarty $item.calendar_id ?>"><img src="<?smarty $smarty.const.FULLCAL_URL ?>/images/delete.png" /></a></td><?smarty else ?><td>&nbsp;</td><?smarty /if ?>
                                                <?smarty else ?>
                                                    <?smarty if $user_id == $item.creator_id ?><td class="not_print" style="width:10px;"><a class="undo_delete_btn" href="<?smarty $smarty.const.FULLCAL_URL ?>/user/calendars?action=undelete&cid=<?smarty $item.calendar_id ?>">Undo delete</a></td><?smarty else ?><td>&nbsp;</td><?smarty /if ?>
                                                
                                                <?smarty /if ?>
                                                
												
											</tr>
										<?smarty /foreach ?>

										</tbody>
									</table>

                                    <?smarty if empty($calendars) ?>
                                        No calendars found
                                    <?smarty /if ?>
								<!--	<div class="pagination"><ul><li class="prev disabled"><a href="<?smarty $smarty.const.FULLCAL_URL ?>/user/calendars?to=<?smarty $from ?>"> Previous</a></li><li class="active"><a href="#">1</a></li><li class="next disabled"><a href="#">Next </a></li></ul></div>	</div>-->


							</div>

					<?smarty elseif $active == 'public_calendars' ?>
							<div id="admin-users"  style="padding-top:20px;padding-left:20px;">
								<?smarty if !empty($error) ?>
								<div style="position:absolute;left:400px;top:60px;color:red;font-weight:bold;">
									<?smarty $error ?>
								</div>
								<?smarty /if ?>

								<?smarty if !empty($msg) ?>
								<div style="position:absolute;left:400px;top:60px;color:green;font-weight:bold;">
									<?smarty $msg ?>
								</div>
								<?smarty /if ?>


								<form id="calendars-form" action="<?smarty $smarty.const.FULLCAL_URL ?>/user/calendars/?action=new_calendar" method="post" class="form-horizontal">
									<?smarty if isset($smarty.session.add_calendar_error) ?>
									<div style="position:absolute;left:400px;color:red;font-weight:bold;">
										<?smarty $smarty.session.add_calendar_error ?>
									</div>
									<?smarty /if ?>

									<div style="float:right;">
										<button id="user-add-calendar-btn" class="btn btn-primary" name="add-calendar" data-complete-text="Changes saved" data-loading-text="saving..." type="submit">Add calendar</button>
									</div>

								</form>

								<legend>Public calendars</legend>

								<div id="calendar_list">
									<table class="table table-striped" style="font-size:14px;">
										<thead>
											<tr>
												<th></th>
												<th><span class="name_label">Name</span></th>
												<th><span class="active_label">Active</span></th>
												

											</tr>
										</thead>
										<tbody>

										<?smarty foreach from=$calendars item=item ?>

											<tr>
												<td style="width:10px;background-color:<?smarty $item.calendar_color ?>;"></td>
												<td><?smarty $item.name ?></a></td>
												<td><img src="<?smarty $smarty.const.FULLCAL_URL ?>/images/<?smarty if $item.active ?>checked.png<?smarty else ?>unchecked.png<?smarty /if ?>" /></td>
												

												<td><a href="<?smarty $smarty.const.FULLCAL_URL ?>/user/calendars/public/?action=set_active&cid=<?smarty $item.calendar_id ?>">Set active</a></td>
												
											</tr>
										<?smarty /foreach ?>

										</tbody>
									</table>

								<!--	<div class="pagination"><ul><li class="prev disabled"><a href="<?smarty $smarty.const.FULLCAL_URL ?>/user/calendars?to=<?smarty $from ?>"> Previous</a></li><li class="active"><a href="#">1</a></li><li class="next disabled"><a href="#">Next </a></li></ul></div>	</div>-->


							</div>

					
                    <?smarty elseif $active == 'calendar' ?>
						<div id="admin-user-calendar" style="padding-top:20px;padding-left:20px;">
							<legend><?smarty if isset($smarty.get.action) && $smarty.get.action == 'new_calendar' ?>Add calendar<?smarty else ?>Edit calendar: <strong><?smarty $calendar.name ?></strong><?smarty /if ?></legend>

							<?smarty if !empty($save_calendar_error) ?>
							<div style="position:absolute;left:400px;top:60px;color:red;font-weight:bold;">
								<?smarty $save_calendar_error ?>
							</div>
							<?smarty /if ?>

							<?smarty if !empty($save_calendar_success) ?>
							<div style="position:absolute;left:400px;top:60px;color:green;font-weight:bold;">
								<?smarty $save_calendar_success ?>
							</div>
							<?smarty /if ?>

							<form action="<?smarty $smarty.const.FULLCAL_URL ?>/user/calendars/?action=save_calendar" method="post" class="form-horizontal">

								<div class="control-group">
									<label for="admin_user_calendar_active" class="control-label">Active </label>
									<div class="controls"  style="padding-top:5px;">
                                        <span>
                                            <input type="radio" value="yes" name="active"  style="float:left;margin-right:5px;" id="admin_calendar_active_yes" <?smarty if $calendar.active == 'yes' || !isset($calendar.active) || empty($calendar.active) ?>checked="true"<?smarty /if ?> /><label for="admin_calendar_active_yes" style="padding-top:1px;width: 33px;float:left;padding-right:20px;">Yes </label>
                                            <input type="radio" value="no" name="active"  style="float:left;margin-right:5px;" <?smarty if $calendar.active == 'no' ?>checked="true"<?smarty /if ?> /><label for="admin_calendar_active_yes" style="padding-top:1px;width: 33px;float:left;padding-right:20px;">No </label>
                                            <input type="radio" value="period" id="radio_specific_period" name="active"  style="float:left;margin-right:5px;" <?smarty if $calendar.active == 'period' ?>checked="true"<?smarty /if ?> /><label for="admin_calendar_active_yes" style="padding-top:1px;width: 200px;float:left;padding-right:20px;">In specific period </label>
									    </span>
                                    </div>
								</div>
                               
                                <!-- active period -->
                                <div class="control-group">
                                    <label for="admin_calendar_active" class="control-label">Active period </label>
									<span class="simple_starttime_label" style="padding-left:20px;margin-bottom: 0;">From: </span>
                                    <input type="text" name="cal_startdate" id="active_period_datepicker_startdate" value="<?smarty $calendar.cal_startdate ?>" <?smarty if $calendar.active != 'period' ?>disabled="disabled"<?smarty /if ?> style="font-size:13px;margin-bottom: 0;width: 95px;padding:3px;z-index:9999;">
                                    <span class="simple_endtime_label">Until: </span>
                                        <input type="text" name="cal_enddate" id="active_period_datepicker_enddate" value="<?smarty $calendar.cal_enddate ?>" <?smarty if $calendar.active != 'period' ?>disabled="disabled"<?smarty /if ?> style="font-size:13px;margin-bottom: 0;width: 95px;padding:3px;" />
                                </div>
                                
                                <!-- alterable period restriction -->
                                <div class="control-group">
                                    <label for="admin_calendar_alterable" class="control-label">Alterable period </label>
									<span class="simple_starttime_label" style="padding-left:20px;margin-bottom: 0;">From: </span>
                                        <input type="text" name="alterable_startdate" id="alterable_period_datepicker_startdate" value="<?smarty $calendar.alterable_startdate ?>" style="font-size:13px;margin-bottom: 0;width: 95px;padding:3px;z-index:9999;">
                                    <span class="simple_endtime_label">To: </span>
                                        <input type="text" name="alterable_enddate" id="alterable_period_datepicker_enddate" value="<?smarty $calendar.alterable_enddate ?>" style="font-size:13px;margin-bottom: 0;width: 95px;padding:3px;" />
                                </div>
                                
                                <div class="control-group">
									<label for="admin_user_calendar_name" class="control-label">Name </label>
									<div class="controls">
										<input type="text" class="" id="calendar_name" name="name" placeholder="Name" value="<?smarty $calendar.name ?>">
									</div>
								</div>

								<!--<div class="control-group">
									<label for="admin_user_calendar_share_type" class="control-label">Type </label>
									<div class="controls">
										<input type="text" class="input-xlarge" id="calendar_type" name="country" value="<?smarty $calendar.share_type ?>">
									</div>
								</div>-->

								<div class="control-group">
									<label for="user_calendar_dditems" class="control-label">DD-items </label>
									<div class="controls">
										<!--<textarea class="input-xlarge" id="calendar_dditems" name="dditems" ><?smarty $calendar.dditems ?></textarea>-->
                                        <input type="hidden" id="calendar_dditems" name="dditems" value="<?smarty $str_dditems ?>" />
									
                                        <table class="table table-striped" style="font-size:14px;width:510px;font-size:13px;margin-bottom:0;">
                                            <thead>
                                                <tr style="">
                                                    <th style="" colspan="2"><span></span></th>
                                                    <th style="text-align:center;border-bottom:1px dotted lightgray;" colspan="2"><span id="monthview_label">Monthview</span></th>
                                                    <th style=""><span></span></th>
                                                   
                                               </tr>
                                            </thead>
                                            <thead>
                                                <tr style="">
                                                    <th style="text-align:center;width:195px;border-top:0 none;padding:0;border-bottom:1px solid lightgray;"><span class="title_label">Title</span> *</th>
                                                    <th style="text-align:center;width:195px;border-top:0 none;padding:0;border-bottom:1px solid lightgray;"><span class="info_label">Info</span></th>
                                                    <th style="text-align:center;width:100px;border-top:0 none;padding:0;border-bottom:1px solid lightgray;"><span id="starttime_label">Start time</span></th>
                                                    <th style="text-align:center;width:100px;border-top:0 none;padding:0;border-bottom:1px solid lightgray;"><span id="endtime_label">End time</span></th>
                                                    <th style="text-align:center;width:100px;border-top:0 none;padding:0;border-bottom:1px solid lightgray;"><span id="nightshift_label">Night shift</span></th>
                                                    <th style="text-align:center;width:60px;border-top:0 none;padding:0;border-bottom:1px solid lightgray;"><span class="color_label">Color</span></th>
                                               </tr>
                                            </thead>
                                            <tbody>

                                            <?smarty foreach from=$calendar.dditems item=item ?>
                                                <tr>
                                                    
                                                    <td style="padding:1px;border:none;"><input type="text" style="width:150px;" name="title<?smarty $item.dditem_id ?>" value="<?smarty $item.title ?>" class="user-dditem-title" id="user-spectrum-colorpicker-dditem-title-<?smarty $item.dditem_id ?>" /></td>
                                                    <td style="padding:1px;border:none;"><input type="text" style="width:150px;" name="info<?smarty $item.dditem_id ?>" value="<?smarty $item.info ?>" class="user-dditem-info" id="user-dditem-info-<?smarty $item.dditem_id ?>" /></td>
                                                    <td style="padding:2px;border:none;"><input type="text" class="user_dditem_timepicker_starttime" id="user_dditem_timepicker_starttime-<?smarty $item.dditem_id ?>" name="starttime<?smarty $item.dditem_id ?>" value="" style="font-size:13px;width: 80px;"></td>
                                                    <td style="padding:2px;border:none;"><input type="text" class="user_dditem_timepicker_endtime" id="user_dditem_timepicker_endtime-<?smarty $item.dditem_id ?>" name="endtime<?smarty $item.dditem_id ?>" value="" style="font-size:13px;width: 80px;"></td>
                                                    <td style="padding:2px;border:none;"><input type="checkbox" class="user_dditem_nightshift" id="user-dditem-nightshift-<?smarty $item.dditem_id ?>" name="nightshift<?smarty $item.dditem_id ?>" <?smarty if $item.nightshift ?>checked="checked"<?smarty /if ?> style="font-size:13px;width: 80px;"></td>
                                                    <td style="padding:1px;border:none;"><input type="text" class="input-xlarge user-spectrum-colorpicker-dditems" id="user-spectrum-colorpicker-dditem-<?smarty $item.dditem_id ?>" name="dditem_color[]" value="<?smarty $item.color ?>" data-title="<?smarty $item.title ?>" data-number="<?smarty $item.dditem_id ?>"></td>
                                                    
                                                </tr>
                                               
                                            <?smarty /foreach ?>

                                            </tbody>
                                        </table>
                                       <input type="button" id="add_dditem" value="Add a DD-item" />
                                    </div>
								</div>
                                <div class="control-group">
									<label for="user_can_see_dditems" class="control-label" id="user_can_see_dditems_label">Can see DD-items </label>
									<div class="controls">
										<!--  -->
										<select name="can_dd_drag">
											<option value="only_owner" <?smarty if $calendar.can_dd_drag == "only_owner" ?>selected="selected"<?smarty /if ?>>Only calendar owner</option>
											<option value="only_loggedin_users" <?smarty if $calendar.can_dd_drag == "only_loggedin_users" ?>selected="selected"<?smarty /if ?>>Only loggedin users</option>
											<option value="everyone" <?smarty if $calendar.can_dd_drag == "everyone" ?>selected="selected"<?smarty /if ?>>Everyone</option>
										</select>
									</div>
								</div>
                                <div class="control-group">
									<label for="user_calendar_origin" class="control-label" id="user_origin_label">Origin </label>
									<div class="controls">
										<!--  -->
										<select name="origin" id="user_calendar_origin">
											<option value="default" <?smarty if !isset($calendar.origin) || empty($calendar.origin) || $calendar.origin == "default" ?>selected="selected"<?smarty /if ?>>Default</option>
											<!--<option value="exchange" <?smarty if $calendar.origin == "exchange" ?>selected="selected"<?smarty /if ?>>Exchange</option>-->
										</select>
									</div>
								</div>
                                <!--<div class="control-group" id="exchange_username_field" style="padding-left: 100px;display:<?smarty if isset($calendar.origin) && !empty($calendar.origin) && $calendar.origin == "exchange" ?>block<?smarty else ?>none<?smarty /if ?>;">
                                    <div style="padding-left: 100px;padding-bottom:4px;font-style: italic;">mcrypt is used to save username and password in the database</div>
                                    <label for="user_exchange_username" class="control-label">Username </label>
									<div class="controls">
										<input type="text" class="" id="exchange_username" name="exchange_username" value="<?smarty $calendar.exchange_username ?>">
                                    </div>
								</div>
                                <div class="control-group" id="exchange_password_field" style="padding-left: 100px;display:<?smarty if isset($calendar.origin) && !empty($calendar.origin) && $calendar.origin == "exchange" ?>block<?smarty else ?>none<?smarty /if ?>;">
									<label for="user_exchange_password" class="control-label">Password </label>
									<div class="controls">
									    <input type="password" class="" id="exchange_password" name="exchange_password" value="<?smarty $calendar.exchange_password ?>">
                                    </div>
								</div>
                                <div class="control-group" id="user_exchange_extra_secure" style="margin-bottom:1px;display:<?smarty if isset($calendar.origin) && !empty($calendar.origin) && $calendar.origin == "exchange" ?>block<?smarty else ?>none<?smarty /if ?>;">
									<div class="controls" style="margin-left:20px;padding-bottom:5px;">
										<label id="" class="control-label"></label>
                                        <input type="checkbox" id="exchange_extra_secure_checkbox" <?smarty if $calendar.exchange_extra_secure == 1 ?>checked="checked" <?smarty /if ?> name="exchange_extra_secure" />
										<span id="user_exchange_extra_secure" style="padding-top:5px;vertical-align:middle;">Extra secure, user needs to know this token</span>
									</div>
								</div>
                                <div class="control-group" id="exchange_token_field" style="padding-left: 100px;display:<?smarty if isset($calendar.origin) && !empty($calendar.origin) && $calendar.origin == "exchange" ?>block<?smarty else ?>none<?smarty /if ?>;">
									<label for="user_exchange_token" class="control-label">Secret token </label>
									<div class="controls">
									    <input type="password" class="" id="exchange_token" name="exchange_token" value="<?smarty $calendar.exchange_token ?>">
                                    </div>
								</div>-->
                                <div class="control-group">
									<label for="user_settings_share_type" class="control-label" id="admin_settings_share_type_label">Share type </label>
									<div class="controls">
										
										<select name="share_type" id="user_settings_share_type">
											<option value="public" <?smarty if $calendar.share_type == "public" ?>selected="selected"<?smarty /if ?>>Public</option>
											<option value="private" <?smarty if $calendar.share_type == "private" ?>selected="selected"<?smarty /if ?>>Private (only for me)</option>
										</select>
									</div>
								</div>
								<div class="control-group">
									<label for="user_calendar_canadd" class="control-label">Others can add </label>
									<div class="controls">
										<input type="checkbox" name="can_add" id="user_settings_can_add" <?smarty if $calendar.can_add && $calendar.share_type != "private" ?>checked="true"<?smarty /if ?> />
									</div>
								</div>
								<div class="control-group">
									<label for="user_calendar_canedit" class="control-label">Others can edit </label>
									<div class="controls">
										<input type="checkbox" name="can_edit" id="user_settings_can_edit" <?smarty if $calendar.can_edit && $calendar.share_type != "private" ?>checked="true"<?smarty /if ?> />
									</div>
								</div>
                                <div class="control-group">
									<label for="user_calendar_candelete" class="control-label">Others can delete </label>
									<div class="controls">
										<input type="checkbox" name="can_delete" id="user_settings_can_delete" <?smarty if $calendar.can_delete && $calendar.share_type != "private" ?>checked="true"<?smarty /if ?> />
									</div>
								</div>
                                <div class="control-group">
									<label for="user_calendar_canchange_color" class="control-label">Others can change color </label>
									<div class="controls">
										<input type="checkbox" id="user_settings_can_change_color" name="can_change_color" <?smarty if $calendar.can_change_color && $calendar.share_type != "private" ?>checked="true"<?smarty /if ?> <?smarty if $calendar.share_type == "private" ?>disabled="disabled"<?smarty /if ?> />
									</div>
								</div>
                                
								<div class="control-group">
									<label for="admin_user_calendar_default" class="control-label">Default </label>
									<div class="controls">
										<input type="checkbox" name="initial_show" <?smarty if $calendar.initial_show ?>checked="true"<?smarty /if ?> /> 
                                        <span id="user_initial_show_checkbox_label" style="padding-top:5px;vertical-align:middle;">The calendar is shown initially</span>
                                    </div>
								</div>

								<div class="control-group">
									<label for="admin_user_calendar_color" class="control-label">Color </label>
									<div class="controls" style="width:150px;">
										<input type="text" class="input-xlarge" id="user-spectrum-colorpicker" name="calendar_color" value="<?smarty $calendar.calendar_color ?>">
									</div>
								</div>

								<div class="control-group" id="color_change_all_events" style="margin-bottom:1px;">
									<div class="controls" style="margin-left:20px;padding-bottom:5px;">
										<label id="" class="control-label"></label>
										<input type="checkbox" name="checkbox_use_color_for_all_events" />
										<span id="admin_color_use_for_all_events_checkbox_label" style="padding-top:5px;vertical-align:middle;">Use current color for all the events of this calendar</span>
									</div>
								</div>
<hr />
                                <h4 style="padding: 10px 0 10px 180px;font-weight:bold;">
                                    <span id="">Edit dialog fields</span>
                                </h4>
                                <div class="control-group" style="margin:0;">
                                    <div style="float:left;">
                                        <label class="control-label" style="padding-top:0;">
                                        <span id="admin_show_description_in_edit_dialog_checkbox_label">Show description field</span>
                                        </label>
                                        <div class="controls" style="width:80px;">
                                            <input type="checkbox" name="show_description_field" <?smarty if $calendar.show_description_field == "on" ?>checked="checked"<?smarty /if ?> />
                                        </div>
                                    </div>
                                    <div>
                                        <label class="control-label" style="padding-top:1px;width:130px;padding-right: 20px;">
                                        <span id="admin_description_field_required_checkbox_label">Required</span>
                                        </label>
                                        <div class="controls" style="margin-left:400px;">
                                            <input type="checkbox" name="description_field_required" <?smarty if $calendar.description_field_required == "on" ?>checked="checked"<?smarty /if ?> />
                                        </div>
                                    </div>
                                </div>
                                <div class="control-group" id="" style="margin-bottom:1px;">
                                    <div style="float:left;">
                                        <label class="control-label" style="padding-top:0;">
                                            <span id="admin_show_location_in_edit_dialog_checkbox_label">Show location field</span>
                                        </label>
                                        <div class="controls" style="width:80px;">
                                            <input type="checkbox" name="show_location_field" <?smarty if $calendar.show_location_field == "on" ?>checked="checked"<?smarty /if ?> />
                                        </div> 
                                    </div>
                                    <div>
                                        <label class="control-label" style="padding-top:1px;width:130px;padding-right: 20px;">
                                        <span id="admin_location_field_required_checkbox_label">Required</span>
                                        </label>
                                        <div class="controls" style="margin-left:400px;">
                                            <input type="checkbox" name="location_field_required" <?smarty if $calendar.location_field_required == "on" ?>checked="checked"<?smarty /if ?> />
                                        </div>
                                    </div>
                                </div>
                                <div class="control-group" id="" style="margin-bottom:1px;">
                                    <div style="float:left;"> 
                                        <label class="control-label" style="padding-top:0;">
                                        <span id="admin_show_phone_in_edit_dialog_checkbox_label">Show phone field</span>
                                        </label>
                                        <div class="controls" style="width:80px;">
                                            <input type="checkbox" name="show_phone_field" <?smarty if $calendar.show_phone_field == "on" ?>checked="checked"<?smarty /if ?> />
                                        </div>
                                    </div>
                                    <div>
                                        <label class="control-label" style="padding-top:1px;width:130px;padding-right: 20px;">
                                        <span id="admin_phone_field_required_checkbox_label">Required</span>
                                        </label>
                                        <div class="controls" style="margin-left:400px;">
                                            <input type="checkbox" name="phone_field_required" <?smarty if $calendar.phone_field_required == "on" ?>checked="checked"<?smarty /if ?> />
                                        </div>
                                    </div>
                                </div>
                                <div class="control-group" id="" style="margin-bottom:1px;">
                                    <div style="float:left;"> 
                                        <label class="control-label" style="padding-top:0;">
                                            <span id="admin_show_url_in_edit_dialog_checkbox_label">Show URL field</span>
                                        </label>
                                        <div class="controls" style="width:80px;">
                                            <input type="checkbox" name="show_url_field" <?smarty if $calendar.show_url_field == "on" ?>checked="checked"<?smarty /if ?> />
                                        </div>
                                    </div>
                                    <div>
                                        <label class="control-label" style="padding-top:1px;width:130px;padding-right: 20px;">
                                        <span id="admin_url_field_required_checkbox_label">Required</span>
                                        </label>
                                        <div class="controls" style="margin-left:400px;">
                                            <input type="checkbox" name="url_field_required" <?smarty if $calendar.url_field_required == "on" ?>checked="checked"<?smarty /if ?> />
                                        </div>
                                    </div>
                                </div>
                                <div class="control-group" id="" style="margin-bottom:1px;">
                                    <div style="float:left;"> 
                                        <label class="control-label" style="padding-top:0;">
                                        <span id="admin_show_teammember_in_edit_dialog_checkbox_label">Show team member field</span>
                                        </label>
                                        <div class="controls" style="width:80px;">
                                            <input type="checkbox" name="show_team_member_field" <?smarty if $calendar.show_team_member_field ?>checked="checked"<?smarty /if ?> />
                                        </div>
                                    </div>
                                </div>

								<input type="hidden" name="calendar_id" value="<?smarty $calendar.calendar_id ?>" />
									
                                                                <div class="form-actions">
										<button id="save-calendar" class="btn btn-primary" name="save-calendar" data-complete-text="Changes saved" data-loading-text="saving..." >Save changes</button>
									</div>

							</form>


							</div>


					<?smarty elseif $active == 'settings' ?>
						<div id="user-settings" style="padding-top:20px;padding-left:20px;">
							<legend>Settings</legend>

							<?smarty if !empty($save_settings_error) ?>
							<div style="position:absolute;left:400px;top:60px;color:red;font-weight:bold;">
								<?smarty $save_settings_error ?>
							</div>
							<?smarty /if ?>

							<?smarty if !empty($save_settings_success) ?>
							<div style="position:absolute;left:400px;top:60px;color:green;font-weight:bold;">
								<?smarty $save_settings_success ?>
							</div>
							<?smarty /if ?>


							<form action="<?smarty $smarty.const.FULLCAL_URL ?>/user/settings/?action=save_settings" method="post" class="form-horizontal">

								<div class="control-group">
									<label for="user_settings_default_view" class="control-label" id="user_settings_defaultview_label">Default calendar view </label>
									<div class="controls">
										<!-- month, basicWeek, agendaWeek, basicDay, agendaDay , agendaList-->
										<select name="default_view">
											<option value="month" <?smarty if $settings.default_view == "month" ?>selected="selected"<?smarty /if ?>>month</option>
											<option value="agendaWeek" <?smarty if $settings.default_view == "agendaWeek" ?>selected="selected"<?smarty /if ?>>week</option>
											<option value="agendaDay" <?smarty if $settings.default_view == "agendaDay" ?>selected="selected"<?smarty /if ?>>day</option>
											<option value="agendaList" <?smarty if $settings.default_view == "agendaList" ?>selected="selected"<?smarty /if ?>>list</option>
										</select>
									</div>
								</div>

                                <div class="control-group">
                                            <label for="user_settings_week_view_type" class="control-label" id="user_settings_week_view_type_label">Weekview type </label>
                                            <div class="controls">
                                                    <!-- basicWeek, agendaWeek -->
                                                    <select name="week_view_type">
                                                            <option value="agendaWeek" <?smarty if $settings.week_view_type == "agendaWeek" ?>selected="selected"<?smarty /if ?>>Agenda week</option>
                                                            <option value="basicWeek" <?smarty if $settings.week_view_type == "basicWeek" ?>selected="selected"<?smarty /if ?>>Basic week</option>
                                                    </select>
                                            </div>
                                    </div>
                                <div class="control-group">
                                        <label for="user_settings_day_view_type" class="control-label" id="user_settings_day_view_type_label">Dayview type </label>
                                        <div class="controls">
                                                <!-- basicDay, agendaDay -->
                                                <select name="day_view_type">
                                                        <option value="agendaDay" <?smarty if $settings.day_view_type == "agendaDay" ?>selected="selected"<?smarty /if ?>>Agenda day</option>
                                                        <option value="basicDay" <?smarty if $settings.day_view_type == "basicDay" ?>selected="selected"<?smarty /if ?>>Basic day</option>
                                                </select>
                                        </div>
                                </div>

                                <div class="control-group">
                                        <label for="user_settings_language" class="control-label" id="user_language_label">Language </label>
                                        <div class="controls">
                                                <select name="language">
                                                        <?smarty foreach from=$current_languages name="current_languages" item=item key=key ?>
                                            <option value="<?smarty $key ?>" <?smarty if $settings.language == $key ?>selected="selected" checked="checked"<?smarty /if ?>><?smarty $item ?></option>
                                            <?smarty /foreach ?>
										</select>
									</div>
								</div>

                                <div class="control-group">
									<label for="user_settings_other_language_label_id" class="control-label" id="user_settings_other_language_label">Other language </label>
									<div class="controls">
                                        <input type="text" class="input-xlarge" style="width:30px;" name="other_language" value="<?smarty $settings.other_language ?>" /> Two capital characters (eg. EN, ES, DE) - <strong>Custom lang**.js is required in script folder</strong>
									</div>
								</div>
                                
                                <div class="control-group" id="user_settings_show_am_pm" style="margin-bottom:1px;">
                                        <div class="controls" style="margin-left:20px;padding-bottom:5px;">
                                                <label id="" class="control-label"></label>
                                                <input type="checkbox" name="show_am_pm" <?smarty if $settings.show_am_pm == "on" ?>checked="checked"<?smarty /if ?> />
                                                <span id="user_show_am_pm_checkbox_label" style="padding-top:5px;vertical-align:middle;">Show AM/PM</span>
                                        </div>
                                </div>
                                <div class="control-group" id="user_settings_show_weeknumbers" style="margin-bottom:1px;">
                                        <div class="controls" style="margin-left:20px;padding-bottom:5px;">
                                                <label id="" class="control-label"></label>
                                                <input type="checkbox" name="show_weeknumbers" <?smarty if $settings.show_weeknumbers == "on" ?>checked="checked"<?smarty /if ?> />
                                                <span id="user_show_weeknumbers_checkbox_label" style="padding-top:5px;vertical-align:middle;">Show weeknumbers</span>
                                        </div>
                                </div>
                                <div class="control-group" id="user_settings_show_notallowed_messages" style="margin-bottom:1px;">
                                        <div class="controls" style="margin-left:20px;padding-bottom:5px;">
                                                <label id="" class="control-label"></label>
                                                <input type="checkbox" name="show_notallowed_messages" <?smarty if $settings.show_notallowed_messages == "on" ?>checked="checked"<?smarty /if ?> />
                                                <span id="user_show_notallowed_messages_checkbox_label" style="padding-top:5px;vertical-align:middle;">Show "not allowed" messages</span>
                                        </div>
                                </div>
                                
                                <div class="control-group">
                                        <label for="user_settings_preview_type" class="control-label" id="user_mouseover_popup_label">Mouseover popup </label>
                                        <div class="controls">
                                                <select name="show_view_type">
                                                        <option value="mouseover" <?smarty if $settings.show_view_type == "mouseover" ?>selected="selected"<?smarty /if ?>>Mouseover</option>
                                                        <option value="none" <?smarty if $settings.show_view_type == "none" ?>selected="selected"<?smarty /if ?>>None</option>

                                                </select>
                                        </div>
                                </div>
                                <div class="control-group" id="user_settings_truncate_title" style="margin-bottom:1px;">
                                        <div class="controls" style="margin-left:20px;padding-bottom:5px;">
                                                <label id="" class="control-label"></label>
                                                <input type="checkbox" name="truncate_title" <?smarty if $settings.truncate_title == "on" ?>checked="checked"<?smarty /if ?> />
                                                <span id="user_truncate_title_checkbox_label" style="padding-top:5px;vertical-align:middle;">Truncate title</span>
                                        </div>
                                </div>
                                <div class="control-group">
                                        <label for="user_settings_truncate_length_label_id" class="control-label" id="user_settings_truncate_length_label">Title length </label>
                                        <div class="controls">
                                                <input type="text" class="input-xlarge" style="width:30px;" name="truncate_length" value="<?smarty $settings.truncate_length ?>" /> <span id="user_settings_amount_of_characters_label">Amount of characters</span>
                                        </div>
                                </div>
                                                            
                                <hr />        <h4 style="padding:30px 0 10px 180px;font-weight:bold;" id="admin_settings_pdf_export_label">PDF export:</h4>
                                
                                <div class="control-group" id="user_settings_pdf_table_look" style="margin-bottom:1px;">
                                    <div class="controls" style="margin-left:20px;padding-bottom:5px;">
                                        <label id="" class="control-label"></label>
                                        <input type="checkbox" name="pdf_table_look" <?smarty if $settings.pdf_table_look == "on" ?>checked="checked"<?smarty /if ?> />
                                        <span id="admin_pdf_table_look_checkbox_label" style="padding-top:5px;vertical-align:middle;">Table-look</span>
                                    </div>
                                </div>
                                
                                <!--<div class="control-group" id="user_settings_show_public_and_private_separately" style="margin-bottom:1px;">
                                    <div class="controls" style="margin-left:20px;padding-bottom:5px;">
                                        <label id="" class="control-label"></label>
                                        <input type="checkbox" name="show_public_and_private_separately" <?smarty if $settings.show_public_and_private_separately == "on" ?>checked="checked"<?smarty /if ?> />
                                        <span id="user_show_public_and_private_separately_checkbox_label" style="padding-top:5px;vertical-align:middle;">Show public and private calendarbuttons separately</span>
                                    </div>
                                </div>-->
                                
                                <?smarty if $smarty.const.USERS_CAN_ADD_CALENDARS ?>
                                                                
                                <?smarty /if ?>
                                
                                <!--<h4 style="padding:50px 0 10px 180px;font-weight:normal;">Edit dialog:</h4>
                                
                                <div class="control-group" id="user_settings_show_description_in_edit_dialog" style="margin-bottom:1px;">
                                    <div class="controls" style="margin-left:20px;padding-bottom:5px;">
                                        <label id="" class="control-label"></label>
                                        <input type="checkbox" name="show_description_field" <?smarty if $settings.show_description_field == "on" ?>checked="checked"<?smarty /if ?> />
                                        <span id="user_show_description_in_edit_dialog_checkbox_label" style="padding-top:5px;vertical-align:middle;">Show description field</span>
                                    </div>
                                </div><div class="control-group" id="user_settings_show_location_in_edit_dialog" style="margin-bottom:1px;">
                                    <div class="controls" style="margin-left:20px;padding-bottom:5px;">
                                        <label id="" class="control-label"></label>
                                        <input type="checkbox" name="show_location_field" <?smarty if $settings.show_location_field == "on" ?>checked="checked"<?smarty /if ?> />
                                        <span id="user_show_location_in_edit_dialog_checkbox_label" style="padding-top:5px;vertical-align:middle;">Show location field</span>
                                    </div>
                                </div>
                                <div class="control-group" id="user_settings_show_phone_in_edit_dialog" style="margin-bottom:1px;">
                                    <div class="controls" style="margin-left:20px;padding-bottom:5px;">
                                        <label id="" class="control-label"></label>
                                        <input type="checkbox" name="show_phone_field" <?smarty if $settings.show_phone_field == "on" ?>checked="checked"<?smarty /if ?> />
                                        <span id="user_show_phone_in_edit_dialog_checkbox_label" style="padding-top:5px;vertical-align:middle;">Show phone field</span>
                                    </div>
                                </div>
                                <div class="control-group" id="user_settings_show_url_in_edit_dialog" style="margin-bottom:1px;">
                                    <div class="controls" style="margin-left:20px;padding-bottom:5px;">
                                        <label id="" class="control-label"></label>
                                        <input type="checkbox" name="show_url_field" <?smarty if $settings.show_url_field == "on" ?>checked="checked"<?smarty /if ?> />
                                        <span id="user_show_url_in_edit_dialog_checkbox_label" style="padding-top:5px;vertical-align:middle;">Show url field</span>
                                    </div>
                                </div>-->
                                
								<input type="hidden" name="user_id" value="<?smarty $user_id ?>" />
								<div class="form-actions">
									<button id="user-save-settings" class="btn btn-primary" name="save-settings" data-complete-text="Changes saved" data-loading-text="saving..." type="submit">Save changes</button>
								</div>
							</form>
						</div>

					<?smarty /if ?>

				</div>
			</div>
		</div>
	</div>

    <script type="text/javascript">
		
        $(document).ready(function() {
            
            MyCalendar.datePickerDateFormat	= '<?smarty $smarty.const.DATEPICKER_DATEFORMAT ?>';
        MyCalendar.last_dditem = 1000000;
        MyCalendar.dditem_string = '';
		
	var current_user = '<?smarty if isset($user) && isset($user.user_id) ?><?smarty $user.user_id ?><?smarty /if ?>';
	
        
        $('#hourcalc_datepicker_startdate').val('<?smarty $startdate ?>');
        $('#hourcalc_datepicker_enddate').val('<?smarty $enddate ?>');
               
        
        $('#userpanel_dates_refresh_button').click(function(t) {			
            var startdate = $('#hourcalc_datepicker_startdate').val();
                var enddate = $('#hourcalc_datepicker_enddate').val();
                var selected_cal = $('#calendar_selectbox').val();
                location.href = '<?smarty $smarty.const.FULLCAL_URL ?>/user/lists/?cid='+selected_cal+'&st='+startdate+'&end='+enddate;
			});
            
             $('#userpanel_user_dates_refresh_button').click(function(t) {
		var startdate = $('#hourcalc_datepicker_startdate').val();
                var enddate = $('#hourcalc_datepicker_enddate').val();
                var selected_cal = $('#calendar_selectbox').val();
                location.href = '<?smarty $smarty.const.FULLCAL_URL ?>/user/lists/?action=get_list&uid='+current_user+'&cid='+selected_cal+'&st='+startdate+'&end='+enddate;
			});
            
			
            
            $('#active_period_datepicker_startdate').val('<?smarty $calendar.cal_startdate ?>');
            $('#active_period_datepicker_enddate').val('<?smarty $calendar.cal_enddate ?>');
            
            $('#alterable_period_datepicker_startdate').val('<?smarty $calendar.alterable_startdate ?>');
            $('#alterable_period_datepicker_enddate').val('<?smarty $calendar.alterable_enddate ?>');
            
            $('input[name="active"]').change(function(t) {
                if($(this).val() == 'period') {
                    $('#active_period_datepicker_startdate').prop('disabled', false);
                    $('#active_period_datepicker_enddate').prop('disabled', false);
                } else {
                    $('#active_period_datepicker_startdate').prop('disabled', true);
                    $('#active_period_datepicker_enddate').prop('disabled', true);
                }
                
            });
            
            $('.simple_endtime_label').html(Lang.Popup.SimpleEndTimeLabel);
            $('.simple_starttime_label').html(Lang.Popup.SimpleStartTimeLabel );

            $('#user_calendar_origin').change(function(t) {
                if($(this).val() == 'exchange') {
                    // show groups combo
                    $('#exchange_username_field').show();
                    $('#exchange_password_field').show();
                    $('#exchange_token_field').show();
                } else {
                    $('#exchange_username_field').hide();
                    $('#exchange_password_field').hide();
                    $('#exchange_token_field').hide();
                }
            });
            
            $('#exchange_extra_secure_checkbox').click(function(t,b) {
                if($(this).is(':checked')) {
                    $('#exchange_token').removeAttr('disabled');
                } else {
                    $('#exchange_token').prop('disabled', true);
                }
            });
            
            $('#user_settings_share_type').change(function(t) {
                if($(this).val() == 'private') {
                    // disable checkboxes
                    $('#user_settings_can_add').prop('disabled', true);
                    $('#user_settings_can_edit').prop('disabled', true);
                    $('#user_settings_can_delete').prop('disabled', true);
                    $('#user_settings_can_change_color').prop('disabled', true);
                    
                    $('#user_settings_can_add').attr('checked', false);
                    $('#user_settings_can_edit').attr('checked', false);
                    $('#user_settings_can_delete').attr('checked', false);
                    $('#user_settings_can_change_color').attr('checked', false);
                } else {
                    // enable checkboxes
                    $('#user_settings_can_add').prop('disabled', false);
                    $('#user_settings_can_edit').prop('disabled', false);
                    $('#user_settings_can_delete').prop('disabled', false);
                    $('#user_settings_can_change_color').prop('disabled', false);
                }
            });
		});
        
        
        
        var arr_palette = [
                ["#000","#444","#666","#999","#ccc","#eee","#f3f3f3","#fff"],
                ["#f00","#f90","#ff0","#0f0","#0ff","#00f","#90f","#f0f"],
                ["#f4cccc","#fce5cd","#fff2cc","#d9ead3","#d0e0e3","#cfe2f3","#d9d2e9","#ead1dc"],
                ["#ea9999","#f9cb9c","#ffe599","#b6d7a8","#a2c4c9","#9fc5e8","#b4a7d6","#d5a6bd"],
                ["#e06666","#f6b26b","#ffd966","#93c47d","#76a5af","#6fa8dc","#8e7cc3","#c27ba0"],
                ["#c00","#e69138","#f1c232","#6aa84f","#45818e","#3d85c6","#674ea7","#a64d79"],
                ["#900","#b45f06","#bf9000","#38761d","#134f5c","#0b5394","#351c75","#741b47"],
                ["#600","#783f04","#7f6000","#274e13","#0c343d","#073763","#20124d","#4c1130"]
            ];
            
            $("#user-spectrum-colorpicker").spectrum({
                showPaletteOnly: true,
                    showPalette:true,
                color: 'blanchedalmond',
                palette: arr_palette,
                change: function(color) {
                         // #ff0000
                        $("#user-spectrum-colorpicker").val(color.toHexString());
                        $("#user-spectrum-colorpicker").spectrum('hide');
                    }
            });
            $("#user-spectrum-colorpicker").val('<?smarty $calendar.calendar_color ?>');
            $("#user-spectrum-colorpicker").spectrum('set', '<?smarty $calendar.calendar_color ?>');
		
        // dditems colors
            <?smarty foreach from=$calendar.dditems item=item ?>
                $("#user-spectrum-colorpicker-dditem-<?smarty $item.dditem_id ?>").spectrum({
                    showPaletteOnly: true,
                        showPalette:true,
                    color: 'blanchedalmond',
                    palette: arr_palette,
                    change: function(color) {
                             // #ff0000
                            $("#user-spectrum-colorpicker-dditem-<?smarty $item.dditem_id ?>").val(color.toHexString());
                            $("#user-spectrum-colorpicker-dditem-<?smarty $item.dditem_id ?>").spectrum('hide');

                    }
                });
                $("#user-spectrum-colorpicker-dditem-<?smarty $item.dditem_id ?>").val('<?smarty if $item.color !== null && !empty($item.color) ?><?smarty $item.color ?><?smarty else ?><?smarty $calendar.calendar_color ?><?smarty /if ?>');
		$("#user-spectrum-colorpicker-dditem-<?smarty $item.dditem_id ?>").spectrum('set', '<?smarty if $item.color !== null && !empty($item.color) ?><?smarty $item.color ?><?smarty else ?><?smarty $calendar.calendar_color ?><?smarty /if ?>');
            
                 <?smarty if !empty($item.starttime) ?>
                    var now = new Date();
                    var startdate     = new Date(now.format('mm/dd/yyyy')+ ' <?smarty $item.starttime ?>');
                    var enddate     = new Date(now.format('mm/dd/yyyy')+ ' <?smarty $item.endtime ?>');

                    if(MyCalendar.showAMPM) {
                        $('#user_dditem_timepicker_starttime-<?smarty $item.dditem_id ?>').val(dateFormat(startdate,'hh:00 TT'));
                        $('#user_dditem_timepicker_endtime-<?smarty $item.dditem_id ?>').val(dateFormat(enddate,'hh:00 TT'));
                    } else {
                        $('#user_dditem_timepicker_starttime-<?smarty $item.dditem_id ?>').val('<?smarty $item.starttime ?>');
                        $('#user_dditem_timepicker_endtime-<?smarty $item.dditem_id ?>').val('<?smarty $item.endtime ?>');
                    }
                <?smarty /if ?>
                
                $('#user_dditem_timepicker_starttime-<?smarty $item.dditem_id ?>').timepicker({
                    zindex:9999,
                    interval: MyCalendar.timePickerMinuteInterval,
                    timeFormat: MyCalendar.showAMPM ? 'hh:mm p' : 'HH:mm'
                });
                $('#user_dditem_timepicker_endtime-<?smarty $item.dditem_id ?>').timepicker({
                    zindex:9999,
                    interval: MyCalendar.timePickerMinuteInterval,
                    timeFormat: MyCalendar.showAMPM ? 'hh:mm p' : 'HH:mm'
                });

            
            <?smarty /foreach ?>
            
            
            
            $('#add_dditem').click(function(t) {
                MyCalendar.last_dditem ++;
                $('.table').append('<tr>'+
                                        '<td style="width:50px;border:none;padding:2px;"><input type="text" style="width:150px;" name="title'+MyCalendar.last_dditem+'" class="user-dditem-title" id="user-spectrum-colorpicker-dditem-title-'+MyCalendar.last_dditem+'" value="" /></td>'+
                                        '<td style="width:50px;border:none;padding:2px;"><input type="text" style="width:150px;" name="info'+MyCalendar.last_dditem+'" class="user-dditem-info" id="user-dditem-info-'+MyCalendar.last_dditem+'" value="" /></td>'+
                                        '<td style="padding:2px;border:none;"><input type="text" class="user_dditem_timepicker_starttime" id="user_dditem_timepicker_starttime-'+MyCalendar.last_dditem+'" name="starttime'+MyCalendar.last_dditem+'" style="font-size:13px;width: 80px;"></td>'+
                                        '<td style="padding:2px;border:none;"><input type="text" class="user_dditem_timepicker_endtime" id="user_dditem_timepicker_endtime-'+MyCalendar.last_dditem+'" name="endtime'+MyCalendar.last_dditem+'" style="font-size:13px;width: 80px;"></td>'+
                                        '<td style="padding:2px;border:none;"><input type="checkbox" class="user_dditem_timepicker_nightshift" id="user-dditem-nightshift-'+MyCalendar.last_dditem+'" name="nightshift'+MyCalendar.last_dditem+'" style="font-size:13px;width: 80px;"></td>'+
                                        '<td style="width:50px;border:none;padding:2px;"><input type="text" class="input-xlarge user-spectrum-colorpicker-dditems" id="user-spectrum-colorpicker-dditem-'+MyCalendar.last_dditem+'" name="dditem_color[]" value="<?smarty $calendar.calendar_color ?>" data-title="" data-number="'+MyCalendar.last_dditem+'"></td>'+
                                 '</tr>');
                
            
                $("#user-spectrum-colorpicker-dditem-"+MyCalendar.last_dditem).spectrum({
                    showPaletteOnly: true,
                    showPalette:true,
                    color: 'blanchedalmond',
                    palette: arr_palette,
                    change: function(color) {
                         // #ff0000
                        $("#user-spectrum-colorpicker-dditem-"+MyCalendar.last_dditem).val(color.toHexString());
                        $("#user-spectrum-colorpicker-dditem-"+MyCalendar.last_dditem).spectrum('hide');

                    }
                });
                $("#user-spectrum-colorpicker-dditem-"+MyCalendar.last_dditem).val('<?smarty $calendar.calendar_color ?>');
                $("#user-spectrum-colorpicker-dditem-"+MyCalendar.last_dditem).spectrum('set', '<?smarty $calendar.calendar_color ?>');
           
            });
            
            var getStarttime = function(number) {
                var str_time_start = '';
                var starttime = $('#user_dditem_timepicker_starttime-'+number).val();
                if(starttime !== '') {
                    var str_date_start_tmp = new Date().format('mm/dd/yyyy') + ' ' + starttime;
                    var str_time_start = new Date(str_date_start_tmp).format('HH:MM:00');
                }
                return str_time_start;
            };
            var getEndtime = function(number) {
                var str_time_end = '';
                var endtime = $('#user_dditem_timepicker_endtime-'+number).val();
                if(endtime !== '') {
                    var str_date_end_tmp = new Date().format('mm/dd/yyyy') + ' ' + endtime;
                    var str_time_end = new Date(str_date_end_tmp).format('HH:MM:00');
                }
                return str_time_end;
            };

            var setStringDDItems = function() {
                MyCalendar.dditem_string = '';
                $('.user-spectrum-colorpicker-dditems').each(function(index,item) {
                    var number = $(item).data('number');       
                    var starttime = getStarttime(number);
                    var endtime = getEndtime(number);

                    MyCalendar.dditem_string += number + '|' + $('#user-spectrum-colorpicker-dditem-title-'+number).val() + '|' + $('#user-dditem-info-'+number).val() + '|' + starttime + '|' + endtime + '|' + $('#user-dditem-nightshift-'+number).is(':checked')+ '|' + $(item).val() + ',';
                
                });
                $('#calendar_dditems').val(MyCalendar.dditem_string);
            };
        
            $('#save-calendar').click(function() {
                setStringDDItems();
                // submit goes automatically
            //    $('#calendar_save_form').submit();
            });
        
            <?smarty if $calendar.share_type == "public" ?>
                $('#admin_settings_share_type').val('<?smarty $calendar.share_type ?>');
            <?smarty /if ?>

            <?smarty if $calendar.origin == "default" ?>
                $('#user_calendar_origin').val('<?smarty $calendar.origin ?>');
            <?smarty /if ?>
            
            $('.title_label').text(Lang.Calendar.LabelTitle);
            $('.info_label').text(Lang.Calendar.LabelInfo);
            $('.color_label').text(Lang.Label.Color);
            $('.active_label').html(Lang.Calendar.LabelActive);
            $('.name_label').html(Lang.Calendar.LabelName);
            $('.active_label').html(Lang.Calendar.LabelActive);
            $('.count_users_label').html(Lang.Calendar.LabelCountUsers);
            $('.owner_label').html(Lang.Calendar.LabelOwner);
            $('.origin_label').html(Lang.Calendar.LabelOrigin);
            $('.dditems_label').html(Lang.Calendar.LabelDDItems);
            $('.type_label').html(Lang.Calendar.LabelType);
            $('.add_label').html(Lang.Calendar.LabelAdd);
            $('.edit_label').html(Lang.Calendar.LabelEdit);
            $('.delete_label').html(Lang.Calendar.LabelDelete);
            $('.changecolor_label').html(Lang.Calendar.LabelChangeColor);
            $('.initialshow_label').html(Lang.Calendar.LabelInitialShow);
            $('#admin_show_teammember_in_edit_dialog_checkbox_label').html(Lang.Calendar.LabelShowTeamMember);
            
            $('#admin_show_description_in_edit_dialog_checkbox_label').html(Lang.Settings.LabelShowDescription);
             
            $('#user_settings_hour_calculation_legend').html(Lang.Hourcalculation.legend);
            $('#user_settings_user_hour_calculation_legend').html(Lang.Hourcalculation.legendOfUser);
            $('#user_settings_info_text').html(Lang.Settings.Infotext);
            $('#user_language_label').html(Lang.Settings.LabelLanguage);
            $('#user_settings_defaultview_label').html(Lang.Settings.DefaultView);
            $('#user_settings_week_view_type_label').html(Lang.Settings.LabelWeekViewType);
            $('#user_settings_day_view_type_label').html(Lang.Settings.LabelDayViewType);
            $('#user_settings_other_language_label').html(Lang.Settings.LabelOtherLanguage);
            $('#user_show_am_pm_checkbox_label').html(Lang.Settings.LabelShowAmPm);
            $('#user_show_weeknumbers_checkbox_label').html(Lang.Settings.LabelShowWeeknumbers);
            $('#user_show_notallowed_messages_checkbox_label').html(Lang.Settings.LabelShowNotAllowedMessages);
            $('#user_mouseover_popup_label').html(Lang.Settings.LabelMouseoverPopup);
            $('#user_truncate_title_checkbox_label').html(Lang.Settings.LabelTruncateTitle);
            $('#user_settings_truncate_length_label').html(Lang.Settings.LabelTitleLength);
            $('#user_settings_edit_dialog_label').html(Lang.Settings.LabelEditDialog);
            $('#user_settings_two_capitals_label').html(Lang.Settings.LabelTwoCapitals);
            $('#user_settings_amount_of_characters_label').html(Lang.Settings.LabelAmountOfCharacters);
            $('#user_settings_colorpicker_type_label').html(Lang.Settings.LabelColorPickerType);
            $('#user_settings_timepicker_type_label').html(Lang.Settings.LabelTimePickerType);
            
           $('.others_can_label').html(Lang.Calendar.LabelOthersCan);
    </script>
  </body>
</html>