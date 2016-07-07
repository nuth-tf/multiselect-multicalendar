<?php /* Smarty version 2.6.18, created on 2016-05-15 18:58:22
         compiled from /Applications/XAMPP/xamppfiles/htdocs/employee-work-schedule/view/dialogs/dlg_profile.html */ ?>
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


		</style>

	<div id="dialog-profile" style="display: none;">
		<div id= "profile_error_message" style="height:20px;font-size:10pt;color:#FF0004;" ></div>
		<form class="form-horizontal" method="post" action="">

			<div class="control-group">
                <label for="profile_title" class="control-label">Title </label>
                <div class="controls">
                    <input type="text" class="input-xlarge" id="profile_title" name="title" value="<?php echo $this->_tpl_vars['profile']['title']; ?>
">
                </div>
            </div>
            <div class="control-group">
				<label id="profile_name_label_id" class="control-label">Name </label>
				<div class="controls">
					<input style="width:100px;" type="text" class="input-xlarge" id="profile_firstname" name="firstname" placeholder="Firstname" value="<?php echo $this->_tpl_vars['profile']['firstname']; ?>
">
					<?php if (@SHOW_INFIX_IN_FORM): ?>
						<input style="width:40px;" type="text" class="input-xlarge" id="profile_infix" name="infix" value="<?php echo $this->_tpl_vars['profile']['infix']; ?>
">
					<?php endif; ?>
					<input style="width:<?php if (@SHOW_INFIX_IN_FORM): ?>110<?php else: ?>170<?php endif; ?>px;" type="text" class="input-xlarge" id="profile_lastname" name="lastname" placeholder="Lastname" value="<?php echo $this->_tpl_vars['profile']['lastname']; ?>
">
				</div>
			</div>

			<div class="control-group">
				<label id="profile_birthdate_label_id" class="control-label">Birthdate </label>
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
				<label id="profile_country_label_id" class="control-label">Country </label>
				<div class="controls">
					<input type="text" class="input-xlarge" id="profile_country" name="country" value="<?php echo $this->_tpl_vars['profile']['country']; ?>
">
				</div>
			</div>

			<div class="control-group">
				<label id="profile_email_label_id" class="control-label">Email </label>
				<div class="controls">
					<input type="text" class="input-xlarge" id="profile_email" name="email" value="<?php echo $this->_tpl_vars['profile']['email']; ?>
">
				</div>
			</div>

			<div class="control-group">
				<label id="profile_username_label_id" class="control-label">Username </label>
				<div class="controls">
					<input type="text" class="input-xlarge" id="profile_username" name="username" value="<?php echo $this->_tpl_vars['profile']['username']; ?>
">
				</div>
			</div>

			<div class="control-group">
				<label id="profile_new_password_label_id" class="control-label">New password </label>
				<div class="controls">
					<input type="password" autocomplete="off" class="input-xlarge" id="profile_password" name="password" placeholder="Leave blank for no change">
				</div>
			</div>

			<div class="control-group">
				<label id="profile_new_password2_label_id" class="control-label">New password again <a tabindex="99" ></label>
				<div class="controls">
					<input type="password" autocomplete="off" class="input-xlarge" id="profile_confirm" name="confirm" placeholder="Leave blank for no change">
				</div>
			</div>
			<input type="hidden" name="user_id" value="<?php echo $this->_tpl_vars['profile']['user_id']; ?>
" />

		</form>
		<!--<b>Hint:</b><span style="font-size: 10px;"> There may have been an all day booking, or perhaps a conflicting booking. Try booking another time slot!</span>-->

	</div>