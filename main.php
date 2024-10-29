<?php
/*
Plugin Name: al3x File Manager
Description: This plugin enables you to offer file downloads to specific users, whereas users are _not_ wp users! File locations are secured by .htaccess and downloads are session (and therefor user) bound. To embed use [[afm_page]] placeholder.
Version: 1.2
Author: Alexander Strestik
Plugin URI: http://www.al3x.de/file_manager/
Author URI: http://www.al3x.de
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=89TLK89BQB5W6
License: GPLv3

+=======================================================================================+
|                                                                                       |
|    Copyright 2010  Alexander Strestik  (email : alexander@strestik.de)                |
|                                                                                       |
|    This program is free software; you can redistribute it and/or modify               |
|    it under the terms of the GNU General Public License, version 3, as                |
|    published by the Free Software Foundation.                                         |
|                                                                                       |
|    This program is distributed in the hope that it will be useful,                    |
|    but WITHOUT ANY WARRANTY; without even the implied warranty of                     |
|    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the                      |
|    GNU General Public License for more details.                                       |
|                                                                                       |
|    You should have received a copy of the GNU General Public License                  |
|    along with this program; if not, write to the Free Software                        |
|    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA         |
|                                                                                       |
+=======================================================================================+

+=======================================================================================+
|                                                                                       |
|    Respect due, respect given                                                         |
|                                                                                       |
|    This wordpress plugin makes use of partly modified but previously published        |
|    free code, namely of these scripts:                                                |
|                                                                                       |
|     - jQuery.treeview pre1.5 by Jörn Zaefferer (2007)                                 |
|     - getFileList() function by www.chirp.com.au (for original header see function)   |
|     - format_bytes() function ( here al3x_format_size() ) by joaoptm78 via php.net    |
|                                                                                       |
+=======================================================================================+

*/
session_start();
if (!defined ('ABSPATH')) die ('No direct access allowed');
// do not change, these are not editable settings, these are shortcuts for my programming convenience
$al3x_plugin_dir = '/al3x-file-manager/';
$al3x_set['url'] = WP_PLUGIN_URL . $al3x_plugin_dir;
$al3x_set['dir'] = WP_PLUGIN_DIR . $al3x_plugin_dir;
$al3x_set['updir'] = WP_PLUGIN_DIR . $al3x_plugin_dir . 'uploads';
$al3x_set['usertab'] = $wpdb->prefix . 'al3x_fl_mngr_users';

