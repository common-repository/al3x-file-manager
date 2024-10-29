=== al3x_file_manager ===
Contributors: al3x.de
Tags: file, upload, download, password, protected, .htaccess, jQuery, user
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=89TLK89BQB5W6
Requires at least: 3.0.1
Tested up to: 3.0.4
Stable tag: 1.2
License: GPLv3


A neat User/File Management with a jQuery frontend to display customizable directory/file tree. Now with public download area.

== Description ==

User/File Management, downloadable files are session bound and .htaccess protected against hot-linking.
Users are _not_ wp users. Files are displayed in a jQuery powered filetree. 
Now with public download area, public downloads are not session bound.
You can even tell wp which subdirectory of the public folder should be used as root-directory.

== Installation ==

1. Upload this folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. On manual installation, set permission of al3x-file-manager/uploads to writable for webserver

== Frequently Asked Questions ==

= How do I embed download area into my post or page? =
To embed download area into a post or page, simply insert one of the following placeholders into your post or page:

  Placeholder: [[afm_page]]
  This will display the login screen for users in your post or page, and if logged it will display download filetree.

   Placeholder: [[afm_public:/]]
   This will display the public download area in your post or page. 

= Protection against hot-linking seems not to work, what is wrong? =
This feature depends on two different things.
First if the bad guy doesn't know the actual location of your files, he can access them only through the 
download function of the plugin.
In this case downloads are session bound if not public. So if files are accessible through the script, then 
maybe you are still logged in as an wp-administrator or files are inside the public directory. In both cases 
access limitation through a session are bypassed.
But if the bad guy does know the location of your files, which is very likely because this plugin is open 
source, then files are protected by the use of a .htaccess file.
So if files are accessible through the actual location, then there seems to be a problem with a configuration 
directive of the webserver your wordpress runs on. Most likely the ability to override directives through a 
.htaccess file is disabled. In that case, please consult your webservers administrator.

= How many instances of subdirectories are possible? =
The plugin script itself does not set a limit to the numbers of subdirectories or the depth of subdirectories. 
How ever keep in mind, each depth of subdirectory is indented in the filetree, so at a certain depth it would 
look plain ugly.
An other thing to consider is, the functions recursivly browse through all subdirectories, and the deeper you 
go, the slower it will get. Just KISS (Keep It Simple & Stupid).

= Is it possible to choose one subdirectory of the public download =
= tree and display this on one page and display another subdirectory =
= somewhere else? I.e. have two seperate public download trees? =
Yes!
Simply replace the slash ( / ) in [[afm_public:/]] with the path of directory you would like to display. That 
is the subpath inside your public directory.

= Why do you change the names of my files and directories? =
To escape encoding problems the plugin allows only alphanumeric characters and the underscore (as a 
replacement for no alnum chars) in filenames, based on the server 'locale' settings. That is why diacritic 
letters which you may deem alphanumeric fail to match critiria and are replaced as well.

= Are there other icons that I may use? =
The safest way to change icons used in the filetree, is to simply exchange the image files in 
[path/to/wp-content/plugins]/al3x-file-manager/images/ with your own, and don't change the script.

= Why is there no internationalization (i18n) support for this plugin? =
Now that public download area is implemented, this is on the top of my ToDo list.

= Cool, what else is on your ToDo list? =
Features I thought of myself and which I would like to see in this plugin are:

* download statistics
* user groups, that is one download area for a group of users
* user registration for one of these groups

If you can think of more, pls let me know at http://www.al3x.de/file_manager/.

= Hey Pal! How do I get rid of this annoying donation note on every page? =
Try clicking on it! Trust me, it won't bring you to any other donation page, it will just drop it!

= Dude, I love your plugin and hate to get it for free, how do I give you my money? =
You shouldn't, but I'd be thankful if you do.
Just go to http://www.al3x.de/file_manager/ and hit the donate button.
Thanks a lot.

== Screenshots ==
1. user panel allows you to create, edit and delete users.
2. file panel allows you to upload files, create directories and delete them again.

== Changelog ==
= 1.2 =
* built-in fallback to mime_content_type() for *older PHP Versions*.
* default value for filetype for *even older PHP Versions*.

= 1.1 =
* replaced deprecated function mime_content_type() with fileinfo functions, thanks to james (ogge.co.uk)
* included icon to menu

= 1.0 =
* now frontend login screen and filetree keep their position inside the post.
* special chars are now allowed in password, username stays alphanumeric.
* screenshots of admin panels added.
* after re-re-reading the whole code I call this 1.0 and stable.

= 0.2.0 =
* Public download area added.
* "in plugin"-FAQ added.
* Minor bugs fixed.
* Donation enabled.

= 0.1.4 =
* Comma seperated tag list.
* FAQ added to readme.
* Comments and whitespaces removed from code.

= 0.1.2 =
* first day, first bug fixes.
   1. plug in directory name changed.
   2. admin download fixed.

= 0.1.1 =
* existing users are now editable, i.e. username and password can be changed.

= 0.1.0 =
* first version.

