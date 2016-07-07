<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Register</title>

<link rel="stylesheet" type="text/css" href="<?smarty $smarty.const.FULLCAL_URL ?>/style/style.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="<?smarty $smarty.const.FULLCAL_URL ?>/register/css/view.css" media="all">
<script type="text/javascript" src="<?smarty $smarty.const.FULLCAL_URL ?>/register/js/view.js"></script>
<script type="text/javascript" src="<?smarty $smarty.const.FULLCAL_URL ?>/register/js/calendar.js"></script>

</head>

<body>

	<!-- body start -->
	<div id="wrap">
		<!-- top shadow start -->

		<!-- top shadow end -->
		<!-- body pannel start -->
		<div id="bodyPannel">
			<?smarty if $success ?>
				 <?smarty if isset($msg) && !empty($msg) ?>
					<span style="color:green;position:relative;"><?smarty $msg ?></span>
				<?smarty /if ?>
			<?smarty else ?>
				<form id="form_837345"  method="post" action="?action=register">
				<div class="form_description">
					<h3>Register</h3>

					 <?smarty if isset($msg) && !empty($msg) ?>
						<span style="color:red;position:relative;"><?smarty $msg ?></span>
					<?smarty /if ?>

				</div>
				<ul >
				<li id="li_4" >
					<label class="description" for="element_4">Name </label>
					<span>
						<input id="element_4_1" type="text" name= "firstname" class="element text" maxlength="255" size="25" value="<?smarty $form.firstname|default:"" ?>"/>
						<label>Firstname</label>
					</span>
					<span>
						<input id="element_4_2" type="text" name= "infix" class="element text" maxlength="255" size="8" value="<?smarty $form.infix|default:"" ?>"/>
						<label>Infix [optional]</label>
					</span>
					<span>
						<input id="element_4_3" type="text" name= "lastname" class="element text" maxlength="255" size="25" value="<?smarty $form.lastname|default:"" ?>"/>
						<label>Lastname</label>
					</span>
				</li>
				<li id="li_2" >
					<label class="description" for="element_2">Birthdate </label>
					<span>
						<input id="element_2_1" name="month" class="element text" size="2" maxlength="2" value="<?smarty $form.month|default:"" ?>" type="text"> /
						<label for="element_2_1">MM</label>
					</span>
					<span>
						<input id="element_2_2" name="day" class="element text" size="2" maxlength="2" value="<?smarty $form.day|default:"" ?>" type="text"> /
						<label for="element_2_2">DD</label>
					</span>
					<span>
				 		<input id="element_2_3" name="year" class="element text" size="4" maxlength="4" value="<?smarty $form.year|default:"" ?>" type="text">
						<label for="element_2_3">YYYY</label>
					</span>

					<span id="calendar_2">
						<img id="cal_img_2" class="datepicker" src="calendar.gif" alt="Pick a date.">
					</span>
					<script type="text/javascript">
						Calendar.setup({
						inputField	 : "element_2_3",
						baseField    : "element_2",
						displayArea  : "calendar_2",
						button		 : "cal_img_2",
						ifFormat	 : "%B %e, %Y",
						onSelect	 : selectDate
						});
					</script>

					</li>
					<li id="li_1" >
						<label class="description" for="element_1">Username</label>
						<div>
							<input id="element_1" name="username" class="element text medium" type="text" maxlength="255" value="<?smarty $form.username|default:"" ?>"/>
						</div>
					</li>
					<?smarty if !$smarty.const.SEND_ACTIVATION_MAIL ?>
						<li id="li_1" >
							<label class="description" for="element_1_1">Password</label>
							<div>
								<input id="element_1_1" name="password" class="element text medium" type="password" maxlength="255" value=""/>
							</div>
						</li>
					<?smarty /if ?>
					<li id="li_3" >
						<label class="description" for="element_3">Email </label>
						<div>
							<input id="element_3" name="email" class="element text medium" type="text" maxlength="255" value="<?smarty $form.email|default:"" ?>"/>
						</div>
					</li>
					<li>
						<img src="<?smarty $smarty.const.FULLCAL_URL ?>/lib/picgen.php?image=captcha&font=segoeprb&size=15&color=347235&rgb_bg=255,255,255&sid=<?php echo rand(10000,99999); ?>">
					</li>
					<li>
						Type the code &nbsp;&nbsp;<input type="text" name="captchacode" value="<?smarty $form.captchacode ?>">

					</li>
						<br />
					<li>
						<input type="checkbox" name="agree_conditions"/> I have read and agree to the <a href="<?smarty $smarty.const.FULLCAL_URL ?>/terms-of-use.html" target="_blank">terms of use</a>
					</li>
					<li class="buttons">
					    <input type="hidden" name="form_id" value="837345" />

						<input id="saveForm" class="button_text" type="submit" name="submit" value="Submit" />
					</li>

				</ul>
			</form>
		<?smarty /if ?>

		</div>
		<!-- body pannel end -->

		<br class="spacer" />

	</div>
	<!-- body end -->

</body>
</html>
