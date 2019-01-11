=== WP Datepicker ===
Contributors: fahadmahmood
Tags: datepicker, jquery-ui, calendar, widget, popup
Requires at least: 3.0.1
Tested up to: 5.0
Stable tag: 1.5.2
License: GPL2
License URI: http://www.gnu.org/licenses/gpl-2.0.html
A great plugin to implement custom styled jQuery UI datepicker site-wide.

== Description ==
* Author: [Fahad Mahmood](http://www.androidbubbles.com/contact)
* Project URI: <http://androidbubble.com/blog/wordpress/plugins/wp-datepicker>

Easy to implement with a simple CSS selector input field.

Video Tutorial:

[youtube http://www.youtube.com/watch?v=eILaObbYucU]


[WDWD Blog][Wordpress][]: http://androidbubble.com/blog/category/website-development/php-frameworks/wordpress/
[TutorsLoop][WordPress Mechanic][]: http://www.tutorsloop.net/app/live.php?id=3891549

Compatibility List:

* GuavaPattern
* Genesis
* Thesis
* WooThemes
* Gantry
* Carrington Core
* Hybrid Core
* Options Framework
* Redux Framework
* SMOF
* UPThemes
* Vafpress
* Codestar

   
== Installation ==

How to install the plugin and get it working:


Method-A:

1. Go to your wordpress admin "yoursite.com/wp-admin"

2. Login and then access "yoursite.com/wp-admin/plugin-install.php?tab=upload

3. Upload and activate this plugin

4. Now go to admin menu -> settings -> WP Datepicker

Method-B:

1.	Download the WP Datepicker installation package and extract the files on

	your computer. 
2.	Create a new directory named `WP Datepicker` in the `wp-content/plugins`

	directory of your WordPress installation. Use an FTP or SFTP client to

	upload the contents of your WP Datepicker archive to the new directory

	that you just created on your web host.
3.	Log in to the WordPress Dashboard and activate the WP Datepicker plugin.
4.	Once the plugin is activated, a new **WP Datepicker** sub-menu will appear in your Wordpress admin -> settings menu.

[WP Datepicker Quick Start]: http://androidbubble.com/blog/wordpress/plugins/wp-datepicker



== Frequently Asked Questions ==

= Is this compatible with all WordPress themes? =

Yes, it is compatible with all WordPress themes which are developed according to the WordPress theme development standards. 

= Is everything ready in this plugin for final deployment? =

Every theme will have different global styles so a few stylesheet properties will be required to be added and/or modified.

= How to install WP Datepicker and Configure =

1) Go to plugin section (wp-admin) click on add new and then write wp datepicker in search bar
2) Click on install button wp datepicker and then click on activate respectively
3) Settings Menu > WP Datepicker > Settings Page

Here we have a few options: 

a) First option is Configure WP Datepicker by Input field's Id
b) Second option is Configure WP Datepicker by Input field's Class
c) Third option is Configure WP Datepicker by Input field's attribute. 
e.g. name, type and HTML5 data

= How to install WP Datepicker and Configure it with Contact Form 7 =

Go to Contact Menu (wp-admin) after installation of contactform7 plugin, click on Contact forms and here we have a contact form 1 by default, click on it. 
You will see something like this:

<label> Your Name (required)  [text* your-name] </label>
<label> Your Email (required)  [email* your-email] </label>
<label> Date 2   [date date-134 class:dp]</label>
<label> Subject  [text your-subject] </label>
<label> Your Message  [textarea your-message] </label>
[submit "Send"]

Create a new field with an id "#Calendar" Like: <label> Date [date date-726 id:calendar]</label>
Create second field with a class ".dp" Like: <label> Date 2   [date date-134 class:dp]</label>
Create second field with a class ".dp" like: <label> Date 3   [date date-499]</label> having no id and class

I) Now go to the options panel in the input field write id of the first input field with hash sight #calendar and click on save
then refresh page, here first input field have a calendar and other two fields do not have this calendar.

II) Now we configure with second option write second input field's class here with dot sign by separating with comma like #calendar, .bday
then refresh your page and here the second field also has this calendar.

III) Now configure with input field's name, write it by separating comma like #calendar, .bday, input [name="datepicker"]
and refresh your page. Click on the third field to try, this field will also have this calendar option.

Finally:
The first "Date" field is configured with id, second "Date 2" with input field's class and third "Date 3" with input field's name.

= How can i report an issue to the plugin author? =

It's better to post on support forum but if you need it be fixed on urgent basis then you can reach me through my blog too. You can find my blog link above.

== Screenshots ==

1. WP Datepicker > Default Settings Page - 1
2. WP Datepicker > Preview - 2
3. WP Datepicker > Preview - 3
4. WP Datepicker > Preview - 4
5. WP Datepicker > Implementation inside content editor
6. WP Datepicker > Preview - 5
7. WP Datepicker > Settings Page - 2
8. WP Datepicker > Settings Page - 3

