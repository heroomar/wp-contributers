=== Contributors Team ===
Contributors: wpkitchen pomypk, umarzaki
Tested up to: 7.1
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

== Description ==


Contributors Team is a WordPress plugin for collecting, managing, and showcasing community contributions.

Contributors can submit their contributions through a frontend form. The plugin can fetch contributor profile information from WordPress.org and create contributor profiles inside WordPress.

Administrators can review and manage contribution submissions from the WordPress dashboard.

== Shortcodes ==

Contribution Form

[wpkcs_contribution_form]

Contributor Profile

[wpkcs_profile profile="username"]

Display All Contributors

[wpkcs_contributors]

== Features ==

* Frontend contribution submission form.
* WordPress.org contributor profile integration.
* Contributor avatar and bio support.
* Contribution management through the WordPress dashboard.
* Contribution categories and types.
* Screenshot and proof upload support.
* Contributor profile pages.
* Contributor contribution history.
* Admin contribution management.
* Timeline-based contribution display.

== Contribution Flow ==

1. Contributor Submission

Users can submit contributions through the frontend contribution form.

The form collects:

* Name
* WordPress.org Username
* Contribution Type
* Contribution Link
* Time Spent
* Date
* Screenshot or Proof

Submitted contributions are saved for review.

2. Contributor Profile

After submission, the plugin checks whether the contributor profile already exists.

If the profile does not exist, the plugin can:

* Fetch the contributor profile from WordPress.org.
* Fetch the contributor avatar.
* Fetch the contributor bio.
* Create a contributor profile.

3. Admin Management

Administrators can manage:

* Contributor Profiles
* Contribution Posts
* Contribution Types
* Contribution Links
* Contribution Dates
* Time Spent
* Screenshots and Proof

== Requirements ==

* WordPress 6.0 or later
* PHP 7.4 or later

== Installation ==

1. Upload the plugin to the `/wp-content/plugins/` directory.
2. Activate the plugin through the WordPress Plugins screen.
3. Add the required shortcodes to your WordPress pages.

== Frequently Asked Questions ==

= How do I display the contribution form? =

Add the following shortcode to a page:

[wpkcs_contribution_form]

= How do I display a contributor profile? =

Use:

[wpkcs_profile profile="username"]

Replace `username` with the contributor's WordPress.org username.

= How do I display all contributors? =

Use:

[wpkcs_contributors]

== License ==

This plugin is licensed under the GPLv2 or later.

License URI: https://www.gnu.org/licenses/gpl-2.0.html

== Changelog ==

= 1.0.0 =
* Initial release.

== Upgrade Notice ==

= 1.0.0 =
Initial release.