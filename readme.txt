=== Lost & Found Animal ===
Contributors: deimos30
Donate link: https://github.com/deimos30
Tags: lost, found, animals, dogs, cats, pets, shelter, rescue
Requires at least: 5.0
Tested up to: 6.9
Stable tag: 1.0.3
Requires PHP: 7.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Manage lost and found animals with photo gallery, filtering, and shortcode display. Works with any WordPress theme.

== Description ==

A WordPress plugin for kennels, shelters, and rescue organizations to manage and display lost and found animals.

= Features =

* Custom Post Type for Animals (Dog, Cat, Other)
* Classic Editor interface (easy to use)
* Photo Gallery with hover cycling
* Status badges (Found Today, Found, Available, Reunited, Not Available)
* Filter by status and gender
* Sort by date or name
* Responsive grid display
* Single animal page with full details
* Social sharing buttons
* Works with any WordPress theme

= Shortcode =

Use `[lost_found_animals]` to display animals on any page or post.

= Shortcode Parameters =

* `limit` - Number of animals (default: -1 for all)
* `status` - Filter by status
* `columns` - Grid columns 1-4 (default: 4)
* `show_filters` - Show filter bar (default: true)

= Examples =

`[lost_found_animals limit="8" columns="4"]`
`[lost_found_animals status="Found" show_filters="false"]`

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/lost-found-animal` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress.
3. Go to 'Lost & Found Animals' in the admin menu to add animals.
4. Use the shortcode `[lost_found_animals]` on any page to display animals.
5. Go to Settings > Permalinks and click Save Changes to flush rewrite rules.

== Frequently Asked Questions ==

= How do I add a main photo? =

Use the "Main Photo (Featured Image)" box in the right sidebar when editing an animal.

= How do I add additional photos? =

Use the "Photo Gallery (Additional Photos)" section below the editor to add more photos.

= Can I filter animals by status? =

Yes, use the shortcode parameter: `[lost_found_animals status="Found"]`

= Does this work with page builders? =

Yes, the shortcode works with Elementor, Gutenberg, and other page builders.

== Screenshots ==

1. Animal grid display on frontend
2. Single animal page with gallery
3. Admin edit screen with meta boxes
4. Filter and sort options

== Changelog ==

= 1.0.3 =
* Security: Added direct file access protection to template file
* Improved: Code security hardening

= 1.0.2 =
* Fixed: Removed deprecated load_plugin_textdomain() call
* Updated: Tested up to WordPress 6.9
* Improved: Code formatting to WordPress coding standards

= 1.0.1 =
* Changed: Switched from Gutenberg to Classic Editor for Animal post type
* Improved: Meta box layout for better usability
* Updated: Author information

= 1.0.0 =
* Initial release

== Upgrade Notice ==

= 1.0.3 =
Security improvement: Added direct file access protection.

= 1.0.2 =
Updated for WordPress 6.9 compatibility. Removed deprecated function calls.

= 1.0.1 =
Improved admin interface with Classic Editor support.
