=== tibit — pocket change for the internet === 
Contributors: tibit
Donate link: https://tib.me/?PAD=mytibs9YhLYtrVhQkmTdbDS51H54WyrxTx&SUB=wordpress-plugin-donate&TIB=tibit.com
Tags: micropayments, microdonations, monetisation, monetization, fundraising, tips, tipping, tibs, revenue, bitcoin
Requires at least: 3.6
Tested up to: 4.5.2
Stable tag: 1.6.5
License: GPL3 
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Collect tips and micropayments for your content. Put customisable ‘tib’ buttons across your site, simply and quickly, with no sign-up required!


== Description ==

= tibs =

**tibs** are a new and original way to monetise your site by collecting ‘pocket change’ size microdonations and payments.  

You can receive tibs as:

*    tips and gestures of appreciation from your visitors, 
*    payments to unlock content, 
*    fundraising for charities or other worthy causes you support

[youtube http://www.youtube.com/watch?v=rUQ_eK6DTys]


= Using our WordPress plugin =

**No signup** or registration whatsoever is required to collect micropayments and/or microdonations through tibit.  Simply configure the plugin with your bitcoin address - getting one is easy!  Links to get one in just a couple of minutes, and more information, can be found at our [collect tibs page](https://tibit.com/wordpress/collect-tibs/).

Our WordPress plugin lets you quickly add **customisable** tib buttons across your site - using shortcodes or as a widget.  Buttons are interactive and acknowledge the visitors tib, and are available with or without a **counter**, which can be site-wide, widget-specific, or post-specific.


= How tibbing works =

Users — we call them ‘tibbers’ — pre-set a personal tib amount in GBP, EUR or USD.  They choose a value small enough that they can be spent ‘without a second’s thought’, but large enough to be meaningful when tipping. 

tibs are purchased in bundles of 10 or more, using Visa or MasterCard.  When a tibber comes to your site, giving you a tib is just a simple click.  They do not need to have, or know anything about, bitcoin.

To see it working, click a link for a site that has already implemented tibbing below.  Every new tibber gets two free tibs, so it won’t cost you anything to try.  You will need to get a tibit account to spend tibs, but you can register using a social media account in two clicks.

*    [Haggerston Times](http://www.haggerston-times.com/)  (basic WordPress implementation)
*    [NotePad++](https://notepad-plus-plus.org/donate/donate-action.html) (native HTML)
*    [tibit’s homepage](https://www.tibit.com/) (tibs are sent to charity)

It’s easier and quicker than throwing a few coins into a busker’s hat, and tibs can be spent anywhere there is a tib button (or link).  Supporting the sites and content a user appreciates becomes an enjoyable and frictionless experience.  It’s monetization that makes the payer happy!

To get a more complete feel for tibit and tibbing, please visit our interactive [demo for blogging and journalism](https://tibit.com/wordpress/demo-site/?interest=jb) 

tibit gets just £0.02 of each tib spent, and forwards the balance to your bitcoin address, typically every few days.


== Installation ==

1.    Install and activate the tibit plugin from the Plugins menu in your WordPress dashboard.

1.    On the left-hand side of the dashboard, navigate to Settings ⇨ tibit

1.    Enter your bitcoin address on the first tab and click save changes.  You can also set how many days the site should acknowledge a user’s tib.  For this duration, the user cannot re-tib the same post, but can still tib other posts if they wish.

1.    Alternatively, you may set an 'Assignee', a charity or other organisation you want to direct your tibs toward.
To do so, you will need a compatible URL for your chosen assignee. For more information on obtaining such a URL, see
[our "collect tibs" page](https://tibit.com/tib-charity/) or e-mail us at [support@tibit.com](mailto:support@tibit.com).

1.    Under the "Posts" section, you can customise functionality relating to attaching tib buttons to your posts. You
 can turn this on/off in the Header/Footer of your posts, change the button style and edit the button caption to your
  liking.

1. Under "Widgets", you can change the button style of posts in any Widgets you choose to add to your site. To add
one of these widgets, Navigate to Dashboard ⇨ Appearance ⇨ Widgets.  Drag the new tibit widget from "Available Widgets" to one of your sidebar, header, or footer widget areas, and set the widget header text you want to be displayed.  You can also specify a background shade.

1.     Under the Shortcodes section, you can customise the button displayed when using the [tib] shortcode. By
adding `[tib]` to your posts, pages, etc you can add a tib button almost anywhere on your site.

There is also a (slightly out of date) [instructional walkthrough video](https://www.youtube.com/watch?v=Nqpkws4YyFM)

If you feel bitcoin is perhaps too complex, just open the ‘Help’ option (from the upper right corner of the tibit
plugin settings page) and follow the simple instructions to generate a bitcoin address.  You can start collecting tibs securely straight away and get to grips with bitcoin later on.

tibit also has a demo mode.  You can experiment with the plugin and the tibit service without any actual money involved. For more information on this feature, see the ‘demo mode’ tab on the left of the help available from the tibit settings page.

We would be very grateful to hear about your experiences installing and configuring the plugin at [**feedback@tibit.com**](mailto:feedback@tibit.com)


== Frequently Asked Questions ==

= what is tibit?

tibit is a platform that enables users to send microdonations and micropayments to sites publishing a tib button alongside their content or service.  Unlike other micropayment offerings, tibit is designed so that these payments can be made 'without a second’s thought'.

In time, this will result in your blog receiving many tiny donations if your site visitors are appreciative, rather than hardly any with other approaches to collecting donations.

= what is a tib?

A tib is a user-specific pre-set value that is sent to sites when the user confirms the payment.

= it sounds very complicated

It's really not.  We suggest trying it out at one of the sites in the plugin description.  We recommend using a social
media login for the most frictionless experience.  Full instructions are available from the plugin settings page.  Getting a bitcoin address is probably much more straightforward than you imagine — and you can start securely collecting tibs to a bitcoin address, and learn more about bitcoin once you have collected a useful number of tibs.

= what is a subreference?

A "subreference" is the identifier we use to distinguish different tib buttons on your site. You'll see this term while setting a widget, where you'll see a "subreference" field, defaulted to "WP_Widget". Any buttons with this same subreference will acknowledge one tib for each button, while incrementing the attached counters, if present. Conversely, two widgets with separate button subreferences will acknowledge tibs separately,
with different counters.

In the case of shortcode buttons, the subreference is set based on the ID of the post or page they're found within. This can be overridden using a shortcode parameter, like so:

`[tib SUB="my_subref"]`

= how do the counters get set

Every time someone confirms a tib to some content of yours, we send back a token via the user’s browser, which is collected and processed by the Plugin. This token includes the total accumulated tibs for the unique combination of bitcoin address and subreference.

= don’t you mean “without a second thought”

That also ;-)


== Screenshots ==

1.    The tib button in action. In this example, the buttons apply on a per-article basis, meaning that users are tibbing individual articles.

2.    When a tib button is clicked, the tibit application opens in a new popup window or tab.  If the user is an existing user (‘tibber’) with a balance of unspent tibs, they are taken directly to the tib confirmation stage. 

3.    After the user confirms the tib, the popup window is closed.  Any paid-for content is revealed, and the tib button is replaced by a static acknowledgement of the tib being received. 

4.    Upon navigating to Settings -> tibit you will be greeted with the following. Here, you can specify your Bitcoin payment address, as well as how long you want your tibs to be acknowledged for.

5.    Navigating to the "Customise Button Appearance" tab will give you the following. Here, you can customise the size, colour, and shape of your tib buttons. Simply click the button you wish to use, drag the slider to scale it, or click the colour picker(s) to customise.

6.    Aside from the provided shortcodes, you may also include the tibit widget in your WordPress sidebars, headers, footers, etc. Simply drag and drop from Available Widgets, and click the widget to customise. Here, you may set the widget's heading, intro text, sub-reference and background colour.


== Changelog ==

= 1.6.5 =

* Fix for glitch with defaulting of PAD/ASN attempting to access empty array items

= 1.6.4 =

* Some small styling tweaks + changes relating to post footer/headers on admin panel

= 1.6.3 =

* Added advanced functionality to only show post footer/headers on single posts

= 1.6.2 =

* Fix widget background colour

= 1.6.1 =

* Small fix for users on earlier versions of PHP
* (CHANGES FROM 1.6.0 INDICATED FOR CLARITY:)
* Added default button styles, and selectors, for shortcode
* Functionality to append buttons before/after posts - includes HTML caption specifiable from wp-admin
* Button Style selector for Widget added
* Fixed issue with counters when using ASN in Widget
* Button Height Selector reworked from slider to number field
* Seperate Button Height Selector for Shortcode and Posts
* Default button heights for each button style added - will be reset to on button change
* Some settings moved to "Advanced Settings"
* Simplified Colour Selector added
* Some Palette colours added to advanced  colour selector
* Default tib acknowledgement duration changed to 1
* Button preview added to settings

= 1.6.0 =

* Added default button styles, and selectors, for shortcode
* Functionality to append buttons before/after posts - includes HTML caption specifiable from wp-admin
* Button Style selector for Widget added
* Fixed issue with counters when using ASN in Widget
* Button Height Selector reworked from slider to number field
* Seperate Button Height Selector for Shortcode and Posts
* Default button heights for each button style added - will be reset to on button change
* Some settings moved to "Advanced Settings"
* Simplified Colour Selector added
* Some Palette colours added to advanced  colour selector
* Default tib acknowledgement duration changed to 1
* Button preview added to settings

= 1.5.2 =

* Fixed issue with BTN reverting to null when updating from 1.5 to 1.5.1

= 1.5.1 =

* Fixed issue with tib counters not updating immediately after a tib.

= 1.5 =

* Integrated new tib.js library to handle tib buttons and tibbing
* Reworked shortcodes to consolidate all shortcodes into single [tib] shortcode
* New parameters for shortcodes, in line with tib.js parameters
* Added support for tib assignees
* Reworked tib callback to work with tib.js library, should result in more accurate numbers on the "List tib counts" tab

= 1.4.6 =

* Updated README.txt, screenshots, and other information on WordPress Plugin Directory
* Fix for issue wherein jQuery was being included in a way that conflicted with certain other plugins.
* Fix for issue wherein generation of Biteasy URL was failing to detect non-testnet addresses while showing balance.
* Tweaks to SVG alignment within tib_post and widget button contexts.
* SVG buttons are now scaled by directly editing height rather than transform: scale
* Updated in-plugin settings
* Tidied up unused assets in plugin folder

= 1.4.5 =

* Updated README.txt and WordPress Plugin Directory information.
* Fix to issue with Plugin Help link on the settings page.

= 1.4.4 =

* Bugfix for issue where BubbleButton class wasn't being appropriately included.

= 1.4.3 =

* Bugfix for issue where __autoload function would cause errors on certain configurations.

= 1.4.2 =

* Fixes to defaulting of values when not set (e.g. when updating from earlier versions) - should now recognise version changes and attempt to default values appropriately.
* Tested up to WordPress 4.3.1
* Default button is now "SideSocialButton" and default colours are now tibit blue/green

= 1.4.1 =

* Some fixes to the display of the test mode icon on buttons.
* Reworked layout and styling for button customisation and selection.
* Ability to customise scale of buttons added.
* Expanded colour picker functionality - now uses built in wordpress colour picker for both primary and secondary colours, and shows/hides secondary colour picker for buttons without a secondary colour picker.
* Button customisation added to its own tab.
* Alignment tweaks to SVG elements within buttons.
* Fixed referencing to some renamed functions.
* PHP version tested to 5.3.
* Buttons that have been tibbed now redirect the user to their profile rather than attempting to resend the tib.

= 1.4 =

* additional button configuration
* bugifx : tib recognition for posts on different pages
* PHP version tested to 5.2.6
* name changes from tibdit to tibit

= 1.3.1 =

* changes to versioning on js / css for cachebusting

= 1.3 =

* Tib post feature added
* Custom colour feature added
* Bugs fixed

= 1.2.36 =

* Expanded the help and information and moved it into the standard WordPress context help system
* Removed 'beta' indicator
* Removed bitcoin address from widget settings - all widgets now use the bitcoin address in the plugin settings
* Added customisation of widget background tint


= 1.2.35 =

* README tweaks
* screenshots added
* versioning added to javascript resources for browser cache management
* checked under WordPress 1.4

= 1.2.34 =

* fixed tooltips
* CSS improvements
* svn glitch

= 1.2.32 =

* Hopefully removed a .js conflict when certain scripts are combined together (and minified) by an unrelated WP plugin.

= 1.2.31 =

* Improved this README file.
* Fixed an incompatibility with earlier versions of PHP.

= 1.2.30 =

* Fixed a bug with plugin options not saving
* Significantly increased the amount of bitcoin address validation.
* Prevented setting save with invalid bitcoin address
* Further improved the CSS to avoid collisions with themes or other plugins.
* Added beta icon to widget and settings page

= 1.2.22 =

* Added .bd CSS class to avoid style collisions with themes or other plugins

= 1.2.21 =

* Fixed tooltip glitch

= 1.2.20 =

* First version uploaded to wordpress.org

