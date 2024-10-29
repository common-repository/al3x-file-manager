<?php
if (!defined ('ABSPATH')) die ('No direct access allowed');
?>
<div class="wrap">
        <h2><a name="TOP"></a>File Manager: faq</h2>
        <div style="width: 640px; ">
		<table border="0" class="widefat">
		<tr>
			<td>
<blockquote>
	<big><b>Frequently asked questions</b></big><br /><br />
	<ol style="list-style-position:inside; ">
	<li><a href="#1">How do I embed download area into my post or page?</a></li>
	<li><a href="#2">Protection against hot-linking seems not to work, what is wrong?</a></li>
	<li><a href="#3">How many instances of subdirectories are possible?</a></li>
	<li><a href="#4">Is it possible to choose one subdirectory of the public download tree and display this?</a></li>
	<li><a href="#5">Why do you change the names of my files and directories?</a></li>
	<li><a href="#6">Are there other icons that I may use?</a></li>
	<li><a href="#7">Why is there no internationalization (i18n) support for this plugin?</a></li>
	<li><a href="#8">Cool, what else is on your ToDo list?</a></li>
	<li><a href="#9">Hey Pal! How do I get rid of this annoying donation note on every page?</a></li>
	<li><a href="#donate">Dude, I love your plugin and hate to get it for free, how do I give you my money?</a></li>
	</ol>
	<br />
	<big><b>... and their answers</b></big><br /><br />
<div style="border-bottom: solid 1px #000000; width: 100%; height: 20px; "><a name="1"></a> </div>
	<b>How do I embed download area into my post or page?</b>
	<blockquote>
		To embed download area into a post or page, simply insert one of the following placeholders into your post or page:
		<blockquote>
			Placeholder: <b>[[afm_page]]</b><br />
			This will display the login screen for users in your post or screen, and if logged it will display download filetree.<br /><br />
			Placeholder: <b>[[afm_public:/]]</b><br />
			This will display the public download area in your post or screen.
		</blockquote>
		<div align="Right"><a href="#TOP">^ top</a>
	</blockquote>
<div style="border-bottom: solid 1px #000000; width: 100%; height: 10px; "><a name="2"></a> </div>
	<b>Protection against hot-linking seems not to work, what is wrong?</b>
	<blockquote>

This feature depends on two different things.<br />
First if the bad guy doesn't know the actual location of your files, he can access them only through the download function of the plugin.<br />
In this case downloads are session bound if not public. So if files are accessible through the script, then maybe you are still logged in as an wp-administrator or files are inside the public directory. In both cases access limitation through a session are bypassed.<br />
But if the bad guy does know the location of your files, which is very likely because this plugin is open source, then files are protected by the use of a .htaccess file.<br />
So if files are accessible through the actual location, then there seems to be a problem with a configuration directive of the webserver your wordpress runs on. Most likely the ability to override directives through a .htaccess file is disabled. In that case, please consult your webservers administrator.
	<div align="Right"><a href="#TOP">^ top</a>
        </blockquote>
<div style="border-bottom: solid 1px #000000; width: 100%; height: 10px; "><a name="3"></a> </div>
	<b>How many instances of subdirectories are possible?</b>
	<blockquote>
		The plugin script itself does not set a limit to the numbers of subdirectories or the depth of subdirectories. How ever keep in mind, each depth of subdirectory is indented in the filetree, so at a certain depth it would look plain ugly.<br />
		An other thing to consider is, the functions recursivly browse through all subdirectories, and the deeper you go, the slower it will get. Just KISS (Keep It Simple & Stupid).
		<div align="Right"><a href="#TOP">^ top</a>
	</blockquote>
<div style="border-bottom: solid 1px #000000; width: 100%; height: 20px; "><a name="4"></a> </div>
	<b>Is it possible to choose one subdirectory of the public download tree and display this on one page and display another subdirectory somewhere else? I.e. have two seperate public download trees?</b>
	<blockquote>
		Yes!<br />
		Simply replace the slash ( / ) in <b>[[afm_public:/]]</b> with the path of directory you would like to display. That is the subpath inside your public directory.
		<div align="Right"><a href="#TOP">^ top</a>
	</blockquote>
<div style="border-bottom: solid 1px #000000; width: 100%; height: 20px; "><a name="5"></a> </div>
	<b>Why do you change the names of my files and directories?</b>
	<blockquote>
		To escape encoding problems the plugin allows only alphanumeric characters and the underscore (as a replacement for no alnum chars) in filenames, based on the server 'locale' settings. That is why diacritic letters which you may deem alphanumeric fail to match critiria and are replaced as well.
		<div align="Right"><a href="#TOP">^ top</a>
	</blockquote>
<div style="border-bottom: solid 1px #000000; width: 100%; height: 20px; "><a name="6"></a> </div>
        <b>Are there other icons that I may use?</b>
	<blockquote>
		The safest way to change icons used in the filetree, is to simply exchange the image files in [path/to/wp-content/plugins]/al3x-file-manager/images/ with your own, and don't change the script.
		<div align="Right"><a href="#TOP">^ top</a>
	</blockquote>
<div style="border-bottom: solid 1px #000000; width: 100%; height: 20px; "><a name="7"></a> </div>
        <b>Why is there no internationalization (i18n) support for this plugin?</b>
	<blockquote>
		Now that public download area is implemented, this is on the top of my ToDo list.
		<div align="Right"><a href="#TOP">^ top</a>
	</blockquote>
<div style="border-bottom: solid 1px #000000; width: 100%; height: 20px; "><a name="8"></a> </div>
        <b>Cool, what else is on your ToDo list?</b>
        <blockquote>
                Features I thought of myself and which I would like to see in this plugin are:
		<ul>
			<li>download statistics</li>
			<li>user groups, that is one download area for a group of users</li>
			<li>user registration for one of these groups</li>
		</ul>
		If you can think of more, pls let me know at <a href="http://www.al3x.de/file_manager/" target="_blank">http://www.al3x.de/file_manager/</a>.
		<div align="Right"><a href="#TOP">^ top</a>
        </blockquote>
<div style="border-bottom: solid 1px #000000; width: 100%; height: 20px; "><a name="9"></a> </div>
        <b>Hey Pal! How do I get rid of this annoying donation note on every page?</b>
        <blockquote>
                Try clicking on it! Trust me, it won't bring you to any other donation page, it will just drop it!
                <div align="Right"><a href="#TOP">^ top</a>
        </blockquote>
<div style="border-bottom: solid 1px #000000; width: 100%; height: 20px; "><a name="donate"></a> </div>
        <b>Dude, I love your plugin and hate to get it for free, how do I give you my money?</b>
        <blockquote>
		You shouldn't, but I'd be thankful if you do!<br />
		Nothing easier than this, just hit the donate button and go for it,<br />and thanks a lot!
		<div align="Center">
		<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
		<input type="hidden" name="cmd" value="_s-xclick">
		<input type="hidden" name="hosted_button_id" value="KF9EEALTUZRXC">
		<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
		<img alt="" border="0" src="https://www.paypal.com/de_DE/i/scr/pixel.gif" width="1" height="1">
		</form>
		</div>
		<div align="Right"><a href="#TOP">^ top</a>
	</blockquote>
</blockquote>
			</td>
		</tr>
		</table>
	</div>
<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
</div>
