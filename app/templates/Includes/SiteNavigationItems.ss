<% loop Children %>
  <li class="$LinkingMode <% if IsHomePage %>is-home<% end_if %>">
    <a href="$Link" class="$LinkingMode" title="$MenuTitle" role="menuitem" <% if AsExternalRedirector %>target="_blank"<% end_if %>>$AssetIcon('chevron') <span>$MenuTitle</span></a>

    <% if Children %>
      <ul class="site-nav-items site-nav-items__submenu">
        <% include SiteNavigationItems %>
      </ul>

      <button class="site-nav__next">$AssetIcon('more-dots') More</button>
    <% end_if %>
  </li>
<% end_loop %>
