<header class="$SiteHeaderClasses">
  <div class="grid-container">
    <div class="site-header__inner">
      <a href="$BaseURL" class="site-header-logo">
        $AssetInline('images/site-logo.svg')
        $SiteConfig.Title
      </a>

      <button class="site-menu-btn js-site-nav-open">Menu</button>

      <% include SiteNavigation %>
    </div>
  </div>
</header>
