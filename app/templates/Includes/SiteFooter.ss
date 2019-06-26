<footer class="site-footer">
  <div class="grid-container">
    <div class="site-footer__top-wrapper">
      <div class="site-footer__info">
        <a href="$BaseURL" class="site-footer__logo"><img src="$Asset(images/site-logo-footer.svg)" alt="$SiteConfig.Title"></a>

        <address class="site-footer-contact">
          <div class="site-footer-contact__address">
            $AssetIcon('map-pin')
            $SiteConfig.ContactAddress<br>
            $SiteConfig.ContactCity, $SiteConfig.ContactState $SiteConfig.ContactZip<br>
          </div>

          <div class="site-footer-contact__phone">
            $AssetIcon('phone')
            $PhoneLink($SiteConfig.ContactPhoneNumber)
          </div>
        </address>
      </div>

      <div class="site-footer__social">
        <div class="site-footer-subscribe">
          <div class="site-footer-subscribe__title">Subscribe</div>
          <p>To get updates on our latest news, please enter your email below.</p>

        </div>

        <% if SiteConfig.SocialMediaList.Count %>
          <ul class="social-media-list">
            <% loop SiteConfig.SocialMediaList %>
              <li><a href="$URL" title="$Name" target="_blank" rel="noopener noreferrer">$AssetIcon($Icon) $Name</a></li>
            <% end_loop %>
          </ul>
        <% end_if %>
      </div>
    </div>
  </div>

  <div class="site-footer__bottom">
    <div class="grid-container">
      <div class="site-footer__bottom-wrapper">
        <nav class="site-footer-nav">
          <ul class="site-footer-nav__list">
            <% loop SitePrimaryMenu %>
              <% if not IsHomePage %>
                <li><a href="$Link">$MenuTitle</a></li>
              <% end_if %>
            <% end_loop %>
          </ul>
        </nav>

        <div class="site-footer-legal">
          <div>&copy; $SiteConfig.Title, All rights reserved.</div>
          <div>Alison Frontier, University of Rochester.</div>
        </div>

        <ul class="site-footer-links">
          <% loop SiteFooterMenu %>
            <li><a href="$Link" <% if IsExternalRedirector %>target="_blank"<% end_if %>>$MenuTitle</a></li>
          <% end_loop %>
        </ul>
      </div>
    </div>
  </div>
</footer>
