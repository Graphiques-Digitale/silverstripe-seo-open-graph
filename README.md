[![Build Status](https://travis-ci.org/Graphiques-Digitale/silverstripe-seo-open-graph.svg?branch=master)](https://travis-ci.org/Graphiques-Digitale/silverstripe-seo-open-graph) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Graphiques-Digitale/silverstripe-seo-open-graph/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Graphiques-Digitale/silverstripe-seo-open-graph/?branch=master)

## Overview ##

Enables Open Graph metadata on pages.

It is a modular extension for [graphiques-digitale/silverstripe-seo-metadata](https://github.com/Graphiques-Digitale/silverstripe-seo-metadata)

Inspired by: [http://ogp.me][8]

It is intended to be used with it's siblings:
* [`Graphiques-Digitale/silverstripe-seo-icons`](https://github.com/Graphiques-Digitale/silverstripe-seo-icons)
* [`Graphiques-Digitale/silverstripe-seo-facebook-domain-insights`](https://github.com/Graphiques-Digitale/silverstripe-seo-facebook-domain-insights)

These are all optional and fragmented from the alpha version [`SSSEO`](https://github.com/Graphiques-Digitale/SSSEO), which is now redundant.

The whole module collection is based largely on [18 Meta Tags Every Webpage Should Have in 2013][1].

Also, a good overview: [5 tips for SEO with Silverstripe 3][2].

## Installation ##

#### Composer ####

* `composer require graphiques-digitale/silverstripe-seo-open-graph`
* rebuild using `/dev/build/?flush`

#### From ZIP ####

* Place the extracted folder `silverstripe-seo-open-graph-{version}` into `silverstripe-seo-open-graph` in the SilverStripe webroot
* rebuild using `/dev/build/?flush`

## CMS Usage ##

@todo explain usage

## Template Usage ##

Depending on your configuration, the general idea is to replace all header content relating to metadata with `$Metadata()` just below the opening `<head>` tag and `<% base_tag %>` include, e.g.:

```html
<head>
    <% base_tag %>
    $Metadata()
    <!-- further includes ~ viewport, etc. -->
</head>
```

This will output something along the lines of:

```html
<head>
    <base href="http://dev.seo.silverstripe.org/"><!--[if lte IE 6]></base><![endif]-->

    <!-- SEO -->
    <!-- Metadata -->
    <meta charset="UTF-8" />
    <link rel="canonical" href="http://dev.seo.silverstripe.org/" />
    <title>Your Site Name | Home - your tagline here</title>
    <meta name="description" content="Welcome to SilverStripe! This is the default home page. You can edit this page by opening the CMS. You can now access the developer documentation, or begin the tutorials." />
    <!-- Open Graph -->
    <meta property="og:type" content="website" />
    <meta property="og:url" content="http://dev.seo.silverstripe.org/" />
    <meta property="og:site_name" content="Your Site Name" />
    <meta property="og:title" content="Home" />
    <meta property="og:description" content="Welcome to SilverStripe! This is the default home page. You can edit this page by opening the CMS. You can now access the developer documentation, or begin the tutorials." />
    <!-- END SEO -->

    <!-- further includes ~ viewport, etc. -->
</head>
```

## Issue Tracker ##

Issues are tracked on GitHub @ [Issue Tracker](https://github.com/Graphiques-Digitale/silverstripe-seo-open-graph/issues)

## Development and Contribution ##

Please get in touch @ [`hello@graphiquesdigitale.net`](mailto:hello@graphiquesdigitale.net) if you have any extertise in any of these SEO module's areas and would like to help ~ they're a lot to maintain, they should be improved continually as HTML evolves and I'm sure they can generally be improved upon by field experts.

## License ##

BSD-3Clause license

See @ [Why BSD?][12]


![Screenshot](screenshot-1.png)

![Screenshot](screenshot-2.png)


[1]: https://www.iacquire.com/blog/18-meta-tags-every-webpage-should-have-in-2013
[2]: http://www.silverstripe.org/blog/5-tips-for-seo-with-silverstripe-3-/
[3]: http://moz.com/learn/seo/title-tag
[4]: https://github.com/audreyr/favicon-cheat-sheet
[5]: http://www.jonathantneal.com/blog/understand-the-favicon/
[6]: http://blogs.msdn.com/b/ie/archive/2012/06/08/high-quality-visuals-for-pinned-sites-in-windows-8.aspx
[7]: https://developers.facebook.com/docs/platforminsights/domains
[8]: http://ogp.me
[9]: https://dev.twitter.com/cards/overview
[10]: https://developers.google.com/+/web/snippet/
[11]: https://mathiasbynens.be/notes/touch-icons
[12]: https://www.silverstripe.org/blog/why-bsd/
