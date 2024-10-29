<?php
if (!defined ('ABSPATH')) die ('No direct access allowed');
	if ( $_REQUEST['killdonatenote'] == 1) al3x_killdonatenote();
	if ( is_numeric($_REQUEST['newuid']) && $_REQUEST['newuid'] > 0 ) {
		$uid = $_REQUEST['newuid'];
	}
	else {
		$uid = $_REQUEST['uid'];
	}
	if ( $_REQUEST['uid'] == 'PUBLIC' || $_REQUEST['newuid'] == 'PUBLIC') $uid = '0';
	if ( is_numeric($uid) && $uid > 0) {
		$user = al3x_get_user_by_id($uid);
		$intUid = $user[0]->id;
	}
	elseif ( is_numeric($uid) && $uid == 0) {
		$intUid = 0;
	}
	if ( is_numeric($intUid) && !is_dir($al3x_set['updir'] . '/' . $intUid) ) {
		if ( !mkdir($al3x_set['updir'] . '/' . $intUid) ) {
			al3x_notify('FATAL ERROR: user-upload-directory can not be created! ', 'error');
		}
	}
	if ( isset($_POST['uploadfile']) && isset($_FILES['newfile']['tmp_name']) && is_numeric($_POST['upd']) ) {
		$arrList = explode('.', $_FILES['newfile']['name'] );
		$extention = array_pop($arrList);
		$newname = preg_replace("/\W/", "_", implode('_', $arrList) ) . '.' . preg_replace("/\W/", "_", $extention);
		if ($newname[0] == '.') $newname = substr($newname, 1);
		$destination = $al3x_set['updir'] . '/' . $intUid . '/' .  substr($_SESSION[$_POST['akey']][$_POST['upd']]['path'] , 1) . $newname;
		if (move_uploaded_file($_FILES['newfile']['tmp_name'], $destination) ) {
			al3x_notify('Success: File uploaded!');
		}
		else {
			al3x_notify('Error: Uploaded file could not be copied to destination, keep in mind, host limitation of filesize and filetype may apply! Current value of upload_max_filesize directive is ' . ini_get('upload_max_filesize') . '!', 'error');
		}
	}
	elseif ( isset($_POST['createdir']) && strlen($_POST['newdir']) > 0 && is_numeric($_POST['cpd']) ) {
		$fullpath = $al3x_set['updir'] . '/' . $intUid . '/' .  substr($_SESSION[$_POST['akey']][$_POST['cpd']]['path'] , 1);
		if (al3x_create_dir($_POST['newdir'], $fullpath ) ) {
			al3x_notify('Success: Directory created!');
		}
		else {
			al3x_notify('Error: Directory could not be created!', 'error');
		}
	}
	elseif ( isset($_GET['action']) && $_GET['action'] == 'delete' && is_numeric($_GET['uid']) ) {
		if ( is_file($al3x_set['updir'] . '/' . $intUid . '/' . $_GET['delpath']) ) {
			if (unlink($al3x_set['updir'] . '/' . $intUid . '/' . $_GET['delpath']) ) al3x_notify('Success: File deleted!');
			else al3x_notify('Error: File could not be deleted!', 'error');
		}
		elseif ( is_dir($al3x_set['updir'] . '/' . $intUid . '/' . $_GET['delpath']) ) {
			if (rmdir($al3x_set['updir'] . '/' . $intUid . '/' . $_GET['delpath'])) al3x_notify('Success: Directory deleted!');
			else al3x_notify('Error: Directory could not be deleted, maybe directory is not empty!', 'error');
		}
	}
?><div class="wrap">
<h2>File Manager: files</h2>
<div style="width: 640px; ">
	<form action="<?php echo $_SERVER['SCRIPT_NAME']?>?page=file_manager/file" name="al3xform" style="margin: 0px;" method="POST" enctype="multipart/form-data">
	<table border="0" class="widefat">
                <colgroup>
                        <col width="10" />
                        <col width="320" />
                        <col width="260" />
                        <col width="50" />
                </colgroup>
