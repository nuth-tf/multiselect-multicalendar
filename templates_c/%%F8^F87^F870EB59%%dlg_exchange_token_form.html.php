<?php /* Smarty version 2.6.18, created on 2016-05-15 18:58:22
         compiled from /Applications/XAMPP/xamppfiles/htdocs/employee-work-schedule/view/dialogs/dlg_exchange_token_form.html */ ?>
<div id="dialog-exchange-token" style="display: none;">
		<div id= "adduser_error_message" style="height:20px;font-size:10pt;color:#FF0004;" ></div>
        <form class="form-horizontal" method="post" id="exchange_token_form" action="<?php echo @FULLCAL_URL; ?>
/?action=save_token">

			<div class="control-group">
				<label id="exchange_token_label_id" class="control-label">Token </label>
				<div class="controls">
					<input type="text" class="input-xlarge" id="exchange_token" name="exchange_token" value="">
				</div>
			</div>

	</form>
		<!--<b>Hint:</b><span style="font-size: 10px;"> There may have been an all day booking, or perhaps a conflicting booking. Try booking another time slot!</span>-->

	</div>