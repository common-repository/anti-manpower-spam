=== Plugin Name ===
Contributors: leninlee
Donate link: http://sinolog.it
Tags: comments, spam
Requires at least: 2.0
Tested up to: 3.0.5
Stable tag: 0.2

Ban some specified people from commenting, according to IPs, Names, URLs, Emails and keywords

== Description ==

When I woke up this morning, I found that there were some spam comments left by a guy. It's clear that they were saved manually.

There are always some guys keeping submitting spam comments in my blog, since they do it manually, my auto anti-spam plugins can't prevent them.

And I finally got tired of deleting manual spam comments, so I spent this sunday on this plugin, which is also my first plugin for wordpress.

== Installation ==

1. Upload `anti-artificial-spam` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Modify 'AMS Settings' in the admin panel, one item per line

== Frequently Asked Questions ==

= Can AMS get rid of spam bots ? =

No, AMS is just a **artificial** anti-spam tool, which uses the most stupid and ancient way to get rid of **artificial** spam.

== TODO ==

1. Allow users to decide whether to ban spam comments directly or move them to the queue waiting to be audited.
1. Allow users to specify the messages which are sent to commenters when their comments are banned.
1. Validate URLs intelligently.
1. i18n
1. Save comments banning log to database.

== Changelog ==

= 0.2 =
* 2011-02-23 Wednesday 22:24:12
  1. Rename anti-manpower-spam to anti-artificial-spam.
  2. Don't just test if the URL is in the banned ones, but also test if it is a substring of one of them.
  3. Optimize the speed of matching.

= 0.1 =
* 2009-12-13 Sunday 21:47:21
* Initial edition.
  1. Allow users to specify lists of comment authors, emails, urls, IP addresses and keywords.
  1. Use the above lists to filter every comment submitted.
  1. Allow users to decide whether to ignore cases while validating comments.
  1. All matched comments will be banned directly and show a notice to the commenter.
