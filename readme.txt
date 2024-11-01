=== Plugin Name ===
Contributors: vsmash
Donate link: http://www.velvary.com.au/vanilla-beans/wordpress/error-mailer/
Tags: Error, Error mail, debug, exception email, exception handler, bug monitor
Requires at least: 4.0
Tested up to: 5.9
Stable tag: 3.11
PHP Tested up to: 7.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Monitor your website, plugins and themes.

== Description ==
Error Mailer is intended to help in a production environment where errors are
typically switched off. An email is sent to nominated recipients in the event
of a  php error.  The error is then passed back to the current error handler.

Exclude list filter is also applied to prevent over-spamming of known errors.

Please note that this is a real-time error alert system. It does not wait to read the
log, and therefore consumes resources.


== Installation ==
Standard Wordpress plugin installation.
Minimum PHP version is v5.4
Depends on wp_mail

== Frequently Asked Questions ==

== Screenshots ==


== Changelog ==
= 3.11 =
- check error_reporting redefined, tested with 5.9

= 3.08 =
- check error_reporting defined, tested with 5.7

= 3.07 =
- recognise @ to omit errors skipped in code

= 3.06 =
- bugfix to allow for previous error to be null

= 3.05 =
- bugfix where some exemptions were being ignored

= 3.04 =
- tested with Wordpress 5.41 on php 7.4

= 3.02 =
- check for slackhooker existence/activation

= 3.01 =
Limited errors to 1 per 3 seconds

= 3.00 =
Added functions to protect against repetition. Maximum 1 msg per 10 seconds for same
repetitive error. Tested on php7.1

= 2.40 =
Removed unreliable CDN resource;
Tested against WP 4.71

= 2.30 =
Fixed unrecognised linebreaks in exemption list

= 2.24 =
Fixed typo, 
Tested against wp 4.5


= 2.22 =
removed mandatory email address

= 2.01 =
updated common functions
added missing icon

= 2.0 =
Added support for slack integration.
Tested against Wordpress 4.4


= 1.73 =
Tested against WP 4.23

= 1.72 =
Added Vanilla product list. No functional changes.

= 1.71 = 
Bugfix on jquery update


= 1.62 =
Bypassed wp_mail dependency.

= 1.61 =
Set email failure to ignore

= 1.60 =
Copy Change
Minor core update
Empty URL handler

= 1.56 =
Tweak to filexists

= 1.55 =
Update filexists to ignore certificate errors

= 1.54 =
Fixed bug in startswith function

= 1.53 =
Removed link to iconsetter

= 1.52 =
Updated Vanilla Bean page

= 1.51 =
Added trim to explode in error number exceptions

= 1.5 =
Added error number exemptions

= 1.43 =
Namespaced functions to fix exemption bug

= 1.42 =
Fixed windows incompaitibility issue

= 1.40 =
Version tweak

= 1.3 =
Added user options for email subject line

= 1.12 =
* Vanilla Beans menu integration

= 1.1 =
* Updated alert to use php mail function rather than wp_mail to catch early errors
* Added support for linebreak separated email list
* Re-assigned funcitons to VanillaBeans namespace

= 1.05 =
Fixed typo in settings

= 1.04 =
Fixed bug caused by capitalized variable;
Added parse capture option

= 1.02 =
Added non-user error types.

= 1.01 =
Settings page exclude textarea hidden

= 1.0 =
Created


== Upgrade Notice ==

= 1.0 =
Initial Release.
