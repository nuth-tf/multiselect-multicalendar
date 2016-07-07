<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Activate your account</title>

<link rel="stylesheet" type="text/css" href="<?smarty $smarty.const.FULLCAL_URL ?>/register/css/view.css" media="all">
<script type="text/javascript" src="<?smarty $smarty.const.FULLCAL_URL ?>/register/js/view.js"></script>
<script type="text/javascript" src="<?smarty $smarty.const.FULLCAL_URL ?>/register/js/calendar.js"></script>

</head>

<body>

	<div id="wrap">

		<!-- body start -->
		<div id="body">
			<!-- top shadow start -->
			<div id="topShadow"></div>
			<!-- top shadow end -->
			<!-- body pannel start -->
			<div id="bodyPannel">

				<?smarty if $forgotten_password ?>
					<div class="form_description"><h3>Reset your password</h3></div>
					<p>Fill in a new password</p>
				<?smarty else ?>
					<h3></h3>
					<p>Your account has been activated after you fill in your password</p>
				<?smarty /if ?>

					<form method="POST" action="<?smarty $smarty.const.FULLCAL_URL ?>/index.php?action=change_password">
	              	<ul >
                            <li>
                                    <span>
                                            <input type="password" name="passw1" class="element text" maxlength="255" size="25" value="<?smarty $form.firstname|default:"" ?>"/>
                                            <label>Password</label>
                                    </span>
                            </li>
                            <li>
                                    <span>
                                            <input type="password" name="passw2" class="element text" maxlength="255" size="25" value="<?smarty $form.infix|default:"" ?>"/>
                                            <label>Confirm your password</label>
                                    </span>
                            </li>

                            <li class="buttons">
                                    <input type="submit" name="submit" class="button_text" value="Submit" />
                            </li>
	              	</ul>


	              	<input type="hidden" name="uid" value="<?smarty $uid ?>" />

	              </form>

			</div>
			<!-- body pannel end -->
			<!-- top shadow start -->
			<div id="bottomShadow"></div><br class="spacer" />
			<!-- bottom shadow end -->
		</div>
	</div>
	<!-- body end -->

</body>
</html>
