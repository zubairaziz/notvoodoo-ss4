<nav class="site-nav">
  <div class="site-search site-search--desktop">
    <form action="/search" class="site-search__form">
      <div class="site-search-input-group">
        <input type="text" name="q" placeholder="Search Our Site..." aria-label="Search Our Site...">
        <button type="submit" aria-label="Search">$AssetIcon('search')</button>
      </div>
    </form>

    <button class="site-search-close js-site-nav-close" aria-label="Close Search">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>

  <div class="site-nav__header">
    <button class="site-nav__prev" aria-label="Back">$AssetIcon('chevron') Back</button>
    <div class="site-nav__header-title">Menu</div>
    <button class="site-menu-btn-close js-site-nav-close" aria-label="Close Menu">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>

  <div class="site-nav__menu" role="menu">
    <% if SitePrimaryMenu %>
      <div class="site-nav--primary">
        <ul class="site-nav-items">
          <% include SiteNavigationItems Children=$SitePrimaryMenu %>
        </ul>

        <div class="site-nav__mobile-actions">
          <div class="site-search site-search--mobile">
            <form action="/search" class="site-search__form">
              <div class="site-search-input-group">
                <input type="text" name="q" placeholder="Search Our Site..." aria-label="Search Our Site...">
                <button type="submit" aria-label="Search">$AssetIcon('search')</button>
              </div>
            </form>
          </div>

          <div class="site-nav-contact">
            $AssetIcon('phone')

            <div class="site-nav-contact__phone">
              $PhoneLink($SiteConfig.ContactPhoneNumber)
            </div>

            <a href="$GetPageByType('ContactPage').Link" class="site-nav-contact__link">Contact Us</a>
          </div>

          <a href="$GetPageByType('WaysToGivePage').Link" class="btn btn--fill btn--mobile-wtg">Ways To Give</a>
        </div>
      </div>
    <% end_if %>
  </div>

  <div class="site-nav__actions">
    <button type="button" class="site-search-trigger js-site-search-trigger" aria-label="Search">$AssetIcon('search')</button>
  </div>
</nav>
