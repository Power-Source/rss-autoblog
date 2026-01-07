=== PS RSS AutoBlog ===
Contributors: DerN3rd (NerdService Eimen)
Tags: rss, import, autoblog
Requires at least: 4.9
Tested up to: 6.8.1
Stable tag: 4.1.7
Requires PHP: 7.2
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Dieses Plugin veröffentlicht automatisch Inhalte aus RSS-Feeds in verschiedenen Blogs auf Deiner WordPress Seite oder in Deiner Multisite...

== Description ==

## Möchtest Du Blog-Inhalte von mehreren Webseiten an einem Ort erneut veröffentlichen? Automatisiere das Posten auf Word/ClassicPress und Multisite mithilfe von RSS-Feeds mit Autoblog.

Kein Code erforderlich. Keine komplizierten Anweisungen. Kopiere einfach eine Feed-URL und füge sie ein. Autoblog beginnt dann mit dem Importieren von Inhalten in Deinem Blog.

![autoblog-add-feed-735x470](https://premium.wpmudev.org/wp-content/uploads/2009/08/autoblog-add-feed-735x470.jpg)
    Starte einen Beitrags-Feed, um Inhalte aus mehreren Blogs an einem Ort zu teilen.

### Inhalte von überall

Plane regelmäßige Importe und sorge dafür, dass Dein Thread mit frischen, relevanten Inhalten aus dem gesamten Web gefüllt ist. Wenn Du mehrere Blogs verwaltest, kann Dir Autoblog eine Menge Zeit sparen. Klicke auf „Veröffentlichen“ und Autoblog importiert und veröffentlicht Deinen Beitrag auf den übrigen Webseiten.

### Die Kontrolle, die Du brauchst

Veröffentliche jedes Mal die relevantesten Inhalte in Deinem Blog. Verwende Wort-, Phrasen-, Ausdrucks- und Tag-Filter, um zu steuern, welche Beiträge importiert werden. Lege Tags, Kategorien und benutzerdefinierte Autoreninformationen fest.

![Auto-Blog-Stats-735x470](https://premium.wpmudev.org/wp-content/uploads/2009/08/Auto-Blog-Stats-735x470.jpg)
    Verfolge die Feed-Aktivität von Deinem Dashboard aus mit dem enthaltenen Statistikmodul

![autoblog-linkback](https://premium.wpmudev.org/wp-content/uploads/2009/08/autoblog-linkback.jpg)
    Stelle Autoblog so ein, dass es auf den ursprünglichen Beitrag verlinkt.

### Ehre wem Ehre gebührt

Stelle Autoblog so ein, dass nur ein Auszug aus importierten Beiträgen veröffentlicht wird. Verlinke die Leser zurück zur Originalquelle, um ein größeres Follower-Netzwerk aufzubauen. Back-Links und Cross-Promotion sind eine großartige Möglichkeit, Deine Webseite aufzubauen.

### Tonnenweise Add-ons

Erweitere die Funktion, indem Du eines der 17 enthaltenen Add-ons aktivierst. Verbessere die Feed-Kompatibilität, importiere oder entferne Post-Bilder, verwende das Originalbild, WPML-Unterstützung, automatisches Tweeten, bette Videos aus einem YouTube-Feed ein – alles, was Du brauchst, in einem Plugin.

![autoblog-addons](https://premium.wpmudev.org/wp-content/uploads/2009/08/autoblog-addons.jpg)
    17 enthaltene Add-ons erleichtern die Verwaltung Deines Post-Feeds.

Denke daran: Mit großer Macht geht große Verantwortung einher. Das Teilen von Inhalten sollte immer mit Genehmigung des Autors erfolgen.

[POWERED BY PSOURCE](https://github.com/Power-Source/psource_kategorien/psource-plugins/)

[Projektseite](https://github.com/Power-Source/piestingtal-source-project/rss-autoblog/)
[GitHub](https://github.com/Power-Source/rss-autoblog)

== Mehr PSOURCE ==

= Finde mehr Piestingtal.Source =

Wirf einen Blick in unser [PSOURCE Sortiment](https://github.com/Power-Source/psource_kategorien/psource-plugins/) und hole noch mehr aus Deinem WordPress/ClassicPress!

Halte Dich mit unserem [Newsletter](https://github.com/Power-Source/webmasterservice-n3rdswork-digalize-das-piestingtal/newsletter-management/) über unsere Piestingtal.Source informiert!

== Hilf uns ==

Viele, viele Kaffees konsumieren wir während wir an unseren Plugins und Themes arbeiten.
Wie wärs? Möchtest Du uns mit einer Kaffee-Spende bei der Arbeit an unseren Plugins unterstützen?

= Unterstütze uns =

Mach eine [Spende per Überweisung oder PayPal](https://github.com/Power-Source/spendenaktionen/unterstuetze-unsere-psource-free-werke/) wir Danken Dir!

Halte Dich mit unserem [Newsletter](https://github.com/Power-Source/webmasterservice-n3rdswork-digalize-das-piestingtal/newsletter-management/) über unsere Piestingtal.Source informiert!

== ChangeLog ==

= 4.1.7 =

* Fix Bildimport
* PhP8.2 Fixes
* Neue Links und Seite

= 4.1.6 =

Großes Release Update für PSOURCE

== 4.1.1 ==
Fix youtube add-on
Fix: "Open Links in Popup" add-on - cache now refreshes content when imported pages/posts are modified manually
Fix: Added more years in the feed processing date range

== 4.1 ==
Fix Import Images add-on fail for relative urls

== 4.0.9.9 ==
Fix disable sanitization add-on
Update Author & Link of all addon

== 4.0.9.8 ==
* Fix bug only pull first paragraph

== 4.0.9.7 ==
* Fix can not assign posts to blog in network

== 4.0.9.6 ==
* Fix wrong utf character
* Sort blogs as Alphabetically in feed edit -> Add posts to

== 4.0.9.5 ==
* Fix strip images
* Fix youtube add-on

== 4.0.9.4 ==
* Bring back twitter add-on
* Fix duplicate bug
* Fix bug except by paragraph not working properly
* Update cache images and cache feature images

== 4.0.9.2 ==
* CDN Mime Filter  add-on. Wordpress rejects images without extensions, such as are sent by some CDN services. 
  This add-on checks the files mime type directly and passes it if in the standard Wordpress list of allowed types.
* Fixed problem with testing for CRON access.  
  
== 4.0.9.1 ==
* WPML addon missing closing "}" caused crash if activated.

== 4.0.9 ==
* Added test for proper Cron function on WP and an error message explaining the options to work around the problems.
* Added Regular Expression Match field to allow generic matching of HTML text like /<img / to select only posts with images.

== 4.0.8 ==
* Create a new Add-on "Clean Face" , which Cleans and Patches the Fackbook RSS Feed which doesn't validate in the first place.
* Created a new Add-on "WPML Languages", to properly inform WPML of autoblog posts. Without it it WPML will not display the autoblogged posts.
* Filter to repair Facebook spoofed relative links.
* Fixed the "Any tags" filter. Previously rejected multiple tags.

== 4.0.7 ==
* Fixed Post Formats Addon

== 4.0.6 ==
* Fixed feed "Last Processed" date column to render it with gmt offset
* Fixed original date importing issue
* Fixed strip all images addon
* Fixed images import which contains spaces in the name
* Implemented ability to import featured image from enclosure
* Improved post type rendering at all feeds screen

== 4.0.5 ==
* Improved performance of dashboard page
* Improved images addons to skip all invalid images from being imported
* Improved feed items filtering, now the plugin filters them by whole words
* Fixed YouTube import addon issue
* Fixed dashboard chart date range rendering
* Fixed dashboard log cleanup process
* Fixed feed last processing time and next check time rendering
* Implemented ability to filter feed details before processing

== 4.0.4 ==

* Fixed image import bug
* Fixed feed post excerpts settings saving
* Fixed bug with assigning author to posts and images
* Implemented fatal errors catching during feed processing
* Implemented ability to export log records
* Implemented ability to clone a feed
* Implemented ability to select default thumbnail

== 4.0.3 ==

* Fixed Uncategorized taxonomy issue

== 4.0.2 ==

* Added YouTube feeds importer add-on

== 4.0.1 ==

* Added ability to adjust dashboard cache TTL time
* Fixed dashboard styles for feeds with long title
* Fixed taxonomy processing issue

== 4.0.0 ==

* Fixed Append Text To Post add-on issue with multi byte string position detection
* Fixed issue with not shown "Open this link in a new window" check box for new feeds
* Implemented ability to use shortcodes inside appended text
* Implemented add-on which strips all images from a post
* Implemented force feed add-on
* Implemented disable sanitization add-on
* Implemented ability to update posts when duplicate is found
* Implemented ability to create/assign custom taxonomy to custom post type during import
* Implemented new dashboard pivot chart
* Implemented ability to clear log records
* Reworked images import to prevent downloading the same image multiple times
* Reworked featured images importing to prevent double downloading the same images
* Removed debug images add-on