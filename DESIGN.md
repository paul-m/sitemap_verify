Sitemap Verify
====

A tool to verify the integrity of links on your site.

This tool is assumed to be used as part of a verification test of a site in a continuous-integration/deployment context. It reports pass or fail as an exit code, with a report generated in plaintext for your log-reading pleasure.

sitemap.xml
---

The sitemap file is parsed to find URLs.

URLs are requested.

If the request fails, the URL is bad and we'll report a fail.

--spider
---

When the `--spider` (`-s`) option is present, we scrape the given URL from the sitemap and then request HEAD on all the URLs contained therein.

So if your sitemap has a reference to `http://example.com/example.html`, then we'll load the contents of `example.html`, look for all loadable URLs inside it, and then check those. This includes resources like JavaScript and CSS files.
