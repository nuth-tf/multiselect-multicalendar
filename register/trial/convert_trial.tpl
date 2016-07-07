<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
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
			
		<div class="form_description">
					<h3>Convert your trial version to a full subscription</h3>

					 <?smarty if isset($msg) && !empty($msg) ?>
						<span style="color:red;position:relative;"><?smarty $msg ?></span>
					<?smarty /if ?>

				</div>
                    <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
                        <input type="hidden" name="cmd" value="_s-xclick">
                        <input type="hidden" name="hosted_button_id" value="Y2PNTDXUQ579C">
                            <input type="hidden" name="business" value="EWS">
                            <table>
                        <tr><td><input type="hidden" name="on0" value="Account type">Account type</td></tr><tr><td><select name="os0">
                                <option value="1 month">1 month €1,00 EUR</option>
                                <option value="6 month">6 month €6,00 EUR</option>
                                <option value="1 year">1 year €10,00 EUR</option>
                        </select> </td></tr>
                        </table>
                        <input type="hidden" name="currency_code" value="EUR">
                        <input type="image" src="https://www.paypalobjects.com/en_GB/i/btn/btn_buynow_LG.gif" border="0" name="submit" alt="PayPal – The safer, easier way to pay online.">
                        <img alt="" border="0" src="https://www.paypalobjects.com/nl_NL/i/scr/pixel.gif" width="1" height="1">
                        </form>

         

		</div>
		<!-- body pannel end -->

		<br class="spacer" />

	</div>
	<!-- body end -->

</body>
</html>
