<!doctype html>
<html lang="en">
<head>

    <% base_tag %>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="format-detection" content="telephone=no">
    $MetaTags(false)
    <title><% if MetaTitle %>$MetaTitle<% else %>$Title<% end_if %> | $SiteConfig.Title</title>

    <link property="stylesheet" rel="stylesheet" href="$ThemeDir/dist/styles/main.css" type="text/css" media="all"/>

</head>
<body>

    $Layout

    <script src="$ThemeDir/dist/js/index.js"></script>

</body>
</html>
