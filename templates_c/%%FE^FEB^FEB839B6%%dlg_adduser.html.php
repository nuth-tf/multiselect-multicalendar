<?php /* Smarty version 2.6.18, created on 2016-05-15 18:58:22
         compiled from /Applications/XAMPP/xamppfiles/htdocs/employee-work-schedule/view/dialogs/dlg_adduser.html */ ?>
<div id="dialog-adduser" style="display: none;">
		<div id= "adduser_error_message" style="height:20px;font-size:10pt;color:#FF0004;" ></div>
		<form class="form-horizontal" method="post" action="">

			<div class="control-group">
                <label for="adduser_profile_title" class="control-label">Title </label>
                <div class="controls">
                    <input type="text" class="input-xlarge" id="profile_title" name="title" value="">
                </div>
            </div>
            <div class="control-group">
				<label id="adduser_name_label_id" class="control-label">Name </label>
				<div class="controls">
					<input style="width:94px;" type="text" class="input-xlarge" id="adduser_firstname" name="firstname" placeholder="Firstname" value="">
					<?php if (@SHOW_INFIX_IN_FORM): ?>
						<input style="width:30px;" type="text" class="input-xlarge" id="adduser_infix" name="infix" value="">
					<?php endif; ?>
					<input style="width:<?php if (@SHOW_INFIX_IN_FORM): ?>110<?php else: ?>170<?php endif; ?>px;" type="text" class="input-xlarge" id="adduser_lastname" name="lastname" placeholder="Lastname" value="">
				</div>
			</div>

			<div class="control-group">
				<label id="adduser_email_label_id" class="control-label">Email </label>
				<div class="controls">
					<input type="text" class="input-xlarge" id="adduser_email" name="email" value="">
				</div>
			</div>

			<?php if (@SHOW_USERNAME_IN_FORM): ?>
			<div class="control-group">
				<label id="adduser_username_label_id" class="control-label">Username </label>
				<div class="controls">
					<input type="text" autocomplete="off" class="input-xlarge" id="adduser_username" name="username" value="">
				</div>
			</div>
			<?php endif; ?>

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

			<?php if (@SEND_ACTIVATION_MAIL): ?>
				<p style="position:absolute;top:185px;font-style:italic;color:#AFAFAF;font-size:0.9em;" id="adduser_activationlink_text">The user can activate with the activation link included in the email.</p>
			<?php else: ?>
				<p style="position:absolute;top:185px;font-style:italic;color:#AFAFAF;font-size:0.9em;" id="adduser_password_text">A password will be generated and included in the email.</p>
			<?php endif; ?>


	</form>
		<!--<b>Hint:</b><span style="font-size: 10px;"> There may have been an all day booking, or perhaps a conflicting booking. Try booking another time slot!</span>-->

	</div>