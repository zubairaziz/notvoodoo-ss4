<article <% if ActiveOverlay %>data-overlay="$ActiveOverlay.Link"<% end_if %>>
<script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>

    <% include TwitterTimeline %>
    <header>
        <div class="home-header-slider slider" data-slider data-slider-fade="true" data-slider-arrows="false" data-slider-dots="false">
        <% loop HomeSlides %>
            <div class="page-header page-header--bg home-header slider__slide">
            <% if Image.Exists %>
                <% with Image %>
                <img srcset="$Me.FocusFill(600, 445).URL 1200w,
                            $Me.FocusFill(2050, 850).URL 2050w"
                    src="$Me.FocusFill(2050, 850).URL"
                    aria-hidden="true"
                    class="page-header__bg-image">
                <% end_with %>
            <% end_if %>

            <div class="grid-container">
                <div class="page-header__inner">
                <div class="page-header__columns">
                    <% if Title %>
                    <h1 class="page-header__title">$TextEmphasize($Title)</h1>
                    <% end_if %>

                    <% if CTAs %>
                    <ul class="page-header__ctas <% if CTAs.Count > 1 %>has-multiple<% end_if %>">
                        <% loop CTAs %>
                        <li class="page-header__nav page-header__nav-action <% if Type = 'Video' %>is-full-width is-video-cta<% end_if %>">
                            <% include CallToAction AriaHidden="true" %>
                        </li>
                        <% end_loop %>
                    </ul>
                    <% end_if %>
                </div>
                </div>
            </div>
            </div>
        <% end_loop %>
        </div>
    </header>

    <aside>
        <div>
            <a
            class="twitter-timeline"
            href="https://twitter.com/Not_Voodoo?ref_src=twsrc%5Etfw"
            data-width="300"
            data-height="900"
            >
                Tweets by Not_Voodoo
            </a>
        </div>
    </aside>

</article>
