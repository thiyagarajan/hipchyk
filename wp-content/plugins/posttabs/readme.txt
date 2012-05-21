=== postTabs ===
Contributors: LeoGermani
Donate link: http://pirex.com.br/wordpress-plugins
Tags: post, page, tabs, content, section, subsection, tab
Requires at least: 2.1
Tested up to: 2.9.2
Stable tag: 2.9.1

postTabs allows you to easily split your post/page content into Tabs that will be shown to your visitors

== Description ==

postTabs allows you to easily split your post/page content into Tabs that will be shown to your visitors.

The colors of the tab can be easily changed to match you theme

Tested on Firefox, IE6, iE7, Opera, Safari and Konqueror.

The tabs will look good on almost every theme. If you find a theme where the tabs do not work out of the box, please send me a link and Ill check it.

== Installation ==

. Download the package
. Extract it to the "plugins" folder of your wordpress
. In the Admin Panes go to "Plugins" and activate it

== Upgrade ==

. Replace the files
. Go to Setting > PostTabs, choose a value to the new options and save it

== Usage ==

Go to Settings > PostTabs (or Options > postTabs) to adjust the colors of your tabs.

Edit your post and page and add the code below wherever you want to insert the tab:

[tab:Tab Title]

All the content bellow this code will be inserted inside this tab, untill another tab is declared with:

[tab:Another Tab]

And so on.. untill the end of the page or optionally you can add the code bellow to end the last tab and add more text outside the tabs:

[tab:END]

You can also have text before the first tab. Just type it as normal text...

== Screenshots ==

1. An example of the text on the editor window and the live result

2. The admin options page where you can set the colors with a colorpick and have a instant preview
	
== Change Log ==

2.9.1
18 dec 2008
* fixed annoying link behavior that would scroll to the top of the page

2.9
08 dec 2008
* Now uses jquery on front end
* Displays everything if javascript is not present


2.7
28 jul 2008
* Fixed bugs on permalink support
* Added Table of Contents and Navigation options
* Improved RSS feed appearence


2.5
06 jul 2008

* Even better cross-theme CSS compatibility

* fixed - now xhtml compliant (tks to ovidiu)
* fixed - path to javascript works with wordpress installed on subdirectory (tks to ovidiu)
* fixed appearence on RSS feeds and other situations where the post is presented outside the context (i.e. wp-print plugin). Now it hides the unordered list and displays a title at the top of each tab content (tks to JK)

New Features

* Choose the tabs alignment
* When page reloads it remembers wich tab was opened
* You can choose wether tabs links will only show-hide tabs or will point to a individual permalink for each tab
* add option to display the tab permalink as post metadata information.



2.0
23 jun 2008
* refactored css stylesheet for better cross-browser cross-themes compatibility 
* now you can change also the line color
* improved admin interface with color picker and preview

1.0
27 may 2008
Released first version! Full functional in all browsers!


0.1 beta
26 may 2008
Released first version, with known issues on styles...
