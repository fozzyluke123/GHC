=== Golf Handicap Calculator ===

Contributors: fozzyluke123, hammerofpompey
Tags: golf, handicap, calculator, score, card, congu
Requires at least: 3.0.1
Tested up to: 3.9.1
Stable tag: trunk,
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Works out the handicap for a user according to the UK CONGU regulations using a form

== Description ==

Works out the handicap for a user according to the UK CONGU regulations using a form generated with a shortcode ([ghc_form]). Will then update the handicap after the initial one is worked out every time the user enters a new card. This is then displayed in a table of all the members registered on the site (again with a shortcode([ghc_display_users])).Admin can update the css from the settings panel and manually change a users handicap from their profile pages. You can also display the current users handicap with the shortcode [ghc_user_details].

== Installation ==

1. Upload `golf-handicap-calculator.zip` to the `/wp-content/plugins/` directory
2. Activate the plugin through the \'Plugins\' menu in WordPress
3. Customise the css in the GHC settings page under settings in your admin area.
4. Add the main form to a page using the shortcode [ghc_form]
5. You can then later add the overall table for all members with a handicap on the site with [ghc_display_users]
6. Add the current users handicap with the shortcode [ghc_user_details]
7. Manually edit any handicaps from the the users profile page if you are an admin.


== Changelog ==

= 1.0.0 =
* Plugin created and uploaded to wordpress plugins directory
