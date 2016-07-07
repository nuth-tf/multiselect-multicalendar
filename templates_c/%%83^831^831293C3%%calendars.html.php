<?php /* Smarty version 2.6.18, created on 2016-05-15 18:58:22
         compiled from /Applications/XAMPP/xamppfiles/htdocs/employee-work-schedule/view/leftblocks/calendars.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'addslashes', '/Applications/XAMPP/xamppfiles/htdocs/employee-work-schedule/view/leftblocks/calendars.html', 9, false),array('modifier', 'count', '/Applications/XAMPP/xamppfiles/htdocs/employee-work-schedule/view/leftblocks/calendars.html', 44, false),)), $this); ?>
        <?php if (@SHOW_PUBLIC_AND_PRIVATE_SEPARATELY): ?>
            <?php if ($this->_tpl_vars['cnt_public'] > 0): ?>
            <?php ob_start(); ?>
                <?php $_from = $this->_tpl_vars['my_active_calendars']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>

                    <?php if ($this->_tpl_vars['item']['share_type'] == 'public'): ?>
                    <div class="onecalendar-wrap" >
                        <?php if ($this->_tpl_vars['item']['initial_show']): ?>
                            <div onclick="MyCalendar.addCalendar('<?php echo $this->_tpl_vars['item']['calendar_id']; ?>
','<?php echo addslashes($this->_tpl_vars['item']['name']); ?>
','<?php echo $this->_tpl_vars['item']['origin']; ?>
','<?php echo $this->_tpl_vars['item']['feedurl']; ?>
');" alt="Click to show/hide" title="Click to show/hide" id="calgroup<?php echo $this->_tpl_vars['item']['calendar_id']; ?>
" class="tick_on" cal_id="<?php echo $this->_tpl_vars['item']['calendar_id']; ?>
" feedurl="<?php echo $this->_tpl_vars['item']['feedurl']; ?>
" origin="<?php echo $this->_tpl_vars['item']['originClick']; ?>
" style="margin:4px 0 0 2px;cursor: pointer; float: left; width: 1.4em;cursor:pointer;font-size:0.85em;">
                                &nbsp;
                            </div>
                        <?php else: ?>
                            <div onclick="MyCalendar.addCalendar('<?php echo $this->_tpl_vars['item']['calendar_id']; ?>
','<?php echo addslashes($this->_tpl_vars['item']['name']); ?>
','<?php echo $this->_tpl_vars['item']['origin']; ?>
','<?php echo $this->_tpl_vars['item']['feedurl']; ?>
');" alt="Click to show/hide" title="Click to show/hide" id="calgroup<?php echo $this->_tpl_vars['item']['calendar_id']; ?>
" class="tick_off" cal_id="<?php echo $this->_tpl_vars['item']['calendar_id']; ?>
" feedurl="<?php echo $this->_tpl_vars['item']['feedurl']; ?>
" origin="<?php echo $this->_tpl_vars['item']['origin']; ?>
" style="margin:6px 0 0 1px;cursor: pointer; float: left; width: 1.4em;cursor:pointer;font-size:12px;color:#FFFFFF;font-weight:bold;">
                                &nbsp;
                            </div>
                        <?php endif; ?>

                        <script type='text/javascript'>
                            document.styleSheets[3].insertRule(" #caldiv_<?php echo $this->_tpl_vars['item']['calendar_id']; ?>
 { border-color:<?php if (! empty ( $this->_tpl_vars['item']['color'] )): ?><?php echo $this->_tpl_vars['item']['color']; ?>
<?php else: ?><?php echo $this->_tpl_vars['item']['calendar_color']; ?>
<?php endif; ?> }",1);
                        </script>
                        
                        <div  class="onecalendar" id="caldiv_<?php echo $this->_tpl_vars['item']['calendar_id']; ?>
" style="width:122px;background-color:<?php if (! empty ( $this->_tpl_vars['item']['color'] )): ?><?php echo $this->_tpl_vars['item']['color']; ?>
<?php else: ?><?php echo $this->_tpl_vars['item']['calendar_color']; ?>
<?php endif; ?>" onclick="MyCalendar.openCalendar('<?php echo $this->_tpl_vars['item']['calendar_id']; ?>
','<?php echo addslashes($this->_tpl_vars['item']['name']); ?>
','<?php echo $this->_tpl_vars['item']['origin']; ?>
','<?php echo $this->_tpl_vars['item']['feedurl']; ?>
');" title="Show only this calendar" >
                            <a id="calname<?php echo $this->_tpl_vars['item']['calendar_id']; ?>
" style="text-decoration: none;color: #EFEFEF;" href="#" >
                            <span style="font-family:tahoma,arial;text-shadow: -1px 0 #9F9F9F, 0 1px #9F9F9F, 1px 0 #9F9F9F, 0 -1px #9F9F9F;color:#fff;padding-left:3px;"><?php echo $this->_tpl_vars['item']['name']; ?>
</span></a><?php if ($this->_tpl_vars['item']['origin'] == 'google'): ?><span style="float:right;"><img src="<?php echo @IMAGES_URL; ?>
/google-icon.png" title="Google Calendar" alt="Google Calendar" /></span><?php endif; ?>
                            <!-- hier stond eerst span caldiv -->
                        </div>
                    </div>
                    <?php endif; ?>

                <?php endforeach; endif; unset($_from); ?>
            <?php $this->_smarty_vars['capture']['public_cals'] = ob_get_contents(); ob_end_clean(); ?>
            <div><?php if ($this->_tpl_vars['cnt_private'] > 0): ?>Public<?php endif; ?>
                    <?php echo $this->_smarty_vars['capture']['public_cals']; ?>
</div>
                
            <?php endif; ?>
               
            <?php if ($this->_tpl_vars['cnt_private'] > 0): ?>
            <?php ob_start(); ?>
                <?php $_from = $this->_tpl_vars['my_active_calendars']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>

                    <?php if ($this->_tpl_vars['item']['share_type'] != 'public'): ?>
                    <div class="onecalendar-wrap" >
                        <?php if ($this->_tpl_vars['item']['initial_show']): ?>
                            <div onclick="MyCalendar.addCalendar('<?php echo $this->_tpl_vars['item']['calendar_id']; ?>
','<?php echo addslashes($this->_tpl_vars['item']['name']); ?>
','<?php echo $this->_tpl_vars['item']['origin']; ?>
','<?php echo $this->_tpl_vars['item']['feedurl']; ?>
');" alt="<?php if (count($this->_tpl_vars['my_active_calendars']) > 1): ?>Click to show/hide<?php endif; ?>" title="<?php if (count($this->_tpl_vars['my_active_calendars']) > 1): ?>Click to show/hide<?php endif; ?>" id="calgroup<?php echo $this->_tpl_vars['item']['calendar_id']; ?>
" class="tick_on" cal_id="<?php echo $this->_tpl_vars['item']['calendar_id']; ?>
" feedurl="<?php echo $this->_tpl_vars['item']['feedurl']; ?>
" origin="<?php echo $this->_tpl_vars['item']['origin']; ?>
" style="margin:4px 0 0 2px;cursor: pointer; float: left; width: 1.4em;cursor:pointer;font-size:0.85em;">
                                &nbsp;
                            </div>
                        <?php else: ?>
                        <div onclick="MyCalendar.addCalendar('<?php echo $this->_tpl_vars['item']['calendar_id']; ?>
','<?php echo addslashes($this->_tpl_vars['item']['name']); ?>
','<?php echo $this->_tpl_vars['item']['origin']; ?>
','<?php echo $this->_tpl_vars['item']['feedurl']; ?>
');" alt="<?php if (count($this->_tpl_vars['my_active_calendars']) > 1): ?>Click to show/hide<?php endif; ?>" title="<?php if (count($this->_tpl_vars['my_active_calendars']) > 1): ?>Click to show/hide<?php endif; ?>" id="calgroup<?php echo $this->_tpl_vars['item']['calendar_id']; ?>
" class="tick_off" cal_id="<?php echo $this->_tpl_vars['item']['calendar_id']; ?>
" feedurl="<?php echo $this->_tpl_vars['item']['feedurl']; ?>
" origin="<?php echo $this->_tpl_vars['item']['origin']; ?>
" style="margin:6px 0 0 1px;cursor: pointer; float: left; width: 1.4em;cursor:pointer;font-size:12px;color:#FFFFFF;font-weight:bold;">
                                &nbsp;
                            </div>
                        <?php endif; ?>

                        <script type='text/javascript'>
                            document.styleSheets[3].insertRule(" #caldiv_<?php echo $this->_tpl_vars['item']['calendar_id']; ?>
 { border-color:<?php if (! empty ( $this->_tpl_vars['item']['color'] )): ?><?php echo $this->_tpl_vars['item']['color']; ?>
<?php else: ?><?php echo $this->_tpl_vars['item']['calendar_color']; ?>
<?php endif; ?> }",1);
                        </script>
                        
                        <div  class="onecalendar" id="caldiv_<?php echo $this->_tpl_vars['item']['calendar_id']; ?>
" style="width:122px;background-color:<?php if (! empty ( $this->_tpl_vars['item']['color'] )): ?><?php echo $this->_tpl_vars['item']['color']; ?>
<?php else: ?><?php echo $this->_tpl_vars['item']['calendar_color']; ?>
<?php endif; ?>" onclick="MyCalendar.openCalendar('<?php echo $this->_tpl_vars['item']['calendar_id']; ?>
','<?php echo addslashes($this->_tpl_vars['item']['name']); ?>
','<?php echo $this->_tpl_vars['item']['origin']; ?>
','<?php echo $this->_tpl_vars['item']['feedurl']; ?>
');" title="<?php if (count($this->_tpl_vars['my_active_calendars']) > 1): ?>Show only this calendar<?php endif; ?>" >
                            <a id="calname<?php echo $this->_tpl_vars['item']['calendar_id']; ?>
" style="text-decoration: none;color: #EFEFEF;" href="#" >
                            <span style="font-family:tahoma,arial;text-shadow: -1px 0 #9F9F9F, 0 1px #9F9F9F, 1px 0 #9F9F9F, 0 -1px #9F9F9F;color:#fff;padding-left:3px;"><?php echo $this->_tpl_vars['item']['name']; ?>
</span></a><?php if ($this->_tpl_vars['item']['origin'] == 'google'): ?><span style="float:right;"><img src="<?php echo @IMAGES_URL; ?>
/google-icon.png" title="Google Calendar" alt="Google Calendar" /></span><?php endif; ?>
                            <!-- hier stond eerst span caldiv -->
                        </div>
                    </div>
                    <?php endif; ?>

                <?php endforeach; endif; unset($_from); ?>
            <?php $this->_smarty_vars['capture']['private_cals'] = ob_get_contents(); ob_end_clean(); ?>
            <div style="padding-top:10px;"><?php if ($this->_tpl_vars['cnt_public'] > 0): ?>Private<?php endif; ?>
                <?php echo $this->_smarty_vars['capture']['private_cals']; ?>
</div>
            <?php endif; ?>
            
           
        <?php else: ?>
            <?php $_from = $this->_tpl_vars['my_active_calendars']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>

                <div class="onecalendar-wrap" >
                    <?php if ($this->_tpl_vars['item']['initial_show']): ?>
                        <div onclick="MyCalendar.addCalendar('<?php echo $this->_tpl_vars['item']['calendar_id']; ?>
','<?php echo addslashes($this->_tpl_vars['item']['name']); ?>
','<?php echo $this->_tpl_vars['item']['origin']; ?>
','<?php echo $this->_tpl_vars['item']['feedurl']; ?>
');" alt="<?php if (count($this->_tpl_vars['my_active_calendars']) > 1): ?>Click to show/hide<?php endif; ?>" title="<?php if (count($this->_tpl_vars['my_active_calendars']) > 1): ?>Click to show/hide<?php endif; ?>" id="calgroup<?php echo $this->_tpl_vars['item']['calendar_id']; ?>
" class="tick_on" cal_id="<?php echo $this->_tpl_vars['item']['calendar_id']; ?>
" feedurl="<?php echo $this->_tpl_vars['item']['feedurl']; ?>
" origin="<?php echo $this->_tpl_vars['item']['origin']; ?>
" style="margin:4px 0 0 2px;cursor: pointer; float: left; width: 1.4em;cursor:pointer;font-size:0.85em;">
                            &nbsp;
                        </div>
                    <?php else: ?>
                    <div onclick="MyCalendar.addCalendar('<?php echo $this->_tpl_vars['item']['calendar_id']; ?>
','<?php echo addslashes($this->_tpl_vars['item']['name']); ?>
','<?php echo $this->_tpl_vars['item']['origin']; ?>
','<?php echo $this->_tpl_vars['item']['feedurl']; ?>
');" alt="<?php if (count($this->_tpl_vars['my_active_calendars']) > 1): ?>Click to show/hide<?php endif; ?>" title="<?php if (count($this->_tpl_vars['my_active_calendars']) > 1): ?>Click to show/hide<?php endif; ?>" id="calgroup<?php echo $this->_tpl_vars['item']['calendar_id']; ?>
" class="tick_off" cal_id="<?php echo $this->_tpl_vars['item']['calendar_id']; ?>
" feedurl="<?php echo $this->_tpl_vars['item']['feedurl']; ?>
" origin="<?php echo $this->_tpl_vars['item']['origin']; ?>
" style="margin:6px 0 0 1px;cursor: pointer; float: left; width: 1.4em;cursor:pointer;font-size:12px;color:#FFFFFF;font-weight:bold;">
                            &nbsp;
                        </div>
                    <?php endif; ?>

                    <script type='text/javascript'>
                        document.styleSheets[3].insertRule(" #caldiv_<?php echo $this->_tpl_vars['item']['calendar_id']; ?>
 { border-color:<?php if (! empty ( $this->_tpl_vars['item']['color'] )): ?><?php echo $this->_tpl_vars['item']['color']; ?>
<?php else: ?><?php echo $this->_tpl_vars['item']['calendar_color']; ?>
<?php endif; ?> }",1);
                    </script>
                    
                    <div  class="onecalendar" id="caldiv_<?php echo $this->_tpl_vars['item']['calendar_id']; ?>
" style="width:122px;background-color:<?php if (! empty ( $this->_tpl_vars['item']['color'] )): ?><?php echo $this->_tpl_vars['item']['color']; ?>
<?php else: ?><?php echo $this->_tpl_vars['item']['calendar_color']; ?>
<?php endif; ?>" onclick="MyCalendar.openCalendar('<?php echo $this->_tpl_vars['item']['calendar_id']; ?>
','<?php echo addslashes($this->_tpl_vars['item']['name']); ?>
','<?php echo $this->_tpl_vars['item']['origin']; ?>
','<?php echo $this->_tpl_vars['item']['feedurl']; ?>
');" title="<?php if (count($this->_tpl_vars['my_active_calendars']) > 1): ?>Show only this calendar<?php endif; ?>" >
                        <a id="calname<?php echo $this->_tpl_vars['item']['calendar_id']; ?>
" style="text-decoration: none;color: #EFEFEF;" href="#" >
                        <span style="font-family:tahoma,arial;text-shadow: -1px 0 #9F9F9F, 0 1px #9F9F9F, 1px 0 #9F9F9F, 0 -1px #9F9F9F;color:#fff;padding-left:3px;"><?php echo $this->_tpl_vars['item']['name']; ?>
</span></a><?php if ($this->_tpl_vars['item']['origin'] == 'google'): ?><span style="float:right;"><img src="<?php echo @IMAGES_URL; ?>
/google-icon.png" title="Google Calendar" alt="Google Calendar" /></span><?php endif; ?>
                        <!-- hier stond eerst span caldiv -->
                    </div>
                </div>
               
            <?php endforeach; endif; unset($_from); ?>
            
        <?php endif; ?>
       
        <?php if (count($this->_tpl_vars['my_active_calendars']) > 1): ?>
                <div id="calendars_div" class="all_cals" <?php if (@SHOW_PUBLIC_AND_PRIVATE_SEPARATELY): ?>style="padding-top:10px;"<?php endif; ?>>
                        <div class="onecalendar-wrap">
                                <div class="onecalendar" onclick="MyCalendar.openCalendar('all');">
                                        &nbsp;&nbsp;&nbsp;Show all
                                </div>
                        </div>
                </div>
        <?php endif; ?>
        <br style="clear:left;" />