<?php
if (!defined ('ABSPATH')) die ('No direct access allowed');
	if ( $_REQUEST['killdonatenote'] == 1) al3x_killdonatenote();
	// before we do anything, we will verify the upload directory!
	if ( al3x_verify_updir() ) {
	// functionality goes here

	// add user
	if ( $_POST['action'] == "add" && isset($_POST['newuser']) && isset($_POST['newpass']) ) {
		if ( al3x_edit_user($_POST['newuser'], $_POST['newpass'] ) ) al3x_notify('Success: User created');
		else al3x_notify('Error: User could not be created! <br />User already exists or illegal characters used. Please use alphanumeric characters only for username and password.','error');
	}
	// edit user by id
	if ( $_POST['action'] == "edit" && is_numeric($_POST['uid']) && isset($_POST['edituser']) && isset($_POST['editpass'])) {
		if ( al3x_edit_user($_POST['edituser'], $_POST['editpass'], $_POST['uid'] ) ) al3x_notify('Success: User modified');
	}
	// delete user by id
	if ( $_REQUEST['action'] == "delete" && is_numeric($_REQUEST['uid']) ) {
		al3x_del_user_by_id($_REQUEST['uid']);
		al3x_notify('Success: User deleted');
	}
	// read in current users
// later: you can specify $where and $order in following function
	$users = al3x_get_all_users();
?><div class="wrap">
	<h2>File Manager: user</h2>
	<div style="width: 640px; ">
		<form action="<?php echo $_SERVER['SCRIPT_NAME']?>?page=file_manager/user" style="margin: 0px;" method="POST">
		<input type="Hidden" name="action" value="add">
		<table border="0" class="widefat">
		<colgroup>
			<col width="40" />
			<col width="150" />
			<col width="150" />
			<col width="300" />
		</colgroup>
		<thead>
			<tr>
				<th>id</th>
				<th>username</th>
				<th>password</th>
				<th>options</th>
			</tr>
		</thead>
		<tbody><?php
			if ($users)
			{
				foreach ($users as $user)
				{
					?>
			<tr><?php
					if ( $_REQUEST['action'] == 'change' && is_numeric($_REQUEST['uid']) && $_REQUEST['uid'] == $user->id ) { ?>
				<td><?php echo $user->id; ?></td>
				<td><input type="Text" name="edituser" value="<?php echo $user->uname; ?>" style="width: 150px; background-color: #c0c0c0; " /></td>
				<td><input type="Text" name="editpass" value="<?php echo $user->pword; ?>" style="width: 150px; background-color: #c0c0c0; " /></td><? 
					}
					else { ?>
				<td><?php echo $user->id; ?></td>
				<td><?php echo $user->uname; ?></td>
				<td><?php echo $user->pword; ?></td><?
					} ?>
				<td><?php
					if ( $_REQUEST['action'] == 'change' && is_numeric($_REQUEST['uid']) && $_REQUEST['uid'] == $user->id ) { ?><input type="Hidden" name="action" value="edit"><input type="Hidden" name="uid" value="<?php echo $user->id; ?>" /><input type="Submit" name="changeuser" value="edit"> | <?
				} ?><i><a href="<?php echo $_SERVER['SCRIPT_NAME']?>?page=file_manager/file&uid=<?php echo $user->id; ?>">user files</a> |
					<a href="<?php echo $_SERVER['SCRIPT_NAME']?>?page=file_manager/user&action=change&uid=<?php echo $user->id; ?>">edit user</a> |
					<a href="<?php echo $_SERVER['SCRIPT_NAME']?>?page=file_manager/user&action=delete&uid=<?php echo $user->id; ?>" onClick="javascript: return confirm('This will delete the user <?php echo $user->uname ?> (ID: <?php echo $user->id; ?>) and all assocciated files! There is no restore available, do you really want to execute?')"><span style="color: red; ">delete</span></a></i></td>
			</tr><?php
				}
			}
			?>
			<tr>
                                <td><i>pub</i></td>
                                <td><i>[anonymous]</i></td>
                                <td><i>[none]</i></td>
                                <td><i><a href="<?php echo $_SERVER['SCRIPT_NAME']?>?page=file_manager/file&uid=PUBLIC">public files</a></i></td>
                        </tr>

			<tr>
                                <td><b><i>new</i></b></td>
                                <td><input type="Text" name="newuser" style="width: 150px; " /></td>
                                <td><input type="Text" name="newpass" style="width: 150px; " /></td>
                                <td><input type="Submit" value="add" /></td>
                        </tr>
		</tbody>
		</table>
		</form><?php include('donatenote.php'); ?>
	</div>
</div>
<?php
}
else {
	// verifying upload directory has failed, report that !
?>
<div class="wrap">
	<div class="error"><b>ATTENTION:</b> <br /><br />Upload directory "<tt><?php echo $al3x_set['updir']; ?></tt>" either does not exist or is not writable for webserver! Please attend to this misconfiguration first, before you use this plugin.</div>
</div>
<?php
} ?>


