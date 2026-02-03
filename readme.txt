=== Lost & Found Animal ===
Contributors: deimos30
Tags: lost, found, animals, dogs, cats, pets, shelter, rescue
Requires at least: 5.0
Tested up to: 6.4
Stable tag: 1.0.4
License: GPLv2 or later
Author: Wojtek Kobylecki / Bella Design Studio

Manage lost and found animals with photo gallery, filtering, and shortcode display.

== Description ==

A WordPress plugin for kennels, shelters, and rescue organizations to manage and display lost and found animals.

**Features:**

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

**Shortcode:**

`[lost_found_animals]`

Parameters:
* `limit` - Number of animals (default: -1 for all)
* `status` - Filter by status
* `columns` - Grid columns 1-4 (default: 4)
* `show_filters` - Show filter bar (default: true)

Example: `[lost_found_animals limit="8" columns="4"]`

== Installation ==

1. Upload plugin folder to `/wp-content/plugins/`
2. Activate through 'Plugins' menu
3. Go to 'Lost & Found Animals' to add animals
4. Use shortcode `[lost_found_animals]` on any page
5. Go to Settings > Permalinks and click Save Changes

== Changelog ==

= 1.0.4 =
* Added Settings page (Lost & Found Animals > Settings)
* Configurable grid columns, animal limit, show/hide filters
* Filter bar color picker
* Compact inline filter bar design
* Shortcode defaults now read from Settings (attributes still override)

= 1.0.1 =
* Changed to Classic Editor (disabled Gutenberg for Animal post type)
* Better meta box layout
* Author info updated

= 1.0.0 =
* Initial release
