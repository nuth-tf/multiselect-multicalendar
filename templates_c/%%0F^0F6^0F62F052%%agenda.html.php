<?php /* Smarty version 2.6.18, created on 2016-05-15 19:57:15
         compiled from /Applications/XAMPP/xamppfiles/htdocs/employee-work-schedule/view/widgets/agenda.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', '/Applications/XAMPP/xamppfiles/htdocs/employee-work-schedule/view/widgets/agenda.html', 235, false),)), $this); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>

<style type='text/css'>

	

    .agenda_title {
            background: none repeat scroll 0 0 #fff;
            font-size: small;
            padding:3px 3px 3px 6px;
            font-weight:bold;
    }
    .agenda_item {
            background-color:#FFFFCC;

            font-size: small;
            padding:3px 3px 3px 6px;
    }
        
    @media print {
        body, html, #wrapper {
            width: 1000px;
            margin:0;
            background-color: none;
            
        }
        div {
            overflow: visible !important;
        }
   
    .container {
        background: none !important;
        border: none !important;
        box-shadow: none !important;
    }
   
    .button-fc-like,
    #calendar-title,
    .btn-primary,
    .not_print,
    #calendar-menu-header,
    #agenda_refresh_btn,
    #listview_back,
    #listview_forward {
        display: none !important;
    }

 
  
        }
        
        .fc-state-default.fc-corner-right {
            border-bottom-right-radius: 4px;
            border-top-right-radius: 4px;
        }
        
        .fc-state-default.fc-corner-left {
            border-bottom-left-radius: 4px;
            border-top-left-radius: 4px;
        }
        
        .customlist_btn {
            background-color: #f5f5f5;
    background-image: linear-gradient(to bottom, #ffffff, #e6e6e6);
    background-repeat: repeat-x;
    box-shadow: 0 1px 0 rgba(255, 255, 255, 0.2) inset, 0 1px 2px rgba(0, 0, 0, 0.05);
    color: #333;
    padding:4px;
    border:1px solid #d4d4d4;
    text-shadow: 0 1px 1px rgba(255, 255, 255, 0.75);
    -moz-user-select: none;
    text-decoration:none;
        }
</style>
    <link rel='stylesheet' type='text/css' href='<?php echo @EXTERNAL_URL; ?>
/jquery/jqueryui/1.8.17/jquery-ui.css' />

         <script src="<?php echo @EXTERNAL_URL; ?>
/jquery/jquery.1.11.1.min.js"></script>
		 <script src="<?php echo @EXTERNAL_URL; ?>
/jquery/jquery-ui.1.11.1.min.js" type="text/javascript" charset="utf-8"></script>

         <link rel='stylesheet' type='text/css' href='<?php echo @FULLCAL_URL; ?>
/style/style.css' />
<link rel='stylesheet' type='text/css' href='<?php echo @FULLCAL_URL; ?>
/style/customstyles.css' />

<script type='text/javascript'>
         $(document).ready(function() {
        
        $('#print_btn').click(function() {
           //$('#calendar').fullCalendar('render');
            window.print();
        });
        
        
        MyCalendar = {};
        MyCalendar.datePickerDateFormat = '<?php echo @DATEPICKER_DATEFORMAT; ?>
';    
        
        var dateFormat = 'dd-mm-yy';
        
        if(MyCalendar.datePickerDateFormat.indexOf('mm/dd/') >= 0) {
            dateFormat = 'mm-dd-yy';
        } else if(MyCalendar.datePickerDateFormat.indexOf('dd/mm/') >= 0) {
            dateFormat = 'dd-mm-yy';
        }
                 
        $( "#agenda_datepicker_startdate" ).datepicker({dateFormat : dateFormat});
            $( "#agenda_datepicker_enddate" ).datepicker({dateFormat : dateFormat});

            $('#agenda_refresh_btn').click(function() {
              var agenda_start = $('#agenda_datepicker_startdate').val();
              var agenda_end = $('#agenda_datepicker_enddate').val();
            
              window.location = '<?php echo @FULLCAL_URL; ?>
/?action=agenda&from=' + agenda_start + '&to=' + agenda_end;
          });
     
            
         } 
         );
 
 function DropDown(el) {
		this.dd = el;
		this.initEvents();
	}
	DropDown.prototype = {
		initEvents : function() {
			var obj = this;

			$('#dd').click( function(event){
				$(this).toggleClass('active');
				event.stopPropagation();
			});
		}
	}

	$(function() {
		var dd = new DropDown( $('#dd') );
		$(document).click(function() {
			// all dropdowns
			$('.wrapper-dropdown-5').removeClass('active');
		});
	});
         
         </script>

<link type="text/css" rel="stylesheet" href="<?php echo @EXTERNAL_URL; ?>
/dropdown/css/style.css" />
<script type="text/javascript" src="<?php echo @EXTERNAL_URL; ?>
/dropdown/mo.js"></script>

</head>
<body style="margin: 0 auto;width:100%;background-color: #FBFBFB;">

<div class="container" style="">
	
	<span id="calendar-title"><?php echo @CALENDAR_TITLE; ?>
</span>
		<div id="calendar-menu-header" class="not_print">
			<?php if (isset ( $this->_tpl_vars['user'] ) && ! empty ( $this->_tpl_vars['user'] )): ?>

				<span id="dd" class="wrapper-dropdown-5" tabindex="1" style="line-height: 24px;"><?php echo $this->_tpl_vars['user']; ?>

				<ul class="dropdown">
                    <li><a href="<?php echo @FULLCAL_URL; ?>
/admin/users/?action=get_profile&uid=<?php echo $this->_tpl_vars['user_id']; ?>
"><i class="icon-user"></i><span id="menu_to_profile">Profile</span></a></li>

                <?php if ($this->_tpl_vars['is_admin']): ?>
                        <li><a href="<?php echo @FULLCAL_URL; ?>
/admin/users/?action=new_user"><i class="icon-plus"></i><span id="menu_add_user">Add user</span></a></li>
                        <li><a href="<?php echo @FULLCAL_URL; ?>
/admin/users/?action=quick_new_user"><i class="icon-plus"></i><span id="menu_quick_add_user">Quickly add user</span></a></li>
                        <li><a id="to_admin_area" href="<?php echo @FULLCAL_URL; ?>
/admin"><i class="icon-th-large"></i>Admin dashboard</a></li>
                    <?php if (! is_superadmin): ?>
                        <li><a href="<?php echo @FULLCAL_URL; ?>
/admin/settings"><i class="icon-cog"></i><span id="menu_settings">Settings</span></a></li>
                    <?php endif; ?>
                <?php else: ?>
                    <li><a href="<?php echo @FULLCAL_URL; ?>
/user/settings"><i class="icon-cog"></i><span id="menu_settings">Settings</span></a></li>
                    <?php if (@USERS_CAN_ADD_CALENDARS): ?>
                        <li><a href="<?php echo @FULLCAL_URL; ?>
/user/calendars"><i class="icon-list"></i><span id="menu_my_calendars">My calendars</span></a></li>
                    <?php endif; ?>
                <?php endif; ?>

				

                        <li><a href="?action=logoff"><i class="icon-close"></i><span id="menu_logout">Log out</span></a></li>
                        </ul>
                        <span class="right-arrow"></span>
                        </span>
                <?php else: ?>
                        <?php if (@ALLOW_ACCESS_BY == 'login' || @ALLOW_ACCESS_BY == 'free'): ?>
                                <?php if (@SHOW_SMALL_LOGIN_LINK): ?>
                                        <a style="line-height: 24px;" class="button-fc-like" href="<?php echo @FULLCAL_URL; ?>
?action=login">Log in</a>
                                        <?php if (@USERS_CAN_REGISTER): ?>
                                                 &nbsp; &nbsp;<a href="<?php echo @FULLCAL_URL; ?>
/register">Register</a>
                                        <?php endif; ?>
                                <?php endif; ?>
                        <?php endif; ?>
                <?php endif; ?>
                <span style="padding-left:10px;"><a href="<?php echo @FULLCAL_URL; ?>
" class="button-fc-like" style="line-height: 24px;">Calendar</a></span>
                
		</div>

		
		<div id="searchbox_and_buttons" style="float: right;">
			<a style="float:right;margin-right: 10px;line-height: 17px !important;" class="button-fc-like" id="print_btn" href="#"><i class="icon-print"></i> Print</a>
                        <!--<a style="float:right;margin-right: 10px;line-height: 17px !important;" class="button-fc-like" id="pdf_btn" href="#"><i class="icon-file"></i> PDF</a>-->
			
             
		</div>
	

</div>

	<div id="agenda" style="width:90%;margin-left:70px;padding:10px;">

		<a class="fc-button fc-button-prev fc-state-default fc-corner-left customlist_btn" href="?action=agenda&to=<?php echo $this->_tpl_vars['from']; ?>
" style="" id="listview_back" to="<?php echo $this->_tpl_vars['from']; ?>
">&nbsp;◄&nbsp;</a>
		<a class="fc-button fc-button-prev fc-state-default fc-corner-right customlist_btn" href="?action=agenda&from=<?php echo $this->_tpl_vars['to']; ?>
" style="" id="listview_forward" cal_id="<?php echo $this->_tpl_vars['cal_id']; ?>
" from="<?php echo $this->_tpl_vars['to']; ?>
">&nbsp;►&nbsp;</a>
                
		
                <span style="left:42%;position:absolute;">
                    <input type="text" id="agenda_datepicker_startdate" value="<?php echo $this->_tpl_vars['from']; ?>
" style="font-size:13px;width: 90px;padding:3px;z-index:9999;">
                - <input type="text" id="agenda_datepicker_enddate" value="<?php echo $this->_tpl_vars['to']; ?>
" style="font-size:13px;width: 90px;padding:3px;z-index:9999;">
                    <input type="button" id="agenda_refresh_btn" value="Refresh" />
                </span>
                            
                
		<br /><br />
		<?php $_from = $this->_tpl_vars['items']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>

			<div class="agenda_title">
				<?php if (@LANGUAGE == 'EN'): ?>
					<td style="width:50px;text-align:left;"><?php echo $this->_tpl_vars['key']; ?>
</td>
				<?php else: ?>
					<td style="width:50px;text-align:left;"><?php echo $this->_tpl_vars['key']; ?>
</td>
				<?php endif; ?>
			</div>

			<div style="border: 1px solid #CCCCCC;">
			<?php $_from = $this->_tpl_vars['item']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['i']):
?>
					<?php if ($this->_tpl_vars['k'] > 0): ?>
						<div style="border-top: 1px dotted #CCCCCC;"></div>
					<?php endif; ?>
					<div style="background-color: <?php if (! empty ( $this->_tpl_vars['i']['color'] )): ?><?php echo $this->_tpl_vars['i']['color']; ?>
<?php else: ?><?php echo $this->_tpl_vars['i']['calendar_color']; ?>
<?php endif; ?>;padding-left: 3px;">
						<div class="agenda_item"><?php if ($this->_tpl_vars['i']['allDay'] == 1): ?><?php else: ?><?php echo ((is_array($_tmp=$this->_tpl_vars['i']['time_start'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%H:%M") : smarty_modifier_date_format($_tmp, "%H:%M")); ?>
 - <?php echo ((is_array($_tmp=$this->_tpl_vars['i']['time_end'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%H:%M") : smarty_modifier_date_format($_tmp, "%H:%M")); ?>
<?php endif; ?>

						<?php if (isset ( $this->_tpl_vars['i']['name'] )): ?><?php echo $this->_tpl_vars['i']['name']; ?>
 - <?php endif; ?><?php echo $this->_tpl_vars['i']['title']; ?>

						</div>
					</div>
			<?php endforeach; endif; unset($_from); ?>
			</div>
                <?php endforeach; else: ?>
                    No items found
		<?php endif; unset($_from); ?>
		<br />
		
	</div>

<div style='clear:both'></div><div id="tooltip" style="position:absolute;
display:none;background-color:white;border:solid 1px gray;padding:5px;z-index:50;" >
</div>
</body>
</html>