<?php
	if (is_numeric($intUid)) {
		$user = al3x_get_user_by_id($intUid);
		if (is_dir($al3x_set['updir'] . '/' . $intUid) ) {
			chdir($al3x_set['updir'] . '/' . $intUid);
			$arrDirectories = getFileList('.' , true);
			// important: add root directory
				$rootdir = array("name" => '.', "path" => './', "type" => 'dir', "size" => 0, "lastmod" => filemtime('.') );
				array_unshift($arrDirectories, $rootdir );
			$sesskey = md5(serialize($arrDirectories));
			$_SESSION[$sesskey] = $arrDirectories;
			$htmloption = al3x_build_html_options($arrDirectories);
		}
		else {
			al3x_notify('FATAL ERROR: No directory to browse!', 'error');
		}
?>
		<thead>
                        <tr>
				<th colspan="2"><?php
				if ($intUid == 0) { ?>manage public files (user: "anonymous") <?php }
				else { ?>manage files for user "<?php echo $user[0]->uname ;?>" (ID: <?php echo $user[0]->id ;?>)<?php } ?></th>
				<th colspan="2" align="Right"><div align="Right"><select size="1" name="newuid" onChange="document.forms['al3xform'].submit();" style="margin: 0px; padding-top: 0px; padding-bottom: 0px; height: 16px; width: 200px; font-size: 9px;">
                                        <option value="x" selected="selected">change user</option>
                                <?php
                                        $arrUser = al3x_get_all_users();
                                        foreach ($arrUser as $user) { ?>
                                        <option value="<?php echo $user->id; ?>"><?php echo $user->uname; ?></option><?php   } ?>
					<option value="PUBLIC" style="font: italic bold">[public files: anonymous]</option>
                                </select></div></th>
                        </tr>
                </thead>
		<tbody>
			<tr>
				<td colspan="4"><big>file upload</big></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td valign="Top">select file:<br /><input type="file" name="newfile" /></td>
				<td valign="Top">store in directory:<br /><select name="upd" size="1" style="width: 250px;">
				<?php echo $htmloption; ?>
					</select><input type="Hidden" name="akey" value="<?php echo $sesskey; ?>"/></td>
				<td valign="Top">&nbsp;<br /><input type="Hidden" name="uid" value="<?php echo $uid; ?>" /><input type="Submit" name="uploadfile" value="upload" /></td>
			</tr>
			<tr>
                                <td colspan="4"><br /><big>create directory</big></td>
                        </tr>
                        <tr>
                                <td>&nbsp;</td>
                                <td valign="Top">directory name:<br /><input type="text" name="newdir" /></td>
                                <td valign="Top">parent directory:<br /><select name="cpd" size="1" style="width: 250px;">
					<?php echo $htmloption; ?>
                                        </select><input type="Hidden" name="akey" value="<?php echo $sesskey; ?>"/></td>
                                <td valign="Top">&nbsp;<br /><input type="Hidden" name="uid" value="<?php echo $uid; ?>" /><input type="Submit" name="createdir" value="create" /></td>
                        </tr>

			<tr>
				<td colspan="4"><br /><big>directory listing</big></td>
			</tr>
                        <tr>
                                <td>&nbsp;</td>
                                <td valign="Top" colspan="3"><?php echo al3x_directory_html($intUid); ?></td>
			</tr>
		</tbody>
<?php
	}
	else {
?>
<thead>
                        <tr>
                                <th colspan="4">User select</th>
                        </tr>
                </thead>
                <tbody>
			<tr>
				<td colspan="4">Please select user to manage files and directories.</td>
			</tr>
                        <tr>
                                <td>&nbsp;</td>
                                <td valign="Top" colspan="3"><select size="1" name="uid" onChange="document.forms['al3xform'].submit();" style="width: 400px; ">
					<option value="x" selected="selected">select user</option>
				<?php
					$arrUser = al3x_get_all_users();
					foreach ($arrUser as $user) { ?>
					<option value="<?php echo $user->id; ?>"><?php echo $user->uname; ?></option><?php } ?>
					<option value="PUBLIC" style="font: italic bold">[public files: anonymous]</option>
				</select>
				</td>
                        </tr>
		</tbody>
<?php
	}
?>
	</table>
	</form><?php include('donatenote.php'); ?>
	</div>
</div>
