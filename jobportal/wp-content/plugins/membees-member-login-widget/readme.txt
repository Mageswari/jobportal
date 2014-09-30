=== Membee Login === 
Contributors: DaleAB, achilles_sm
Tags: membership, login, members, membee, social, authentication
Requires at least: 2.7.0
Tested up to: 3.9.1
Stable tag: 1.1.7
Add member authentication and access role management to your WordPress site via Membee's powerful Member Single Sign-On web service

== Description ==

This plug-in allows a WordPress developer to utilize the popular membership management system, [Membee](http://www.membee.com/) to control user access to a WordPress site. For a membership based organizations, this plug-in extends to WordPress the ability to manage access and roles within the member's record in Membee and then use the roles to permit access to content in a WordPress site. Since Membee allows for the creation of unlimited groups and committees, each with their own unlimited access roles, the WordPress developer has very granular control over access to content. For the client membership based organization, they gain the desired ability manage all aspects of their relationship with their member, including website content access in one place, Membee.

For example, the assignment a "BoardOnly" role to the "Board of Directors" committee in Membee would restrict access to website content secured in WordPress using the "BoardOnly" role. All roles created and managed in Membee are passed to WordPress via this plug-in so there are no additional steps to insure the roles are the same in Membee and the WordPress site. Since committee members inherit the access role from the committee, adding people to the committee or removing them instantly grants or removes the roll respectively. For the WordPress developer, this means one time only deployment of the functionality without the need to constantly revise their site as their client organization adds, drops, and revises groups and committees in Membee.

The plug-in also extends Membee's support for it's Social Login feature. This feature allows an organization to activate support for social network login in Membee to permit members to use their social network identity (Facebook, Twitter, Google, Yahoo, and LinkedIn) to access restricted website content and features. The plug-in allows the WordPress developer to permit the use of the social network identities by members to access content the developer has restricted access to. To extend the example above, a member serving on the Board of Directors could access the site content restricted with the "BoardOnly" access role using their Facebook username and password.

== Installation ==

Here's how to install the Membee Login plugin in your WordPress site:

1. Login to your WordPress site and go to the Dashboard 
1. Choose Plugins
1. Choose "Add New"
1. Type "membee" and click Search Plugins
1. When the Membee Login plugin appears in the search results, choose the "Install Now" link 
1. Answer "OK" to the "Are you sure you want to install this plugin?" prompt 
1. Choose "Activate Plugin" - takes you to the Installed Plugins section of the Dashboard
1. Choose Settings on the Dashboard
1. Choose Membee Login
1. In Membee Login Options, enter the Client ID, Secret, and Application ID (these are generated in Membee's "Programs & Access Roles" feature - see http://membee.zendesk.com/entries/21277223-setting-up-membee-s-integrated-login-system-with-your-wordpress-site )
1. Choose Save Options
1. You're done!

Known Issues

Occasional 302 Redirect Errors in AJAX Calls

It seems that for sites making a large number of AJAX calls, you may encounter an occasional 302 error from your AJAX function. Membee's Login plugin may be the culprit because membee_init() is called via a WordPress add_action 'init' so it is called for every AJAX call made. 

Note you should only make the following change if your site exhibits the behavior described above.

The fix is to temporarily disable the Membee Login plugin for the AJAX calls you are making. Here are the steps:

a) Open your theme's "functions.php" file and add the following code:

if (check_ajax_referer( 'your-special-string', 'security', false )) {  
  remove_action('init', 'membee_init');
}

b) Substitute arguments for check_ajax_referer function with values you used in your AJAX call

== Frequently Asked Questions == 

= Do we need use Membee to manage our membership? =

Yes. The sole purpose for this plugin is allow you to manage member access to protected content from their member record, committees, or groups in Membee while making it "check box easy" to grant or restrict access to content over in your WordPress site when you create a new page or section.

= What do we need to do when we get a new member in Membee? =

Nothing. Just send the member the link to setup their password ([here is how to do that in Membee for an individual member or a group of member](http://membee.zendesk.com/entries/20662423/)) and once they have setup their password or selected a Social Network to use to login, the plugin takes it from there. When the member then visits your site and tries to access a "members only" content area, their latest and greatest access role information is updated in WordPress via the Membee Login plugin. 

= How do we create access roles in Membee? =

Easy. In Membee, just create the role and then assign it to a group ( a list of people) or a committee. Once the role is associated with the group and/or committee, all of the members in that group or committee automatically get the role. Similarly, remove someone from a group or committee and they automatcially lose any role(s) associated with that group or committee. [Here are the steps in Membee](http://membee.zendesk.com/entries/20730812/).

= Do our members need to login twice if we use other Membee features on our site? =

No. The Membee Login plugin supports Membee's full member single sign-on service. So, a member can choose to login to access "members only" content and then decide to update the member profile in Membee's Profile widget and Membee knows who they are and presents their member profile to them for updating. Yes, the reverse scenario works too!

== Changelog ==

= 1.1.7 =
* Removed a conflict with a Google+ plugin
* Added friendlier error handling
* Will retain settings when WordPress updates its version

= 1.1.6 =
* Removed a conflict with FeedBurner

= 1.1.5 =
* Fixed a jQuery issue with the flyout feature of the login

= 1.1.4 =
* Insured that the plugin uses the latest stable version of the jQuery UI Core

= 1.1.3 =
* Insured that the plugin uses the latest stable version of the jQuery library

= 1.1.2 =
* Addressed the erroneous generation of two script errors that did not affect the performance of the plugin

= 1.1.1 =
* Removed an issue with a social sharing plugin that prevented the "fetching" of images from a WP post when a user was trying to share the post on a social network. The plugin now allows for people in the WP Users table with site admin roles to inherit member roles defined & managed in Membee. 

= 1.0.4 =
* Revision to better take advantage of WordPress' ability to hide/display menu choices via the "Display In Menus" feature based on whether or not the site visitor has logged in

= 1.0.3 = 
* Approved for public release* Minor update to fix an obscure cirumstance where members using one of their social network identities to login to the WordPress site may not be authenticated correctly

= 1.0.2 = 
* Strenghtened the check to insure all access is removed in the WordPress site if the member's login access is deactivated completely in their member record in Membee

= 1.0.1 =
* Fixed a bug that prevented accurate updating of the member's login information in WordPress if they had muitple roles in Membee

= 1.0.0 =
* Initial release for testers.

== Upgrade Notice ==

= 1.1.6 =
* Removed a conflict with FeedBurner

= 1.1.5 =
* Fixed a jQuery issue with the flyout feature of the login

= 1.1.4 =
* Insured that the plugin uses the latest stable version of the jQuery UI Core

= 1.1.3 =
* Insured that the plugin uses the latest stable version of the jQuery library

= 1.1.2 =
* Addressed the erroneous generation of two script errors that did not affect the performance of the plugin

= 1.1.1 = 
Removed an issue with a social sharing plugin that prevented the "fetching" of images from a WP post when a user was trying to share the post on a social network. The plugin now allows for people in the WP Users table with site admin roles to inherit member roles defined & managed in Membee.

= 1.0.4 =
Better support for the "Display In Menus" WordPress feature

= 1.0.3 =
Initial stable release
