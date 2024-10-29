<?php
if (!defined ('ABSPATH')) die ('No direct access allowed');
	$strReturn = '';
	if ( $_REQUEST['al3x_action'] == 'login' && strlen($_REQUEST['al3x_user']) > 0 && strlen($_REQUEST['al3x_pass']) > 0 ) {
		if (! al3x_user_login($_REQUEST['al3x_user'], $_REQUEST['al3x_pass'])) $strReturn .= '<div style="border: solid 2px #ff0000; background: #faaaac; color: #000000; padding: 5px; width: 100%; ">Error: Could not log in, please check credentials.</div>';
	}
	if ( $_REQUEST['al3x_action'] == 'logout') unset($_SESSION['al3x']);
	if ( isset($_SESSION['al3x']['sid']) && is_numeric($_SESSION['al3x']['uid']) ) {
			$strReturn .= '<div align="Right" style="border-bottom: solid 1px #000000; "><form action="" method="POST" style="margin: 0px;"><input type="Hidden" name="al3x_action" value="logout"><input type="Submit" value="log out"></form></div>';
		if (is_dir($al3x_set['updir'] . '/' . $_SESSION['al3x']['uid'] ) ) {
			$strReturn .= al3x_tree_js();
			$strReturn .= al3x_directory_html($_SESSION['al3x']['uid']);
		}
		else {
			$strReturn .= 'no files';
		}
	}
	else {
		$strReturn .= '<form action="" method="POST" style="margin: 0px; " ><input type="Hidden" name="al3x_action" value="login">
	<label>Username:<br /><input type="Text" name="al3x_user" ></label><br />
	<label>Password:<br /><input type="Password" name="al3x_pass" ></label><br />
	<input type="submit" value="log in" tabindex="100" />
</form>';
	}
?>
