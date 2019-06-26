<% if Type = 'Internal' %>
  <a href="$InternalLink.Link<% if InternalLinkAnchorTarget%>$InternalLinkAnchorTarget<% end_if %>" class="$Classes" <% if AriaHidden %>tabindex="-1"<% end_if %>>$Title</a>
<% end_if %>

<% if Type = 'Anchor' %>
  <a href="$AnchorTarget" class="$Classes" data-scroll="$AnchorTarget" <% if AriaHidden %>tabindex="-1"<% end_if %>>$Title</a>
<% end_if %>

<% if Type = 'External' %>
  <a href="$ExternalLink" class="$Classes" target="_blank" <% if AriaHidden %>tabindex="-1"<% end_if %>>$Title</a>
<% end_if %>

<% if Type = Video %>
  <% if ButtonThumbnail.Exists %>
    <a href="$VideoURL" class="$Classes" data-modal="video">$ButtonThumbnail</a>
  <% else %>
    <a href="$VideoURL" class="$Classes" data-modal="video">$AssetIcon('play-button') $Title</a>
  <% end_if %>
<% end_if %>

<% if Type = 'File' %>
  <a href="$File.Link" class="$Classes" target="_blank" <% if AriaHidden %>tabindex="-1"<% end_if %>>$Title</a>
<% end_if %>
