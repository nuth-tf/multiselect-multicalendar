<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Forgotten password</title>

<link rel="stylesheet" type="text/css" href="<?smarty $smarty.const.FULLCAL_URL ?>/style/style.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="<?smarty $smarty.const.FULLCAL_URL ?>/register/css/view.css" media="all">
<!--<script type="text/javascript" src="<?smarty $smarty.const.FULLCAL_URL ?>/register/js/view.js"></script>
<script type="text/javascript" src="<?smarty $smarty.const.FULLCAL_URL ?>/register/js/calendar.js"></script>-->

</head>

<body>

	<!-- body start -->
	<div id="wrap">

		<!-- body pannel start -->
		<div id="bodyPannel">
			<?smarty if $success ?>
				 <?smarty if isset($msg) && !empty($msg) ?>
					<span style="color:green;position:relative;"><?smarty $msg ?></span>
				<?smarty /if ?>
			<?smarty else ?>

				<form id="form_837763"  method="post" action="?action=reset">
					<div class="form_description">
						<h3>Reset your password</h3>

						 <?smarty if isset($msg) && !empty($msg) ?>
							<span style="color:red;position:relative;"><?smarty $msg ?></span>
						<?smarty /if ?>

					</div>
					<ul >
						<li id="li_4" >
							<span>
								<input type="text" name= "email" class="element text" maxlength="255" size="25" value="<?smarty $form.firstname|default:"" ?>"/>
								<label>Email</label>
							</span>
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