== Changelog ==
= 1.5.2 =
* Each selector should have a separate default value. [Thanks to Raul Pinto]
= 1.5.1 =
* Autocomplete OFF. [Thanks to Michael Ellis]
= 1.5.0 =
* Update regional settings with dateFormat overridden possibility. [Thanks to Jmashweb]
= 1.4.9 =
* Added a textarea field for beforeShowDay. [Thanks to William V. Hughes]
= 1.4.8 =
* Added extra checks for front end scripts. [Thanks to Ricardo Orozco Vergara]
= 1.4.7 =
* Added a check for admin side scripts. [Thanks to rabidin]
= 1.4.6 =
* JS interval based errors are stopped. [Thanks to Arnold S]
= 1.4.5 =
* Custom colors are improved. [Thanks to Dalia Herceg]
= 1.4.3 =
* Weekends can be turned off now. [Thanks to Tem Balanco]
= 1.4.1 =
* Language selection refined and today button functionality added. [Thanks to Richard Rowley]
= 1.4.0 =
* Default value issue reported and fixed. [Thanks to Guy Hagen]
= 1.3.9 =
* Capabilities and roles related bug fixed. [Thanks to Paul Munro]
= 1.3.8 =
* Sanitized input and fixed direct file access issues.
= 1.3.7 =
* Multilingual months can be in short and full. These are now capitialize as well. [Thanks to Jose Braña]
= 1.3.6 =
* Change year related option refined. [Thanks to Makenzi Edwin]
= 1.3.5 =
* Repeater fields compatibility refined
= 1.3.4 =
* Repeater fields compatibility added
= 1.3.3 =
* Datepicker dateFormat option provided.
* Translated in German language.
= 1.3.2 =
* Datepicker options refined.
= 1.3.1 =
* Datepicker with 74 languages.
= 1.3 =
* jQuery live to on [Thanks to nickylew]
= 1.2.9 =
* minDate & maxDate added in Pro version.
= 1.2.8 =
* Fixed: Stopping google translate from translating datepicker.
= 1.2.7 =
* A few minor fixes.
* FAQ's are added.
= 1.2.6 =
* Code Generator Added.
= 1.2.4 =
* An important fix related to mobile responsive layout.
= 1.2.3 =
* An important fix.
= 1.2.2 =
* A few important tweaks.
= 1.2.1 =
* A javascript file excluded.
= 1.2 =
* More styles are added.
= 1.1 =
* Options & ColorPicker added for Pro Users.
= 1.0 =
* Initial Commit

== Upgrade Notice ==
= 1.5.2 =
Each selector should have a separate default value.
= 1.5.1 =
Autocomplete OFF.
= 1.5.0 =
Update regional settings with dateFormat overridden possibility.
= 1.4.9 =
Added a textarea field for beforeShowDay.
= 1.4.8 =
Added extra checks for front end scripts.
= 1.4.7 =
Added a check for admin side scripts.
= 1.4.6 =
JS interval based errors are stopped.
= 1.4.5 =
Custom colors are improved.
= 1.4.3 =
Weekends can be turned off now.
= 1.4.1 =
Language selection refined and today button functionality added.
= 1.4.0 =
Default value issue reported and fixed.
= 1.3.9 =
Capabilities and roles related bug fixed.
= 1.3.8 =
Sanitized input and fixed direct file access issues.
= 1.3.7 =
Multilingual months can be in short and full. These are now capitialize as well.
= 1.3.6 =
Change year related option refined.
= 1.3.5 =
Repeater fields compatibility refined
= 1.3.4 =
Repeater fields compatibility added
= 1.3.3 =
Datepicker dateFormat option provided and translated in German language.
= 1.3.2 =
Datepicker options refined.
= 1.3.1 =
Datepicker with 74 languages.
= 1.3 =
jQuery live to on [Thanks to nickylew]
= 1.2.9 =
No need to updated if you are using FREE version.
= 1.2.8 =
Fixed: Stopping google translate from translating datepicker.
= 1.2.7 =
A few minor fixes.
= 1.2.6 =
Code Generator Added.
= 1.2.4 =
An important fix related to mobile responsive layout.
= 1.2.3 =
An important fix.
= 1.2.2 =
A few important tweaks.
= 1.2.1 =
An important fix.
= 1.2 =
More styles are added.
= 1.1 =
Options & ColorPicker added for Pro Users.
= 1.0 =
Initial Commit

== Arbitrary section ==

I would appreciate the suggestions related to new features. Please don't forget to support this free plugin by giving your awesome reviews.

== A brief Markdown Example ==

Ordered list:

1. Can be used with WooCommerce
2. Exceptional support is available
3. Developed according to the WordPress plugin development standards





== License ==
This WordPress Plugin is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
 
This free software is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with this software. If not, see http://www.gnu.org/licenses/gpl-2.0.html.