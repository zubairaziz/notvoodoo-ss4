<!doctype html>
<html lang="$ContentLocale">
<head>
    <% include SiteMeta %>

    <link rel="stylesheet" href="https://use.typekit.net/ogp5crq.css">

    $SiteCSS

    <% include GoogleTagManager GoogleID=$SiteConfig.GoogleID %>
</head>

<body class="$BodyClasses">
    <% include GoogleTagManager NoScript="true", GoogleID=$SiteConfig.GoogleID %>

    <% include SiteHeader %>

    <main class="site-wrapper" role="main">
        $Layout
    </main>

    <% include SiteFooter %>

    $SiteJS
</body>
</html>