// file download hook and function
if ($_REQUEST['al3x_download'] == 'file') {
	add_action('init', 'al3x_download');
}
// AJAX hook, only for filemanager
if ($_REQUEST['page'] == 'file_manager/file') {
	add_action('admin_head', 'al3x_display_js');
	function al3x_display_js() {
		echo al3x_tree_js();
	}
}
// admin menue hook
add_action('admin_menu','al3x_adm_menu');
function al3x_adm_menu() {
	global $al3x_set;
	add_menu_page(__('File Manager'), 'File Manager', 4, 'file_manager/user', 'al3x_file_manager_user', $al3x_set['url'] . '/images/afm.png' );
	add_submenu_page('file_manager/user', 'File Manager: manage users', 'user panel', 4, 'file_manager/user', 'al3x_file_manager_user');
	add_submenu_page('file_manager/user', 'File Manager: manage files', 'file panel', 4, 'file_manager/file', 'al3x_file_manager_file');
	add_submenu_page('file_manager/user', 'File Manager: faq', 'faq', 4, 'file_manager/faq', 'al3x_file_manager_faq');
}
function al3x_file_manager_user() {
	global $wpdb, $al3x_set;
	include('usermanager.php');
}
function al3x_file_manager_file() {
        global $wpdb, $al3x_set;
	include('filemanager.php');
}
function al3x_file_manager_faq() {
	include('faq.php');
}
// frontend hooks
add_action('the_content', 'al3x_frontend');
// activation and deactivation hooks and functions
register_activation_hook(__FILE__, 'install_al3x_file_manager');
register_uninstall_hook(__FILE__,'uninstall_al3x_file_manager');
function install_al3x_file_manager() {
	global $wpdb, $al3x_set;
	$tab1 = 'CREATE TABLE IF NOT EXISTS `' . $wpdb->prefix . 'al3x_fl_mngr_users' . '` (
`id` BIGINT( 23 ) UNSIGNED NOT NULL AUTO_INCREMENT ,
`uname` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`pword` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
PRIMARY KEY ( `id` ) ,
UNIQUE ( `uname` )
) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT = \'al3x file manager table containing user information\';';

	$file_path=ABSPATH . 'wp-admin/includes/upgrade.php';
        require_once($file_path);
	dbDelta($tab1);
}
function uninstall_al3x_file_manager() {
	global $al3x_set, $wpdb;
	$sql = 'DROP TABLE IF EXISTS `' . $wpdb->prefix . 'al3x_fl_mngr_users' . '`';
	$wpdb->query($sql);
}
// various functions
function al3x_download() {
        global $al3x_set;
        global $current_user;
        if (! headers_sent()) {
		$user_info = get_userdata($current_user->ID);
		$userlevel = (int)$user_info->user_level;
                if ( ( $userlevel >= 4 &&  is_numeric($_REQUEST['userid']) ) || ( is_numeric($_REQUEST['userid']) && md5($_REQUEST['userid'] . $_SESSION['al3x']['sid']) == $_REQUEST['checksum']) || $_REQUEST['userid'] == 'PUBLIC') {
			if ($_REQUEST['userid'] == 'PUBLIC') $upath = '0';
			else $upath = $_REQUEST['userid'];
                        $al3xfile = $al3x_set['updir'] . '/' . $upath . '/' . addslashes($_REQUEST['filepath']);
                        if (is_file($al3xfile)) {
				header('HTTP/1.0 200 OK');
				header('Cache-Control: no-cache, must-revalidate');
				header('Content-Description: File Transfer');
				header('Content-Disposition: attachment; filename='.basename($al3xfile));
                                header('Content-Type: application/octet-stream');
                                header('Content-Transfer-Encoding: binary');
				header('Expires: 0');
				header('Pragma: public');
				header('Content-Length: ' . filesize($al3xfile));
                                ob_clean();
                                flush();
                                readfile($al3xfile);
                        }
                }
		else {
	                header('HTTP/1.0 403 Forbidden');
			header('Content-Type: text/plain');
	                echo "error 403: no permission";
	        }
        }
        exit();
}
function al3x_create_dir($dirname, $parentdir) {
	$return_val = false;
	$dirname = preg_replace("/\W/", "_", $dirname);
	if ( chdir($parentdir)) {
		mkdir($dirname);
		$return_val = true;
	}
	return $return_val;
}
function al3x_get_user_by_id($id) {
	global $wpdb, $al3x_set;
	if (is_numeric($id) ) {
		$sql = 'SELECT * FROM `' . $al3x_set['usertab'] . '` WHERE id = "' . $id . '"';
		$re = $wpdb->get_results($sql);
	}
	return $re;
}
function al3x_del_user_by_id($id) {
	global $wpdb, $al3x_set;
	$sql = 'DELETE FROM `' . $al3x_set['usertab'] . '` WHERE `' . $al3x_set['usertab'] . '`.`id` = ' . $id ;
	$wpdb->query($sql);
	if (is_dir($al3x_set['updir']. '/' . $id) ) al3x_delete_dir_tree($al3x_set['updir'] . '/' . $id);
}
function al3x_edit_user($uname, $pword, $uid = '' ) {
	global  $wpdb, $al3x_set;
	$return_value = false;
	if ( ctype_alnum(trim($uname)) && trim($pword) )
	{
		if (is_numeric($uid) ) $mode = "REPLACE";
		else $mode = "INSERT";
		$sql = $mode . ' INTO ' . $al3x_set['usertab'] . ' VALUES("' . $uid . '","' . $uname . '","' . $wpdb->escape($pword) . '")';
		if ($wpdb->query($sql)) $return_value = true;
	}
	return $return_value;
}
function al3x_notify($msg,$type='updated') {
	echo '<div class="' . $type . ' fade">' . $msg . '</div>';
}
function al3x_verify_updir() {
	global $al3x_set;
	$return_val = false;
	$htac = '.htaccess';
	if (is_writable($al3x_set['updir'] . '/' . $htac) ) {
		$return_val = true;
	}
	elseif (is_dir($al3x_set['updir']) ) {
		if ($hf = fopen($al3x_set['updir'] .'/'. $htac, 'w') ) {
			fwrite($hf, "order allow,deny\ndeny from all" );
			fclose($hf);
			$return_val = true;
		}
	}
	if ($indexf = fopen($al3x_set['updir'] .'/index.php', 'w') ) {
                        fwrite($indexf, "\n" );
                        fclose($indexf);
	}
	return $return_val;
}
function al3x_get_all_users($where='1 = 1', $order='uname') {
	global $wpdb, $al3x_set;
	$sql = 'SELECT * FROM `' . $al3x_set['usertab'] . '` WHERE ' . $where . ' ORDER BY ' . $order ;
        return $wpdb->get_results($sql);
}
function al3x_build_html_options( $arrDir, $index="0", $levelprefix=">&nbsp;" ) {
	if (is_array($arrDir) ) {
		// sort by path!
		foreach ($arrDir as $key => $arrEntry) {
			if ($arrEntry['type'] == 'dir') $arrSortbyPath[$arrEntry['path']] = $key;
		}
		ksort($arrSortbyPath);
		// sort end
		foreach ($arrSortbyPath as $path => $key) {
			if ($key == 0) $path = 'root directory (default)';
			else $path = substr($path, 2);
			$htmloption .= '<option value="'.$key.'"';
			if ($key == 0) $htmloption .= ' style="font-weight: bold; " selected="selected" ';
			$htmloption .= '>`-> '.$path.'</option>';
		}
	}
	return $htmloption;
}
function getFileList($dir, $recurse=false, $depth=false) {
	/*
		this function is based on the works of: www.chirp.com.au
		original header:
			# Original PHP code by Chirp Internet: www.chirp.com.au
			# Please acknowledge use of this code by including this header.
	*/
	global $al3x_set;
	$retval = array();
	if(substr($dir, -1) != "/") $dir .= "/";
	$d = dir($dir);
	while(false !== ($entry = $d->read())) {
		if($entry[0] == ".") continue;
		if(is_dir($dir . $entry)) {
			$retval[] = array("name" => $entry, "path" => $dir. $entry."/", "type" => filetype($dir.$entry), "size" => 0, "lastmod" => filemtime($dir.$entry) );
			if($recurse && is_readable($dir.$entry."/")) {
				if($depth === false)
					$retval = array_merge($retval, getFileList($dir.$entry."/", true));
				elseif($depth > 0)
					$retval = array_merge($retval, getFileList($dir.$entry."/", true, $depth-1));
			}
		}
		elseif(is_readable($dir.$entry)) {
			$ftype = "application/octet-stream";
			if ( function_exists('finfo_open') ) {
				$finfo = finfo_open(FILEINFO_MIME_TYPE);
				$ftype = finfo_file($finfo, $dir.$entry);
				finfo_close($finfo);
			} elseif ( function_exists('mime_content_type') ) {
				$ftype = mime_content_type($dir.$entry);
			}
			$retval[] = array( "name" => $entry, "path"  => $dir.$entry, "type" => $ftype, "size" => filesize($dir.$entry), "lastmod" => filemtime($dir.$entry) );
			unset($finfo);
			unset($ftype);
		}
	}
	$d->close();
	return $retval;
}
function al3x_tree_js() {
        global $al3x_set;
        return '
                <style type="text/css">
                        <!-- //
			ul li { list-style-type: none; list-style-position:inside; background-color: transparent; }
                        .treeview, .treeview ul { padding: 0; margin: 0; list-style: none; }
                        .treeview ul { margin-top: 2px; }
                        .treeview .hitarea { background: url(' . $al3x_set['url'] . 'images/treeview-default.gif) -64px -25px no-repeat; height: 16px; width: 16px; margin-left: -16px; float: left; cursor: pointer; }
                        /* fix for IE6 */
                        * html .hitarea { display: inline; float:none; }
                        .treeview li { margin: 0; padding: 3px 0pt 3px 16px; }
                        .treeview a.selected { background-color: #eee; }
                        #treecontrol { margin: 1em 0; display: none; }
                        .treeview .hover { color: red; cursor: pointer; }
                        .treeview li { background: url(' . $al3x_set['url'] . 'images/treeview-default-line.gif) 0 0 no-repeat; }
                        .treeview li.collapsable, .treeview li.expandable { background-position: 0 -176px; }
                        .treeview .expandable-hitarea { background-position: -80px -3px; }
                        .treeview li.last { background-position: 0 -1766px }
                        .treeview li.lastCollapsable, .treeview li.lastExpandable { background-image: url(' . $al3x_set['url'] . 'images/treeview-default.gif); }
                        .treeview li.lastCollapsable { background-position: 0 -111px }
                        .treeview li.lastExpandable { background-position: -32px -67px }
                        .treeview div.lastCollapsable-hitarea, .treeview div.lastExpandable-hitarea { background-position: 0; }
                        .treeview .placeholder { background: url(' . $al3x_set['url'] . 'images/ajax-loader.gif) 0 0 no-repeat;  height: 16px; width: 16px; display: block; }
                        .filetree li { padding: 3px 0 2px 16px; }
                        .filetree span.folder, .filetree span.file { padding: 1px 0 1px 16px; display: block; }
                        .filetree span.folder { background: url(' . $al3x_set['url'] . 'images/folder.png) 0 0 no-repeat; }
                        .filetree li.expandable span.folder { background: url(' . $al3x_set['url'] . 'images/folder-closed.png) 0 0 no-repeat; }
                        .filetree span.file { background: url(' . $al3x_set['url'] . 'images/file.png) 0 0 no-repeat; }
                        // -->
                </style>
                <script src="' . $al3x_set['url'] . 'js/jquery.js" type="text/javascript"></script>
                <script src="' . $al3x_set['url'] . 'js/jquery.cookie.js" type="text/javascript"></script>
                <script src="' . $al3x_set['url'] . 'js/jquery.treeview.js" type="text/javascript"></script>
                <script type="text/javascript">
                        <!-- //
                        $(document).ready(function(){ $("#al3xdirlist").treeview(); });
                        // -->
                </script>
';
}
function al3x_directory_html($uid, $level=0, $subpath='', $itemcount='' ) {
	global $al3x_set;
	$strReturn = '';
	$dir = $al3x_set['updir'] . '/' . $uid . '/' . $subpath ;
	if (is_dir($dir) ) {
		chdir($dir);
		$arrDirList = getFileList('.', $recurse=false, $depth=false);
		$arrDirs = array();
		$arrFiles = array();
		foreach ($arrDirList as $arrItem) {
			if ($arrItem['type'] == 'dir') $arrDirs[$arrItem['name']] = $arrItem;
			else $arrFiles[$arrItem['name']] = $arrItem;
		}
		ksort($arrDirs);
		ksort($arrFiles);
		$arrAll = array_merge($arrDirs, $arrFiles);
		if ($level == 0) {
			$strReturn .= '<ul id="al3xdirlist" class="filetree" style="list-style-type: none; ">';
		}
		else {
			$strReturn .= '<ul id="folder';
			if ($level > 1) $strReturn .= $itemcount . $level;
			$strReturn .= '" class="filetree" style="list-style-type: none; ">';
		}
		$level++;
		foreach (array_values($arrAll) as $arrItem) {
			$i++;
			$strReturn .= '<li ';
			$subsub = $subpath . substr($arrItem['path'], 2);
			if (is_numeric($_POST['upd']) && isset($_POST['uploadfile']) ) $comsub = substr($_SESSION[$_POST['akey']][$_POST['upd']]['path'], 2, -1);
                        if (is_numeric($_POST['cpd']) && isset($_POST['createdir']) ) $comsub = substr($_SESSION[$_POST['akey']][$_POST['cpd']]['path'], 2, -1);
			$arrCompare = explode('/', $comsub);
			if ($arrItem['type'] == 'dir') {
				if (strlen($arrItem['path']) > 0 && $arrCompare[ $level-1 ] != substr($arrItem['path'], 2, -1) ) $strReturn .= ' class="closed"';
			}
			$strReturn .= '><span class="';
			if ($arrItem['type'] == 'dir') $strReturn .= 'folder';
			else $strReturn .= 'file';
			$strReturn .= '" style="margin-top: 0px; padding-top: 0px; "> ';
				if ($arrItem['type'] != 'dir') {
				$strReturn .= '<a href="'. $_SERVER['SCRIPT_NAME'] .'?al3x_download=file&userid=';
				if ($uid == '0') $strReturn .= 'PUBLIC';
				else $strReturn .=  $uid;
				$strReturn .= '&filepath='. $subsub;
				if (isset($_SESSION['al3x']['sid'])) {
					$strReturn .= '&checksum='. md5($uid . $_SESSION['al3x']['sid']);
				}
				$strReturn .= '">';
			}
			$strReturn .= $arrItem['name'];
			if ($arrItem['type'] != 'dir') {
				$strReturn .= '</a> ('. al3x_format_size($arrItem['size']) .')';
			}
			else {
				$arrTMPdirList = scandir($dir . substr($arrItem['path'], 2));
				$ecounter = 0;
				foreach ($arrTMPdirList as $strTMPelement) {
					if ($strTMPelement[0] == '.') continue;
					else $ecounter++;
				}
				$strReturn .= ' ('. $ecounter .' items)';
			}
			if (is_admin() ) {
				$strReturn .= ' &nbsp; <a href="'. $_SERVER['SCRIPT_NAME'] .'?page=file_manager/file&action=delete&uid='. $uid .'&delpath='. $subpath . substr($arrItem['path'], 2);
				$strReturn .= '" onClick="javascript: return confirm(\'This will delete '. $subsub .' ! There is no restore available, do you really want to execute?\')"><img src="'. $al3x_set['url'];
				$strReturn .= 'images/x.png" alt="delete item" /></a>';
			}
			$strReturn .= '</span>';
			if ($arrItem['type'] == 'dir') $strReturn .= al3x_directory_html($uid, $level, $subpath . substr($arrItem['path'], 2) , $i );
			$strReturn .= '</li>';
			unset($arrCompare);
		}
		if ($level == 1 && count($arrAll) == 0) $strReturn .= 'no files';
		$strReturn .= '</ul>';
	}
	return $strReturn;
}
function al3x_delete_dir_tree($tree)
{
	global $al3x_set;
	$lengthofupdir = strlen($al3x_set['updir']);
	if (substr($tree, 0, $lengthofupdir) != $al3x_set['updir'] ) {
		return false;
	}
	else {
		$todelete = getFileList($tree);
		foreach ($todelete as $arr2del)
		{
			if ($arr2del['name'] == '.' ) continue;
			if ($arr2del['type'] == 'dir') {
				al3x_delete_dir_tree($arr2del['path']);
				rmdir($arr2del['path']);
			}
			else {
				unlink($arr2del['path']);
			}
		}
		@rmdir($tree);
		return true;
	}
}
function al3x_format_size($size) {
    $units = array(' B', ' KB', ' MB', ' GB', ' TB');
    for ($i = 0; $size >= 1024 && $i < 4; $i++) $size /= 1024;
    return round($size, 2).$units[$i];
}
function al3x_frontend($content) {
	if(strpos($content,'[[afm_page]]') == true) {
		$content = str_replace('[[afm_page]]', al3x_front_output(), $content);
	}
	elseif ( preg_match("!\[\[afm_public\:(.+)\]\]!", $content, $arrfound) > 0  ) {
		$content = str_replace($arrfound[0], al3x_front_public($arrfound[1]) , $content);
	}
	return $content;
}
function al3x_front_public($strPath='/') {
	global $al3x_set;
	if ($strPath == '/') $strPath = substr($strPath, 1);
	if (substr($strPath, -1 ) != '/') $strPath = $strPath . '/';
	include('frontend_public.php');
	return $strReturn;
}
function al3x_front_output() {
	global $al3x_set;
	include('frontend.php');
	return $strReturn;
}
function al3x_user_login($uname, $pword) {
	global $al3x_set, $wpdb;
	$return_val = false;
	if ( ctype_alnum(trim($uname)) && trim($pword) )
        {
		$sql = 'SELECT * FROM `'.$al3x_set['usertab'].'` WHERE `uname` = "'.$uname.'" AND `pword`  = "'.$wpdb->escape($pword).'"';
		$re = $wpdb->get_var($sql);
		if (is_numeric($re) ) {
			$sessionkey = md5(mt_rand() * $wpdb->id);
			$_SESSION['al3x']['sid'] = $sessionkey;
			$_SESSION['al3x']['uid'] = $re;
			$return_val = true;
		}
	}
	return $return_val;
}
function al3x_killdonatenote() {
	global $al3x_set;
	$dn = fopen($al3x_set['dir'] . 'donatenote.php', 'w');
	fwrite($dn, '<?php /* nothing */ ?>');
	fclose($dn);
}
?>
