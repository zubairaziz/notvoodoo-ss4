<article>
  <% include PageHeader %>

  <div class="grid-container">
    <section class="page-container">
      <div class="page-container__inner">
        <div class="grid-x grid-margin-x">
          <div class="cell tablet-7 large-8 xlarge-9">
            <div class="contact-content">
              <div class="contact-content__inner">
                $Content

                <% include RequiredFieldsMessage %>
              </div>

              $ContactForm
            </div>
          </div>

          <div class="cell tablet-5 large-4 xlarge-3">
            <div class="contact-sidebar">
              <div class="contact-sidebar__logo">
                $AssetInline('images/site-logo.svg')
              </div>

              <dl class="contact-sidebar__details">
                <dt><span>$AssetIcon('map-pin')</span> ICAN</dt>
                <dd>
                  <address>
                    $SiteConfig.ContactAddress<br>
                    $SiteConfig.ContactCity, $SiteConfig.ContactState $SiteConfig.ContactZip<br>
                  </address>
                </dd>

                <dt><span>$AssetIcon('phone')</span> Call</dt>
                <dd>
                  $PhoneLink($SiteConfig.ContactPhoneNumber)
                </dd>
              </dl>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</article>
