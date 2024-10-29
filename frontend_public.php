<?php
if (!defined ('ABSPATH')) die ('No direct access allowed');
$strReturn = '<div style="border-bottom: solid 1px #000000; width: 100%; ">&nbsp;</div>';
if (is_dir($al3x_set['updir'] . '/0/' . $strPath) ) {
	$strReturn .= al3x_tree_js();
	$strReturn .= al3x_directory_html(0, 0, $strPath);
}
else {
	$strReturn .= 'no files';
}
?>
