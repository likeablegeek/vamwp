=== Plugin Name ===
Contributors: adanesh
Tags: vamwp, vam, virtualairlines, aviation
Requires at least: 4.9.5
Tested up to: 4.9.5
Stable tag: trunk
License: Apache License, Version 2.0
License URI: http://www.apache.org/licenses/LICENSE-2.0

VAMwp is a Wordpress plugin that provides integration with a Virtual Airlines Manager (VAM) installation on the same server.

== Description ==

VAMwp is a Wordpress plugin that provides integration with a Virtual Airlines Manager (http://virtualairlinesmanager.net/)
installation on the same server. VVAMwp 1.0.0 is the current version of the plugin which supports VAM 2.6.2.

VAMwp provides a set of widgets which can be included in any Wordpress site using any theme to create a rich Wordpress-Based
web site for a virtual airline. The roadmap is to allow complete management of the VAM airline from within Wordpress without
the need for end-user visitors or pilots to access the VAM administration portal -- which would only be needed for managing
the airline itself by airline staff.

At this time, the widgets provided allow the creation of a visitor-facing web site presenting a rich set of widgets to
present pilot rosters, fleet details, hubs, and flights. These widgets can be combined in many different combinations in
most Wordpress sites.

Future releases will provide widgets to allow pilots to book flights, file PIREPs and more.

VAMwp was originally built for the launch of the new Finnair Virtual airline (http://www.finnairvirtual.fi/) but is now being
packaged and released to the wider VAM and Wordpress community.

The plugin is also available on GitHub for download as unpackaged source code at https://github.com/likeablegeek/vamwp.
Documentation of VAMwp is available on GitHub in the project's [Wiki](https://github.com/likeablegeek/vamwp/wiki).

It is worth noting that VAMwp is a work-in-progress. As such it has some limitations:

1. The current version presumes Wordpress and VAM are installed on the same server in the same virtual host
(i.e. under the same base url such as http://www.mydomain.com/). The typical installation pattern is that the
VAM installation directory would be at /vam/ and the Wordpress site would occupy the root directory of the
site.
2. To fully integrate the user experience with VAM currently requires the creation of a set of rewrite rules
for mod_rewrite (if using Apache as an HTTP server) or equivalent for nginx or other HTTP servers. This is to
allow links from various widgets to redirect away from the back-end VAM portal (if desired). This is explained
with an example in the documentation but is not required to use the plugin.
3. There is an underlying assumption that users of the plugin fully understand how to use VAM and their VAM
installation. The plugin and its documentation in no way simplifies or supports the installation of VAM and
the configuration of a virtual airline based on the VAM platform. Users needing support to install, configure
and manage VAM should refer to the VAM documentation and the [VAM help and support forums](http://virtualairlinesmanager.net/foro//index.php).

== Installation ==

Basic installation is straightforward:

1. Install the plugin by installing through Wordpress itself from the Wordpress plugin repository or
upload the plugin contents to a folder called `vamwp` in `/wp-content-plugins`.
2. In the Wordpress administration interface visit Settings > VAMwp to configure the following settings:
the URL path to your VAM instance (typically `/vam/`), your VAM MySQL server hostname, database name,
username and password to allow the plugin to access your VAM database.
3. Place and use VAMwp widgets in your site as with any other Wordpress widgets.

More detailed instructions can be found in the [project documentation](https://github.com/likeablegeek/vamwp/wiki).

== Frequently Asked Questions ==

= Does VAMwp replace VAM? =

No, VAMwp integrates with VAM. A full VAM insallation is needed -- it must be configured and managed as with any
other VAM-based virtual airline.

= Can I change the appearance of the VAMwp widgets? =

Yes and no. In theory, VAMwp widgets can be deployed with any theme and will inherit many of the visual aspects
of those themes. However, the exact content of widgets cannot be changed or managed at this time. However,
internationalisation of widgets labels, etc can be managed through the Polylang plugin.

= Can I internationalise the VAMwp widgets? =

Yes. VAMwp has been built with internationalisation of widget labels, titles, etc in mind. However, no translations
are provided except for the default English. Additionally, you can use tools like Polylang or WPML to fully manage
a multi-lingual site if you need.

== Screenshots ==

********

1. A sample web site built using VAMwp.

== Changelog ==

= 0.1.0 =
* First public release of VAMwp.

== Upgrade Notice ==

= 0.1.0 =
First public releast of VAMwp.
