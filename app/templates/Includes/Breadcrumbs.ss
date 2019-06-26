<% if Pages %>
  <ul class="page-breadcrumbs">
    <% loop $Pages %><% if $Last %><li>$MenuTitle.XML</li><% else %><li><a href="$Link">$MenuTitle.XML</a></li><li class="delimiter">$Up.Delimiter</li><% end_if %><% end_loop %>
  </ul>
<% end_if %>
