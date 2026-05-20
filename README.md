Shortcodes

Contribution Form

[wpkcs_contribution_form]

Contributor Profile

[wpkcs_profile profile="username"]

Display All Contributors

[wpkcs_contributors]



Plugin Description

Wp Kitchen Contributers is a modern WordPress contributor management plugin designed to collect, manage, and showcase community contributions in a professional timeline-based profile system.

The plugin allows contributors to submit their WordPress-related contributions through a secure frontend form. It automatically fetches contributor profile information from WordPress.org, including avatar and bio, and creates contributor profiles inside WordPress.

Admins can review, manage, and verify submissions directly from the WordPress dashboard using custom post types, custom meta fields, and enhanced admin tables.

Flow
1. Contributor Submission

User opens the frontend contribution form using shortcode:

Contributor submits:

Name
WordPress.org Username
Contribution Type
Contribution Link
Time Spent
Date
Screenshot/Proof

Contribution is stored as pending review

After submission:

Plugin checks if contributor profile already exists
If not:
Fetches profile data from WordPress.org
Fetches avatar
Fetches bio
Creates Contributor Profile CPT entry

Admin Management

Admin can manage:

Contribution Posts
Contribution Type
