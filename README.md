Sitemap Verify
===

A tool in PHP to verify whether your sitemap.xml file is healthy and happy.

How?
---

Pull down the repo. Install the dependencies with Composer, like this:

	composer install

Run the thing:

	php ./sitemap.php sitemap:verify http://example.com --spider

You'll get some status info and then a list of links which are broken in some way.

Why Doesn't This Do X?
---

Because it doesn't. :-) Pull requests are very happily considered.
