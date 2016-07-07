<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Register for free trial</title>

<link rel="stylesheet" type="text/css" href="<?smarty $smarty.const.FULLCAL_URL ?>/style/style.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="<?smarty $smarty.const.FULLCAL_URL ?>/register/css/view.css" media="all">


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
				<form id="form_837345"  method="post" action="?action=trial">
				<div class="form_description">
					<h3>Register for free 1-month trial</h3>

					 <?smarty if isset($msg) && !empty($msg) ?>
						<span style="color:red;position:relative;"><?smarty $msg ?></span>
					<?smarty /if ?>

				</div>
				<ul >
				<li>
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
				<li>
						<label class="description" for="element_3">Email </label>
						<div>
							<input id="element_3" name="email" class="element text medium" type="text" maxlength="255" value="<?smarty $form.email|default:"" ?>"/>
						</div>
					</li>
					
					<li>
						<label class="description" for="element_1">Username</label>
						<div>
							<input id="element_1" name="username" class="element text medium" type="text" maxlength="255" value="<?smarty $form.username|default:"" ?>"/>
						</div>
					</li>
                                        <li>
                                                <label class="description" for="element_1_1">Password</label>
                                                <div>
                                                        <input id="element_1_1" name="password" class="element text medium" type="password" maxlength="255" value=""/>
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
