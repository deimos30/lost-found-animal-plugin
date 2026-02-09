=== Lost & Found Animal ===
Contributors: deimos30
Donate link: https://github.com/deimos30
Tags: lost, found, animals, pets, shelter
Requires at least: 5.0
Tested up to: 6.9
Stable tag: 1.0.5
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
* Responsive grid display (1-4 columns)
* Single animal page with full details
* Social sharing buttons
* **Settings page with customizable options**
* **Filter bar width and alignment controls**
* **Color pickers for styling**
* Works with ANY WordPress theme

= Shortcode =

Use `[lost_found_animals]` to display animals on any page or post.

= Shortcode Parameters =

* `limit` - Number of animals (default: from Settings, -1 for all)
* `status` - Filter by status
* `columns` - Grid columns 1-4 (default: from Settings)
* `show_filters` - Show filter bar (default: from Settings)

= Examples =

`[lost_found_animals limit="8" columns="4"]`
`[lost_found_animals status="Found" show_filters="false"]`

== Installation ==

1. Upload the plugin files to `/wp-content/plugins/lost-found-animal` directory
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Go to 'Lost & Found Animals' > 'Settings' to configure options
4. Use shortcode `[lost_found_animals]` on any page
5. Go to Settings > Permalinks and click Save Changes

== Frequently Asked Questions ==

= The filter bar doesn't display correctly? =

Go to Settings and adjust the Filter Bar Width. Use "Compact" or "Medium" for better display. The plugin uses !important CSS rules to ensure proper display regardless of theme.

= How do I change the filter bar size and position? =

Go to Lost & Found Animals > Settings:
- Filter Bar Width: Compact (520px), Medium (720px), Large (920px), or Full Width
- Filter Bar Alignment: Left, Center, or Right

= How do I change colors? =

Go to Lost & Found Animals > Settings and use the color pickers.

== Screenshots ==

1. Animal grid display on frontend
2. Single animal page with gallery
3. Settings page with width and alignment options
4. Filter bar in compact mode

== Changelog ==

= 1.0.5 =
* Fixed: Filter bar now displays as single horizontal line (theme-independent)
* New: Filter Bar Width setting (Compact/Medium/Large/Full)
* New: Filter Bar Alignment setting (Left/Center/Right)
* Improved: CSS uses !important to override theme styles
* Improved: Better responsive design on mobile

= 1.0.4 =
* New: Settings page under Lost & Found Animals menu
* New: Configurable grid columns (1-4)
* New: Configurable animals limit
* New: Show/hide filters option
* New: Color picker for filter bar background
* New: Color picker for Reset button

= 1.0.3 =
* Security: Added direct file access protection

= 1.0.2 =
* Fixed: Removed deprecated load_plugin_textdomain()
* Updated: Tested up to WordPress 6.9

= 1.0.1 =
* Changed: Switched to Classic Editor for Animal post type

= 1.0.0 =
* Initial release

== Upgrade Notice ==

= 1.0.5 =
Filter bar fix! Now displays correctly regardless of theme. New width and alignment options.
