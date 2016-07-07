<?php /* Smarty version 2.6.18, created on 2016-05-27 19:31:47
         compiled from /Applications/XAMPP/xamppfiles/htdocs/employee-work-schedule/view/admin_panel.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', '/Applications/XAMPP/xamppfiles/htdocs/employee-work-schedule/view/admin_panel.tpl', 340, false),array('modifier', 'count', '/Applications/XAMPP/xamppfiles/htdocs/employee-work-schedule/view/admin_panel.tpl', 569, false),array('modifier', 'floor', '/Applications/XAMPP/xamppfiles/htdocs/employee-work-schedule/view/admin_panel.tpl', 578, false),array('function', 'math', '/Applications/XAMPP/xamppfiles/htdocs/employee-work-schedule/view/admin_panel.tpl', 569, false),)), $this); ?>
<!DOCTYPE html>
<html lang="<?php if (isset ( $this->_tpl_vars['language'] ) && ! empty ( $this->_tpl_vars['language'] )): ?><?php echo $this->_tpl_vars['language']; ?>
<?php else: ?><?php echo @LANGUAGE; ?>
<?php endif; ?>">
    <head>
        <meta charset="utf-8">
        <title><?php echo @CALENDAR_TITLE; ?>
</title>

        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="<?php echo @CALENDAR_TITLE; ?>
">
        <meta name="author" content="">

        <!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
        <!--[if lt IE 9]>
                <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->

        <link href="<?php echo @EXTERNAL_URL; ?>
/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="<?php echo @FULLCAL_URL; ?>
/style/custom_dashboard_styles.css" rel="stylesheet">

        <link rel='stylesheet' type='text/css' href='<?php echo @EXTERNAL_URL; ?>
/jquery/jqueryui/1.8.17/jquery-ui.css' />
        <link rel='stylesheet' type='text/css' href='<?php echo @EXTERNAL_URL; ?>
/fullcalendar-1.6.4/fullcalendar/fullcalendar.print.css' media='print' />

        <link rel="shortcut icon" href="/favicon.ico">

        <script src="<?php echo @EXTERNAL_URL; ?>
/jquery/jquery.1.11.1.min.js"></script>
        <script src="<?php echo @EXTERNAL_URL; ?>
/jquery/jquery-ui.1.11.1.min.js" type="text/javascript" charset="utf-8"></script>

        <script type="text/javascript" src="<?php echo @EXTERNAL_URL; ?>
/jquery/multiselect/js/ui.multiselect.js"></script>
        <script type="text/javascript" src="<?php echo @EXTERNAL_URL; ?>
/jquery/multiselect/js/plugins/tmpl/jquery.tmpl.1.1.1.js"></script>
        
        
        <link rel='stylesheet' href='<?php echo @EXTERNAL_URL; ?>
/jquery/multiselect/css/ui.multiselect.css' />
		
		<script src='<?php echo @EXTERNAL_URL; ?>
/bgrins-spectrum/spectrum.js'></script>
		<link rel='stylesheet' href='<?php echo @EXTERNAL_URL; ?>
/bgrins-spectrum/spectrum.css' />
		
		<link rel='stylesheet' type='text/css' href='<?php echo @EXTERNAL_URL; ?>
/jquery-timepicker-1.3.2/jquery.timepicker.css'  />
        <script type='text/javascript' src='<?php echo @EXTERNAL_URL; ?>
/jquery-timepicker-1.3.2/jquery.timepicker.min.js'></script>
		
        <script type='text/javascript' src='<?php echo @EXTERNAL_URL; ?>
/dateformat.js'></script>
        
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

			#admin-colpick-colorpicker {
				border:0;
				width:70px;
				border-right:20px solid green;
				border-image: none;
    			border-style: solid;
    			border-width: 1px 20px 1px 1px;
			}

			
            
            fieldset {
                display: block;
                margin-left: 2px;
                margin-right: 2px;
                padding-top: 0.35em;
                padding-bottom: 0.625em;
                padding-left: 0.75em;
                padding-right: 0.75em;
                border: 2px groove (internal value);
            } 
            
            .dashboard_btn {
                padding:4px;
                border:1px solid #D4D4D4;
                border-radius: 3px;
                cursor: pointer;
                text-decoration: none;
            }
            
            .dashboard_block {
                border: 1px solid #DCDCDC;
                background-color: #f9f9f9;
                padding: 15px;
                border-radius: 15px;
            }
    
            h4 {
                margin: 5px 0;
                padding-top: 10px;
            }
            
            .form-horizontal .control-label {
                width: 190px;
            }
            
            .form-horizontal .controls  {
                margin-left: 210px;
            }
            
            .ui-dialog { z-index: 9999 !important ;}
            
            .ui-multiselect {
                width: auto !important;
                height: 220px;
                border: none;
               
            }
            #select_user .selected, #select_user .available {
                width: auto !important;
                min-width: 190px;
                
            }
            .list {
                height:100% !important;
            }
           
            .connected-list {
                height: 190px !important;
            }
            .ui-dialog .ui-dialog-content {
                padding: 0.5em 0.1em;
                
            }
            
            .ui-dialog-titlebar-close {
                display: none !important;
            }
            
            .ui-multiselect li {
                font-size: 12px;
            }
            
            .sp-replacer {
                width: 50px;
            }
            
            .user-import-sjabloon-block .required {
                background-color: #F3F3F3;
            }
            
            .user-import-sjabloon-block td {
                padding-left:5px;
                padding-right:5px;
                border:1px solid lightgray;
            }
            
            #file_upload_form td {
                height: 30px;
            }
            
            #admin_settings_dropdowns_are_linked td {
                border: 1px solid #AFAFAF;
                text-align: center;
            }
            img {
                max-width: 16px !important;
            }
            
		</style>
		<script type='text/javascript'>
			MyCalendar = {};
			MyCalendar.sendActivationMail			= <?php if (@SEND_ACTIVATION_MAIL): ?>true<?php else: ?>false<?php endif; ?>;
            MyCalendar.FCfirstDay                   = '<?php echo @FIRSTDAY_OF_WEEK; ?>
';
    
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
                $('#print_btn2').click(function() {
                    window.print();
                });
                
                $('#lists_to_excel_btn').click(function(t) {
                    tableToExcel('lists_table', 'Hour calculation');
                });
                $('#list_to_excel_btn').click(function(t) {
                    tableToExcel('list_table', 'Hour calculation of');
                });
                
                $('#team_member_field_id').click(function(t) {
                    if($(this).is(':checked')) {
                        $('#add_team_member_to_title').show();
                        $('#event_title_additions_hr').show();
                        $('#event_title_additions_h4').show();
                    } else {
                        $('#add_team_member_to_title').hide();
                    }
                });
                
                $('#custom_dropdown1_field_id').click(function(t) {
                    if($(this).is(':checked')) {
                        $('#add_custom_dropdown1_to_title').show();
                        $('#event_title_additions_hr').show();
                        $('#event_title_additions_h4').show();
                    } else {
                        $('#add_custom_dropdown1_to_title').hide();
                    }
                });
                
                $('#custom_dropdown2_field_id').click(function(t) {
                    if($(this).is(':checked')) {
                        $('#add_custom_dropdown2_to_title').show();
                        $('#event_title_additions_hr').show();
                        $('#event_title_additions_h4').show();
                    } else {
                        $('#add_custom_dropdown2_to_title').hide();
                    }
                });
            });
            
            <?php if ($this->_tpl_vars['active'] == 'group'): ?>
                if(MyCalendar === undefined) {
                    MyCalendar = {};
                }
                MyCalendar.cntGroupUsers = <?php if ($this->_tpl_vars['group']['cnt_users'] !== null && $this->_tpl_vars['group']['cnt_users'] !== ''): ?><?php echo $this->_tpl_vars['group']['cnt_users']; ?>
<?php else: ?>0<?php endif; ?>;
            <?php endif; ?>
		</script>
        
        <script type='text/javascript' src='<?php echo @FULLCAL_URL; ?>
/script/listeners.js'></script>
		<script type='text/javascript' src='<?php echo @FULLCAL_URL; ?>
/script/lang<?php echo $this->_tpl_vars['language']; ?>
.js'></script>

	</head>

	<body>

	<div class="navbar navbar-fixed-top">
            <div class="navbar">
                <div class="navbar-inner">
                    <div class="container">

                        <a class="brand" href="<?php echo @FULLCAL_URL; ?>
/admin">Admin Dashboard</a>
                        <a style="float:left;padding-top:17px;color:#777777;text-decoration: none;" href="<?php echo @FULLCAL_URL; ?>
">
                        <span class="dashboard_btn"><i class="icon-calendar"></i> Calendar</span></a>
                        <span style="float:right;padding-top:17px;">Logged in: <?php echo $this->_tpl_vars['name']; ?>
</span>
                        <span style="float:right;margin-right: 10px;padding-top: 17px;" class="button-fc-like" id="print_btn2">
                            <span class="dashboard_btn">
                                <i class="icon-print"></i> Print
                            </span>
                        </span>
                   		
                    </div>
                </div>
            </div>
	</div>


	<div class="container">
		<div class="row">

			<div class="span12">
				<div class="tabbable tabs-left">

					<ul class="nav nav-tabs">
						<?php if ($this->_tpl_vars['is_super_admin']): ?>
                        
                        <?php else: ?>
                        
                        <?php endif; ?>
                        
                        <li <?php if ($this->_tpl_vars['active'] == 'users'): ?>class="active"<?php endif; ?>><a href="<?php echo @FULLCAL_URL; ?>
/admin/users"><i class="icon-user"></i> <?php if ($this->_tpl_vars['is_super_admin']): ?><span id="admin_admins_menu">Admins</span> /<br /><span id="admin_users_menu">Users</span><?php else: ?><span id="admin_users_menu">Users</span><?php endif; ?></a></li>
                        
                        <?php if ($this->_tpl_vars['active'] == 'new_user'): ?><li class="active"><a href="#new_user" data-toggle="tab"><i class="icon-user"></i> <span id="admin_add_user_menu">Add user</span></a></li><?php endif; ?>
                        <?php if ($this->_tpl_vars['active'] == 'quick_new_user'): ?><li class="active"><a href="#quick_new_user" data-toggle="tab"><i class="icon-user"></i> <span id="<?php if ($this->_tpl_vars['is_super_admin']): ?>admin_quick_add_admin_menu<?php else: ?>admin_quick_add_user_menu<?php endif; ?>">Quick add user</span></a></li><?php endif; ?>
                        <?php if ($this->_tpl_vars['active'] == 'import_users'): ?><li class="active"><a href="#import_users" data-toggle="tab"><i class="icon-user"></i> <span id="admin_import_users_menu">Import users</span></a></li><?php endif; ?>
                        <?php if ($this->_tpl_vars['active'] == 'availability'): ?><li class="active"><a href="#" data-toggle="tab"><i class="icon-list"></i> <span id="admin_add_availability_menu">Add availability</span></a></li><?php endif; ?>
                        
                        <?php if ($this->_tpl_vars['active'] == 'profile'): ?><li class="active"><a href="#profile" data-toggle="tab"><i class="icon-user"></i> Profile</a></li><?php endif; ?>
						
                        <?php if ($this->_tpl_vars['is_admin']): ?>
                        <li <?php if ($this->_tpl_vars['active'] == 'groups'): ?>class="active"<?php endif; ?>><a href="<?php echo @FULLCAL_URL; ?>
/admin/groups"><img src="<?php echo @FULLCAL_URL; ?>
/images/group.png" style="position:relative;left:-2px;" /> <span style="position:relative;left:-2px;" class="<?php if ($this->_tpl_vars['is_super_admin']): ?>groups_lable<?php elseif ($this->_tpl_vars['is_admin']): ?>my_groups_lable<?php endif; ?>">Groups</span></a></li>
						<?php if ($this->_tpl_vars['active'] == 'group'): ?><li class="active"><a href="#group" data-toggle="tab"><img src="<?php echo @FULLCAL_URL; ?>
/images/group.png" style="position:relative;left:-2px;" /> <?php if (isset ( $_GET['action'] ) && $_GET['action'] == 'new_group'): ?>Add group<?php else: ?>Edit group<?php endif; ?></a></li><?php endif; ?>
                        <?php endif; ?>
                        
                        <?php if ($this->_tpl_vars['active'] == 'new_group'): ?><li class="active"><a href="#new_group" data-toggle="tab"><img src="<?php echo @FULLCAL_URL; ?>
/images/group.png" style="position:relative;left:-2px;" /> <span style="position:relative;left:-2px;" id="admin_add_group_menu">Add group</span></a></li><?php endif; ?>
                        
                        <li <?php if ($this->_tpl_vars['active'] == 'calendars'): ?>class="active"<?php endif; ?>><a href="<?php echo @FULLCAL_URL; ?>
/admin/calendars"><i class="icon-list"></i> <span class="<?php if ($this->_tpl_vars['is_super_admin']): ?>admin_calendars_menu<?php elseif ($this->_tpl_vars['is_admin']): ?>admin_my_calendars_menu<?php endif; ?>">Calendars</span></a></li>
						<?php if ($this->_tpl_vars['active'] == 'calendar'): ?><li class="active"><a href="#calendar" data-toggle="tab"><i class="icon-calendar"></i> <?php if (isset ( $_GET['action'] ) && $_GET['action'] == 'new_calendar'): ?>Add calendar<?php else: ?>Edit calendar<?php endif; ?></a></li><?php endif; ?>
                         
                        <?php if ($this->_tpl_vars['is_admin'] && ! $this->_tpl_vars['is_super_admin']): ?>
                        <li <?php if ($this->_tpl_vars['active'] == 'lists'): ?>class="active"<?php endif; ?>><a href="<?php echo @FULLCAL_URL; ?>
/admin/lists"><i class="icon-time"></i> <span id="admin_hour_calculation_menu">Hour Calculation</span></a></li>
						<?php endif; ?>
                        
                        <?php if ($this->_tpl_vars['is_admin'] && ! $this->_tpl_vars['is_super_admin']): ?>
                        <li <?php if ($this->_tpl_vars['active'] == 'settings'): ?>class="active"<?php endif; ?>><a href="<?php echo @FULLCAL_URL; ?>
/<?php if ($this->_tpl_vars['is_user']): ?>user/?action=get_settings<?php else: ?>admin/settings<?php endif; ?>"><i class="icon-cog"></i> <span id="admin_settings_menu">Settings</span></a></li>
                        <?php endif; ?>
                        
                        
                        <li><a href="<?php echo @FULLCAL_URL; ?>
?action=logoff"><i class="icon-close"></i><span id="menu_logout">Log out</span></a></li>
                    </ul>

					<div class="tab-content">

					<?php if ($this->_tpl_vars['active'] == 'admin'): ?>
						<div id="admin-current-events"  style="padding-top:20px;padding-left:20px;">
							<?php if (! empty ( $this->_tpl_vars['current_events'] )): ?>
                                <h4>Current events</h4>
                                <div class="dashboard_block">

                                    <?php $_from = $this->_tpl_vars['current_events']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['event']):
?>
                                        <span style="font-weight:bold;<?php if (! empty ( $this->_tpl_vars['event']['calendar_color'] )): ?>color: <?php echo $this->_tpl_vars['event']['calendar_color']; ?>
;<?php endif; ?>"><?php echo $this->_tpl_vars['event']['name']; ?>
: &nbsp;</span> 
                                        <?php echo $this->_tpl_vars['event']['title']; ?>
 </span> - 
                                        <?php if ($this->_tpl_vars['event']['allDay']): ?>allDay<?php endif; ?>
                                        <?php if (! $this->_tpl_vars['event']['end_is_today']): ?>until <?php echo $this->_tpl_vars['event']['date_end']; ?>
 
                                            <?php if (! $this->_tpl_vars['event']['allDay']): ?> <?php echo $this->_tpl_vars['event']['time_end']; ?>
<?php endif; ?>
                                        <?php else: ?>
                                            <?php if (! $this->_tpl_vars['event']['allDay']): ?>until  <?php echo $this->_tpl_vars['event']['time_end']; ?>
<?php endif; ?>
                                        <?php endif; ?> <br />
                                    <?php endforeach; endif; unset($_from); ?>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (! empty ( $this->_tpl_vars['last_added_events'] )): ?>
                                <h4>Last added events</h4>
                                <div class="dashboard_block">

                                    <?php $_from = $this->_tpl_vars['last_added_events']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['event']):
?>
                                    <span style="font-weight:bold;<?php if (! empty ( $this->_tpl_vars['event']['calendar_color'] )): ?>color: <?php echo $this->_tpl_vars['event']['calendar_color']; ?>
;<?php endif; ?>"><?php echo $this->_tpl_vars['event']['name']; ?>
: &nbsp;</span> 
                                        <?php echo $this->_tpl_vars['event']['title']; ?>
 
                                        
                                         
                                        <span style="float:right;color:gray;">(Added on <?php if (@SHOW_AM_PM): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['event']['create_date'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%m-%d-%Y %r") : smarty_modifier_date_format($_tmp, "%m-%d-%Y %r")); ?>
<?php else: ?><?php echo ((is_array($_tmp=$this->_tpl_vars['event']['create_date'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%d-%m-%Y %R") : smarty_modifier_date_format($_tmp, "%d-%m-%Y %R")); ?>
<?php endif; ?>)</span>
                                        <br />
                                    <?php endforeach; endif; unset($_from); ?>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (! empty ( $this->_tpl_vars['last_loggedin_users'] )): ?>
                                <h4>Last logged in users</h4>
                                <div class="dashboard_block">

                                    <?php $_from = $this->_tpl_vars['last_loggedin_users']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['user']):
?>
                                    <span style="font-weight:bold;"><?php echo $this->_tpl_vars['user']['firstname']; ?>
<?php if (! empty ( $this->_tpl_vars['user']['infix'] )): ?> <?php echo $this->_tpl_vars['user']['infix']; ?>
<?php endif; ?> <?php echo $this->_tpl_vars['user']['lastname']; ?>
&nbsp;</span> 
                                        
                                        <span style="float:right;color:gray;">(<?php if (@SHOW_AM_PM): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['user']['date'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%m-%d-%Y %r") : smarty_modifier_date_format($_tmp, "%m-%d-%Y %r")); ?>
<?php else: ?><?php echo ((is_array($_tmp=$this->_tpl_vars['user']['date'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%d-%m-%Y %R") : smarty_modifier_date_format($_tmp, "%d-%m-%Y %R")); ?>
<?php endif; ?>)</span>
                                        <br />
                                    <?php endforeach; endif; unset($_from); ?>
                                </div>
                            <?php endif; ?>
                            
						</div>
                    

                            <?php elseif ($this->_tpl_vars['active'] == 'users'): ?>
                            <div id="admin-users"  style="padding-top:20px;padding-left:20px;">
                                    <?php if (! empty ( $this->_tpl_vars['error'] )): ?>
                                    <div style="position:absolute;left:400px;top:60px;color:red;font-weight:bold;">
                                            <?php echo $this->_tpl_vars['error']; ?>

                                    </div>
                                    <?php endif; ?>

                                    <?php if (! empty ( $this->_tpl_vars['msg'] )): ?>
                                    <div style="position:absolute;left:400px;top:60px;color:green;font-weight:bold;">
                                            <?php echo $this->_tpl_vars['msg']; ?>

                                    </div>
                                    <?php endif; ?>

                                <?php if ($this->_tpl_vars['is_super_admin']): ?>
                                    <form style="float:right;" id="settings-form" action="<?php echo @FULLCAL_URL; ?>
/admin/users/?action=new_admin" method="post" class="form-horizontal">
                                        <?php if (isset ( $_SESSION['add_user_error'] )): ?>
                                        <div style="position:absolute;left:400px;color:red;font-weight:bold;">
                                            <?php echo $_SESSION['add_user_error']; ?>

                                        </div>
                                        <?php endif; ?>

                                        <div>
                                            <button id="add-admin-btn" class="btn btn-primary" name="add-user" data-complete-text="Changes saved" data-loading-text="saving..." type="submit">Add admin</button>
                                        </div>
                                    </form>
                                    <form style="float:right;padding-right:5px;" id="settings-quick-add-user-form" action="<?php echo @FULLCAL_URL; ?>
/admin/users/?action=quick_new_admin" method="post" class="form-horizontal">

                                        <div>
                                            <button id="quick_add-admin-btn" class="btn btn-primary" name="quick-add-user" data-complete-text="Changes saved" data-loading-text="saving..." type="submit">Quick add admin</button>
                                        </div>
                                    </form>
                                <?php else: ?>
                                    <form style="float:right;padding-right:5px;" id="users-import-form" action="<?php echo @FULLCAL_URL; ?>
/admin/users/?action=import_users" method="post" class="form-horizontal">

                                        <div>
                                            <button id="quick_import-user-btn" class="btn btn-secondary" name="import-user" data-complete-text="Changes saved" data-loading-text="saving..." type="submit">Import users</button>
                                        </div>
                                    </form>
                                    <form style="float:right;padding-right:5px;" id="settings-form" action="<?php echo @FULLCAL_URL; ?>
/admin/users/?action=new_user" method="post" class="form-horizontal">
                                        <?php if (isset ( $_SESSION['add_user_error'] )): ?>
                                        <div style="position:absolute;left:400px;color:red;font-weight:bold;">
                                            <?php echo $_SESSION['add_user_error']; ?>

                                        </div>
                                        <?php endif; ?>

                                        <div>
                                            <button id="add-user-btn" class="btn btn-primary" name="add-user" data-complete-text="Changes saved" data-loading-text="saving..." type="submit">Add user</button>
                                        </div>
                                    </form>
                                    <form style="float:right;padding-right:5px;" id="settings-quick-add-user-form" action="<?php echo @FULLCAL_URL; ?>
/admin/users/?action=quick_new_user" method="post" class="form-horizontal">

                                        <div>
                                            <button id="quick_add-user-btn" class="btn btn-primary" name="quick-add-user" data-complete-text="Changes saved" data-loading-text="saving..." type="submit">Quick add user</button>
                                        </div>
                                    </form>
                                <?php endif; ?>
                                
								

                                        <legend><?php if ($this->_tpl_vars['is_super_admin']): ?><span class="admins_lable">Admins</span> / <span class="users_lable">Users</span><?php else: ?><span class="users_lable">Users</span><?php endif; ?></legend>

                                        <div id="user_list">
                                                <table class="table table-striped" style="font-size:14px;">
                                                        <thead>
                                                                <tr>
                                                                        <th><span class="username_label">Username</span></th>
                                                                        <th><span class="title_label">Title</span></th>
                                                                        <th><span class="name_label">Name</span></th>
                                                                        <th><span class="email_label">Email</span></th>
                                                                        <th><span class="registration_date_label">Registration date</span></th>
                                                                        <th><span class="active_label">Active</span></th>

                                                                </tr>
                                                        </thead>
                                                        <tbody>

                                                        <?php $_from = $this->_tpl_vars['users']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>

                                                                <tr>
                                                                        <td><?php echo $this->_tpl_vars['item']['username']; ?>
</a> <?php if ($this->_tpl_vars['item']['superadmin']): ?><span class="label label-important">superadmin</span><?php elseif ($this->_tpl_vars['item']['admin']): ?><span class="label label-important">admin</span><?php elseif ($this->_tpl_vars['item']['trial']): ?><span class="label label-info">trial</span><?php elseif ($this->_tpl_vars['item']['has_subscription']): ?><span class="label label-info">subscr. ends <?php echo ((is_array($_tmp=$this->_tpl_vars['item']['subscription']['enddate'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%d-%m-%Y") : smarty_modifier_date_format($_tmp, "%d-%m-%Y")); ?>
</span><?php endif; ?></td>
                                                                        <td><?php echo $this->_tpl_vars['item']['title']; ?>
</td>
                                                                        <td><?php echo $this->_tpl_vars['item']['firstname']; ?>
 <?php echo $this->_tpl_vars['item']['lastname']; ?>
</td>
                                                                        <td><?php echo $this->_tpl_vars['item']['email']; ?>
</td>
                                                                        <td><?php echo $this->_tpl_vars['item']['registration_date']; ?>
</td>
                                                                        <td style="text-align:center;"><img src="<?php echo @FULLCAL_URL; ?>
/images/<?php if ($this->_tpl_vars['item']['active']): ?>checked.png<?php else: ?>unchecked.png<?php endif; ?>" /></td>

                                                                        <td class="not_print" style="border-left:1px solid #DDDDDD;"><a href="<?php echo @FULLCAL_URL; ?>
/admin/users?action=get_profile&uid=<?php echo $this->_tpl_vars['item']['user_id']; ?>
"><img src="<?php echo @FULLCAL_URL; ?>
/images/edit.png" /></a></td>
                                                                        <td class="not_print"><?php if (! $this->_tpl_vars['item']['superadmin'] && $this->_tpl_vars['item']['user_id'] != $this->_tpl_vars['user_id']): ?><a href="<?php echo @FULLCAL_URL; ?>
/admin/users?action=delete&uid=<?php echo $this->_tpl_vars['item']['user_id']; ?>
"><img src="<?php echo @FULLCAL_URL; ?>
/images/delete.png" /></a><?php endif; ?></td>
                                                                </tr>
                                                        <?php endforeach; endif; unset($_from); ?>

                                                        </tbody>
                                                </table>

								<!--	<div class="pagination"><ul><li class="prev disabled"><a href="<?php echo @FULLCAL_URL; ?>
/admin/users?to=<?php echo $this->_tpl_vars['from']; ?>
"> Previous</a></li><li class="active"><a href="#">1</a></li><li class="next disabled"><a href="#">Next </a></li></ul></div>	</div>-->


							</div>

                        <?php elseif ($this->_tpl_vars['active'] == 'groups' && $this->_tpl_vars['is_admin']): ?>
						
                            <div id="admin-groups"  style="padding-top:20px;padding-left:20px;">
                                    <?php if (! empty ( $this->_tpl_vars['error'] )): ?>
                                    <div style="position:absolute;left:400px;top:60px;color:red;font-weight:bold;">
                                            <?php echo $this->_tpl_vars['error']; ?>

                                    </div>
                                    <?php endif; ?>

                                    <?php if (! empty ( $this->_tpl_vars['msg'] )): ?>
                                    <div style="position:absolute;left:400px;top:60px;color:green;font-weight:bold;">
                                            <?php echo $this->_tpl_vars['msg']; ?>

                                    </div>
                                    <?php endif; ?>

                                    <form style="float:right;" id="settings-form" action="<?php echo @FULLCAL_URL; ?>
/admin/groups/?action=new_group" method="post" class="form-horizontal">
                                        <?php if (isset ( $_SESSION['add_user_error'] )): ?>
                                        <div style="position:absolute;left:400px;color:red;font-weight:bold;">
                                            <?php echo $_SESSION['add_user_error']; ?>

                                        </div>
                                        <?php endif; ?>

                                        <div>
                                            <button id="add-group-btn" class="btn btn-primary" name="add-group" data-complete-text="Changes saved" data-loading-text="saving..." type="submit">Add group</button>
                                        </div>
                                    </form>
                                                                   								

                                    <legend><span class="<?php if ($this->_tpl_vars['is_super_admin']): ?>groups_lable<?php elseif ($this->_tpl_vars['is_admin']): ?>my_groups_lable<?php endif; ?>">My Groups</span></legend>

                                    <div id="user_list">
                                            <table class="table table-striped" style="font-size:14px;">
                                                    <thead>
                                                            <tr>
                                                                    <th><span class="name_label">Name</span></th>
                                                                    <th><span class="count_users_label">Count users</span></th>
                                                                    <?php if ($this->_tpl_vars['is_super_admin']): ?><th><span class="owner_label">Owner</span></th><?php endif; ?>
                                                                    <th><span class="active_label">Active</span></th>

                                                            </tr>
                                                    </thead>
                                                    <tbody>

                                                    <?php $_from = $this->_tpl_vars['groups']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>

                                                            <tr>
                                                                    <td><?php echo $this->_tpl_vars['item']['name']; ?>
</td>
                                                                    <td><?php echo $this->_tpl_vars['item']['cnt_users']; ?>
</td>
                                                                    <?php if ($this->_tpl_vars['is_super_admin']): ?><td><?php echo $this->_tpl_vars['item']['fullname']; ?>
</td><?php endif; ?>
                                                                    <td style="text-align:left;"><img src="<?php echo @FULLCAL_URL; ?>
/images/<?php if ($this->_tpl_vars['item']['active']): ?>checked.png<?php else: ?>unchecked.png<?php endif; ?>" /></td>

                                                                    <td class="not_print" style="width:10px;border-left:1px solid #DDDDDD;"><a href="<?php echo @FULLCAL_URL; ?>
/admin/groups?action=get_group&gid=<?php echo $this->_tpl_vars['item']['group_id']; ?>
"><img src="<?php echo @FULLCAL_URL; ?>
/images/edit.png" /></a></td>
                                                                    <td class="not_print" style="width:10px;"><a href="<?php echo @FULLCAL_URL; ?>
/admin/groups?action=delete&gid=<?php echo $this->_tpl_vars['item']['group_id']; ?>
"><img src="<?php echo @FULLCAL_URL; ?>
/images/delete.png" /></a></td>
                                                            </tr>
                                                    <?php endforeach; endif; unset($_from); ?>

                                                    </tbody>
                                            </table>

								<!--	<div class="pagination"><ul><li class="prev disabled"><a href="<?php echo @FULLCAL_URL; ?>
/admin/users?to=<?php echo $this->_tpl_vars['from']; ?>
"> Previous</a></li><li class="active"><a href="#">1</a></li><li class="next disabled"><a href="#">Next </a></li></ul></div>	</div>-->


					</div>
                                </div>
                    <?php elseif ($this->_tpl_vars['active'] == 'group'): ?>
							<div id="admin-user-add-group" style="padding-top:20px;padding-left:20px;">
								<?php if (isset ( $this->_tpl_vars['error'] )): ?>
									<div style="position:absolute;left:400px;color:red;font-weight:bold;">
										<?php echo $this->_tpl_vars['error']; ?>

									</div>
								<?php endif; ?>

								<legend><?php if (isset ( $_GET['action'] ) && $_GET['action'] == 'new_group'): ?><span id="add_group_label">Add group</span><?php else: ?><span id="edit_group_label">Edit group</span>: <strong><?php echo $this->_tpl_vars['group']['name']; ?>
</strong><?php endif; ?></legend>

                                <form action="<?php echo @FULLCAL_URL; ?>
/admin/groups/?action=save_group" method="POST" class="form-horizontal">
									<div class="control-group">
										<label for="admin_add_name" class="control-label">Name </label>
										<div class="controls">
                                            <input type="text" class="input-xlarge" id="addgroup_name" name="name" value="<?php echo $this->_tpl_vars['group']['name']; ?>
">
										</div>
									</div>

									<div class="control-group">
										<label for="admin_add_description" class="control-label">Description </label>
										<div class="controls">
											<input type="text" class="input-xlarge" id="addgroup_description" name="description" value="<?php echo $this->_tpl_vars['group']['description']; ?>
">
										</div>
									</div>

                                    <div class="control-group">
                                        <label for="admin_group_active" class="control-label" id="admin_group_active_label"> Active </label>
                                        <div class="controls">
                                            <input type="checkbox" id="admin_group_active" name="active" <?php if ($this->_tpl_vars['group']['active']): ?>checked="true"<?php endif; ?> />
                                        </div>
                                    </div>
                                    
                                    <div id="select_user" style="display: none;">
                                        <select id="groupusers" class="multiselect" multiple="multiple" name="groupusers[]">
                                            <?php $_from = $this->_tpl_vars['group']['users']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>
                                            <option <?php if ($this->_tpl_vars['item']['selected']): ?>selected="selected"<?php endif; ?> value="<?php echo $this->_tpl_vars['item']['user_id']; ?>
"><?php echo $this->_tpl_vars['item']['fullname']; ?>
</option>
                                            <?php endforeach; endif; unset($_from); ?>
                                        </select>
                                    </div>
                                    <fieldset>
                                        <label style="margin-bottom:0;"><span id="group_users_label">Group users:</span></label>
                                    <div class="control-group" id="groupusers" style="border:1px solid #F3F3F3;padding:3px 3px 5px 0;" >
                                         <div class="controls" style="margin-left:20px;">
                                            <?php echo smarty_function_math(array('assign' => 'amount_on_col','equation' => 'x/y','x' => count($this->_tpl_vars['group']['users']),'y' => 3), $this);?>

                                       
                                            <div style="width:180px;float:left;border-right:1px solid #F3F3F3;margin-right: 5px;">
                                            <?php $_from = $this->_tpl_vars['group']['users']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>

                                                <?php if ($this->_tpl_vars['item']['selected']): ?>
                                                    <?php echo $this->_tpl_vars['item']['fullname']; ?>
<br />

                                                    <?php if ($this->_tpl_vars['key'] > 0): ?>
                                                        <?php if (( $this->_tpl_vars['key']+1 ) % ( ((is_array($_tmp=$this->_tpl_vars['amount_on_col'])) ? $this->_run_mod_handler('floor', true, $_tmp) : floor($_tmp)) ) == 0): ?>
                                                            </div><div style="float:left;width:180px;border-right:1px solid #F3F3F3;margin-right: 5px;">
                                                        <?php endif; ?>
                                                    <?php endif; ?>
                                                <?php endif; ?> 
                                            <?php endforeach; endif; unset($_from); ?>
                                            <?php if ($this->_tpl_vars['group']['cnt_users'] == 0): ?>
                                            <span id="no_users_found">No users found</span>
                                            <?php endif; ?>
                                            
                                            </div>
                                         <div style="clear:left;" id="added_users"></div>      
                                           
                                        </div>
                                        
                                    </div>
                                        <input type="button" class="btn" id="add_groupuserfield" value="Add/delete users" <?php if (isset ( $_GET['action'] ) && $_GET['action'] == 'new_group'): ?> disabled="disabled"<?php endif; ?> /><?php if (isset ( $_GET['action'] ) && $_GET['action'] == 'new_group'): ?> <span style="font-style:italic;">You can add users after the group is saved</span><?php endif; ?>
                                    </fieldset>
                                    
                                    <input type="hidden" id="calendar_groupusers" name="groupusers" value="<?php echo $this->_tpl_vars['str_groupusers']; ?>
" />
                                    <input type="hidden" name="group_id" value="<?php echo $this->_tpl_vars['group']['group_id']; ?>
" />

                                    <div class="form-actions">
                                            <button class="btn btn-primary" name="save-add-group" type="submit">Save group</button>
                                    </div>
                                </form>
                            </div>
                    
                    <?php elseif ($this->_tpl_vars['active'] == 'import_users'): ?>
                        <div id="admin-user-add-user" style="padding-top:20px;padding-left:20px;">
                                <?php if (isset ( $this->_tpl_vars['error'] )): ?>
                                        <div style="position:absolute;left:400px;color:red;font-weight:bold;">
                                                <?php echo $this->_tpl_vars['error']; ?>

                                        </div>
                                <?php endif; ?>

                                <legend>Import users</legend>
                                <p>
                                    Export your users table completely (all columns) to CSV. <br />
                                    Choose export method: 'extended'. <br />
                                    Columns enclosed with: [empty]<br />
                                    Columns converted with character: [empty]<br />
                                    Column names places in the first row: true<br /><br />
                                </p>
                                <form id="file_upload_form" method="post" enctype="multipart/form-data" action="<?php echo @FULLCAL_URL; ?>
/admin/users/?action=upload_file_and_import" class="form-horizontal">
                                    <table>
                                        <tr>
                                            <td style="width:20px;">
                                                <input type="radio" name="user_import_file" value="wordpress" />    
                                            </td>
                                            <td>
                                                <b>Wordpress</b> (10 columns)
                                            </td>  
                                        
                                        </tr> 
                                    </table>
                                    <table>
                                        <tr class="user-import-sjabloon-block" style="height:20px;">
                                            <td style="width:20px !important;">ID</td>
                                            <td class="required">user_login</td>
                                            <td>user_pass</td>
                                            <td>user_nicename</td>
                                            <td class="required">user_email</td>
                                            <td>user_url</td>
                                            <td class="required">user_registered</td>
                                            <td>user_activation_key</td>
                                            <td>user_status</td>
                                            <td class="required">display_name</td>
                                            
                                        </tr>
                                    </table>
                                    <br />
                                    <table>
                                        <tr>
                                            <td style="width:20px;">
                                                <input type="radio" name="user_import_file" value="phpbb_3.0.14" />   
                                            </td>
                                            <td>
                                                <b>phpBB 3.0.14</b> (76 columns)
                                            </td>  
                                            
                                            
                                        </tr> 
                                    </table>
                                    <table>
                                        <tr class="user-import-sjabloon-block" style="height:20px;">
                                            <td>...</td>	
                                            <td class="required">user_ip</td>
                                            <td class="required">user_regdate</td>
                                            <td class="required">username</td>
                                            <td>...</td> 	
                                            <td class="required">user_email</td>
                                            <td>...</td>	
                                            <td class="required">user_birthday</td>
                                            <td>...</td>
                                            <td class="required">user_from</td>
                                            <td>...</td> 
                                            <td class="required">user_interests</td>
                                            
                                        </tr>
                                    </table>
                                    <br />
                                    <table>
                                        <tr>
                                            <td style="width:20px;">
                                                <input type="radio" name="user_import_file" value="phpbb_3.1.7" />   
                                            </td>
                                            <td>
                                                <b>phpBB 3.1.7</b> (66 columns)
                                            </td>  
                                            
                                            
                                        </tr> 
                                    </table>
                                    <table>
                                        <tr class="user-import-sjabloon-block" style="height:20px;">
                                            <td>...</td>	
                                            <td class="required">user_ip</td>
                                            <td class="required">user_regdate</td>
                                            <td class="required">username</td>
                                            <td>...</td> 	
                                            <td class="required">user_email</td>
                                            <td>...</td>	
                                            <td class="required">user_birthday</td>
                                            <td>...</td>
                                             
                                            
                                        </tr>
                                    </table>
                                    <br />
                                    <table>
                                        <tr>
                                            <td style="width:20px;">
                                                <input type="radio" name="user_import_file" checked="checked" value="generic" />    
                                            </td>
                                            <td style="width:70px;">
                                                <b>Generic</b>    
                                            </td>  
                                            
                                        </tr>  
                                    </table>
                                    <table>
                                        <tr class="user-import-sjabloon-block" style="height:20px;">
                                            <td>title</td>	
                                            <td>firstname</td>	
                                            <td>infix</td>	
                                            <td class="required">lastname</td>	
                                            <td class="required">email</td>
                                            <td class="required">registration_date</td>
                                            <td>birth_date</td> 	
                                            <td class="required">username</td>
                                            <td>ip</td>
                                            <td>country</td>
                                            <td>user_info</td>
                                            
                                        </tr>
                                    </table>
                                                         
                                    <br /><br />
                                    <div class="control-group">
                                        <label for="admin_import_users_select_file" class="control-label">Select a CSV file </label>
                                        <div class="controls">
                                            <input name="file" type="file" id="select_file_field" />
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label for="admin_import_users_separated_by" class="control-label">Columns separated by </label>
                                        <div class="controls">
                                            <select style="width:40px;" name='columns_separated_by'>
                                                <option value='comma'>,</option>
                                                <option value='dot_comma'>;</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    
                                    <div class="form-actions">
                                            <button class="btn btn-primary" name="save-import-users" type="submit">Import</button>
                                    </div>
                                </form>
                        </div>

                    
                    <?php elseif ($this->_tpl_vars['active'] == 'new_user'): ?>
                                <div id="admin-user-add-user" style="padding-top:20px;padding-left:20px;">
                                        <?php if (isset ( $this->_tpl_vars['error'] )): ?>
                                                <div style="position:absolute;left:400px;color:red;font-weight:bold;">
                                                        <?php echo $this->_tpl_vars['error']; ?>

                                                </div>
                                        <?php endif; ?>

                                        <legend>Add user</legend>

                                <form action="<?php echo @FULLCAL_URL; ?>
/admin/users/?action=add_<?php if ($this->_tpl_vars['is_super_admin']): ?>admin<?php else: ?>user<?php endif; ?>" method="POST" class="form-horizontal">
                                    <div class="control-group">
                                            <label for="admin_user_add_title" class="control-label">Title </label>
                                            <div class="controls">
                                                    <input type="text" class="input-xlarge" id="adduser_title" name="title" value="">
                                            </div>
                                    </div>
                                    <div class="control-group">
                                        <label for="admin_user_add_name" class="control-label">Name </label>
                                        <div class="controls">
                                                <input style="width:94px;" type="text" class="input-xlarge" id="adduser_firstname" name="firstname" placeholder="Firstname" value="">
                                                <?php if (@SHOW_INFIX_IN_USER_FRM): ?>
                                                        <input style="width:30px;" type="text" class="input-xlarge" id="adduser_infix" name="infix" value="">
                                                <?php endif; ?>
                                                <input style="width:<?php if (@SHOW_INFIX_IN_USER_FRM): ?>110<?php else: ?>152<?php endif; ?>px;" type="text" class="input-xlarge" id="adduser_lastname" name="lastname" placeholder="Lastname" value="">
                                        </div>
                                </div>

                                <div class="control-group">
                                        <label for="admin_user_add_email" class="control-label">Email </label>
                                        <div class="controls">
                                                <input type="text" class="input-xlarge" id="adduser_email" name="email" value="">
                                        </div>
                                </div>

                                <?php if (@SHOW_USERNAME_IN_USER_FRM): ?>
                                <div class="control-group">
                                        <label for="admin_user_add_username" class="control-label">Username </label>
                                        <div class="controls">
                                                <input type="text" autocomplete="off" class="input-xlarge" id="adduser_username" name="username" value="">
                                        </div>
                                </div>
                                <?php endif; ?>

                                    <?php if (! $this->_tpl_vars['is_super_admin']): ?>
                                        <?php if (@SHOW_CHECKBOX_COPY_TO_ADMIN): ?>
                                        <div class="control-group">
                                            <label id="adduser_copy_to_admin_label_id" class="control-label">Copy to admin </label>
                                            <div class="controls">
                                                <span style="position: relative;top: 5px;">
                                                    <input type="checkbox" id="adduser_copy_to_admin" name="copy_to_admin" style="width:0;" />
                                                </span>
                                            </div>
                                        </div>
                                        <?php endif; ?>
                                    <?php endif; ?>

                                    <?php if (@SEND_ACTIVATION_MAIL): ?>
                                            <p style="font-style:italic;color:#AFAFAF;font-size:0.9em;" id="adduser_activationlink_text">The user can activate with the activation link included in the email.</p>
                                    <?php else: ?>
                                            <p style="font-style:italic;color:#AFAFAF;font-size:0.9em;" id="adduser_password_text">A password will be generated and included in the email.</p>
                                    <?php endif; ?>

                                    <div class="form-actions">
                                            <button class="btn btn-primary" name="save-add-user" type="submit">Save user</button>
                                    </div>
                            </form>
                    </div>

                    <?php elseif ($this->_tpl_vars['active'] == 'quick_new_user'): ?>
                                    <div id="admin-user-quick-add-user" style="padding-top:20px;padding-left:20px;">
                                            <?php if (isset ( $this->_tpl_vars['error'] )): ?>
                                                    <div style="position:absolute;left:400px;color:red;font-weight:bold;">
                                                            <?php echo $this->_tpl_vars['error']; ?>

                                                    </div>
                                            <?php endif; ?>

                                            <legend>Quick add <?php if ($this->_tpl_vars['is_super_admin']): ?>admin<?php else: ?>user<?php endif; ?></legend>
                                <p style="font-size:14px;padding-bottom:10px;color:#AFAFAF;">With this form you can quickly add <?php if ($this->_tpl_vars['is_super_admin']): ?> an admin<?php else: ?>a user<?php endif; ?><br />No email is send to the user and the admin sets the password.<br /><span style="font-style:italic;">First name and prefix are optional.</span></p>
                                
                                <form action="<?php echo @FULLCAL_URL; ?>
/admin/users/?action=quick_add_<?php if ($this->_tpl_vars['is_super_admin']): ?>admin<?php else: ?>user<?php endif; ?>" method="POST" class="form-horizontal">
                                    <div class="control-group">
                                            <label for="admin_user_add_title" class="control-label">Title </label>
                                            <div class="controls">
                                                    <input type="text" class="input-xlarge" id="adduser_title" name="title" value="<?php echo $this->_tpl_vars['values']['title']; ?>
">
                                            </div>
                                    </div>
                                    <div class="control-group">
                                        <label for="admin_user_add_name" class="control-label">Name </label>
                                        <div class="controls">
                                            <input style="width:94px;" type="text" class="input-xlarge" id="adduser_firstname" name="firstname" placeholder="First name" value="<?php echo $this->_tpl_vars['values']['firstname']; ?>
">
                                                    <?php if (@SHOW_INFIX_IN_USER_FRM): ?>
                                                            <input style="width:30px;" type="text" class="input-xlarge" id="adduser_infix" name="infix" value="<?php echo $this->_tpl_vars['values']['infix']; ?>
">
                                                    <?php endif; ?>
                                                    <input style="width:<?php if (@SHOW_INFIX_IN_USER_FRM): ?>110<?php else: ?>152<?php endif; ?>px;" type="text" class="input-xlarge" id="adduser_lastname" name="lastname" placeholder="Last name" value="<?php echo $this->_tpl_vars['values']['lastname']; ?>
">
                                            </div>
                                    </div>

                                    <div class="control-group">
                                            <label for="admin_user_add_email" class="control-label">Email </label>
                                            <div class="controls">
                                                    <input type="text" class="input-xlarge" id="adduser_email" name="email" value="<?php echo $this->_tpl_vars['values']['email']; ?>
">
                                            </div>
                                    </div>

                                    <div class="control-group">
                                            <label for="admin_user_add_username" class="control-label">Username </label>
                                            <div class="controls">
                                                    <input type="text" autocomplete="off" class="input-xlarge" id="adduser_username" name="username" value="<?php echo $this->_tpl_vars['values']['username']; ?>
">
                                            </div>
                                    </div>
                                    <div class="control-group">
                                            <label for="admin_user_add_password" class="control-label">Password </label>
                                            <div class="controls">
                                                    <input type="password" autocomplete="off" class="input-xlarge" id="adduser_username" name="password" value="">
                                            </div>
                                    </div>

                                    <div class="form-actions">
                                            <button class="btn btn-primary" name="save-quick-add-user" type="submit">Save user</button>
                                    </div>
                            </form>
			</div>

					<?php elseif ($this->_tpl_vars['active'] == 'calendars'): ?>
							<div id="admin-calendars"  style="padding-top:20px;padding-left:20px;">
								<?php if (! empty ( $this->_tpl_vars['error'] )): ?>
								<div style="position:absolute;left:400px;top:60px;color:red;font-weight:bold;">
									<?php echo $this->_tpl_vars['error']; ?>

								</div>
								<?php endif; ?>

								<?php if (! empty ( $this->_tpl_vars['msg'] )): ?>
								<div style="position:absolute;left:400px;top:60px;color:green;font-weight:bold;">
									<?php echo $this->_tpl_vars['msg']; ?>

								</div>
								<?php endif; ?>

								<?php if (! $this->_tpl_vars['is_super_admin']): ?>
								<form id="calendars-form" action="<?php echo @FULLCAL_URL; ?>
/admin/calendars/?action=new_calendar" method="post" class="form-horizontal">
									<?php if (isset ( $_SESSION['add_calendar_error'] )): ?>
									<div style="position:absolute;left:400px;color:red;font-weight:bold;">
										<?php echo $_SESSION['add_calendar_error']; ?>

									</div>
									<?php endif; ?>

									<div style="float:right;">
                                                                            <button id="add-calendar-btn" class="btn btn-primary" name="add-calendar" data-complete-text="Changes saved" data-loading-text="saving..." type="submit">Add calendar</button>
									</div>
 								</form>
								<?php endif; ?>
								
								<legend class="<?php if ($this->_tpl_vars['is_super_admin']): ?>admin_calendars_menu<?php elseif ($this->_tpl_vars['is_admin']): ?>admin_my_calendars_menu<?php endif; ?>">Calendars</legend>

								<div id="calendar_list">
									<table class="table table-striped" style="font-size:14px;">
										<thead>
											<tr>
												<th colspan="5"></th>
												<th style="text-align:center;" colspan="4">Others/Group can</th>
												<th colspan="2"></th>
											</tr>
										</thead>
                                        <thead>
                                                <tr>
                                                        <th class="not_print" style="border-top:0 none;"></th>
                                                        <th style="border-top:0 none;"><span class="name_label">Name</span></th>
                                                        <th style="border-top:0 none;"><span class="origin_label">Origin</span></th>
                                                        <!--<?php if (! $this->_tpl_vars['is_super_admin']): ?><th style="border-top:0 none;"><span class="canseedditems_label">Can see DD-items</span></th><?php endif; ?>-->
                                                        <th style="border-top:0 none;"><span class="type_label">Type</span></th>
                                                        <th style="text-align:center;border-top:0 none;width:45px;"><span class="view_label">Public view</span></th>
                                                        <th style="text-align:center;border-top:1px dotted lightgray;width:45px;"><span class="add_label">Add</span></th>
                                                        <th style="text-align:center;border-top:1px dotted lightgray;width:45px;"><span class="edit_label">Edit</span></th>
                                                        <th style="text-align:center;border-top:1px dotted lightgray;width:45px;"><span class="delete_label">Delete</span></th>
                                                        <th style="text-align:center;border-top:1px dotted lightgray;width:45px;"><span class="changecolor_label">Change color</span></th>
                                                        <th style="text-align:center;border-top:0 none;"><span class="initialshow_label">Initial show</span></th>
                                                        <th style="text-align:center;border-top:0 none;"><span class="active_label">Active</span></th>
                                                        <?php if ($this->_tpl_vars['is_super_admin']): ?><th style="border-top:0 none;"><span class="owner_label">Owner</span></th><?php endif; ?>

                                                </tr>
                                        </thead>
                                        <tbody>

                                        <?php $_from = $this->_tpl_vars['calendars']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['calendars'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['calendars']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['item']):
        $this->_foreach['calendars']['iteration']++;
?>

                                                <tr>
                                                        <td style="border: 14px solid white;width:1px;background-color:<?php echo $this->_tpl_vars['item']['calendar_color']; ?>
;"></td>
                                                        <td><?php echo $this->_tpl_vars['item']['name']; ?>
</a> <?php if ($this->_tpl_vars['item']['superadmin']): ?><span class="label label-important">superadmin</span><?php elseif ($this->_tpl_vars['item']['admin']): ?><span class="label label-important">admin</span><?php endif; ?></td>
                                                        <td><?php echo $this->_tpl_vars['item']['origin']; ?>
</td>
                                                        <!--<?php if (! $this->_tpl_vars['is_super_admin']): ?><td><?php if ($this->_tpl_vars['item']['can_dd_drag'] == 'only_owner'): ?>Only owner<?php elseif ($this->_tpl_vars['item']['can_dd_drag'] == 'only_loggedin_users'): ?>Only loggedin users<?php else: ?>Everyone<?php endif; ?></td><?php endif; ?>-->
                                                        <td><?php if ($this->_tpl_vars['item']['share_type'] == 'private_group'): ?>Private (group)<?php elseif ($this->_tpl_vars['item']['share_type'] == 'private'): ?>Private<?php else: ?><?php echo $this->_tpl_vars['item']['share_type']; ?>
<?php endif; ?></td>
                                                        <td style="text-align:center;"><img src="<?php echo @FULLCAL_URL; ?>
/images/<?php if ($this->_tpl_vars['item']['can_view']): ?>checked.png<?php else: ?>unchecked.png<?php endif; ?>" /></td>
                                                        <td style="text-align:center;"><img src="<?php echo @FULLCAL_URL; ?>
/images/<?php if ($this->_tpl_vars['item']['can_add']): ?>checked.png<?php else: ?>unchecked.png<?php endif; ?>" /></td>
                                                        <td style="text-align:center;"><img src="<?php echo @FULLCAL_URL; ?>
/images/<?php if ($this->_tpl_vars['item']['can_edit']): ?>checked.png<?php else: ?>unchecked.png<?php endif; ?>" /></td>
                                                        <td style="text-align:center;"><img src="<?php echo @FULLCAL_URL; ?>
/images/<?php if ($this->_tpl_vars['item']['can_delete']): ?>checked.png<?php else: ?>unchecked.png<?php endif; ?>" /></td>
                                                        <td style="text-align:center;"><img src="<?php echo @FULLCAL_URL; ?>
/images/<?php if ($this->_tpl_vars['item']['can_change_color']): ?>checked.png<?php else: ?>unchecked.png<?php endif; ?>" /></td>
                                                        <td style="text-align:center;"><img src="<?php echo @FULLCAL_URL; ?>
/images/<?php if ($this->_tpl_vars['item']['initial_show']): ?>checked.png<?php else: ?>unchecked.png<?php endif; ?>" /></td>
                                                        <td style="text-align:center;"><img src="<?php echo @FULLCAL_URL; ?>
/images/<?php if ($this->_tpl_vars['item']['active'] == 'yes'): ?>checked.png<?php else: ?>unchecked.png<?php endif; ?>" /></td>
                                                        <?php if ($this->_tpl_vars['is_super_admin']): ?><td><?php echo $this->_tpl_vars['item']['fullname']; ?>
</td><?php endif; ?>
                                                        <?php if (! ($this->_foreach['calendars']['iteration'] <= 1)): ?><td class="not_print" style="border-left:1px solid #DDDDDD;width:10px;"><a href="<?php echo @FULLCAL_URL; ?>
/admin/calendars/?action=up&ids=<?php echo $this->_tpl_vars['cal_ids']; ?>
&cid=<?php echo $this->_tpl_vars['item']['calendar_id']; ?>
"><img width="14" src="<?php echo @FULLCAL_URL; ?>
/images/glyphicons/glyphicons-214-up-arrow.png" /></a></td>
                                                        <?php else: ?><td class="not_print" style="border-left:1px solid #DDDDDD;width:10px;">&nbsp;</td><?php endif; ?>

                                                        <?php if (($this->_foreach['calendars']['iteration'] == $this->_foreach['calendars']['total'])): ?><td class="not_print" style="width:10px;">&nbsp;</td>
                                                        <?php else: ?>
                                                        <td class="not_print" style="width:10px;"><a href="<?php echo @FULLCAL_URL; ?>
/admin/calendars/?action=down&ids=<?php echo $this->_tpl_vars['cal_ids']; ?>
&cid=<?php echo $this->_tpl_vars['item']['calendar_id']; ?>
"><img width="14" src="<?php echo @FULLCAL_URL; ?>
/images/glyphicons/glyphicons-213-down-arrow.png" /></a></td>
                                                        <?php endif; ?>
                                                        <?php if ($this->_tpl_vars['item']['deleted'] == 0): ?>
                                                        <td class="not_print" style="border-left:1px solid #DDDDDD;width:10px;"><a href="<?php echo @FULLCAL_URL; ?>
/admin/calendars?action=get_calendar&cid=<?php echo $this->_tpl_vars['item']['calendar_id']; ?>
"><img src="<?php echo @FULLCAL_URL; ?>
/images/edit.png" /></a></td>
                                                            <?php if ($this->_tpl_vars['user_id'] == $this->_tpl_vars['item']['creator_id']): ?><td class="not_print" style="width:10px;"><a href="<?php echo @FULLCAL_URL; ?>
/admin/calendars?action=delete&cid=<?php echo $this->_tpl_vars['item']['calendar_id']; ?>
"><img src="<?php echo @FULLCAL_URL; ?>
/images/delete.png" /></a></td><?php endif; ?>
                                                        <?php else: ?>
                                                            <?php if ($this->_tpl_vars['user_id'] == $this->_tpl_vars['item']['creator_id']): ?><td class="not_print" style="width:10px;"><a class="undo_delete_btn" href="<?php echo @FULLCAL_URL; ?>
/admin/calendars?action=undelete&cid=<?php echo $this->_tpl_vars['item']['calendar_id']; ?>
">Undo delete</a></td><?php endif; ?>

                                                        <?php endif; ?>
                                                    </tr>
                                                            <?php endforeach; endif; unset($_from); ?>

                                                            </tbody>
                                                    </table>
                                    <?php if ($this->_tpl_vars['cnt_deleted_calendars'] > 0 && $_GET['action'] != 'get_deleted'): ?>
                                    <div style="float:right;padding-top:20px;">
                                        <a id="deleted_cals_btn" href="<?php echo @FULLCAL_URL; ?>
/admin/calendars?action=get_deleted">Deleted calendars</a>
                                    </div>
                                    <?php endif; ?>
                                    
								<!--	<div class="pagination"><ul><li class="prev disabled"><a href="<?php echo @FULLCAL_URL; ?>
/admin/calendars?to=<?php echo $this->_tpl_vars['from']; ?>
"> Previous</a></li><li class="active"><a href="#">1</a></li><li class="next disabled"><a href="#">Next </a></li></ul></div>	</div>-->


							</div>

					
                                    <?php elseif ($this->_tpl_vars['active'] == 'profile'): ?>
					<div class="left-tabs clearfix">
                                                <ul class="nav nav-tabs">
                                                    <li <?php if ($this->_tpl_vars['subactive'] == 'profile'): ?>class="active"<?php else: ?><?php endif; ?>><a data-toggle="tab" href="<?php echo @FULLCAL_URL; ?>
/admin/users/?action=get_profile&uid=<?php echo $this->_tpl_vars['profile']['user_id']; ?>
">Profile</a></li>
                                                    <li <?php if ($this->_tpl_vars['subactive'] == 'availibility'): ?>class="active"<?php else: ?><?php endif; ?>><a data-toggle="tab" href="<?php echo @FULLCAL_URL; ?>
/admin/users/?action=get_availibility&uid=<?php echo $this->_tpl_vars['profile']['user_id']; ?>
">Availability</a></li>
                                                </ul>

                                        </div>	
                                        <div class="tab_content">
                                        <?php if ($this->_tpl_vars['subactive'] == 'profile'): ?>
                                            <div id="admin-user-profile" class="tab-pane <?php if ($this->_tpl_vars['subactive'] == 'profile'): ?>active<?php endif; ?>" style="padding-top:20px;padding-left:20px;">
                                            <?php if (! empty ( $this->_tpl_vars['save_profile_error'] )): ?>
                                            <div style="position:absolute;left:400px;top:60px;color:red;font-weight:bold;">
                                                    <?php echo $this->_tpl_vars['save_profile_error']; ?>

                                            </div>
                                            <?php endif; ?>

                                            <?php if (! empty ( $this->_tpl_vars['save_profile_success'] )): ?>
                                            <div style="position:absolute;left:400px;top:60px;color:green;font-weight:bold;">
                                                    <?php echo $this->_tpl_vars['save_profile_success']; ?>

                                            </div>
                                            <?php endif; ?>


                                            <form action="<?php echo @FULLCAL_URL; ?>
<?php if ($this->_tpl_vars['is_user']): ?>/user<?php else: ?>/admin/users<?php endif; ?>/?action=save_profile" method="post" class="form-horizontal">
                                
                                                <div class="control-group" <?php if ($this->_tpl_vars['is_super_admin']): ?>style="display:none;"<?php endif; ?> >
                                                        <label for="admin_profile_useractive" class="control-label" id="admin_profile_user_active_label"> Active </label>
                                                        <div class="controls">
                                                                <input type="checkbox" id="admin_profile_user_active" name="active" <?php if ($this->_tpl_vars['profile']['active']): ?>checked="true"<?php endif; ?> />
                                                        </div>
                                                </div>

                                                <div class="control-group">
									<label for="admin_user_profile_title" class="control-label">Title </label>
									<div class="controls">
										<input type="text" class="input-xlarge" id="profile_title" name="title" value="<?php echo $this->_tpl_vars['profile']['title']; ?>
">
									</div>
								</div>
								<div class="control-group">
									<label for="admin_user_profile_name" class="control-label">Name </label>
									<div class="controls">
										<input style="width:94px;" type="text" class="input-xlarge" id="profile_firstname" name="firstname" placeholder="Firstname" value="<?php echo $this->_tpl_vars['profile']['firstname']; ?>
">
										<?php if (@SHOW_INFIX_IN_USER_FRM): ?>
											<input style="width:30px;" type="text" class="input-xlarge" id="profile_infix" name="infix" value="<?php echo $this->_tpl_vars['profile']['infix']; ?>
">
										<?php endif; ?>
										<input style="width:<?php if (@SHOW_INFIX_IN_USER_FRM): ?>110<?php else: ?>152<?php endif; ?>px;" type="text" class="input-xlarge" id="profile_lastname" name="lastname" placeholder="Lastname" value="<?php echo $this->_tpl_vars['profile']['lastname']; ?>
">
									</div>
								</div>

								<div class="control-group">
									<label for="admin_user_profile_birthdate" class="control-label">Birthdate </label>
									<div class="controls">
									<?php if (@DATEPICKER_DATEFORMAT == 'dd/mm/yy'): ?>
										<input style="width:25px;" type="text" placeholder="DD" class="input-xlarge" id="profile_birthdate_day" name="birthdate_day" value="<?php echo $this->_tpl_vars['profile']['birthdate_day']; ?>
">
										<input style="width:25px;" type="text" placeholder="MM" class="input-xlarge" id="profile_birthdate_month" name="birthdate_month" value="<?php echo $this->_tpl_vars['profile']['birthdate_month']; ?>
">

									<?php else: ?>
										<input style="width:25px;" type="text" placeholder="MM" class="input-xlarge" id="profile_birthdate_month" name="birthdate_month" value="<?php echo $this->_tpl_vars['profile']['birthdate_month']; ?>
">
										<input style="width:25px;" type="text" placeholder="DD" class="input-xlarge" id="profile_birthdate_day" name="birthdate_day" value="<?php echo $this->_tpl_vars['profile']['birthdate_day']; ?>
">

									<?php endif; ?>
									<input style="width:45px;" type="text" placeholder="YYYY" class="input-xlarge" id="profile_birthdate_year" name="birthdate_year" value="<?php echo $this->_tpl_vars['profile']['birthdate_year']; ?>
">

									</div>
								</div>

								<div class="control-group">
									<label for="admin_user_profile_country" class="control-label">Country </label>
									<div class="controls">
										<input type="text" class="input-xlarge" id="profile_country" name="country" value="<?php echo $this->_tpl_vars['profile']['country']; ?>
">
									</div>
								</div>

								<div class="control-group">
									<label for="admin_user_profile_email" class="control-label">Email </label>
									<div class="controls">
										<input type="text" class="input-xlarge" id="profile_email" name="email" value="<?php echo $this->_tpl_vars['profile']['email']; ?>
">
									</div>
								</div>

								<div class="control-group">
									<label for="admin_user_profile_username" class="control-label">Username </label>
									<div class="controls">
										<input type="text" class="input-xlarge" id="profile_username" name="username" value="<?php echo $this->_tpl_vars['profile']['username']; ?>
">
									</div>
								</div>

								<div class="control-group">
									<label for="admin_user_profile_password" class="control-label">New password </label>
									<div class="controls">
										<input type="password" autocomplete="off" class="input-xlarge" id="profile_password" name="password" placeholder="Leave blank for no change">
									</div>
								</div>

								<div class="control-group">
									<label for="admin_user_profile_new_password" class="control-label">New password again </label>
									<div class="controls">
										<input type="password" autocomplete="off" class="input-xlarge" id="profile_confirm" name="confirm" placeholder="Leave blank for no change">
									</div>
								</div>
                                
                                <div class="control-group">
									<label for="admin_user_profile_user_info" class="control-label">Info </label>
									<div class="controls">
                                        <textarea autocomplete="off" style="height:100px;" class="input-xlarge" id="profile_user_info" name="user_info"><?php echo $this->_tpl_vars['profile']['user_info']; ?>
</textarea>
									</div>
								</div>
                                
                                <input type="hidden" name="user_id" value="<?php echo $this->_tpl_vars['profile']['user_id']; ?>
" />
									<div class="form-actions">
										<button id="save-profile" class="btn btn-primary" name="save-profile" data-complete-text="Changes saved" data-loading-text="saving..." type="submit">Save changes</button>
									</div>

							</form>


							</div>
                                        <?php endif; ?>      
                                        <?php if ($this->_tpl_vars['subactive'] == 'availibility'): ?>
                                        <div id="admin-user-availibility" style="padding-top:20px;padding-left:20px;">
                                             <div id="admin-availibility-rows"  style="padding-top:20px;padding-left:20px;">
                                                <?php if (! empty ( $this->_tpl_vars['error'] )): ?>
                                                <div style="position:absolute;left:400px;top:60px;color:red;font-weight:bold;">
                                                        <?php echo $this->_tpl_vars['error']; ?>

                                                </div>
                                                <?php endif; ?>

                                                <?php if (! empty ( $this->_tpl_vars['msg'] )): ?>
                                                <div style="position:absolute;left:400px;top:60px;color:green;font-weight:bold;">
                                                        <?php echo $this->_tpl_vars['msg']; ?>

                                                </div>
                                                <?php endif; ?>

                                                <form style="float:right;" id="settings-form" action="<?php echo @FULLCAL_URL; ?>
/admin/availability/?action=new" method="post" class="form-horizontal">
                                                    <?php if (isset ( $_SESSION['add_user_error'] )): ?>
                                                    <div style="position:absolute;left:400px;color:red;font-weight:bold;">
                                                        <?php echo $_SESSION['add_user_error']; ?>

                                                    </div>
                                                    <?php endif; ?>

                                                    <div>
                                                        <button id="add-availability-btn" class="btn btn-primary" name="add-group" data-complete-text="Changes saved" data-loading-text="saving..." type="submit">Add availability</button>
                                                    </div>
                                                </form>


                                                <legend><span class="groups_label">Availability</span></legend>

                                                <div id="user_availability">
                                                        <table class="table table-striped" style="font-size:14px;">
                                                                <thead>
                                                                        <tr>
                                                                                <th><span class="name_label">Info</span></th>
                                                                                <th><span class="startdate_label">Start date</span></th>
                                                                                <th><span class="enddate_label">End date</span></th>
                                                                                
                                                                        </tr>
                                                                </thead>
                                                                <tbody>

                                                                <?php $_from = $this->_tpl_vars['groups']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>

                                                                        <tr>
                                                                                <td><?php echo $this->_tpl_vars['item']['name']; ?>
</td>
                                                                                <td><?php echo $this->_tpl_vars['item']['cnt_users']; ?>
</td>
                                                                                <td><?php echo $this->_tpl_vars['item']['cnt_users']; ?>
</td>
                                                                                
                                                                                <td class="not_print" style="width:10px;border-left:1px solid #DDDDDD;"><a href="<?php echo @FULLCAL_URL; ?>
/admin/groups?action=get_group&gid=<?php echo $this->_tpl_vars['item']['group_id']; ?>
"><img src="<?php echo @FULLCAL_URL; ?>
/images/edit.png" /></a></td>
                                                                                <td class="not_print" style="width:10px;"><a href="<?php echo @FULLCAL_URL; ?>
/admin/groups?action=delete&gid=<?php echo $this->_tpl_vars['item']['group_id']; ?>
"><img src="<?php echo @FULLCAL_URL; ?>
/images/delete.png" /></a></td>
                                                                        </tr>
                                                                <?php endforeach; endif; unset($_from); ?>

                                                                </tbody>
                                                        </table>

                                                                            <!--	<div class="pagination"><ul><li class="prev disabled"><a href="<?php echo @FULLCAL_URL; ?>
/admin/users?to=<?php echo $this->_tpl_vars['from']; ?>
"> Previous</a></li><li class="active"><a href="#">1</a></li><li class="next disabled"><a href="#">Next </a></li></ul></div>	</div>-->


                                                    </div>
                                            </div>
                                        </div>
                                        <?php endif; ?>
                                      
                                    </div><!-- EO tab-content -->
                                    
                                    <?php elseif ($this->_tpl_vars['active'] == 'availability'): ?>
                                    <div id="admin-user-calendar" style="padding-top:20px;padding-left:20px;">
                                        <legend>(UNDER CONSTRUCTION)<?php if (isset ( $_GET['action'] ) && $_GET['action'] == 'new'): ?><span id="add_availability_label">Add availability</span><?php else: ?><span id="edit_availability_label">Edit availability</span>: <strong><?php echo $this->_tpl_vars['availability']['name']; ?>
</strong><?php endif; ?></legend>

                                        <?php if (! empty ( $this->_tpl_vars['save_calendar_error'] )): ?>
                                        <div style="position:absolute;left:400px;top:60px;color:red;font-weight:bold;">
                                                <?php echo $this->_tpl_vars['save_calendar_error']; ?>

                                        </div>
                                        <?php endif; ?>

                                        <?php if (! empty ( $this->_tpl_vars['save_calendar_success'] )): ?>
                                        <div style="position:absolute;left:400px;top:60px;color:green;font-weight:bold;">
                                                <?php echo $this->_tpl_vars['save_calendar_success']; ?>

                                        </div>
                                        <?php endif; ?>
                                        
                                        <form id="calendar_save_form" action="<?php echo @FULLCAL_URL; ?>
/admin/calendars/?action=save_calendar" method="post" class="form-horizontal">

                                            <div class="control-group">
                                            <span id="month_label_id" style="padding-left:30px;margin-bottom: 0;">Info: </span>
                                                <input type="text" id="hourcalc_datepicker_startdate" style="font-size:13px;margin-bottom: 0;width: 300px;padding:3px;z-index:9999;">
                                            <span id="month_label_id" style="padding-left:30px;margin-bottom: 0;">From: </span>
                                                <input type="text" id="hourcalc_datepicker_startdate" style="font-size:13px;margin-bottom: 0;width: 80px;padding:3px;z-index:9999;">
                                            <span id="time_label_id">To: </span>
                                                <input type="text" id="hourcalc_datepicker_enddate" style="font-size:13px;margin-bottom: 0;width: 80px;padding:3px;" />
                                            </div>
                                        <hr />
                                       
                                           
                                            <!--<div class="control-group">
                                                <label class="control-label"><span id="availability_tuesday_label">Tuesday</span> </label>
                                                <div class="controls">
                                                    <span>
                                                        <input type="text" class="input-xlarge" id="admin_calendar_admin_email" name="calendar_admin_email" value="<?php echo $this->_tpl_vars['calendar']['calendar_admin_email']; ?>
" style="font-size:13px;margin-bottom: 0;width: 95px;padding:3px;">
                                                        <input type="text" class="input-xlarge" id="admin_monday_enddate" name="calendar_admin_email" value="<?php echo $this->_tpl_vars['calendar']['calendar_admin_email']; ?>
" style="font-size:13px;margin-bottom: 0;width: 95px;padding:3px;">
                                                    </span>
                                                    <span style="padding-left:40px;">
                                                        <input type="text" class="input-xlarge" id="admin_calendar_admin_email" name="calendar_admin_email" value="<?php echo $this->_tpl_vars['calendar']['calendar_admin_email']; ?>
" style="font-size:13px;margin-bottom: 0;width: 95px;padding:3px;">
                                                        <input type="text" class="input-xlarge" id="admin_monday_enddate" name="calendar_admin_email" value="<?php echo $this->_tpl_vars['calendar']['calendar_admin_email']; ?>
" style="font-size:13px;margin-bottom: 0;width: 95px;padding:3px;">
                                                
                                                    </span>
                                                </div>
                                            </div>
                                            -->
                                        <div class="control-group">
                                            <label class="control-label"><span id="availability_monday_label">Monday</span> </label>
                                            <div class="controls">
                                                <div id="time-range-1">
                                                    <div class="slider-1" style="width:70%;float:left;">
                                                        <div class="slider-range1" data-nr="m1" data-st="540" data-e="1020"></div>
                                                        <div class="slider-range2" data-nr="m2" style="display:none;" data-st="1080" data-e="1380"></div>
                                                    </div>
                                                    <div style="float:right;padding-top:5px;"><span id="slider-1-1"></span> - <span id="slider-1-2"></span></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label class="control-label"><span id="availability_tuesday_label">Tuesday</span> </label>
                                            <div class="controls">
                                                <div id="time-range-2">
                                                    <div class="slider-2" style="width:70%;float:left;">
                                                        <div class="slider-range1" data-nr="t1" data-st="540" data-e="1020"></div>
                                                        <div class="slider-range2" data-nr="t2" style="display:none;" data-st="1080" data-e="1380"></div>
                                                    </div>
                                                    <div style="float:right;padding-top:5px;"><span id="slider-2-1"></span> - <span id="slider-2-2"></span></div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        </form>
                                        
                                    </div>
                                    
                                    <?php elseif ($this->_tpl_vars['active'] == 'calendar'): ?>
                                            <div id="admin-user-calendar" style="padding-top:20px;padding-left:20px;">
                            <legend><?php if (isset ( $_GET['action'] ) && $_GET['action'] == 'new_calendar'): ?><span id="add_calendar_label">Add calendar</span><?php else: ?><span id="edit_calendar_label">Edit calendar</span>: <strong><?php echo $this->_tpl_vars['calendar']['name']; ?>
</strong><?php endif; ?></legend>

                            <?php if (! empty ( $this->_tpl_vars['save_calendar_error'] )): ?>
                            <div style="position:absolute;left:400px;top:60px;color:red;font-weight:bold;">
                                    <?php echo $this->_tpl_vars['save_calendar_error']; ?>

                            </div>
                            <?php endif; ?>

                            <?php if (! empty ( $this->_tpl_vars['save_calendar_success'] )): ?>
                            <div style="position:absolute;left:400px;top:60px;color:green;font-weight:bold;">
                                    <?php echo $this->_tpl_vars['save_calendar_success']; ?>

                            </div>
                            <?php endif; ?>

                            <form id="calendar_save_form" action="<?php echo @FULLCAL_URL; ?>
/admin/calendars/?action=save_calendar" method="post" class="form-horizontal">

                                <div class="control-group">
                                    <label for="admin_user_calendar_name" class="control-label"><span class="name_label">Name</span> </label>
                                            <div class="controls">
                                                    <input type="text" class="input-xlarge" id="calendar_name" name="name" placeholder="Name" value="<?php echo $this->_tpl_vars['calendar']['name']; ?>
">
                                            </div>
                                    </div>
                                                            
                                                            <div class="control-group">
									<label for="admin_user_calendar_active" class="control-label"><span id="active_label">Active</span> </label>
									<div class="controls"  style="padding-top:5px;">
                                        <span>
                                            <input type="radio" value="yes" name="active"  style="float:left;margin-right:5px;" id="admin_calendar_active_yes" <?php if ($this->_tpl_vars['calendar']['active'] == 'yes' || ! isset ( $this->_tpl_vars['calendar']['active'] ) || empty ( $this->_tpl_vars['calendar']['active'] )): ?>checked="true"<?php endif; ?> /><label for="admin_calendar_active_yes" style="padding-top:1px;width: 33px;float:left;padding-right:20px;"><span id="yes_label">Yes</span> </label>
                                            <input type="radio" value="no" name="active"  style="float:left;margin-right:5px;" <?php if ($this->_tpl_vars['calendar']['active'] == 'no'): ?>checked="true"<?php endif; ?> /><label for="admin_calendar_active_yes" style="padding-top:1px;width: 33px;float:left;padding-right:20px;"><span id="no_label">No</span> </label>
                                            <input type="radio" value="period" id="radio_specific_period" name="active"  style="float:left;margin-right:5px;" <?php if ($this->_tpl_vars['calendar']['active'] == 'period'): ?>checked="true"<?php endif; ?> /><label for="admin_calendar_active_yes" style="padding-top:1px;width: 200px;float:left;padding-right:20px;"><span id="in_specific_period_label">In specific period</span> </label>
									    </span>
                                    </div>
								</div>
                               
                                <!-- active period -->
                                <div class="control-group">
                                    <label for="admin_calendar_active" class="control-label"><span id="active_period_label">Active period</span> </label>
									<span class="simple_starttime_label" style="padding-left:20px;margin-bottom: 0;">From: </span>
                                    <input type="text" name="cal_startdate" id="active_period_datepicker_startdate" value="<?php echo $this->_tpl_vars['calendar']['cal_startdate']; ?>
" <?php if ($this->_tpl_vars['calendar']['active'] != 'period'): ?>disabled="disabled"<?php endif; ?> style="font-size:13px;margin-bottom: 0;width: 95px;padding:3px;z-index:9999;">
                                    <span class="simple_endtime_label">Until: </span>
                                        <input type="text" name="cal_enddate" id="active_period_datepicker_enddate" value="<?php echo $this->_tpl_vars['calendar']['cal_enddate']; ?>
" <?php if ($this->_tpl_vars['calendar']['active'] != 'period'): ?>disabled="disabled"<?php endif; ?> style="font-size:13px;margin-bottom: 0;width: 95px;padding:3px;" />
                                </div>
                                
                                <!-- alterable period restriction -->
                                <div class="control-group">
                                    <label for="admin_calendar_alterable" class="control-label"><span id="alterable_period_label">Alterable period</span> </label>
									<span class="simple_starttime_label" style="padding-left:20px;margin-bottom: 0;">From: </span>
                                        <input type="text" name="alterable_startdate" id="alterable_period_datepicker_startdate" value="<?php echo $this->_tpl_vars['calendar']['alterable_startdate']; ?>
" style="font-size:13px;margin-bottom: 0;width: 95px;padding:3px;z-index:9999;">
                                    <span class="simple_endtime_label">To: </span>
                                        <input type="text" name="alterable_enddate" id="alterable_period_datepicker_enddate" value="<?php echo $this->_tpl_vars['calendar']['alterable_enddate']; ?>
" style="font-size:13px;margin-bottom: 0;width: 95px;padding:3px;" />
                                </div>
                                        
				<div class="control-group">
                                    <label for="admin_user_calendar_next_days_visible" class="control-label"><span class="next_days_visible_label">Users can see next</span> </label>
                                    <div class="controls">
                                            <input type="text" style="width:50px;" class="input-xlarge" id="calendar_next_days_visible" name="next_days_visible" value="<?php echo $this->_tpl_vars['calendar']['next_days_visible']; ?>
"> days
                                    </div>
                                </div>				

								<!--<div class="control-group">
									<label for="admin_user_calendar_share_type" class="control-label">Type </label>
									<div class="controls">
										<input type="text" class="input-xlarge" id="calendar_type" name="country" value="<?php echo $this->_tpl_vars['calendar']['share_type']; ?>
">
									</div>
								</div>-->
                                <hr />
                                <h4 style="padding: 10px 0 10px 180px;font-weight:bold;">
                                    <span id="">Drag & drop items</span>
                                </h4>
                                <div class="control-group">
                                        <label for="admin_usergroup_dditems" class="control-label" id="admin_usergroup_dditems_label">User-group DD-items </label>
						<div class="controls">
					<select name="dditems_usergroup_id" id="admin_dditems_usergroups">
                                            <option value="0" <?php if ($this->_tpl_vars['calendar']['dditems_usergroup_id'] == 0): ?>selected="selected"<?php endif; ?>>none</option>
                                            <?php $_from = $this->_tpl_vars['my_groups']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>
                                            <option value="<?php echo $this->_tpl_vars['item']['group_id']; ?>
" <?php if ($this->_tpl_vars['calendar']['dditems_usergroup_id'] == $this->_tpl_vars['item']['group_id']): ?>selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['item']['name']; ?>
</option>
                                            <?php endforeach; endif; unset($_from); ?>
                                        </select>
                                        <?php if (count($this->_tpl_vars['my_groups']) == 0): ?>
                                            No active groups found
                                        <?php endif; ?>
									</div>
                                    <div class="controls" style="margin-left:20px;padding-bottom:5px;">
					<label id="" class="control-label"></label>
						<input type="checkbox" name="assign_dditem_to_user" <?php if ($this->_tpl_vars['calendar']['assign_dditem_to_user'] == 1): ?>checked="checked" <?php endif; ?> />
						<span id="assign_to_users_label" style="padding-top:5px;vertical-align:middle;">Assign to users</span>
                                    </div>
				</div>
                                <div class="control-group">
					<label for="admin_usergroup_dditems_viewtype" class="control-label" id="admin_usergroup_dditems_viewtype_label">How to show DD-items </label>
					<div class="controls">
										<!--  -->
						<select name="usergroup_dditems_viewtype">
							<option value="dropdown" <?php if ($this->_tpl_vars['calendar']['usergroup_dditems_viewtype'] == 'dropdown'): ?>selected="selected"<?php endif; ?>>Dropdown list</option>
							<option value="buttons" <?php if (! isset ( $this->_tpl_vars['calendar']['usergroup_dditems_viewtype'] ) || $this->_tpl_vars['calendar']['usergroup_dditems_viewtype'] == 'buttons'): ?>selected="selected"<?php endif; ?>>Show all DD-items</option>
						</select>
					</div>
				</div>
                                
				<div class="control-group" style="padding-top:10px;">
                                    <label for="admin_user_calendar_dditems" style="padding-top: 35px;" class="control-label"><span id="dd_items_label" >Custom DD-items</span> </label>
									<div class="controls">
										<!--<textarea class="input-xlarge" id="calendar_dditems" name="dditems" ><?php echo $this->_tpl_vars['calendar']['dditems']; ?>
</textarea>-->
                                        <input type="hidden" id="calendar_dditems" name="dditems" value="<?php echo $this->_tpl_vars['str_dditems']; ?>
" />
									
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
                                                    <th style="text-align:center;width:195px;border-top:0 none;padding:0;border-bottom:1px solid lightgray;"><span id="title_label">Title</span> *</th>
                                                    <th style="text-align:center;width:195px;border-top:0 none;padding:0;border-bottom:1px solid lightgray;"><span id="info_label">Info</span></th>
                                                    <th style="text-align:center;width:100px;border-top:0 none;padding:0;border-bottom:1px solid lightgray;"><span id="starttime_label">Start time</span></th>
                                                    <th style="text-align:center;width:100px;border-top:0 none;padding:0;border-bottom:1px solid lightgray;"><span id="endtime_label">End time</span></th>
                                                    <th style="text-align:center;width:100px;border-top:0 none;padding:0;border-bottom:1px solid lightgray;"><span id="nightshift_label">Night shift</span></th>
                                                    <th style="text-align:center;width:60px;border-top:0 none;padding:0;border-bottom:1px solid lightgray;"><span class="color_label">Color</span></th>
                                               </tr>
                                            </thead>
                                            <tbody>

                                            <?php if (! empty ( $this->_tpl_vars['calendar']['dditems'] )): ?>
                                                <?php $_from = $this->_tpl_vars['calendar']['dditems']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>
                                                    <?php if (! empty ( $this->_tpl_vars['item'] )): ?>
                                                    <tr>
                                                        <td style="padding:1px;border:none;"><input type="text" style="width:150px;" name="title<?php echo $this->_tpl_vars['item']['dditem_id']; ?>
" value="<?php echo $this->_tpl_vars['item']['title']; ?>
" class="admin-dditem-title" id="admin-spectrum-colorpicker-dditem-title-<?php echo $this->_tpl_vars['item']['dditem_id']; ?>
" /></td>
                                                        <td style="padding:1px;border:none;"><input type="text" style="width:150px;" name="info<?php echo $this->_tpl_vars['item']['dditem_id']; ?>
" value="<?php echo $this->_tpl_vars['item']['info']; ?>
" class="admin-dditem-info" id="admin-dditem-info-<?php echo $this->_tpl_vars['item']['dditem_id']; ?>
" /></td>
                                                        <td style="padding:2px;border:none;"><input type="text" class="dditem_timepicker_starttime" id="dditem_timepicker_starttime-<?php echo $this->_tpl_vars['item']['dditem_id']; ?>
" name="starttime<?php echo $this->_tpl_vars['item']['dditem_id']; ?>
" value="" style="font-size:13px;width: 80px;"></td>
                                                        <td style="padding:2px;border:none;"><input type="text" class="dditem_timepicker_endtime" id="dditem_timepicker_endtime-<?php echo $this->_tpl_vars['item']['dditem_id']; ?>
" name="endtime<?php echo $this->_tpl_vars['item']['dditem_id']; ?>
" value="" style="font-size:13px;width: 80px;"></td>
                                                        <td style="padding:2px;border:none;"><input type="checkbox" class="admin_dditem_nightshift" id="admin-dditem-nightshift-<?php echo $this->_tpl_vars['item']['dditem_id']; ?>
" name="nightshift<?php echo $this->_tpl_vars['item']['dditem_id']; ?>
" <?php if ($this->_tpl_vars['item']['nightshift']): ?>checked="checked"<?php endif; ?> style="font-size:13px;width: 80px;"></td>
                                                        <td style="padding:1px;border:none;"><input type="text" class="input-xlarge admin-spectrum-colorpicker-dditems" id="admin-spectrum-colorpicker-dditem-<?php echo $this->_tpl_vars['item']['dditem_id']; ?>
" name="dditem_color[]" value="<?php echo $this->_tpl_vars['item']['color']; ?>
" data-title="<?php echo $this->_tpl_vars['item']['title']; ?>
" data-number="<?php echo $this->_tpl_vars['item']['dditem_id']; ?>
"></td>
                                                    </tr>
                                                    <?php endif; ?>
                                                <?php endforeach; endif; unset($_from); ?>
                                            <?php endif; ?>
                                            </tbody>
                                        </table>
                                       <input type="button" id="add_dditem" style="margin-top:10px;" value="Add a DD-item" />
                                    </div>
								</div>
                                 <div class="control-group">
                                        <label for="admin_calendar_can_see_dditems" class="control-label" id="admin_can_see_dditems_label">Can see DD-items </label>
                                        <div class="controls">
                                                <!--  -->
                                                <select name="can_dd_drag">
                                                        <option value="only_owner" <?php if ($this->_tpl_vars['calendar']['can_dd_drag'] == 'only_owner'): ?>selected="selected"<?php endif; ?>>Only calendar owner</option>
                                                        <option value="only_loggedin_users" <?php if ($this->_tpl_vars['calendar']['can_dd_drag'] == 'only_loggedin_users'): ?>selected="selected"<?php endif; ?>>Only loggedin users</option>
                                                        <option value="everyone" <?php if ($this->_tpl_vars['calendar']['can_dd_drag'] == 'everyone'): ?>selected="selected"<?php endif; ?>>Everyone</option>
                                                </select>
                                        </div>
                                </div>
   <hr />
                                <div class="control-group">
                                        <label for="admin_calendar_origin" class="control-label" id="admin_origin_label">Origin </label>
                                        <div class="controls">
                                                <!--  -->
                                                <select name="origin" id="admin_calendar_origin">
                                                        <option value="default" <?php if (! isset ( $this->_tpl_vars['calendar']['origin'] ) || empty ( $this->_tpl_vars['calendar']['origin'] ) || $this->_tpl_vars['calendar']['origin'] == 'default'): ?>selected="selected"<?php endif; ?>>Default</option>
                                                        <option value="exchange" <?php if ($this->_tpl_vars['calendar']['origin'] == 'exchange'): ?>selected="selected"<?php endif; ?>>Exchange</option>

                                                </select>
                                        </div>
                                </div>
                                <div class="control-group" id="exchange_username_field" style="padding-left: 100px;display:<?php if (isset ( $this->_tpl_vars['calendar']['origin'] ) && ! empty ( $this->_tpl_vars['calendar']['origin'] ) && $this->_tpl_vars['calendar']['origin'] == 'exchange'): ?>block<?php else: ?>none<?php endif; ?>;">
									<div style="padding-left: 100px;padding-bottom:4px;font-style: italic;">mcrypt is used to save username and password in the database</div>
                                    <label for="admin_exchange_username" class="control-label">Username </label>
									<div class="controls">
										<input type="text" class="" id="exchange_username" name="exchange_username" value="<?php echo $this->_tpl_vars['calendar']['exchange_username']; ?>
">
                                    </div>
								</div>
                                <div class="control-group" id="exchange_password_field" style="padding-left: 100px;display:<?php if (isset ( $this->_tpl_vars['calendar']['origin'] ) && ! empty ( $this->_tpl_vars['calendar']['origin'] ) && $this->_tpl_vars['calendar']['origin'] == 'exchange'): ?>block<?php else: ?>none<?php endif; ?>;">
									<label for="admin_exchange_password" class="control-label">Password </label>
									<div class="controls">
									    <input type="password" class="" id="exchange_password" name="exchange_password" value="<?php echo $this->_tpl_vars['calendar']['exchange_password']; ?>
">
                                    </div>
								</div>
                                <div class="control-group" id="admin_exchange_extra_secure" style="margin-bottom:1px;display:<?php if (isset ( $this->_tpl_vars['calendar']['origin'] ) && ! empty ( $this->_tpl_vars['calendar']['origin'] ) && $this->_tpl_vars['calendar']['origin'] == 'exchange'): ?>block<?php else: ?>none<?php endif; ?>;">
									<div class="controls" style="margin-left:20px;padding-bottom:5px;">
										<label id="" class="control-label"></label>
                                        <input type="checkbox" id="exchange_extra_secure_checkbox" <?php if ($this->_tpl_vars['calendar']['exchange_extra_secure'] == 1): ?>checked="checked" <?php endif; ?> name="exchange_extra_secure" />
										<span id="admin_exchange_extra_secure" style="padding-top:5px;vertical-align:middle;">Extra secure, user needs to know this token</span>
									</div>
								</div>
                                <div class="control-group" id="exchange_token_field" style="padding-left: 100px;display:<?php if (isset ( $this->_tpl_vars['calendar']['origin'] ) && ! empty ( $this->_tpl_vars['calendar']['origin'] ) && $this->_tpl_vars['calendar']['origin'] == 'exchange'): ?>block<?php else: ?>none<?php endif; ?>;">
                                    <label for="admin_exchange_token" class="control-label">Secret token </label>
                                    <div class="controls">
                                        <input type="password" class="" id="exchange_token" name="exchange_token" value="<?php echo $this->_tpl_vars['calendar']['exchange_token']; ?>
" <?php if (! $this->_tpl_vars['calendar']['exchange_extra_secure'] == 1): ?>disabled="disabled" <?php endif; ?>>
                                    </div>
								</div>
                                <div class="control-group">
                                        <label for="admin_calendar_share_type" class="control-label" id="admin_share_type_label">Share type </label>
                                        <div class="controls">
                                                <!--  -->
                                                <select name="share_type" id="admin_calendar_share_type">
                                                        <option value="public" <?php if ($this->_tpl_vars['calendar']['share_type'] == 'public'): ?>selected="selected"<?php endif; ?>>Public</option>
                                                        <option value="private" <?php if ($this->_tpl_vars['calendar']['share_type'] == 'private'): ?>selected="selected"<?php endif; ?>>Private (only for me)</option>
                                                        <option value="private_group" <?php if ($this->_tpl_vars['calendar']['share_type'] == 'private_group'): ?>selected="selected"<?php endif; ?>>Private (only for group)</option>
                                                </select>
                                        </div>
                                </div>
                                <div class="control-group" id="admin_calendar_group_combo" style= "<?php if ($this->_tpl_vars['calendar']['share_type'] == 'private_group'): ?>display:block;<?php else: ?>display:none;<?php endif; ?>">
                                    <label for="admin_calendar_admingroups" class="control-label" id="admin_admin_groups_label"> </label>
                                    <div class="controls">
                                            <!--  -->
                                            <select name="usergroup_id" id="admin_settings_usergroups">
                                                    <?php $_from = $this->_tpl_vars['my_groups']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>
                                            <option value="<?php echo $this->_tpl_vars['item']['group_id']; ?>
" <?php if ($this->_tpl_vars['calendar']['usergroup_id'] == $this->_tpl_vars['item']['group_id']): ?>selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['item']['name']; ?>
</option>
                                            <?php endforeach; endif; unset($_from); ?>
                                        </select>
                                        <?php if (count($this->_tpl_vars['my_groups']) == 0): ?>
                                            No active groups found
                                        <?php endif; ?>
									</div>
                                </div>
				<div id="admin_others_can_view" class="control-group" style="margin:0;display: <?php if ($this->_tpl_vars['calendar']['share_type'] == 'private_group'): ?>block<?php else: ?>none<?php endif; ?>;">
                                    <label for="admin_calendar_otherscanview" class="control-label" id="admin_calendar_can_view_label"><span class="others_label">Others</span> <span id="can_view_label">can view</span> </label>
                                            <div class="controls">
                                                    <input type="checkbox" id="admin_calendar_others_can_view" name="can_view" <?php if ($this->_tpl_vars['calendar']['can_view'] && $this->_tpl_vars['calendar']['share_type'] == 'private_group'): ?>checked="true"<?php endif; ?> />
                                            </div>
                                    </div>
                                    <div class="control-group" style="margin:0;">
                                    <label for="admin_calendar_canadd" class="control-label" id="admin_calendar_can_add_label"><?php if ($this->_tpl_vars['calendar']['share_type'] == 'private_group'): ?>Group<?php else: ?><span class="others_label">Others</span><?php endif; ?> <span id="can_add_label">can add</span> </label>
									<div class="controls">
										<input type="checkbox" id="admin_calendar_can_add" name="can_add" <?php if ($this->_tpl_vars['calendar']['can_add'] && $this->_tpl_vars['calendar']['share_type'] != 'private'): ?>checked="true"<?php endif; ?> <?php if ($this->_tpl_vars['calendar']['share_type'] == 'private'): ?>disabled="disabled"<?php endif; ?> />
									</div>
								</div>
								<div class="control-group" style="margin:0;">
                                    <label for="admin_calendar_canedit" class="control-label" id="admin_calendar_can_edit_label"><?php if ($this->_tpl_vars['calendar']['share_type'] == 'private_group'): ?>Group<?php else: ?><span class="others_label">Others</span><?php endif; ?> <span id="can_edit_label">can edit</span> </label>
									<div class="controls">
										<input type="checkbox" id="admin_calendar_can_edit" name="can_edit" <?php if ($this->_tpl_vars['calendar']['can_edit'] && $this->_tpl_vars['calendar']['share_type'] != 'private'): ?>checked="true"<?php endif; ?> <?php if ($this->_tpl_vars['calendar']['share_type'] == 'private'): ?>disabled="disabled"<?php endif; ?> />
									</div>
								</div>
                                <div class="control-group" style="margin:0;">
                                    <label for="admin_calendar_candelete" class="control-label" id="admin_calendar_can_delete_label"><?php if ($this->_tpl_vars['calendar']['share_type'] == 'private_group'): ?>Group<?php else: ?><span class="others_label">Others</span><?php endif; ?> <span id="can_delete_label">can delete</span> </label>
                                            <div class="controls">
                                                    <input type="checkbox" id="admin_calendar_can_delete" name="can_delete" <?php if ($this->_tpl_vars['calendar']['can_delete'] && $this->_tpl_vars['calendar']['share_type'] != 'private'): ?>checked="true"<?php endif; ?> <?php if ($this->_tpl_vars['calendar']['share_type'] == 'private'): ?>disabled="disabled"<?php endif; ?> />
                                            </div>
                                    </div>
                                    <div class="control-group" style="margin:0;">
                                            <label for="admin_calendar_canchange_color" class="control-label" id="admin_calendar_can_change_color_label"><?php if ($this->_tpl_vars['calendar']['share_type'] == 'private_group'): ?>Group<?php else: ?><span class="others_label">Others</span><?php endif; ?> <span id="can_change_color_label">can change color</span> </label>
                                            <div class="controls">
                                                    <input type="checkbox" id="admin_calendar_can_change_color" name="can_change_color" <?php if ($this->_tpl_vars['calendar']['can_change_color'] && $this->_tpl_vars['calendar']['share_type'] != 'private'): ?>checked="true"<?php endif; ?> <?php if ($this->_tpl_vars['calendar']['share_type'] == 'private'): ?>disabled="disabled"<?php endif; ?> />
                                            </div>
                                    </div>
                                    <div class="control-group" style="margin:0;">
                                    <label for="admin_calendar_default" class="control-label"><span id="admin_default_calendar_label">Default calendar</span></label>
                                    <div class="controls">
                                            <input type="checkbox" name="initial_show" <?php if ($this->_tpl_vars['calendar']['initial_show']): ?>checked="true"<?php endif; ?> />
									    
                                    </div>
								</div>
                               

								<div class="control-group">
                                    <label for="admin_calendar_color" class="control-label"><span class="color_label">Color</span> </label>
                                            <div class="controls" style="width:150px;">
                                                    <input type="text" class="input-xlarge" id="admin-spectrum-colorpicker" name="calendar_color" value="<?php echo $this->_tpl_vars['calendar']['calendar_color']; ?>
">
                                            </div>
                                    </div>

                                    <div class="control-group" id="color_change_all_events" style="margin-bottom:1px;">
                                            <div class="controls" style="margin-left:20px;padding-bottom:5px;">
                                                    <label id="" class="control-label"></label>
                                                    <input type="checkbox" name="checkbox_use_color_for_all_events" />
                                                    <span id="use_color_for_all_events_label" style="padding-top:5px;vertical-align:middle;">Use current color for all the events of this calendar</span>
                                            </div>
                                    </div>
                                <hr />
                                <h4 style="padding: 10px 0 10px 180px;font-weight:bold;">
                                    <span id="">Edit-dialog fields</span>
                                </h4>
                                <div class="control-group" style="margin:0;">
                                    <div style="float:left;">
                                        <label class="control-label" style="padding-top:0;">
                                        <span id="admin_show_description_in_edit_dialog_checkbox_label">Show description field</span>
                                        </label>
                                        <div class="controls" style="width:80px;">
                                            <input type="checkbox" name="show_description_field" <?php if ($this->_tpl_vars['calendar']['show_description_field']): ?>checked="checked"<?php endif; ?> />
                                        </div>
                                    </div>
                                    <div>
                                        <label class="control-label" style="padding-top:1px;width:130px;padding-right: 20px;">
                                        <span id="admin_description_field_required_checkbox_label">Required</span>
                                        </label>
                                        <div class="controls" style="margin-left:400px;">
                                            <input type="checkbox" name="description_field_required" <?php if ($this->_tpl_vars['calendar']['description_field_required']): ?>checked="checked"<?php endif; ?> />
                                        </div>
                                    </div>
                                </div>
                                <div class="control-group" id="" style="margin-bottom:1px;">
                                    <div style="float:left;">
                                        <label class="control-label" style="padding-top:0;">
                                            <span id="admin_show_location_in_edit_dialog_checkbox_label">Show location field</span>
                                        </label>
                                        <div class="controls" style="width:80px;">
                                            <input type="checkbox" name="show_location_field" <?php if ($this->_tpl_vars['calendar']['show_location_field']): ?>checked="checked"<?php endif; ?> />
                                        </div> 
                                    </div>
                                    <div>
                                        <label class="control-label" style="padding-top:1px;width:130px;padding-right: 20px;">
                                        <span id="admin_location_field_required_checkbox_label">Required</span>
                                        </label>
                                        <div class="controls" style="margin-left:400px;">
                                            <input type="checkbox" name="location_field_required" <?php if ($this->_tpl_vars['calendar']['location_field_required']): ?>checked="checked"<?php endif; ?> />
                                        </div>
                                    </div>
                                </div>
                                <div class="control-group" id="" style="margin-bottom:1px;">
                                    <div style="float:left;"> 
                                        <label class="control-label" style="padding-top:0;">
                                        <span id="admin_show_phone_in_edit_dialog_checkbox_label">Show description field</span>
                                        </label>
                                        <div class="controls" style="width:80px;">
                                            <input type="checkbox" name="show_phone_field" <?php if ($this->_tpl_vars['calendar']['show_phone_field']): ?>checked="checked"<?php endif; ?> />
                                        </div>
                                    </div>
                                    <div>
                                        <label class="control-label" style="padding-top:1px;width:130px;padding-right: 20px;">
                                        <span id="admin_phone_field_required_checkbox_label">Required</span>
                                        </label>
                                        <div class="controls" style="margin-left:400px;">
                                            <input type="checkbox" name="phone_field_required" <?php if ($this->_tpl_vars['calendar']['phone_field_required']): ?>checked="checked"<?php endif; ?> />
                                        </div>
                                    </div>
                                </div>
                                <div class="control-group" id="" style="margin-bottom:1px;">
                                    <div style="float:left;"> 
                                        <label class="control-label" style="padding-top:0;">
                                            <span id="admin_show_url_in_edit_dialog_checkbox_label">Show URL field</span>
                                        </label>
                                        <div class="controls" style="width:80px;">
                                            <input type="checkbox" name="show_url_field" <?php if ($this->_tpl_vars['calendar']['show_url_field']): ?>checked="checked"<?php endif; ?> />
                                        </div>
                                    </div>
                                    <div>
                                        <label class="control-label" style="padding-top:1px;width:130px;padding-right: 20px;">
                                        <span id="admin_url_field_required_checkbox_label">Required</span>
                                        </label>
                                        <div class="controls" style="margin-left:400px;">
                                            <input type="checkbox" name="url_field_required" <?php if ($this->_tpl_vars['calendar']['url_field_required']): ?>checked="checked"<?php endif; ?> />
                                        </div>
                                    </div>
                                </div>
                                <div class="control-group" id="" style="margin-bottom:1px;">
                                    <div style="float:left;"> 
                                        <label class="control-label" style="padding-top:0;">
                                        <span id="admin_show_teammember_in_edit_dialog_checkbox_label">Show team member field</span>
                                        </label>
                                        <div class="controls" style="width:80px;">
                                            <input type="checkbox" id="team_member_field_id" name="show_team_member_field" <?php if ($this->_tpl_vars['calendar']['show_team_member_field'] === true): ?>checked="checked"<?php endif; ?> />
                                        </div>
                                    </div>
                                    <div>
                                        <label class="control-label" style="padding-top:1px;width:130px;padding-right: 20px;">
                                        <span id="admin_notify_assign_teammember_checkbox_label">When assigned notify user</span>
                                        </label>
                                        <div class="controls" style="margin-left:400px;">
                                            <input type="checkbox" name="notify_assign_teammember" <?php if ($this->_tpl_vars['calendar']['notify_assign_teammember']): ?>checked="checked"<?php endif; ?> />
                                        </div>
                                    </div>
                                </div>
                                <div class="control-group" id="" style="margin-bottom:1px;">
                                    <div style="float:left;"> 
                                        <label class="control-label" style="padding-top:0;">
                                        <span id="admin_show_dropdown_1_field_in_edit_dialog_checkbox_label">Show custom dropdown #1</span>
                                        </label>
                                        <div class="controls" style="width:80px;">
                                            <input type="checkbox" id="custom_dropdown1_field_id" name="show_dropdown_1_field" <?php if ($this->_tpl_vars['calendar']['show_dropdown_1_field']): ?>checked="checked"<?php endif; ?> /> <span style="vertical-align:middle;font-style:italic;"> &nbsp;<?php echo $this->_tpl_vars['calendar']['dropdown1_label']; ?>
</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="control-group" id="" style="margin-bottom:1px;">
                                    <div style="float:left;"> 
                                        <label class="control-label" style="padding-top:0;">
                                        <span id="admin_show_dropdown_2_field_in_edit_dialog_checkbox_label">Show custom dropdown #2</span>
                                        </label>
                                        <div class="controls" style="width:80px;">
                                            <input type="checkbox" id="custom_dropdown2_field_id" name="show_dropdown_2_field" <?php if ($this->_tpl_vars['calendar']['show_dropdown_2_field']): ?>checked="checked"<?php endif; ?> /> <span style="vertical-align:middle;font-style:italic;"> &nbsp;<?php echo $this->_tpl_vars['calendar']['dropdown2_label']; ?>
</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="control-group">
                                    <label for="admin_user_calendar_location" class="control-label"><span id="event_location_label">Event location field</span> </label>
									<div class="controls"  style="padding-top:5px;">
                                        <span>
                                            <input type="radio" value="text" name="locationfield"  style="float:left;margin-right:5px;" id="admin_calendar_location_text" <?php if (count($this->_tpl_vars['calendar']['locations']) == 0 || ! isset ( $this->_tpl_vars['calendar']['locations'] ) || empty ( $this->_tpl_vars['calendar']['locations'] )): ?>checked="true"<?php endif; ?> /><label for="admin_calendar_locationfield_text" style="padding-top:1px;width: 33px;float:left;padding-right:20px;">text </label>
                                            <input type="radio" value="combo" name="locationfield"  style="float:left;margin-right:5px;" <?php if (count($this->_tpl_vars['calendar']['locations']) > 0): ?>checked="true"<?php endif; ?> /><label for="admin_calendar_locationfield_combo" style="padding-top:1px;width: 33px;float:left;padding-right:20px;">dropdown-list </label>
                                        </span>
                                    </div>
				</div>
                                
                                <div class="control-group" id="locations" style="<?php if (! isset ( $this->_tpl_vars['calendar']['locations'] ) || empty ( $this->_tpl_vars['calendar']['locations'] )): ?>display:none;<?php endif; ?>">
                                    <label for="admin_user_calendar_locations" style="padding-top:35px;" class="control-label"><span id="predefined_locations_label">Predefined locations</span> </label>
									<div class="controls">
										<input type="hidden" id="calendar_locations" name="locations" value="<?php echo $this->_tpl_vars['str_locations']; ?>
" />
									
                                        <table id="locationtable" class="table table-striped" style="font-size:14px;width:510px;font-size:13px;margin-bottom:0;">
                                            <thead>
                                                <tr style="">
                                                    <th style="width:50px;border-top:0 none;text-align:left;"><span class="name_label">Name</span></th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            <?php $_from = $this->_tpl_vars['calendar']['locations']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>
                                                <tr>
                                                    <td style="width:50px;padding:1px;border:none;"><input type="text" name="name<?php echo $this->_tpl_vars['item']['location_id']; ?>
" value="<?php echo $this->_tpl_vars['item']['name']; ?>
" data-number="<?php echo $this->_tpl_vars['item']['location_id']; ?>
" class="admin-location-name" id="admin-location-name-<?php echo $this->_tpl_vars['item']['location_id']; ?>
" /></td>
                                                </tr>
                                               
                                            <?php endforeach; endif; unset($_from); ?>

                                            </tbody>
                                        </table>
                                       <input type="button" id="add_locationfield" value="Add a location" />
                                    </div>
				</div>
                                
                                <hr id="event_title_additions_hr" style="display:<?php if (! $this->_tpl_vars['calendar']['show_team_member_field'] && ! $this->_tpl_vars['calendar']['show_dropdown_1_field'] && ! $this->_tpl_vars['calendar']['show_dropdown_2_field']): ?>none;<?php else: ?>block;<?php endif; ?>" />
                                <h4 id="event_title_additions_h4" style="padding: 10px 0 10px 180px;font-weight:bold;display:<?php if (! $this->_tpl_vars['calendar']['show_team_member_field'] && ! $this->_tpl_vars['calendar']['show_dropdown_1_field'] && ! $this->_tpl_vars['calendar']['show_dropdown_2_field']): ?>none;<?php else: ?>block;<?php endif; ?>">
                                    <span id="">Event title additions</span>
                                </h4>
                                
                                <div class="control-group" id="add_team_member_to_title" style="margin-bottom:1px;display:<?php if ($this->_tpl_vars['calendar']['show_team_member_field']): ?>block;<?php else: ?>none;<?php endif; ?>">
                                    <div style="float:left;"> 
                                        <label class="control-label" style="padding-top:0;">
                                        <span id="admin_add_team_member_to_title_label">Team member</span>
                                        </label>
                                        <div class="controls" style="width:80px;">
                                            <input type="checkbox" name="add_team_member_to_title" <?php if ($this->_tpl_vars['calendar']['add_team_member_to_title']): ?>checked="checked"<?php endif; ?> />
                                        </div>
                                    </div>
                                </div>
                                <div class="control-group" id="add_custom_dropdown1_to_title" style="margin-bottom:1px;display:<?php if ($this->_tpl_vars['calendar']['show_dropdown_1_field']): ?>block;<?php else: ?>none;<?php endif; ?>">
                                    <div style="float:left;"> 
                                        <label class="control-label" style="padding-top:0;">
                                        <span id="admin_add_team_member_to_title_label">Custom dropdown #1</span>
                                        </label>
                                        <div class="controls" style="width:80px;">
                                            <input type="checkbox" name="add_custom_dropdown1_to_title" <?php if ($this->_tpl_vars['calendar']['add_custom_dropdown1_to_title']): ?>checked="checked"<?php endif; ?> />
                                        </div>
                                    </div>
                                </div>
                                <div class="control-group" id="add_custom_dropdown2_to_title" style="margin-bottom:1px;display:<?php if ($this->_tpl_vars['calendar']['show_dropdown_2_field']): ?>block;<?php else: ?>none;<?php endif; ?>">
                                    <div style="float:left;"> 
                                        <label class="control-label" style="padding-top:0;">
                                        <span id="admin_add_team_member_to_title_label">Custom dropdown #2</span>
                                        </label>
                                        <div class="controls" style="width:80px;">
                                            <input type="checkbox" name="add_custom_dropdown2_to_title" <?php if ($this->_tpl_vars['calendar']['add_custom_dropdown2_to_title']): ?>checked="checked"<?php endif; ?> />
                                        </div>
                                    </div>
                                </div>
                                
                       <hr />         
                                <h4 style="padding: 10px 0 10px 180px;font-weight:bold;">
                                    <span id="notifications_label">Notifications (only when a user is logged in)</span>
                                </h4>
                                <div class="control-group">
                                    <label for="users_can_email_event" class="control-label"><span id="manually_label">Manually</span> </label>
									<div class="controls">
										<input type="checkbox" name="users_can_email_event" <?php if ($this->_tpl_vars['calendar']['users_can_email_event']): ?>checked="true"<?php endif; ?> />
									    <span id="manually_info_label" style="padding-top:5px;vertical-align:middle;">Users can mail an event to admin/employer</span>
                                    </div>
								</div>
                                <div class="control-group">
                                    <label for="all_event_mods_to_admin" class="control-label"><span id="automatic_label">Automatic</span> </label>
									<div class="controls">
										<input type="checkbox" name="all_event_mods_to_admin" <?php if ($this->_tpl_vars['calendar']['all_event_mods_to_admin']): ?>checked="true"<?php endif; ?> />
									    <span id="automatic_info_label" style="padding-top:5px;vertical-align:middle;">An email with event changes is automatically send to admin/employer</span>
                                    </div>
								</div>
                                
                                <div class="control-group">
                                    <label for="admin_calendar_admin_email" class="control-label"><span id="calendar_admin_email_label">Calendar admin email</span> </label>
									<div class="controls">
                                        <input type="text" class="input-xlarge" id="admin_calendar_admin_email" name="calendar_admin_email" value="<?php echo $this->_tpl_vars['calendar']['calendar_admin_email']; ?>
"><span style="padding-left:5px;font-style:italic;" id="when_admin_email_is_empty_info">When empty: MAIL_EVENT_MAILADDRESS from config.php is used</span>
                                    </div>
								</div>
                               <hr />
                                <div class="control-group">
                                    <label for="assigned_event_to_admin" class="control-label"><span id="assigned_event_to_user_label">Assigned events</span> </label>
									<div class="controls" style="padding-bottom:10px;">
										<input type="checkbox" name="mail_assigned_event_to_user" <?php if ($this->_tpl_vars['calendar']['mail_assigned_event_to_user']): ?>checked="true"<?php endif; ?> />
									    <span id="assigned_event_to_user_info_label" style="padding-top:5px;vertical-align:middle;">An email is sent to the assigned user</span>
                                    </div>
                                    <span style="padding-left:210px;font-style:italic;" id="fill_in_from_mailaddress">FROM_EMAILADDRESS needs to be filled in in config.php</span>
                                    
								</div>
                       
								<input type="hidden" name="calendar_id" value="<?php echo $this->_tpl_vars['calendar']['calendar_id']; ?>
" />
									<div class="form-actions">
										<button id="save-calendar" class="btn btn-primary" name="save-calendar" data-complete-text="Changes saved" data-loading-text="saving..." >Save changes</button>
									</div>

							</form>


							</div>

                    
					<?php elseif ($this->_tpl_vars['active'] == 'settings'): ?>
						<div id="admin-settings" style="padding-top:20px;padding-left:20px;">
							<legend id="admin_settings_legend">Settings</legend>
                            
                            <p id="admin_settings_info_text" style="padding: 0 0 20px 180px;font-style: italic;">
                                These settings will only be applied when someone is logged in, otherwise the settings from config.php will be used.
                            </p>
                            
							<?php if (! empty ( $this->_tpl_vars['save_settings_error'] )): ?>
							<div style="position:absolute;left:400px;top:60px;color:red;font-weight:bold;">
								<?php echo $this->_tpl_vars['save_settings_error']; ?>

							</div>
							<?php endif; ?>

							<?php if (! empty ( $this->_tpl_vars['save_settings_success'] )): ?>
							<div style="position:absolute;left:400px;top:60px;color:green;font-weight:bold;">
								<?php echo $this->_tpl_vars['save_settings_success']; ?>

							</div>
							<?php endif; ?>


							<form action="<?php echo @FULLCAL_URL; ?>
/admin/settings/?action=save_settings" method="post" class="form-horizontal">

								<div class="control-group">
									<label for="admin_settings_default_view" class="control-label" id="admin_settings_defaultview_label">Default calendar view </label>
									<div class="controls">
										<!-- month, basicWeek, agendaWeek, basicDay, agendaDay , agendaList-->
										<select name="default_view">
											<option value="month" <?php if ($this->_tpl_vars['settings']['default_view'] == 'month'): ?>selected="selected"<?php endif; ?>>month</option>
											<option value="agendaWeek" <?php if ($this->_tpl_vars['settings']['default_view'] == 'agendaWeek'): ?>selected="selected"<?php endif; ?>>week</option>
											<option value="agendaDay" <?php if ($this->_tpl_vars['settings']['default_view'] == 'agendaDay'): ?>selected="selected"<?php endif; ?>>day</option>
											<option value="agendaList" <?php if ($this->_tpl_vars['settings']['default_view'] == 'agendaList'): ?>selected="selected"<?php endif; ?>>list</option>
										</select>
									</div>
								</div>
                                <div class="control-group">
									<label for="admin_settings_week_view_type" class="control-label" id="admin_settings_week_view_type_label">Weekview type </label>
									<div class="controls">
										<!-- basicWeek, agendaWeek -->
										<select name="week_view_type">
											<option value="agendaWeek" <?php if ($this->_tpl_vars['settings']['week_view_type'] == 'agendaWeek'): ?>selected="selected"<?php endif; ?>>Agenda week</option>
											<option value="basicWeek" <?php if ($this->_tpl_vars['settings']['week_view_type'] == 'basicWeek'): ?>selected="selected"<?php endif; ?>>Basic week</option>
										</select>
									</div>
								</div>
                                <div class="control-group">
									<label for="admin_settings_day_view_type" class="control-label" id="admin_settings_day_view_type_label">Dayview type </label>
									<div class="controls">
										<!-- basicDay, agendaDay -->
										<select name="day_view_type">
											<option value="agendaDay" <?php if ($this->_tpl_vars['settings']['day_view_type'] == 'agendaDay'): ?>selected="selected"<?php endif; ?>>Agenda day</option>
											<option value="basicDay" <?php if ($this->_tpl_vars['settings']['day_view_type'] == 'basicDay'): ?>selected="selected"<?php endif; ?>>Basic day</option>
										</select>
									</div>
								</div>

								<div class="control-group">
									<label for="admin_settings_language" class="control-label" id="admin_settings_language_label">Language </label>
									<div class="controls">
										<select name="language">
											<?php $_from = $this->_tpl_vars['current_languages']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['current_languages'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['current_languages']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
        $this->_foreach['current_languages']['iteration']++;
?>
                                            <option value="<?php echo $this->_tpl_vars['key']; ?>
" <?php if ($this->_tpl_vars['settings']['language'] == $this->_tpl_vars['key']): ?>selected="selected" checked="checked"<?php endif; ?>><?php echo $this->_tpl_vars['item']; ?>
</option>
                                            <?php endforeach; endif; unset($_from); ?>
                                        </select>
									</div>
								</div>

								<div class="control-group">
									<label for="admin_settings_other_language_label_id" class="control-label" id="admin_settings_other_language_label">Other language </label>
									<div class="controls">
                                        <input type="text" class="input-xlarge" style="width:30px;" name="other_language" value="<?php echo $this->_tpl_vars['settings']['other_language']; ?>
" /> <span id="admin_settings_two_capitals_label">Two capital characters</span> (eg. EN, ES, DE) - <strong>Custom lang**.js is required in script folder</strong>
									</div>
								</div>

                                <div class="control-group" id="admin_settings_show_weeknumbers" style="margin-bottom:1px;">
                                        <div class="controls" style="margin-left:20px;padding-bottom:5px;">
                                                <label id="" class="control-label"></label>
                                                <input type="checkbox" name="show_weeknumbers" <?php if ($this->_tpl_vars['settings']['show_weeknumbers'] == 'on'): ?>checked="checked"<?php endif; ?> />
                                                <span id="admin_show_weeknumbers_checkbox_label" style="padding-top:5px;vertical-align:middle;">Show weeknumbers</span>
                                        </div>
                                </div>
                                
                                <!--<div class="control-group" id="admin_settings_custom_list_button" style="margin-bottom:1px;">
									<div class="controls" style="margin-left:20px;padding-bottom:5px;">
										<label id="" class="control-label"></label>
										<input type="checkbox" name="show_custom_list_button" <?php if ($this->_tpl_vars['settings']['show_custom_list_button'] == 'on'): ?>checked="checked"<?php endif; ?> />
										<span id="admin_show_custom_list_btn_checkbox_label" style="padding-top:5px;vertical-align:middle;">Show custom listview button</span>
									</div>
								</div>-->
                                
                                <div class="control-group" id="admin_settings_show_am_pm" style="margin-bottom:1px;">
                                        <div class="controls" style="margin-left:20px;padding-bottom:5px;">
                                                <label id="" class="control-label"></label>
                                                <input type="checkbox" name="show_am_pm" <?php if ($this->_tpl_vars['settings']['show_am_pm'] == 'on'): ?>checked="checked"<?php endif; ?> />
                                                <span id="admin_show_am_pm_checkbox_label" style="padding-top:5px;vertical-align:middle;">Show AM/PM</span>
                                        </div>
                                </div>
                                <div class="control-group" id="admin_settings_show_notallowed_messages" style="margin-bottom:1px;">
                                        <div class="controls" style="margin-left:20px;padding-bottom:5px;">
                                                <label id="" class="control-label"></label>
                                                <input type="checkbox" name="show_notallowed_messages" <?php if ($this->_tpl_vars['settings']['show_notallowed_messages'] == 'on'): ?>checked="checked"<?php endif; ?> />
                                                <span id="admin_show_notallowed_messages_checkbox_label" style="padding-top:5px;vertical-align:middle;">Show "not allowed" messages</span>
                                        </div>
                                </div>
                                <div class="control-group" id="admin_settings_show_user_filter" style="margin-bottom:1px;">
                                        <div class="controls" style="margin-left:20px;padding-bottom:5px;">
                                                <label id="" class="control-label"></label>
                                                <input type="checkbox" name="show_user_filter" <?php if ($this->_tpl_vars['settings']['show_user_filter'] == 'on'): ?>checked="checked"<?php endif; ?> />
                                                <span id="admin_show_user_filter_checkbox_label" style="padding-top:5px;vertical-align:middle;">Show user filter</span>
                                        </div>
                                </div>
                                <div class="control-group">
									<label for="admin_settings_preview_type" class="control-label" id="admin_settings_mouseover_popup_label">Mouseover popup </label>
									<div class="controls">
										<select name="show_view_type">
											<option value="mouseover" <?php if ($this->_tpl_vars['settings']['show_view_type'] == 'mouseover'): ?>selected="selected"<?php endif; ?>>Mouseover</option>
											<option value="none" <?php if ($this->_tpl_vars['settings']['show_view_type'] == 'none'): ?>selected="selected"<?php endif; ?>>None</option>
											
										</select>
									</div>
								</div>
                                <div class="control-group" id="admin_settings_truncate_title" style="margin-bottom:1px;">
									<div class="controls" style="margin-left:20px;padding-bottom:5px;">
										<label id="" class="control-label"></label>
										<input type="checkbox" name="truncate_title" <?php if ($this->_tpl_vars['settings']['truncate_title'] == 'on'): ?>checked="checked"<?php endif; ?> />
										<span id="admin_truncate_title_checkbox_label" style="padding-top:5px;vertical-align:middle;">Truncate title</span>
									</div>
								</div>
                                <div class="control-group">
									<label for="admin_settings_truncate_length_label_id" class="control-label" id="admin_settings_truncate_length_label">Title length </label>
									<div class="controls">
                                        <input type="text" class="input-xlarge" style="width:30px;" name="truncate_length" value="<?php echo $this->_tpl_vars['settings']['truncate_length']; ?>
" /> <span id="admin_settings_amount_of_characters_label">Amount of characters</span>
									</div>
								</div>
                                
                                                                
                        <hr />        <h4 style="padding:30px 0 10px 180px;font-weight:bold;" id="admin_settings_edit_dialog_label">Edit-dialog:</h4>
                                
                                <div class="control-group">
									<label for="admin_settings_colorpicker_type" class="control-label" id="admin_settings_colorpicker_type_label">Colorpicker type </label>
									<div class="controls">
										<select name="editdialog_colorpicker_type">
											<option value="spectrum" <?php if ($this->_tpl_vars['settings']['editdialog_colorpicker_type'] == 'spectrum'): ?>selected="selected"<?php endif; ?>>Spectrum</option>
											<option value="simple" <?php if ($this->_tpl_vars['settings']['editdialog_colorpicker_type'] == 'simple'): ?>selected="selected"<?php endif; ?>>Simple</option>
											
										</select>
									</div>
								</div>
                                <div class="control-group">
									<label for="admin_settings_timepicker_type" class="control-label" id="admin_settings_timepicker_type_label">Timepicker type </label>
									<div class="controls">
										<select name="editdialog_timepicker_type">
											<option value="ui" <?php if ($this->_tpl_vars['settings']['editdialog_timepicker_type'] == 'ui'): ?>selected="selected"<?php endif; ?>>jQuery UI</option>
											<option value="simple" <?php if ($this->_tpl_vars['settings']['editdialog_timepicker_type'] == 'simple'): ?>selected="selected"<?php endif; ?>>Simple</option>
											
										</select>
									</div>
								</div>
                                
                                
                                
                                <!--<div class="control-group" id="admin_settings_show_description_in_edit_dialog" style="margin-bottom:1px;">
                                    <div class="controls" style="margin-left:20px;padding-bottom:5px;">
                                        <label id="" class="control-label"></label>
                                        <input type="checkbox" name="show_description_field" <?php if ($this->_tpl_vars['settings']['show_description_field'] == 'on'): ?>checked="checked"<?php endif; ?> />
                                        <span id="admin_show_description_in_edit_dialog_checkbox_label" style="padding-top:5px;vertical-align:middle;">Show description field</span>
                                    </div>
                                </div><div class="control-group" id="admin_settings_show_location_in_edit_dialog" style="margin-bottom:1px;">
                                    <div class="controls" style="margin-left:20px;padding-bottom:5px;">
                                        <label id="" class="control-label"></label>
                                        <input type="checkbox" name="show_location_field" <?php if ($this->_tpl_vars['settings']['show_location_field'] == 'on'): ?>checked="checked"<?php endif; ?> />
                                        <span id="admin_show_location_in_edit_dialog_checkbox_label" style="padding-top:5px;vertical-align:middle;">Show location field</span>
                                    </div>
                                </div>
                                <div class="control-group" id="admin_settings_show_phone_in_edit_dialog" style="margin-bottom:1px;">
                                    <div class="controls" style="margin-left:20px;padding-bottom:5px;">
                                        <label id="" class="control-label"></label>
                                        <input type="checkbox" name="show_phone_field" <?php if ($this->_tpl_vars['settings']['show_phone_field'] == 'on'): ?>checked="checked"<?php endif; ?> />
                                        <span id="admin_show_phone_in_edit_dialog_checkbox_label" style="padding-top:5px;vertical-align:middle;">Show phone field</span>
                                    </div>
                                </div>
                                <div class="control-group" id="admin_settings_show_url_in_edit_dialog" style="margin-bottom:1px;">
                                    <div class="controls" style="margin-left:20px;padding-bottom:5px;">
                                        <label id="" class="control-label"></label>
                                        <input type="checkbox" name="show_url_field" <?php if ($this->_tpl_vars['settings']['show_url_field'] == 'on'): ?>checked="checked"<?php endif; ?> />
                                        <span id="admin_show_url_in_edit_dialog_checkbox_label" style="padding-top:5px;vertical-align:middle;">Show url field</span>
                                    </div>
                                </div>-->
                                <div class="control-group" id="admin_settings_show_delete_confirm_dialog" style="margin-bottom:1px;">
                                    <div class="controls" style="margin-left:20px;padding-bottom:5px;">
                                        <label id="" class="control-label"></label>
                                        <input type="checkbox" name="show_delete_confirm_dialog" <?php if ($this->_tpl_vars['settings']['show_delete_confirm_dialog'] == 'on'): ?>checked="checked"<?php endif; ?> />
                                        <span id="admin_show_delete_confirm_dialog_checkbox_label" style="padding-top:5px;vertical-align:middle;">Show confirm dialog when deleting an item</span>
                                    </div>
                                </div>
                                
                                <H5 style="padding:50px 0 10px 180px;font-weight:bold;font-size:14px;">Custom dropdown-field 1</h5>
                                
                               <!-- <div class="control-group" id="show_custom_dropdown_1_dialog" style="margin-bottom:1px;">
                                    <div class="controls" style="margin-left:20px;padding-bottom:5px;">
                                        <label id="" class="control-label"></label>
                                        <input type="checkbox" name="show_custom_dropdown_1" <?php if ($this->_tpl_vars['settings']['show_custom_dropdown_1'] == 'on'): ?>checked="checked"<?php endif; ?> />
                                        <span id="show_custom_dropdown_1_checkbox_label" style="padding-top:5px;vertical-align:middle;">Active</span>
                                    </div>
                                </div>-->
                                
                                <div class="control-group">
                                    <label for="settings_custom_dropdown_1_label" class="control-label"><span id="settings_custom_dropdown_1_label_label">Label</span> </label>
                                    <div class="controls">
                                        <input type="text" class="input-xlarge" id="settings_custom_dropdown_1_label" name="dropdown1_label" value="<?php echo $this->_tpl_vars['settings']['dropdown1_label']; ?>
">
                                    </div>
				</div>
                                <input type="hidden" id="settings_dropdown_1" name="dropdown_1_options" value="<?php echo $this->_tpl_vars['str_dropdown_1']; ?>
" />
					
                                
                                <div class="control-group" id="custom_dropdown_1" style="">
                                    <label for="admin_user_calendar_locations" style="padding-top:35px;" class="control-label"><span id="dropdown_1_options_label">Dropdown items</span> </label>
                                        <div class="controls">
                                                			
                                        <table id="dropdown_1_table" class="table table-striped" style="font-size:14px;width:510px;font-size:13px;margin-bottom:0;">
                                            <thead>
                                                <tr style="">
                                                    <th style="width:50px;border-top:0 none;text-align:left;"><span class="items_label">Items</span></th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            <?php $_from = $this->_tpl_vars['settings']['dropdown_1']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>
                                                <tr>
                                                    <td style="width:50px;border:none;padding:2px;"><input type="text" name="dropdown_1_item" value="<?php echo $this->_tpl_vars['item']['text']; ?>
" data-number="<?php echo $this->_tpl_vars['item']['option_id']; ?>
" class="settings-dropdown_1_label" id="settings-dropdown-1-option-<?php echo $this->_tpl_vars['item']['option_id']; ?>
" /></td>
                                                    <td style="width:50px;border:none;padding:2px;"><input type="text" class="input-xlarge admin-spectrum-colorpicker-dropdown_1" id="admin-spectrum-colorpicker-dropdown_1-<?php echo $this->_tpl_vars['item']['option_id']; ?>
" name="dropdown_1_color" value="<?php echo $this->_tpl_vars['item']['color']; ?>
" data-title="<?php echo $this->_tpl_vars['item']['text']; ?>
" data-number="<?php echo $this->_tpl_vars['item']['option_id']; ?>
"></td>
                                                </tr>
                                               
                                            <?php endforeach; endif; unset($_from); ?>

                                            </tbody>
                                        </table>
                                       <input type="button" id="add_dropdown_1_option_field" value="Add an item" />
                                    </div>
				</div>
                                <div class="control-group" id="admin_settings_show_custom_dropdown1_filter" style="margin-bottom:1px;">
                                    <div class="controls" style="margin-left:20px;padding-bottom:5px;">
                                        <label id="" class="control-label"></label>
                                        <input type="checkbox" name="show_custom_dropdown1_filter" <?php if ($this->_tpl_vars['settings']['show_custom_dropdown1_filter'] == 'on'): ?>checked="checked"<?php endif; ?> />
                                        <span id="admin_show_custom_dropdown1_filter_checkbox_label" style="padding-top:5px;vertical-align:middle;">Show filter</span>
                                    </div>
                                </div> 
                                
                                <br />
                                <H5 style="padding:50px 0 10px 180px;font-weight:bold;font-size:14px;">Custom dropdown-field 2</h5>
                                
                                
                                <div class="control-group">
                                    <label for="settings_custom_dropdown_2_label" class="control-label"><span id="settings_custom_dropdown_2_label_label">Label</span> </label>
                                    <div class="controls">
                                        <input type="text" class="input-xlarge" id="settings_custom_dropdown_2_label" name="dropdown2_label" value="<?php echo $this->_tpl_vars['settings']['dropdown2_label']; ?>
">
                                    </div>
				</div>
                                <input type="hidden" id="settings_dropdown_2" name="dropdown_2_options" value="<?php echo $this->_tpl_vars['str_dropdown_2']; ?>
" />
					
                               
                                <div class="control-group" id="custom_dropdown_2" style="">
                                    <label for="admin_user_calendar_locations" style="padding-top:35px;" class="control-label"><span id="dropdown_2_options_label">Dropdown items</span> </label>
                                        <div class="controls">
                                                			
                                        <table id="dropdown_2_table" class="table table-striped" style="font-size:14px;width:510px;font-size:13px;margin-bottom:0;">
                                            <thead>
                                                <tr style="">
                                                    <th style="width:50px;border-top:0 none;text-align:left;"><span class="items_label">Items</span></th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            <?php $_from = $this->_tpl_vars['settings']['dropdown_2']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>
                                                <tr>
                                                    <td style="width:50px;border:none;padding:2px;"><input type="text" name="dropdown_2_item" value="<?php echo $this->_tpl_vars['item']['text']; ?>
" data-number="<?php echo $this->_tpl_vars['item']['option_id']; ?>
" class="settings-dropdown_2_label" id="settings-dropdown-2-option-<?php echo $this->_tpl_vars['item']['option_id']; ?>
" /></td>
                                                    <td style="width:50px;border:none;padding:2px;"><input type="text" class="input-xlarge admin-spectrum-colorpicker-dropdown_2" id="admin-spectrum-colorpicker-dropdown_2-<?php echo $this->_tpl_vars['item']['option_id']; ?>
" name="dropdown_2_color" value="<?php echo $this->_tpl_vars['item']['color']; ?>
" data-title="<?php echo $this->_tpl_vars['item']['text']; ?>
" data-number="<?php echo $this->_tpl_vars['item']['option_id']; ?>
"></td>
                                                </tr>
                                               
                                            <?php endforeach; endif; unset($_from); ?>

                                            </tbody>
                                        </table>
                                       <input type="button" id="add_dropdown_2_option_field" value="Add an item" />
                                    </div>
				</div>
                                
                                <div class="control-group" id="admin_settings_show_custom_dropdown2_filter" style="margin-bottom:1px;">
                                    <div class="controls" style="margin-left:20px;padding-bottom:5px;">
                                        <label id="" class="control-label"></label>
                                        <input type="checkbox" name="show_custom_dropdown2_filter" <?php if ($this->_tpl_vars['settings']['show_custom_dropdown2_filter'] == 'on'): ?>checked="checked"<?php endif; ?> />
                                        <span id="admin_show_custom_dropdown2_filter_checkbox_label" style="padding-top:5px;vertical-align:middle;">Show filter</span>
                                    </div>
                                </div> 
                                
                                <H5 style="padding:50px 0 10px 180px;font-weight:bold;font-size:14px;">Method of filtering the custom dropdowns</h5>
                                
                                <div class="control-group" id="admin_settings_dropdowns_are_linked" style="margin-bottom:1px;">
                                    <div class="controls" style="margin-left:20px;padding-bottom:5px;">
                                        <label id="" class="control-label"></label>
                                        <input type="checkbox" name="dropdowns_are_linked" <?php if ($this->_tpl_vars['settings']['dropdowns_are_linked'] == 'on'): ?>checked="checked"<?php endif; ?> />
                                        <span id="admin_dropdowns_are_linked_checkbox_label" style="padding-top:5px;vertical-align:middle;">Dropdowns are linked ('AND' condition, events are visible where A1 AND B1 are set )</span>
                                        
                                    </div>
                                </div> 
                                
                        <hr />        <h4 style="padding:30px 0 10px 180px;font-weight:bold;" id="admin_settings_pdf_export_label">PDF export:</h4>
                                
                                <div class="control-group" id="admin_settings_pdf_table_look" style="margin-bottom:1px;">
                                    <div class="controls" style="margin-left:20px;padding-bottom:5px;">
                                        <label id="" class="control-label"></label>
                                        <input type="checkbox" name="pdf_table_look" <?php if ($this->_tpl_vars['settings']['pdf_table_look'] == 'on'): ?>checked="checked"<?php endif; ?> />
                                        <span id="admin_pdf_table_look_checkbox_label" style="padding-top:5px;vertical-align:middle;">Table-look</span>
                                    </div>
                                </div>
                                <div class="control-group" id="admin_settings_pdf_show_time_columns" style="margin-bottom:1px;">
                                    <div class="controls" style="margin-left:20px;padding-bottom:5px;">
                                        <label id="" class="control-label"></label>
                                        <input type="checkbox" name="pdf_show_time_columns" <?php if (! isset ( $this->_tpl_vars['settings']['pdf_show_time_columns'] ) || $this->_tpl_vars['settings']['pdf_show_time_columns'] == 'on'): ?>checked="checked"<?php endif; ?> />
                                        <span id="admin_pdf_show_time_columns_checkbox_label" style="padding-top:5px;vertical-align:middle;">Show the time columns</span>
                                    </div>
                                </div>
                                <div class="control-group" id="admin_settings_pdf_show_date_on_every_line" style="margin-bottom:1px;">
                                    <div class="controls" style="margin-left:20px;padding-bottom:5px;">
                                        <label id="" class="control-label"></label>
                                        <input type="checkbox" name="pdf_show_date_on_every_line" <?php if ($this->_tpl_vars['settings']['pdf_show_date_on_every_line'] == 'on'): ?>checked="checked"<?php endif; ?> />
                                        <span id="admin_pdf_show_date_on_every_line_checkbox_label" style="padding-top:5px;vertical-align:middle;">Show date on every line</span>
                                    </div>
                                </div>
                                <div class="control-group" id="admin_settings_pdf_show_logo" style="margin-bottom:1px;">
                                    <div class="controls" style="margin-left:20px;padding-bottom:5px;">
                                        <label id="" class="control-label"></label>
                                        <input type="checkbox" name="pdf_show_logo" <?php if (! isset ( $this->_tpl_vars['settings']['pdf_show_logo'] ) || $this->_tpl_vars['settings']['pdf_show_logo'] == 'on'): ?>checked="checked"<?php endif; ?> />
                                        <span id="admin_pdf_show_logo_checkbox_label" style="padding-top:5px;vertical-align:middle;">Show your logo</span>
                                    </div>
                                </div>
                                <div class="control-group" id="admin_settings_pdf_show_custom_dropdown_values" style="margin-bottom:1px;">
                                    <div class="controls" style="margin-left:20px;padding-bottom:5px;">
                                        <label id="" class="control-label"></label>
                                        <input type="checkbox" name="pdf_show_custom_dropdown_values" <?php if (! isset ( $this->_tpl_vars['settings']['pdf_show_custom_dropdown_values'] ) || $this->_tpl_vars['settings']['pdf_show_custom_dropdown_values'] == 'on'): ?>checked="checked"<?php endif; ?> />
                                        <span id="admin_pdf_show_custom_dropdown_values_checkbox_label" style="padding-top:5px;vertical-align:middle;">Show the custom dropdown values</span>
                                    </div>
                                </div>
                                <div class="control-group" id="admin_settings_pdf_show_calendarname_each_line" style="margin-bottom:1px;">
                                    <div class="controls" style="margin-left:20px;padding-bottom:5px;">
                                        <label id="" class="control-label"></label>
                                        <input type="checkbox" name="pdf_show_calendarname_each_line" <?php if (! isset ( $this->_tpl_vars['settings']['pdf_show_calendarname_each_line'] ) || $this->_tpl_vars['settings']['pdf_show_calendarname_each_line'] == 'on'): ?>checked="checked"<?php endif; ?> />
                                        <span id="admin_pdf_show_calendarname_each_line_checkbox_label" style="padding-top:5px;vertical-align:middle;">Show calendar name on each line</span>
                                    </div>
                                </div>
                                <div class="control-group" id="admin_settings_pdf_fontweight_bold" style="margin-bottom:1px;">
                                    <div class="controls" style="margin-left:20px;padding-bottom:5px;">
                                        <label id="" class="control-label"></label>
                                        <input type="checkbox" name="pdf_fontweight_bold" <?php if ($this->_tpl_vars['settings']['pdf_fontweight_bold'] == 'on'): ?>checked="checked"<?php endif; ?> />
                                        <span id="admin_pdf_fontweight_bold_checkbox_label" style="padding-top:5px;vertical-align:middle;">Font-weight: bold</span>
                                    </div>
                                </div>
                                <div class="control-group" id="admin_settings_pdf_colored_rows" style="margin-bottom:1px;">
                                    <div class="controls" style="margin-left:20px;padding-bottom:5px;">
                                        <label id="" class="control-label"></label>
                                        <input type="checkbox" name="pdf_colored_rows" <?php if ($this->_tpl_vars['settings']['pdf_colored_rows'] == 'on'): ?>checked="checked"<?php endif; ?> />
                                        <span id="admin_pdf_colored_rows_checkbox_label" style="padding-top:5px;vertical-align:middle;">Row colors gray/white</span>
                                    </div>
                                </div>
                                <div class="control-group" id="admin_settings_pdf_sorting" style="margin-bottom:1px;">
                                    <div class="controls" style="margin-left:20px;padding-bottom:5px;">
                                        <label id="" class="control-label"></label>
                                        <input type="checkbox" name="pdf_sorting" <?php if ($this->_tpl_vars['settings']['pdf_sorting'] == 'on'): ?>checked="checked"<?php endif; ?> />
                                        <span id="admin_pdf_sorting_checkbox_label" style="padding-top:5px;vertical-align:middle;">Sort by calendar order</span>
                                    </div>
                                </div>
                                
                                <div id="admin_pdf_pagination_translation" style="padding:10px 0 10px 200px;font-weight:bold;">Pagination translation</div>
                                <div class="control-group">
                                    <div style="padding-left:230px;"><span class="admin_label_page">Page</span>&nbsp;&nbsp;&nbsp; 1 &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;<span class="admin_label_of">of</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 3</div>
                                    <div class="controls">
										<input type="text" class="input-xlarge" style="width:50px;" name="lang_page" value="<?php echo $this->_tpl_vars['settings']['lang_page']; ?>
" />
                                        &nbsp;&nbsp;&nbsp;
                                        <input type="text" class="input-xlarge" style="width:40px;" name="lang_of" value="<?php echo $this->_tpl_vars['settings']['lang_of']; ?>
" />
                                        
                                    </div>
								</div>
                                <div id="admin_pdf_column_names" style="padding:10px 0 10px 200px;font-weight:bold;">Table column names</div>
                                <div class="control-group">
                                    <div style="padding-left:210px;"><span class="admin_label_date_column">Date column</span> | <span class="admin_label_start_time_column">Start time column</span> | <span class="admin_label_end_time_column">End time column</span> | <span class="admin_label_event_title_column">Event title column</span></div>
                                    <div class="controls">
										<input type="text" class="input-xlarge" style="width:90px;" name="date_header" value="<?php echo $this->_tpl_vars['settings']['date_header']; ?>
" />
                                        <input type="text" class="input-xlarge" style="width:90px;" name="starttime_header" value="<?php echo $this->_tpl_vars['settings']['starttime_header']; ?>
" />
                                        <input type="text" class="input-xlarge" style="width:90px;" name="endtime_header" value="<?php echo $this->_tpl_vars['settings']['endtime_header']; ?>
" />
                                        <input type="text" class="input-xlarge" style="width:90px;" name="title_header" value="<?php echo $this->_tpl_vars['settings']['title_header']; ?>
" />
                                    </div>
								</div>
                                
                                <h4 style="padding:50px 0 10px 180px;font-weight:bold;" id="admin_settings_hour_calculation_label">Hour calculation:</h4>
                                
                                <div class="control-group">
									<label for="admin_settings_workday_hours_label_id" class="control-label" id="admin_settings_workday_hours_label">Workday hours </label>
									<div class="controls">
                                        <input type="text" class="input-xlarge" style="width:30px;" name="hourcalculation_workday_hours" value="<?php echo $this->_tpl_vars['settings']['hourcalculation_workday_hours']; ?>
" /> <span id="admin_settings_amount_of_hours_label">Amount of hours in a workday</span>
									</div>
								</div>
                                <div class="control-group">
									<label for="admin_settings_default_period_label_id" class="control-label" id="admin_settings_default_period_label">Default period </label>
									<div class="controls">
										<input type="text" class="input-xlarge" style="width:30px;" name="hourcalculation_default_period" value="<?php echo $this->_tpl_vars['settings']['hourcalculation_default_period']; ?>
" /> <span id="admin_settings_initial_period_label">Initial period in months</span>
									</div>
								</div>
                                
                                
                                <!--<div class="control-group" id="admin_settings_users_can_register" style="margin-bottom:1px;">
                                    <div class="controls" style="margin-left:20px;padding-bottom:5px;">
                                        <label id="" class="control-label"></label>
                                        <input type="checkbox" name="users_can_register" <?php if ($this->_tpl_vars['settings']['users_can_register'] == 'on'): ?>checked="checked"<?php endif; ?> />
                                        <span id="admin_users_can_register_checkbox_label" style="padding-top:5px;vertical-align:middle;">Users can register</span>
                                    </div>
                                </div>-->
                               
                        
                        <h4 style="padding:50px 0 10px 180px;font-weight:bold;" id="admin_settings_registration_label">Registration:</h4>
                                <p id="admin_settings_registration_info_label" style="padding-left: 180px;font-style: italic;">
                                    USERS_CAN_REGISTER can be set in config.php
                                </p>
                                <div class="control-group" id="admin_settings_send_activation_mail" style="margin-bottom:1px;">
                                    <div class="controls" style="margin-left:20px;padding-bottom:5px;">
                                        <label id="" class="control-label"></label>
                                        <input type="checkbox" name="send_activation_mail" <?php if ($this->_tpl_vars['settings']['send_activation_mail'] == 'on'): ?>checked="checked"<?php endif; ?> />
                                        <span id="admin_send_activation_mail_checkbox_label" style="padding-top:5px;vertical-align:middle;">Send activation mail</span>
                                    </div>
                                </div>
                                
                                
                                
                                            <input type="hidden" name="user_id" value="<?php echo $this->_tpl_vars['user_id']; ?>
" />
                                            <div class="form-actions">
                                                    <button id="save-settings" class="btn btn-primary" name="save-settings" data-complete-text="Changes saved" data-loading-text="saving..." >Save changes</button>
                                            </div>
                                          

                                    </form>
                            </div>

					<?php elseif ($this->_tpl_vars['active'] == 'lists'): ?>
						<div id="admin-lists"  style="padding-top:20px;padding-left:20px;">
								<?php if (! empty ( $this->_tpl_vars['error'] )): ?>
								<div style="position:absolute;left:400px;top:60px;color:red;font-weight:bold;">
									<?php echo $this->_tpl_vars['error']; ?>

								</div>
								<?php endif; ?>

								<?php if (! empty ( $this->_tpl_vars['msg'] )): ?>
								<div style="position:absolute;left:400px;top:60px;color:green;font-weight:bold;">
									<?php echo $this->_tpl_vars['msg']; ?>

								</div>
								<?php endif; ?>

                                <span style="float:right;padding-top: 17px;" id="lists_to_excel_btn" class="not_print">
                                    <span class="dashboard_btn not_print">
                                        <i class="icon-th"></i> To Excel
                                    </span>
                                </span>	
                                
                                <form id="calendars-form" action="<?php echo @FULLCAL_URL; ?>
/admin/calendars/?action=new_calendar" method="post" class="form-horizontal">
                                        <?php if (isset ( $_SESSION['add_calendar_error'] )): ?>
                                        <div style="position:absolute;left:400px;color:red;font-weight:bold;">
                                                <?php echo $_SESSION['add_calendar_error']; ?>

                                        </div>
                                        <?php endif; ?>

                                </form>

                                <legend id="admin_settings_hour_calculation_legend">Hour calculation</legend>
								
								
                                <div class="control-group" style="padding: 20px 0 30px 0;">
									<span class="control-label" id="admin_settings_calendars_label" style="width:auto;padding-right:5px;">Calendar </span>
                                    <select id="calendar_selectbox" name="calendar" style="width:150px;margin-bottom: 0;">
                                        <option class="calendar_option" value="all" <?php if ($this->_tpl_vars['selected_calendar'] == 'all'): ?>selected="selected"<?php endif; ?>>All</option>
                                        <?php $_from = $this->_tpl_vars['calendars']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>
                                            <option class="calendar_option" value="<?php echo $this->_tpl_vars['item']['calendar_id']; ?>
" <?php if ($this->_tpl_vars['selected_calendar'] == $this->_tpl_vars['item']['calendar_id']): ?>selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['item']['name']; ?>
</option>
                                        <?php endforeach; endif; unset($_from); ?>
                                    </select>


                                    <span id="month_label_id" style="padding-left:30px;margin-bottom: 0;">From: </span>
                                        <input type="text" id="hourcalc_datepicker_startdate" style="font-size:13px;margin-bottom: 0;width: 80px;padding:3px;z-index:9999;">
                                    <span id="time_label_id">To: </span>
                                        <input type="text" id="hourcalc_datepicker_enddate" style="font-size:13px;margin-bottom: 0;width: 80px;padding:3px;" />

                                    <button id="dates_clear_button" style="padding:3px 12px;" class="btn btn-secondary" name="clear-list" data-complete-text="fields cleared" data-loading-text="saving..." >Clear</button> 
                                    <button id="dates_refresh_button" style="float:right;margin-left:50px;padding:3px 12px;" class="btn btn-primary" name="refresh-list" data-complete-text="Changes saved" data-loading-text="saving..." >Refresh</button> 
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

										<?php $_from = $this->_tpl_vars['list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>

											<tr>
												<td><?php echo $this->_tpl_vars['item']['fullname']; ?>
 <?php if ($this->_tpl_vars['item']['superadmin']): ?><span class="label label-important">superadmin</span><?php elseif ($this->_tpl_vars['item']['admin']): ?><span class="label label-important">admin</span><?php endif; ?></td>
												<td><?php echo $this->_tpl_vars['item']['days']; ?>
</td>
												<td><?php echo $this->_tpl_vars['item']['hours']; ?>
</td>
                                                <td class="not_print" style="border-left:1px solid #DDDDDD;width:10px;"><a href="<?php echo @FULLCAL_URL; ?>
/admin/lists?action=get_list&uid=<?php echo $this->_tpl_vars['item']['user_id']; ?>
<?php if (isset ( $_GET['cid'] ) && ! empty ( $_GET['cid'] )): ?>&cid=<?php echo $_GET['cid']; ?>
<?php endif; ?>"><img src="<?php echo @FULLCAL_URL; ?>
/images/view.png" /></a></td>
												
												
											</tr>
										<?php endforeach; else: ?>
											<tr>
												<td>No rows found</td>
												<td>&nbsp;</td>
												<td>&nbsp;</td>
												<td>&nbsp;</td>
											</tr>
										<?php endif; unset($_from); ?>

										</tbody>
									</table>

								<!--	<div class="pagination"><ul><li class="prev disabled"><a href="<?php echo @FULLCAL_URL; ?>
/admin/calendars?to=<?php echo $this->_tpl_vars['from']; ?>
"> Previous</a></li><li class="active"><a href="#">1</a></li><li class="next disabled"><a href="#">Next </a></li></ul></div>	</div>-->


							</div>
					<?php elseif ($this->_tpl_vars['active'] == 'list'): ?>
						<div id="admin-list"  style="padding-top:20px;padding-left:20px;">
								<?php if (! empty ( $this->_tpl_vars['error'] )): ?>
								<div style="position:absolute;left:400px;top:60px;color:red;font-weight:bold;">
									<?php echo $this->_tpl_vars['error']; ?>

								</div>
								<?php endif; ?>

								<?php if (! empty ( $this->_tpl_vars['msg'] )): ?>
								<div style="position:absolute;left:400px;top:60px;color:green;font-weight:bold;">
									<?php echo $this->_tpl_vars['msg']; ?>

								</div>
								<?php endif; ?>

								
								<!--<form id="calendars-form" action="" method="post" class="form-horizontal">-->
									<?php if (isset ( $_SESSION['add_calendar_error'] )): ?>
									<div style="position:absolute;left:400px;color:red;font-weight:bold;">
										<?php echo $_SESSION['add_calendar_error']; ?>

									</div>
									<?php endif; ?>

                                    <span style="float:right;" id="list_to_excel_btn">
                                        <span class="dashboard_btn">
                                            <i class="icon-th"></i> To Excel
                                        </span>
                                    </span>	
									
								
                                    <legend><span id="admin_settings_user_hour_calculation_legend">Hour calculation of</span> <strong><?php echo $this->_tpl_vars['user']['title']; ?>
 <?php echo $this->_tpl_vars['user']['lastname']; ?>
, <?php echo $this->_tpl_vars['user']['firstname']; ?>
 <?php echo $this->_tpl_vars['user']['infix']; ?>
</strong></legend>
								
								<div class="control-group" style="padding: 20px 0 30px 0;">
									<span class="control-label" id="admin_settings_calendars_label" style="width:auto;padding-right:5px;">Calendar </span>
                                    <select id="calendar_selectbox" name="calendar" style="width:150px;margin-bottom: 0;">
                                        <option class="calendar_option" value="all" <?php if ($this->_tpl_vars['selected_calendar'] == 'all'): ?>selected="selected"<?php endif; ?>>All</option>
                                        <?php $_from = $this->_tpl_vars['calendars']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>
                                            <option class="calendar_option" value="<?php echo $this->_tpl_vars['item']['calendar_id']; ?>
" <?php if ($this->_tpl_vars['selected_calendar'] == $this->_tpl_vars['item']['calendar_id']): ?>selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['item']['name']; ?>
</option>
                                        <?php endforeach; endif; unset($_from); ?>
                                    </select>


                                    <span id="month_label_id" style="padding-left:30px;margin-bottom: 0;">From: </span>
                                        <input type="text" id="hourcalc_datepicker_startdate" style="font-size:13px;margin-bottom: 0;width: 80px;padding:3px;z-index:9999;">
                                    <span id="time_label_id">To: </span>
                                        <input type="text" id="hourcalc_datepicker_enddate" style="font-size:13px;margin-bottom: 0;width: 80px;padding:3px;" />

                                    <button id="dates_clear_button" style="padding:3px 12px;" class="btn btn-secondary" name="clear-list" data-complete-text="fields cleared" data-loading-text="saving..." >Clear</button> 
                                    <button id="user_dates_refresh_button" style="float:right;margin-left:50px;padding:3px 12px;" class="btn btn-primary" name="refresh-list" data-complete-text="Changes saved" data-loading-text="saving..." >Refresh</button> 
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

										<?php $_from = $this->_tpl_vars['list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>

											<tr>
												<td style="width:190px;"><?php echo $this->_tpl_vars['item']['date_start']; ?>
<?php if ($this->_tpl_vars['item']['date_end'] != $this->_tpl_vars['item']['date_start']): ?> - <?php echo $this->_tpl_vars['item']['date_end']; ?>
<?php endif; ?></td>
												<td><?php if ($this->_tpl_vars['item']['allDay']): ?>allday<?php else: ?><?php echo $this->_tpl_vars['item']['time_start']; ?>
 - <?php echo $this->_tpl_vars['item']['time_end']; ?>
<?php endif; ?></td>
												<td><?php echo $this->_tpl_vars['item']['days']; ?>
</td>
												<td><?php echo $this->_tpl_vars['item']['hours']; ?>
</td>
												<td><?php echo $this->_tpl_vars['item']['name']; ?>
</td>
											</tr>
										<?php endforeach; endif; unset($_from); ?>
										
										<tr style="border-top:2px solid #333333;">
											<td>Total</td>
											<td>&nbsp;</td>
											<td><?php echo $this->_tpl_vars['total_day_count']; ?>
</td>
											<td><?php echo $this->_tpl_vars['total_hour_count']; ?>
</td>
											<td>&nbsp;</td>
										</tr>
											
										</tbody>
									</table>

								<!--	<div class="pagination"><ul><li class="prev disabled"><a href="<?php echo @FULLCAL_URL; ?>
/admin/calendars?to=<?php echo $this->_tpl_vars['from']; ?>
"> Previous</a></li><li class="active"><a href="#">1</a></li><li class="next disabled"><a href="#">Next </a></li></ul></div>	</div>-->


							</div>
					
                    
					<?php endif; ?>

				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript">
            MyCalendar.datePickerDateFormat = '<?php echo @DATEPICKER_DATEFORMAT; ?>
';
            MyCalendar.last_dditem = '';
            MyCalendar.dditem_string = '';
            MyCalendar.last_location = '';
            MyCalendar.location_string = '';
            MyCalendar.last_dropdown_1_option = '';
            MyCalendar.last_dropdown_2_option = '';

            MyCalendar.timePickerMinHour 			= <?php echo @MINHOUR; ?>
;
            MyCalendar.timePickerMaxHour 			= <?php echo @MAXHOUR; ?>
;
            MyCalendar.timePickerMinuteInterval 	= <?php echo @MINUTE_INTERVAL; ?>
;
            MyCalendar.showAMPM						= <?php if ($this->_tpl_vars['settings']['show_am_pm'] == 'on'): ?>true<?php else: ?>false<?php endif; ?>;
	
        $('#calendar_dditems').val('<?php echo $this->_tpl_vars['str_dditems']; ?>
');
        $('#settings_dropdown_1').val('<?php echo $this->_tpl_vars['str_dropdown_1']; ?>
');
        $('#settings_dropdown_2').val('<?php echo $this->_tpl_vars['str_dropdown_2']; ?>
');
    
	var current_user = '<?php if (isset ( $this->_tpl_vars['user'] ) && isset ( $this->_tpl_vars['user']['user_id'] )): ?><?php echo $this->_tpl_vars['user']['user_id']; ?>
<?php endif; ?>';
		
	$(document).ready(function() {
            MyCalendar.last_dditem = 1000000;
            MyCalendar.last_dropdown_1_option = 1000000;
            MyCalendar.last_dropdown_2_option = 1000000;
            MyCalendar.last_location = '<?php echo $this->_tpl_vars['last_location']; ?>
';
            
            $('#hourcalc_datepicker_startdate').val('<?php echo $this->_tpl_vars['startdate']; ?>
');
            $('#hourcalc_datepicker_enddate').val('<?php echo $this->_tpl_vars['enddate']; ?>
');
           
            
            $('#active_period_datepicker_startdate').val('<?php echo $this->_tpl_vars['calendar']['cal_startdate']; ?>
');
            $('#active_period_datepicker_enddate').val('<?php echo $this->_tpl_vars['calendar']['cal_enddate']; ?>
');
            
            $('#alterable_period_datepicker_startdate').val('<?php echo $this->_tpl_vars['calendar']['alterable_startdate']; ?>
');
            $('#alterable_period_datepicker_enddate').val('<?php echo $this->_tpl_vars['calendar']['alterable_enddate']; ?>
');
            
            $('.lists_calendar_option').click(function(t) {
                    location.href = '<?php echo @FULLCAL_URL; ?>
/admin/lists/?cid='+$(this).val();
            });
            
            $('.calendar_option').click(function(t) {
                    //location.href = '<?php echo @FULLCAL_URL; ?>
/admin/lists/?action=get_list&uid='+current_user+'&cid='+$(this).val();
            });
			
            
            $('#dates_clear_button').click(function(t) {
                $('#hourcalc_datepicker_startdate').val('');
                $('#hourcalc_datepicker_enddate').val('');
                
            });
            $('#dates_refresh_button').click(function(t) {
				var startdate = $('#hourcalc_datepicker_startdate').val();
                var enddate = $('#hourcalc_datepicker_enddate').val();
                var selected_cal = $('#calendar_selectbox').val();
                location.href = '<?php echo @FULLCAL_URL; ?>
/admin/lists/?cid='+selected_cal+'&st='+startdate+'&end='+enddate;
			});
            
             $('#user_dates_refresh_button').click(function(t) {
				var startdate = $('#hourcalc_datepicker_startdate').val();
                var enddate = $('#hourcalc_datepicker_enddate').val();
                var selected_cal = $('#calendar_selectbox').val();
                location.href = '<?php echo @FULLCAL_URL; ?>
/admin/lists/?action=get_list&uid='+current_user+'&cid='+selected_cal+'&st='+startdate+'&end='+enddate;
			});
            
            $('input[name="active"]').change(function(t) {
                if($(this).val() == 'period') {
                    $('#active_period_datepicker_startdate').prop('disabled', false);
                    $('#active_period_datepicker_enddate').prop('disabled', false);
                } else {
                    $('#active_period_datepicker_startdate').prop('disabled', true);
                    $('#active_period_datepicker_enddate').prop('disabled', true);
                }
                
            });
            
            $('input[name="locationfield"]').change(function(t) {
                if($(this).val() == 'combo') {
                    $('#locations').show();
                    
                } else {
                    $('#locations').hide();
                   
                }
                
            });
            
            $('#admin_calendar_origin').change(function(t) {
                if($(this).val() == 'exchange') {
                    // show groups combo
                    $('#exchange_username_field').show();
                    $('#exchange_password_field').show();
                    $('#exchange_token_field').show();
                    $('#admin_exchange_extra_secure').show();
                } else {
                    $('#exchange_username_field').hide();
                    $('#exchange_password_field').hide();
                    $('#exchange_token_field').hide();
                    $('#admin_exchange_extra_secure').hide();
                }
            });
            
            $('#exchange_extra_secure_checkbox').click(function(t,b) {
                if($(this).is(':checked')) {
                    $('#exchange_token').removeAttr('disabled');
                } else {
                    $('#exchange_token').prop('disabled', true);
                }
            });
            
            $('#admin_calendar_share_type').change(function(t) {
                if($(this).val() == 'private') {
                    // disable checkboxes
                    $('#admin_calendar_can_add').prop('disabled', true);
                    $('#admin_calendar_can_edit').prop('disabled', true);
                    $('#admin_calendar_can_delete').prop('disabled', true);
                    $('#admin_calendar_can_change_color').prop('disabled', true);
                    
                    $('#admin_calendar_can_add').attr('checked', false);
                    $('#admin_calendar_can_edit').attr('checked', false);
                    $('#admin_calendar_can_delete').attr('checked', false);
                    $('#admin_calendar_can_change_color').attr('checked', false);
                } else {
                    // enable checkboxes
                    $('#admin_calendar_can_add').prop('disabled', false);
                    $('#admin_calendar_can_edit').prop('disabled', false);
                    $('#admin_calendar_can_delete').prop('disabled', false);
                    $('#admin_calendar_can_change_color').prop('disabled', false);
                }
                if($(this).val() == 'private_group') {
                    $('#admin_calendar_can_add_label').text(Lang.Calendar.LabelGroup +' '+ Lang.Calendar.LabelCanAdd);
                    $('#admin_calendar_can_edit_label').text(Lang.Calendar.LabelGroup +' '+ Lang.Calendar.LabelCanEdit);
                    $('#admin_calendar_can_delete_label').text(Lang.Calendar.LabelGroup +' '+ Lang.Calendar.LabelCanDelete);
                    $('#admin_calendar_can_change_color_label').text(Lang.Calendar.LabelGroup +' '+ Lang.Calendar.LabelCanChangeColor);
                    $('#admin_calendar_can_view_label').text(Lang.Calendar.LabelEverybody +' '+ Lang.Calendar.LabelCanView);
                    
                    
                    // show groups combo
                    $('#admin_calendar_group_combo').show();
                    $('#admin_others_can_view').show();
                } else {
                    $('#admin_calendar_can_add_label').text(Lang.Calendar.LabelOthers +' '+ Lang.Calendar.LabelCanAdd);
                    $('#admin_calendar_can_edit_label').text(Lang.Calendar.LabelOthers +' '+ Lang.Calendar.LabelCanEdit);
                    $('#admin_calendar_can_delete_label').text(Lang.Calendar.LabelOthers +' '+ Lang.Calendar.LabelCanDelete);
                    $('#admin_calendar_can_change_color_label').text(Lang.Calendar.LabelOthers +' '+ Lang.Calendar.LabelCanChangeColor);
                    
                    // hide groups combo
                    $('#admin_calendar_group_combo').hide();
                    $('#admin_others_can_view').hide();
                }
            });
      
            <?php if ($this->_tpl_vars['calendar']['share_type'] == 'private_group'): ?>
                $('#admin_calendar_can_add_label').text(Lang.Calendar.LabelGroup +' '+ Lang.Calendar.LabelCanAdd);
                $('#admin_calendar_can_edit_label').text(Lang.Calendar.LabelGroup +' '+ Lang.Calendar.LabelCanEdit);
                $('#admin_calendar_can_delete_label').text(Lang.Calendar.LabelGroup +' '+ Lang.Calendar.LabelCanDelete);
                $('#admin_calendar_can_change_color_label').text(Lang.Calendar.LabelGroup +' '+ Lang.Calendar.LabelCanChangeColor);
                $('#admin_calendar_can_view_label').text(Lang.Calendar.LabelEverybody +' '+ Lang.Calendar.LabelCanView);
                    
                <?php else: ?>
                $('#admin_calendar_can_add_label').text(Lang.Calendar.LabelOthers +' '+ Lang.Calendar.LabelCanAdd);
                $('#admin_calendar_can_edit_label').text(Lang.Calendar.LabelOthers +' '+ Lang.Calendar.LabelCanEdit);
                $('#admin_calendar_can_delete_label').text(Lang.Calendar.LabelOthers +' '+ Lang.Calendar.LabelCanDelete);
                $('#admin_calendar_can_change_color_label').text(Lang.Calendar.LabelOthers +' '+ Lang.Calendar.LabelCanChangeColor);
                    
            <?php endif; ?>
            
            <?php if ($this->_tpl_vars['active'] == 'availability'): ?>
            
            var updateTimes = function(arrMinutes, me) {console.log(arrMinutes[0]);
                var hours1 = Math.floor(arrMinutes[0] / 60);console.log(hours1);
                var minutes1 = arrMinutes[0] - (hours1 * 60);

                if (hours1.length == 1) hours1 = '0' + hours1;
                if (minutes1.length == 1) minutes1 = '0' + minutes1;
                if (minutes1 == 0) minutes1 = '00';
                if (hours1 >= 12) {console.log(hours1);
                    if (hours1 == 12) {
                        
                        minutes1 = minutes1 + " PM";
                    } else {
                        hours1 = hours1 - 12;
                        minutes1 = minutes1 + " PM";
                    }
                } else {
                    
                    minutes1 = minutes1 + " AM";
                }
                if (hours1 == 0) {
                    hours1 = 12;
                    minutes1 = minutes1;
                }


                $('#'+me.parent()[0].className+'-1').html(hours1 + ':' + minutes1);

                var hours2 = Math.floor(arrMinutes[1] / 60);
                var minutes2 = arrMinutes[1] - (hours2 * 60);

                if (hours2.length == 1) hours2 = '0' + hours2;
                if (minutes2.length == 1) minutes2 = '0' + minutes2;
                if (minutes2 == 0) minutes2 = '00';
                if (hours2 >= 12) {
                    if (hours2 == 12) {
                        hours2 = hours2;
                        minutes2 = minutes2 + " PM";
                    } else if (hours2 == 24) {
                        hours2 = 11;
                        minutes2 = "59 PM";
                    } else {
                        hours2 = hours2 - 12;
                        minutes2 = minutes2 + " PM";
                    }
                } else {
                    hours2 = hours2;
                    minutes2 = minutes2 + " AM";
                }

                $('#'+me.parent()[0].className+'-2').html(hours2 + ':' + minutes2);

            };
    
            MyCalendar.sliders = [];
            for(i=1;i<6;i++) {
            $(".slider-range"+i).slider({
                range: true,
                min: 0,
                max: 1440,
                step: 15,
                values: [$(".slider-range"+i).data('st'), $(".slider-range"+i).data('e')],
                nr: $(".slider-range"+i).data('nr'),
                //slide: function(e, ui) {
                    //var hours = Math.floor(ui.value / 60);
                    //var minutes = ui.value - (hours * 60);

                    //if(hours.toString().length == 1) hours = '0' + hours;
                    //if(minutes.toString().length == 1) minutes = '0' + minutes;

                    //$('.slider-time').html(hours+':'+minutes);
                //}
                create: function( event, ui ) {
                    //$( ".slider-range" ).slider( "values", 0, 55 );
                    MyCalendar.sliders[i] = $(".slider-range"+i).data('nr');
                    
                  //  console.log($(".slider-range"+i).css('display'));
                    if($(".slider-range"+i).css('display') !== 'none') {
                        updateTimes([$(".slider-range"+i).data('st'), $(".slider-range"+i).data('e')], $(this));
                    }
                    
                },
                slide: function (e, ui) {//console.log(ui, $(this).parent()[0].className);
                    updateTimes(ui.values, $(this));
                }
            });
            $(".slider-range"+i).slider('option',{nr:i});
        }
            <?php endif; ?>
	});
        
        var getStarttime = function(number) {
            var str_time_start = '';
            var starttime = $('#dditem_timepicker_starttime-'+number).val();
            if(starttime !== '') {
                var str_date_start_tmp = new Date().format('mm/dd/yyyy') + ' ' + starttime;
                var str_time_start = new Date(str_date_start_tmp).format('HH:MM:00');
            }
            return str_time_start;
        };
        var getEndtime = function(number) {
            var str_time_end = '';
            var endtime = $('#dditem_timepicker_endtime-'+number).val();
            if(endtime !== '') {
                var str_date_end_tmp = new Date().format('mm/dd/yyyy') + ' ' + endtime;
                var str_time_end = new Date(str_date_end_tmp).format('HH:MM:00');
            }
            return str_time_end;
        };
        
        var setStringDDItems = function() {
            MyCalendar.dditem_string = '';
            $('.admin-spectrum-colorpicker-dditems').each(function(index,item) {
                var number = $(item).data('number');       
                var starttime = getStarttime(number);
                var endtime = getEndtime(number);
                
                MyCalendar.dditem_string += number + '|' + $('#admin-spectrum-colorpicker-dditem-title-'+number).val() + '|' + $('#admin-dditem-info-'+number).val() + '|' + starttime + '|' + endtime + '|' + $('#admin-dditem-nightshift-'+number).is(':checked')+ '|' + $(item).val() + ',';
            });
            $('#calendar_dditems').val(MyCalendar.dditem_string);
        };
        
        var setStringCustomDropdowns = function() {
            MyCalendar.dropdown_1_string = '';
            MyCalendar.dropdown_2_string = '';
            $('.admin-spectrum-colorpicker-dropdown_1').each(function(index,item) {
                var number = $(item).data('number');
                
                MyCalendar.dropdown_1_string += number + '|' + $('#settings-dropdown-1-option-'+number).val() + '|' + $(item).val() + ',';
            });
            $('#settings_dropdown_1').val(MyCalendar.dropdown_1_string);
            
            $('.admin-spectrum-colorpicker-dropdown_2').each(function(index,item) {
                var number = $(item).data('number');       
                
                MyCalendar.dropdown_2_string += number + '|' + $('#settings-dropdown-2-option-'+number).val() + '|' + $(item).val() + ',';
            });
            $('#settings_dropdown_2').val(MyCalendar.dropdown_2_string);
        };
    
    
        $('.admin-location-name').focusout(function(t) {
            MyCalendar.location_string = '';
            $('.admin-location-name').each(function(index,item) {
                var number = $(item).data('number');
                MyCalendar.location_string += $('#admin-location-name-'+number).val() + ',';
            });
            $('#calendar_locations').val(MyCalendar.location_string);
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

        <?php if ($this->_tpl_vars['active'] == 'calendar'): ?>
        
           $("#admin-spectrum-colorpicker").spectrum({
            showPaletteOnly: true,
            showPalette:true,
            color: 'blanchedalmond',
            palette: arr_palette,
            change: function(color) {
                 // #ff0000
                $("#admin-spectrum-colorpicker").val(color.toHexString());
                $("#admin-spectrum-colorpicker").spectrum('hide');
            }
        });
        $("#admin-spectrum-colorpicker").val('<?php echo $this->_tpl_vars['calendar']['calendar_color']; ?>
');
        $("#admin-spectrum-colorpicker").spectrum('set', '<?php echo $this->_tpl_vars['calendar']['calendar_color']; ?>
');

        // dditems colors
        <?php $_from = $this->_tpl_vars['calendar']['dditems']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>
            <?php if (! empty ( $this->_tpl_vars['item'] )): ?>
                $("#admin-spectrum-colorpicker-dditem-<?php echo $this->_tpl_vars['item']['dditem_id']; ?>
").spectrum({
                    showPaletteOnly: true,
                    showPalette:true,
                    color: 'blanchedalmond',
                    palette: arr_palette,
                    change: function(color) {
                         // #ff0000
                        $("#admin-spectrum-colorpicker-dditem-<?php echo $this->_tpl_vars['item']['dditem_id']; ?>
").val(color.toHexString());
                        $("#admin-spectrum-colorpicker-dditem-<?php echo $this->_tpl_vars['item']['dditem_id']; ?>
").spectrum('hide');

                    //    setStringDDItems();
                    }
                });
                $("#admin-spectrum-colorpicker-dditem-<?php echo $this->_tpl_vars['item']['dditem_id']; ?>
").val('<?php if ($this->_tpl_vars['item']['color'] !== null && ! empty ( $this->_tpl_vars['item']['color'] )): ?><?php echo $this->_tpl_vars['item']['color']; ?>
<?php else: ?><?php echo $this->_tpl_vars['calendar']['calendar_color']; ?>
<?php endif; ?>');
                $("#admin-spectrum-colorpicker-dditem-<?php echo $this->_tpl_vars['item']['dditem_id']; ?>
").spectrum('set', '<?php if ($this->_tpl_vars['item']['color'] !== null && ! empty ( $this->_tpl_vars['item']['color'] )): ?><?php echo $this->_tpl_vars['item']['color']; ?>
<?php else: ?><?php echo $this->_tpl_vars['calendar']['calendar_color']; ?>
<?php endif; ?>');

                <?php if (! empty ( $this->_tpl_vars['item']['starttime'] )): ?>
                    var now = new Date();
                    var startdate     = new Date(now.format('mm/dd/yyyy')+ ' <?php echo $this->_tpl_vars['item']['starttime']; ?>
');
                    var enddate     = new Date(now.format('mm/dd/yyyy')+ ' <?php echo $this->_tpl_vars['item']['endtime']; ?>
');

                    if(MyCalendar.showAMPM) {
                        $('#dditem_timepicker_starttime-<?php echo $this->_tpl_vars['item']['dditem_id']; ?>
').val(dateFormat(startdate,'hh:00 TT'));
                        $('#dditem_timepicker_endtime-<?php echo $this->_tpl_vars['item']['dditem_id']; ?>
').val(dateFormat(enddate,'hh:00 TT'));
                    } else {
                        $('#dditem_timepicker_starttime-<?php echo $this->_tpl_vars['item']['dditem_id']; ?>
').val('<?php echo $this->_tpl_vars['item']['starttime']; ?>
');
                        $('#dditem_timepicker_endtime-<?php echo $this->_tpl_vars['item']['dditem_id']; ?>
').val('<?php echo $this->_tpl_vars['item']['endtime']; ?>
');
                    }
                <?php endif; ?>
                
                $('#dditem_timepicker_starttime-<?php echo $this->_tpl_vars['item']['dditem_id']; ?>
').timepicker({
                    zindex:9999,
                    interval: MyCalendar.timePickerMinuteInterval,
                    timeFormat: MyCalendar.showAMPM ? 'hh:mm p' : 'HH:mm'
                });
                $('#dditem_timepicker_endtime-<?php echo $this->_tpl_vars['item']['dditem_id']; ?>
').timepicker({
                    zindex:9999,
                    interval: MyCalendar.timePickerMinuteInterval,
                    timeFormat: MyCalendar.showAMPM ? 'hh:mm p' : 'HH:mm'
                });


            <?php endif; ?>
        <?php endforeach; endif; unset($_from); ?>

        <?php endif; ?>

        <?php if ($this->_tpl_vars['active'] == 'settings'): ?>
        
        <?php if (isset ( $this->_tpl_vars['settings']['dropdown_1'] )): ?>
        <?php $_from = $this->_tpl_vars['settings']['dropdown_1']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>
            <?php if (! empty ( $this->_tpl_vars['item'] )): ?>
                $("#admin-spectrum-colorpicker-dropdown_1-<?php echo $this->_tpl_vars['item']['option_id']; ?>
").spectrum({
                    showPaletteOnly: true,
                    showPalette:true,
                    color: 'blanchedalmond',
                    palette: arr_palette,
                    change: function(color) {
                         // #ff0000
                        $("#admin-spectrum-colorpicker-dropdown_1-<?php echo $this->_tpl_vars['item']['option_id']; ?>
").val(color.toHexString());
                        $("#admin-spectrum-colorpicker-dropdown_1-<?php echo $this->_tpl_vars['item']['option_id']; ?>
").spectrum('hide');
                    }
                });
                $("#admin-spectrum-colorpicker-dropdown_1-<?php echo $this->_tpl_vars['item']['option_id']; ?>
").val('<?php if ($this->_tpl_vars['item']['color'] !== null && ! empty ( $this->_tpl_vars['item']['color'] )): ?><?php echo $this->_tpl_vars['item']['color']; ?>
<?php else: ?>#3366CC<?php endif; ?>');
                $("#admin-spectrum-colorpicker-dropdown_1-<?php echo $this->_tpl_vars['item']['option_id']; ?>
").spectrum('set', '<?php if ($this->_tpl_vars['item']['color'] !== null && ! empty ( $this->_tpl_vars['item']['color'] )): ?><?php echo $this->_tpl_vars['item']['color']; ?>
<?php else: ?>#3366CC<?php endif; ?>');

            <?php endif; ?>
        <?php endforeach; endif; unset($_from); ?>
        <?php endif; ?>
        
        <?php if (isset ( $this->_tpl_vars['settings']['dropdown_2'] )): ?>
        <?php $_from = $this->_tpl_vars['settings']['dropdown_2']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>
            <?php if (! empty ( $this->_tpl_vars['item'] )): ?>
                $("#admin-spectrum-colorpicker-dropdown_2-<?php echo $this->_tpl_vars['item']['option_id']; ?>
").spectrum({
                    showPaletteOnly: true,
                    showPalette:true,
                    color: 'blanchedalmond',
                    palette: arr_palette,
                    change: function(color) {
                         // #ff0000
                        $("#admin-spectrum-colorpicker-dropdown_2-<?php echo $this->_tpl_vars['item']['option_id']; ?>
").val(color.toHexString());
                        $("#admin-spectrum-colorpicker-dropdown_2-<?php echo $this->_tpl_vars['item']['option_id']; ?>
").spectrum('hide');
                    }
                });
                $("#admin-spectrum-colorpicker-dropdown_2-<?php echo $this->_tpl_vars['item']['option_id']; ?>
").val('<?php if ($this->_tpl_vars['item']['color'] !== null && ! empty ( $this->_tpl_vars['item']['color'] )): ?><?php echo $this->_tpl_vars['item']['color']; ?>
<?php else: ?>#3366CC<?php endif; ?>');
                $("#admin-spectrum-colorpicker-dropdown_2-<?php echo $this->_tpl_vars['item']['option_id']; ?>
").spectrum('set', '<?php if ($this->_tpl_vars['item']['color'] !== null && ! empty ( $this->_tpl_vars['item']['color'] )): ?><?php echo $this->_tpl_vars['item']['color']; ?>
<?php else: ?>#3366CC<?php endif; ?>');

            <?php endif; ?>
        <?php endforeach; endif; unset($_from); ?>
        <?php endif; ?>
        
        <?php endif; ?>
            
        $('#add_dditem').click(function(t) {
            MyCalendar.last_dditem ++;
            $('.table').append('<tr>'+
                                    '<td style="width:50px;border:none;padding:2px;"><input type="text" style="width:150px;" name="title'+MyCalendar.last_dditem+'" class="admin-dditem-title" id="admin-spectrum-colorpicker-dditem-title-'+MyCalendar.last_dditem+'" value="" /></td>'+
                                    '<td style="width:50px;border:none;padding:2px;"><input type="text" style="width:150px;" name="info'+MyCalendar.last_dditem+'" class="admin-dditem-info" id="admin-dditem-info-'+MyCalendar.last_dditem+'" value="" /></td>'+
                                    '<td style="padding:2px;border:none;"><input type="text" class="dditem_timepicker_starttime" id="dditem_timepicker_starttime-'+MyCalendar.last_dditem+'" name="starttime'+MyCalendar.last_dditem+'" style="font-size:13px;width: 80px;"></td>'+
                                    '<td style="padding:2px;border:none;"><input type="text" class="dditem_timepicker_endtime" id="dditem_timepicker_endtime-'+MyCalendar.last_dditem+'" name="endtime'+MyCalendar.last_dditem+'" style="font-size:13px;width: 80px;"></td>'+
                                    '<td style="padding:2px;border:none;"><input type="checkbox" class="dditem_timepicker_nightshift" id="admin-dditem-nightshift-'+MyCalendar.last_dditem+'" name="nightshift'+MyCalendar.last_dditem+'" style="font-size:13px;width: 80px;"></td>'+
                                    '<td style="width:50px;border:none;padding:2px;"><input type="text" class="input-xlarge admin-spectrum-colorpicker-dditems" id="admin-spectrum-colorpicker-dditem-'+MyCalendar.last_dditem+'" name="dditem_color[]" value="<?php echo $this->_tpl_vars['calendar']['calendar_color']; ?>
" data-title="" data-number="'+MyCalendar.last_dditem+'"></td>'+
                                '</tr>');
                        
            $('#dditem_timepicker_starttime-'+MyCalendar.last_dditem).timepicker({
                zindex:9999,
                interval: MyCalendar.timePickerMinuteInterval,
                timeFormat: MyCalendar.showAMPM ? 'hh:mm p' : 'HH:mm'
            });
            $('#dditem_timepicker_endtime-'+MyCalendar.last_dditem).timepicker({
                zindex:9999,
                interval: MyCalendar.timePickerMinuteInterval,
                timeFormat: MyCalendar.showAMPM ? 'hh:mm p' : 'HH:mm'
            });

           

            $("#admin-spectrum-colorpicker-dditem-"+MyCalendar.last_dditem).spectrum({
                showPaletteOnly: true,
                showPalette:true,
                color: 'blanchedalmond',
                palette: arr_palette,
                change: function(color) {
                     // #ff0000
                    $("#admin-spectrum-colorpicker-dditem-"+MyCalendar.last_dditem).val(color.toHexString());
                    $("#admin-spectrum-colorpicker-dditem-"+MyCalendar.last_dditem).spectrum('hide');

              //      setStringDDItems();
                }
            });
            $("#admin-spectrum-colorpicker-dditem-"+MyCalendar.last_dditem).val('<?php echo $this->_tpl_vars['calendar']['calendar_color']; ?>
');
            $("#admin-spectrum-colorpicker-dditem-"+MyCalendar.last_dditem).spectrum('set', '<?php echo $this->_tpl_vars['calendar']['calendar_color']; ?>
');

        });
            
        $('#add_locationfield').click(function(t) {
            MyCalendar.last_location ++;
            $('#locationtable').append('<tr>'+
                                    '<td style="width:50px;border:none;padding:2px;"><input type="text" name="location'+MyCalendar.last_location+'" data-number="'+MyCalendar.last_location+'" class="admin-location-name" id="admin-location-name-'+MyCalendar.last_location+'" value="" /></td>'+
                                '</tr>');


            $('.admin-location-name').focusout(function(t) {
                MyCalendar.location_string = '';
                $('.admin-location-name').each(function(index,item) {
                    var number = $(item).data('number');
                    MyCalendar.location_string += $('#admin-location-name-'+number).val() + ',';
                });
                $('#calendar_locations').val(MyCalendar.location_string);
            });
        });
     
        $('#add_dropdown_1_option_field').click(function(t) {
            MyCalendar.last_dropdown_1_option ++;
            $('#dropdown_1_table').append('<tr>'+
                                    '<td style="width:50px;border:none;padding:2px;"><input type="text" name="dropdown_1_item" data-number="'+MyCalendar.last_dropdown_1_option+'" class="settings-dropdown_1_label" id="settings-dropdown-1-option-'+MyCalendar.last_dropdown_1_option+'" value="" /></td>'+
                                    '<td style="width:50px;border:none;padding:2px;"><input type="text" class="input-xlarge admin-spectrum-colorpicker-dropdown_1" id="admin-spectrum-colorpicker-dropdown_1-'+MyCalendar.last_dropdown_1_option+'" name="dropdown_1_color" value="#3366CC" data-title="" data-number="'+MyCalendar.last_dropdown_1_option+'"></td>'+
                                '</tr>');


            $("#admin-spectrum-colorpicker-dropdown_1-"+MyCalendar.last_dropdown_1_option).spectrum({
                showPaletteOnly: true,
                showPalette:true,
                color: 'blanchedalmond',
                palette: arr_palette,
                change: function(color) {
                     // #ff0000
                    $("#admin-spectrum-colorpicker-dropdown_1-"+MyCalendar.last_dropdown_1_option).val(color.toHexString());
                    $("#admin-spectrum-colorpicker-dropdown_1-"+MyCalendar.last_dropdown_1_option).spectrum('hide');

              //      setStringDDItems();
                }
            });
            $("#admin-spectrum-colorpicker-dropdown_1-"+MyCalendar.last_dropdown_1_option).val('#3366cc');
            $("#admin-spectrum-colorpicker-dropdown_1-"+MyCalendar.last_dropdown_1_option).spectrum('set', '#3366cc');
            
            $('#settings-dropdown-1-option-'+MyCalendar.last_dropdown_1_option).focus();
        });
        
        $('#add_dropdown_2_option_field').click(function(t) {
            MyCalendar.last_dropdown_2_option ++;
            $('#dropdown_2_table').append('<tr>'+
                                    '<td style="width:50px;border:none;padding:2px;"><input type="text" name="dropdown_2_item" data-number="'+MyCalendar.last_dropdown_2_option+'" class="settings-dropdown_2_label" id="settings-dropdown-2-option-'+MyCalendar.last_dropdown_2_option+'" value="" /></td>'+
                                    '<td style="width:50px;border:none;padding:2px;"><input type="text" class="input-xlarge admin-spectrum-colorpicker-dropdown_2" id="admin-spectrum-colorpicker-dropdown_2-'+MyCalendar.last_dropdown_2_option+'" name="dropdown_2_color" value="#3366CC" data-title="" data-number="'+MyCalendar.last_dropdown_2_option+'"></td>'+
                                '</tr>');


            $("#admin-spectrum-colorpicker-dropdown_2-"+MyCalendar.last_dropdown_2_option).spectrum({
                showPaletteOnly: true,
                showPalette:true,
                color: 'blanchedalmond',
                palette: arr_palette,
                change: function(color) {
                     // #ff0000
                    $("#admin-spectrum-colorpicker-dropdown_2-"+MyCalendar.last_dropdown_2_option).val(color.toHexString());
                    $("#admin-spectrum-colorpicker-dropdown_2-"+MyCalendar.last_dropdown_2_option).spectrum('hide');

              //      setStringDDItems();
                }
            });
            $("#admin-spectrum-colorpicker-dropdown_2-"+MyCalendar.last_dropdown_2_option).val('#3366cc');
            $("#admin-spectrum-colorpicker-dropdown_2-"+MyCalendar.last_dropdown_2_option).spectrum('set', '#3366cc');
            
            $('#settings-dropdown-2-option-'+MyCalendar.last_dropdown_2_option).focus();
        });
        
        $('#save-calendar').click(function() {
            setStringDDItems();
            
        });
        
        $('#save-settings').click(function() {
            setStringCustomDropdowns();
            
        });
        
        //$('#calendar_save_form').submit(function(evt) {
        //    evt.preventDefault();
        //    evt.stopImmediatePropagation();
        //    setStringDDItems();
           // $('#calendar_save_form').submit();
        //});
        
        
      
        
        <?php if ($this->_tpl_vars['calendar']['share_type'] == 'public'): ?>
            $('#admin_calendar_share_type').val('<?php echo $this->_tpl_vars['calendar']['share_type']; ?>
');
        <?php endif; ?>
        
        <?php if ($this->_tpl_vars['calendar']['origin'] == 'default'): ?>
            $('#admin_calendar_origin').val('<?php echo $this->_tpl_vars['calendar']['origin']; ?>
');
        <?php endif; ?>
        
        
        $('#admin_user_profile_name_label').html(Lang.Popup.LabelName);
        $('#admin_user_profile_email_label').html(Lang.Popup.LabelEmail);
        $('#admin_user_profile_username_label').html(Lang.Popup.LabelUsername);
        $('#admin_user_profile_birthdate_label').html(Lang.Popup.LabelBirthdate);
        $('#admin_user_profile_country_label').html(Lang.Popup.LabelCountry);
        $('#admin_user_profile_new_password_label').html(Lang.Popup.LabelNewPassword);
        $('#admin_user_profile_new_password2_label').html(Lang.Popup.LabelNewPasswordAgain);
        $('#admin_user_profile_color_label').html(Lang.Popup.ProfileEventColor);

        $('#admin_users_menu').html(Lang.Menu.TitleUsers);
        $('#admin_add_user_menu').html(Lang.Menu.TitleAddUser);
        $('#admin_quick_add_user_menu').html(Lang.Menu.TitleQuickAdduser);
        $('#admin_quick_add_admin_menu').html(Lang.Menu.TitleQuickAddAdmin);
        $('#admin_admins_menu').html(Lang.Menu.TitleAdmins);
        $('#admin_settings_menu').html(Lang.Menu.TitleSettings);
        $('#admin_calendars_menu').html(Lang.Menu.TitleCalendars);
        $('.admin_my_calendars_menu').html(Lang.Menu.TitleMyCalendars);
        $('#admin_hour_calculation_menu').html(Lang.Menu.TitleHourCalculation);
        
        $('#admin_add_group_menu').html(Lang.Button.AddGroup);
        
     
        $('#admin_hour_calculation_menu').html(Lang.Menu.TitleHourCalculation);
        $('#admin_settings_legend').html(Lang.Settings.Legend);
        $('#admin_users_legend').html(Lang.Menu.TitleUsers);
        $('#admin_settings_hour_calculation_legend').html(Lang.Hourcalculation.legend);
        $('#admin_settings_user_hour_calculation_legend').html(Lang.Hourcalculation.legendOfUser);
        $('#admin_settings_info_text').html(Lang.Settings.Infotext);
        $('#admin_settings_language_label').html(Lang.Settings.LabelLanguage);
        $('#admin_settings_defaultview_label').html(Lang.Settings.DefaultView);
        $('#admin_settings_week_view_type_label').html(Lang.Settings.LabelWeekViewType);
        $('#admin_settings_day_view_type_label').html(Lang.Settings.LabelDayViewType);
        $('#admin_settings_other_language_label').html(Lang.Settings.LabelOtherLanguage);
        $('#admin_show_am_pm_checkbox_label').html(Lang.Settings.LabelShowAmPm);
        $('#admin_show_weeknumbers_checkbox_label').html(Lang.Settings.LabelShowWeeknumbers);
        $('#admin_show_notallowed_messages_checkbox_label').html(Lang.Settings.LabelShowNotAllowedMessages);
        $('#admin_settings_mouseover_popup_label').html(Lang.Settings.LabelMouseoverPopup);
        $('#admin_truncate_title_checkbox_label').html(Lang.Settings.LabelTruncateTitle);
        $('#admin_settings_truncate_length_label').html(Lang.Settings.LabelTitleLength);
        $('#admin_settings_edit_dialog_label').html(Lang.Settings.LabelEditDialog);
        $('#admin_settings_two_capitals_label').html(Lang.Settings.LabelTwoCapitals);
        $('#admin_settings_amount_of_characters_label').html(Lang.Settings.LabelAmountOfCharacters);
        $('#admin_settings_colorpicker_type_label').html(Lang.Settings.LabelColorPickerType);
        $('#admin_settings_timepicker_type_label').html(Lang.Settings.LabelTimePickerType);
        $('#admin_show_description_in_edit_dialog_checkbox_label').html(Lang.Settings.LabelShowDescription);
        $('#admin_show_location_in_edit_dialog_checkbox_label').html(Lang.Settings.LabelShowLocation);
        $('#admin_show_phone_in_edit_dialog_checkbox_label').html(Lang.Settings.LabelShowPhone);
        $('#admin_show_url_in_edit_dialog_checkbox_label').html(Lang.Settings.LabelShowUrl);
        $('#admin_show_delete_confirm_dialog_checkbox_label').html(Lang.Settings.LabelShowDeleteConfirmDialog);
        $('#admin_settings_hour_calculation_label').html(Lang.Settings.LabelHourcalculation);
        $('#admin_settings_workday_hours_label').html(Lang.Settings.LabelWorkdayHours);
        $('#admin_settings_default_period_label').html(Lang.Settings.LabelDefaultPeriod);
        $('#admin_settings_amount_of_hours_label').html(Lang.Settings.LabelWorkdayHoursInfo);
        $('#admin_settings_initial_period_label').html(Lang.Settings.LabelDefaultPeriodInfo);
        $('#admin_settings_registration_label').html(Lang.Settings.LabelRegistration);
        $('#admin_settings_registration_info_label').html('USERS_CAN_REGISTER ' + Lang.Settings.LabelRegistrationInfo);
        $('#admin_send_activation_mail_checkbox_label').html(Lang.Settings.LabelSendActivationMail);
        $('#assign_to_users_label').html(Lang.Calendar.LabelAssignToUsers);
        $('#admin_show_teammember_in_edit_dialog_checkbox_label').html(Lang.Calendar.LabelShowTeamMember);
            
    
        $('#menu_logout').text(Lang.Menu.TitleLogout);
        $('#active_label').text(Lang.Calendar.LabelActive);
        $('#yes_label').text(Lang.Label.Yes);
        $('#no_label').text(Lang.Label.No);
        $('#active_period_label').text(Lang.Calendar.LabelActivePeriod);
        $('#alterable_period_label').text(Lang.Calendar.LabelAlterablePeriod);
        $('#in_specific_period_label').text(Lang.Calendar.LabelInSpecificPeriod);
        $('.name_label').text(Lang.Calendar.LabelName);
        $('.days_label').html(Lang.Calendar.LabelDays);
        $('.hours_label').html(Lang.Calendar.LabelHours);
        $('.date_label').html(Lang.Calendar.LabelDate);
        $('.time_label').html(Lang.Calendar.LabelTime);
        $('.calendar_label').html(Lang.Calendar.LabelCalendar);
        $('.username_label').html(Lang.Calendar.LabelUsername);
        $('.email_label').html(Lang.Calendar.LabelEmail);
        $('.registration_date_label').html(Lang.Calendar.LabelRegistrationDate);
        $('.active_label').html(Lang.Calendar.LabelActive);
        $('.count_users_label').html(Lang.Calendar.LabelCountUsers);
        $('.owner_label').html(Lang.Calendar.LabelOwner);
        $('.origin_label').html(Lang.Calendar.LabelOrigin);
        $('.canseedditems_label').html(Lang.Calendar.LabelCanSeeDDItems);
        $('.type_label').html(Lang.Calendar.LabelType);
        $('.add_label').html(Lang.Calendar.LabelAdd);
        $('.edit_label').html(Lang.Calendar.LabelEdit);
        $('.delete_label').html(Lang.Calendar.LabelDelete);
        $('.changecolor_label').html(Lang.Calendar.LabelChangeColor);
        $('.initialshow_label').html(Lang.Calendar.LabelInitialShow);
        $('.view_label').html(Lang.Calendar.LabelView);
		
                                                
        $('#dd_items_label').text(Lang.Calendar.LabelDDItemsCalForm);
        $('#admin_usergroup_dditems_label').text(Lang.Calendar.LabelUsergroupDDItems);
        
        $('#title_label').text(Lang.Calendar.LabelTitle);
        $('#info_label').text(Lang.Calendar.LabelInfo);
        $('#starttime_label').text(Lang.Calendar.LabelStarttime);
        $('#endtime_label').text(Lang.Calendar.LabelEndtime);
        $('#nightshift_label').text(Lang.Calendar.LabelNightShift);
        $('.color_label').text(Lang.Label.Color);
        $('#add_dditem').val(Lang.Button.AddDDItem);
        $('#admin_can_see_dditems_label').text(Lang.Calendar.LabelCanSeeDDItems);
        $('#admin_share_type_label').text(Lang.Calendar.LabelShareType);
        $('.others_label').text(Lang.Calendar.LabelOthers);
        $('#can_add_label').text(Lang.Calendar.LabelCanAdd);
        $('#can_edit_label').text(Lang.Calendar.LabelCanEdit);
        $('#can_delete_label').text(Lang.Calendar.LabelCanDelete);
        $('#can_change_color_label').text(Lang.Calendar.LabelCanChangeColor);
        $('#admin_default_calendar_label').text(Lang.Calendar.LabelDefaultCalendar);
        $('#event_location_label').text(Lang.Calendar.LabelEventLocation);
        $('#use_color_for_all_events_label').text(Lang.Calendar.LabelUseColorForAllEvents);
        $('#predefined_locations_label').text(Lang.Calendar.LabelPredefinedLocations);
        $('#add_locationfield').val(Lang.Button.AddLocation); 
        $('#notifications_label').text(Lang.Calendar.LabelNotifications);
        $('#manually_label').text(Lang.Calendar.LabelManually);
        $('#automatic_label').text(Lang.Calendar.LabelAutomatic);
        $('#manually_info_label').text(Lang.Calendar.LabelManuallyInfo);
        $('#automatic_info_label').text(Lang.Calendar.LabelAutomaticInfo);
        $('#calendar_admin_email_label').text(Lang.Calendar.LabelCalendarAdminEmail);
        $('#when_admin_email_is_empty_info').text(Lang.Calendar.LabelWhenAdminEmailEmptyInfo);
        $('#save-calendar').html(Lang.Button.SaveChanges); 
        $('#add_calendar_label').text(Lang.Calendar.LabelAddCalendar);
        
        $('#admin_pdf_table_look_checkbox_label').html(Lang.Calendar.LabelTableLook);
        $('#admin_pdf_show_time_columns_checkbox_label').html(Lang.Calendar.LabelShowTimeColumns);
        $('#admin_pdf_show_date_on_every_line_checkbox_label').html(Lang.Calendar.LabelShowDateOnEveryLine);
        $('#admin_pdf_show_logo_checkbox_label').html(Lang.Calendar.LabelShowLogo);
        $('#admin_pdf_show_custom_dropdown_values_checkbox_label').html(Lang.Calendar.LabelShowCustomDropdownValues);
        
    
    
        $('#admin_pdf_fontweight_bold_checkbox_label').html(Lang.Calendar.LabelFontWeightBold);
        $('#admin_pdf_colored_rows_checkbox_label').html(Lang.Calendar.LabelRowColorsGrayWhite);
        $('#admin_pdf_sorting_checkbox_label').html(Lang.Calendar.LabelSorting);
        $('#admin_settings_pdf_export_label').html(Lang.Calendar.LabelPdfExport);
        $('#admin_pdf_pagination_translation').html(Lang.Calendar.LabelPaginationTranslation);
        $('.admin_label_page').html(Lang.Calendar.LabelPage);
        $('.admin_label_of').html(Lang.Calendar.LabelOf);
        $('#admin_pdf_column_names').html(Lang.Calendar.LabelColumnNames);
        $('#admin_label_date_column').html(Lang.Calendar.LabelDateColumn);
        $('#admin_label_start_time_column').html(Lang.Calendar.LabelStartTimeColumn);
        $('#admin_label_end_time_column').html(Lang.Calendar.LabelEndTimeColumn);
        $('#admin_label_event_title_column').html(Lang.Calendar.LabelEventTitleColumn);
        
        $('#add-group-btn').html(Lang.Button.AddGroup);
        $('#add-user-btn').html(Lang.Menu.TitleAdduser);
        $('#quick_add-user-btn').html(Lang.Menu.TitleQuickAdduser);
        $('#dates_refresh_button').html(Lang.Button.Refresh);
        $('#user_dates_refresh_button').html(Lang.Button.Refresh);
        $('#add-availability-btn').html(Lang.Button.AddAvailability);
        
        
        $('.edit_btn').html(Lang.Popup.editButtonText);
        $('.delete_btn').html(Lang.Popup.deleteButtonText);
        $('.admins_lable').html(Lang.Menu.TitleAdmins);
        $('.users_lable').html(Lang.Menu.TitleUsers);
        $('.groups_lable').html(Lang.Menu.TitleGroups);
        $('.my_groups_lable').html(Lang.Menu.TitleMyGroups);
        $('.availability_label').html(Lang.Menu.TitleAvailability);
        
        $('#add-calendar-btn').html(Lang.Button.addCalendar);
        
        $('.simple_endtime_label').html(Lang.Popup.SimpleEndTimeLabel);
        $('.simple_starttime_label').html(Lang.Popup.SimpleStartTimeLabel );

        
        $('#save-settings-btn').html(Lang.Popup.saveButtonText);
                
        $('#availability_monday_label').html(Lang.Fullcalendar.dayNames[1]);
        $('#availability_tuesday_label').html(Lang.Fullcalendar.dayNames[2]);

        $('#admin_add_availability_menu').html(Lang.Menu.TitleAddAvailability);
        $('#admin_import_users_menu').html(Lang.Menu.TitleImportUsers);

	</script>

  </body>
</html>