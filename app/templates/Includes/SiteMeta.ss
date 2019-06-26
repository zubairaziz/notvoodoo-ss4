<% base_tag %>

<meta http-equiv="x-ua-compatible" content="ie=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<link rel="apple-touch-icon" sizes="180x180" href="/resources/$ThemeDir/client/dist/favicon/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="/resources/$ThemeDir/client/dist/favicon/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="/resources/$ThemeDir/client/dist/favicon/favicon-16x16.png">
<link rel="manifest" href="/resources/$ThemeDir/client/dist/favicon/site.webmanifest">
<link rel="mask-icon" href="/resources/$ThemeDir/client/dist/favicon/safari-pinned-tab.svg" color="#0081c3">
<meta name="msapplication-TileColor" content="#00a300">
<meta name="theme-color" content="#ffffff">

$MetaTags(false)

<% if ObjectMetaTags %>
  <title>$ObjectMetaTags.MetaTitle | $SiteConfig.Title</title>

  <% if ObjectMetaTags.MetaDescription %>
    <meta name="description" content="$ObjectMetaTags.MetaDescription">
  <% end_if %>
<% else %>
  <title><% if MetaTitle %>$MetaTitle<% else %>$Title<% end_if %> | $SiteConfig.Title</title>
<% end_if %>

<% if SiteConfig.SocialSharePhoto.Exists %>
  <meta property="og:image" content="$SiteConfig.SocialSharePhoto.AbsoluteURL">
<% end_if %>

<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Organization",
  "name": "$SiteConfig.Title",
  "url": "https://ican.family",
  "sameAs": [
    <% loop SiteConfig.SocialMediaList %>"$URL"<% if not Last %>,<% end_if %><% end_loop %>
  ]
}
</script>
