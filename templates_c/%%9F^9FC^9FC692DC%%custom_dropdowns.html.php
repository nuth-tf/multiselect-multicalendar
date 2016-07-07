<?php /* Smarty version 2.6.18, created on 2016-05-25 18:00:20
         compiled from /Applications/XAMPP/xamppfiles/htdocs/employee-work-schedule/view/leftblocks/custom_dropdowns.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'addslashes', '/Applications/XAMPP/xamppfiles/htdocs/employee-work-schedule/view/leftblocks/custom_dropdowns.html', 15, false),array('modifier', 'count', '/Applications/XAMPP/xamppfiles/htdocs/employee-work-schedule/view/leftblocks/custom_dropdowns.html', 15, false),)), $this); ?>
<div id="custom_dropdown<?php echo $this->_tpl_vars['drd_number']; ?>
" >
    <span style="font-style:italic;"><?php echo $this->_tpl_vars['drd_name']; ?>
</span>   
    
    <script type='text/javascript'>
        <?php if ($this->_tpl_vars['drd_number'] == 1): ?>     
            MyCalendar.currentDropdown1Options = '';
        <?php elseif ($this->_tpl_vars['drd_number'] == 2): ?>
            MyCalendar.currentDropdown2Options = '';
        <?php endif; ?>
    </script>
    
    <?php $_from = $this->_tpl_vars['dropdown']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>

        <div class="dropdown_option" >
            <div onclick="MyCalendar.addCustomOption('<?php echo $this->_tpl_vars['item']['option_id']; ?>
','<?php echo addslashes($this->_tpl_vars['item']['text']); ?>
','<?php echo $this->_tpl_vars['drd_number']; ?>
');" alt="<?php if (count($this->_tpl_vars['my_active_calendars']) > 1): ?>Click to show/hide<?php endif; ?>" title="<?php if (count($this->_tpl_vars['my_active_calendars']) > 1): ?>Click to show/hide<?php endif; ?>" id="dropdown_group<?php echo $this->_tpl_vars['item']['option_id']; ?>
" class="dropdown_<?php echo $this->_tpl_vars['drd_number']; ?>
_tick_on" option_id="<?php echo $this->_tpl_vars['item']['option_id']; ?>
" style="margin:6px 0 0 1px;cursor: pointer; float: left; width: 1.4em;cursor:pointer;font-size:12px;color:#FFFFFF;font-weight:bold;">
                    &nbsp;
                </div>

            <div  class="onedrdoption" id="caldiv_<?php echo $this->_tpl_vars['item']['calendar_id']; ?>
" style="width:122px;background-color:<?php if (! empty ( $this->_tpl_vars['item']['color'] )): ?><?php echo $this->_tpl_vars['item']['color']; ?>
<?php else: ?><?php echo $this->_tpl_vars['item']['calendar_color']; ?>
<?php endif; ?>" onclick="MyCalendar.openCustomOption('<?php echo $this->_tpl_vars['item']['option_id']; ?>
','<?php echo addslashes($this->_tpl_vars['item']['text']); ?>
','<?php echo $this->_tpl_vars['drd_number']; ?>
');" title="<?php if (count($this->_tpl_vars['my_active_calendars']) > 1): ?>Show only this option<?php endif; ?>" >
                <a id="calname<?php echo $this->_tpl_vars['item']['calendar_id']; ?>
" style="text-decoration: none;color: #EFEFEF;" href="#" >
                <span style="font-family:tahoma,arial;text-shadow: -1px 0 #9F9F9F, 0 1px #9F9F9F, 1px 0 #9F9F9F, 0 -1px #9F9F9F;color:#fff;padding-left:3px;"><?php echo $this->_tpl_vars['item']['text']; ?>
</span></a><?php if ($this->_tpl_vars['item']['origin'] == 'google'): ?><span style="float:right;"><img src="<?php echo @IMAGES_URL; ?>
/google-icon.png" title="Google Calendar" alt="Google Calendar" /></span><?php endif; ?>
                <!-- hier stond eerst span caldiv -->
            </div>
        </div>
    
        <script type='text/javascript'>
        <?php if ($this->_tpl_vars['drd_number'] == 1): ?>          
            if(MyCalendar.currentDropdown1Options === '') {
                MyCalendar.currentDropdown1Options += '<?php echo $this->_tpl_vars['item']['option_id']; ?>
';
            } else {
                MyCalendar.currentDropdown1Options += ',<?php echo $this->_tpl_vars['item']['option_id']; ?>
';
            }
        <?php elseif ($this->_tpl_vars['drd_number'] == 2): ?>
            if(MyCalendar.currentDropdown2Options === '') {
                MyCalendar.currentDropdown2Options += '<?php echo $this->_tpl_vars['item']['option_id']; ?>
';
            } else {
                MyCalendar.currentDropdown2Options += ',<?php echo $this->_tpl_vars['item']['option_id']; ?>
';
            }
        <?php endif; ?>    
        </script>

    <?php endforeach; endif; unset($_from); ?>
         
    <script type='text/javascript'>
        <?php if ($this->_tpl_vars['drd_number'] == 1): ?>    
            MyCalendar.initialDropdown1Options = MyCalendar.currentDropdown1Options;
        <?php elseif ($this->_tpl_vars['drd_number'] == 2): ?>
            MyCalendar.initialDropdown2Options = MyCalendar.currentDropdown2Options;
        <?php endif; ?>
    </script>

    <?php if (isset ( $this->_tpl_vars['dropdown'] )): ?>
            <div id="dropdown_div" class="all_cals" >
                    <div class="onecalendar-wrap">
                            <div class="onedrdoption" onclick="MyCalendar.openCustomOption('all','',<?php echo $this->_tpl_vars['drd_number']; ?>
);">
                                    &nbsp;&nbsp;&nbsp;Show all
                            </div>
                    </div>
            </div>
    <?php endif; ?>
    <br style="clear:left;" />
</div>